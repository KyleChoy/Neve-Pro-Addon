<?php
/**
 *  Class that add quick view functionality.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

/**
 * Class Quick_View
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Quick_View extends Abstract_Shop_Product {

	/**
	 * Register quick view hooks.
	 *
	 * @return mixed|void
	 */
	public function register_hooks() {
		add_action( 'wp', array( $this, 'run' ) );
	}

	/**
	 * Run quick view actions.
	 */
	public function run() {
		$quick_view = get_theme_mod( 'neve_quick_view', 'none' );
		if ( $quick_view === 'none' || neve_is_amp() ) {
			return;
		}
		add_filter( 'neve_wrapper_class', array( $this, 'add_to_cart_button_class' ) );
		add_action( 'neve_image_buttons', array( $this, 'quick_view_button' ), 13 );
		add_action( 'wp_footer', array( $this, 'render_modal' ), 100 );
	}

	/**
	 * Add class to products wrapper.
	 *
	 * @param string $classes Classes of products wrapper.
	 *
	 * @return string
	 */
	public function add_to_cart_button_class( $classes ) {
		if ( strpos( $classes, 'nv-button-on-image' ) ) {
			return $classes;
		}

		return $classes . ' nv-button-on-image';
	}

	/**
	 * Markup for the quick view button.
	 */
	public function quick_view_button() {
		global $product;
		$quick_view_text = apply_filters( 'neve_quick_view_button_text', esc_html__( 'Quick view', 'neve' ) );
		$quick_view      = get_theme_mod( 'neve_quick_view', 'none' );
		echo '<a href="#" class="nv-quick-view-product ' . esc_attr( $quick_view ) . '" data-pid="' . esc_attr( $product->get_id() ) . '">' . esc_html( $quick_view_text ) . '</a>';
	}

	/**
	 * Quick view modal markup
	 */
	public function render_modal() {
		echo '<div id="quick-view-modal" class="nv-modal" aria-modal="true">';
		echo '<div class="nv-modal-overlay jsOverlay"></div>';
		echo '<div class="nv-modal-container is-loading">';
		echo '<button class="nv-modal-close jsModalClose" aria-label="' . esc_html__( 'Close Quick View', 'neve' ) . '">&#10005;</button>';
		echo '<div class="nv-modal-inner-content"></div>';
		echo '<div class="nv-loader-wrap"><span class="nv-loader"></span></div>';
		echo '</div>';
		echo '</div>';
	}
}
