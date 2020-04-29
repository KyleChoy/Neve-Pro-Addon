<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-07
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Scroll_To_Top;

use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Scroll_To_Top
 */
class Module extends Abstract_Module {

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
		$this->slug          = 'scroll_to_top';
		$this->name          = __( 'Scroll To Top', 'neve' );
		$this->description   = __( 'Simple but effective module to help you navigate back to the top of the really long pages.', 'neve' );
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1060-scroll-to-top-module-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);
		$this->order         = 5;
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
	 * Run Scroll to Top Module
	 */
	public function run_module() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'neve_after_primary', array( $this, 'render_button' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'neve_pro_filter_customizer_modules', array( $this, 'add_customizer_classes' ) );
	}

	/**
	 * Add customizer classes.
	 *
	 * @param array $classes loaded classes.
	 *
	 * @return array
	 */
	public function add_customizer_classes( $classes ) {
		$classes[] = 'Modules\Scroll_To_Top\Customizer\Scroll_To_Top';

		return $classes;
	}

	/**
	 * Enqueue module scripts
	 *
	 * @return bool | void
	 */
	public function enqueue_scripts() {
		if ( neve_is_amp() ) {
			return false;
		}

		wp_register_script( 'neve-pro-scroll-to-top', NEVE_PRO_INCLUDES_URL . 'modules/scroll_to_top/assets/js/script.js', array(), NEVE_PRO_VERSION, true );

		wp_enqueue_script( 'neve-pro-scroll-to-top' );

		wp_localize_script( 'neve-pro-scroll-to-top', 'scrollOffset', $this->localize_scroll() );
	}

	/**
	 * Send offset to the JS object
	 *
	 * @return array
	 */
	private function localize_scroll() {
		return array(
			'offset' => get_theme_mod( 'neve_scroll_to_top_offset', 0 ),
		);
	}

	/**
	 * Enqueue module scripts for Customizer
	 */
	public function enqueue_customizer() {
		wp_enqueue_script( 'neve-pro-scroll-to-top', NEVE_PRO_INCLUDES_URL . 'modules/scroll_to_top/assets/js/customizer.js', array(), NEVE_PRO_VERSION, true );
	}

	/**
	 * Enqueue module style
	 */
	public function enqueue_style() {
		$this->rtl_enqueue_style( 'neve-scroll-to-top', NEVE_PRO_INCLUDES_URL . 'modules/scroll_to_top/assets/style.min.css', array(), NEVE_PRO_VERSION );
	}

	/**
	 * Display scroll to top button
	 */
	public function render_button() {
		echo '<div id="scroll-to-top" class="scroll-to-top" aria-hidden="true">';
		echo '<svg class="scroll-to-top-icon"  width="15" height="15" viewBox="0 0 448 512"><path fill="currentColor" d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"/></svg>';
		echo '</div>';
	}
}
