<?php
/**
 * Customizer Class for Header Footer Grid.
 *
 * Name:    Header Footer Grid Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve_Pro\Modules\Header_Footer_Grid\Module;

/**
 * Class Header_Footer_Grid
 *
 * @package Neve_Pro\Customizer\Options
 */
class Transparent_Header extends Base_Customizer {
	/**
	 * Add the transparent header control.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_controls() {
		$this->add_control(
			new Control(
				'neve_transparent_header',
				array(
					'transport'         => 'refresh',
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'              => esc_html__( 'Enable Transparent Header', 'neve' ),
					'section'            => 'neve_pro_global_header_settings',
					'type'               => 'neve_toggle_control',
					'priority'           => 10,
					'conditional_header' => true,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_transparent_only_on_home',
				array(
					'transport'         => 'refresh',
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => true,
				),
				array(
					'label'           => esc_html__( 'Show Transparent Header on the Homepage Only', 'neve' ),
					'section'         => 'neve_pro_global_header_settings',
					'type'            => 'neve_toggle_control',
					'priority'        => 15,
					'active_callback' => [ $this, 'is_transparent_enabled' ],
				)
			)
		);
	}

	/**
	 * Add classes to header wrapper.
	 *
	 * @param string $classes The classes for the wrapper.
	 *
	 * @return string
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_class_to_header_wrapper( $classes ) {
		// Handle customizer with conditional headers.
		if ( is_customize_preview() ) {
			$conditional_header = get_theme_mod( 'neve_header_conditional_selector' );
			if ( isset( $conditional_header['layout'] ) && $conditional_header['layout'] !== 'default' && get_theme_mod( 'neve_transparent_header', false ) ) {
				// Flag script for enqueue.
				Module::flag_for_enqueue();

				return $classes . ' neve-transparent-header';
			}
		}

		if ( ! get_theme_mod( 'neve_transparent_only_on_home', true ) && get_theme_mod( 'neve_transparent_header', false ) ) {
			// Flag script for enqueue.
			Module::flag_for_enqueue();

			return $classes . ' neve-transparent-header';
		}

		// check that page is front page but not blog.
		if ( is_front_page() && get_option( 'show_on_front' ) === 'page' && get_theme_mod( 'neve_transparent_header', false ) ) {
			// Flag script for enqueue.
			Module::flag_for_enqueue();

			return $classes . ' neve-transparent-header';
		}

		return $classes;
	}

	/**
	 * Active callback for `neve_transparent_only_on_home` setting.
	 *
	 * @return bool
	 */
	public function is_transparent_enabled() {
		$conditional_header = get_theme_mod( 'neve_header_conditional_selector' );
		$is_default_header  = ! isset( $conditional_header['layout'] ) || $conditional_header['layout'] === 'default';

		return get_theme_mod( 'neve_transparent_header' ) && $is_default_header;
	}
}
