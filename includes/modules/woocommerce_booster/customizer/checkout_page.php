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

/**
 * Class Checkout_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Checkout_Page extends Base_Customizer {

	/**
	 * Add customizer controls
	 */
	public function add_controls() {
		$this->group_controls();
		$this->add_checkboxes();
		$this->add_style_control();
	}

	/**
	 * Add control groups to better organize the customizer.
	 */
	private function group_controls() {
		$this->add_control(
			new Control(
				'neve_woo_checkout_settings_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'General', 'neve' ),
					'section'          => 'woocommerce_checkout',
					'priority'         => 0,
					'class'            => 'woo-checkout-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 8,
					'expanded'         => true,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_checkout_settings_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Style', 'neve' ),
					'section'          => 'woocommerce_checkout',
					'priority'         => 500,
					'class'            => 'neve-checkout-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 5,
					'expanded'         => true,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Add checkboxes controls
	 *
	 * - fixed order summary
	 * - distraction free toggle
	 * - order note toggle
	 * - checkout coupon toggle
	 * - labels to placeholders toggle
	 */
	private function add_checkboxes() {
		$checkboxes = array(
			'neve_enable_checkout_fixed_order'  => array(
				'default'  => false,
				'priority' => 510,
				'label'    => __( 'Enable Fixed Order Box', 'neve' ),
			),
			'neve_enable_checkout_order_note'   => array(
				'default'  => true,
				'priority' => 520,
				'label'    => __( 'Show Order Note', 'neve' ),
			),
			'neve_enable_checkout_coupon'       => array(
				'default'  => true,
				'priority' => 530,
				'label'    => __( 'Show Coupon', 'neve' ),
			),
			'neve_checkout_labels_placeholders' => array(
				'default'  => false,
				'priority' => 540,
				'label'    => __( 'Use Labels as Placeholders', 'neve' ),
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
						'section'         => 'woocommerce_checkout',
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
	private function add_style_control() {
		$this->add_control(
			new Control(
				'neve_checkout_page_style',
				array(
					'default'           => 'normal',
					'sanitize_callback' => array( $this, 'sanitize_checkout_style' ),
				),
				array(
					'label'    => esc_html__( 'Style', 'neve' ),
					'section'  => 'woocommerce_checkout',
					'priority' => 550,
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
	 * Sanitize the cart style control
	 *
	 * @param string $value control value.
	 *
	 * @return string
	 */
	public function sanitize_checkout_style( $value ) {
		$allowed = array( 'normal', 'boxed' );

		if ( ! in_array( $value, $allowed, true ) ) {
			return 'normal';
		}

		return $value;
	}

}
