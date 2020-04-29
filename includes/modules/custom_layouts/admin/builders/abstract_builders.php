<?php
/**
 * Abstract class for builders compatibility.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;
use Neve_Pro\Traits\Conditional_Display;

/**
 * Class Abstract_Builders
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
abstract class Abstract_Builders {
	use Core;
	use Conditional_Display;

	/**
	 * Id of the current builder
	 *
	 * @var string
	 */
	protected $builder_id;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	abstract function should_load();

	/**
	 * Get builder id.
	 *
	 * @return string
	 */
	abstract function get_builder_id();

	/**
	 * Add actions to hooks.
	 */
	public function register_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ), 9 );
		add_filter( 'neve_custom_layout_magic_tags', array( $this, 'replace_magic_tags' ), 10, 2 );
	}

	/**
	 * Replace magic tags from post content.
	 *
	 * @param string $post_content Current post content.
	 * @param int    $post_id Post id.
	 * @return string
	 */
	public function replace_magic_tags( $post_content, $post_id ) {
		$condition_groups = json_decode( get_post_meta( $post_id, 'custom-layout-conditional-logic', true ), true );
		if ( empty( $condition_groups ) ) {
			return $post_content;
		}

		$archive_taxonomy = array( 'category', 'product_cat', 'post_tag', 'product_tag' );

		foreach ( $archive_taxonomy as $type ) {
			if ( $this->layout_has_condition( 'archive_taxonomy', $type, $condition_groups[0] ) ) {
				$category     = get_queried_object();
				$title        = $category->name;
				$description  = $category->description;
				$post_content = str_replace( '{title}', $title, $post_content );
				$post_content = str_replace( '{description}', $description, $post_content );
			}
		}

		if ( $this->layout_has_condition( 'archive_type', 'author', $condition_groups[0] ) ) {
			$author_id         = get_the_author_meta( 'ID' );
			$author_name       = get_the_author_meta( 'display_name' );
			$author_decription = get_the_author_meta( 'description' );
			$author_avatar     = get_avatar( $author_id, 32 );
			$post_content      = str_replace( '{author}', $author_name, $post_content );
			$post_content      = str_replace( '{author_description}', $author_decription, $post_content );
			$post_content      = str_replace( '{author_avatar}', $author_avatar, $post_content );
		}

		if ( $this->layout_has_condition( 'archive_type', 'date', $condition_groups[0] ) ) {
			$date         = get_the_archive_title();
			$post_content = str_replace( '{date}', $date, $post_content );
		}

		return $post_content;
	}

	/**
	 * Check if current custom layout has a specific condition.
	 *
	 * @param string $root Page category.
	 * @param string $end  Page type.
	 * @param array  $condition_groups List of conditions.
	 *
	 * @return bool
	 */
	private function layout_has_condition( $root, $end, $condition_groups ) {
		foreach ( $condition_groups as $index => $conditions ) {
			if ( $conditions['root'] === $root && $conditions['end'] === $end && $conditions['condition'] === '===' ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the builder that you used to edit a post.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return string
	 */
	public static function get_post_builder( $post_id ) {
		if ( get_post_meta( $post_id, 'neve_editor_mode', true ) === '1' ) {
			return 'custom';
		}

		if ( class_exists( '\Elementor\Plugin', false ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
			return 'elementor';
		}

		if ( class_exists( 'FLBuilderModel', false ) && get_post_meta( $post_id, '_fl_builder_enabled', true ) ) {
			return 'beaver';
		}

		if ( class_exists( 'Brizy_Editor_Post', false ) ) {
			try {
				$post = \Brizy_Editor_Post::get( $post_id );
				if ( $post->uses_editor() ) {
					return 'brizy';
				}
			} catch ( \Exception $exception ) {
				// The post type is not supported by Brizy hence Brizy should not be used render the post.
			}
		}

		return 'default';
	}

	/**
	 * Abstract function that needs to be implemented in Builders classes.
	 * It loads the markup based on current hook.
	 *
	 * @param int $id Layout id.
	 *
	 * @return mixed
	 */
	abstract function render( $id );

	/**
	 * Function that enqueues styles if needed.
	 */
	abstract function add_styles();
}
