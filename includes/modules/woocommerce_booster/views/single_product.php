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
 * Class Single_Product
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Single_Product extends Base_View {

	/**
	 * Content ordering mapping with priority and hooked function.
	 *
	 * @var array
	 */
	protected $mapping = array(
		'title'       => array(
			'initial' => 5,
			'method'  => 'woocommerce_template_single_title',
		),
		'reviews'     => array(
			'initial' => 10,
			'method'  => 'woocommerce_template_single_rating',
		),
		'price'       => array(
			'initial' => 10,
			'method'  => 'woocommerce_template_single_price',
		),
		'description' => array(
			'initial' => 20,
			'method'  => 'woocommerce_template_single_excerpt',
		),
		'add_to_cart' => array(
			'initial' => 30,
			'method'  => 'woocommerce_template_single_add_to_cart',
		),
		'meta'        => array(
			'initial' => 40,
			'method'  => 'woocommerce_template_single_meta',
		),
	);

	/**
	 * Default content ordering.
	 *
	 * @var array
	 */
	protected $default_order = array(
		'title',
		'price',
		'description',
		'add_to_cart',
		'meta',
	);

	/**
	 * Check if submodule should be loaded.
	 *
	 * @return bool
	 */
	private function should_load() {
		if ( ! class_exists( 'Woocommerce' ) ) {
			return false;
		}

		$post_id = get_the_ID();
		if ( ! $post_id || ! in_array( get_post_type( $post_id ), array( 'product', 'product_variation' ), true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize the module.
	 */
	public function init() {
		add_action( 'wp', array( $this, 'run' ), 10 );
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

		$this->remove_elements();
		$this->reorder_elements();
		$this->image_zoom_effect();
		$this->breadcrumbs();
		$this->tabs();
		$this->related_products();
		$this->upsells();
		$this->recently_viewed();
		$this->add_gallery_classes();
		$this->product_navigation();
		$this->product_navigation_style();
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_number' ), 20 );
		add_filter( 'body_class', array( $this, 'seamless_add_to_cart' ) );
	}

	/**
	 * Add the recently viewed products box.
	 */
	private function recently_viewed() {
		$status = get_theme_mod( 'neve_enable_related_viewed', false );

		if ( $status !== true || neve_is_amp() ) {
			return;
		}
		add_action(
			'woocommerce_after_single_product_summary',
			function () {
				the_widget(
					'WC_Widget_Recently_Viewed',
					array(
						'number' => 6,
						'title'  => __( 'Recently Viewed', 'neve' ),
					),
					array(
						'before_widget' => '<div class="nv-recently-viewed expanded">',
						'before_title'  => '<span class="close"></span><h5 class="title">',
						'after_widget'  => '</div>',
						'after_title'   => '</h5>',
						'widget_id'     => 'neve_related_widget_sp',
					)
				);
			}
		);
	}

	/**
	 * Add gallery classes
	 */
	public function add_gallery_classes() {
		if ( ! is_single() ) {
			return;
		}
		$gallery_layout = get_theme_mod( 'neve_single_product_gallery_layout', 'normal' );
		$gallery_slider = get_theme_mod( 'neve_enable_product_gallery_thumbnails_slider', false );
		$new_classes    = array();
		if ( $gallery_layout === 'left' ) {
			$new_classes[] = 'nv-left-gallery';
		}
		if ( $gallery_slider === true ) {
			$new_classes[] = 'nv-slider-gallery';
		}
		if ( $this->is_product_nav_active() ) {
			$new_classes[] = 'nv-has-product-nav';
		}

		add_filter(
			'body_class',
			function ( $classes ) use ( $new_classes ) {
				return array_merge( $classes, $new_classes );
			}
		);
	}

	/**
	 * Product navigation control function.
	 *
	 * @return bool
	 */
	private function product_navigation() {
		if ( ! $this->is_product_nav_active() ) {
			return false;
		}
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'render_product_navigation' ), 10 );

		return true;
	}

	/**
	 * Make the product navigation buttons to be customizable from secondary buttons controls
	 *
	 * @return void
	 */
	private function product_navigation_style() {

		add_filter(
			'neve_secondary_button_color_filter',
			function ( $color_setup ) {
				if ( array_key_exists( 'background-color', $color_setup ) ) {
					$color_setup['background-color']['selectors'] .= ', .nv-product-nav a.nv-button';
				}
				if ( array_key_exists( 'color', $color_setup ) ) {
					$color_setup['color']['selectors'] .= ', .nv-product-nav a.nv-button';
				}
				if ( array_key_exists( 'border-color', $color_setup ) ) {
					$color_setup['border-color']['selectors'] .= ', .nv-product-nav a.nv-button';
				}

				return $color_setup;
			}
		);

		add_filter(
			'neve_secondary_button_hover_color_filter',
			function ( $color_setup ) {
				if ( array_key_exists( 'color', $color_setup ) ) {
					$color_setup['color']['selectors'] .= ', .nv-product-nav a.nv-button:hover';
				}
				if ( array_key_exists( 'border-color', $color_setup ) ) {
					$color_setup['border-color']['selectors'] .= ', .nv-product-nav a.nv-button:hover';
				}

				return $color_setup;
			}
		);

		add_filter(
			'neve_secondary_button_border_radius_selectors_filter',
			function ( $border_selectors ) {
				return $border_selectors . ', .nv-product-nav a.nv-button';
			}
		);

		add_filter(
			'neve_secondary_button_border_radius_selector_filter',
			function ( $border_selectors ) {
				return $border_selectors . ', .nv-product-nav a.nv-button';
			}
		);

		add_filter(
			'neve_button_padding_selectors',
			function ( $css_selectors, $theme_mod ) {
				if ( $theme_mod !== 'neve_secondary_button_padding' ) {
					return $css_selectors;
				}

				return $css_selectors . ', .nv-product-nav a.nv-button';
			},
			10,
			2
		);
	}

	/**
	 * Check if product nav is active.
	 *
	 * @return bool
	 */
	private function is_product_nav_active() {
		$next_post          = get_next_post( true, '', 'product_cat' );
		$prev_post          = get_previous_post( true, '', 'product_cat' );
		$product_navigation = get_theme_mod( 'neve_enable_product_navigation', false );
		if ( $product_navigation !== true || ( ! is_a( $next_post, 'WP_Post' ) && ! is_a( $prev_post, 'WP_Post' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Render product navigation on single product page
	 *
	 * @return bool
	 */
	public function render_product_navigation() {

		$next_post = get_next_post( true, '', 'product_cat' );
		$prev_post = get_previous_post( true, '', 'product_cat' );

		// Next text
		$next_text = esc_html__( 'Previous Product', 'neve' );
		$next_text = apply_filters( 'neve_woo_nav_next_text', $next_text );

		// Prev text
		$prev_text = esc_html__( 'Next Product', 'neve' );
		$prev_text = apply_filters( 'neve_woo_nav_prev_text', $prev_text );

		echo '<div class="nv-product-nav-wrap">';
		woocommerce_show_product_sale_flash();
		echo '<ul class="nv-product-nav">';
		if ( is_a( $next_post, 'WP_Post' ) ) {
			echo '<li class="next-li">';
			echo '<a href="' . get_the_permalink( $next_post->ID ) . '" class="nv-button nv-button-secondary next" rel="next">';
			echo '<svg width="14" height="14" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"/></svg>';
			echo '</a>';
			echo '<a href="' . get_the_permalink( $next_post->ID ) . '" class="nv-nav-text next-text">';
			echo esc_attr( $next_text );
			echo '</a>';
			echo '</li>';
		}

		if ( is_a( $prev_post, 'WP_Post' ) ) {
			echo '<li class="prev-li">';
			echo '<a href="' . get_the_permalink( $prev_post->ID ) . '" class="nv-nav-text prev-text">';
			echo esc_attr( $prev_text );
			echo '</a>';
			echo '<a href="' . get_the_permalink( $prev_post->ID ) . '" class="nv-button nv-button-secondary prev" rel="next">';
			echo '<svg width="14" height="14" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>';
			echo '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';

		return true;
	}

	/**
	 * Remove the elements from single product.
	 */
	private function remove_elements() {
		array_walk(
			$this->mapping,
			function ( $args ) {
				remove_action( 'woocommerce_single_product_summary', $args['method'], $args['initial'] );
			}
		);
	}

	/**
	 * Reorder the elements on single product.
	 */
	private function reorder_elements() {
		$order = get_theme_mod( 'neve_single_product_elements_order', json_encode( $this->default_order ) );

		$order = json_decode( $order );

		if ( ! is_array( $order ) ) {
			$order = $this->default_order;
		}

		array_walk(
			$order,
			function ( $value, $index ) {
				add_action( 'woocommerce_single_product_summary', $this->mapping[ $value ]['method'], $index );
			}
		);
	}

	/**
	 * Toggle breadcrumbs
	 */
	private function breadcrumbs() {
		$enable_crumbs = get_theme_mod( 'neve_enable_product_breadcrumbs', true );

		if ( $enable_crumbs === true ) {
			return;
		}

		remove_all_actions( 'neve_before_shop_loop_content' );
	}

	/**
	 * Toggle tabs
	 */
	private function tabs() {
		$enable_tabs = get_theme_mod( 'neve_enable_product_tabs', true );

		if ( $enable_tabs === true ) {
			return;
		}

		add_filter( 'woocommerce_product_tabs', '__return_empty_array', PHP_INT_MAX );
		add_filter(
			'post_class',
			function ( $classes, $class = '', $post_id = 0 ) {
				$classes[] = 'nv-tabless-product';

				return $classes;
			},
			20,
			3
		);
		add_action(
			'woocommerce_after_single_product_summary',
			function () {
				echo '<div class="nv-related-clearfix"></div>';
			},
			5
		);
	}

	/**
	 * Toggle image zoom effect
	 */
	private function image_zoom_effect() {
		$enable_zoom = get_theme_mod( 'neve_enable_product_image_zoom_effect', true );

		if ( $enable_zoom === true ) {
			return;
		}

		remove_theme_support( 'wc-product-gallery-zoom' );
	}

	/**
	 * Toggle related products
	 */
	private function related_products() {
		$enable_related_prods = get_theme_mod( 'neve_enable_product_related', true );

		if ( $enable_related_prods === true ) {
			return;
		}
		add_filter( 'woocommerce_related_products', '__return_empty_array' );
	}

	/**
	 * Toggle upsells.
	 */
	private function upsells() {
		$enable_upsells = get_theme_mod( 'neve_enable_product_upsells', true );

		if ( $enable_upsells === true ) {
			return;
		}
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

	/**
	 * Change related products number.
	 *
	 * @param array $args query parameters for related products.
	 *
	 * @return mixed
	 */
	public function related_products_number( $args ) {
		$related_count = get_theme_mod( 'neve_single_product_related_count', 4 );
		$related_cols  = get_theme_mod( 'neve_single_product_related_columns', 4 );
		if ( empty( $related_count ) && empty( $related_cols ) ) {
			return $args;
		}

		if ( ! empty( $related_cols ) ) {
			$args['columns'] = absint( $related_cols );
		}

		if ( ! empty( $related_count ) ) {
			$args['posts_per_page'] = absint( $related_count );
		}

		return $args;
	}

	/**
	 * Add the seamless add to cart class on body.
	 *
	 * @param array $classes Body classes.
	 *
	 * @return array
	 */
	public function seamless_add_to_cart( $classes ) {

		if ( ! is_product() ) {
			return $classes;
		}

		$product_id = get_the_ID();
		$product    = wc_get_product( $product_id );
		if ( $product->is_type( 'external' ) ) {
			return $classes;
		}

		if ( $product->is_type( 'grouped' ) ) {
			return $classes;
		}

		$is_seamless_add_to_cart = get_theme_mod( 'neve_enable_seamless_add_to_cart', false );
		if ( $is_seamless_add_to_cart === true ) {
			$classes[] = 'seamless-add-to-cart';
		}

		return $classes;
	}
}
