<?php
/**
 * Logo Component Wrapper class extends Header Footer Grid Component.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Core\Components\Logo as CoreLogo;

/**
 * Class Logo
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Logo extends CoreLogo {
	const CUSTOM_LOGO = 'custom_logo';
	/**
	 * Holds the instance count.
	 * Starts at 1 since the base component is not altered.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected static $instance_count = 1;
	/**
	 * Holds the current instance count.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $instance_number;
	/**
	 * The maximum allowed instances of this class.
	 * This refers to the global scope, across all builders.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $max_instance = 2;

	/**
	 * Logo constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
		$this->set_property( 'section', 'title_tagline' . '_' . $this->instance_number );
	}


	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
		parent::add_settings();

		if ( $this->instance_number <= 1 ) {
			return;
		}

		$custom_logo_args = get_theme_support( 'custom-logo' );
		SettingsManager::get_instance()->add(
			[
				'id'                => self::CUSTOM_LOGO,
				'group'             => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => 'absint',
				'default'           => get_theme_mod( 'custom_logo' ),
				'label'             => __( 'Logo', 'neve' ),
				'type'              => '\WP_Customize_Cropped_Image_Control',
				'options'           => [
					'priority'      => 0,
					'height'        => isset( $custom_logo_args[0]['height'] ) ? $custom_logo_args[0]['height'] : null,
					'width'         => isset( $custom_logo_args[0]['width'] ) ? $custom_logo_args[0]['width'] : null,
					'flex_height'   => isset( $custom_logo_args[0]['flex-height'] ) ? $custom_logo_args[0]['flex-height'] : null,
					'flex_width'    => isset( $custom_logo_args[0]['flex-width'] ) ? $custom_logo_args[0]['flex-width'] : null,
					'button_labels' => array(
						'select'       => __( 'Select logo' ),
						'change'       => __( 'Change logo' ),
						'remove'       => __( 'Remove' ),
						'default'      => __( 'Default' ),
						'placeholder'  => __( 'No logo selected' ),
						'frame_title'  => __( 'Select logo' ),
						'frame_button' => __( 'Choose logo' ),
					),
				],
				'section'           => $this->section,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                => 'shortcut',
				'group'             => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'esc_attr',
				'type'              => '\Neve\Customizer\Controls\Button',
				'options'           => [
					'button_text'      => __( 'Edit Title, Tagline & Site Icon', 'neve' ),
					'icon_class'       => 'nametag',
					'control_to_focus' => 'blogname',
				],
				'section'           => $this->section,
			]
		);
	}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @since   1.0.1
	 * @access public
	 * @return bool
	 */
	public function is_active() {
		if ( $this->max_instance < $this->instance_number ) {
			return false;
		}
		return parent::is_active();
	}

	/**
	 * Allow for constant changes in pro.
	 *
	 * @since   1.0.0
	 * @access  protected
	 *
	 * @param string $const Name of the constant.
	 *
	 * @return mixed
	 */
	protected function get_class_const( $const ) {
		return constant( 'static::' . $const ) . '_' . $this->instance_number;
	}
}
