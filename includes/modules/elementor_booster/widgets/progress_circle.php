<?php
/**
 * Elementor Progress Circle Widget.
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

/**
 * Class Progress_Circle
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Progress_Circle extends Elementor_Booster_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_progress_circle';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Progress Circle', 'neve' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'fa fa-circle-o-notch';
	}

	/**
	 * Get widget keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'progress', 'loading', 'percent', 'load', 'circle', 'neve' ];
	}

	/**
	 * Retrieve the list of scripts the progress-circle widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'neb-progress-circle' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_circle_progress',
			[
				'label' => __( 'Circle Progress', 'neve' ),
			]
		);

		$this->add_control(
			'goal',
			[
				'label'   => __( 'Percent', 'neve' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '60',
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => __( 'Speed (s)', 'neve' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '1',
			]
		);

		$this->add_control(
			'step',
			[
				'label'   => __( 'Steps', 'neve' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '1',
			]
		);

		$this->add_control(
			'delay',
			[
				'label'   => __( 'Delay', 'neve' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '1',
			]
		);

		$this->add_control(
			'text_before',
			[
				'label'       => __( 'Text Before', 'neve' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'text_middle',
			[
				'label'       => __( 'Text Middle', 'neve' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'text_after',
			[
				'label'       => __( 'Text After', 'neve' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'neve' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label'   => __( 'Content', 'neve' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'Add your content here', 'neve' ),
				'dynamic' => [ 'active' => true ],
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
				'label' => __( 'Circle Progress', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'barsize',
			[
				'label'   => __( 'Bar Size', 'neve' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
			]
		);

		$this->add_control(
			'circle_outside_color',
			[
				'label'     => esc_html__( 'Circle Outside Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle svg ellipse' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'circle_inside_color',
			[
				'label'     => esc_html__( 'Circle Inside Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle svg path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'circle_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_before_heading',
			[
				'label'     => __( 'Text Before', 'neve' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_before_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-before',
			]
		);

		$this->add_control(
			'text_before_color',
			[
				'label'     => esc_html__( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_before_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_middle_heading',
			[
				'label'     => __( 'Number/Text Middle', 'neve' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_middle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-middle',
			]
		);

		$this->add_control(
			'text_middle_color',
			[
				'label'     => esc_html__( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-middle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_middle_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-middle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_after_heading',
			[
				'label'     => __( 'Text After', 'neve' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_after_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-after',
			]
		);

		$this->add_control(
			'text_after_color',
			[
				'label'     => esc_html__( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_after_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle .neb-progress-circle-label .neb-progress-circle-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content',
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label'     => __( 'Background Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'content_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-progress-circle-wrap .neb-progress-circle-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrap', 'class', 'neb-progress-circle-wrap' );

		$this->add_render_attribute(
			'inner',
			'class',
			[
				'neb-progress-circle',
				'pieProgress',
			]
		);

		$this->add_render_attribute( 'inner', 'role', 'progressbar' );

		if ( ! empty( $settings['goal'] ) ) {
			$this->add_render_attribute( 'inner', 'data-goal', $settings['goal'] );
		}

		$this->add_render_attribute( 'inner', 'data-valuemin', '0' );

		if ( ! empty( $settings['speed'] ) ) {
			$this->add_render_attribute( 'inner', 'data-speed', $settings['speed'] * 15 );
		}

		if ( ! empty( $settings['step'] ) ) {
			$this->add_render_attribute( 'inner', 'data-step', $settings['step'] );
		}

		if ( ! empty( $settings['delay'] ) ) {
			$this->add_render_attribute( 'inner', 'data-delay', $settings['delay'] * 1000 );
		}

		if ( ! empty( $settings['barsize'] ) ) {
			$this->add_render_attribute( 'inner', 'data-barsize', intval( $settings['barsize'] ) );
		}

		$this->add_render_attribute( 'inner', 'data-valuemax', '100' );

		$this->add_render_attribute( 'label', 'class', 'neb-progress-circle-label' );
		$this->add_render_attribute( 'before', 'class', 'neb-progress-circle-before' );
		$this->add_render_attribute( 'text', 'class', 'neb-progress-circle-middle' );
		$this->add_render_attribute(
			'number',
			'class',
			[
				'neb-progress-circle-number',
				'neb-progress-circle-middle',
			]
		);
		$this->add_render_attribute( 'after', 'class', 'neb-progress-circle-after' );
		$this->add_render_attribute( 'content', 'class', 'neb-progress-circle-content' ); ?>

		<div <?php echo $this->get_render_attribute_string( 'wrap' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'inner' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'label' ); ?>>
					<?php
					if ( $settings['text_before'] ) {
						?>
						<div <?php echo $this->get_render_attribute_string( 'before' ); ?>><?php echo esc_html( $settings['text_before'] ); ?></div>
						<?php
					}

					if ( $settings['text_middle'] ) {
						?>
						<div <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo esc_html( $settings['text_middle'] ); ?></div>
						<?php
					} else {
						?>
						<div <?php echo $this->get_render_attribute_string( 'number' ); ?>></div>
						<?php
					}

					if ( $settings['text_after'] ) {
						?>
						<div <?php echo $this->get_render_attribute_string( 'after' ); ?>><?php echo esc_html( $settings['text_after'] ); ?></div>
						<?php
					}
					?>
				</div>
			</div>

			<?php
			if ( $settings['content'] ) {
				?>
				<div <?php echo $this->get_render_attribute_string( 'content' ); ?>><?php echo $settings['content']; ?></div>
				<?php
			}
			?>

		</div>

		<?php
	}
}
