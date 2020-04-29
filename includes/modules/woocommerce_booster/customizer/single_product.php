<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-02-11
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve\Customizer\Types\Section;

/**
 * Class Single_Product
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Single_Product extends Base_Customizer {
	/**
	 * Add customizer controls
	 */
	public function add_controls() {
		$this->add_order_control();
		$this->add_gallery_layout_control();
		$this->add_checkboxes();
		$this->add_related_count();
		$this->add_related_accordion();
	}

	/**
	 * Add related products number control.
	 */
	private function add_related_count() {
		$this->add_control(
			new Control(
				'neve_single_product_related_count',
				array(
					'sanitize_callback' => 'absint',
				),
				array(
					'label'           => esc_html__( 'Number of Related Products', 'neve' ),
					'section'         => 'neve_single_product_layout',
					'default'         => 4,
					'type'            => 'number',
					'input_attrs'     => array(
						'min' => 1,
						'max' => 20,
					),
					'priority'        => 59,
					'active_callback' => array( $this, 'hide_if_related_disabled' ),
				)
			)
		);
		$this->add_control(
			new Control(
				'neve_single_product_related_columns',
				array(
					'sanitize_callback' => 'absint',
				),
				array(
					'label'           => esc_html__( 'Number of Columns', 'neve' ),
					'section'         => 'neve_single_product_layout',
					'default'         => 4,
					'type'            => 'number',
					'input_attrs'     => array(
						'min' => 1,
						'max' => 6,
					),
					'priority'        => 60,
					'active_callback' => array( $this, 'hide_if_related_disabled' ),
				)
			)
		);
	}

	/**
	 * Active callback for when related is disabled.
	 *
	 * @return mixed|string
	 */
	public function hide_if_related_disabled() {
		return get_theme_mod( 'neve_enable_product_related', true );
	}

	/**
	 * Add component ordering.
	 */
	private function add_order_control() {
		$order_default_components = array(
			'title',
			'price',
			'description',
			'add_to_cart',
			'meta',
		);

		$components = array(
			'title'       => __( 'Title', 'neve' ),
			'reviews'     => __( 'Reviews', 'neve' ),
			'price'       => __( 'Price', 'neve' ),
			'description' => __( 'Short Description', 'neve' ),
			'add_to_cart' => __( 'Add to cart', 'neve' ),
			'meta'        => __( 'Meta', 'neve' ),
		);

		$this->add_control(
			new Control(
				'neve_single_product_elements_order',
				array(
					'sanitize_callback' => array( $this, 'sanitize_elements_ordering' ),
					'default'           => json_encode( $order_default_components ),
				),
				array(
					'label'      => esc_html__( 'Elements Order', 'neve' ),
					'section'    => 'neve_single_product_layout',
					'type'       => 'ordering',
					'components' => $components,
					'priority'   => 10,
				),
				'Neve\Customizer\Controls\Ordering'
			)
		);
	}

	/**
	 * Sanitize components ordering
	 *
	 * @param string $value json encoded array.
	 *
	 * @return string
	 */
	public function sanitize_elements_ordering( $value ) {

		$allowed = array(
			'title',
			'reviews',
			'price',
			'description',
			'add_to_cart',
			'meta',
		);

		if ( empty( $value ) ) {
			return json_encode( $allowed );
		}

		$decoded = json_decode( $value, true );
		if ( ! is_array( $decoded ) ) {
			return json_encode( $allowed );
		}

		foreach ( $decoded as $val ) {
			if ( ! in_array( $val, $allowed, true ) ) {
				return json_encode( $allowed );
			}
		}

		return $value;
	}

	/**
	 * Add gallery layout control.
	 */
	private function add_gallery_layout_control() {
		$this->add_control(
			new Control(
				'neve_single_product_gallery_layout',
				array(
					'default'           => 'normal',
					'sanitize_callback' => array( $this, 'sanitize_gallery_layout' ),
				),
				array(
					'label'    => esc_html__( 'Gallery Layout', 'neve' ),
					'section'  => 'neve_single_product_layout',
					'priority' => 15,
					'choices'  => array(
						'normal' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAACVBMVEUAyv/V1dX////o4eoDAAAAcUlEQVR4Ae3ZMQoAMQhFQd37H3pJbxOIIDKv/NW0Ynwjw1rPisZ2sbCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCmhOWzf/Ysut37WFhYWFhYWFhYWFhYXTnIsLLo5Y6FhYWFhYWFhYWF5U7EmtYP2ZZKOeVUSPIAAAAASUVORK5CYII=',
						),
						'left'   => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAACVBMVEUAyv/V1dX////o4eoDAAAAc0lEQVR42u3bMQoAIAwEwej/H21hG5AQEJHZ0mqqwBXGfDKs71mRtN/7YWFhYWFhYWFhYWH1WNWwsLBus0YSFhYWFhYWFhYWFtaBZWJgYWG5W1hYWFhYWFhYWFhWNRYWlruFhYWFhYWFhYWFZVX7+4pVaAGXVUBZRr/2PwAAAABJRU5ErkJggg==',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);
	}

	/**
	 * Sanitize gallery layout control.
	 *
	 * @param string $value the value.
	 *
	 * @return string
	 */
	public function sanitize_gallery_layout( $value ) {
		$allowed_values = array( 'normal', 'left' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'normal';
		}

		return esc_html( $value );
	}

	/**
	 * Add checkbox controls.
	 *
	 * - image zoom toggle
	 * - breadcrumbs toggle
	 * - product tabs toggle
	 * - related products toggle
	 * - up-sells toggle
	 * - related products slider toggle
	 * - related viewed products box toggle
	 */
	private function add_checkboxes() {
		$checkboxes = array(
			'neve_enable_product_gallery_thumbnails_slider' => array(
				'default'  => false,
				'priority' => 20,
				'label'    => __( 'Enable Gallery Thumbnails Slider', 'neve' ),
			),
			'neve_enable_product_image_zoom_effect' => array(
				'default'  => true,
				'priority' => 25,
				'label'    => __( 'Enable Image Zoom Effect', 'neve' ),
			),
			'neve_enable_product_breadcrumbs'       => array(
				'default'  => true,
				'priority' => 30,
				'label'    => __( 'Show Breadcrumbs', 'neve' ),
			),
			'neve_enable_product_tabs'              => array(
				'default'  => true,
				'priority' => 35,
				'label'    => __( 'Show Product Tabs', 'neve' ),
			),
			'neve_enable_seamless_add_to_cart'      => array(
				'default'  => false,
				'priority' => 36,
				'label'    => __( 'Enable Seamless Add to Cart', 'neve' ),
			),
			'neve_enable_product_upsells'           => array(
				'default'  => true,
				'priority' => 45,
				'label'    => __( 'Show Upsell Products', 'neve' ),
			),
			'neve_enable_related_viewed'            => array(
				'default'  => false,
				'priority' => 50,
				'label'    => __( 'Show Recently Viewed Products', 'neve' ),
			),
			'neve_enable_product_navigation'        => array(
				'default'  => false,
				'priority' => 55,
				'label'    => __( 'Enable Product Navigation', 'neve' ),
			),
			'neve_enable_product_related'           => array(
				'default'  => true,
				'priority' => 57,
				'label'    => __( 'Show Related Products', 'neve' ),
			),
			'neve_enable_product_related_slider'    => array(
				'default'         => false,
				'priority'        => 58,
				'active_callback' => array( $this, 'hide_if_related_disabled' ),
				'label'           => __( 'Enable Related Products Slider', 'neve' ),
			),
		);

		foreach ( $checkboxes as $id => $args ) {
			$this->add_control(
				new Control(
					$id,
					array(
						'default'           => $args['default'],
						'sanitize_callback' => 'neve_sanitize_checkbox',
					),
					array(
						'label'           => $args['label'],
						'section'         => 'neve_single_product_layout',
						'type'            => 'neve_toggle_control',
						'priority'        => $args['priority'],
						'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : '__return_true',
					)
				)
			);
		}
	}

	/**
	 * Adds related products settings accordion
	 */
	private function add_related_accordion() {
		$this->add_control(
			new Control(
				'neve_related_products_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Related Products', 'neve' ),
					'section'          => 'neve_single_product_layout',
					'priority'         => 56,
					'class'            => 'related-products-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 4,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}
}
