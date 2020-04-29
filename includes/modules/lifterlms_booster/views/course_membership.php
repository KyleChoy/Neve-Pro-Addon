<?php
/**
 * Class that modify Memberships and Courses archives pages.
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Views
 */

namespace Neve_Pro\Modules\LifterLMS_Booster\Views;

/**
 * Class Course_Membership
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Views
 */
class Course_Membership {

	/**
	 * Init function.
	 */
	public function register_hooks() {
		add_filter( 'lifterlms_loop_columns', array( $this, 'lifterlms_desktop_columns' ) );
		add_filter( 'llms_get_loop_list_classes', array( $this, 'lifterlms_responsive_columns' ) );
		add_action( 'wp', array( $this, 'run' ) );
	}

	/**
	 * Run the module.
	 */
	public function run() {
		$this->archives_pagination();
		$this->list_layout();
	}

	/**
	 * Courses and Memberships Archives pagination
	 */
	private function archives_pagination() {

		add_action( 'lifterlms_before_loop_item', array( $this, 'wrap_lifter_card_media' ), 0 );
		add_action( 'lifterlms_before_loop_item_title', array( $this, 'wrap_close' ), 40 );
		add_action( 'lifterlms_before_loop_item_title', array( $this, 'wrap_lifter_card_content' ), 45 );
		add_action( 'lifterlms_after_loop_item', array( $this, 'wrap_close' ), 40 );

		$theme_mod = '';
		if ( is_courses() ) {
			$theme_mod = 'neve_course_pagination_type';
		}
		if ( is_memberships() ) {
			$theme_mod = 'neve_membership_pagination_type';
		}
		if ( empty( $theme_mod ) ) {
			return;
		}
		$pagination_type = get_theme_mod( $theme_mod, 'number' );
		if ( $pagination_type === 'number' || neve_is_amp() ) {
			return;
		}
		add_filter( 'llms_get_template_part', array( $this, 'remove_pagination' ), 10, 3 );
		add_filter( 'neve_lifter_wrap_classes', array( $this, 'lifterlms_infinite_scroll' ) );
		add_action( 'lifterlms_after_loop', array( $this, 'load_more_courses_sentinel' ) );
	}

	/**
	 * List layout for courses / memberships.
	 */
	private function list_layout() {
		$theme_mod = '';
		if ( is_courses() ) {
			$theme_mod = 'neve_course_card_layout';
		}
		if ( is_memberships() ) {
			$theme_mod = 'neve_membership_card_layout';
		}
		if ( empty( $theme_mod ) ) {
			return;
		}
		$view = get_theme_mod( $theme_mod, 'grid' );
		if ( ! empty( $view ) && $view === 'list' ) {
			add_filter(
				'neve_lifter_wrap_classes',
				function ( $classes ) {
					return $classes . ' nv-lifter-list-view';
				}
			);
		}

	}

	/**
	 * Card media wrapper.
	 */
	public function wrap_lifter_card_media() {
		echo '<div class="nv-lifter-card-media-wrap">';
	}

	/**
	 * Close wrapper.
	 */
	public function wrap_close() {
		echo '</div>';
	}

	/**
	 * Card content wrapper.
	 */
	public function wrap_lifter_card_content() {
		echo '<div class="nv-lifter-card-content-wrap">';
	}


	/**
	 * Remove pagination when fetching posts.
	 *
	 * @param string $template Template.
	 * @param string $slug Slug.
	 * @param string $name Name.
	 *
	 * @return string;
	 */
	public function remove_pagination( $template, $slug, $name ) {
		if ( $slug === 'loop/pagination' ) {
			return;
		}

		return $template;
	}

	/**
	 * Add infinite scroll on LifterLMS container.
	 *
	 * @param string $classes Container classes.
	 *
	 * @return string
	 */
	public function lifterlms_infinite_scroll( $classes ) {
		return $classes . ' nv-infinite-scroll';
	}

	/**
	 * Add a sentinel to know when the request should happen.
	 */
	public function load_more_courses_sentinel() {
		$sentinel_name = '';
		if ( is_courses() ) {
			$sentinel_name = 'courses';
		}
		if ( is_memberships() ) {
			$sentinel_name = 'memberships';
		}
		if ( empty( $sentinel_name ) ) {
			return;
		}
		echo '<div class="lifter-load-more-posts load-more-' . esc_attr( $sentinel_name ) . '"><span class="nv-loader" style="display: none;"></span><span class="infinite-scroll-trigger"></span></div>';
	}

	/**
	 * Number of columns on desktop.
	 *
	 * @param int $default Default number of columns.
	 *
	 * @return int
	 */
	public function lifterlms_desktop_columns( $default ) {
		$theme_mod = '';
		if ( is_courses() ) {
			$theme_mod = 'neve_courses_per_row';
		}
		if ( is_memberships() ) {
			$theme_mod = 'neve_memberships_per_row';
		}
		if ( empty( $theme_mod ) ) {
			return $default;
		}
		$columns = get_theme_mod(
			$theme_mod,
			json_encode(
				array(
					'desktop' => 3,
					'tablet'  => 2,
					'mobile'  => 1,
				)
			)
		);
		$columns = json_decode( $columns, true );
		if ( ! empty( $columns ) && ! empty( $columns['desktop'] ) ) {
			return $columns['desktop'];
		}

		return $default;
	}

	/**
	 * Number of columns on responsive.
	 *
	 * @param array $classes Array of classes.
	 *
	 * @return array
	 */
	public function lifterlms_responsive_columns( $classes ) {
		$theme_mod = '';
		if ( is_courses() ) {
			$theme_mod = 'neve_courses_per_row';
		}
		if ( is_memberships() ) {
			$theme_mod = 'neve_memberships_per_row';
		}
		if ( empty( $theme_mod ) ) {
			return $classes;
		}

		$columns = get_theme_mod(
			$theme_mod,
			json_encode(
				array(
					'desktop' => 3,
					'tablet'  => 2,
					'mobile'  => 1,
				)
			)
		);
		$columns = json_decode( $columns, true );
		if ( ! empty( $columns ) ) {
			if ( ! empty( $columns['tablet'] ) ) {
				$classes[] = 'tablet-columns-' . $columns['tablet'];
			}
			if ( ! empty( $columns['mobile'] ) ) {
				$classes[] = 'mobile-columns-' . $columns['mobile'];
			}
		}

		return $classes;
	}

}
