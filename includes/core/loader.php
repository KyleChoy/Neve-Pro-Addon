<?php
/**
 * Main core file.
 *
 * Handles module loading and main hooks.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2018-12-03
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Core;

use Neve\Core\Factory;

/**
 * Class Loader
 *
 * @package Neve_Pro\Core
 */
class Loader {
	/**
	 * Neve_Pro\Loader The single instance of Starter_Plugin.
	 *
	 * @var    object
	 * @access   private
	 * @since    0.0.1
	 */
	private static $_instance = null;

	/**
	 * Modules to load.
	 *
	 * @var array
	 * @access private
	 * @since  0.0.1
	 */
	private $modules = array();
	/**
	 * Holds a list of pluggable modules.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var array
	 */
	private $pluggable_modules = array();

	/**
	 * Loader constructor.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function __construct() {
		$this->declare_modules();
		$this->load_modules();
	}

	/**
	 * Declare the modules that will be loaded.
	 *
	 * @access private
	 * @since  0.0.1
	 */
	private function declare_modules() {
		$this->add_pluggable_modules();
		$core_modules  = apply_filters(
			'neve_pro_main_modules',
			array(
				'Admin\Dashboard',
				'Admin\Starter_Sites',
				'Customizer\Loader',
				'Views\Inline\Injector',
				'Translations\Translations_Manager',
				'Admin\Metabox\Injector',
				'Admin\Custom_Layouts_Cpt',
			)
		);
		$this->modules = array_merge( $this->modules, $core_modules );
	}

	/**
	 * Add pluggable modules.
	 */
	private function add_pluggable_modules() {

		$modules_to_load = array(
			'Modules\Blog_Pro\Module',
			'Modules\Header_Footer_Grid\Module',
			'Modules\Scroll_To_Top\Module',
			'Modules\Woocommerce_Booster\Module',
			'Modules\Elementor_Booster\Module',
			'Modules\White_Label\Module',
			'Modules\Custom_Layouts\Module',
			'Modules\LifterLMS_Booster\Module',
			'Modules\Typekit_Fonts\Module',
		);
		$modules_to_load = apply_filters( 'neve_pro_add_pluggable_modules', $modules_to_load );
		foreach ( $modules_to_load as $module_name ) {
			$class_name = '\Neve_Pro\\' . $module_name;
			if ( ! class_exists( $class_name ) || ! in_array( 'Neve_Pro\Core\Module_Interface', class_implements( $class_name ), true ) ) {
				continue;
			}

			array_push( $this->modules, $module_name );
		}
	}

	/**
	 * Check Features and register them.
	 *
	 * @access  private
	 * @since   0.0.1
	 */
	private function load_modules() {
		$factory = new Factory( $this->modules, '\\Neve_Pro\\' );
		foreach ( $this->modules as $module_name ) {
			$module = $factory->build( $module_name );
			if ( $module !== null ) {
				$module->init();
				if ( in_array( 'Neve_Pro\Core\Module_Interface', class_implements( $module ), true ) ) {
					$this->pluggable_modules[] = $module;
				}
			}
		}
	}

	/**
	 * Retrieve a list of pluggable modules.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return array
	 */
	public function get_modules() {
		return $this->pluggable_modules;
	}

	/**
	 * Main Loader Instance
	 *
	 * @access public
	 * @return Loader Plugin instance.
	 * @since  0.0.1
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'neve' ), NEVE_PRO_VERSION );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'neve' ), NEVE_PRO_VERSION );
	}

}
