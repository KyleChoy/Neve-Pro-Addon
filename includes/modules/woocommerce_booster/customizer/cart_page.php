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
 * Class Cart_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Cart_Page extends Base_Customizer {

	/**
	 * Add customizer controls
	 */
	public function add_controls() {
		$this->add_cart_page_section();
		$this->add_layout_style_controls();
		$this->add_checkboxes();
	}


	/**
	 * Add cart page section in customizer.
	 */
	private function add_cart_page_section() {
		$this->add_section(
			new Section(
				'neve_cart_page_layout',
				array(
					'priority' => 70,
					'title'    => esc_html__( 'Cart Page', 'neve' ),
					'panel'    => 'woocommerce',
				)
			)
		);
	}

	/**
	 * Add checkbox controls.
	 *
	 * - fixed total box
	 * - cart upsells toggle
	 */
	private function add_checkboxes() {
		$checkboxes = array(
			'neve_enable_cart_fixed_total' => array(
				'default'         => false,
				'priority'        => 20,
				'label'           => __( 'Enable Fixed Total Box', 'neve' ),
				'active_callback' => function () {
					return get_theme_mod( 'neve_cart_page_layout', 'normal' ) === 'side-by-side';
				},
			),
			'neve_enable_cart_upsells'     => array(
				'default'  => true,
				'priority' => 30,
				'label'    => __( 'Show Cross-Sell Products', 'neve' ),
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
						'section'         => 'neve_cart_page_layout',
						'type'            => 'neve_toggle_control',
						'priority'        => $args['priority'],
						'active_callback' => isset( $args['active_callback'] ) ? $args['active_callback'] : '__return_true',
					)
				)
			);
		}
	}

	/**
	 * Add gallery layout control.
	 */
	private function add_layout_style_controls() {
		$this->add_control(
			new Control(
				'neve_cart_page_layout',
				array(
					'default'           => 'normal',
					'sanitize_callback' => array( $this, 'sanitize_cart_layout' ),
				),
				array(
					'label'    => esc_html__( 'Layout', 'neve' ),
					'section'  => 'neve_cart_page_layout',
					'priority' => 15,
					'choices'  => array(
						'normal'       => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAADFBMVEUAyv/V1dXs7Oz///9ui+0vAAAAdklEQVR4Ae3aIQ4AIAwDwAH//zMKMbdkiImrIqgTTYogzshgYWFhzWZhYe1esLCw/rNWSr56Z6zhLJXHwsLCMj5YKo+FhYVlfLAqUXksLCzjg6XyWFhYWDYRS+WxsHyUwopesLCwsLCwsLCwsLzlsbCwsLCwKrmkTJxkTK+KIAAAAABJRU5ErkJggg==',
						),
						'side-by-side' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAADFBMVEUAyv/V1dXs7Oz///9ui+0vAAAAf0lEQVR4Ae3buwrAIAyG0V7e/52dRLIoRJQM559Kh3KGwDf1+UsOCwsLqxgLC+vLbHx7upMsLCwsrDcsvurPuywsJ4+FhYWFpYlS7eSxsLCwxAfLyWNhYWFJNZZUY2FhYWmi+GgiVn5YWFhY9+OzHpbbwsLCwvIvBhYWFhYWVgOmcpE9Ng+lAQAAAABJRU5ErkJggg==',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);

		$this->add_control(
			new Control(
				'neve_cart_page_style',
				array(
					'default'           => 'normal',
					'sanitize_callback' => array( $this, 'sanitize_cart_style' ),
				),
				array(
					'label'    => esc_html__( 'Style', 'neve' ),
					'section'  => 'neve_cart_page_layout',
					'priority' => 25,
					'choices'  => array(
						'normal' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAADFBMVEUAyv/V1dXs7Oz///9ui+0vAAAAdklEQVR4Ae3aIQ4AIAwDwAH//zMKMbdkiImrIqgTTYogzshgYWFhzWZhYe1esLCw/rNWSr56Z6zhLJXHwsLCMj5YKo+FhYVlfLAqUXksLCzjg6XyWFhYWDYRS+WxsHyUwopesLCwsLCwsLCwsLzlsbCwsLCwKrmkTJxkTK+KIAAAAABJRU5ErkJggg==',
						),
						'boxed'  => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAADFBMVEUu1P/V1dXg4OD///+b75jGAAAAdklEQVR4Ae3aIQ4AIAwDwAH//zMKMbdkiImrIqgTTYog9shgYWFhzWZhYZ1esLCw/rNWSr56Z6zhLJXHwsLCMj5YKo+FhYVlfLAqUXksLCzjg6XyWFhYWDYRS+WxsHyUwopesLCwsLCwsLCwsLzlsbCwsLCwKrlDt38YT8wNagAAAABJRU5ErkJggg==',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);
	}

	/**
	 * Sanitize the cart layout control
	 *
	 * @param string $value control value.
	 *
	 * @return string
	 */
	public function sanitize_cart_layout( $value ) {
		$allowed = array( 'normal', 'side-by-side' );

		if ( ! in_array( $value, $allowed, true ) ) {
			return 'normal';
		}

		return $value;
	}

	/**
	 * Sanitize the cart style control
	 *
	 * @param string $value control value.
	 *
	 * @return string
	 */
	public function sanitize_cart_style( $value ) {
		$allowed = array( 'normal', 'boxed' );

		if ( ! in_array( $value, $allowed, true ) ) {
			return 'normal';
		}

		return $value;
	}

}
