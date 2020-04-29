<?php
/**
 * Elementor booster widgets classes Wrapper
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;

use Elementor\Widget_Base;

/**
 * Class Elementor_Booster_Base
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
abstract class Elementor_Booster_Base extends Widget_Base {

	/**
	 * Array of sharing networks
	 *
	 * @var array
	 */
	public $brands = array();

	/**
	 * Set the category of the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'neve-elementor-widgets' );
	}

	/**
	 * Register widget controls
	 */
	protected function _register_controls() {
		do_action( 'neve_elementor_booster_start_register_controls', $this );

		$this->register_content_controls();

		$this->register_style_controls();

		do_action( 'neve_elementor_booster_end_register_controls', $this );
	}

	/**
	 * Register content controls
	 *
	 * @return void
	 */
	abstract protected function register_content_controls();

	/**
	 * Register style controls
	 *
	 * @return void
	 */
	abstract protected function register_style_controls();

	/**
	 * Get a translatable string with allowed html tags.
	 *
	 * @param string $level Allowed levels are basic and intermediate.
	 *
	 * @return string
	 */
	protected function get_allowed_html_desc( $level = 'basic' ) {
		if ( ! in_array( $level, [ 'basic', 'intermediate' ], true ) ) {
			$level = 'basic';
		}

		$tags_str = '<' . implode( '>,<', array_keys( $this->get_allowed_html_tags( $level ) ) ) . '>';

		/* translators: %1$s is allowed tags */

		return sprintf( __( 'This input field has support for the following HTML tags: %1$s', 'neve' ), '<code>' . esc_html( $tags_str ) . '</code>' );
	}

	/**
	 * Get a list of all the allowed html tags.
	 *
	 * @param string $level Allowed levels are basic and intermediate.
	 * @return array
	 */
	public function get_allowed_html_tags( $level = 'basic' ) {
		$allowed_html = [
			'b'      => [],
			'i'      => [],
			'u'      => [],
			'em'     => [],
			'br'     => [],
			'abbr'   => [
				'title' => [],
			],
			'span'   => [
				'class' => [],
			],
			'p'      => [
				'class' => [],
			],
			'strong' => [],
			'button' => [
				'class' => [],
				'type'  => [],
				'value' => [],
			],
		];

		if ( $level === 'intermediate' ) {
			$allowed_html['a'] = [
				'href'  => [],
				'title' => [],
				'class' => [],
				'id'    => [],
			];
		}

		return $allowed_html;
	}

	/**
	 * Gets the options for the repeater from the `$brands` array.
	 *
	 * @return array
	 */
	public function __get_social_options() {
		$options = array();
		foreach ( $this->brands as $id => $brand ) {
			$options[ $id ] = $brand['name'];
		}
		return $options;
	}

	/**
	 * Get all elementor page templates
	 *
	 * @param string $type Page template type.
	 *
	 * @return array
	 */
	static function get_page_templates( $type = null ) {
		$args = [
			'post_type'      => 'elementor_library',
			'posts_per_page' => -1,
		];

		if ( $type ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'elementor_library_type',
					'field'    => 'slug',
					'terms'    => $type,
				],
			];
		}

		$page_templates = get_posts( $args );
		if ( is_wp_error( $page_templates ) || empty( $page_templates ) ) {
			return array();
		}

		$options = array();
		foreach ( $page_templates as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		return $options;
	}
}
