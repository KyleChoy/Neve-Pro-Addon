<?php
/**
 * Handle styles that need to be added to already existing inline style managers in the theme.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2018-12-03
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Views\Inline;

use Neve_Pro\Core\Settings;

/**
 * Class Injector
 *
 * @package Neve Pro Addon
 */
class Injector {

	/**
	 * Initialize the injector and hook in the new classes.
	 */
	public function init() {
		add_filter( 'neve_filter_inline_style_classes', array( $this, 'inject_front_end_style_classes' ), 10, 3 );
		add_filter( 'neve_filter_inline_style_classes', array( $this, 'inject_gutenberg_style_classes' ), 10, 3 );
	}

	/**
	 * Inject front end style classes from this plugin.
	 *
	 * @param array $style_classes style classes from Neve.
	 *
	 * @return array
	 * @see \Neve\Views\Inline\Front_End_Style_Manager
	 */
	public function inject_front_end_style_classes( $style_classes, $style_handle ) {
		if ( $style_handle !== 'neve-generated-style' ) {
			return $style_classes;
		}
		$style_classes[] = '\\Neve_Pro\\Views\\Inline\\Colors';

		$additional = array();
		$settings   = new Settings();

		if ( $settings->is_module_active( 'woocommerce_booster' ) && class_exists( 'WooCommerce' ) ) {
			$additional[] = '\\Neve_Pro\\Modules\\Woocommerce_Booster\\Inline\\Shop_Page';
		}

		if ( $settings->is_module_active( 'lifterlms_booster' ) && class_exists( 'LifterLMS' ) ) {
			$additional[] = '\\Neve_Pro\\Modules\\LifterLMS_Booster\\Inline\\Course_Membership';
			$additional[] = '\\Neve_Pro\\Modules\\LifterLMS_Booster\\Inline\\Colors';
		}

		if ( $settings->is_module_active( 'scroll_to_top' ) ) {
			$additional[] = '\\Neve_Pro\\Modules\\Scroll_To_Top\\Inline\\Scroll_To_Top';
		}

		if ( $settings->is_module_active( 'blog_pro' ) ) {
			$additional[] = '\\Neve_Pro\\Modules\\Blog_Pro\\Inline\\Blog_Pro';
		}

		return array_merge( $additional, $style_classes );
	}

	/**
	 * Inject front end style classes from this plugin.
	 *
	 * @param array  $style_classes Style classes from Neve.
	 * @param string $style_handle Handle gutenberg style.
	 *
	 * @return array
	 * @see \Neve\Views\Inline\Gutenberg_Style_Manager
	 */
	public function inject_gutenberg_style_classes( $style_classes, $style_handle ) {
		if ( $style_handle !== 'neve-gutenberg-style' ) {
			return $style_classes;
		}

		return $style_classes;
	}
}
