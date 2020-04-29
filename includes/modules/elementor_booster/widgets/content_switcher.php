<?php
/**
 * Elementor Content Switcher Widget
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Controls_Manager;
use Elementor\Frontend;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;

/**
 * Class Content_Switcher
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Content_Switcher extends Elementor_Booster_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_content_switcher';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Content Switcher', 'neve' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'fa fa-toggle-on';
	}


	/**
	 * Retrieve the list of scripts the content-switcher widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'neb-content-switcher' ];
	}

	/**
	 * Get widget keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'content', 'switch', 'hide', 'toggle', 'show', 'neve' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'headings_section',
			[
				'label' => __( 'Switcher', 'neve' ),
			]
		);

		$this->add_control(
			'labels_switcher',
			[
				'label'     => __( 'Show Labels', 'neve' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => 'Show',
				'label_off' => 'Hide',
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'heading_one',
			[
				'label'     => __( 'First Label', 'neve' ),
				'default'   => __( 'Content #1', 'neve' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'labels_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_two',
			[
				'label'     => __( 'Second Label', 'neve' ),
				'default'   => __( 'Content #2', 'neve' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'labels_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'headings_size',
			[
				'label'     => __( 'HTML Tag', 'neve' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default'   => 'h3',
				'condition' => [
					'labels_switcher' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_layout',
			[
				'label'     => __( 'Display', 'neve' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'no'  => __( 'Inline', 'neve' ),
					'yes' => __( 'Block', 'neve' ),
				],
				'default'   => 'no',
				'condition' => [
					'labels_switcher' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'headings_alignment',
			[
				'label'     => __( 'Alignment', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'neve' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-switcher'                                   => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .neb-content-switcher-stack-yes .neb-content-switcher-switcher' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'first_content_section',
			[
				'label' => __( 'Content 1', 'neve' ),
			]
		);

		$this->add_control(
			'first_content_tools',
			[
				'label'   => __( 'Content to Show', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'text_editor'         => __( 'Text Editor', 'neve' ),
					'elementor_templates' => __( 'Elementor Template', 'neve' ),
				],
				'default' => 'text_editor',
			]
		);

		$this->add_control(
			'first_content_text',
			[
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'condition'   => [
					'first_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_responsive_control(
			'first_content_alignment',
			[
				'label'     => __( 'Alignment', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'neve' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'neve' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'condition' => [
					'first_content_tools' => 'text_editor',
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-front-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_content_templates',
			[
				'label'       => __( 'Elementor Template', 'neve' ),
				'description' => __( 'Elementor Template is a template which you can choose from Elementor library. Each template will be shown in content', 'neve' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor_Booster_Base::get_page_templates(),
				'label_block' => true,
				'condition'   => [
					'first_content_tools' => 'elementor_templates',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'second_content_section',
			[
				'label' => __( 'Content 2', 'neve' ),
			]
		);

		$this->add_control(
			'second_content_tools',
			[
				'label'   => __( 'Content', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'text_editor'         => __( 'Text Editor', 'neve' ),
					'elementor_templates' => __( 'Elementor Template', 'neve' ),
				],
				'default' => 'text_editor',
			]
		);

		$this->add_control(
			'second_content_text',
			[
				'label'       => __( 'Text Editor', 'neve' ),
				'type'        => Controls_Manager::WYSIWYG,
				'dynamic'     => [ 'active' => true ],
				'default'     => 'Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus.Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt.',
				'label_block' => true,
				'condition'   => [
					'second_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_responsive_control(
			'second_content_alignment',
			[
				'label'     => __( 'Alignment', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'neve' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'neve' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'condition' => [
					'second_content_tools' => 'text_editor',
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-back-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'second_content_templates',
			[
				'label'       => __( 'Elementor Template', 'neve' ),
				'description' => __( 'Elementor Template is a template which you can choose from Elementor library. Each template will be shown in content', 'neve' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor_Booster_Base::get_page_templates(),
				'label_block' => true,
				'condition'   => [
					'second_content_tools' => 'elementor_templates',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_display',
			[
				'label' => __( 'Display Options', 'neve' ),
			]
		);

		$this->add_control(
			'switch_animation',
			[
				'label'   => __( 'Animation', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'opacity' => __( 'Fade', 'neve' ),
					'fade'    => __( 'Slide', 'neve' ),
				],
				'default' => 'opacity',
			]
		);

		$this->add_control(
			'fade_dir',
			[
				'label'     => __( 'Direction', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'top'    => [
						'title' => __( 'Top', 'neve' ),
						'icon'  => 'fa fa-arrow-down',
					],
					'right'  => [
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-arrow-left',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'neve' ),
						'icon'  => 'fa fa-arrow-up',
					],
					'left'   => [
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-arrow-right',
					],
				],
				'default'   => 'top',
				'condition' => [
					'switch_animation' => 'fade',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'switcher_headings_container_style_section',
			[
				'label' => __( 'Switcher', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'swithcer_headings_container_tabs' );

		$this->start_controls_tab(
			'switcher_style_tab',
			[
				'label' => __( 'Switcher', 'neve' ),
			]
		);

		$this->add_responsive_control(
			'switch_size',
			[
				'label'     => __( 'Size', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-button' => 'font-size: {{SIZE}}px',
				],

			]
		);

		$this->add_control(
			'switcher_colors_popover',
			[
				'label' => __( 'Colors', 'neve' ),
				'type'  => Controls_Manager::POPOVER_TOGGLE,
			]
		);

		$this->start_popover();

		$this->add_control(
			'popover_switch_first_content_color',
			[
				'label' => __( 'Switcher Content 1 Color', 'neve' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'switch_normal_background_color',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .neb-content-switcher-switch-control:before',
			]
		);

		$this->add_control(
			'popover_switch_second_content_color',
			[
				'label' => __( 'Switcher Content 2 Color', 'neve' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'switch_active_background_color',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .neb-content-switcher-switch:checked + .neb-content-switcher-switch-control:before',
			]
		);

		$this->add_control(
			'popover_switch_background',
			[
				'label' => __( 'Switcher Background', 'neve' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'fieldset_active_background_color',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .neb-content-switcher-switch-control',
			]
		);

		$this->end_popover();

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'label'    => __( 'Switcher Shadow', 'neve' ),
				'name'     => 'switch_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-switch-control:before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'label'    => __( 'Background Shadow', 'neve' ),
				'name'     => 'fieldset_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-switch-control',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'headings_style_tab',
			[
				'label'     => __( 'Labels', 'neve' ),
				'condition' => [
					'labels_switcher' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_headings_spacing',
			[
				'label'     => __( 'Spacing', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-stack-no .neb-content-switcher-heading-one'  => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .neb-content-switcher-stack-no .neb-content-switcher-heading-two'  => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} .neb-content-switcher-stack-yes .neb-content-switcher-heading-one' => 'margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .neb-content-switcher-stack-yes .neb-content-switcher-heading-two' => 'margin-top: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'left_heading_head',
			[
				'label' => __( 'First Label', 'neve' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'left_heading_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-heading-one *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'left_heading_typhography',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-one *',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'left_heading_text_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-one *',
			]
		);

		$this->add_control(
			'left_heading_background_color',
			[
				'label'     => __( 'Background', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-heading-one *' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'left_heading_border',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-one *',
			]
		);

		$this->add_control(
			'left_heading_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-heading-one *' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'left_heading_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-one *',
			]
		);

		$this->add_responsive_control(
			'left_headings_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-heading-one *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'right_heading_head',
			[
				'label' => __( 'Second Label', 'neve' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'right_heading_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-heading-two *' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'right_heading_typography',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-two *',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'right_heading_text_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-two *',
			]
		);

		$this->add_control(
			'right_heading_background_color',
			[
				'label'     => __( 'Background', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-heading-two *' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'premium_content_right_heading_content_border',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-two *',
			]
		);

		$this->add_control(
			'right_heading_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-heading-two *' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'right_heading_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-heading-two *',
			]
		);

		$this->add_responsive_control(
			'right_heading_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-heading-two *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'container_tab',
			[
				'label' => __( 'Container', 'neve' ),
			]
		);

		$this->add_control(
			'switcher_container_background_color',
			[
				'label'     => __( 'Background', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-switcher' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'switcher_container_border',
				'selector' => '{{WRAPPER}} .neb-content-switcher-switcher',
			]
		);

		$this->add_control(
			'switcher_container_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-switcher' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'switcher_container_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-switcher',
			]
		);

		$this->add_responsive_control(
			'switcher_container_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-switcher' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'switcher_container_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-switcher' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'content_style_section',
			[
				'label' => __( 'Content', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'two_content_height',
			[
				'label'      => __( 'Height', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'step' => 1,
						'max'  => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-container .neb-content-switcher-two-content > li' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'content_style_tabs' );

		$this->start_controls_tab(
			'first_content_style_tab',
			[
				'label' => __( 'First Content', 'neve' ),
			]
		);

		$this->add_control(
			'first_content_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-front-text'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .neb-content-switcher-front-text *' => 'color: {{VALUE}};',
				],
				'condition' => [
					'first_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'first_content_typhography',
				'selector'  => '{{WRAPPER}} .neb-content-switcher-front-text',
				'condition' => [
					'first_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'      => 'first_content_text_shadow',
				'selector'  => '{{WRAPPER}} .neb-content-switcher-front-text',
				'condition' => [
					'first_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_control(
			'first_content_background_color',
			[
				'label'     => __( 'Background', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-front' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'first_content_border',
				'selector' => '{{WRAPPER}} .neb-content-switcher-front',
			]
		);

		$this->add_control(
			'first_content_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-front' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'first_content_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-front',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'second_content_style_tab',
			[
				'label' => __( 'Second Content', 'neve' ),
			]
		);

		$this->add_control(
			'second_content_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-back-text'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .neb-content-switcher-back-text *' => 'color: {{VALUE}};',
				],
				'condition' => [
					'second_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'second_content_typhography',
				'selector'  => '{{WRAPPER}} .neb-content-switcher-back-text',
				'condition' => [
					'second_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'      => 'second_content_text_shadow',
				'selector'  => '{{WRAPPER}} .neb-content-switcher-back-text',
				'condition' => [
					'second_content_tools' => 'text_editor',
				],
			]
		);

		$this->add_control(
			'second_content_background_color',
			[
				'label'     => __( 'Background', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-content-switcher-back' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'second_content_border',
				'selector' => '{{WRAPPER}} .neb-content-switcher-back',
			]
		);

		$this->add_control(
			'second_content_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-back' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'second_content_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-back',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'contents_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'contents_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-front, {{WRAPPER}} .neb-content-switcher-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'container_style',
			[
				'label' => __( 'Container', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'container_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .neb-content-switcher-container',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .neb-content-switcher-container',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-container' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .neb-content-switcher-container',
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-content-switcher-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the widget
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$switch_animation = '';

		if ( 'opacity' === $settings['switch_animation'] ) {
			$switch_animation = 'opacity';
		}

		if ( 'fade' === $settings['switch_animation'] ) {
			$switch_animation = 'fade-' . $settings['fade_dir'];
		}

		$this->add_inline_editing_attributes( 'heading_one', 'basic' );

		$this->add_inline_editing_attributes( 'heading_two', 'basic' );

		$this->add_inline_editing_attributes( 'first_content_text', 'advanced' );

		$this->add_inline_editing_attributes( 'second_content_text', 'advanced' );

		$this->add_render_attribute( 'first_content_text', 'class', 'neb-content-switcher-front-text' );

		$this->add_render_attribute( 'second_content_text', 'class', 'neb-content-switcher-back-text' );

		echo '<div class="neb-content-switcher-container neb-content-switcher-container-' . esc_attr( $this->get_id() ) . ' ' . ( $settings['heading_layout'] === 'yes' ? 'neb-content-switcher-stack-yes' : 'neb-content-switcher-stack-no' ) . '">';
		echo '<div class="neb-content-switcher-switcher">';

		if ( 'yes' === $settings['labels_switcher'] ) {

			echo '<div class="neb-content-switcher-heading-one">';
			echo '<' . $settings['headings_size'] . ' ' . $this->get_render_attribute_string( 'heading_one' ) . '>';
			echo esc_html( $settings['heading_one'] );
			echo '</' . $settings['headings_size'] . '>';
			echo '</div>';
		}

		echo '<div class="neb-content-switcher-button">';
		echo '<label class="neb-content-switcher-switch-label">';
		echo '<input class="neb-content-switcher-switch neb-content-switcher-switch-normal elementor-clickable" type="checkbox">';
		echo '<span class="neb-content-switcher-switch-control elementor-clickable"></span>';
		echo '</label>';
		echo '</div>';

		if ( 'yes' === $settings['labels_switcher'] ) {
			echo '<div class="neb-content-switcher-heading-two">';
			echo '<' . $settings['headings_size'] . ' ' . $this->get_render_attribute_string( 'heading_two' ) . '>';
			echo esc_attr( $settings['heading_two'] );
			echo '</' . $settings['headings_size'] . '>';
			echo '</div>';
		}

		echo '</div>';

		echo '<div class="neb-content-switcher-list ' . esc_attr( $switch_animation ) . '">';
		echo '<ul class="neb-content-switcher-two-content">';
		echo '<li data-type="neb-content-switcher-front" class="neb-content-switcher-is-visible neb-content-switcher-front">';
		if ( 'text_editor' === $settings['first_content_tools'] ) {
			echo '<div ' . $this->get_render_attribute_string( 'first_content_text' ) . '>';
			echo $this->parse_text_editor( $settings['first_content_text'] );
			echo '</div>';
		}
		if ( 'elementor_templates' === $settings['first_content_tools'] ) {
			$first_content_page_id = $settings['first_content_templates'];

			$first_content = new Frontend();

			echo '<div class="neb-content-switcher-first-content-item-wrapper">';
			echo $first_content->get_builder_content( $first_content_page_id, true );
			echo '</div>';
		}
		echo '</li>';

		echo '<li data-type="neb-content-switcher-back" class="neb-content-switcher-is-hidden neb-content-switcher-back">';
		if ( 'text_editor' === $settings['second_content_tools'] ) {

			echo '<div ' . $this->get_render_attribute_string( 'second_content_text' ) . '>';
			echo $this->parse_text_editor( $settings['second_content_text'] );
			echo '</div>';
		}

		if ( 'elementor_templates' === $settings['second_content_tools'] ) {
			$second_content_page_id = $settings['second_content_templates'];

			$second_content = new Frontend;

			echo '<div class="neb-content-switcher-second-content-item-wrapper">';
			echo $second_content->get_builder_content( $second_content_page_id, true );
			echo '</div>';
		}
		echo '</li>';
		echo '</ul>';
		echo '</div>';
		echo '</div>';
	}
}
