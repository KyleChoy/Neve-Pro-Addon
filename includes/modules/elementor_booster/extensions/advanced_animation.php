<?php
/**
 * Advanced Animation class
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Extensions
 */

namespace Neve_Pro\Modules\Elementor_Booster\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

/**
 * Class Advanced_Animation
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Extensions
 */
class Advanced_Animation {

	/**
	 * Advanced_Animation constructor.
	 */
	public function __construct() {
		add_action( 'elementor/frontend/widget/before_render', array( $this, 'before_render' ) );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'add_controls_section' ] );
	}

	/**
	 * Add controls section
	 *
	 * @param Element_Base $element Elementor Instance.
	 */
	public function add_controls_section( Element_Base $element ) {
		$element->start_controls_section(
			'section_advanced_effects',
			[
				'label' => __( 'Advanced Effects', 'neve' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_floating_effects( $element );
		$this->add_css_effects( $element );

		$element->end_controls_section();
	}

	/**
	 * Add floating effects controls
	 *
	 * @param Element_Base $element Elementor Instance.
	 */
	private function add_floating_effects( Element_Base $element ) {
		$element->add_control(
			'neb_floating_fx',
			[
				'label'              => __( 'Floating Effects', 'neve' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_translate_toggle',
			[
				'label'              => __( 'Translate', 'neve' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => [
					'neb_floating_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'neb_floating_fx_translate_x',
			[
				'label'              => __( 'Translate X', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 0,
						'to'   => 5,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_translate_toggle' => 'yes',
					'neb_floating_fx'                  => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_translate_y',
			[
				'label'              => __( 'Translate Y', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 0,
						'to'   => 5,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_translate_toggle' => 'yes',
					'neb_floating_fx'                  => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_translate_duration',
			[
				'label'              => __( 'Duration', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'default'            => [
					'size' => 1000,
				],
				'condition'          => [
					'neb_floating_fx_translate_toggle' => 'yes',
					'neb_floating_fx'                  => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_translate_delay',
			[
				'label'              => __( 'Delay', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 5000,
						'step' => 100,
					],
				],
				'condition'          => [
					'neb_floating_fx_translate_toggle' => 'yes',
					'neb_floating_fx'                  => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_floating_fx_rotate_toggle',
			[
				'label'              => __( 'Rotate', 'neve' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => [
					'neb_floating_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'neb_floating_fx_rotate_x',
			[
				'label'              => __( 'Rotate X', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 0,
						'to'   => 45,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_rotate_toggle' => 'yes',
					'neb_floating_fx'               => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_rotate_y',
			[
				'label'              => __( 'Rotate Y', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 0,
						'to'   => 45,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_rotate_toggle' => 'yes',
					'neb_floating_fx'               => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_rotate_z',
			[
				'label'              => __( 'Rotate Z', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 0,
						'to'   => 45,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_rotate_toggle' => 'yes',
					'neb_floating_fx'               => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_rotate_duration',
			[
				'label'              => __( 'Duration', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'default'            => [
					'size' => 1000,
				],
				'condition'          => [
					'neb_floating_fx_rotate_toggle' => 'yes',
					'neb_floating_fx'               => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_rotate_delay',
			[
				'label'              => __( 'Delay', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 5000,
						'step' => 100,
					],
				],
				'condition'          => [
					'neb_floating_fx_rotate_toggle' => 'yes',
					'neb_floating_fx'               => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_floating_fx_scale_toggle',
			[
				'label'              => __( 'Scale', 'neve' ),
				'type'               => Controls_Manager::POPOVER_TOGGLE,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => [
					'neb_floating_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_control(
			'neb_floating_fx_scale_x',
			[
				'label'              => __( 'Scale X', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 1,
						'to'   => 1.2,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_scale_toggle' => 'yes',
					'neb_floating_fx'              => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_scale_y',
			[
				'label'              => __( 'Scale Y', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'sizes' => [
						'from' => 1,
						'to'   => 1.2,
					],
					'unit'  => 'px',
				],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'labels'             => [
					__( 'From', 'neve' ),
					__( 'To', 'neve' ),
				],
				'scales'             => 1,
				'handles'            => 'range',
				'condition'          => [
					'neb_floating_fx_scale_toggle' => 'yes',
					'neb_floating_fx'              => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_scale_duration',
			[
				'label'              => __( 'Duration', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'default'            => [
					'size' => 1000,
				],
				'condition'          => [
					'neb_floating_fx_scale_toggle' => 'yes',
					'neb_floating_fx'              => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'neb_floating_fx_scale_delay',
			[
				'label'              => __( 'Delay', 'neve' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min'  => 0,
						'max'  => 5000,
						'step' => 100,
					],
				],
				'condition'          => [
					'neb_floating_fx_scale_toggle' => 'yes',
					'neb_floating_fx'              => 'yes',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
	}

	/**
	 * Add css effects controls
	 *
	 * @param Element_Base $element Elementor Instance.
	 */
	private function add_css_effects( Element_Base $element ) {
		$element->add_control(
			'neb_transform_fx',
			[
				'label'        => __( 'CSS Transform', 'neve' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'neb_transform_fx_translate_toggle',
			[
				'label'        => __( 'Translate', 'neve' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'neb_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'neb_transform_fx_translate_x',
			[
				'label'      => __( 'Translate X', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
				],
				'condition'  => [
					'neb_transform_fx_translate_toggle' => 'yes',
					'neb_transform_fx'                  => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'neb_transform_fx_translate_y',
			[
				'label'      => __( 'Translate Y', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
				],
				'condition'  => [
					'neb_transform_fx_translate_toggle' => 'yes',
					'neb_transform_fx'                  => 'yes',
				],
				'selectors'  => [
					'(desktop){{WRAPPER}}' =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px);',
					'(tablet){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px);',
					'(mobile){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px);',
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_transform_fx_rotate_toggle',
			[
				'label'     => __( 'Rotate', 'neve' ),
				'type'      => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'neb_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'neb_transform_fx_rotate_x',
			[
				'label'      => __( 'Rotate X', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'condition'  => [
					'neb_transform_fx_rotate_toggle' => 'yes',
					'neb_transform_fx'               => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'neb_transform_fx_rotate_y',
			[
				'label'      => __( 'Rotate Y', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'condition'  => [
					'neb_transform_fx_rotate_toggle' => 'yes',
					'neb_transform_fx'               => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'neb_transform_fx_rotate_z',
			[
				'label'      => __( 'Rotate Z', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'condition'  => [
					'neb_transform_fx_rotate_toggle' => 'yes',
					'neb_transform_fx'               => 'yes',
				],
				'selectors'  => [
					'(desktop){{WRAPPER}}' =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg);',
					'(tablet){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg);',
					'(mobile){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg);',
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_transform_fx_scale_toggle',
			[
				'label'        => __( 'Scale', 'neve' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'neb_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'neb_transform_fx_scale_x',
			[
				'label'      => __( 'Scale X', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 1,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'condition'  => [
					'neb_transform_fx_scale_toggle' => 'yes',
					'neb_transform_fx'              => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'neb_transform_fx_scale_y',
			[
				'label'      => __( 'Scale Y', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'size' => 1,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'condition'  => [
					'neb_transform_fx_scale_toggle' => 'yes',
					'neb_transform_fx'              => 'yes',
				],
				'selectors'  => [
					'(desktop){{WRAPPER}}' =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y.SIZE || 1}});'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y.SIZE || 1}});'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y.SIZE || 1}});',
					'(tablet){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_tablet.SIZE || 1}});'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_tablet.SIZE || 1}});'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_tablet.SIZE || 1}});',
					'(mobile){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_mobile.SIZE || 1}});'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_mobile.SIZE || 1}});'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_mobile.SIZE || 1}});',
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_transform_fx_skew_toggle',
			[
				'label'        => __( 'Skew', 'neve' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'neb_transform_fx' => 'yes',
				],
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'neb_transform_fx_skew_x',
			[
				'label'      => __( 'Skew X', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'condition'  => [
					'neb_transform_fx_skew_toggle' => 'yes',
					'neb_transform_fx'             => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'neb_transform_fx_skew_y',
			[
				'label'      => __( 'Skew Y', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'px' => [
						'min' => - 180,
						'max' => 180,
					],
				],
				'condition'  => [
					'neb_transform_fx_skew_toggle' => 'yes',
					'neb_transform_fx'             => 'yes',
				],
				'selectors'  => [
					'(desktop){{WRAPPER}}' =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x.SIZE || 0}}deg, {{neb_transform_fx_skew_y.SIZE || 0}}deg);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x.SIZE || 0}}deg, {{neb_transform_fx_skew_y.SIZE || 0}}deg);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x.SIZE || 0}}px, {{neb_transform_fx_translate_y.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x.SIZE || 0}}deg, {{neb_transform_fx_skew_y.SIZE || 0}}deg);',
					'(tablet){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_tablet.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{neb_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_tablet.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{neb_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_tablet.SIZE || 0}}px, {{neb_transform_fx_translate_y_tablet.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_tablet.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{neb_transform_fx_skew_y_tablet.SIZE || 0}}deg);',
					'(mobile){{WRAPPER}}'  =>
						'-ms-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_mobile.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{neb_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
						. '-webkit-transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_mobile.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{neb_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
						. 'transform:'
						. 'translate({{neb_transform_fx_translate_x_mobile.SIZE || 0}}px, {{neb_transform_fx_translate_y_mobile.SIZE || 0}}px) '
						. 'rotateX({{neb_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{neb_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{neb_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
						. 'scaleX({{neb_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{neb_transform_fx_scale_y_mobile.SIZE || 1}}) '
						. 'skew({{neb_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{neb_transform_fx_skew_y_mobile.SIZE || 0}}deg);',
				],
			]
		);

		$element->end_popover();

		$element->add_control(
			'neb_transform_reduced_motion_switch',
			[
				'label'              => sprintf(
					/* translators: %s is reduce motion link */
					__( 'Disable effect on %s devices', 'neve' ),
					sprintf(
						/* translators: %s is educe motion label */
						'<a target="_blank" href="https://a11y-101.com/development/reduced-motion">%s</a>',
						__( 'reduce motion', 'neve' )
					)
				),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);
	}

	/**
	 * Before section render.
	 *
	 * @param Object $element Elementor instance.
	 *
	 * @return bool
	 */
	public function before_render( $element ) {

		$settings = $element->get_settings();
		if ( ! isset( $settings['neb_floating_fx'] ) ) {
			return false;
		}
		if ( $settings['neb_floating_fx'] === 'yes' ) {
			wp_enqueue_script( 'neb-animations' );
		}

		return true;

	}
}
