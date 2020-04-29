<?php
/**
 * Elementor Custom_Field Widget
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Scheme_Typography;

/**
 * Class Custom_Field
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Custom_Field extends Elementor_Booster_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_custom_field';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Custom Field', 'neve' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'fa fa-wpforms';
	}

	/**
	 * Get widget keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'advanced', 'custom', 'field', 'acf', 'meta', 'neve' ];
	}

	/**
	 * Retrieve the list of styles the custom field widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_style_depends() {
		return [ 'font-awesome-5-all' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_cfield',
			[
				'label' => __( 'Custom Field', 'neve' ),
			]
		);

		$this->add_control(
			'data_source',
			[
				'label'   => __( 'Data Source', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'meta',
				'options' => [
					'meta' => __( 'Post Meta', 'neve' ),
					'acf'  => __( 'ACF', 'neve' ),
				],
			]
		);

		$this->add_control(
			'field_name_acf',
			[
				'label'     => __( 'Field Name', 'neve' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => false,
				'options'   => $this->get_acf_fields(),
				'condition' => [
					'data_source' => 'acf',
				],
			]
		);

		$this->add_control(
			'field_name_meta',
			[
				'label'     => __( 'Field Name', 'neve' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => false,
				'options'   => $this->get_meta_fields(),
				'condition' => [
					'data_source' => 'meta',
				],
			]
		);

		$this->add_control(
			'field_type',
			[
				'label'   => __( 'Field Type', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text' => __( 'Text', 'neve' ),
					'link' => __( 'Link', 'neve' ),
				],
			]
		);

		$this->add_control(
			'link_text',
			[
				'label'     => __( 'Link Text', 'neve' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'field_type' => 'link',
				],
				'dynamic'   => [ 'active' => true ],
			]
		);

		$this->add_control(
			'link_target',
			[
				'label'     => __( 'Link Target', 'neve' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'self',
				'options'   => [
					'self'  => __( 'Self', 'neve' ),
					'blank' => __( 'Blank', 'neve' ),
				],
				'condition' => [
					'field_type' => 'link',
				],
			]
		);

		$this->add_control(
			'link_nofollow',
			[
				'label'     => __( 'Add Nofollow', 'neve' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'field_type' => 'link',
				],
			]
		);

		$this->add_control(
			'field_label',
			[
				'label'   => __( 'Label', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'neve' ),
				'type'  => Controls_Manager::ICONS,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => __( 'Icon Position', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => __( 'Before', 'neve' ),
					'right' => __( 'After', 'neve' ),
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => __( 'Icon Spacing', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neb-cfield .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .neb-cfield .elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'neve' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .neb-cfield' => 'text-align: {{VALUE}};',
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
			'section_style',
			[
				'label' => __( 'Field', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'field_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .neb-cfield .neb-cfield-field',
			]
		);

		$this->add_control(
			'field_color',
			[
				'label'     => __( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .neb-cfield .neb-cfield-field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_label_style',
			[
				'label'     => __( 'Label', 'neve' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'field_label!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'label_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .neb-cfield .neb-cfield-label',
				'condition' => [
					'field_label!' => '',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => __( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .neb-cfield .neb-cfield-label' => 'color: {{VALUE}};',
				],
				'condition' => [
					'field_label!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label'     => __( 'Icon', 'neve' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .neb-cfield .neb-cfield-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => __( 'Size', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .neb-cfield .neb-cfield-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the widget
	 *
	 * @return bool
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$data_source = $settings['data_source'];
		if ( $data_source === 'meta' && ! is_singular() ) {
			if ( current_user_can( 'manage_options' ) ) {
				echo '<div class="neb-cfield-error">' . __( 'You can\'t use the data source as post meta while you\'re not on a singular page. Please switch to ACF data source.', 'neve' ) . '</div>';
			}

			return false;
		}
		if ( $data_source === 'acf' && ( ! defined( 'ACF_VERSION' ) || ! function_exists( 'get_field' ) ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				echo '<div class="neb-cfield-error">' . __( 'You need to install ACF or change the data source to post meta', 'neve' ) . '</div>';
			}

			return false;
		}

		$type = $settings['field_type'];
		$pid  = get_the_ID();
		$this->add_render_attribute( 'wrap', 'class', 'neb-cfield' );

		if ( ! empty( $settings['icon'] ) ) {
			$this->add_render_attribute(
				'icon',
				'class',
				[
					'neb-cfield-icon',
					'elementor-align-icon-' . $settings['icon_align'],
				]
			);
		}

		$this->add_render_attribute( 'label', 'class', 'neb-cfield-label' );
		$this->add_render_attribute( 'field', 'class', 'neb-cfield-field' );

		$this->add_render_attribute( 'link', 'class', 'neb-cfield-field' );

		if ( $data_source === 'meta' ) {
			$data = ! empty( $settings['field_name_meta'] ) ? get_post_meta( $pid, $settings['field_name_meta'], true ) : '';
		} else {
			$data = ! empty( $settings['field_name_acf'] ) ? get_field( $settings['field_name_acf'] ) : '';
		}

		$this->add_render_attribute( 'link', 'href', esc_url( $data ) );
		$this->add_render_attribute( 'link', 'target', '_' . $settings['link_target'] );

		if ( 'yes' === $settings['link_nofollow'] ) {
			$this->add_render_attribute( 'link', 'rel', 'nofollow' );
		}

		echo '<div ' . $this->get_render_attribute_string( 'wrap' ) . '>';
		if ( ! empty( $settings['icon'] ) && 'left' === $settings['icon_align'] ) {
			echo '<span ' . $this->get_render_attribute_string( 'icon' ) . '>';
			Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
			echo '</span>';
		}

		if ( ! empty( $settings['field_label'] ) ) {
			echo '<span ' . $this->get_render_attribute_string( 'label' ) . '>';
			echo esc_attr( $settings['field_label'] );
			echo '</span>';
		}

		if ( 'text' === $type ) {
			echo '<span ' . $this->get_render_attribute_string( 'field' ) . '>';
			echo $data;
			echo '</span>';
		}

		if ( 'link' === $type ) {
			echo '<a ' . $this->get_render_attribute_string( 'link' ) . '>';
			if ( ! empty( $settings['link_text'] ) ) {
				echo esc_attr( $settings['link_text'] );
			} else {
				echo $data;
			}
			echo '</a>';
		}

		if ( ! empty( $settings['icon'] ) && 'right' === $settings['icon_align'] ) {
			echo '<span ' . $this->get_render_attribute_string( 'icon' ) . '>';
			Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
			echo '</span>';
		}
		echo '</div>';

		return true;
	}

	/**
	 * Get acf fields.
	 *
	 * @return array
	 */
	private function get_acf_fields() {
		if ( ! defined( 'ACF_VERSION' ) || ! function_exists( 'get_field_objects' ) ) {
			return [];
		}
		$result        = [];
		$fields        = get_field_objects();
		$allowed_types = [ 'text', 'textarea', 'number', 'range', 'email', 'url', 'wysiwyg', 'select', 'radio' ];
		if ( empty( $fields ) ) {
			return [];
		}
		foreach ( $fields as $key => $value ) {
			if ( ! in_array( $value['type'], $allowed_types, true ) ) {
				continue;
			}
			$result[ $key ] = $key;
		}

		return $result;
	}

	/**
	 * Get meta fields options.
	 *
	 * @return array
	 */
	private function get_meta_fields() {
		if ( ! is_singular() ) {
			return [];
		}
		$id     = get_the_ID();
		$fields = get_post_meta( $id );
		foreach ( $fields as $key => $value ) {
			$fields[ $key ] = $key;
		}

		return $fields;
	}
}
