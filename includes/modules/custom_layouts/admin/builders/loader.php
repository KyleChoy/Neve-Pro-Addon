<?php
/**
 * Factory for loading the builders compatibility.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */


namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Admin\Custom_Layouts_Cpt;
use Neve_Pro\Traits\Conditional_Display;

/**
 * Class Loader
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Loader {

	use Conditional_Display;

	/**
	 * Possible builders list.
	 *
	 * @var array List of them.
	 */
	public $builders_list = [
		'Default_Editor',
		'Php_Editor',
		'Elementor',
		'Brizy',
		'Beaver',
	];
	/**
	 * List of possible builders.
	 *
	 * @var Abstract_Builders[] $available_builders List.
	 */
	private $available_builders = [];
	/**
	 * Hooks map to check.
	 *
	 * @var array Hooks map.
	 */
	protected $hooks_map = array(
		'neve_do_header'       => array(
			'hooks_to_deactivate' => array( 'neve_do_header', 'neve_do_top_bar' ),
			'posts_map_key'       => 'header',
		),
		'neve_do_footer'       => array(
			'hooks_to_deactivate' => array( 'neve_do_footer' ),
			'posts_map_key'       => 'footer',
		),
		'neve_do_404'          => array(
			'hooks_to_deactivate' => array( 'neve_do_404' ),
			'posts_map_key'       => 'not_found',
		),
		'neve_do_offline'      => array(
			'hooks_to_deactivate' => array( 'neve_do_offline' ),
			'posts_map_key'       => 'offline',
		),
		'neve_do_server_error' => array(
			'hooks_to_deactivate' => array( 'neve_do_server_error' ),
			'posts_map_key'       => 'server_error',
		),
	);

	/**
	 * Constructor.
	 *
	 * Register actions and editors.
	 */
	public function __construct( $namespace ) {
		if ( function_exists( 'do_blocks' ) ) {
			add_filter( 'neve_post_content', 'do_blocks' );
		}
		add_filter( 'neve_post_content', 'wptexturize' );
		add_filter( 'neve_post_content', 'convert_smilies' );
		add_filter( 'neve_post_content', 'convert_chars' );
		add_filter( 'neve_post_content', 'wpautop' );
		add_filter( 'neve_post_content', 'shortcode_unautop' );
		add_filter( 'neve_post_content', 'do_shortcode' );
		add_action( 'template_redirect', [ $this, 'render_single' ] );
		foreach ( $this->builders_list as $index => $builder ) {
			$builder = $namespace . $builder;
			$builder = new $builder;
			/**
			 * Builder instance.
			 *
			 * @var Abstract_Builders $builder Builder object.
			 */
			if ( ! $builder->should_load() ) {
				continue;
			}
			$builder->register_hooks();
			$this->available_builders[ $builder->get_builder_id() ] = $builder;
		}
		$post_map = Custom_Layouts_Cpt::get_custom_layouts();
		foreach ( $post_map as $layout => $posts ) {
			switch ( $layout ) {
				case 'header':
					add_action( 'neve_do_header', [ $this, 'render_first_markup' ], 1 );
					break;
				case 'footer':
					add_action( 'neve_do_footer', [ $this, 'render_first_markup' ], 1 );
					break;
				case 'not_found':
					add_action( 'neve_do_404', [ $this, 'render_first_markup' ], 1 );
					break;
				case 'offline':
					add_action( 'neve_do_offline', [ $this, 'render_first_markup' ], 1 );
					break;
				case 'server_error':
					add_action( 'neve_do_server_error', [ $this, 'render_first_markup' ], 1 );
					break;
				default:
					add_action( $layout, [ $this, 'render_all_markup' ], 1 );
					break;
			}
		}
	}

	/**
	 * Render all custom layouts attached.
	 */
	public function render_all_markup() {
		$this->render_inline_markup( false );
	}

	/**
	 * Render first custom layouts attached.
	 */
	public function render_first_markup() {
		$this->render_inline_markup( true );
	}

	/**
	 * Render inline markup.
	 *
	 * @return bool Has rendered?
	 */
	public function render_inline_markup( $single = true ) {
		// Remove rendering on custom layout.
		if ( is_singular( 'neve_custom_layouts' ) ) {
			return false;
		}
		$current_hook        = current_filter();
		$hooks_to_deactivate = isset( $this->hooks_map[ $current_hook ]['hooks_to_deactivate'] ) ? $this->hooks_map[ $current_hook ]['hooks_to_deactivate'] : [];
		$posts_map_key       = isset( $this->hooks_map[ $current_hook ]['posts_map_key'] ) ? $this->hooks_map[ $current_hook ]['posts_map_key'] : $current_hook;

		$all_posts = Custom_Layouts_Cpt::get_custom_layouts();

		if ( empty( $all_posts ) || ! isset( $all_posts[ $posts_map_key ] ) ) {
			return false;
		}

		$posts = $all_posts[ $posts_map_key ];
		if ( empty( $posts ) ) {
			return false;
		}

		if ( in_array( $posts_map_key, [ 'header', 'footer' ], true ) ) {
			$ids_array        = array_keys( $posts );
			$highest_priority = $this->get_greatest_priority_rule( $ids_array );
			if ( $highest_priority === false ) {
				return false;
			}
			$new_posts                      = [];
			$new_posts[ $highest_priority ] = '10';
			$posts                          = $new_posts;

		}

		asort( $posts );
		foreach ( $posts as $post_id => $priority ) {
			$editor = Abstract_Builders::get_post_builder( $post_id );

			if ( ! isset( $this->available_builders[ $editor ] ) ) {
				continue;
			}

			if ( ! $this->available_builders[ $editor ]->check_conditions( $post_id ) ) {
				continue;
			}
			if ( $single ) {
				foreach ( $hooks_to_deactivate as $hook ) {
					remove_all_actions( $hook );
				}
			}
			$this->available_builders[ $editor ]->render( $post_id );

			if ( $single ) {
				return true;
			}
		}

		return true;
	}


	/**
	 * Footer markup on Custom Layouts preview.
	 */
	public function render_footer() {
		echo '<footer class="nv-custom-footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter">';
		$this->render_content();
		echo '</footer>';
	}


	/**
	 * This function handles the display on Custom Layouts preview, the single of Custom Layouts custom post type.
	 *
	 * @return bool
	 */
	public function render_single() {
		if ( ! is_singular( 'neve_custom_layouts' ) ) {
			return false;
		}
		$post_id = get_the_ID();

		$layout = get_post_meta( $post_id, 'custom-layout-options-layout', true );
		switch ( $layout ) {
			case 'header':
				remove_all_actions( 'neve_do_header' );
				remove_all_actions( 'neve_do_top_bar' );
				add_action( 'neve_do_header', array( $this, 'render_header' ) );
				break;
			case 'footer':
				remove_all_actions( 'neve_do_footer' );
				add_action( 'neve_do_footer', array( $this, 'render_footer' ) );
				break;
			case 'offline':
			case 'server_error':
				remove_all_actions( 'neve_do_footer' );
				remove_all_actions( 'neve_do_header' );
				add_action( 'neve_custom_layouts_template_content', array( $this, 'render_content' ) );
				break;
			case 'not_found':
				add_action( 'neve_custom_layouts_template_content', array( $this, 'render_content' ) );
				break;
			default:
				remove_all_actions( 'neve_do_footer' );
				remove_all_actions( 'neve_do_header' );
				remove_all_actions( 'neve_do_top_bar' );
				remove_all_actions( 'neve_custom_layouts_template_content' );
				add_action( 'neve_custom_layouts_template_content', array( $this, 'render_content' ) );
				break;
		}

		return true;
	}


	/**
	 * Header markup on Custom Layouts preview.
	 */
	public function render_header() {
		echo '<header class="nv-custom-header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">';
		$this->render_content();
		echo '</header>';
	}

	/**
	 * Get the layout content.
	 */
	public function render_content() {
		while ( have_posts() ) {
			the_post();
			$post_id = get_the_ID();
			$builder = Abstract_Builders::get_post_builder( $post_id );
			if ( $builder !== 'custom' ) {
				the_content();
				continue;
			}
			$file_name = get_post_meta( $post_id, 'neve_editor_content', true );
			if ( empty( $file_name ) ) {
				continue;
			}
			$wp_upload_dir = wp_upload_dir( null, false );
			$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
			$file_path     = $upload_dir . $file_name . '.php';
			if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
				include_once( $file_path );
			}
		}
	}

}
