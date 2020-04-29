<?php
/**
 * Php Editor to add custom code;
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Modules\Custom_Layouts\Module;
use Neve_Pro\Traits\Core;

/**
 * Class Php_Editor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Php_Editor extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	function should_load() {
		return true;
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
		return 'custom';
	}

	/**
	 * Load markup for current hook.
	 *
	 * @param int $post_id Layout id.
	 *
	 * @return mixed|void
	 */
	function render( $post_id ) {
		$file_name     = get_post_meta( $post_id, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';
		include_once( $file_path );

		return true;
	}

}
