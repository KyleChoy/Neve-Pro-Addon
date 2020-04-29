<?php
/**
 * Abstract Module Class for Neve Pro Addon Modules.
 *
 * Name:    Neve Pro Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Core;

use Neve_Pro\Traits\Core;

/**
 * Class Abstract_Module
 *
 * @package Neve_Pro\Core
 */
abstract class Abstract_Module implements Module_Interface {
	use Core;

	/**
	 * Holds an instance of Settings class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var Settings $settings
	 */
	public $settings;
	/**
	 * The module slug.
	 * Must be unique.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var string $slug
	 */
	public $slug = 'module-slug';
	/**
	 * The module name.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var string
	 */
	public $name = 'Unnamed Module';
	/**
	 * The module description.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var string
	 */
	public $description = 'An unnamed module.';
	/**
	 * Optional links for frontend tiles.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var array
	 */
	public $links = array();
	/**
	 * Optional documentation links.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var array
	 */
	public $documentation = array();
	/**
	 * Optional order for the module when displayed in frontend.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var int
	 */
	public $order = 0;
	/**
	 * Dependent plugins for the module.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var array
	 */
	public $dependent_plugins = array();
	/**
	 * Type of license.
	 *
	 * @var int
	 */
	public $min_req_license = 1;
	/**
	 * Minimum version of theme that the module requires.
	 *
	 * @var string
	 */
	public $theme_min_version = '2.3.10';
	/**
	 * Module settings form.
	 *
	 * @var array
	 */
	public $settings_form = array();
	/**
	 * Default state for the module.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var bool
	 */
	protected $active = false;

	/**
	 * Abstract_Module constructor.
	 */
	public function __construct() {
		$this->define_module_properties();
		$this->set_active_status();
		add_filter( NEVE_PRO_NAMESPACE . '_default_settings', array( $this, 'append_to_settings_schema' ) );
		add_filter( NEVE_PRO_NAMESPACE . '_dashboard_settings', array( $this, 'add_module_options' ) );
		$this->settings = new Settings();
	}

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string $this->slug        The slug of the module.
	 * @property string $this->name        The pretty name of the module.
	 * @property string $this->description The description of the module.
	 * @property string $this->order       Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active      Optional. Default `false`. The state of the module by default.
	 *
	 * @version 1.0.0
	 */
	abstract public function define_module_properties();

	/**
	 * Set active status depending on the license.
	 *
	 * @return void
	 */
	private function set_active_status() {
		$this->active = apply_filters( 'nv_pro_module_active_' . $this->slug, $this->is_available_for_license() );
	}

	/**
	 * Checks if module is available for current license.
	 *
	 * @return bool
	 */
	private function is_available_for_license() {
		$availability = $this->get_license_type();

		if ( $availability >= $this->min_req_license ) {
			return true;
		}

		return false;
	}

	/**
	 * Add module options.
	 *
	 * @param array $settings Module settings.
	 *
	 * @return mixed
	 */
	public function add_module_options( $settings ) {
		if ( empty( $this->settings_form ) ) {
			return $settings;
		}

		$module_options = array();
		foreach ( $this->settings_form as $field_name => $field_settings ) {
			$field_value                   = get_option( $field_name, '' );
			$module_options[ $field_name ] = $field_value;
		}

		$settings['modules_options'][ $this->slug ] = $module_options;

		return $settings;
	}

	/**
	 * Check theme version before module load.
	 *
	 * @return bool
	 */
	protected function is_min_req_theme_version() {
		if ( version_compare( NEVE_VERSION, $this->theme_min_version ) < 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Method to register slug to default settings_schema.
	 *
	 * @param array $settings_schema The default schema defined in Settings class.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  public
	 */
	public function append_to_settings_schema( $settings_schema ) {
		if ( isset( $settings_schema['modules_status'] ) && is_array( $settings_schema['modules_status'] ) ) {
			$settings_schema['modules_status'][ $this->slug ] = ( $this->active ) ? 'enabled' : 'disabled';
		}

		return $settings_schema;
	}

	/**
	 * Retrieve info about the module.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function get_module_info() {
		$info = array(
			$this->slug => array(
				'nicename'          => $this->name,
				'description'       => $this->description,
				'order'             => $this->order,
				'availabilityLevel' => $this->min_req_license,
				'settingsForm'      => $this->settings_form,
			),
		);

		$info[ $this->slug ]['required_actions'] = $this->check_theme_version();
		if ( $info[ $this->slug ]['required_actions'] === false && is_array( $this->dependent_plugins ) && ! empty( $this->dependent_plugins ) ) {
			$info[ $this->slug ]['required_actions'] = $this->check_dependent_plugins();
		}

		if ( is_array( $this->links ) && ! empty( $this->links ) ) {
			$info[ $this->slug ]['links'] = $this->links;
		}

		if ( is_array( $this->documentation ) && ! empty( $this->documentation ) ) {
			$info[ $this->slug ]['documentation'] = $this->documentation;
		}

		return $info;
	}

	/**
	 * Check if the theme should update.
	 */
	private function check_theme_version() {
		if ( ! $this->is_min_req_theme_version() ) {
			$link = admin_url( 'themes.php' );
			return sprintf(
				'<a href="%1$s" target="_blank"><span class="dashicons dashicons-warning"></span> <span>%2$s</span> </a>',
				esc_url( $link ),
				esc_html__( 'You need to update the theme in order to use this module!', 'neve' )
			);
		}

		return false;
	}

	/**
	 * Check dependent plugins.
	 */
	protected function check_dependent_plugins() {
		if ( empty( $this->dependent_plugins ) ) {
			return false;
		}
		foreach ( $this->dependent_plugins as $slug => $plugin ) {
			if ( ! is_plugin_active( $plugin['path'] ) ) {
				$state = $this->check_plugin_state( $plugin['path'] );
				if ( $state === 'install' ) {
					$link = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $slug,
							),
							admin_url( 'update.php' )
						),
						'install-plugin_' . $slug
					);
				}
				if ( $state === 'activate' ) {
					$link = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'activate',
								'plugin' => $plugin['path'],
							),
							admin_url( 'plugins.php' )
						),
						'activate-plugin_' . $plugin['path']
					);
				}

				$stat_strings = array(
					'install'  => __( 'installing', 'neve' ),
					'activate' => __( 'activating', 'neve' ),
				);

				/* translators: %1$s - plugin install url, %2$s - Required action text */

				return sprintf(
					'<a href="%1$s" target="_blank"><span class="dashicons dashicons-warning"></span> <span>%2$s</span> </a>',
					esc_url( $link ),
					/* translators: %s - plugin to activate */
					sprintf( __( 'The module requires %s plugin.' ), $stat_strings[ $state ] . ' ' . $plugin['name'] )
				);
			}
		}

		return false;
	}

	/**
	 * Check plugin state.
	 *
	 * @param string $plugin_path Plugin path.
	 *
	 * @return bool
	 */
	public function check_plugin_state( $plugin_path ) {
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $plugin_path ) ) {
			return 'activate';
		}

		return 'install';
	}

	/**
	 * Init module function
	 *
	 * @return void
	 */
	public function init() {
		if ( ! $this->is_available_for_license() ) {
			return;
		}

		if ( ! $this->is_min_req_theme_version() ) {
			return;
		}

		if ( ! $this->should_load() ) {
			return;
		}
		$this->run_module();
	}

	/**
	 * Check if module should load
	 *
	 * @return bool
	 */
	abstract function should_load();

	/**
	 * Run module's functions.
	 *
	 * @return void
	 */
	abstract function run_module();
}
