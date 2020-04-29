<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-02-11
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

use Neve\Views\Base_View;

/**
 * Class Cart_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Cart_Page extends Base_View {

	/**
	 * Check if submodule should be loaded.
	 *
	 * @return bool
	 */
	private function should_load() {
		if ( ! class_exists( 'Woocommerce' ) ) {
			return false;
		}

		if ( ! is_cart() ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize the module.
	 */
	public function init() {
		add_action( 'wp', array( $this, 'run' ) );
	}

	/**
	 * Register submodule hooks
	 */
	public function register_hooks() {
		$this->init();
	}

	/**
	 * Run the module.
	 */
	public function run() {
		if ( ! $this->should_load() ) {
			return;
		}

		$this->layout();
		$this->style();
		$this->toggle_upsells();
	}

	/**
	 * Change cart page style.
	 */
	private function style() {
		$style = get_theme_mod( 'neve_cart_page_style', 'normal' );

		if ( $style === 'normal' ) {
			return;
		}

		add_filter(
			'body_class',
			function ( $classes ) {
				$classes[] = 'nv-cart-boxed-style';

				return $classes;
			}
		);
	}

	/**
	 * Change cart page layout.
	 */
	private function layout() {
		$layout = get_theme_mod( 'neve_cart_page_layout', 'normal' );

		if ( $layout === 'normal' ) {
			return;
		}

		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
		add_action( 'woocommerce_after_cart_table', 'woocommerce_cart_totals', 10 );
		add_filter(
			'body_class',
			function ( $classes ) {
				$classes[] = 'nv-cart-side-by-side';

				return $classes;
			}
		);

		$fixed = get_theme_mod( 'neve_enable_cart_fixed_total', false );

		if ( $fixed === false ) {
			return;
		}

		add_filter(
			'body_class',
			function ( $classes ) {
				$classes[] = 'nv-cart-total-fixed';

				return $classes;
			}
		);
	}

	/**
	 * Toggle cart page up-sells.
	 */
	private function toggle_upsells() {
		$enable_cart_upsells = get_theme_mod( 'neve_enable_cart_upsells', true );

		if ( $enable_cart_upsells === true ) {
			return;
		}

		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}
}
