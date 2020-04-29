<?php
/**
 * The customizer addons loader class.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2018-12-03
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Customizer;


use Neve\Core\Factory;
use Neve_Pro\Admin\Conditional_Display;
use Neve_Pro\Traits\Core;

/**
 * Class Loader
 *
 * @since   0.0.1
 * @package Neve Pro Addon
 */
class Loader {
	use Core;

	/**
	 * Customizer modules.
	 *
	 * @access private
	 * @since  0.0.1
	 * @var array
	 */
	private $modules = array();

	/**
	 * Loader constructor.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_controls' ) );
	}

	/**
	 * Initialize the customizer functionality
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function init() {
		global $wp_customize;

		if ( ! isset( $wp_customize ) ) {
			return;
		}

		$this->define_modules();
		$this->load_modules();
	}

	/**
	 * Define the modules that will be loaded.
	 *
	 * @access private
	 * @since  0.0.1
	 */
	private function define_modules() {
		$this->modules = apply_filters(
			'neve_pro_filter_customizer_modules',
			array(
				'Customizer\Options\Main',
			)
		);
	}

	/**
	 * Enqueue customizer controls script.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function enqueue_customizer_controls() {
		// Legacy controls.
		wp_enqueue_script( 'neve-pro-controls', NEVE_PRO_INCLUDES_URL . 'customizer/controls/js/bundle.js', array(), NEVE_PRO_VERSION );
		$this->rtl_enqueue_style( 'neve-pro-controls', NEVE_PRO_INCLUDES_URL . 'customizer/controls/css/customizer-controls.min.css', array(), NEVE_PRO_VERSION );

		// React controls.
		$editor_dependencies = [
			'wp-i18n',
			'wp-components',
			'wp-edit-post',
			'wp-element',
			'customize-controls',
		];

		wp_register_script( 'neve-pro-react-controls', NEVE_PRO_INCLUDES_URL . 'customizer/controls/react/bundle/controls.js', $editor_dependencies, NEVE_PRO_VERSION );

		$localization = apply_filters(
			'neve_pro_react_controls_localization',
			[
				'conditionalRules' => $this->get_conditional_rules_array(),
				'headerLayouts'    => $this->get_header_layouts(),
				'headerControls'   => [ 'hfg_header_layout' ],
				'currentValues'    => [ 'hfg_header_layout' => json_decode( get_theme_mod( 'hfg_header_layout' ), true ) ],
			]
		);

		wp_localize_script( 'neve-pro-react-controls', 'NeveProReactCustomize', $localization );
		wp_enqueue_script( 'neve-pro-react-controls' );

		$this->rtl_enqueue_style( 'neve-pro-react-controls', NEVE_PRO_INCLUDES_URL . 'customizer/controls/react/bundle/controls.css', [ 'wp-components' ], NEVE_PRO_VERSION );
	}

	/**
	 * Get header layouts already available.
	 *
	 * @return array
	 */
	private function get_header_layouts() {
		$posts = [];
		$args  = array(
			'post_type'              => 'neve_custom_layouts',
			'meta_query'             => array(
				array(
					'key'     => 'header-layout',
					'value'   => true,
					'compare' => '=',
				),
			),
			'posts_per_page'         => 100,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'post_status'            => 'publish',
		);

		$query = new \WP_Query( $args );
		if ( ! $query->have_posts() ) {
			return [];
		}
		foreach ( $query->posts as $post ) {
			$posts[ $post->post_name ] = [
				'label'      => $post->post_title,
				'conditions' => json_decode( get_post_meta( $post->ID, 'custom-layout-conditional-logic', true ), true ),
				'mods'       => json_decode( get_post_meta( $post->ID, 'theme-mods', true ), true ),
			];
		}

		return $posts;
	}

	/**
	 * Enqueue customizer preview script.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function enqueue_customizer_preview() {
	}

	/**
	 * Load the customizer modules.
	 *
	 * @access private
	 * @return void
	 * @since  0.0.1
	 */
	private function load_modules() {
		$factory = new Factory( $this->modules, '\\Neve_Pro\\' );
		$factory->load_modules();
	}

	/**
	 * Get the conditional rules array.
	 *
	 * @return array
	 */
	private function get_conditional_rules_array() {
		$conditional_display = new Conditional_Display();

		return [
			'root' => $conditional_display->get_root_ruleset(),
			'end'  => $conditional_display->get_end_ruleset(),
			'map'  => $conditional_display->get_ruleset_map(),
		];
	}
}
