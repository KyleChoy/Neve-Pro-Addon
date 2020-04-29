<?php
/**
 * Rest Endpoints Handler.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Rest
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Rest;

use Neve_Pro\Modules\Woocommerce_Booster\Views\Quick_View;
use Neve_Pro\Modules\Woocommerce_Booster\Views\Shop_Product;
use Neve_Pro\Modules\Woocommerce_Booster\Views\Single_Product;
use Neve_Pro\Modules\Woocommerce_Booster\Views\Wish_List;

/**
 * Class Server
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Rest
 */
class Server {
	/**
	 * Wish list class instance.
	 *
	 * @var Wish_List
	 */
	private $wish_list_instance;

	/**
	 *  Quick View class instance.
	 *
	 * @var Quick_View
	 */
	private $quick_view_instance;

	/**
	 *  Shop Product class instance.
	 *
	 * @var Shop_Product
	 */
	private $shop_product_instance;

	/**
	 * Server constructor.
	 */
	public function __construct() {
		$this->wish_list_instance = new Wish_List();

		$this->quick_view_instance = new Quick_View();

		$this->shop_product_instance = new Shop_Product();
	}

	/**
	 * Initialize the rest functionality.
	 */
	public function register_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Register endpoints.
	 */
	public function register_endpoints() {
		/**
		 * Quick View endpoint.
		 */
		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/products/post/(?P<product_id>\d+)/',
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_product' ),
				'args'     => array(
					'product_id' => array(
						'validate_callback' => 'is_numeric',
					),
				),
			)
		);

		/**
		 * Wish List endpoint.
		 */
		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/wishlist/(?P<product_id>\d+)/',
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'toggle_wishlist_product' ),
				'args'     => array(
					'product_id' => array(
						'validate_callback' => 'is_numeric',
					),
				),
			)
		);

		/**
		 * Wish List update endpoint.
		 */
		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/update_wishlist/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_wishlist' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);
	}

	/**
	 * Get quick view content.
	 *
	 * @param \WP_REST_Request $request the request.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_product( \WP_REST_Request $request ) {
		if ( empty( $request['product_id'] ) ) {
			return new \WP_REST_Response(
				array(
					'code'    => 'error',
					'message' => __( 'Quick View modal error: Product id is missing.', 'neve' ),
					'markup'  => '<p class="request-notice">' . __( 'Something went wrong while displaying the product.' ) . '</p>',
				),
				200
			);
		}

		$product_id = intval( $request['product_id'] );

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'post__in'       => array( $product_id ),
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();
				$this->run_markup_changes( $product_id );
				echo '<div class="woocommerce single product">';
				echo '<div id="product-' . esc_attr( $product_id ) . '" class="' . join( ' ', get_post_class( 'product', $product_id ) ) . '">';
				woocommerce_show_product_sale_flash();
				echo '<div class="nv-qv-gallery-wrap">';
				$this->render_gallery( $product_id );
				echo '</div>';
				echo '<div class="summary entry-summary">';
				echo '<div class="summary-content">';
				do_action( 'woocommerce_single_product_summary' );
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			$markup = ob_get_clean();
			$markup = str_replace( 'href="#reviews"', 'href="' . esc_url( get_permalink( $product_id ) ) . '#reviews"', $markup );

			return new \WP_REST_Response(
				array(
					'code'   => 'success',
					'markup' => $markup,
				),
				200
			);
		}

		return new \WP_REST_Response(
			array(
				'code'    => 'error',
				'message' => __( 'Quick View modal error: Product id is missing.', 'neve' ),
				'markup'  => '<p class="request-notice">' . __( 'Something went wrong while displaying the product.' ) . '</p>',
			),
			400
		);
	}

	/**
	 * Run markup changes needed.
	 *
	 * @param int $product_id the product id.
	 */
	private function run_markup_changes( $product_id ) {
		// Run single product changes.
		$single = new Single_Product();
		$single->run();

		// Hook in the add to cart button as it's not always available.
		// [depends on hook priority which is not foreseeable]
		$product = wc_get_product( $product_id );
		add_action( 'woocommerce_' . $product->get_type() . '_add_to_cart', 'woocommerce_' . $product->get_type() . '_add_to_cart', 30 );
		add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

		// Wrap buttons.
		add_action(
			'woocommerce_' . $product->get_type() . '_add_to_cart',
			function () {
				echo '<div class="qv-actions">';
			},
			29
		);
		// Add more details and close wrap.
		add_action(
			'woocommerce_' . $product->get_type() . '_add_to_cart',
			function () use ( $product ) {
				echo '<a class="button button-secondary more-details" href="' . esc_url( $product->get_permalink() ) . '">' . esc_html__( 'More Details', 'neve' ) . '</a></div>';
			},
			31
		);

		// Remove quantity
		add_filter( 'woocommerce_is_sold_individually', '__return_true', 10, 2 );
	}

	/**
	 * Render the product gallery.
	 *
	 * @param int $product_id the product id.
	 */
	private function render_gallery( $product_id ) {
		$product        = wc_get_product( $product_id );
		$attachment_ids = array();

		$attachment_ids[] = get_post_thumbnail_id( $product_id );
		$attachment_ids   = array_merge( $attachment_ids, $product->get_gallery_image_ids() );

		$thumbnails  = array();
		$full_images = array();

		foreach ( $attachment_ids as $attachment_id ) {
			$thumbnails[]  = wp_get_attachment_image_url( $attachment_id, 'thumbnail', true );
			$full_images[] = wp_get_attachment_image_url( $attachment_id, 'full' );
		}

		echo '<div class="nv-slider-gallery">';
		foreach ( $full_images as $index => $url ) {
			echo '<img data-slide="' . esc_attr( $index ) . '" src="' . esc_url( $url ) . '"/>';
		}
		echo '</div>';

		echo neve_kses_svg( $this->get_gallery_arrows() ); // WPCS: XSS OK.
	}

	/**
	 * Get the gallery arrows markup.
	 *
	 * @return string
	 */
	private function get_gallery_arrows() {
		$arrow_map = array(
			'left'  => '<svg width="25px" height="30px" viewBox="0 0 50 80"><polyline fill="none" stroke="#333" stroke-width="7" points="25,76 10,38 25,0"/></svg>',
			'right' => '<svg width="25px" height="30px" viewBox="0 0 50 80"><polyline fill="none" stroke="#333" stroke-width="7" points="25,0 40,38 25,75"/></svg>',
		);
		$markup    = '';

		$markup .= '<div class="nv-slider-controls">';
		$markup .= '<a href="#" aria-label="' . __( 'Previous image', 'neve' ) . '" class="prev">';
		$markup .= $arrow_map['left'];
		$markup .= '</a>';
		$markup .= '<a href="#" aria-label="' . __( 'Next image', 'neve' ) . '" class="next">';
		$markup .= $arrow_map['right'];
		$markup .= '</a>';
		$markup .= '</div>';

		return $markup;
	}

	/**
	 * Update the wishlist.
	 *
	 * @param \WP_REST_Request $request the rest request.
	 *
	 * @return \WP_REST_Response
	 */
	public function update_wishlist( \WP_REST_Request $request ) {
		$user_id       = get_current_user_id();
		$data          = $request->get_json_params() ? $request->get_json_params() : array();
		$current_value = $this->wish_list_instance->get_meta_wishlist_array( $user_id );

		if ( is_array( $current_value ) && ! empty( $current_value ) ) {
			$data = array_replace( $current_value, $data );
		}

		$data = array_filter( $data );

		if ( sizeof( $data ) >= 50 ) {
			$first_element = array_keys( $data );
			unset( $data[ $first_element[0] ] );
		}

		update_user_meta( $user_id, 'wish_list_products', json_encode( $data ) );

		return new \WP_REST_Response(
			array(
				'code'    => 'success',
				'message' => esc_html__( 'Wishlist updated', 'neve' ),
				'data'    => $data,
			)
		);
	}
}
