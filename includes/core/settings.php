<?php
/**
 * Settings handler.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-01-28
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Core;

use Neve_Pro\Traits\Core;

/**
 * Class Settings
 *
 * @since   0.0.1
 * @package Neve Pro Addon
 */
final class Settings {
	use Core;

	/**
	 * Default settings schema
	 *
	 * @var array
	 */
	private $settings_schema = array(
		'modules_status'  => array(
			'white_label'         => 'disabled',
			'scroll_to_top'       => 'disabled',
			'woocommerce_booster' => 'enabled',
			'blog_pro'            => 'disabled',
			'custom_layouts'      => 'disabled',
		),
		'modules_options' => array(),
	);

	/**
	 * Option key.
	 *
	 * @var string Option name.
	 */
	private $options_key;

	/**
	 * Holds all options from db.
	 *
	 * @var array All options.
	 */
	private $options;

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		$this->options_key     = NEVE_PRO_NAMESPACE . '_settings';
		$this->settings_schema = apply_filters( NEVE_PRO_NAMESPACE . '_default_settings', $this->settings_schema );

		$options       = get_option( $this->options_key, json_encode( $this->settings_schema ) );
		$options_array = json_decode( $options, true );
		$this->options = $this->rec_wp_parse_args( $options_array, $this->settings_schema );
	}

	/**
	 * Update settings.
	 *
	 * @param array $value Settings value.
	 *
	 * @return mixed
	 */
	public function update( $value ) {
		if ( ! is_array( $value ) ) {
			return new \WP_Error( 500, __( 'An error occurred. Options couldn\'t be saved', 'neve' ) );
		}
		if ( empty( $value ) ) {
			$update = $this->reset();

			return $update;
		}

		foreach ( $value as $option_key => $option_value ) {
			/**
			 * Do disable actions at module disable.
			 */
			$old_value = $this->options['modules_status'][ $option_key ];
			if ( $old_value !== $option_value && $option_value === 'disabled' ) {
				do_action( $option_key . '_disable_actions' );
			}

			/**
			 * Update module status.
			 */
			$this->options['modules_status'][ $option_key ] = $option_value;
		}

		$update = update_option( $this->options_key, json_encode( $this->options ), false );

		return $update;
	}

	/**
	 * Reset options to defaults.
	 *
	 * @return bool Reset action status.
	 */
	public function reset() {
		$update = update_option( $this->options_key, json_encode( $this->settings_schema ) );
		if ( $update ) {
			$this->options = $this->settings_schema;
		}

		return $update;
	}

	/**
	 * Check if module is active.
	 *
	 * @param string $slug The module slug.
	 *
	 * @return bool
	 */
	public function is_module_active( $slug ) {
		$status = $this->get_option( 'modules_status' );

		if ( isset( $status[ $slug ] ) && $status[ $slug ] === 'enabled' ) {
			return true;
		}

		return false;
	}

	/**
	 * Get single option
	 *
	 * @param string $key option key.
	 *
	 * @return bool
	 */
	public function get_option( $key ) {
		if ( ! $this->is_allowed( $key ) ) {
			return false;
		}

		return $this->options[ $key ];
	}

	/**
	 * Check if key is allowed.
	 *
	 * @param string $key Is key allowed or not.
	 *
	 * @return bool Is key allowed or not.
	 */
	private function is_allowed( $key ) {
		return isset( $this->settings_schema[ $key ] );
	}

	/**
	 * Get all options.
	 *
	 * @return array
	 */
	public function get_all() {
		return $this->options;
	}

	/**
	 * Get default module status
	 *
	 * @return mixed
	 */
	public function get_default_settings() {
		return $this->settings_schema['modules_status'];
	}
}
