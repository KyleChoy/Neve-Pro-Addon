<?php
/**
 * Replace header, footer or hooks for Beaver Builder page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Modules\Custom_Layouts\Module;
use Neve_Pro\Traits\Core;

/**
 * Class Beaver
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Beaver extends Abstract_Builders {

	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	function should_load() {
		return class_exists( 'FLBuilderModel', false );
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	function add_styles() {
		return false;
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'beaver';
	}

	/**
	 * Load markup for current hook.
	 *
	 * @param int $post_id Layout id.
	 *
	 * @return mixed|void
	 */
	function render( $post_id ) {
		$content = \FLBuilderShortcodes::insert_layout(
			array(
				'id' => $post_id,
			)
		);
		echo apply_filters( 'neve_custom_layout_magic_tags', $content, $post_id );
		return true;
	}
}
