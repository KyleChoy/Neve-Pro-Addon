<?php
/**
 * Elementor Review Box Widget.
 *
 * @example https://developers.elementor.com/creating-a-new-widget/
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

/**
 * Class Review_Box
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Review_Box extends Elementor_Booster_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_review_box';
	}

	/**
	 * Widget Label.
	 *
	 * @return string
	 */
	public function get_title() {
		return 'Review Box';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-star';
	}

	/**
	 * Retrieve the list of styles the review box widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_style_depends() {
		return [ 'font-awesome-5-all' ];
	}

	/**
	 * Render out the control.
	 */
	public function render() {
		$settings      = $this->get_settings();
		$overall_score = 0;
		$score_count   = 0;
		if ( ! empty( $settings['scores_list'] ) ) {
			foreach ( $settings['scores_list'] as $i => $score ) {
				if ( $score['score']['size'] ) {
					$item_score     = ( (int) $score['score']['size'] );
					$overall_score += $item_score;
					$score_count ++;
				}
			}
		}
		$average        = $overall_score / $score_count;
		$score_class    = ' eaw-rated-p' . ( (int) round( $average / 10 ) * 10 );
		$rated          = $this->get_rating_type_class( $average );
		$review_classes = $score_class . ' ' . $rated;
		$display_score  = round( $average / 10, 1 );
		$link_open      = '';
		$link_close     = '';
		if ( ! empty( $settings['link_type'] ) && $settings['link_type'] === 'on_title' && ! empty( $settings['product_link']['url'] ) ) {
			$url        = $settings['product_link']['url'];
			$target     = $settings['product_link']['is_external'] === true ? 'target="_blank"' : '';
			$rel        = $settings['product_link']['nofollow'] === true ? 'rel="nofollow"' : '';
			$link_open  = '<a href="' . esc_url( $url ) . '" ' . $target . ' ' . $rel . '>';
			$link_close = '</a>';
		}
		?>
		<div class="eaw-review-box-wrapper">
			<div class="eaw-review-box-top">
				<?php
				if ( ! empty( $settings['title'] ) ) {
					echo $link_open;
					echo '<h3 class="eaw-review-box-title">' . $settings['title'] . '</h3>';
					echo $link_close;
				}
				if ( ! empty( $settings['price'] ) ) {
					?>
					<h3 class="eaw-review-box-price"><?php echo $settings['price']; ?></h3>
				<?php } ?>
			</div>
			<div class="eaw-content-wrapper">
			<div class="eaw-review-box-left">
				<div class="eaw-review-header">
					<?php
					if ( ! empty( $settings['image']['url'] ) ) {
						?>
						<div class="elementor-review-box-image">
							<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
						</div>
						<?php
					}
					if ( ! empty( $display_score ) ) {
						?>
						<div class="eaw-rating">
							<div class="eaw-grade-content">
								<div class="eaw-c100 <?php echo esc_attr( $review_classes ); ?>">
									<span><?php echo esc_html( $display_score ); ?></span>
									<div class="eaw-slice">
										<div class="eaw-bar"></div>
										<div class="eaw-fill"></div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div><!-- /.eaw-review-header -->

				<?php if ( ! empty( $settings['scores_list'] ) ) { ?>
					<div class="eaw-review-score-list">
						<?php foreach ( $settings['scores_list'] as $i => $score ) { ?>
							<div class="eaw-score-wrapper">
								<p class="eaw-score-title"><?php echo $score['text']; ?></p>
								<?php
								if ( $score['score']['size'] ) {
									$individual_score = ( (int) $score['score']['size'] / 10 );
									?>
									<strong class="eaw-review-box-score"><?php echo $individual_score; ?></strong>
									<div class="eaw-icon-score-display">
										<div class="eaw-grey">
											<?php
											if ( empty( $score['icon'] ) ) {
												$score['icon'] = 'fa fa-star';
											}
											for ( $i = 0; $i < 10; $i ++ ) {
												Icons_Manager::render_icon( $score['review_icon'], [ 'aria-hidden' => 'true' ] );
											}
											?>
										</div>
										<div class="eaw-colored <?php echo $this->get_rating_type_class( $score['score']['size'] ); ?>"
												style="width: <?php echo $individual_score * 10; ?>%">
											<?php
											for ( $i = 0; $i < 10; $i ++ ) {
												Icons_Manager::render_icon( $score['review_icon'], [ 'aria-hidden' => 'true' ] );
											}
											?>
										</div>
									</div>
								<?php } ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div><!-- /.eaw-review-box-left -->
			<div class="eaw-review-box-right">
				<?php
				if ( ! empty( $settings['pros_title'] ) ) {
					?>
					<h4 class="eaw-pros-title"><?php echo esc_html( $settings['pros_title'] ); ?></h4>
					<?php
				}
				if ( ! empty( $settings['pro_list'] ) ) {
					?>
					<ul class="elementor-review-box-pro-list">
						<?php foreach ( $settings['pro_list'] as $i => $pro ) { ?>
							<li class="elementor-repeater-item-<?php echo $pro['_id']; ?>">
								<?php echo $pro['text']; ?>
							</li>
						<?php } ?>
					</ul>
					<?php
				}

				if ( ! empty( $settings['cons_title'] ) ) {
					?>
					<h4 class="eaw-cons-title"><?php echo esc_html( $settings['cons_title'] ); ?></h4>
					<?php
				}
				if ( ! empty( $settings['cons_list'] ) ) {
					?>
					<ul class="elementor-review-box-con-list">
						<?php foreach ( $settings['cons_list'] as $i => $con ) { ?>
							<li class="elementor-repeater-item-<?php echo $con['_id']; ?>">
								<?php echo $con['text']; ?>
							</li>
						<?php } ?>
					</ul>
					<?php
				}

				if ( ! empty( $settings['link_type'] ) && $settings['link_type'] === 'buttons' && ! empty( $settings['review_buttons'] ) ) {
					echo '<div class="eaw-buttons-wrapper">';
					foreach ( $settings['review_buttons'] as $review_button ) {
						$button_text = $review_button['button_text'];
						if ( empty( $button_text ) ) {
							continue;
						}
						$url    = $review_button['button_link']['url'];
						$target = $review_button['button_link']['is_external'] === true ? 'target="_blank"' : '';
						$rel    = $review_button['button_link']['nofollow'] === true ? 'rel="nofollow"' : '';
						echo '<a class=" elementor-repeater-item-' . $review_button['_id'] . ' eaw-button" href="' . esc_url( $url ) . '" ' . $target . ' ' . $rel . '>' . $button_text . '</a>';
					}
					echo '</div>';
				}

				?>
			</div><!-- /.eaw-review-box-right -->
			</div>
		</div><!-- /.eaw-review-box-wrapper -->
		<?php
	}

	/**
	 * Get the type of rating class based on score.
	 *
	 * @param int $score the score that will be passed (between 0-100).
	 *
	 * @return string the class which will be added to items.
	 */
	public function get_rating_type_class( $score ) {
		switch ( true ) {
			case $score <= 25:
				$rated = 'eaw-review-weak';
				break;
			case $score <= 50:
				$rated = 'eaw-review-not-bad';
				break;
			case $score <= 75:
				$rated = 'eaw-review-good';
				break;
			default:
				$rated = 'eaw-review-very-good';
				break;
		}

		return $rated;
	}

	/**
	 * Register Box main controls.
	 */
	protected function _register_box_controls() {

		$this->start_controls_section(
			'section_box_settings',
			array(
				'label'      => __( 'Box Settings', 'neve' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Review Box', 'neve' ),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => __( 'Price', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '100$',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Choose Image', 'neve' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Box Pros controls.
	 */
	protected function _register_pro_controls() {

		$this->start_controls_section(
			'section_pros',
			array(
				'label'      => __( 'Pros', 'neve' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'pros_title',
			array(
				'label'   => __( 'Title', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Pros', 'neve' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'Label', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Pro', 'neve' ),
			)
		);

		$this->add_control(
			'pro_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'text' => __( 'Pro #1', 'neve' ),
					),
					array(
						'text' => __( 'Pro #2', 'neve' ),
					),
					array(
						'text' => __( 'Pro #3', 'neve' ),
					),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Box Cons controls.
	 */
	protected function _register_cons_controls() {
		$this->start_controls_section(
			'section_cons',
			array(
				'label'      => __( 'Cons', 'neve' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'cons_title',
			array(
				'label'   => __( 'Title', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Cons', 'neve' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'Text', 'neve' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List con', 'neve' ),
			)
		);

		$this->add_control(
			'cons_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'text' => __( 'Con #1', 'neve' ),
						'icon' => 'fa fa-close',
					),
					array(
						'text' => __( 'Con #2', 'neve' ),
						'icon' => 'fa fa-close',
					),
					array(
						'text' => __( 'Con #3', 'neve' ),
						'icon' => 'fa fa-close',
					),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Box Scores controls.
	 */
	protected function _register_scores_controls() {
		$this->start_controls_section(
			'section_scores',
			array(
				'label'      => __( 'Scores', 'neve' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'text',
			array(
				'label' => __( 'Score Label', 'neve' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'score',
			array(
				'label' => __( 'Score', 'neve' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
			)
		);
		$repeater->add_control(
			'review_icon',
			array(
				'label'            => __( 'Icon', 'neve' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => [
					'value'   => 'fas fa-star',
					'library' => 'solid',
				],
			)
		);

		$this->add_control(
			'scores_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'text'  => __( 'Our Rating', 'neve' ),
						'score' => array(
							'size' => '95',
							'unit' => 'px',
						),
						'color' => 'fa fa-star',
					),
					array(
						'text'  => __( 'User Rating', 'neve' ),
						'score' => array(
							'size' => '87',
							'unit' => 'px',
						),
						'icon'  => 'fa fa-star',
					),
					array(
						'text'  => __( 'Product Quality', 'neve' ),
						'score' => array(
							'size' => '65',
							'unit' => 'px',
						),
						'icon'  => 'fa fa-star',
					),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Links Controls
	 */
	private function _register_links_controls() {
		$this->start_controls_section(
			'section_links',
			array(
				'label'      => __( 'Links', 'neve' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);
		$this->add_control(
			'link_type',
			array(
				'label'   => __( 'Link type', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''         => __( 'None', 'neve' ),
					'on_title' => __( 'Link on review title', 'neve' ),
					'buttons'  => __( 'Buttons', 'neve' ),
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'     => __( 'Alignment', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'default'   => 'left',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'textdomain' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'textdomain' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'textdomain' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-buttons-wrapper' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'link_type' => 'buttons',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'neve' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .eaw-button',
				'condition' => array(
					'link_type' => 'buttons',
				),
			)
		);

		$this->add_control(
			'product_link',
			array(
				'label'         => __( 'Link', 'neve' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => 'https://your-link.com',
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
				'condition'     => array(
					'link_type' => 'on_title',
				),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'button_text',
			array(
				'label' => __( 'Button Text', 'neve' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'button_link',
			array(
				'label'         => __( 'Link', 'neve' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => 'https://your-link.com',
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$repeater->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$repeater->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => __( 'Border', 'textdomain' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			)
		);

		$repeater->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border radius', 'plugin-domain' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			)
		);

		$repeater->start_controls_tabs(
			'style_tabs'
		);
		$repeater->start_controls_tab(
			'style_normal_tab',
			array(
				'label' => __( 'Normal', 'neve' ),
			)
		);
		$repeater->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
			)
		);
		$repeater->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'style_hover_tab',
			array(
				'label' => __( 'Hover', 'neve' ),
			)
		);
		$repeater->add_control(
			'background_color_hover',
			array(
				'label'     => __( 'Background Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$repeater->add_control(
			'text_color_hover',
			array(
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tab();

		$this->add_control(
			'review_buttons',
			array(
				'type'      => Controls_Manager::REPEATER,
				'fields'    => array_values( $repeater->get_controls() ),
				'condition' => array(
					'link_type' => 'buttons',
				),
			)
		);
		$this->end_controls_section();
	}


	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->_register_box_controls();
		$this->_register_pro_controls();
		$this->_register_cons_controls();
		$this->_register_scores_controls();
		$this->_register_links_controls();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'review_box',
			array(
				'label' => __( 'Review Box', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'inner_border_color',
			array(
				'label'     => __( 'Inner Border Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .eaw-review-header .eaw-rating' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-header'    => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-box-right' => 'border-color: {{VALUE}}',
				],
			)
		);

		$this->add_control(
			'border_top_color',
			array(
				'label'     => __( 'Outer Border Top Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'border-top-color: {{VALUE}}',
				],
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'review_box_title',
			array(
				'label' => __( 'Review Box Header', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'review_box_title_padding',
			array(
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eaw-review-box-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'review_box_title_color',
			array(
				'label'     => __( 'Title Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-review-box-title'   => 'color: {{VALUE}}',
					'{{WRAPPER}} a .eaw-review-box-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_box_price_color',
			array(
				'label'     => __( 'Price Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-review-box-price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_title_typography',
				'label'    => __( 'Title Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-review-box-title, {{WRAPPER}} a .eaw-review-box-title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_price_typography',
				'label'    => __( 'Price Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-review-box-price',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'review_box_scores',
			array(
				'label' => __( 'Review Box Scores', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'review_box_score_padding',
			array(
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eaw-review-score-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'review_box_score_title_color',
			array(
				'label'     => __( 'Score Title Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-score-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_box_very_good_color',
			array(
				'label'     => __( 'Very Good Review Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8DC153',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-review-very-good>i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eaw-review-very-good .eaw-bar' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-very-good .eaw-fill' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-very-good > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_box_good_color',
			array(
				'label'     => __( 'Good Review Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#50C1E9',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-review-good>i'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .eaw-review-good .eaw-bar' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-good .eaw-fill' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-good > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_box_fair_color',
			array(
				'label'     => __( 'Fair Review Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFCE55',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-review-not-bad>i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eaw-review-not-bad .eaw-bar' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-not-bad .eaw-fill' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .eaw-review-not-bad > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_box_bad_color',
			array(
				'label'     => __( 'Bad Review Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF7F66',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}}  .eaw-review-weak>i'      => 'color: {{VALUE}};',
					'{{WRAPPER}}  .eaw-review-weak .eaw-bar' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}}  .eaw-review-weak .eaw-fill' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}}  .eaw-review-weak > span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'review_box_score_color',
			array(
				'label'     => __( 'Score Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-review-box-score' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_score_title_typography',
				'label'    => __( 'Score Title Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-score-title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_score_typography',
				'label'    => __( 'Score Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-review-box-score',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'review_box_pros_cons',
			array(
				'label' => __( 'Review Box Pros and Cons', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'review_box_pros_cons_padding',
			array(
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eaw-review-box-right' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'review_box_pros_title_color',
			array(
				'label'     => __( 'Pros Title Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-pros-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_pros_title_typography',
				'label'    => __( 'Pros Title Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-pros-title',
			)
		);

		$this->add_control(
			'review_box_pros_list_color',
			array(
				'label'     => __( 'Pros List Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-review-box-pro-list' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_pros_list_typography',
				'label'    => __( 'Pros List Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-review-box-pro-list',
			)
		);

		$this->add_control(
			'review_box_cons_title_color',
			array(
				'label'     => __( 'Cons Title Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-cons-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_cons_title_typography',
				'label'    => __( 'Cons Title Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-cons-title',
			)
		);

		$this->add_control(
			'review_box_cons_list_color',
			array(
				'label'     => __( 'Cons List Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-review-box-con-list' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_box_cons_list_typography',
				'label'    => __( 'Cons List Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-review-box-con-list',
			)
		);

		$this->end_controls_section();
	}
}
