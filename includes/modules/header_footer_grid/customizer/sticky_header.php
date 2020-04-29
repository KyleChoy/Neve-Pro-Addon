<?php
/**
 * Customizer Class for Header Footer Grid.
 *
 * Name:    Header Footer Grid Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Customizer;

use Neve\Customizer\Base_Customizer;
use HFG\Core\Settings\Manager as SettingsManager;
use Neve_Pro\Modules\Header_Footer_Grid\Module;

/**
 * Class Header_Footer_Grid
 *
 * @package Neve_Pro\Customizer\Options
 */
class Sticky_Header extends Base_Customizer {
	/**
	 * A list of dependent controls.
	 *
	 * @var array
	 */
	protected $sticky_rows = array();

	/**
	 * Function that should be extended to add customizer controls.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'hfg_add_settings_to_rows', array( $this, 'hook_into_hfg_row_settings' ), 10, 4 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts_enqueue' ) );
	}

	/**
	 * Filter header row classes.
	 *
	 * @param array  $classes   Classes added to row.
	 * @param string $row_index The row index.
	 *
	 * @return array
	 * @since   1.0.1
	 * @access  public
	 */
	public function header_row_classes( $classes, $row_index ) {
		$is_sticky = get_theme_mod( 'hfg_header_layout_' . $row_index . '_sticky', false );
		if ( $is_sticky ) {
			$classes[] = 'is_sticky';
			// Flag script for enqueue.
			Module::flag_for_enqueue();
			$is_sticky_on_scroll = get_theme_mod( 'hfg_header_layout_' . $row_index . '_sticky_on_scroll', false );
			if ( $is_sticky_on_scroll ) {
				$classes[] = 'is_sticky_on_scroll';
			}
		}

		return $classes;
	}

	/**
	 * Filter footer row classes.
	 *
	 * @param array  $classes   Classes added to row.
	 * @param string $row_index The row index.
	 *
	 * @return array
	 * @since   1.1.7
	 * @access  public
	 */
	public function footer_row_classes( $classes, $row_index ) {
		$is_sticky = get_theme_mod( 'hfg_footer_layout_' . $row_index . '_sticky', false );
		if ( ! $is_sticky ) {
			return $classes;
		}
		$classes[] = 'is_sticky';
		// Flag script for enqueue.
		Module::flag_for_enqueue();

		return $classes;
	}

	/**
	 * Enqueue scripts for defined controls.
	 *
	 * @since   1.0.
	 * @access  public
	 */
	public function scripts_enqueue() {
		wp_register_script(
			'hfg-sticky-custom-customize',
			NEVE_PRO_INCLUDES_URL . 'modules/header_footer_grid/assets/js/sticky.customize.js',
			array(
				'jquery',
				'customize-controls',
			),
			false,
			true
		);
		wp_localize_script( 'hfg-sticky-custom-customize', 'stickyRows', $this->sticky_rows );
		wp_enqueue_script( 'hfg-sticky-custom-customize' );
	}

	/**
	 * Append to settings for row.
	 *
	 * @param SettingsManager $settings_manager An instance of the settings manager.
	 * @param string          $row_setting_id   The row setting id.
	 * @param string          $row_id           The row id.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function hook_into_hfg_row_settings( SettingsManager $settings_manager, $row_setting_id = '', $row_id = '', $builder_id = '' ) {
		if ( $builder_id === 'header' && $row_id !== 'sidebar' || $builder_id === 'footer' ) {
			$settings_manager->add(
				array(
					'id'                 => 'sticky',
					'group'              => $row_setting_id,
					'tab'                => $settings_manager::TAB_LAYOUT,
					'section'            => $row_setting_id,
					'label'              => $builder_id === 'header' ? __( 'Stick to top', 'neve' ) : __( 'Stick to bottom', 'neve' ),
					'type'               => 'neve_toggle_control',
					'options'            => array(
						'priority' => 1,
					),
					'transport'          => 'post' . $row_setting_id,
					'sanitize_callback'  => 'neve_sanitize_checkbox',
					'default'            => false,
					'conditional_header' => true,
				)
			);
			if ( $builder_id !== 'footer' ) {
				$settings_manager->add(
					array(
						'id'                 => 'sticky_on_scroll',
						'group'              => $row_setting_id,
						'tab'                => $settings_manager::TAB_LAYOUT,
						'section'            => $row_setting_id,
						'label'              => __( 'Show only on scroll', 'neve' ),
						'type'               => 'neve_toggle_control',
						'options'            => array(
							'priority' => 2,
						),
						'transport'          => 'post' . $row_setting_id,
						'sanitize_callback'  => 'neve_sanitize_checkbox',
						'default'            => false,
						'conditional_header' => true,
					)
				);
				$this->sticky_rows[ $row_setting_id . '_sticky' ] = $row_setting_id . '_sticky_on_scroll';
			}
		}
	}

	/**
	 * Function that should be extended to add customizer controls.
	 *
	 * @return void
	 */
	public function add_controls() {
	}
}
