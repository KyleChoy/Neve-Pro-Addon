<?php
/**
 * Replace header, footer or hooks with the default editor.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Modules\Custom_Layouts\Module;
use Neve_Pro\Traits\Core;

/**
 * Class Default_Editor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Default_Editor extends Abstract_Builders {
	use Core;

	/**
	 * Otter plugin instance.
	 *
	 * @var $otter_instance \ThemeIsle\GutenbergBlocks Otter instance.
	 */
	private $otter_instance;

	/**
	 * Default_Editor constructor.
	 */
	public function __construct() {
		if ( class_exists( '\ThemeIsle\GutenbergBlocks\Main', false ) ) {
			$this->otter_instance = \ThemeIsle\GutenbergBlocks\Main::instance( '' );
		} elseif ( class_exists( '\ThemeIsle\GutenbergBlocks', false ) ) {
			$this->otter_instance = new \ThemeIsle\GutenbergBlocks( '' );
		}

	}

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	public function should_load() {
		return true;
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	public function add_styles() {
		return false;
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'default';
	}

	/**
	 * Load markup for current hook.
	 *
	 * @param int $post_id Layout id.
	 *
	 * @return mixed|void
	 */
	function render( $post_id ) {
		$content_post = get_post( $post_id );
		$content      = apply_filters( 'neve_post_content', $content_post->post_content );
		$content      = apply_filters( 'neve_custom_layout_magic_tags', $content, $post_id );
		if ( defined( 'THEMEISLE_GUTENBERG_BLOCKS_VERSION' ) && version_compare( THEMEISLE_GUTENBERG_BLOCKS_VERSION, '1.2.2' ) >= 0 && method_exists( $this->otter_instance, 'render_server_side_css' ) ) {
			$this->otter_instance->render_server_side_css( $post_id );
			$this->render_otter_fa( $post_id );

		}
		echo wp_kses_post( $content );

		return true;
	}

	/**
	 * Render Font Awesome from otter in case one of the blocks that use fa is in any custom field.
	 *
	 * @param int $post_id Post id.
	 */
	private function render_otter_fa( $post_id ) {
		if ( has_block( 'themeisle-blocks/button-group', $post_id ) || has_block( 'themeisle-blocks/font-awesome-icons', $post_id ) || has_block( 'themeisle-blocks/sharing-icons', $post_id ) || has_block( 'themeisle-blocks/plugin-cards', $post_id ) || has_block( 'block', $post_id ) ) {
			wp_enqueue_style( 'font-awesome-5', WP_PLUGIN_URL . '/otter-blocks/assets/fontawesome/css/all.min.css' );
			wp_enqueue_style( 'font-awesome-4-shims', WP_PLUGIN_URL . '/otter-blocks/assets/fontawesome/css/v4-shims.min.css' );
		}
	}

}
