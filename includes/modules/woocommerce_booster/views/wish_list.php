<?php
/**
 * Class that add wish list functionality
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

use HFG\Core\Components\Nav;

/**
 * Class Wish_List
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Wish_List extends Abstract_Shop_Product {

	/**
	 * Cookie id.
	 *
	 * @var string
	 */
	public $cookie_id = 'nv-wishlist';

	/**
	 * Register wish list hooks.
	 *
	 * @return mixed|void
	 */
	public function register_hooks() {
		add_action( 'wp', array( $this, 'run' ) );
		$wish_list = get_theme_mod( 'neve_wish_list', 'none' );
		if ( $wish_list === 'none' ) {
			return null;
		}
		add_action( 'woocommerce_account_menu_items', array( $this, 'add_account_tab' ) );
		add_action( 'init', array( $this, 'add_wish_list_endpoint' ) );
		add_filter( 'query_vars', array( $this, 'wish_list_query_vars' ), 0 );
		add_action( 'woocommerce_account_nv-wish-list_endpoint', array( $this, 'render_wish_list_table' ) );
		add_action( 'wp_login', array( $this, 'update_wishlist_from_cookie' ), 10, 2 );
	}

	/**
	 * Updates wish list from $_COOKIE.
	 *
	 * @param  string   $user_login the user name.
	 * @param \WP_User $user       user object.
	 */
	public function update_wishlist_from_cookie( $user_login, \WP_User $user ) {
		$meta_wish_list = $this->get_meta_wishlist_array( $user->ID );

		if ( empty( $meta_wish_list ) || $meta_wish_list === null ) {
			$meta_wish_list = array();
		}

		if ( ! isset( $_COOKIE[ $this->cookie_id ] ) || ! is_array( $this->get_cookie_wishlist_array() ) ) {
			return;
		}

		$meta_wish_list = array_replace( $meta_wish_list, $this->get_cookie_wishlist_array() );

		if ( sizeof( $meta_wish_list ) >= 50 ) {
			$first_element = array_keys( $meta_wish_list );
			unset( $meta_wish_list[ $first_element[0] ] );
		}

		update_user_meta( $user->ID, 'wish_list_products', json_encode( $meta_wish_list ) );
		setcookie( $this->cookie_id, null, - 1, '/' );
	}

	/**
	 * Run wish list actions.
	 */
	public function run() {
		if ( neve_is_amp() ) {
			return false;
		}
		add_action( 'neve_last_menu_item_wish_list', array( $this, 'add_wish_list_menu_item' ) );

		$wish_list = get_theme_mod( 'neve_wish_list', 'none' );
		if ( $wish_list === 'none' ) {
			return false;
		}
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'add_wish_list_button' ), 13 );
		add_action( 'wp_footer', array( $this, 'render_wl_notifications' ) );
	}

	/**
	 * Checks if the product is in the wishlist.
	 *
	 * @param int $product_id the product id.
	 *
	 * @return bool
	 */
	private function is_product_in_wishlist( $product_id ) {
		$user_id          = get_current_user_id();
		$cookie_wish_list = $this->get_cookie_wishlist_array();
		if ( $user_id !== 0 ) {
			$wish_list = $this->get_meta_wishlist_array( $user_id );
			$wish_list = array_replace( $wish_list, $cookie_wish_list );

			if ( ! empty( $wish_list ) && isset( $wish_list[ $product_id ] ) ) {
				return $wish_list[ $product_id ];
			}

			return false;
		}

		if ( array_key_exists( $product_id, $cookie_wish_list ) ) {
			return $cookie_wish_list[ $product_id ];
		}

		return false;
	}

	/**
	 * Get wish list from cookie.
	 *
	 * @return array
	 */
	private function get_cookie_wishlist_array() {
		if ( ! isset( $_COOKIE[ $this->cookie_id ] ) ) {
			return array();
		}

		$cookie_wishlist = json_decode( wp_unslash( $_COOKIE[ $this->cookie_id ] ), true );
		if ( ! is_array( $cookie_wishlist ) ) {
			return array();
		}

		return $cookie_wishlist;
	}

	/**
	 * Get wish list from user meta.
	 *
	 * @return array
	 */
	public function get_meta_wishlist_array( $user_id ) {
		$meta_wishlist = json_decode( get_user_meta( $user_id, 'wish_list_products', true ), true );
		if ( ! is_array( $meta_wishlist ) ) {
			return array();
		}

		return $meta_wishlist;
	}

	/**
	 * Wish List button markup.
	 */
	public function add_wish_list_button() {
		global $product;

		$position        = get_theme_mod( 'neve_wish_list', 'none' );
		$icon_class      = 'add-to-wl';
		$product_id      = $product->get_id();
		$wish_list_label = apply_filters( 'neve_wish_list_label', __( 'Add to wishlist', 'neve' ) );
		/* translators: %s - product title */
		$title_sr = apply_filters( 'neve_sr_title', sprintf( __( 'Add %s to wishlist', 'neve' ), get_the_title() ) );

		if ( $this->is_product_in_wishlist( $product_id ) ) {
			$icon_class .= ' item-added';
		}

		echo '<div class="nv-wl-wrap ' . esc_attr( $position ) . '">';
		echo '<a href="#" class="' . esc_attr( $icon_class ) . '" data-pid="' . esc_attr( $product_id ) . '" aria-label="' . esc_html( $title_sr ) . '">';
		echo '<svg width="18" height="18" viewBox="0 0 512 512"><path xmlns="http://www.w3.org/2000/svg" fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"/></svg>';
		echo '<span class="tooltip tooltip-left">' . esc_html( $wish_list_label ) . '</span>';
		echo '</a>';
		echo '</div>';
	}

	/**
	 * Add wish list menu item.
	 *
	 * @param string $items Items markup.
	 *
	 * @return string
	 */
	public function add_wish_list_menu_item( $items ) {
		$default = array(
			'search',
		);
		if ( class_exists( 'WooCommerce', false ) ) {
			array_push( $default, 'cart' );
		}
		$current_component = 'default';
		if ( isset( Nav::$current_component ) ) {
			$current_component = Nav::$current_component;
		}
		$last_menu_setting_slug = apply_filters( 'neve_last_menu_setting_slug_' . $current_component, 'neve_last_menu_item' );

		$last_menu_items = get_theme_mod( $last_menu_setting_slug, $default );
		$last_menu_items = json_decode( $last_menu_items, true );
		if ( ! in_array( 'wish_list', $last_menu_items, true ) ) {
			return $items;
		}
		$wl = $this->render_wish_list_icon();

		return $items . $wl;
	}

	/**
	 * Wish list icon markup.
	 *
	 * @param array $settings Settings array.
	 *
	 * @return string
	 */
	public function render_wish_list_icon( $settings = array() ) {
		$wish_list = get_theme_mod( 'neve_wish_list', 'none' );
		if ( $wish_list === 'none' && is_customize_preview() ) {
			$message = sprintf(
				/* translators: %s - path to wish list control */
				esc_html__( 'Activate your wish list from %s', 'neve' ),
				sprintf(
					'<strong>%s</strong>',
					esc_html__( 'Customizer > Layout > Shop > Product Card > Wish List', 'neve' )
				)
			);

			return '<li>' . $message . '</li>';
		}
		$tag   = ! empty( $settings['tag'] ) ? $settings['tag'] : 'li';
		$class = ! empty( $settings['class'] ) ? $settings['class'] : 'menu-item-nav-wish-list';
		$label = ! empty( $settings['label'] ) ? $settings['label'] : '';
		$wl    = '';
		$url   = wc_get_endpoint_url( 'nv-wish-list', '', get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );

		$wl .= '<' . esc_attr( $tag ) . ' class="' . esc_attr( $class ) . '">';
		$wl .= '<a href="' . esc_url( $url ) . '" class="wl-icon-wrapper" aria-label="' . __( 'Wish list', 'neve' ) . '">';
		$wl .= '<svg width="18" height="18" viewBox="0 0 512 512"><path xmlns="http://www.w3.org/2000/svg" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"/></svg>';
		$wl .= '<span class="screen-reader-text">' . __( 'Wish list', 'neve' ) . '</span>';
		if ( ! empty( $label ) ) {
			$wl .= '<p class="wl-label">' . wp_kses_post( $label ) . '</p>';
		}
		$wl .= '</a>';
		$wl .= '</' . esc_attr( $tag ) . '>';

		return $wl;
	}

	/**
	 * Register new endpoint to use for My Account page
	 */
	public function add_wish_list_endpoint() {
		add_rewrite_endpoint( 'nv-wish-list', EP_ROOT | EP_PAGES );
		flush_rewrite_rules();
	}

	/**
	 * Add new query var
	 *
	 * @param array $vars Query vars.
	 *
	 * @return array
	 */
	public function wish_list_query_vars( $vars ) {
		$vars[] = 'nv-wish-list';

		return $vars;
	}

	/**
	 * Add Wish List tab in account page.
	 *
	 * @param array $items WooCommerce tabs.
	 *
	 * @return array
	 */
	public function add_account_tab( $items ) {
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );
		$items['nv-wish-list']    = esc_html__( 'Wish List', 'neve' );
		$items['customer-logout'] = $logout;

		return $items;
	}

	/**
	 * Render wish list in My account page.
	 */
	public function render_wish_list_table() {
		$user_id            = get_current_user_id();
		$wish_list_products = array_filter( array_replace( $this->get_meta_wishlist_array( $user_id ), $this->get_cookie_wishlist_array() ) );
		if ( empty( $wish_list_products ) ) {
			echo apply_filters( 'neve_wishlist_empty', esc_html__( 'You don\'t have any products in your wish list yet.', 'neve' ) );

			return;
		}

		echo '<div class="nv-wishlist-wrap">';
		foreach ( $wish_list_products as $pid => $enabled ) {
			$product = wc_get_product( $pid );
			if ( ! ( $product instanceof \WC_Product ) ) {
				continue;
			}
			$availability = $product->get_availability();
			$stock_status = isset( $availability['class'] ) ? $availability['class'] : false;

			echo '<div class="nv-wl-product">';
			echo '<div class="loader-wrap"><span class="nv-loader"></span></div>';
			echo '<div class="nv-wl-product-content">';

			echo '<div class="product-thumbnail">';
			echo '<a href="' . esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $pid ) ) ) . '">';
			echo $product->get_image( 'woocommerce_gallery_thumbnail' );
			echo '</a>';
			echo '</div>';

			echo '<div class="details">';

			echo '<div class="product-name">';
			echo '<a href="' . esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $pid ) ) ) . '">';
			echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product );
			echo '</a>';
			echo '</div>';

			echo '<div class="price-stock">';
			echo '<div class="product-stock-status">';
			echo $stock_status === 'out-of-stock' ? '<span class="wishlist-out-of-stock">' . esc_html__( 'Out of Stock', 'neve' ) . '</span>' : '<span class="wishlist-in-stock">' . esc_html__( 'In Stock', 'neve' ) . '</span>';
			echo '</div>';

			echo '<div class="product-price">';
			echo $product->get_price() ? $product->get_price_html() : apply_filters( 'neve_wishlist_table_free_text', esc_html__( 'Free!', 'neve' ), $product );
			echo '</div>';
			echo '</div>'; // .price-stock

			echo '<div class="actions">';
			if ( isset( $stock_status ) && $stock_status !== 'out-of-stock' ) {
				echo '<div class="product-add-to-cart">';
				echo apply_filters(
					'woocommerce_loop_add_to_cart_link',
					sprintf(
						'<a href="%s" data-quantity="1" class="button button-primary">%s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_html( $product->add_to_cart_text() )
					),
					$product
				);
				echo '</div>'; // .product-add-to-cart
			}

			echo '<a class="remove remove-wl-item" data-pid="' . esc_attr( $pid ) . '">';
			echo '<span class="dashicons dashicons-no-alt"></span>';
			echo '</a>';
			echo '</div>'; // .actions

			echo '</div>'; // .details
			echo '</div>'; // .nv-wl-product
			echo '</div>'; // .nv-wl-product-content
		}
		echo '</div>'; // .nv-wishlist-wrap
	}

	/**
	 * Render function for wish list notification
	 */
	public function render_wl_notifications() {
		echo '<div class="nv-wl-notification" role="dialog">';
		echo '<div class="wl-notification-icon">';
		echo '<svg width="50" height="50" viewBox="0 0 512 512"><path xmlns="http://www.w3.org/2000/svg" fill="currentColor" d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"/></svg>';
		echo '</div>';
		echo '<div class="wl-notification-content">';
		echo '</div>';
		echo '</div>';
	}
}
