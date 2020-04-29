<?php
/**
 * Module Class for Header Footer Grid.
 *
 * Name:    Header Footer Grid Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid;

use Neve_Pro\Admin\Custom_Layouts_Cpt;
use Neve_Pro\Core\Abstract_Module;
use Neve_Pro\Modules\Header_Footer_Grid\Customizer\Conditional_Headers;
use Neve_Pro\Modules\Header_Footer_Grid\Customizer\Custom_Panel;
use Neve_Pro\Modules\Header_Footer_Grid\Customizer\Sticky_Header;
use Neve_Pro\Modules\Header_Footer_Grid\Customizer\Transparent_Header;
use Neve_Pro\Traits\Conditional_Display;
use WP_Customize_Manager;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid
 */
class Module extends Abstract_Module {

	use Conditional_Display;

	/**
	 * Enqueue script flag.
	 *
	 * @var bool
	 */
	static private $should_add_script = false;

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug        The slug of the module.
	 * @property string  $this->name        The pretty name of the module.
	 * @property string  $this->description The description of the module.
	 * @property string  $this->order       Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active      Optional. Default `false`. The state of the module by default.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug          = 'hfg_module';
		$this->name          = __( 'Header Booster', 'neve' );
		$this->description   = __( 'Take the header builder to a new level with new awesome components, sticky and transparent menu, socials, and many more.', 'neve' );
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1057-header-booster-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);
		$this->order         = 1;
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	public function should_load() {
		return $this->settings->is_module_active( $this->slug );
	}

	/**
	 * Run Header Footer Grid Module
	 */
	public function run_module() {
		require_once 'functions.php';
		add_filter( 'hfg_template_locations', array( $this, 'add_module_template_location' ) );
		add_filter( 'hfg_after_builder_header_registered', array( $this, 'after_builder_registered' ), 10, 2 );
		add_filter( 'neve_pro_filter_customizer_modules', array( $this, 'add_customizer_modules' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 999 );
		add_action( 'neve_do_footer', array( $this, 'maybe_dequeue' ), 999 );

		add_filter( 'hfg_theme_support_filter', array( $this, 'add_to_theme_support' ) );
		add_filter( 'neve_register_nav_menus', array( $this, 'register_additional_nav_menus' ) );

		$sticky_header = new Sticky_Header();
		add_filter( 'hfg_header_row_classes', array( $sticky_header, 'header_row_classes' ), 10, 2 );
		add_filter( 'hfg_footer_row_classes', array( $sticky_header, 'footer_row_classes' ), 10, 2 );

		$transparent_header = new Transparent_Header();
		add_filter( 'hfg_header_wrapper_class', array( $transparent_header, 'add_class_to_header_wrapper' ) );
		add_filter( 'nv_header_classes', array( $transparent_header, 'add_class_to_header_wrapper' ) );

		add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
		add_filter( 'hfg_settings_schema', array( $this, 'add_page_header_defaults' ), 100 );

		add_action( 'wp', array( $this, 'replace_theme_mods' ) );
	}

	/**
	 * Add page header to default HFG schema.
	 *
	 * @param array $defaults HFG default schema.
	 *
	 * @return array
	 */
	public function add_page_header_defaults( $defaults ) {
		return array_merge(
			[
				'hfg_page_header_layout' => json_encode(
					[
						'desktop' => [
							'top'    => [],
							'bottom' => [],
						],
						'mobile'  => [
							'top'    => [],
							'bottom' => [],
						],
					]
				),
			],
			$defaults
		);
	}

	/**
	 * Add necessary theme supports.
	 */
	public function add_theme_supports() {
		add_theme_support( 'yoast-seo-breadcrumbs' );
	}

	/**
	 * Add additional navigation locations.
	 *
	 * @param array $nav_menus_to_register List of nav locations to be registered.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  public
	 */
	public function register_additional_nav_menus( $nav_menus_to_register ) {
		$nav_menus_to_register['page-header'] = esc_html__( 'Page Header Menu', 'neve' );

		return $nav_menus_to_register;
	}

	/**
	 * Append to the theme support builders.
	 *
	 * @param array $theme_support The theme support array.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_to_theme_support( $theme_support ) {
		if ( ! empty( $theme_support[0]['builders'] ) ) {
			$theme_support[0]['builders']['Neve_Pro\Modules\Header_Footer_Grid\Builder\Page_Header'] = array(
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Button',
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Button',
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Button',
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Html_Page',
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Html_Page',
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Html_Page',
				'Neve_Pro\Modules\Header_Footer_Grid\Components\Nav',
			);

			$theme_support[0]['builders']['HFG\Core\Builder\Footer'] = array_merge(
				$theme_support[0]['builders']['HFG\Core\Builder\Footer'],
				array(
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Social_Icons',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Payment_Icons',
				)
			);

			$theme_support[0]['builders']['HFG\Core\Builder\Header'] = array_merge(
				$theme_support[0]['builders']['HFG\Core\Builder\Header'],
				array(
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Button',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Button',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Html',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Html',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Logo',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Search',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Primary_Nav',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Social_Icons',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Contact',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Language_Switcher',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Yoast_Breadcrumbs',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Wish_List',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\Menu_Icon',
					'Neve_Pro\Modules\Header_Footer_Grid\Components\My_Account',
				)
			);

			$theme_support[0]['builders']['HFG\Core\Builder\Header'] = apply_filters( 'neve_header_module_loader', $theme_support[0]['builders']['HFG\Core\Builder\Header'] );
		}

		return $theme_support;
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return bool | void
	 */
	public function enqueue() {
		$this->rtl_enqueue_style( $this->slug, NEVE_PRO_INCLUDES_URL . 'modules/header_footer_grid/assets/style.min.css', array(), NEVE_PRO_VERSION );
		if ( neve_is_amp() ) {
			return false;
		}

		wp_enqueue_script( $this->slug, NEVE_PRO_INCLUDES_URL . 'modules/header_footer_grid/assets/js/front-end.js', array(), NEVE_PRO_VERSION, true );
	}

	/**
	 * Maybe dequeue script if needed.
	 */
	public function maybe_dequeue() {
		if ( ! self::$should_add_script ) {
			wp_dequeue_script( $this->slug );
		}
	}

	/**
	 * Add module templates location
	 *
	 * @param array $locations the default templates locations.
	 *
	 * @return array
	 */
	public function add_module_template_location( $locations ) {
		$locations[] = NEVE_PRO_PATH . 'includes/modules/header_footer_grid/templates/';

		return $locations;
	}

	/**
	 * Hooks into customizer loader and adds additional modules to load.
	 *
	 * @param array $modules A list of modules to be loaded.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_customizer_modules( $modules ) {
		array_push( $modules, 'Modules\Header_Footer_Grid\Customizer\Transparent_Header' );
		array_push( $modules, 'Modules\Header_Footer_Grid\Customizer\Conditional_Headers' );
		array_push( $modules, 'Modules\Header_Footer_Grid\Customizer\Sticky_Header' );

		return $modules;
	}

	/**
	 * Alter WP_Customize_Manager object.
	 *
	 * @param WP_Customize_Manager               $wp_customize The instance of the WordPress Manager.
	 * @param \HFG\Core\Builder\Abstract_Builder $builder      The builder object.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function after_builder_registered( WP_Customize_Manager $wp_customize, \HFG\Core\Builder\Abstract_Builder $builder ) {
		$title = ( isset( $builder->title ) && ! empty( $builder->title ) )
			? $builder->title
			: __( 'Header', 'neve' );

		$description = ( isset( $builder->description ) && ! empty( $builder->description ) )
			? $builder->description
			: '';

		$wp_customize->remove_panel( 'hfg_header' );
		$wp_customize->register_panel_type( 'Neve_Pro\Modules\Header_Footer_Grid\Customizer\Custom_Panel' );
		$panel = new Custom_Panel(
			$wp_customize,
			'hfg_header',
			array(
				'priority'       => 25,
				'capability'     => 'edit_theme_options',
				'theme_supports' => 'hfg_support',
				'title'          => $title,
				'description'    => $description,
			)
		);
		$wp_customize->add_panel( $panel );
	}

	/**
	 * Replace theme mods with the ones from the available headers.
	 */
	public function replace_theme_mods() {
		if ( get_theme_mod( 'neve_global_header', true ) ) {
			return;
		}

		$headers = Custom_Layouts_Cpt::get_conditional_headers();

		if ( empty( $headers ) || ! is_array( $headers ) ) {
			return;
		}

		if ( is_customize_preview() ) {
			$current_selection = get_theme_mod( 'neve_header_conditional_selector' );
			if ( ! isset( $current_selection['layout'] ) || $current_selection['layout'] === 'default' ) {
				return;
			}

			return;
		}
		$valid_conditions = [];

		foreach ( $headers as $id => $theme_mods ) {
			if ( ! $this->check_conditions( $id ) ) {
				continue;
			}

			$valid_conditions[] = $id;
		}

		$final_header = $this->get_greatest_priority_rule( $valid_conditions, true );
		if ( ! $final_header ) {
			return;
		}
		$theme_mods = json_decode( $headers[ $final_header ], true );
		if ( ! is_array( $theme_mods ) || empty( $theme_mods ) ) {
			return;
		}
		foreach ( $theme_mods as $mod => $val ) {
			if ( in_array( $mod, Conditional_Headers::JSON_THEME_MODS, true ) ) {
				$val = json_encode( $val );
			}

			add_filter(
				'theme_mod_' . $mod,
				function () use ( $val ) {
					return $val;
				}
			);
		}

		// Make sure if transparent header is on to apply it global for this header.
		add_filter( 'theme_mod_neve_transparent_only_on_home', '__return_false' );
	}

	/**
	 * Flag script for enqueue.
	 */
	public static function flag_for_enqueue() {
		self::$should_add_script = true;
	}
}
