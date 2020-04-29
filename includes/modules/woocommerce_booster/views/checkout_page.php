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
 * Class Checkout_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Checkout_Page extends Base_View {

	/**
	 * Check if submodule should be loaded.
	 *
	 * @return bool
	 */
	private function should_load() {
		if ( ! class_exists( 'Woocommerce' ) ) {
			return false;
		}

		if ( ! is_checkout() ) {
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

		$this->style();
		$this->toggle_coupon();
		$this->fixed_order_box();
		$this->toggle_order_note();
		$this->labels_as_placeholders();
	}

	/**
	 * Use labels as placeholders.
	 */
	private function labels_as_placeholders() {
		$placeholder_labels = get_theme_mod( 'neve_checkout_labels_placeholders', false );

		if ( $placeholder_labels === false ) {
			return;
		}

		add_filter(
			'body_class',
			function ( $classes ) {
				$classes[] = 'nv-checkout-labels-placeholders';

				return $classes;
			}
		);
		add_filter(
			'woocommerce_form_field_args',
			function ( $args, $key, $value ) {
				if ( ! isset( $args['label'] ) ) {
					return $args;
				}
				$required            = ( $args['required'] === true ) ? ' *' : '';
				$args['placeholder'] = esc_html( $args['label'] . $required );

				return $args;
			},
			0,
			3
		);
	}

	/**
	 * Add body class for boxed style.
	 */
	private function style() {
		$style = get_theme_mod( 'neve_checkout_page_style', 'normal' );

		if ( $style === 'normal' ) {
			return;
		}

		add_filter(
			'body_class',
			function ( $classes ) {
				$classes[] = 'nv-checkout-boxed-style';

				return $classes;
			}
		);
	}

	/**
	 * Handle the fixed order box
	 */
	private function fixed_order_box() {
		$fixed_order_box = get_theme_mod( 'neve_enable_checkout_fixed_order', false );

		if ( $fixed_order_box === false ) {
			return;
		}

		add_filter(
			'body_class',
			function ( $classes ) {
				$classes[] = 'nv-checkout-fixed-total';

				return $classes;
			}
		);
		// Move payment to left column.
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		add_action( 'woocommerce_checkout_shipping', 'woocommerce_checkout_payment', 100 );
	}

	/**
	 * Toggle order note.
	 */
	private function toggle_order_note() {
		$order_note = get_theme_mod( 'neve_enable_checkout_order_note', true );
		if ( $order_note === true ) {
			return;
		}

		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
	}

	/**
	 * Toggle checkout coupon.
	 */
	private function toggle_coupon() {
		$coupon = get_theme_mod( 'neve_enable_checkout_coupon', true );
		if ( $coupon === true ) {
			return;
		}

		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	}
}
