<?php
/**
 *  Class that add shop products functionalities.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

/**
 * Class Shop_Product
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Shop_Product extends Abstract_Shop_Product {

	/**
	 * Register shop product hooks
	 *
	 * @return mixed|void
	 */
	public function register_hooks() {
		/**
		 * Remove default product image width form neve theme.
		 */
		add_filter( 'neves_woocommerce_args', array( $this, 'remove_woocommerce_image_width' ) );

		/**
		 * Add link to product only on product title.
		 */
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		/**
		 * Wrap product image in a div.
		 */
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'card_content_wrapper' ), 7 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 7 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrap' ), 8 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		// Close .img-wrap
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wrapper_close_div' ), 11 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wrapper_close_div' ), 14 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wrapper_close_div' ), 14 );

		/**
		 * Wrap product content in a div.
		 */
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'neve_before_product_content' ), 999 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'wrapper_close_div' ), 999 );

		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'wrap_image_buttons' ), 12 );
		add_action( 'wp_loaded', array( $this, 'run' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_price_style' ) );
	}

	/**
	 * Add inline price style.
	 */
	public function add_inline_price_style() {
		$alignment = get_theme_mod( 'neve_product_content_alignment', 'left' );
		if ( $alignment !== 'inline' ) {
			return false;
		}
		$class_map = array(
			'title'             => '.woocommerce-loop-product__link',
			'reviews'           => '.advanced-rating-wraper',
			'category'          => '.product_meta',
			'short-description' => '.woocommerce-product-details__short-description',
		);

		$order_default_components = array( 'title', 'reviews', 'price' );
		$elements_order           = get_theme_mod( 'neve_layout_product_elements_order', json_encode( $order_default_components ) );
		$elements_order           = json_decode( $elements_order, true );
		$index                    = 1;
		$main_selector            = 'li.product .nv-product-content.inline';
		$custom_css               = '';
		foreach ( $elements_order as $element ) {
			if ( $element === 'price' ) {
				continue;
			}
			$custom_css .= $main_selector . ' ' . $class_map[ $element ] . '{ order: ' . $index . '!important;} ';
			if ( $element === 'title' ) {
				$custom_css .= $main_selector . ' span.price { order: ' . $index . '!important; } ';
				$index++;
			}
			$index++;
		}

		wp_add_inline_style( 'neve-style', $custom_css );
		return true;
	}

	/**
	 * Wrapper for card content.
	 */
	public function card_content_wrapper() {
		echo '<div class="nv-card-content-wrapper">';
	}

	/**
	 * Remove width for image.
	 *
	 * @param array $settings Settings for woocommerce image.
	 *
	 * @return mixed
	 */
	public function remove_woocommerce_image_width( $settings ) {
		unset( $settings['thumbnail_image_width'] );

		return $settings;
	}

	/**
	 * Product image wrapper.
	 */
	public function product_image_wrap() {
		$product_classes = apply_filters( 'neve_wrapper_class', '' );
		echo '<div class="nv-product-image ' . esc_attr( $product_classes ) . '">';
		echo '<div class="img-wrap">';
	}

	/**
	 * Markup before product content.
	 */
	public function neve_before_product_content() {
		$content_classes = apply_filters( 'neve_product_content_class', '' );
		echo '<div class="nv-product-content ' . esc_attr( $content_classes ) . '">';
	}

	/**
	 * Closing tag
	 */
	public function wrapper_close_div() {
		echo '</div>';
	}

	/**
	 * Wrap image buttons.
	 */
	public function wrap_image_buttons() {
		$quick_view     = get_theme_mod( 'neve_quick_view', 'none' );
		$button_display = get_theme_mod( 'neve_add_to_cart_display', 'none' );
		$classes        = '';
		$classes       .= ( $quick_view !== 'none' ? ' nv-quick-view-' . $quick_view : '' );
		$classes       .= ( $button_display !== 'none' ? ' nv-add-to-cart-' . $button_display : '' );
		$overlay        = $quick_view !== 'none' || $button_display === 'on_image' ? ' overlay ' : '';
		echo '<div class="nv-image-buttons ' . esc_attr( $classes ) . '">';
		echo '<a href="' . esc_url( get_permalink() ) . '" class="nv-product-overlay-link' . esc_attr( $overlay ) . '" tabindex="0" aria-label="' . esc_html( get_the_title() ) . ' ' . __( 'Product page', 'neve' ) . '">';
		echo '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span>';
		echo '</a>';
		do_action( 'neve_image_buttons' );
		echo '</div>';
	}

	/**
	 * Run functions.
	 */
	public function run() {
		$this->list_layout();
		$this->add_to_cart();
		$this->force_image_height();
		$this->elements_order();
		$this->sale_tag();
		$this->product_content_alignment();
		$this->display_products_filter();

		add_action( 'wp', array( $this, 'product_image_style' ) );
	}

	/**
	 * List layout display.
	 */
	public function list_layout() {
		$view = isset( $_GET['ref'] ) ? $_GET['ref'] : get_theme_mod( 'neve_product_card_layout', 'grid' );
		if ( ! empty( $view ) && $view === 'list' ) {
			add_filter(
				'neve_before_shop_classes',
				function ( $classes ) {
					return $classes . ' nv-list';
				}
			);
		}

		return;
	}

	/**
	 * Position the button after the content or on the image.
	 */
	public function add_to_cart() {
		$button_display = get_theme_mod( 'neve_add_to_cart_display', 'none' );
		if ( $button_display === 'none' ) {
			return;
		}
		if ( $button_display === 'after' ) {
			add_action(
				'woocommerce_after_shop_loop_item_title',
				function() {
					echo '<div class="flex-break"></div>';
				},
				997
			);
			add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 998 );
		}

		if ( $button_display === 'on_image' ) {
			add_filter(
				'neve_wrapper_class',
				function ( $classes ) {
					if ( strpos( $classes, 'nv-button-on-image' ) ) {
						return $classes;
					}

					return $classes . ' nv-button-on-image';
				}
			);
			add_action( 'neve_image_buttons', 'woocommerce_template_loop_add_to_cart', 12 );
		}
	}

	/**
	 * If force image height is enabled, add a class on image wrapper.
	 */
	private function force_image_height() {
		$should_load = get_theme_mod( 'neve_force_same_image_height' );
		if ( $should_load === false ) {
			return;
		}
		add_filter(
			'neve_wrapper_class',
			function ( $class ) {
				return $class . ' nv-same-image-height';
			}
		);
	}

	/**
	 * Reorder element in a product card.
	 */
	private function elements_order() {
		$elements = array(
			'category'          => array(
				'woocommerce_after_shop_loop_item_title',
				array( $this, 'render_product_category' ),
				10,
			),
			'title'             => array(
				'woocommerce_shop_loop_item_title',
				'woocommerce_template_loop_product_title',
				10,
			),
			'short-description' => array(
				'woocommerce_after_shop_loop_item_title',
				'woocommerce_template_single_excerpt',
				10,
			),
			'reviews'           => array( 'neve_rating', array( $this, 'reviews_markup' ), 6 ),
			'price'             => array(
				'woocommerce_after_shop_loop_item_title',
				'woocommerce_template_loop_price',
				10,
			),
		);
		foreach ( $elements as $element_name => $element ) {
			$element_hook     = $element[0];
			$element_function = $element[1];
			$element_priority = $element[2];
			remove_action( $element_hook, $element_function, $element_priority );
			if ( $element_name === 'reviews' ) {
				remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			}
		}

		$order_default_components = array( 'title', 'reviews', 'price' );
		$elements_order           = get_theme_mod( 'neve_layout_product_elements_order', json_encode( $order_default_components ) );
		$elements_order           = json_decode( $elements_order, true );
		$alignment                = get_theme_mod( 'neve_product_content_alignment', 'left' );
		foreach ( $elements_order as $index => $element ) {
			$priority         = ( $index + 1 ) * 5;
			$element_function = $elements[ $element ][1];
			if ( $element === 'title' ) {
				add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', $priority - 1 );
				add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', $priority + 1 );
			}
			if ( $element === 'price' && ! in_array( 'title', $elements_order, true ) && $alignment === 'inline' ) {
				continue;
			}
			add_action( 'woocommerce_after_shop_loop_item_title', $element_function, $priority );
		}
	}

	/**
	 *  Sale TAG hooks.
	 */
	private function sale_tag() {
		$tag_position = get_theme_mod( 'neve_sale_tag_position', 'inside' );
		if ( $tag_position !== 'inside' ) {
			add_filter(
				'woocommerce_sale_flash',
				function ( $value ) {
					return str_replace( 'onsale', 'onsale outside', $value );
				}
			);
		}

		$tag_alignment = get_theme_mod( 'neve_sale_tag_alignment', 'left' );
		if ( $tag_alignment !== 'left' ) {
			add_filter(
				'woocommerce_sale_flash',
				function ( $value ) {
					return str_replace( 'onsale', 'onsale right', $value );
				}
			);
		}

		$text = get_theme_mod( 'neve_sale_tag_text' );
		if ( ! empty( $text ) ) {
			add_filter(
				'woocommerce_sale_flash',
				function ( $value ) {
					$text = get_theme_mod( 'neve_sale_tag_text' );

					return '<span class="onsale">' . esc_html( $text ) . '</span>';
				}
			);
		}

		$sale_percentage = get_theme_mod( 'neve_enable_sale_percentage' );
		if ( $sale_percentage !== false ) {
			add_filter(
				'woocommerce_sale_flash',
				function ( $markup ) {
					global $product;

					$regular_price = (float) $product->get_regular_price(); // Regular price
					$sale_price    = (float) $product->get_price(); // Active price (the "Sale price" when on-sale)
					if ( $regular_price === (float) 0 ) {
						return $markup;
					}
					$saving_percentage = round( 100 - ( $sale_price / $regular_price * 100 ) );
					$tag_position      = get_theme_mod( 'neve_sale_tag_position', 'inside' );
					$tag_alignment     = get_theme_mod( 'neve_sale_tag_alignment', 'left' );
					$tag_format        = get_theme_mod( 'neve_sale_percentage_format', '{value}%' );
					if ( empty( $tag_format ) ) {
						$tag_format = '{value}%';
					}
					$saving_percentage = str_replace( '{value}', $saving_percentage, $tag_format );

					return '<span class="onsale ' . esc_attr( $tag_position ) . ' ' . esc_attr( $tag_alignment ) . '">' . esc_html( $saving_percentage ) . '</span>';
				}
			);
		}
	}

	/**
	 * Product content alignment.
	 */
	private function product_content_alignment() {
		$content_alignment = get_theme_mod( 'neve_product_content_alignment', 'left' );
		if ( $content_alignment === 'left' ) {
			return;
		}
		add_filter(
			'neve_product_content_class',
			function () {
				return get_theme_mod( 'neve_product_content_alignment', 'left' );
			}
		);
	}

	/**
	 * Product image style ( zoom/swipe ).
	 */
	public function product_image_style() {
		$image_style = get_theme_mod( 'neve_image_hover', 'none' );
		if ( neve_is_amp() ) {
			return;
		}
		if ( $image_style === 'none' ) {
			return;
		}
		add_filter(
			'neve_wrapper_class',
			function ( $class ) use ( $image_style ) {
				return $class . ' ' . $image_style;
			}
		);

		if ( $image_style === 'swipe' ) {
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'get_second_thumbnail' ) );
		}
	}

	/**
	 * Display product filter.
	 */
	public function display_products_filter() {
		$enable_filter = get_theme_mod( 'neve_enable_product_filter', true );
		if ( $enable_filter === true ) {
			return;
		}
		remove_action( 'nv_woo_header_bits', 'woocommerce_catalog_ordering', 30 );
	}

	/**
	 * Render product category.
	 */
	public function render_product_category() {
		global $product;
		echo '<div class="product_meta">';
		echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'neve' ) . ' ', '</span>' );
		echo '</div>';
	}

	/**
	 * Get the second thumbnail for swipe effect.
	 */
	public function get_second_thumbnail() {
		global $product;
		$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
		if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
			$gallery_attachment_ids = $product->get_gallery_image_ids();
			if ( ! empty( $gallery_attachment_ids[0] ) ) {
				echo wp_get_attachment_image( $gallery_attachment_ids[0], $image_size, '', 'data-secondary' );
			}
		}
	}

	/**
	 * Add wrapper for reviews.
	 */
	public function reviews_markup() {
		add_action( 'neve_rating', 'woocommerce_template_loop_rating', 5 );
		echo '<div class="advanced-rating-wraper">';
		do_action( 'neve_rating' );
		$this->advanced_reviews_markup();
		echo '</div>';
	}

	/**
	 * Advanced review.
	 */
	public function advanced_reviews_markup() {
		$advanced_reviews = get_theme_mod( 'neve_advanced_reviews' );
		if ( $advanced_reviews === false ) {
			return;
		}
		if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
			return;
		}
		global $product;
		$review_count = $product->get_review_count();
		if ( $review_count === 0 ) {
			return;
		}
		$average = $product->get_average_rating();

		echo '<span class="advanced-rating">';
		echo $average;
		if ( comments_open() ) {
			echo '<a href="' . get_permalink() . '#reviews" class="woocommerce-review-link" rel="nofollow">';
			echo '<span class="count">(' . esc_html( $review_count ) . ')</span>';
			echo '</a>';
		}
		echo '</span>';
	}

}
