<?php
/**
 * Button Component class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Main;
use Neve_Pro\Traits\Core;

/**
 * Class Social_Icons
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Social_Icons extends Abstract_Component {

	use Core;

	/**
	 * Holds the instance count.
	 * Starts at 1 since the base component is not altered.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected static $instance_count = 0;
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

	const COMPONENT_ID  = 'social_icons';
	const REPEATER_ID   = 'content_setting';
	const NEW_TAB       = 'new_tab';
	const ICON_SIZE     = 'icon_size';
	const ICON_SPACING  = 'icon_spacing';
	const ICON_PADDING  = 'icon_padding';
	const BORDER_RADIUS = 'border_radius';
	/**
	 * Repeater defaults
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var array
	 */
	private $repeater_default = array(
		array(
			'title'            => 'Facebook',
			'url'              => '#',
			'icon'             => 'facebook',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#3b5998',
		),
		array(
			'title'            => 'Twitter',
			'url'              => '#',
			'icon'             => 'twitter',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#1da1f2',
		),
		array(
			'title'            => 'Youtube',
			'url'              => '#',
			'icon'             => 'youtube-play',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#cd201f',
		),
		array(
			'title'            => 'Instagram',
			'url'              => '#',
			'icon'             => 'instagram',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#e1306c',
		),
	);

	/**
	 * Social_Icons constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
		$this->set_property( 'section', $this->get_class_const( 'COMPONENT_ID' ) );
	}


	/**
	 * Initialize.
	 *
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Social Icons', 'neve' ) );
		$this->set_property( 'id', $this->get_class_const( 'COMPONENT_ID' ) );
		$this->set_property( 'width', 4 );
		$this->set_property( 'section', 'social_icons' . '_' . $this->instance_number );
		$this->set_property( 'icon', 'share' );
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
		SettingsManager::get_instance()->add(
			array(
				'id'                => self::REPEATER_ID,
				'group'             => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => array( $this, 'sanitize_social_icons_repeater' ),
				'default'           => json_encode( $this->repeater_default ),
				'label'             => __( 'Social Icons', 'neve' ),
				'type'              => 'Neve_Pro\Customizer\Controls\Repeater',
				'options'           => array(
					'type'   => 'neve-repeater',
					'fields' => array(
						'title'            => array(
							'type'  => 'text',
							'label' => 'Title',
						),
						'icon'             => array(
							'type'  => 'icon',
							'label' => 'Icon',
						),
						'url'              => array(
							'type'  => 'text',
							'label' => 'Link',
						),
						'icon_color'       => array(
							'type'  => 'color',
							'label' => 'Icon Color',
						),
						'background_color' => array(
							'type'  => 'color',
							'label' => 'Background Color',
						),
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::NEW_TAB,
				'group'             => $this->get_id(),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => 'absint',
				'default'           => 0,
				'label'             => __( 'Open in new tab', 'neve' ),
				'type'              => 'neve_toggle_control',
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::ICON_SIZE,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 18,
				'label'              => __( 'Icon Size', 'neve' ),
				'conditional_header' => $this->get_builder_id() === 'header',
				'type'               => 'neve_range_control',
				'options'            => array(
					'input_attr' => array(
						'step' => 1,
						'min'  => 10,
						'max'  => 40,
					),
				),
				'section'            => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::ICON_SPACING,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 10,
				'label'              => __( 'Icon Spacing', 'neve' ),
				'type'               => 'neve_range_control',
				'conditional_header' => $this->get_builder_id() === 'header',
				'options'            => array(
					'input_attr' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'section'            => $this->section,
			)
		);
		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::BORDER_RADIUS,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 5,
				'label'              => __( 'Border Radius (px)', 'neve' ),
				'type'               => 'neve_range_control',
				'conditional_header' => $this->get_builder_id() === 'header',
				'options'            => array(
					'input_attr' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
				),
				'section'            => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                 => self::ICON_PADDING,
				'group'              => $this->get_id(),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => array( $this, 'sanitize_spacing_array' ),
				'conditional_header' => $this->get_builder_id() === 'header',
				'default'            => array(
					'desktop'      => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'tablet'       => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'mobile'       => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				),
				'options'            => [
					'input_attrs' => array(
						'hideResponsiveButtons' => true,
					),
				],
				'label'              => __( 'Icon Padding', 'neve' ),
				'type'               => '\Neve\Customizer\Controls\React\Spacing',
				'section'            => $this->section,
			)
		);
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_style( array $css_array = array() ) {
		$this->default_selector = '.builder-item--' . $this->get_id() . ' ul.nv-social-icons-list';
		$icon_spacing           = get_theme_mod( $this->get_id() . '_' . self::ICON_SPACING, 10 );
		$border_radius          = get_theme_mod( $this->get_id() . '_' . self::BORDER_RADIUS, 5 );
		$icon_padding           = get_theme_mod( $this->get_id() . '_' . self::ICON_PADDING, '' );
		$icon_selector          = $this->default_selector . ' li a';

		$css_array[ $this->default_selector . ' > li:not(:first-child)' ] = array( 'margin-left' => $icon_spacing . 'px' );
		$css_array[ $icon_selector ]                                      = array( 'border-radius' => $border_radius . 'px' );

		if ( isset( $icon_padding['mobile'] ) ) {
			$css_array[' @media (max-width: 576px)'][ $icon_selector ]['padding'] =
				$icon_padding['mobile']['top'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['right'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['bottom'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['left'] . $icon_padding['mobile-unit'];
		}
		if ( isset( $icon_padding['tablet'] ) ) {
			$css_array[' @media (min-width: 576px)'][ $icon_selector ]['padding'] =
				$icon_padding['tablet']['top'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['right'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['bottom'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['left'] . $icon_padding['tablet-unit'];
		}
		if ( isset( $icon_padding['desktop'] ) ) {
			$css_array[' @media (min-width: 961px)'][ $icon_selector ]['padding'] =
				$icon_padding['desktop']['top'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['right'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['bottom'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['left'] . $icon_padding['desktop-unit'];
		}

		return parent::add_style( $css_array );
	}

	/**
	 * Sanitize repeater values.
	 *
	 * @param string $value repeater json value.
	 *
	 * @return string
	 */
	public function sanitize_social_icons_repeater( $value ) {
		$fields = array(
			'title',
			'url',
			'icon',
			'visibility',
			'icon_color',
			'background_color',
		);
		$valid  = $this->sanitize_repeater_json( $value, $fields );

		if ( $valid === false ) {
			return json_encode( $this->repeater_default );
		}

		return $value;
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-social-icons' );
	}

	/**
	 * Allow for constant changes in pro.
	 *
	 * @param string $const Name of the constant.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  protected
	 */
	protected function get_class_const( $const ) {
		return $this->instance_number > 1 ? constant( 'static::' . $const ) . '_' . $this->instance_number : constant( 'static::' . $const );
	}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @return bool
	 * @since   1.0.1
	 * @access  public
	 */
	public function is_active() {
		if ( $this->max_instance < $this->instance_number ) {
			return false;
		}

		return parent::is_active();
	}
}
