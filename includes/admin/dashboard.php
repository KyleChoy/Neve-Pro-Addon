<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-01-28
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Admin;


use Neve_Pro\Core\Abstract_Module;
use Neve_Pro\Core\Loader;
use Neve_Pro\Core\Settings;
use Neve_Pro\Traits\Core;

/**
 * Class Dashboard
 *
 * @package Neve Pro Addon
 */
class Dashboard {
	use Core;
	/**
	 * Neve Pro plugin name
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The app script handle.
	 *
	 * @var string
	 */
	private $script_handle;

	/**
	 * The app endpoint.
	 *
	 * @var string
	 */
	private $rest_endpoint;

	/**
	 * The app rest server instance.
	 *
	 * @var Rest_Server
	 */
	private $rest_server;

	/**
	 * Dashboard constructor.
	 */
	public function __construct() {
		$this->plugin_name   = apply_filters( 'ti_wl_plugin_name', NEVE_PRO_NAME );
		$this->script_handle = NEVE_PRO_NAMESPACE . '-dashboard-app';
		$this->rest_endpoint = NEVE_PRO_REST_NAMESPACE;
		$this->rest_server   = new Rest_Server( $this->rest_endpoint );
	}

	/**
	 * Initialize the module.
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'ti_about_config_filter', array( $this, 'add_neve_pro_addons_tab' ), 20 );
		add_filter( 'neve_contact_support_filter', array( $this, 'update_contact_support_pro_link' ) );
	}

	/**
	 * Add about page tab list item.
	 *
	 * @param array $config about page config.
	 *
	 * @return array
	 */
	public function add_neve_pro_addons_tab( $config ) {
		$config['custom_tabs']['neve_pro_addons'] = array(
			'title'           => $this->plugin_name,
			'render_callback' => array(
				$this,
				'render_tab_content',
			),
		);

		return $config;
	}

	/**
	 * Render tab content.
	 */
	public function render_tab_content() {
		echo '<div id="neve-pro-dashboard"></div>';
	}

	/**
	 * Enqueue dashboard app.
	 */
	public function enqueue() {
		$screen = get_current_screen();

		if ( ! isset( $screen->id ) || $screen->id !== 'appearance_page_neve-welcome' ) {
			return;
		}

		wp_register_script( $this->script_handle, NEVE_PRO_ASSETS_URL . 'dashboard/bundle/build.js', array(), NEVE_PRO_VERSION, true );

		wp_localize_script( $this->script_handle, 'neveProData', $this->localize_dashboard() );

		wp_enqueue_script( $this->script_handle );
	}

	/**
	 * Localize the dashboard app.
	 *
	 * @return array
	 */
	private function localize_dashboard() {
		return array(
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'options'      => $this->get_options(),
			'strings'      => $this->get_strings(),
			'modules'      => $this->sort_modules( $this->get_modules() ),
			'apiRoot'      => rest_url( $this->rest_endpoint ),
			'license'      => $this->get_license_type(),
			'upgradeLinks' => $this->get_upgrade_links(),
		);
	}

	/**
	 * Get upgrade links.
	 *
	 * @return array
	 */
	private function get_upgrade_links() {
		return array(
			'1' => 'https://themeisle.com/themes/neve/upgrade/',
			'2' => 'https://themeisle.com/themes/neve/upgrade/',
			'3' => 'https://themeisle.com/themes/neve/upgrade/',
		);
	}

	/**
	 * Utility method to sort modules by order key.
	 *
	 * @since   1.0.0
	 * @access  private
	 *
	 * @param array $modules The modules list.
	 *
	 * @return mixed
	 */
	private function sort_modules( $modules ) {
		uasort(
			$modules,
			function ( $item1, $item2 ) {
				if ( ! isset( $item1['order'] ) ) {
					return - 1;
				}
				if ( ! isset( $item2['order'] ) ) {
					return - 1;
				}
				if ( $item1['order'] === $item2['order'] ) {
					return 0;
				}

				return $item1['order'] < $item2['order'] ? - 1 : 1;
			}
		);

		return $modules;
	}

	/**
	 * Get the plugin options
	 *
	 * @return array
	 */
	private function get_options() {
		$settings = new Settings();

		return apply_filters( NEVE_PRO_NAMESPACE . '_dashboard_settings', $settings->get_all() );
	}

	/**
	 * Get the dashboard strings.
	 *
	 * @return array
	 */
	private function get_strings() {
		return array(
			// translators: %s - plugin name
			'title'       => sprintf( __( '%s Modules', 'neve' ), $this->plugin_name ),
			'subtitle'    => __( 'Enable the modules you want to use with a simple click.', 'neve' ),
			'saveOptions' => __( 'Save Options', 'neve' ),
			'enabled'     => __( 'Enabled', 'neve' ),
			'disabled'    => __( 'Disabled', 'neve' ),
			'actions'     => __( 'Actions Required', 'neve' ),
			'unavailable' => __( 'Unavailable', 'neve' ),
			'upgrade'     => __( 'Upgrade', 'neve' ),
			'purchase'    => __( 'Purchase', 'neve' ),
		);
	}

	/**
	 * Get modules.
	 *
	 * For the unload option use classes from Neve_Pro\Core\Loader
	 *
	 * @return array
	 */
	private function get_modules() {

		$pluggable_modules = Loader::instance()->get_modules();
		$modules           = array();
		if ( ! empty( $pluggable_modules ) ) {
			/**
			 * Iterates over instances of Abstract_Module
			 *
			 * @var Abstract_Module $module A module instance.
			 */
			foreach ( $pluggable_modules as $module ) {
				$modules = array_merge( $modules, $module->get_module_info() );
			}
		}

		/**
		 * White label module
		 */
		$white_label_settings  = get_option( 'ti_white_label_inputs' );
		$white_label_settings  = json_decode( $white_label_settings, true );
		$white_label_is_hidden = $white_label_settings['white_label'];
		if ( $white_label_is_hidden === true && isset( $modules['white_label'] ) ) {
			unset( $modules['white_label'] );
		}

		return apply_filters( 'neve_pro_filter_dashboard_modules', $modules );
	}

	/**
	 * Update the Contact Support link to our Themeisle site
	 *
	 * @return string
	 */
	public function update_contact_support_pro_link() {
		return 'https://themeisle.com/contact/';
	}
}
