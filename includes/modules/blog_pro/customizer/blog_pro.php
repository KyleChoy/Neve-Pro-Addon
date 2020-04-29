<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-27
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\Blog_Pro\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve_Pro\Traits\Core;

/**
 * Class Blog_Pro
 *
 * @package Neve_Pro\Modules\Blog_Pro\Customizer
 */
class Blog_Pro extends Base_Customizer {
	use Core;

	/**
	 * Social icons defaults.
	 *
	 * @var array
	 */
	private $social_icons_default = array(
		array(
			'social_network'  => 'facebook',
			'title'           => 'Facebook',
			'visibility'      => 'yes',
			'display_desktop' => true,
			'display_mobile'  => true,
		),
		array(
			'social_network'  => 'twitter',
			'title'           => 'Twitter',
			'visibility'      => 'yes',
			'display_desktop' => true,
			'display_mobile'  => true,
		),
		array(
			'social_network'  => 'email',
			'title'           => 'Email',
			'visibility'      => 'yes',
			'display_desktop' => true,
			'display_mobile'  => true,
		),
	);

	/**
	 * Base initialization
	 */
	public function init() {

		parent::init();
		add_filter( 'neve_single_post_elements', array( $this, 'filter_single_post_elements' ) );
	}

	/**
	 * Add customizer section and controls
	 */
	public function add_controls() {
		$this->post_sorting();
		$this->read_more();
		$this->post_meta_advanced_options();
		$this->author_avatar();
		$this->related_posts();
		$this->sharing();
		$this->comments();
	}

	/**
	 * Sort posts controls.
	 */
	public function post_sorting() {
		$this->add_control(
			new Control(
				'neve_posts_order',
				array(
					'default'           => 'date_posted_desc',
					'sanitize_callback' => array( $this, 'sanitize_posts_sorting' ),
				),
				array(
					'label'    => esc_html__( 'Order posts by', 'neve' ),
					'section'  => 'neve_blog_archive_layout',
					'priority' => 25,
					'type'     => 'select',
					'choices'  => array(
						'date_posted_desc' => esc_html__( 'Date posted descending', 'neve' ),
						'date_posted_asc'  => esc_html__( 'Date posted ascending', 'neve' ),
						'date_updated'     => esc_html__( 'Date updated', 'neve' ),
					),
				)
			)
		);
	}

	/**
	 * Read More Options
	 */
	public function read_more() {
		/*
		 * Heading for Read More options
		 */
		$this->add_control(
			new Control(
				'neve_read_more_options',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Read More', 'neve' ),
					'section'          => 'neve_blog_archive_layout',
					'priority'         => 85,
					'class'            => 'advanced-sidebar-accordion',
					'accordion'        => false,
					'controls_to_wrap' => 2,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		/*
		 * Read More Text
		 */
		$this->add_control(
			new Control(
				'neve_read_more_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Read More', 'neve' ) . ' &raquo;',
				),
				array(
					'priority' => 90,
					'section'  => 'neve_blog_archive_layout',
					'label'    => esc_html__( 'Text', 'neve' ),
					'type'     => 'text',
				)
			)
		);

		/*
		 * Read More Style
		 */
		$this->add_control(
			new Control(
				'neve_read_more_style',
				array(
					'default'           => 'text',
					'sanitize_callback' => array( $this, 'sanitize_read_more_style' ),
				),
				array(
					'label'    => esc_html__( 'Style', 'neve' ),
					'section'  => 'neve_blog_archive_layout',
					'priority' => 95,
					'type'     => 'select',
					'choices'  => array(
						'text'             => esc_html__( 'Text', 'neve' ),
						'primary_button'   => esc_html__( 'Primary Button', 'neve' ),
						'secondary_button' => esc_html__( 'Secondary Button', 'neve' ),
					),
				)
			)
		);
	}

	/**
	 * Metadata advanced options
	 */
	public function post_meta_advanced_options() {
		/*
		 * Heading for Meta options
		 */
		$this->add_control(
			new Control(
				'neve_metadata_options',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Meta', 'neve' ),
					'section'          => 'neve_blog_archive_layout',
					'priority'         => 50,
					'class'            => 'advanced-sidebar-accordion',
					'accordion'        => false,
					'controls_to_wrap' => 2,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		/*
		 * Meta separator
		 */
		$this->add_control(
			new Control(
				'neve_metadata_separator',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html( '/' ),
				),
				array(
					'priority'    => 55,
					'section'     => 'neve_blog_archive_layout',
					'label'       => esc_html__( 'Separator', 'neve' ),
					'description' => esc_html__( 'For special characters make sure to use Unicode. For example > can be displayed using \003E.', 'neve' ),
					'type'        => 'text',
				)
			)
		);

	}

	/**
	 * Author avatar options
	 */
	public function author_avatar() {
		$this->add_control(
			new Control(
				'neve_author_avatar_size',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => json_encode(
						array(
							'desktop' => 20,
							'tablet'  => 20,
							'mobile'  => 20,
						)
					),
				),
				array(
					'label'       => esc_html__( 'Size', 'neve' ),
					'section'     => 'neve_blog_archive_layout',
					'units'       => array(
						'px',
					),
					'input_attr'  => array(
						'mobile'  => array(
							'min'          => 20,
							'max'          => 50,
							'default'      => 20,
							'default_unit' => 'px',
						),
						'tablet'  => array(
							'min'          => 20,
							'max'          => 50,
							'default'      => 20,
							'default_unit' => 'px',
						),
						'desktop' => array(
							'min'          => 20,
							'max'          => 50,
							'default'      => 20,
							'default_unit' => 'px',
						),
					),
					'input_attrs' => [
						'step'       => 1,
						'min'        => 20,
						'max'        => 50,
						'defaultVal' => [
							'mobile'  => 20,
							'tablet'  => 20,
							'desktop' => 20,
						],
						'units'      => [ 'px' ],
					],
					'priority'    => 80,
					'responsive'  => true,
				),
				version_compare( NEVE_VERSION, '2.6.3', '>=' ) ? 'Neve\Customizer\Controls\React\Responsive_Range' : 'Neve\Customizer\Controls\Responsive_Number'
			)
		);
	}

