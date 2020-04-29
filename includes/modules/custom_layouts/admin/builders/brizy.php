<?php
/**
 * Replace header, footer or hooks for Brizy page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Admin\Custom_Layouts_Cpt;
use Neve_Pro\Traits\Core;

/**
 * Class Brizy
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Brizy extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	function should_load() {
		return class_exists( 'Brizy_Editor_Post' );
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	public function add_styles() {
		$posts = Custom_Layouts_Cpt::get_custom_layouts();
		foreach ( $posts as $hook => $value ) {
			foreach ( $value as $pid => $priority ) {
				try {
					$post = \Brizy_Editor_Post::get( $pid );
					if ( ! $post ) {
						continue;
					}

					$main = new \Brizy_Public_Main( $post );
					add_filter( 'body_class', array( $main, 'body_class_frontend' ) );
					add_action( 'wp_enqueue_scripts', array( $main, '_action_enqueue_preview_assets' ), 9999 );
					add_action(
						'wp_head',
						function () use ( $post ) {
							$html = new \Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
							echo $html->get_head();
						}
					);
				} catch ( \Exception $exception ) {
					// The post type is not supported by Brizy hence Brizy should not be used render the post.
				}
			}
		}
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'brizy';
	}

	/**
	 * Load markup for current hook.
	 *
	 * @param int $post_id Layout id.
	 *
	 * @return mixed|void
	 */
	function render( $post_id ) {
		try {
			$post = \Brizy_Editor_Post::get( $post_id );
			if ( $post ) {
				$html    = new \Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
				$content = apply_filters( 'neve_post_content', $html->get_body() );
				echo apply_filters( 'neve_custom_layout_magic_tags', $content, $post_id );
			}
		} catch ( \Exception $exception ) {
			// The post type is not supported by Brizy hence Brizy should not be used render the post.
		}

		return true;
	}

}
