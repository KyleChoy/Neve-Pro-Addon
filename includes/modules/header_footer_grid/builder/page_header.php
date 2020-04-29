<?php
/**
 * Page Header class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Builder;

use HFG\Core\Builder\Abstract_Builder;
use HFG\Main;

/**
 * Class Page_Header
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Builder
 */
class Page_Header extends Abstract_Builder {
	/**
	 * Builder name.
	 */
	const BUILDER_NAME = 'page_header';

	/**
	 * Header init.compo
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'title', __( 'Page Header', 'neve' ) );
		$this->set_property(
			'description',
			sprintf(
				/* translators: %s link to documentation */
				esc_html__( 'Design your %1$s by dragging, dropping and resizing all the elements in real-time. %2$s.', 'neve' ),
				/* translators: %s builder type */
				$this->get_property( 'title' ),
				/* translators: %s link text */
				sprintf(
					'<br/><a target="_blank" href="https://docs.themeisle.com/article/1057-header-booster-documentation">%s</a>',
					esc_html__( 'Read full documentation', 'neve' )
				)
			)
		);
		$this->set_property(
			'instructions_array',
			array(
				'description' => sprintf(
					/* translators: %s builder type */
					esc_html__( 'Welcome to the %1$s builder! Click the “+” button to add a new component or follow the Quick Links.', 'neve' ),
					$this->get_property( 'title' )
				),
				'image'       => esc_url( get_template_directory_uri() . '/header-footer-grid/assets/images/customizer/hfg.mp4' ),
				'quickLinks'  => array(
					'hfg_page_header_layout_top_background' => array(
						'label' => esc_html__( 'Change Top Row Color', 'neve' ),
						'icon'  => 'dashicons-admin-appearance',
					),
				),
			)
		);
		$this->devices = array(
			'desktop' => __( 'Desktop', 'neve' ),
		);
		if ( version_compare( NEVE_VERSION, '2.5.4', '>=' ) ) {
			$this->devices['mobile'] = __( 'Mobile', 'neve' );
		}
		add_filter( 'hfg_template_locations', array( $this, 'register_template_location' ) );
		add_action( 'neve_after_header_hook', array( $this, 'render_on_neve_page_header' ), 1, 1 );
		add_action( 'customize_preview_init', array( $this, 'page_header_customize_preview_init' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'page_header_enqueue_scripts' ) );
	}

	/**
	 * Enqueue previewer scripts.
	 * Used to change page for Page Header Builder.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function page_header_customize_preview_init() {
		wp_register_script( 'hfg-page-header-preview', NEVE_PRO_INCLUDES_URL . 'modules/header_footer_grid/assets/js/page-header.preview.js', array( 'customize-preview' ), false, true );
		wp_localize_script(
			'hfg-page-header-preview',
			'pageHeader',
			array(
				'blog' => get_permalink( get_option( 'page_for_posts' ) ),
			)
		);
		wp_enqueue_script( 'hfg-page-header-preview' );
	}

	/**
	 * Enqueue control listener scripts.
	 * Used to change page for Page Header Builder.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function page_header_enqueue_scripts() {
		wp_register_script(
			'hfg-page-header-customize',
			NEVE_PRO_INCLUDES_URL . 'modules/header_footer_grid/assets/js/page-header.customize.js',
			array(
				'jquery',
				'customize-controls',
			),
			false,
			true
		);
		wp_localize_script(
			'hfg-page-header-customize',
			'pageHeader',
			array(
				'blog' => get_permalink( get_option( 'page_for_posts' ) ),
			)
		);
		wp_enqueue_script( 'hfg-page-header-customize' );
	}

	/**
	 * Invoke page header render on neve hook.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_on_neve_page_header() {
		if ( is_page_template() ) {
			return;
		}
		if ( ( ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && 'post' === get_post_type() ) || is_archive() || ( is_page() && ! is_front_page() ) ) {
			do_action( 'hfg_' . self::BUILDER_NAME . '_render' );
		}
	}

	/**
	 * Register a new template location for pro.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param array $template_locations An array with places to look for templates.
	 *
	 * @return mixed
	 */
	public function register_template_location( $template_locations ) {
		array_push( $template_locations, NEVE_PRO_SPL_ROOT . 'modules/header_footer_grid/templates/' );

		return $template_locations;
	}

	/**
	 * Method called via hook.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function load_template() {
		Main::get_instance()->load( 'page-header-wrapper' );
	}

	/**
	 * Get builder id.
	 *
	 * @return string Builder id.
	 */
	public function get_id() {
		return self::BUILDER_NAME;
	}

	/**
	 * Render builder row.
	 *
	 * @param string $device_id   The device id.
	 * @param string $row_id      The row id.
	 * @param array  $row_details Row data.
	 */
	public function render_row( $device_id, $row_id, $row_details ) {
		Main::get_instance()->load( 'row-page-wrapper', $row_id );
	}

	/**
	 * Return  the builder rows.
	 *
	 * @since   1.0.0
	 * @updated 1.0.1
	 * @access  protected
	 * @return array
	 */
	protected function get_rows() {
		return array(
			'top'    => array(
				'title'       => esc_html__( 'Page Header Top', 'neve' ),
				'description' => $this->get_property( 'description' ),
			),
			'bottom' => array(
				'title'       => esc_html__( 'Page Header Bottom', 'neve' ),
				'description' => $this->get_property( 'description' ),
			),
		);
	}
}