	/**
	 * Related Posts customizer controls
	 */
	public function related_posts() {

		/**
		 * Heading for Related posts options
		 */
		$this->add_control(
			new Control(
				'neve_related_posts',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Related Posts', 'neve' ),
					'section'          => 'neve_single_post_layout',
					'priority'         => 15,
					'class'            => 'advanced-sidebar-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 4,
					'active_callback'  => array( $this, 'related_posts_active_callback' ),
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		/**
		 * Related posts title - text
		 */
		$this->add_control(
			new Control(
				'neve_related_posts_title',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => esc_html__( 'Related Posts', 'neve' ),
				),
				array(
					'priority'        => 20,
					'section'         => 'neve_single_post_layout',
					'label'           => esc_html__( 'Title', 'neve' ),
					'type'            => 'text',
					'active_callback' => array( $this, 'related_posts_active_callback' ),
				)
			)
		);

		/**
		 * Related posts taxonomy - select
		 */
		$this->add_control(
			new Control(
				'neve_related_posts_taxonomy',
				array(
					'default'           => 'category',
					'sanitize_callback' => array( $this, 'sanitize_related_posts_taxonomy' ),
				),
				array(
					'label'           => esc_html__( 'Related Posts By', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'priority'        => 25,
					'type'            => 'select',
					'choices'         => array(
						'category' => esc_html__( 'Categories', 'neve' ),
						'post_tag' => esc_html__( 'Tags', 'neve' ),
					),
					'active_callback' => array( $this, 'related_posts_active_callback' ),
				)
			)
		);

		/**
		 * Related posts number - text
		 */
		$this->add_control(
			new Control(
				'neve_related_posts_number',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => 3,
				),
				array(
					'label'           => esc_html__( 'Number of Related Posts', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'input_attr'      => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
					'priority'        => 35,
					'type'            => 'number',
					'active_callback' => array( $this, 'related_posts_active_callback' ),
				)
			)
		);

		/**
		 * Related posts excerpt length
		 */
		$this->add_control(
			new Control(
				'neve_related_posts_excerpt_length',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => 25,
				),
				array(
					'label'           => esc_html__( 'Excerpt Length', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'type'            => 'range-value',
					'step'            => 5,
					'input_attr'      => array(
						'min'     => 5,
						'max'     => 300,
						'default' => 40,
					),
					'priority'        => 40,
					'active_callback' => array( $this, 'related_posts_active_callback' ),
				),
				'Neve\Customizer\Controls\Range'
			)
		);
	}

	/**
	 * Add single post sharing controls.
	 */
	public function sharing() {
		/**
		 * Heading for Related posts options
		 */
		$this->add_control(
			new Control(
				'neve_sharing_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Sharing icons', 'neve' ),
					'section'          => 'neve_single_post_layout',
					'priority'         => 45,
					'class'            => 'sharing-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 1,
					'active_callback'  => array( $this, 'sharing_active_callback' ),
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$default_value = apply_filters( 'neve_sharing_icons_default_value', $this->social_icons_default );
		$this->add_control(
			new Control(
				'neve_sharing_icons',
				array(
					'sanitize_callback' => array( $this, 'sanitize_sharing_icons_repeater' ),
					'default'           => json_encode( $default_value ),
				),
				array(
					'label'           => esc_html__( 'Choose your social icons', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'type'            => 'neve-repeater',
					'fields'          => array(
						'title'           => array(
							'type'  => 'text',
							'label' => esc_html__( 'Title', 'neve' ),
						),
						'social_network'  => array(
							'type'    => 'select',
							'label'   => __( 'Social Network', 'neve' ),
							'choices' => array(
								'facebook'  => 'Facebook',
								'twitter'   => 'Twitter',
								'email'     => 'Email',
								'pinterest' => 'Pinterest',
								'linkedin'  => 'LinkedIn',
								'tumblr'    => 'Tumblr',
								'reddit'    => 'Reddit',
								'whatsapp'  => 'WhatsApp',
								'sms'       => 'SMS',
								'vk'        => 'VKontakte',
							),
						),
						'display_desktop' => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Show on Desktop', 'neve' ),
						),
						'display_mobile'  => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Show on Mobile', 'neve' ),
						),
					),
					'priority'        => 50,
					'active_callback' => array( $this, 'sharing_active_callback' ),
				),
				'\Neve_Pro\Customizer\Controls\Repeater'
			)
		);
	}

	/**
	 * Add comments customizer controls.
	 */
	private function comments() {
		/**
		 * Heading for Related posts options
		 */
		$this->add_control(
			new Control(
				'neve_comments_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'            => esc_html__( 'Comments', 'neve' ),
					'section'          => 'neve_single_post_layout',
					'priority'         => 60,
					'class'            => 'comments-accordion',
					'accordion'        => true,
					'expanded'         => false,
					'controls_to_wrap' => 1,
					'active_callback'  => array( $this, 'comments_active_callback' ),
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		/*
		 * Comment Section Style
		 */
		$this->add_control(
			new Control(
				'neve_comment_section_style',
				array(
					'default'           => 'always',
					'sanitize_callback' => array( $this, 'sanitize_comment_section_style' ),
				),
				array(
					'label'           => esc_html__( 'Comment Section Style', 'neve' ),
					'section'         => 'neve_single_post_layout',
					'priority'        => 65,
					'type'            => 'select',
					'choices'         => array(
						'always' => esc_html__( 'Always Show', 'neve' ),
						'toggle' => esc_html__( 'Show/Hide mechanism', 'neve' ),
					),
					'active_callback' => array( $this, 'comments_active_callback' ),
				)
			)
		);
	}

	/**
	 * Sanitize read more button style
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_read_more_style( $value ) {
		$allowed_values = array( 'text', 'primary_button', 'secondary_button' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'number';
		}

		return esc_html( $value );
	}

	/**
	 * Filter single post elements
	 *
	 * @param array $input - controls registered by the theme.
	 *
	 * @return array
	 */
	public function filter_single_post_elements( $input ) {

		$new_controls = array(
			'author-biography' => __( 'Author Biography', 'neve' ),
			'related-posts'    => __( 'Related Posts', 'neve' ),
			'sharing-icons'    => __( 'Sharing Icons', 'neve' ),
		);

		$single_post_elements = array_merge( $input, $new_controls );

		return $single_post_elements;
	}

	/**
	 * Active callback for sharing controls.
	 */
	public function sharing_active_callback() {
		$default_order = apply_filters(
			'neve_single_post_elements_default_order',
			array(
				'title-meta',
				'thumbnail',
				'content',
				'tags',
				'comments',
			)
		);

		$content_order = get_theme_mod( 'neve_layout_single_post_elements_order', json_encode( $default_order ) );
		$content_order = json_decode( $content_order, true );
		if ( ! in_array( 'sharing-icons', $content_order, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Active callback for related posts controls.
	 */
	public function related_posts_active_callback() {
		$default_order = apply_filters(
			'neve_single_post_elements_default_order',
			array(
				'title-meta',
				'thumbnail',
				'content',
				'tags',
				'comments',
			)
		);

		$content_order = get_theme_mod( 'neve_layout_single_post_elements_order', json_encode( $default_order ) );
		$content_order = json_decode( $content_order, true );
		if ( ! in_array( 'related-posts', $content_order, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Active callback for comments controls.
	 */
	public function comments_active_callback() {
		$default_order = apply_filters(
			'neve_single_post_elements_default_order',
			array(
				'title-meta',
				'thumbnail',
				'content',
				'tags',
				'comments',
			)
		);

		$content_order = get_theme_mod( 'neve_layout_single_post_elements_order', json_encode( $default_order ) );
		$content_order = json_decode( $content_order, true );
		if ( ! in_array( 'comments', $content_order, true ) ) {
			return false;
		}

		return true;
	}


	/**
	 * Sanitize comment section style.
	 *
	 * @param string $value Value from the control.
	 *
	 * @return string
	 */
	public function sanitize_comment_section_style( $value ) {
		$allowed_values = array( 'toggle', 'always' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'always';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize sharing order.
	 *
	 * @param string $value Value from the control.
	 *
	 * @return string
	 */
	public function sanitize_sharing_icons_repeater( $value ) {
		$default_value = apply_filters( 'neve_sharing_icons_default_value', $this->social_icons_default );
		$fields        = array(
			'title',
			'social_network',
			'visibility',
		);
		$valid         = $this->sanitize_repeater_json( $value, $fields );

		if ( $valid === false ) {
			return json_encode( $default_value );
		}

		return $value;
	}

	/**
	 * Sanitize posts sorting
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_posts_sorting( $value ) {
		$allowed_values = array( 'date_posted_asc', 'date_posted_desc', 'date_updated' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'date_posted_desc';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize related posts taxonomy
	 *
	 * @param string $value Value from the control.
	 *
	 * @return string
	 */
	public function sanitize_related_posts_taxonomy( $value ) {
		$allowed_values = array( 'category', 'post_tag' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'category';
		}

		return esc_html( $value );
	}
}
