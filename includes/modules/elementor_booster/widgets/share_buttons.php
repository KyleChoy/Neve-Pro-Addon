<?php
/**
 * Elementor Share Buttons Widget.
 *
 * @example https://developers.elementor.com/creating-a-new-widget/
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Scheme_Typography;

/**
 * Class Share_Buttons.
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Share_Buttons extends Elementor_Booster_Base {

	/**
	 * Array of sharing networks
	 *
	 * @var array
	 */
	public $brands = array(
		'facebook'   => array(
			'name'     => 'Facebook',
			'fa5_icon' => 'fab fa-facebook-f',
		),
		'twitter'    => array(
			'name'     => 'Twitter',
			'fa5_icon' => 'fab fa-twitter',
		),
		'google'     => array(
			'name'     => 'Google',
			'fa5_icon' => 'fab fa-google',
		),
		'tumblr'     => array(
			'name'     => 'Tumblr',
			'fa5_icon' => 'fab fa-tumblr',
		),
		'email'      => array(
			'name'     => 'E-Mail',
			'fa5_icon' => 'fas fa-envelope',
		),
		'pinterest'  => array(
			'name'     => 'Pinterest',
			'fa5_icon' => 'fab fa-pinterest',
		),
		'linkedin'   => array(
			'name'     => 'LinkedIn',
			'fa5_icon' => 'fab fa-linkedin-in',
		),
		'reddit'     => array(
			'name'     => 'Reddit',
			'fa5_icon' => 'fab fa-reddit',
		),
		'xing'       => array(
			'name'     => 'XING',
			'fa5_icon' => 'fab fa-xing',
		),
		'whatsapp'   => array(
			'name'     => 'WhatsApp',
			'fa5_icon' => 'fab fa-whatsapp',
		),
		'hackernews' => array(
			'name'     => 'Hacker News',
			'fa5_icon' => 'fab fa-yahoo',
		),
		'vk'         => array(
			'name'     => 'VK',
			'fa5_icon' => 'fab fa-vk',
		),
		'telegram'   => array(
			'name'     => 'Telegram',
			'fa5_icon' => 'fab fa-telegram',
		),
	);

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_share_buttons';
	}

	/**
	 * Widget Label.
	 *
	 * @return string
	 */
	public function get_title() {
		return 'Share Buttons';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-share-alt';
	}

	/**
	 * Retrieve the list of styles the share buttons widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_style_depends() {
		return [ 'font-awesome-5-all' ];
	}

	/**
	 * The render function/
	 */
	public function render() {
		$settings = $this->get_settings();
		if ( empty( $settings['share_buttons'] ) ) {
			return false;
		}

		foreach ( $settings['share_buttons'] as $key => $button ) {
			$brand       = $button['brand'];
			$icon        = $this->brands[ $brand ]['fa5_icon'];
			$name        = $this->brands[ $brand ]['name'];
			$preposition = 'on';
			$target      = 'target="_blank"';

			if ( $brand === 'email' ) {
				$target      = 'target="_self"';
				$preposition = 'by';
			}

			/* translators: %1$s - Preposition, %2$s - Name*/
			$label = sprintf( __( 'Share %1$s %2$s', 'neve' ), $preposition, $name );
			echo '<a class="eaw-share-link eaw-btn eaw-btn-' . esc_attr( $brand ) . '" href="' . esc_url( $this->get_social_share_link( $brand ) ) . '" ' . $target . ' aria-label="' . esc_attr( $label ) . '">';
			echo $settings['show_icon'] === 'yes' ? '<i class="eaw-icon ' . esc_attr( $icon ) . '"></i>' : '';
			echo $settings['show_label'] === 'yes' ? '<span class="eaw-label">' . $label . '</span>' : '';
			echo '</a>';
		}
		return true;
	}

	/**
	 * Returns the share link for a given social platform.
	 * Can also support a target url and a page title.
	 *
	 * @param string $brand The social network brand.
	 * @param null   $target_url The target url to share.
	 * @param null   $title The page title.
	 *
	 * @return string
	 */
	protected function get_social_share_link( $brand, $target_url = null, $title = null ) {

		if ( null === $target_url ) {
			$target_url = get_permalink();
		}

		if ( null === $title ) {
			$title = get_bloginfo( 'name' );
		}

		switch ( $brand ) {

			case 'facebook':
				$r = "https://facebook.com/sharer/sharer.php?u=$target_url";
				break;

			case 'twitter':
				$r = "https://twitter.com/intent/tweet/?text=$title&amp;url=$target_url";
				break;

			case 'google':
				$r = "https://plus.google.com/share?url=$target_url";
				break;

			case 'tumblr':
				$r = "https://www.tumblr.com/widgets/share/tool?posttype=link&amp;title=$title&amp;caption=$title&amp;content=$target_url&amp;canonicalUrl=$target_url&amp;shareSource=tumblr_share_button";
				break;

			case 'email':
				$r = "mailto:?subject=$title&amp;body=$target_url";
				break;

			case 'pinterest':
				$r = "https://pinterest.com/pin/create/button/?url=$target_url&amp;media=$target_url&amp;description=$title";
				break;

			case 'linkedin':
				$r = "https://www.linkedin.com/shareArticle?mini=true&amp;url=$target_url&amp;title=$title&amp;summary=$title&amp;source=$target_url";
				break;

			case 'reddit':
				$r = "https://reddit.com/submit/?url=$target_url";
				break;

			case 'xing':
				$r = "https://www.xing.com/app/user?op=share;url=$target_url;title=$title";
				break;

			case 'whatsapp':
				$r = "whatsapp://send?text=$title%20$target_url";
				break;

			case 'hackernews':
				$r = "https://news.ycombinator.com/submitlink?u=$target_url&amp;t=$title";
				break;

			case 'vk':
				$r = "http://vk.com/share.php?title=$title&amp;url=$target_url";
				break;

			case 'telegram':
				$r = "https://telegram.me/share/url?text=$title&amp;url=$target_url";
				break;

			default:
				$r = '#';
				break;
		}

		return $r;
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Social Share Buttons', 'neve' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'brand',
			array(
				'label'   => __( 'Platform', 'neve' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->__get_social_options(),
				'default' => 'facebook',
			)
		);

		$this->add_control(
			'share_buttons',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'brand' => 'facebook',
					),
					array(
						'brand' => 'google',
					),
					array(
						'brand' => 'twitter',
					),
					array(
						'brand' => 'linkedin',
					),
				),
				'title_field' => '<i class="fa fa-{{{brand}}}"></i> {{{ brand }}}',
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => __( 'Icon', 'neve' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'neve' ),
				'label_off'    => __( 'Hide', 'neve' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'        => __( 'Label', 'neve' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'neve' ),
				'label_off'    => __( 'Hide', 'neve' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'neve' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justify', 'neve' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
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
			'review_box',
			array(
				'label' => __( 'Styling options', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'share_button_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eaw-share-link.eaw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'share_button_label_typography',
				'label'    => __( 'Typography', 'neve' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eaw-share-link.eaw-btn .eaw-label',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'neve' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 25,
				),
				'range'     => array(
					'px' => array(
						'min' => 15,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .eaw-btn i'          => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .eaw-btn .eaw-label' => 'line-height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
}
