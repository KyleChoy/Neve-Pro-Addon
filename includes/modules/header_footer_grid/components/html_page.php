<?php
/**
 * Html Component Wrapper class extends Header Footer Grid Component.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Main;

/**
 * Class Html
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Html_Page extends Html {
	/**
	 * Holds the instance count.
	 * Starts at 1 since the base component is not altered.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected static $instance_count = 3;

	/**
	 * The supported magic tags and the callback function.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var array
	 */
	protected $magic_tags = array();

	/**
	 * Html constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
	}

	/**
	 * Html init.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		parent::init();
		$this->set_property(
			'description',
			sprintf(
				// translators: tags available
				__( 'You can use magic tags like %1$s, %2$s or %3$s which will be replaced automatically.', 'neve' ),
				'{' . __( 'title', 'neve' ) . '}',
				'{' . __( 'date', 'neve' ) . '}',
				'{' . __( 'author', 'neve' ) . '}'
			)
		);

		$this->magic_tags = array(
			'title'  => array( $this, 'replace_title' ),
			'date'   => array( $this, 'replace_date' ),
			'author' => array( $this, 'replace_author' ),
		);

		add_filter( 'neve_page_header_content', array( $this, 'filter_content_magic_tags' ) );
	}

	/**
	 * Function to replace the title magic tag.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return string
	 */
	public function replace_title() {
		add_filter(
			'neve_filter_toggle_content_parts',
			function ( $value, $item ) {
				if ( $item === 'title' ) {
					return false;
				}

				return $value;
			},
			101,
			2
		);
		if ( is_home() && get_option( 'show_on_front' ) === 'posts' ) {
			return '';
		}

		if ( get_option( 'show_on_front' ) === 'page' && is_home() ) {
			$blog_page_id = get_option( 'page_for_posts' );

			return get_the_title( $blog_page_id );
		}

		if ( is_archive() ) {
			return get_the_archive_title();
		}

		return get_the_title();
	}

	/**
	 * Function to replace the date magic tag.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return false|string
	 */
	public function replace_date() {
		return get_the_date( 'F j, Y' );
	}

	/**
	 * Function to replace the author magic tag.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @return string|null
	 */
	public function replace_author() {
		return get_the_author_meta( 'display_name', get_post_field( 'post_author', get_the_ID() ) );
	}

	/**
	 * Filter the custom html content.
	 *
	 * @since   1.0.0
	 * @access  public
	 *
	 * @param string $content The content.
	 *
	 * @return mixed
	 */
	public function filter_content_magic_tags( $content ) {
		if ( empty( $this->magic_tags ) ) {
			return $content;
		}

		foreach ( $this->magic_tags as $tag => $function ) {
			$value = call_user_func( $function );
			if ( $tag === 'author' || $tag === 'title' ) {
				$value = html_entity_decode( $value );
			}
			$content = str_replace( '{' . $tag . '}', wp_kses_post( $value ), $content );
		}

		return $content;
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'components/page-header-html' );
	}
}
