<?php
/**
 * Elementor Booster Module main file.
 *
 * @package Neve_Pro\Modules\Elementor_Booster
 */

namespace Neve_Pro\Modules\Elementor_Booster;

use Elementor\Core\Files\CSS\Post;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Elementor_Booster
 */
class Module extends Abstract_Module {

	/**
	 * Holds the base module namespace
	 * Used to load submodules.
	 *
	 * @var string $module_namespace
	 */
	private $module_namespace = 'Neve_Pro\Modules\Elementor_Booster';

	/**
	 * Elementor widgets class array.
	 *
	 * @var array
	 */
	private $widgets;

	/**
	 * Elementor extensions.
	 *
	 * @var array
	 */
	private $extensions;

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug              The slug of the module.
	 * @property string  $this->name              The pretty name of the module.
	 * @property string  $this->description       The description of the module.
	 * @property string  $this->order             Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active            Optional. Default `false`. The state of the module by default.
	 * @property boolean $this->dependent_plugins Optional. Dependent plugin for this module.
	 * @property boolean $this->documentation     Optional. Module documentation.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug              = 'elementor_booster';
		$this->name              = __( 'Elementor Booster', 'neve' );
		$this->description       = __( 'Leverage the true flexibility of Elementor with powerful addons and templates that you can import with just one click.', 'neve' );
		$this->order             = 7;
		$this->dependent_plugins = array(
			'elementor' => array(
				'path' => 'elementor/elementor.php',
				'name' => 'Elementor',
			),
		);
		$this->documentation     = array(
			'url'   => 'https://docs.themeisle.com/article/1063-elementor-booster-module-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);

		$this->widgets = array(
			$this->module_namespace . '\Widgets\Flip_Card',
			$this->module_namespace . '\Widgets\Review_Box',
			$this->module_namespace . '\Widgets\Share_Buttons',
			$this->module_namespace . '\Widgets\Typed_Headline',
			$this->module_namespace . '\Widgets\Team_Member',
			$this->module_namespace . '\Widgets\Progress_Circle',
			$this->module_namespace . '\Widgets\Banner',
			$this->module_namespace . '\Widgets\Content_Switcher',
			$this->module_namespace . '\Widgets\Custom_Field',
		);

		$this->extensions = array(
			$this->module_namespace . '\Extensions\Particle_Section',
			$this->module_namespace . '\Extensions\Content_Protection',
			$this->module_namespace . '\Extensions\Advanced_Animation',
		);
	}

	/**
	 * Check if module should be loaded.
	 *
	 * @return bool
	 */
	function should_load() {
		return ( $this->settings->is_module_active( $this->slug ) && defined( 'ELEMENTOR_VERSION' ) );
	}

	/**
	 * Run Elementor Booster Module
	 */
	function run_module() {
		add_action( 'after_setup_theme', array( $this, 'register_extensions' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'register_styles' ) );

		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Register extensions
	 */
	public function register_extensions() {
		foreach ( $this->extensions as $extension ) {
			new $extension();
		}
	}

	/**
	 * Register Elementor Widgets.
	 */
	public function register_widgets() {
		foreach ( $this->widgets as $widget ) {
			Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
		}
	}

	/**
	 * Add a new category of widgets.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'neve-elementor-widgets',
			array(
				'title' => esc_html__( 'Neve Pro Addon Widgets', 'neve' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register styles and maybe load them on the editor side when needed.
	 */
	function register_styles() {
		$this->rtl_enqueue_style( 'neve-elementor-widgets-styles', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/css/style.min.css', array(), NEVE_PRO_VERSION );
		wp_register_style(
			'font-awesome-5-all',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
			false,
			NEVE_PRO_VERSION
		);
	}

	/**
	 * Register scripts.
	 */
	public function register_scripts() {

		// Typed text widget scripts
		wp_register_script( 'neb-typed-animation', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/typed-text/typed.min.js', array( 'jquery' ), NEVE_PRO_VERSION );
		wp_register_script( 'neb-typed-script', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/typed-text/typed-main.js', array( 'neb-typed-animation' ), NEVE_PRO_VERSION );

		// Flip card widget scripts
		wp_register_script( 'neb-flip-card-script', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/flip-card.js', array( 'jquery' ), NEVE_PRO_VERSION );

		// Particles script
		wp_register_script( 'neb-particles', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/particles/particles.min.js', array( 'jquery' ), NEVE_PRO_VERSION );
		wp_register_script( 'neb-particles-script', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/particles/particles-main.js', array( 'neb-particles' ), NEVE_PRO_VERSION );
		wp_localize_script( 'neb-particles-script', 'nebData', $this->localize_data() );
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_script( 'neb-particles-script' );
		}

		// Progress Circle
		wp_register_script( 'neb-as-pie-progress', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/progress-circle/jquery-asPieProgress.min.js', array( 'jquery' ), NEVE_PRO_VERSION );
		wp_register_script( 'neb-appear', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/progress-circle/jquery.appear.min.js', array( 'jquery' ), NEVE_PRO_VERSION );
		wp_register_script( 'neb-progress-circle', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/progress-circle/progress-circle.js', array( 'neb-as-pie-progress', 'neb-appear' ), NEVE_PRO_VERSION );

		// Content Switcher
		wp_register_script( 'neb-content-switcher', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/content-switcher.js', array( 'jquery' ), NEVE_PRO_VERSION );

		// Advanced Animations
		wp_register_script( 'neb-anime', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/advanced-animations/anime.min.js', array(), NEVE_PRO_VERSION );
		wp_register_script( 'neb-animations', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/advanced-animations/advanced-animations.js', array( 'neb-anime', 'jquery' ), NEVE_PRO_VERSION );
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_script( 'neb-animations' );
		}
	}

	/**
	 * Localize data for js script
	 *
	 * @return array
	 */
	private function localize_data() {

		$data                       = [];
		$data['ParticleThemesData'] = [
			'default'  => '{"particles":{"number":{"value":160,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'nasa'     => '{"particles":{"number":{"value":250,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'bubble'   => '{"particles":{"number":{"value":15,"density":{"enable":true,"value_area":800}},"color":{"value":"#1b1e34"},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":50,"random":false,"anim":{"enable":true,"speed":10,"size_min":40,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":8,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'snow'     => '{"particles":{"number":{"value":450,"density":{"enable":true,"value_area":800}},"color":{"value":"#fff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":5,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":500,"color":"#ffffff","opacity":0.4,"width":2},"move":{"enable":true,"speed":6,"direction":"bottom","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":0.5}},"bubble":{"distance":400,"size":4,"duration":0.3,"opacity":1,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'nyan_cat' => '{"particles":{"number":{"value":150,"density":{"enable":false,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"star","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"http://wiki.lexisnexis.com/academic/images/f/fb/Itunes_podcast_icon_300.jpg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":4,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":14,"direction":"left","random":false,"straight":true,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":200,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
		];

		return $data;
	}

	/**
	 * Enqueue font awesome 5.
	 *
	 * @param int $post_id Post id.
	 * @return bool
	 */
	public function enqueue_fa5_fonts( $post_id ) {
		$post_css = new Post( $post_id );
		$meta     = $post_css->get_meta();
		var_dump( $meta );
		if ( empty( $meta['icons'] ) ) {
			return false;
		}
		$icons_types = Icons_Manager::get_icon_manager_tabs();
		foreach ( $meta['icons'] as $icon_font ) {
			if ( ! isset( $icons_types[ $icon_font ] ) ) {
				continue;
			}
			Plugin::instance()->frontend->enqueue_font( $icon_font );
		}

		return true;
	}
}
