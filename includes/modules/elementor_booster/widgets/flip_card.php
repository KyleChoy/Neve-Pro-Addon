<?php
/**
 * Elementor Flip Card Widget.
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Scheme_Typography;

/**
 * Class Flipcard
 *
 * @package ThemeIsle\ElementorExtraWidgets
 */
class Flip_Card extends Elementor_Booster_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_flipcard';
	}

	/**
	 * Widget Label.
	 *
	 * @return string
	 */
	public function get_title() {
		return 'Flip Card';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-share-square';
	}

	/**
	 * Retrieve the list of scripts the flip card widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			return [ 'neb-flip-card-script' ];
		}
		return [];
	}

	/**
	 * Render function.
	 */
	public function render() {
		$settings = $this->get_settings();

		echo '<div class="eaw-flipcard-container">';
		echo '<div class="eaw-flipcard">';
		echo '<div class="eaw-flipcard-front">';
		echo '<div class="eaw-flipcard-content-wrap">';
		echo $settings['frontside_content'];
		echo '</div>';
		echo '</div>';

		echo '<div class="eaw-flipcard-back">';
		echo '<div class="eaw-flipcard-content-wrap">';

		echo $settings['backside_content'];

		echo '<div class="eaw-flipcard-buttons">';

		foreach ( $settings['buttons'] as $button ) {
			if ( ! empty( $button['link']['url'] ) ) {
				$link_props = ' href="' . esc_url( $button['link']['url'] ) . '" ';
				if ( $button['link']['is_external'] === 'on' ) {
					$link_props .= ' target="_blank" ';
				}
				if ( $button['link']['nofollow'] === 'on' ) {
					$link_props .= ' rel="nofollow" ';
				}
				echo '<a class="eaw-flipcard-button" ' . $link_props . '>' . wp_kses_post( $button['text'] ) . '</a>';
			}
		}

		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'eaw_front_side_section',
			array(
				'label' => esc_html__( 'Front Side', 'neve' ),
			)
		);

		$this->start_controls_tabs( 'front_side_control_tabs' );
		$this->start_controls_tab( 'front_side_content_tab', array( 'label' => __( 'Content', 'neve' ) ) );
		// Content
		$this->add_control(
			'frontside_content',
			array(
				'label'       => __( 'Front Side Content', 'neve' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => __( 'The content of the first side', 'neve' ),
				'show_label'  => true,
				'default'     => '<p style="text-align: center;"><strong>Dribbble</strong></p><h6 style="text-align: center; font-weight: 600;">"Dribbble just acquired Crew, a very interesting startup..."</h6><p style="text-align: center;">Don\'t be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...</p>',
			)
		);

		$this->add_control(
			'front_side_color',
			array(
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaw-flipcard-front, {{WRAPPER}} .eaw-flipcard-front h1, {{WRAPPER}} .eaw-flipcard-front h2, {{WRAPPER}} .eaw-flipcard-front h3, {{WRAPPER}} .eaw-flipcard-front h4, {{WRAPPER}} .eaw-flipcard-front h5, {{WRAPPER}} .eaw-flipcard-front h6' => 'color: {{VALUE}};',
				),
				'default'   => '#ffffff',
			)
		);
		$this->end_controls_tab();
		// Background
		$this->start_controls_tab( 'front_side_background_tab', array( 'label' => __( 'Background', 'neve' ) ) );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'frontside_backround_color',
				'types'          => array( 'classic', 'gradient' ),
				'fields_options' => array(
					'color' => array(
						'label'     => _x( 'Color', 'Background Control', 'neve' ),
						'type'      => Controls_Manager::COLOR,
						'title'     => _x( 'Background Color', 'Background Control', 'neve' ),
						'selectors' => array(
							'{{WRAPPER}} .eaw-flipcard > .eaw-flipcard-front' => 'background: {{VALUE}};',
						),
					),
				),
				'selector'       => '{{WRAPPER}} .eaw-flipcard > .eaw-flipcard-front',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section(); // end front side

		$this->start_controls_section(
			'eaw_backside_section',
			array(
				'label' => esc_html__( 'Back Side', 'neve' ),
			)
		);

		$this->start_controls_tabs( 'back_side_control_tabs' );
		$this->start_controls_tab( 'back_side_content_tab', array( 'label' => __( 'Content', 'neve' ) ) );
		// Content
		$this->add_control(
			'backside_content',
			array(
				'label'       => __( 'Back Side Content', 'neve' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => __( 'The content of the second side', 'neve' ),
				'show_label'  => true,
				'default'     => '<p style="text-align: center;"<strong>Dribbble</strong></p><h6 style="text-align: center; font-weight: 600;">"Dribbble just acquired Crew, a very interesting startup..."</h6>',
			)
		);
		$this->add_control(
			'back_side_color',
			array(
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaw-flipcard-back, {{WRAPPER}} .eaw-flipcard-back h1, {{WRAPPER}} .eaw-flipcard-back h2, {{WRAPPER}} .eaw-flipcard-back h3, {{WRAPPER}} .eaw-flipcard-back h4, {{WRAPPER}} .eaw-flipcard-back h5, {{WRAPPER}} .eaw-flipcard-back h6, {{WRAPPER}} .eaw-flipcard-back .eaw-flipcard-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eaw-flipcard-back .eaw-flipcard-button'                                                                                                                                                                                                                                                   => 'border-color: {{VALUE}}; color: {{VALUE}}',
				),
				'default'   => '#ffffff',
			)
		);
		$this->add_control(
			'buttons',
			array(
				'label'       => __( 'Buttons', 'neve' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'text' => __( 'Read', 'neve' ),
						'link' => array( 'url' => '#' ),
					),
					array(
						'text' => __( 'Bookmark', 'neve' ),
						'link' => array( 'url' => '#' ),
					),
				),
				'fields'      => array(
					array(
						'type'        => Controls_Manager::TEXT,
						'name'        => 'text',
						'label_block' => true,
						'label'       => __( 'Text', 'neve' ),
						'default'     => __( 'Click Me', 'neve' ),
					),
					array(
						'type'        => Controls_Manager::URL,
						'name'        => 'link',
						'label'       => __( 'Link to', 'neve' ),
						'separator'   => 'before',
						'placeholder' => __( 'https://example.com', 'neve' ),
						'default'     => array( 'url' => '#' ),
					),
				),
				'title_field' => '{{text}}',
			)
		);

		$this->end_controls_tab();
		// Background.
		$this->start_controls_tab( 'back_side_background_tab', array( 'label' => __( 'Background', 'neve' ) ) );
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'backside_background_color',
				'types'          => array( 'classic', 'gradient' ),
				'fields_options' => array(
					'color' => array(
						'label'     => _x( 'Color', 'Background Control', 'neve' ),
						'type'      => Controls_Manager::COLOR,
						'title'     => _x( 'Background Color', 'Background Control', 'neve' ),
						'selectors' => array(
							'{{WRAPPER}} .eaw-flipcard > .eaw-flipcard-back' => 'background: {{VALUE}};',
						),
					),
				),
				'selector'       => '{{WRAPPER}} .eaw-flipcard > .eaw-flipcard-back',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section(); // end back side

		$this->start_controls_section(
			'options',
			array(
				'label' => __( 'Options', 'neve' ),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'     => __( 'Height', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'default'   => array(
					'size' => 300,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-flipcard, {{WRAPPER}} .eaw-flipcard-front,  {{WRAPPER}} .eaw-flipcard-back' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'eaw_flipcard_style',
			array(
				'label' => esc_html__( 'Content', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		// Items internal padding.
		$this->add_control(
			'front_side_padding',
			array(
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eaw-flipcard .eaw-flipcard-front, {{WRAPPER}} .eaw-flipcard .eaw-flipcard-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'front_side_font_family_new',
				'label'    => __( 'Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-flipcard .eaw-flipcard-front, {{WRAPPER}} .eaw-flipcard .eaw-flipcard-back',
			]
		);

		$this->add_control(
			'border_radius',
			array(
				'label'     => __( 'Border Radius', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'   => array(
					'size' => 6,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-flipcard, {{WRAPPER}} .eaw-flipcard-front, {{WRAPPER}} .eaw-flipcard-back' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .eaw-flipcard-front, {{WRAPPER}} .eaw-flipcard-back',
			)
		);
		$this->end_controls_section();
	}
}
