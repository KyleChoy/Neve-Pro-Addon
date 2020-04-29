<?php
/**
 * Handles rest api endpoints for the addon dashboard.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-01-28
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Admin;

use Neve_Pro\Core\Settings;

/**
 * Class Rest_Server
 *
 * @package Neve Pro Addon
 */
class Rest_Server {

	/**
	 * Rest endpoint root.
	 *
	 * @var string
	 */
	private $endpoint_root;

	/**
	 * Settings handler
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Rest_Server constructor.
	 *
	 * @param string $endpoint_root rest api endpoint root.
	 */
	public function __construct( $endpoint_root ) {
		if ( empty( $endpoint_root ) ) {
			return;
		}
		$this->endpoint_root = $endpoint_root;
		$this->settings      = new Settings();
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Register rest endpoints
	 */
	public function register_endpoints() {
		register_rest_route(
			$this->endpoint_root,
			'/save_options',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_options' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'modules_status' => array(
						'type'              => 'array',
						'default'           => $this->settings->get_default_settings(),
						'sanitize_callback' => array( $this, 'sanitize_options' ),
						'validate_callback' => array( $this, 'validate_options' ),
					),
				),
			)
		);

		register_rest_route(
			$this->endpoint_root,
			'/save_module_settings',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_module_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'neve_pro_typekit_id' => array(
						'type'              => 'string',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),

				),
			)
		);
	}

	/**
	 * Save plugin options
	 *
	 * @param \WP_REST_Request $request the request.
	 *
	 * @return \WP_REST_Response
	 */
	public function save_options( \WP_REST_Request $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_REST_Response(
				array(
					'message' => __( 'You are not allowed to change options.', 'neve' ),
					'success' => false,
				)
			);
		}
		$body   = $request->get_body_params();
		$update = $this->settings->update( $body );

		if ( is_wp_error( $update ) ) {
			return rest_ensure_response( $update );
		}

		return new \WP_REST_Response(
			array(
				'message' => __( 'Options Saved', 'neve' ),
				'success' => true,
			)
		);
	}

	/**
	 * Sanitize callback for Enable/Disable module request.
	 *
	 * @param array $value The value for the setting.
	 *
	 * @return array
	 */
	public function sanitize_options( $value ) {
		$defaults = $this->settings->get_default_settings();

		return wp_parse_args( $value, $defaults );
	}

	/**
	 * Validate a request argument based on details registered to the route.
	 *
	 * @param mixed            $value Value of the 'filter' argument.
	 * @param \WP_REST_Request $request The current request object.
	 * @param string           $param Key of the parameter. In this case it is 'filter'.
	 *
	 * @return boolean
	 */
	public function validate_options( $value, $request, $param ) {
		if ( ! is_array( $value ) ) {
			return false;
		}

		$attributes = $request->get_attributes();
		$args       = $attributes['args'][ $param ];
		$default    = $args['default'];
		$value_keys = array_keys( $value );
		$key_values = array( 'enabled', 'disabled' );
		foreach ( $value_keys as $key ) {
			if ( ! array_key_exists( $key, $default ) || ! in_array( $value[ $key ], $key_values, true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Save module settings.
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response
	 */
	public function save_module_settings( \WP_REST_Request $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_REST_Response(
				array(
					'message' => __( 'You are not allowed to change options.', 'neve' ),
					'success' => false,
				)
			);
		}

		$fields    = $request->get_body_params();
		$module_id = $request->get_param( 'module_id' );
		if ( $module_id === 'typekit_fonts' ) {
			return $this->handle_typekit_fonts( $fields );
		}

		return new \WP_REST_Response(
			array(
				'message' => __( 'Nothing to do.', 'neve' ),
				'success' => true,
			)
		);
	}

	/**
	 * Handle typekit fonts module options.
	 *
	 * @param array $fields Module options.
	 *
	 * @return \WP_REST_Response
	 */
	private function handle_typekit_fonts( $fields ) {
		$kit_id = $fields['neve_pro_typekit_id'];

		$typekit_uri = 'https://typekit.com/api/v1/json/kits/' . $kit_id . '/published';
		$response    = wp_remote_get(
			$typekit_uri,
			array(
				'timeout' => '30',
			)
		);

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			update_option( 'neve_pro_typekit_id', '' );
			update_option( 'neve_pro_typekit_data', json_encode( array() ) );

			return new \WP_REST_Response(
				array(
					'message' => __( 'Invalid typekit id.', 'neve' ),
					'success' => false,
				)
			);
		}

		$typekit_info = array();
		$data         = json_decode( wp_remote_retrieve_body( $response ), true );
		$families     = $data['kit']['families'];

		foreach ( $families as $family ) {

			$family_name = str_replace( ' ', '-', $family['name'] );

			$typekit_info[ $family_name ] = array(
				'family'   => $family_name,
				'fallback' => str_replace( '"', '', $family['css_stack'] ),
				'weights'  => array(),
			);

			foreach ( $family['variations'] as $variation ) {

				$variations = str_split( $variation );
				$weight     = $variations[1] . '00';

				if ( ! in_array( $weight, $typekit_info[ $family_name ]['weights'], true ) ) {
					$typekit_info[ $family_name ]['weights'][] = $weight;
				}
			}

			$typekit_info[ $family_name ]['slug']      = $family['slug'];
			$typekit_info[ $family_name ]['css_names'] = $family['css_names'];
		}

		update_option( 'neve_pro_typekit_data', json_encode( $typekit_info ) );
		update_option( 'neve_pro_typekit_id', $kit_id );

		return new \WP_REST_Response(
			array(
				'message' => __( 'Options Saved', 'neve' ),
				'success' => true,
			)
		);

	}
}
