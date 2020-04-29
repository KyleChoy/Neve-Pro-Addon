<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-06
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Scroll_To_Top\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Section;
use Neve\Customizer\Types\Control;

/**
 * Class Scroll_To_Top
 *
 * @package Neve_Pro\Customizer\Options
 */
class Scroll_To_Top extends Base_Customizer {

	/**
	 * Add customizer section and controls
	 */
	public function add_controls() {
		$this->scroll_to_top_section();
		$this->scroll_to_top_options();
	}

	/**
	 * Register customizer section for the module
	 */
	public function scroll_to_top_section() {

		$this->add_section(
			new Section(
				'neve_scroll_to_top',
				array(
					'priority' => 80,
					'title'    => esc_html__( 'Scroll To Top', 'neve' ),
					'panel'    => 'neve_layout',
				)
			)
		);

	}

	/**
	 * Register option toggle in customizer
	 */
	public function scroll_to_top_options() {

		/**
		 * Button side
		 */
		$this->add_control(
			new Control(
				'neve_scroll_to_top_side',
				array(
					'default'           => 'right',
					'sanitize_callback' => array( $this, 'sanitize_scroll_to_top_side' ),
				),
				array(
					'label'    => esc_html__( 'Choose Side', 'neve' ),
					'section'  => 'neve_scroll_to_top',
					'priority' => 10,
					'type'     => 'select',
					'choices'  => array(
						'left'  => esc_html__( 'Left', 'neve' ),
						'right' => esc_html__( 'Right', 'neve' ),
					),
				)
			)
		);

		/**
		 * Offset
		 */
		$this->add_control(
			new Control(
				'neve_scroll_to_top_offset',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 0,
				),
				array(
					'label'       => esc_html__( 'Offset (px)', 'neve' ),
					'description' => esc_html__( 'Show button when page is scrolled x pixels.', 'neve' ),
					'section'     => 'neve_scroll_to_top',
					'step'        => 1,
					'input_attr'  => array(
						'min'     => 0,
						'max'     => 1000,
						'default' => 0,
					),
					'input_attrs' => array(
						'min'        => 0,
						'max'        => 1000,
						'defaultVal' => 0,
					),
					'priority'    => 15,
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);

		/**
		 * Hide on mobile
		 */
		$this->add_control(
			new Control(
				'neve_scroll_to_top_on_mobile',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'    => esc_html__( 'Disable On Mobile', 'neve' ),
					'section'  => 'neve_scroll_to_top',
					'type'     => 'neve_toggle_control',
					'priority' => 20,
				),
				'Neve\Customizer\Controls\Checkbox'
			)
		);

		/**
		 * Style accordion
		 */
		$this->add_control(
			new Control(
				'neve_scroll_to_top_style',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Style', 'neve' ),
					'section'          => 'neve_scroll_to_top',
					'priority'         => 25,
					'class'            => 'advanced-sidebar-accordion',
					'accordion'        => true,
					'expanded'         => true,
					'controls_to_wrap' => 6,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$color_controls = array(
			'neve_scroll_to_top_icon_color'             => array(
				'default'  => '#ffffff',
				'priority' => 30,
				'label'    => esc_html__( 'Icon Color', 'neve' ),
			),
			'neve_scroll_to_top_icon_hover_color'       => array(
				'default'  => '#ffffff',
				'priority' => 35,
				'label'    => esc_html__( 'Icon Hover Color', 'neve' ),
			),
			'neve_scroll_to_top_background_color'       => array(
				'default'  => '#0366d6',
				'priority' => 40,
				'label'    => esc_html__( 'Background Color', 'neve' ),
			),
			'neve_scroll_to_top_background_hover_color' => array(
				'default'  => '#0366d6',
				'priority' => 45,
				'label'    => esc_html__( 'Background Hover Color', 'neve' ),
			),
		);

		/**
		 * Color controls
		 */
		foreach ( $color_controls as $control_id => $control_properties ) {
			$this->add_control(
				new Control(
					$control_id,
					array(
						'sanitize_callback' => 'neve_sanitize_colors',
						'default'           => $control_properties['default'],
					),
					array(
						'label'    => $control_properties['label'],
						'section'  => 'neve_scroll_to_top',
						'priority' => $control_properties['priority'],
					),
					'\Neve\Customizer\Controls\React\Color'
				)
			);
		}

		/**
		 * Icon size
		 */
		$this->add_control(
			new Control(
				'neve_scroll_to_top_icon_size',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => '{ "mobile": "16", "tablet": "16", "desktop": "16" }',
				),
				[
					'label'       => esc_html__( 'Icon Size (px)', 'neve' ),
					'section'     => 'neve_scroll_to_top',
					'media_query' => true,
					'step'        => 1,
					'input_attr'  => [
						'mobile'  => [
							'min'     => 10,
							'max'     => 50,
							'default' => 16,
						],
						'tablet'  => [
							'min'     => 10,
							'max'     => 50,
							'default' => 16,
						],
						'desktop' => [
							'min'     => 10,
							'max'     => 50,
							'default' => 16,
						],
					],
					'input_attrs' => [
						'step'       => 1,
						'min'        => 10,
						'max'        => 50,
						'defaultVal' => [
							'mobile'  => 16,
							'tablet'  => 16,
							'desktop' => 16,
						],
						'units'      => [ 'px' ],
					],
					'priority'    => 50,
				],
				class_exists( 'Neve\Customizer\Controls\React\Responsive_Range', false ) ? 'Neve\Customizer\Controls\React\Responsive_Range' : 'Neve\Customizer\Controls\Range'
			)
		);

		/**
		 * Button border radius
		 */
		$this->add_control(
			new Control(
				'neve_scroll_to_top_border_radius',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 3,
				),
				array(
					'label'       => esc_html__( 'Border Radius', 'neve' ),
					'section'     => 'neve_scroll_to_top',
					'step'        => 1,
					'input_attr'  => array(
						'min'     => 0,
						'max'     => 50,
						'default' => 3,
					),
					'input_attrs' => array(
						'min'        => 0,
						'max'        => 50,
						'defaultVal' => 3,
					),
					'priority'    => 55,
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);
	}

	/**
	 * Sanitize scroll to top side
	 *
	 * @param string $value - value of the control.
	 *
	 * @return string
	 */
	public function sanitize_scroll_to_top_side( $value ) {
		$allowed_values = array( 'left', 'right' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'right';
		}

		return esc_html( $value );
	}

}
