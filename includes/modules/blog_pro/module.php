<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-27
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\Blog_Pro;

use Neve_Pro\Core\Abstract_Module;
use Neve_Pro\Modules\Header_Footer_Grid\Components\Icons;

/**
 * Class Module  - main class for the module
 * Enqueue scripts, style
 * Render functions
 *
 * @package Neve_Pro\Modules\Blog_Pro
 */
class Module extends Abstract_Module {

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug        The slug of the module.
	 * @property string  $this->name        The pretty name of the module.
	 * @property string  $this->description The description of the module.
	 * @property string  $this->order       Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active      Optional. Default `false`. The state of the module by default.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug          = 'blog_pro';
		$this->name          = __( 'Blog Booster', 'neve' );
		$this->description   = __( 'Give a huge boost to your entire blogging experience with features specially designed for increased user experience.', 'neve' );
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1059-blog-booster-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);
		$this->order         = 4;
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	public function should_load() {
		return $this->settings->is_module_active( $this->slug );
	}

	/**
	 * Run Blog Pro Module
	 */
	public function run_module() {
		add_filter( 'kses_allowed_protocols', array( $this, 'custom_allowed_protocols' ), 1000 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'meta_custom_separator' ) );
		add_filter( 'neve_read_more_args', array( $this, 'get_read_more_args' ) );
		add_action( 'neve_layout_single_post_author_biography', array( $this, 'author_biography_render' ) );
		add_action( 'neve_do_related_posts', array( $this, 'related_posts_render' ) );
		add_action( 'pre_get_posts', array( $this, 'order_posts' ), 1 );
		add_action( 'neve_do_sharing', array( $this, 'render_sharing_icons' ) );
		add_action( 'init', array( $this, 'count_post_words' ) );
		add_filter( 'neve_meta_filter', array( $this, 'add_reading_time_meta' ) );
		add_filter( 'neve_do_read_time', array( $this, 'render_read_time_meta' ) );
		add_action( 'save_post', array( $this, 'update_number_of_words' ) );
		add_action( 'neve_do_comment_area', array( $this, 'before_comment_area' ), 0 );
		add_action( 'neve_do_comment_area', array( $this, 'after_comment_area' ), PHP_INT_MAX );
		add_filter( 'neve_pro_filter_customizer_modules', array( $this, 'add_customizer_classes' ) );
	}

	/**
	 * Add customizer classes.
	 *
	 * @param array $classes loaded classes.
	 *
	 * @return array
	 */
	public function add_customizer_classes( $classes ) {
		$classes[] = 'Modules\Blog_Pro\Customizer\Blog_Pro';

		return $classes;
	}

	/**
	 * Enqueue module styles
	 */
	public function enqueue_style() {
		$this->rtl_enqueue_style( 'neve-blog-pro', NEVE_PRO_INCLUDES_URL . 'modules/blog_pro/assets/style.min.css', array(), NEVE_PRO_VERSION );
	}

	/**
	 * Get read more text options from customizer
	 *
	 * @return bool|array - text and style
	 */
	public function get_read_more_args() {

		$read_more_text = get_theme_mod( 'neve_read_more_text', esc_html__( 'Read More', 'neve' ) . ' &raquo;' );
		if ( empty( $read_more_text ) ) {
			return false;
		}
		$read_more_side = get_theme_mod( 'neve_read_more_style', 'text' );

		$read_more_classes = '';
		if ( $read_more_side === 'primary_button' ) {
			$read_more_classes = 'button button-primary';
		}
		if ( $read_more_side === 'secondary_button' ) {
			$read_more_classes = 'button button-secondary';
		}

		$args = array(
			'text'    => $read_more_text,
			'classes' => $read_more_classes,
		);

		return $args;
	}

	/**
	 * Change metadata separator according to the customizer setting
	 */
	public function meta_custom_separator() {

		$separator = get_theme_mod( 'neve_metadata_separator', esc_html( '/' ) );

		$custom_css  = '';
		$custom_css .= '.nv-meta-list li:not(:last-child):after { content:"' . esc_html( $separator ) . '" }';

		wp_add_inline_style( 'neve-style', $custom_css );
	}


	/**
	 * Render author biography
	 */
	public function author_biography_render() {

		$author_email = get_the_author_meta( 'user_email' );
		$avatar_url   = get_avatar_url( $author_email );

		$first_name  = get_the_author_meta( 'user_firstname' );
		$last_name   = get_the_author_meta( 'user_lastname' );
		$author_name = esc_html( $first_name ) . ' ' . esc_html( $last_name );

		$author_description = wp_kses_post( get_the_author_meta( 'description' ) );

		$section_markup  = '';
		$section_markup .= '<div class="nv-author-biography">';
		$section_markup .= '<img src="' . esc_url( $avatar_url ) . '" alt="nv-author-image" class="nv-author-bio-image">';
		$section_markup .= '<div class="nv-author-bio-text-wrapper">';
		if ( ! empty( $first_name ) || ! empty( $last_name ) ) {
			$section_markup .= '<h4 class="nv-author-bio-name">' . wp_kses_post( $author_name ) . '</h4>';
		}
		if ( ! empty( $author_description ) ) {
			$section_markup .= '<p class="nv-author-bio-desc">' . wp_kses_post( $author_description ) . '</p>';
		}
		$section_markup .= '</div>';
		$section_markup .= '</div>';

		echo $section_markup;
	}

	/**
	 * Render related posts
	 */
	public function related_posts_render() {

		global $post;

		$default_title   = esc_html__( 'Related Posts', 'neve' );
		$section_title   = get_theme_mod( 'neve_related_posts_title', $default_title );
		$taxonomy        = get_theme_mod( 'neve_related_posts_taxonomy', 'category' );
		$number_of_posts = get_theme_mod( 'neve_related_posts_number', 3 );

		$current_taxonomy_ids = wp_get_object_terms(
			$post->ID,
			$taxonomy,
			array(
				'fields' => 'ids',
			)
		);

		$args = array(
			'posts_per_page'      => $number_of_posts,
			'orderby'             => 'date',
			'ignore_sticky_posts' => true,
			'post__not_in'        => array( $post->ID ),
		);

		if ( $taxonomy === 'post_tag' ) {
			$args['tag__in'] = $current_taxonomy_ids;
		} else {
			$args['cat'] = $current_taxonomy_ids;
		}

		$loop = new \WP_Query( $args );

		if ( $loop->have_posts() ) { ?>
			<div class="nv-related-posts">
				<div class="section-title"><h2><?php echo $section_title; ?></h2></div>
				<div class="posts-wrapper row">
					<?php
					while ( $loop->have_posts() ) {
						$loop->the_post();
						?>
						<div class="related-post col-12 col-sm-4">
							<div class="content">
								<?php
								$post_thumbnail = get_the_post_thumbnail_url();
								if ( ! empty( $post_thumbnail ) ) :
									?>
									<div><img src="<?php the_post_thumbnail_url(); ?>" alt=""></div>
									<?php
								endif;
								$title = get_the_title();
								if ( ! empty( $title ) ) :
									$link = get_permalink();
									?>
									<h3 class="title entry-title"><a
												href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a>
									</h3>
								<?php endif; ?>
								<div class="category nv-meta-list"><?php the_category(); ?></div>
								<?php
								$excerpt = get_the_excerpt();
								if ( ! empty( $excerpt ) ) :
									?>
									<div class="descripiton excerpt-wrap">
										<?php
										add_filter(
											'excerpt_length',
											array(
												$this,
												'related_posts_excerpt_length',
											),
											10
										);
										the_excerpt();
										remove_filter(
											'excerpt_length',
											array(
												$this,
												'related_posts_excerpt_length',
											),
											10
										);
										?>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		wp_reset_postdata();
	}

	/**
	 * Custom excerpt length for related posts
	 */
	public function related_posts_excerpt_length() {
		$excerpt_length = get_theme_mod( 'neve_related_posts_excerpt_length', 25 );
		$excerpt_length = round( $excerpt_length );

		return absint( $excerpt_length );
	}

	/**
	 * Order posts by date ( asc / desc ) or by last edited.
	 *
	 * @param \WP_Query $query Main Query.
	 */
	public function order_posts( $query ) {
		$order = get_theme_mod( 'neve_posts_order', 'date_posted_desc' );
		if ( ! is_admin() && $query->is_main_query() ) {
			if ( $order === 'date_updated' ) {
				$query->set( 'orderby', 'modified' );
			}
			if ( $order === 'date_posted_asc' ) {
				$query->set( 'order', 'asc' );
			}
		}
	}

	/**
	 * Render sharing icons.
	 */
	public function render_sharing_icons() {
		$post_categories = strip_tags( get_the_category_list( ',' ) );
		$post_title      = get_the_title();
		$post_link       = urlencode( get_the_permalink() );
		$email_title     = str_replace( '&', '%26', $post_title );
		$icon_size       = 15;

		$link_map = array(
			'facebook'  => array(
				'link' => add_query_arg(
					array(
						'u' => $post_link,
					),
					'https://www.facebook.com/sharer.php'
				),
				'icon' => Icons::get_instance()->get_single_icon( 'facebook', $icon_size ),

			),
			'twitter'   => array(
				'link' => add_query_arg(
					array(
						'url'      => $post_link,
						'text'     => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) ),
						'hashtags' => $post_categories,
					),
					'http://twitter.com/share'
				),
				'icon' => Icons::get_instance()->get_single_icon( 'twitter', $icon_size ),
			),
			'email'     => array(
				'link'   => add_query_arg(
					array(
						'subject' => wp_strip_all_tags( $email_title ),
						'body'    => $post_link,
					),
					'mailto:'
				),
				'icon'   => Icons::get_instance()->get_single_icon( 'envelope', $icon_size ),
				'target' => '0',
			),
			'pinterest' => array(
				'link' => 'https://pinterest.com/pin/create/bookmarklet/?media=' . get_the_post_thumbnail_url() . '&url=' . $post_link . '&description=' . $post_title,
				'icon' => Icons::get_instance()->get_single_icon( 'pinterest', $icon_size ),
			),
			'linkedin'  => array(
				'link' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_link . '&title=' . urlencode( $post_title ) . '&source=' . urlencode( get_bloginfo( 'name' ) ),
				'icon' => Icons::get_instance()->get_single_icon( 'linkedin', $icon_size ),
			),
			'tumblr'    => array(
				'link' => 'http://www.tumblr.com/share/link?url=' . $post_link . '&title=' . $post_title,
				'icon' => Icons::get_instance()->get_single_icon( 'tumblr', $icon_size ),
			),
			'reddit'    => array(
				'link' => 'https://reddit.com/submit?url=' . $post_link . '&title=' . $post_title,
				'icon' => Icons::get_instance()->get_single_icon( 'reddit', $icon_size ),
			),
			'whatsapp'  => array(
				'link'   => 'https://wa.me/?text=' . $post_link,
				'icon'   => Icons::get_instance()->get_single_icon( 'whatsapp', $icon_size ),
				'target' => '0',
			),
			'sms'       => array(
				'link'   => 'sms://?&body=' . $post_title . ' - ' . $post_link,
				'icon'   => Icons::get_instance()->get_single_icon( 'comments', $icon_size ),
				'target' => '0',
			),
			'vk'        => array(
				'link' => 'http://vk.com/share.php?url=' . urlencode( $post_link ),
				'icon' => Icons::get_instance()->get_single_icon( 'vk', $icon_size ),
			),
		);

		$default_value = apply_filters(
			'neve_sharing_icons_default_value',
			array(
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
			)
		);
		$sharing_icons = get_theme_mod( 'neve_sharing_icons', json_encode( $default_value ) );
		$sharing_icons = json_decode( $sharing_icons, true );
		if ( empty( $sharing_icons ) ) {
			return;
		}

		echo '<div class="nv-post-share">';
		echo '<ul>';
		echo '<li class="nv-social-icon social-share">';
		echo Icons::get_instance()->get_single_icon( 'share-alt', $icon_size );
		echo '</li>';

		foreach ( $sharing_icons as $icon => $values ) {
			if ( $values['visibility'] !== 'yes' ) {
				continue;
			}

			$link_map_item = $link_map[ $values['social_network'] ];
			$is_blank      = ! ( isset( $link_map_item['target'] ) && $link_map_item['target'] === 0 );
			$hide_mobile   = isset( $values['display_mobile'] ) && $values['display_mobile'] === false;
			$hide_desktop  = isset( $values['display_desktop'] ) && $values['display_desktop'] === false;
			$classes       = '';
			if ( $hide_mobile ) {
				$classes .= ' hide-mobile ';
			}
			if ( $hide_desktop ) {
				$classes .= ' hide-desktop ';
			}

			echo '<li class="nv-social-icon social-' . esc_attr( $values['social_network'] ) . esc_attr( $classes ) . '">';
			echo '<a ' . ( $is_blank ? 'target="_blank"' : '' ) . ' title="' . esc_attr( $values['title'] ) . '" href="' . esc_url( $link_map_item['link'] ) . '" class="' . esc_attr( $values['social_network'] ) . '">';
			echo $link_map_item['icon'];
			echo '</a>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}

	/**
	 * Count words for every post and store the number in a meta field.
	 * This actions happens only once for posts that already exists.
	 */
	public function count_post_words() {
		$posts_have_nb_of_words = get_option( 'posts_have_nb_of_words', 'no' );
		if ( $posts_have_nb_of_words === 'yes' ) {
			return;
		}
		$args       = array(
			'post_type'      => 'post',
			'posts_per_page' => - 1,
		);
		$post_query = new \WP_Query( $args );
		if ( ! $post_query->have_posts() ) {
			return;
		}

		while ( $post_query->have_posts() ) {
			$post_query->the_post();

			$post_id    = get_the_ID();
			$word_count = $this->get_number_of_words( $post_id );
			update_post_meta( $post_id, 'nb_of_words', $word_count );
		}

		update_option( 'posts_have_nb_of_words', 'yes' );
	}


	/**
	 * Get number of words for a post.
	 *
	 * @param int $pid Post id.
	 *
	 * @return int
	 */
	private function get_number_of_words( $pid ) {
		$words_per_minute = apply_filters( 'neve_words_per_minute', 200 );
		$content          = get_post_field( 'post_content', $pid );
		$number_of_images = substr_count( strtolower( $content ), '<img ' );
		$content          = strip_shortcodes( $content );
		$content          = wp_strip_all_tags( $content );
		$word_count       = count( preg_split( '/\s+/', $content ) );
		if ( $number_of_images !== 0 ) {
			$additional_words_for_images = $this->calculate_images( $number_of_images, $words_per_minute );
			$word_count                 += $additional_words_for_images;
		}

		return $word_count;
	}

	/**
	 * Adds additional reading time for images
	 *
	 * Calculate additional reading time added by images in posts. Based on calculations by Medium.
	 * https://blog.medium.com/read-time-and-you-bc2048ab620c
	 *
	 * @param int   $total_images number of images in post.
	 * @param array $wpm          words per minute.
	 *
	 * @return int  Additional time added to the reading time by images.
	 */
	public function calculate_images( $total_images, $wpm ) {
		$additional_time = 0;
		// For the first image add 12 seconds, second image add 11, ..., for image 10+ add 3 seconds.
		for ( $i = 1; $i <= $total_images; $i ++ ) {
			if ( $i >= 10 ) {
				$additional_time += 3 * (int) $wpm / 60;
			} else {
				$additional_time += ( 12 - ( $i - 1 ) ) * (int) $wpm / 60;
			}
		}

		return $additional_time;
	}

	/**
	 * Add estimated reading time in meta fields.
	 *
	 * @param array $meta_fields Meta fields.
	 *
	 * @return mixed
	 */
	public function add_reading_time_meta( $meta_fields ) {
		$meta_fields['reading'] = __( 'Estimated reading time', 'neve' );

		return $meta_fields;
	}

	/**
	 * Output function for post read time.
	 */
	public function render_read_time_meta() {
		$post_id    = get_the_ID();
		$word_count = get_post_meta( $post_id, 'nb_of_words', true );
		if ( empty( $word_count ) && $word_count !== 0 ) {
			return '';
		}
		$words_per_minute = apply_filters( 'neve_words_per_minute', 200 );
		$reading_time     = ceil( $word_count / $words_per_minute );
		if ( $reading_time < 1 ) {
			$value = __( 'Less than 1 min read', 'neve' );

			return $value;
		}

		/* translators: %s - reading time */

		return sprintf( __( '%s min read', 'neve' ), $reading_time );
	}

	/**
	 * Update number of words on post save.
	 *
	 * @param int $post_id Post id.
	 */
	public function update_number_of_words( $post_id ) {
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );
		if ( $post_type !== 'post' ) {
			return;
		}

		$word_count = $this->get_number_of_words( $post_id );
		update_post_meta( $post_id, 'nb_of_words', $word_count );
	}

	/**
	 * Render toggle button and wrap comment area content.
	 */
	public function before_comment_area() {
		$post_id = get_the_ID();
		if ( ! comments_open( $post_id ) ) {
			return;
		}
		$comment_area = get_theme_mod( 'neve_comment_section_style', 'always' );
		if ( $comment_area !== 'toggle' || neve_is_amp() ) {
			return;
		}

		$text_show = apply_filters( 'neve_show_comments_button_text', __( 'Show comments', 'neve' ) );
		$text_hide = apply_filters( 'neve_hide_comments_button_text', __( 'Hide comments', 'neve' ) );
		?>
		<script type="text/javascript">
					document.addEventListener( 'DOMContentLoaded', function() {
						var trigger = document.getElementById( 'toggle-comment-area' );
						trigger.addEventListener( 'click', function() {
							var wrapper = document.getElementById( 'comment-area-wrapper' );
							if ( wrapper.classList.contains( 'nv-comments-hidden' ) ) {
								wrapper.classList.remove( 'nv-comments-hidden' );
								trigger.innerHTML = "<?php echo esc_html( $text_hide ); ?>";
							} else {
								wrapper.classList.add( 'nv-comments-hidden' );
								trigger.innerHTML = "<?php echo esc_html( $text_show ); ?>";
							}
						} );
					} );
		</script>
		<?php
		$wrap_class  = isset( $_GET['replytocom'] ) ? '' : 'nv-comments-hidden';
		$button_text = isset( $_GET['replytocom'] ) ? $text_hide : $text_show;
		echo '<button id="toggle-comment-area" class="button button-primary">' . esc_html( $button_text ) . '</button>';
		echo '<div id="comment-area-wrapper" class="' . esc_attr( $wrap_class ) . '">';
	}

	/**
	 * Close content area wrapper.
	 */
	public function after_comment_area() {
		$post_id = get_the_ID();
		if ( ! comments_open( $post_id ) ) {
			return;
		}
		$comment_area = get_theme_mod( 'neve_comment_section_style', 'always' );
		if ( $comment_area !== 'toggle' || neve_is_amp() ) {
			return;
		}
		echo '</div>';
	}

	/**
	 * Add extra protocols to list of allowed protocols.
	 *
	 * @param array $protocols List of protocols from core.
	 *
	 * @return array Updated list including extra protocols added.
	 */
	public function custom_allowed_protocols( $protocols ) {
		$protocols[] = 'whatsapp';
		$protocols[] = 'sms';

		return $protocols;
	}
}
