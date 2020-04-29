<?php
/**
 * Core traits, shared with other classes.
 *
 * Name:    Neve Pro Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve_Pro
 */

namespace Neve_Pro\Traits;

/**
 * Trait Core
 *
 * @package Neve_Pro\Traits
 */
trait Core {

	/**
	 * License tier map.
	 *
	 * @var array
	 */
	private $tier_map = array(
		1 => array( 1, 2, 7 ),
		2 => array( 3, 4, 8 ),
		3 => array( 5, 6, 9 ),
	);

	/**
	 * Recursive wp_parse_args.
	 * Extends parse args for nested arrays.
	 *
	 * @param array $target  The target array.
	 * @param array $default The defaults array.
	 *
	 * @return array
	 */
	public function rec_wp_parse_args( &$target, $default ) {
		$target  = (array) $target;
		$default = (array) $default;
		$result  = $default;
		foreach ( $target as $key => &$value ) {
			if ( is_array( $value ) && isset( $result[ $key ] ) ) {
				$result[ $key ] = $this->rec_wp_parse_args( $value, $result[ $key ] );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Sanitize the repeater control.
	 *
	 * @param string $value            json value.
	 * @param array  $must_have_fields array of must have fields for repeater.
	 *
	 * @return bool
	 */
	public function sanitize_repeater_json( $value, $must_have_fields = array( 'visibility' ) ) {
		$decoded = json_decode( $value, true );

		if ( ! is_array( $decoded ) ) {
			return false;
		}
		foreach ( $decoded as $item ) {
			if ( ! is_array( $item ) ) {
				return false;
			}

			foreach ( $must_have_fields as $field_key ) {
				if ( ! array_key_exists( $field_key, $item ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get post id of wp_query result for header or footer templates.
	 *
	 * @param string $location  Template location.
	 * @param string $hook_name Hook name.
	 *
	 * @return array|bool
	 */
	public function get_post_at( $location, $hook_name = '' ) {
		$options = array( 'header', 'footer', 'hooks' );
		if ( ! in_array( $location, $options, true ) ) {
			return false;
		}
		$args = array(
			'post_type'      => 'neve_custom_layouts',
			'meta_query'     => array(
				array(
					'key'     => 'custom-layout-options-layout',
					'value'   => $location,
					'compare' => 'LIKE',
				),
			),
			'posts_per_page' => 1,
			'order'          => 'ASC',
			'fields'         => 'ids',
		);

		if ( $location === 'hooks' ) {
			$args['posts_per_page'] = - 1;
		}

		if ( ! empty( $hook_name ) ) {
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][]           = array(
				'key'     => 'custom-layout-options-hook',
				'value'   => $hook_name,
				'compare' => 'LIKE',
			);
		}
		$query = new \WP_Query( $args );
		if ( ! $query->have_posts() ) {
			return false;
		}

		$post_with_priority = array();
		foreach ( $query->posts as $post_id ) {
			$priority = get_post_meta( $post_id, 'custom-layout-options-priority', true );
			if ( $priority === '' ) {
				$priority = 10;
			}
			$post_with_priority[ $post_id ] = $priority;
		}
		asort( $post_with_priority );

		return $post_with_priority;
	}

	/**
	 * License type.
	 *
	 * @return int
	 */
	public function get_license_type() {

		$option_name = basename( dirname( NEVE_PRO_BASEFILE ) );
		$option_name = str_replace( '-', '_', strtolower( trim( $option_name ) ) );
		$status      = get_option( $option_name . '_license_data' );
		if ( $status === false ) {
			return 1;
		}

		if ( ! isset( $status->price_id ) ) {
			return 1;
		}

		// TODO: Handle 'expired'.
		if ( ! isset( $status->license ) && $status->license !== 'valid' ) {
			return 1;
		}

		foreach ( $this->tier_map as $tier_id => $price_ids_array ) {
			if ( in_array( $status->price_id, $price_ids_array, true ) ) {
				return (int) $tier_id;
			}
		}

		return 1;
	}

	/**
	 * Enqueue with RTL support.
	 *
	 * @param string $handle       style handle.
	 * @param string $src          style src.
	 * @param array  $dependencies dependencies.
	 * @param string $version      version.
	 */
	public function rtl_enqueue_style( $handle, $src, $dependencies, $version ) {
		wp_register_style( $handle, $src, $dependencies, $version );
		wp_style_add_data( $handle, 'rtl', 'replace' );
		wp_style_add_data( $handle, 'suffix', '.min' );
		wp_enqueue_style( $handle );
	}
}
