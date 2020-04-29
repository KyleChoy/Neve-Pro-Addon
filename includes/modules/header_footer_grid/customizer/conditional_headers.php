<?php
/**
 * Created on:      2019-12-19
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve\Customizer\Types\Section;

/**
 * Class Conditional_Headers
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Customizer
 */
class Conditional_Headers extends Base_Customizer {
	/**
	 * Theme mods that should be JSON.
	 *
	 * @var array
	 */
	const JSON_THEME_MODS = [
		'hfg_header_layout',
		'hfg_header_layout_main_height',
		'hfg_header_layout_top_height',
		'hfg_header_layout_bottom_height',
	];

	/**
	 * Initialize the class.
	 */
	public function init() {
		parent::init();

		add_filter( 'customize_changeset_save_data', array( $this, 'conditional_headers_filtering' ), 10, 2 );
	}

	/**
	 * Catch theme mods for conditional headers and use them as meta.
	 *
	 * @param array $data           data coming from the customizer.
	 * @param array $filter_context data about the current change-set.
	 *
	 * @return array
	 */
	public function conditional_headers_filtering( $data, $filter_context ) {
		$prefix = get_option( 'stylesheet' ) . '::';
		$mod    = $prefix . 'neve_header_conditional_selector';
		if ( $filter_context['status'] !== 'publish' || ! isset( $data[ $mod ] ) ) {
			return $data;
		}

		if ( isset( $data[ $mod ]['value']['delete'] ) ) {
			$to_delete = $data[ $mod ]['value']['delete'];
			if ( is_array( $to_delete ) ) {
				foreach ( $to_delete as $layout ) {
					$layout_to_delete = get_page_by_path( $layout, OBJECT, 'neve_custom_layouts' );
					if ( $layout_to_delete !== null ) {
						$post_id = $layout_to_delete->ID;
						wp_delete_post( $post_id, true );
					}
				}
			}
		}
		if ( isset( $data[ $mod ]['value']['add'] ) ) {
			$to_add = $data[ $mod ]['value']['add'];
			if ( is_array( $to_add ) ) {
				foreach ( $to_add as $layout => $name ) {
					$layout_to_add = get_page_by_path( $layout, OBJECT, 'neve_custom_layouts' );
					if ( $layout_to_add === null ) {
						$post_id = wp_insert_post(
							array(
								'post_title'  => $name,
								'post_name'   => $layout,
								'post_status' => 'publish',
								'post_type'   => 'neve_custom_layouts',
								'post_author' => get_current_user_id(),
							)
						);
						update_post_meta( $post_id, 'header-layout', true );
					}
				}
			}
		}

		if ( isset( $data[ $mod ]['value']['rules'] ) ) {
			$conditions = $data[ $mod ]['value']['rules'];
			foreach ( $conditions as $custom_layout_slug => $rules ) {
				$custom_layout = get_page_by_path( $custom_layout_slug, OBJECT, 'neve_custom_layouts' );
				if ( $custom_layout !== null ) {
					foreach ( $rules as $rule_group_index => $rule_group ) {
						foreach ( $rule_group as $index => $rule ) {
							if ( empty( $rule['root'] ) || empty( $rule['end'] ) ) {
								unset( $rules[ $rule_group_index ][ $index ] );
							}
						}
						if ( empty( $rule_group ) ) {
							unset( $rules[ $rule_group_index ] );
						}
					}
					update_post_meta( $custom_layout->ID, 'custom-layout-conditional-logic', json_encode( $rules ) );
				}
			}
		}

		if ( isset( $data[ $mod ]['value']['themeMods'] ) ) {
			$to_update = $data[ $mod ]['value']['themeMods'];
			$json_mods = self::JSON_THEME_MODS;

			foreach ( $to_update as $custom_layout_slug => $value ) {
				if ( $custom_layout_slug === 'default' ) {
					foreach ( $value as $key => $val ) {
						$val = in_array( $key, $json_mods, true ) ? json_encode( $val ) : $val;
						set_theme_mod( $key, $val );
						unset( $data[ $prefix . $key ] );
					}
					continue;
				}

				$page = get_page_by_path( $custom_layout_slug, OBJECT, 'neve_custom_layouts' );
				if ( $page === null ) {
					continue;
				}

				$new_meta = [];
				$old_meta = json_decode( get_post_meta( $page->ID, 'theme-mods', true ), true );

				foreach ( $value as $key => $val ) {
					$new_meta[ $key ] = $val;
				}

				if ( is_array( $old_meta ) ) {
					$new_meta = array_merge( $old_meta, $new_meta );
				}
				update_post_meta( $page->ID, 'theme-mods', json_encode( wp_slash( $new_meta ) ) );
			}
		}

		unset( $data[ $mod ] );
		delete_transient( 'custom_layouts_post_map' );

		return $data;
	}

	/**
	 * Add customizer controls.
	 */
	public function add_controls() {
		$this->add_section(
			new Section(
				'neve_pro_global_header_settings',
				[
					'priority' => 100,
					'title'    => esc_html__( 'Global Header Settings', 'neve' ),
					'panel'    => 'hfg_header',
				]
			)
		);

		if ( version_compare( NEVE_VERSION, '2.5.5', '>=' ) ) {
			$this->add_control(
				new Control(
					'neve_global_header',
					array(
						'transport'         => 'refresh',
						'sanitize_callback' => 'neve_sanitize_checkbox',
						'default'           => true,
					),
					array(
						'label'    => esc_html__( 'Show This Header Site-wide', 'neve' ),
						'section'  => 'neve_pro_global_header_settings',
						'type'     => 'neve_toggle_control',
						'priority' => 20,
					)
				)
			);
			$this->add_control(
				new Control(
					'neve_header_conditional_selector',
					[
						'transport'         => 'postMessage',
						'sanitize_callback' => function ( $val ) {
							return $val;
						},
						'default'           => [
							'themeMods' => [],
							'rules'     => [],
						],
					],
					[
						'label'           => esc_html__( 'Display This Header On', 'neve' ),
						'section'         => 'neve_pro_global_header_settings',
						'type'            => 'neve_context_conditional_selector',
						'active_callback' => [ $this, 'is_not_global_header' ],
						'priority'        => 20,
					]
				)
			);
		}
	}

	/**
	 * Is not set to global header.
	 *
	 * @return bool
	 */
	public function is_not_global_header() {
		return ! get_theme_mod( 'neve_global_header' );
	}
}
