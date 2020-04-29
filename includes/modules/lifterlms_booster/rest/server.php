<?php
/**
 * Rest Endpoints Handler.
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Rest
 */

namespace Neve_Pro\Modules\LifterLMS_Booster\Rest;

use WP_Query;

/**
 * Class Server
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Rest
 */
class Server {

	/**
	 * Initialize the rest functionality.
	 */
	public function register_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Register endpoints.
	 */
	public function register_endpoints() {

		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'/courses/page/(?P<page_number>\d+)/',
			array(
				'methods'  => \WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'get_courses' ),
				'args'     => array(
					'query'       => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => array( $this, 'validate_infinite_scroll_query' ),
					),
					'page_number' => array(
						'validate_callback' => 'is_numeric',
					),
				),
			)
		);

		register_rest_route(
			NEVE_PRO_REST_NAMESPACE,
			'memberships/page/(?P<page_number>\d+)/',
			array(
				'methods'  => \WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'get_memberships' ),
				'args'     => array(
					'query'       => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => array( $this, 'validate_infinite_scroll_query' ),
					),
					'page_number' => array(
						'validate_callback' => 'is_numeric',
					),
				),
			)
		);
	}

	/**
	 * Check if query is a valid JSON.
	 *
	 * @param string $query Query in JSON format.
	 *
	 * @return bool
	 */
	public function validate_infinite_scroll_query( $query ) {
		json_decode( $query );

		return ( json_last_error() === 0 );
	}


	/**
	 * Infinite scroll REST Api callback for courses.
	 *
	 * @param \WP_Rest_Request $request Rest request.
	 *
	 * @return string.
	 */
	public function get_courses( \WP_Rest_Request $request ) {

		if ( empty( $request['page_number'] ) ) {
			return new \WP_REST_Response(
				array(
					'code'    => 'error',
					'message' => esc_html__( 'Course infinite scroll error: Page number is missing.', 'neve' ),
				),
				400
			);
		}
		$args     = $request->get_json_params();
		$args     = json_decode( $args['query'], true );
		$per_page = get_option( 'lifterlms_shop_courses_per_page', 9 );
		$sorting  = explode( ',', get_option( 'lifterlms_shop_ordering', 'menu_order,ASC' ) );
		$order_by = empty( $sorting[0] ) ? 'menu_order' : $sorting[0];

		if ( 'menu_order' === $order_by ) {
			$order_by .= ' post_title';
		}
		$order    = empty( $sorting[1] ) ? 'ASC' : $sorting[1];
		$order_by = apply_filters( 'llms_courses_orderby', $order_by );
		$order    = apply_filters( 'llms_courses_order', $order );

		remove_all_actions( 'lifterlms_before_loop' );
		remove_all_actions( 'lifterlms_after_loop' );
		add_filter( 'llms_get_template_part', array( $this, 'remove_pagination' ), 10, 3 );
		add_action( 'lifterlms_before_loop_item', array( $this, 'wrap_lifter_card_media' ), 0 );
		add_action( 'lifterlms_before_loop_item_title', array( $this, 'wrap_close' ), 40 );
		add_action( 'lifterlms_before_loop_item_title', array( $this, 'wrap_lifter_card_content' ), 45 );
		add_action( 'lifterlms_after_loop_item', array( $this, 'wrap_close' ), 40 );

		ob_start();

		$args['paged']          = $request['page_number'];
		$args['post_status']    = 'publish';
		$args['post_type']      = 'course';
		$args['posts_per_page'] = $per_page;
		$args['order']          = $order;
		$args['orderby']        = $order_by;

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			lifterlms_loop( $query );
		}
		$markup = ob_get_contents();
		ob_end_clean();

		if ( $markup === '' ) {
			return new \WP_REST_Response(
				null,
				204
			);
		}

		return new \WP_REST_Response(
			array(
				'code'   => 'success',
				'markup' => $markup,
			),
			200
		);
	}

	/**
	 * Infinite scroll REST Api callback for memberships.
	 *
	 * @param \WP_Rest_Request $request Rest request.
	 *
	 * @return string.
	 */
	public function get_memberships( \WP_Rest_Request $request ) {

		if ( empty( $request['page_number'] ) ) {
			return new \WP_REST_Response(
				array(
					'code'    => 'error',
					'message' => esc_html__( 'Membership infinite scroll error: Page number is missing.', 'neve' ),
				),
				400
			);
		}

		$args     = $request->get_json_params();
		$args     = json_decode( $args['query'], true );
		$per_page = get_option( 'lifterlms_memberships_per_page', 9 );
		$order_by = empty( $sorting[0] ) ? 'menu_order' : $sorting[0];
		if ( 'menu_order' === $order_by ) {
			$order_by .= ' post_title';
		}
		$order = empty( $sorting[1] ) ? 'ASC' : $sorting[1];

		$order_by = apply_filters( 'llms_memberships_orderby', $order_by );
		$order    = apply_filters( 'llms_memberships_order', $order );

		$args['paged']          = $request['page_number'];
		$args['post_status']    = 'publish';
		$args['post_type']      = 'llms_membership';
		$args['posts_per_page'] = $per_page;
		$args['order']          = $order;
		$args['orderby']        = $order_by;

		$query = new WP_Query( $args );
		remove_all_actions( 'lifterlms_before_loop' );
		remove_all_actions( 'lifterlms_after_loop' );
		add_filter( 'llms_get_template_part', array( $this, 'remove_pagination' ), 10, 3 );
		add_action( 'lifterlms_before_loop_item', array( $this, 'wrap_lifter_card_media' ), 0 );
		add_action( 'lifterlms_before_loop_item_title', array( $this, 'wrap_close' ), 40 );
		add_action( 'lifterlms_before_loop_item_title', array( $this, 'wrap_lifter_card_content' ), 45 );
		add_action( 'lifterlms_after_loop_item', array( $this, 'wrap_close' ), 40 );

		ob_start();
		if ( $query->have_posts() ) {
			lifterlms_loop( $query );
		}
		$markup = ob_get_contents();
		ob_end_clean();

		if ( $markup === '' ) {
			return new \WP_REST_Response(
				null,
				204
			);
		}

		return new \WP_REST_Response(
			array(
				'code'   => 'success',
				'markup' => $markup,
			),
			200
		);
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
			return '';
		}

		return $template;
	}

}
