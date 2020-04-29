<?php
/**
 * Team Member widget class
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Utils;

/**
 * Class Team_Member
 */
class Team_Member extends Elementor_Booster_Base {

	/**
	 * Array of sharing networks
	 *
	 * @var array
	 */
	public $brands = array(
		'500px'          => array(
			'name'     => '500px',
			'fa5_icon' => 'fab fa-500px',
		),
		'apple'          => array(
			'name'     => 'Apple',
			'fa5_icon' => 'fab fa-apple',
		),
		'behance'        => array(
			'name'     => 'Behance',
			'fa5_icon' => 'fab fa-behance',
		),
		'bitbucket'      => array(
			'name'     => 'BitBucket',
			'fa5_icon' => 'fab fa-bitbucket',
		),
		'codepen'        => array(
			'name'     => 'CodePen',
			'fa5_icon' => 'fab fa-codepen',
		),
		'delicious'      => array(
			'name'     => 'Delicious',
			'fa5_icon' => 'fab fa-delicious',
		),
		'deviantart'     => array(
			'name'     => 'DeviantArt',
			'fa5_icon' => 'fab fa-deviantart',
		),
		'digg'           => array(
			'name'     => 'Digg',
			'fa5_icon' => 'fa fab-digg',
		),
		'dribbble'       => array(
			'name'     => 'Dribbble',
			'fa5_icon' => 'fab fa-dribbble',
		),
		'email'          => array(
			'name'     => 'E-Mail',
			'fa5_icon' => 'fas fa-envelope',
		),
		'facebook'       => array(
			'name'     => 'Facebook',
			'fa5_icon' => 'fab fa-facebook-f',
		),
		'flickr'         => array(
			'name'     => 'Flicker',
			'fa5_icon' => 'fa fab-flickr',
		),
		'foursquare'     => array(
			'name'     => 'FourSquare',
			'fa5_icon' => 'fa fab-foursquare',
		),
		'github'         => array(
			'name'     => 'Github',
			'fa5_icon' => 'fab fa-github',
		),
		'google'         => array(
			'name'     => 'Google',
			'fa5_icon' => 'fab fa-google',
		),
		'hackernews'     => array(
			'name'     => 'Hacker News',
			'fa5_icon' => 'fab fa-yahoo',
		),
		'houzz'          => array(
			'name'     => 'Houzz',
			'fa5_icon' => 'fab fa-houzz',
		),
		'instagram'      => array(
			'name'     => 'Instagram',
			'fa5_icon' => 'fab fa-instagram',
		),
		'jsfiddle'       => array(
			'name'     => 'JS Fiddle',
			'fa5_icon' => 'fab fa-jsfiddle',
		),
		'linkedin'       => array(
			'name'     => 'LinkedIn',
			'fa5_icon' => 'fab fa-linkedin-in',
		),
		'medium'         => array(
			'name'     => 'Medium',
			'fa5_icon' => 'fab fa-medium',
		),
		'pinterest'      => array(
			'name'     => 'Pinterest',
			'fa5_icon' => 'fab fa-pinterest',
		),
		'product-hunt'   => array(
			'name'     => 'Product Hunt',
			'fa5_icon' => 'fab fa-product-hunt',
		),
		'reddit'         => array(
			'name'     => 'Reddit',
			'fa5_icon' => 'fab fa-reddit',
		),
		'slideshare'     => array(
			'name'     => 'Slide Share',
			'fa5_icon' => 'fab fa-slideshare',
		),
		'snapchat'       => array(
			'name'     => 'Snapchat',
			'fa5_icon' => 'fab fa-snapchat',
		),
		'soundcloud'     => array(
			'name'     => 'SoundCloud',
			'fa5_icon' => 'fab fa-soundcloud',
		),
		'spotify'        => array(
			'name'     => 'Spotify',
			'fa5_icon' => 'fab fa-spotify',
		),
		'stack-overflow' => array(
			'name'     => 'StackOverflow',
			'fa5_icon' => 'fab fa-stack-overflow',
		),
		'telegram'       => array(
			'name'     => 'Telegram',
			'fa5_icon' => 'fab fa-telegram',
		),
		'tripadvisor'    => array(
			'name'     => 'TripAdvisor',
			'fa5_icon' => 'fab fa-tripadvisor',
		),
		'tumblr'         => array(
			'name'     => 'Tumblr',
			'fa5_icon' => 'fab fa-tumblr',
		),
		'twitch'         => array(
			'name'     => 'Twitch',
			'fa5_icon' => 'fab fa-twitch',
		),
		'twitter'        => array(
			'name'     => 'Twitter',
			'fa5_icon' => 'fab fa-twitter',
		),
		'vimeo'          => array(
			'name'     => 'Vimeo',
			'fa5_icon' => 'fab fa-vimeo',
		),
		'vk'             => array(
			'name'     => 'VK',
			'fa5_icon' => 'fab fa-vk',
		),
		'whatsapp'       => array(
			'name'     => 'WhatsApp',
			'fa5_icon' => 'fab fa-whatsapp',
		),
		'wordpress'      => array(
			'name'     => 'WordPress',
			'fa5_icon' => 'fab fa-wordpress',
		),
		'xing'           => array(
			'name'     => 'XING',
			'fa5_icon' => 'fab fa-xing',
		),
		'yelp'           => array(
			'name'     => 'Yelp',
			'fa5_icon' => 'fab fa-yelp',
		),
		'youtube'        => array(
			'name'     => 'YouTube',
			'fa5_icon' => 'fab fa-youtube',
		),
	);

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_team_member';
	}
	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Team Member', 'neve' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-users';
	}

	/**
	 * Retrieve the list of styles the team member widget depended on.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_style_depends() {
		return [ 'font-awesome-5-all' ];
	}

	/**
	 * Get widget keywords
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'team', 'member', 'crew', 'staff', 'person', 'neve' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_image',
			[
				'label' => __( 'Image', 'neve' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => __( 'Photo', 'neve' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'full',
				'separator' => 'none',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Content', 'neve' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_link',
			[
				'label'        => __( 'Enable link', 'neve' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'neve' ),
				'label_off'    => __( 'Off', 'neve' ),
				'separator'    => 'before',
				'return_value' => 'on',
			]
		);

		$this->add_control(
			'link_on',
			[
				'label'     => __( 'Link on', 'plugin-domain' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'image',
				'options'   => [
					'image' => __( 'Image', 'neve' ),
					'title' => __( 'Title', 'neve' ),
					'both'  => __( 'Image and Title', 'neve' ),
				],
				'condition' => [
					'enable_link' => 'on',
				],
			]
		);

		$this->add_control(
			'member_link',
			[
				'label'         => __( 'Member Link', 'neve' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition'     => [
					'enable_link' => 'on',
				],
			]
		);

		$generic_names = [ 'John Smith', 'John Doe', 'Jane Doe', 'Joe Bloggs', 'Joe Schmoe' ];
		$name          = $generic_names[ array_rand( $generic_names ) ];
		$this->add_control(
			'title',
			[
				'label'       => __( 'Name', 'neve' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => $name,
				'placeholder' => __( 'Type Member Name', 'neve' ),
				'separator'   => 'before',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'job_title',
			[
				'label'       => __( 'Job Title', 'neve' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Employee', 'neve' ),
				'placeholder' => __( 'Type Member Job Title', 'neve' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'bio',
			[
				'label'       => __( 'Short Bio', 'neve' ),
				'description' => $this->get_allowed_html_desc( 'intermediate' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => __( 'Title HTML Tag', 'neve' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'h1' => [
						'title' => __( 'H1', 'neve' ),
						'icon'  => 'eicon-editor-h1',
					],
					'h2' => [
						'title' => __( 'H2', 'neve' ),
						'icon'  => 'eicon-editor-h2',
					],
					'h3' => [
						'title' => __( 'H3', 'neve' ),
						'icon'  => 'eicon-editor-h3',
					],
					'h4' => [
						'title' => __( 'H4', 'neve' ),
						'icon'  => 'eicon-editor-h4',
					],
					'h5' => [
						'title' => __( 'H5', 'neve' ),
						'icon'  => 'eicon-editor-h5',
					],
					'h6' => [
						'title' => __( 'H6', 'neve' ),
						'icon'  => 'eicon-editor-h6',
					],
				],
				'default'   => 'h2',
				'toggle'    => false,
				'separator' => 'before',
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_social',
			[
				'label' => __( 'Social Profiles', 'neve' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'name',
			[
				'label'          => __( 'Profile Name', 'neve' ),
				'type'           => Controls_Manager::SELECT2,
				'select2options' => [
					'allowClear' => false,
				],
				'options'        => $this->__get_social_options(),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'         => __( 'Profile Link', 'neve' ),
				'placeholder'   => __( 'Add your profile link', 'neve' ),
				'type'          => Controls_Manager::URL,
				'label_block'   => false,
				'autocomplete'  => false,
				'show_external' => false,
				'condition'     => [
					'name!' => 'email',
				],
				'dynamic'       => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'email',
			[
				'label'       => __( 'Email Address', 'neve' ),
				'placeholder' => __( 'Add your email address', 'neve' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'input_type'  => 'email',
				'condition'   => [
					'name' => 'email',
				],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'customize',
			[
				'label'          => __( 'Want To Customize?', 'neve' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Yes', 'neve' ),
				'label_off'      => __( 'No', 'neve' ),
				'return_value'   => 'yes',
				'style_transfer' => true,
			]
		);

		$repeater->start_controls_tabs(
			'_tab_icon_colors',
			[
				'condition' => [ 'customize' => 'yes' ],
			]
		);
		$repeater->start_controls_tab(
			'_tab_icon_normal',
			[
				'label' => __( 'Normal', 'neve' ),
			]
		);

		$repeater->add_control(
			'color',
			[
				'label'          => __( 'Text Color', 'neve' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				],
				'condition'      => [ 'customize' => 'yes' ],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'bg_color',
			[
				'label'          => __( 'Background Color', 'neve' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
				'condition'      => [ 'customize' => 'yes' ],
				'style_transfer' => true,
			]
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'_tab_icon_hover',
			[
				'label' => __( 'Hover', 'neve' ),
			]
		);

		$repeater->add_control(
			'hover_color',
			[
				'label'          => __( 'Text Color', 'neve' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}:focus' => 'color: {{VALUE}}',
				],
				'condition'      => [ 'customize' => 'yes' ],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'hover_bg_color',
			[
				'label'          => __( 'Background Color', 'neve' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}:focus' => 'background-color: {{VALUE}}',
				],
				'condition'      => [ 'customize' => 'yes' ],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'hover_border_color',
			[
				'label'          => __( 'Border Color', 'neve' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .neb-member-links > {{CURRENT_ITEM}}:focus' => 'border-color: {{VALUE}}',
				],
				'condition'      => [ 'customize' => 'yes' ],
				'style_transfer' => true,
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'profiles',
			[
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '<# print(name.slice(0,1).toUpperCase() + name.slice(1)) #>',
				'default'     => [
					[
						'link' => [ 'url' => 'https://facebook.com/' ],
						'name' => 'facebook',
					],
					[
						'link' => [ 'url' => 'https://twitter.com/' ],
						'name' => 'twitter',
					],
					[
						'link' => [ 'url' => 'https://linkedin.com/' ],
						'name' => 'linkedin',
					],
				],
			]
		);

		$this->add_control(
			'show_profiles',
			[
				'label'          => __( 'Show Profiles', 'neve' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Show', 'neve' ),
				'label_off'      => __( 'Hide', 'neve' ),
				'return_value'   => 'yes',
				'default'        => 'yes',
				'separator'      => 'before',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_image',
			[
				'label' => __( 'Photo', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => __( 'Width', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'%'  => [
						'min' => 20,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 700,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-figure' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => __( 'Height', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 700,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-figure' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .neb-member-figure img',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .neb-member-figure img',
			]
		);

		$this->add_control(
			'image_bg_color',
			[
				'label'     => __( 'Background Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-figure img' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'force_round_avatar',
			[
				'label'        => esc_html__( 'Force Round Avatar', 'neve' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label'      => __( 'Image size', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				],
				'condition'  => [
					'force_round_avatar' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-figure.force-round img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'force_round_avatar!' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_content',
			[
				'label' => __( 'Name, Job Title & Bio', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Content Padding', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Name', 'neve' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .neb-member-name',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .neb-member-name',
			]
		);

		$this->add_control(
			'_heading_job_title',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Job Title', 'neve' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'job_title_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'job_title_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-position' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job_title_typography',
				'selector' => '{{WRAPPER}} .neb-member-position',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'job_title_text_shadow',
				'selector' => '{{WRAPPER}} .neb-member-position',
			]
		);

		$this->add_control(
			'_heading_bio',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Short Bio', 'neve' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'bio_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-bio' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'bio_typography',
				'selector' => '{{WRAPPER}} .neb-member-bio',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'bio_text_shadow',
				'selector' => '{{WRAPPER}} .neb-member-bio',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_social',
			[
				'label' => __( 'Social Icons', 'neve' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'links_spacing',
			[
				'label'      => __( 'Right Spacing', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-links > a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_padding',
			[
				'label'      => __( 'Padding', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-links > a' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_icon_size',
			[
				'label'      => __( 'Icon Size', 'neve' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-links > a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'links_border',
				'selector' => '{{WRAPPER}} .neb-member-links > a',
			]
		);

		$this->add_responsive_control(
			'links_border_radius',
			[
				'label'      => __( 'Border Radius', 'neve' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .neb-member-links > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_tab_links_colors' );
		$this->start_controls_tab(
			'_tab_links_normal',
			[
				'label' => __( 'Normal', 'neve' ),
			]
		);

		$this->add_control(
			'links_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-links > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'links_bg_color',
			[
				'label'     => __( 'Background Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-links > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_links_hover',
			[
				'label' => __( 'Hover', 'neve' ),
			]
		);

		$this->add_control(
			'links_hover_color',
			[
				'label'     => __( 'Text Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-links > a:hover, {{WRAPPER}} .neb-member-links > a:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'links_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-links > a:hover, {{WRAPPER}} .neb-member-links > a:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'links_hover_border_color',
			[
				'label'     => __( 'Border Color', 'neve' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .neb-member-links > a:hover, {{WRAPPER}} .neb-member-links > a:focus' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'links_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'neb-member-name' );

		$this->add_inline_editing_attributes( 'job_title', 'basic' );
		$this->add_render_attribute( 'job_title', 'class', 'neb-member-position' );

		$this->add_inline_editing_attributes( 'bio', 'intermediate' );
		$this->add_render_attribute( 'bio', 'class', 'neb-member-bio' );

		$image_link   = '';
		$header_link  = '';
		$closing_link = '';
		if ( $settings['enable_link'] === 'on' && ! empty( $settings['member_link']['url'] ) ) {
			$closing_link  = '</a>';
			$external_link = ! empty( $settings['member_link']['is_external'] ) ? ' target="_blank"' : '';
			$nofollow      = ! empty( $settings['member_link']['nofollow'] ) ? ' rel="nofollow"' : '';
			if ( $settings['link_on'] === 'image' || $settings['link_on'] === 'both' ) {
				$image_link = '<a href="' . esc_url( $settings['member_link']['url'] ) . '" ' . $external_link . $nofollow . '>';
			}
			if ( $settings['link_on'] === 'title' || $settings['link_on'] === 'both' ) {
				$header_link = '<a href="' . esc_url( $settings['member_link']['url'] ) . '" ' . $external_link . $nofollow . '>';
			}
		}

		if ( $settings['image']['url'] || $settings['image']['id'] ) {
			$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
			$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
			$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );
			$settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation

			echo $image_link;
			echo '<figure class="neb-member-figure ' . ( $settings['force_round_avatar'] === 'yes' ? 'force-round' : '' ) . '">';
			echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
			echo '</figure>';
			echo $closing_link;
		}

		echo '<div class="neb-member-body">';

		if ( $settings['title'] ) {
			echo $header_link;
			printf(
				'<%1$s %2$s>%3$s</%1$s>',
				tag_escape( $settings['title_tag'] ),
				$this->get_render_attribute_string( 'title' ),
				wp_kses( $settings['title'], $this->get_allowed_html_tags( 'basic' ) )
			);
			echo $closing_link;
		}
		if ( $settings['job_title'] ) {
			echo '<div ';
			$this->print_render_attribute_string( 'job_title' );
			echo '>';
			echo wp_kses( $settings['job_title'], $this->get_allowed_html_tags( 'basic' ) );
			echo '</div>';
		}

		if ( $settings['bio'] ) {
			echo '<div ';
			$this->print_render_attribute_string( 'bio' );
			echo '>';
			echo '<p>';
			echo wp_kses( $settings['bio'], $this->get_allowed_html_tags( 'intermediate' ) );
			echo '</p>';
			echo '</div>';
		}

		if ( $settings['show_profiles'] && is_array( $settings['profiles'] ) ) {
			echo '<div class="neb-member-links">';
			foreach ( $settings['profiles'] as $profile ) {
				$selected_icon = $profile['name'];
				$icon_class    = $this->brands[ $selected_icon ]['fa5_icon'];
				$url           = esc_url( $profile['link']['url'] );

				if ( $selected_icon === 'email' ) {
					$url = 'mailto:' . antispambot( $profile['email'] );
				}

				printf(
					'<a target="_blank" rel="noopener" href="%s" class="elementor-repeater-item-%s"><i class="%s" aria-hidden="true"></i></a>',
					$url,
					esc_attr( $profile['_id'] ),
					esc_attr( $icon_class )
				);
			}
			echo '</div>';
		}
		echo '</div>';
	}
}
