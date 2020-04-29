<?php
/**
 * Replace header, footer or hooks for Elementor page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Modules\Custom_Layouts\Module;
use Neve_Pro\Traits\Core;

/**
 * Class Elementor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Elementor extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	public function should_load() {
		return class_exists( '\Elementor\Plugin', false );
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
		return 'elementor';
	}

	/**
	 * Load markup for current hook.
	 *
	 * @param int $post_id Layout id.
	 *
	 * @return mixed|void
	 */
	function render( $post_id ) {
		$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id, true );
		echo apply_filters( 'neve_custom_layout_magic_tags', $content, $post_id );
		return true;
	}

}
