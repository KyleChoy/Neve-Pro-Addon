<?php
/**
 * Class that adds the php editor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin;

/**
 * Class PHP_Editor_Admin
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */
class PHP_Editor_Admin {

	/**
	 * Init PHP Editor admin.
	 *
	 * @return bool
	 */
	public function init() {
		if ( ! $this->should_load() ) {
			return false;
		}
		add_action( 'admin_footer', array( $this, 'print_admin_js_template' ) );
		add_filter( 'admin_body_class', array( $this, 'custom_editor_body_class' ), 999 );
		add_action( 'before_delete_post', array( $this, 'clean_template_files' ) );
		return true;
	}

	/**
	 * Decide if the editor should load or not.
	 */
	private function should_load() {
		return current_user_can( 'administrator' );
	}

	/**
	 * Add templates for switch button and for editor.
	 */
	public function print_admin_js_template() {
		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}
		global $post;
		$value = $this->get_editor_mode( $post->ID );
		echo '<script id="neve-gutenberg-button-switch-mode" type="text/html">';
		echo '<div id="neve-editor-mode">';
		echo '<input id="neve-switch-editor-mode" type="hidden" name="neve-edit-mode" value="' . esc_attr( $value ) . '" />';
		echo '<button id="neve-switch-mode-button" type="button" class="button ' . ( $value === '0' ? 'button-primary' : '' ) . ' button-large">';
		echo '<span class="neve-switch-mode-on ' . ( $value === '0' ? 'hidden' : '' ) . '">';
		echo __( 'Back to WordPress Editor', 'neve-pro-addon' );
		echo '</span>';
		echo '<span class="neve-switch-mode-off ' . ( $value === '0' ? '' : 'hidden' ) . '">';
		echo __( 'Add Custom Code', 'neve-pro-addon' );
		echo '</span>';
		echo '</button>';
		echo '</div>';
		echo '</script>';

		$file_name     = get_post_meta( $post->ID, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';
		global $wp_filesystem;
		WP_Filesystem();
		$value = $wp_filesystem->get_contents( $file_path );

		if ( empty( $value ) ) {
			$value = '<!-- Add your PHP or HTML code here -->&#13;&#10;';
		}
		echo '<script id="neve-gutenberg-panel" type="text/html">';
		echo '<div id="neve-editor">';
		echo '<textarea id="neve-advanced-hook-php-code" name="neve-advanced-hook-php-code" class="wp-editor-area">' . htmlentities( $value ) . '</textarea>';
		echo '</div>';
		echo '</script>';
	}

	/**
	 * Check if current post is edited with Neve custom editor or not.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return mixed|string
	 */
	private function get_editor_mode( $post_id ) {
		$editor_value = get_post_meta( $post_id, 'neve_editor_mode', true );
		if ( empty( $editor_value ) ) {
			return '0';
		}

		return $editor_value;
	}

	/**
	 * Add class on body to know that the current page is edited with this custom editor
	 *
	 * @param string $classes Body classes.
	 *
	 * @return string
	 */
	public function custom_editor_body_class( $classes ) {
		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return $classes;
		}
		global $post;
		if ( $this->get_editor_mode( $post->ID ) === '1' ) {
			return $classes . ' neve-custom-editor-mode';
		}

		return $classes;
	}

	/**
	 * Remove template files when the post is deleted.
	 *
	 * @param int $post_id Post id.
	 */
	public function clean_template_files( $post_id ) {
		global $post_type;
		global $wp_filesystem;
		if ( $post_type !== 'neve_custom_layouts' ) {
			return;
		}

		$file_name     = get_post_meta( $post_id, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';

		WP_Filesystem();
		$wp_filesystem->delete( $file_path, false, 'f' );
	}

}
