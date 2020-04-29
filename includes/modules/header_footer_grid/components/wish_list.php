<?php
/**
 * Wish List Component class for Header Footer Grid.
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Core\Components\Abstract_Component;
use HFG\Main;
use Neve_Pro\Modules\Woocommerce_Booster\Module;


/**
 * Class Wish_List
 */
class Wish_List extends Abstract_Component {
	const COMPONENT_ID   = 'wish_list';
	const SIZE_ID        = 'icon_size';
	const COLOR_ID       = 'color';
	const HOVER_COLOR_ID = 'hover_color';

	/**
	 * Wish List constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Wish List', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 1 );
		$this->set_property( 'icon', 'heart' );
		$this->set_property( 'is_auto_width', true );
		$this->set_property( 'default_selector', '.builder-item--' . $this->get_id() . ' .wl-icon-wrapper' );
		$this->set_property(
			'default_padding_value',
			array(
				'mobile'       => array(
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
				),
				'tablet'       => array(
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
				),
				'desktop'      => array(
					'top'    => 0,
					'right'  => 10,
					'bottom' => 0,
					'left'   => 10,
				),
				'mobile-unit'  => 'px',
				'tablet-unit'  => 'px',
				'desktop-unit' => 'px',
			)
		);
	}

	/**
	 * Method to filter component loading.
	 *
	 * @return bool
	 */
	public function is_active() {
		$woo_booster_instance = new Module();

		return $woo_booster_instance->should_load();
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
		// Add style settings only after 2.5.3 theme version as the controls aren't available before this.
		if ( version_compare( NEVE_VERSION, '2.5.3', '>=' ) ) {
			SettingsManager::get_instance()->add(
				[
					'id'                    => self::SIZE_ID,
					'group'                 => $this->get_id(),
					'tab'                   => SettingsManager::TAB_STYLE,
					'transport'             => 'postMessage',
					'sanitize_callback'     => 'absint',
					'default'               => 15,
					'label'                 => __( 'Icon Size', 'neve' ),
					'type'                  => 'neve_range_control',
					'live_refresh_selector' => $this->default_selector . ' > svg',
					'live_refresh_css_prop' => array(
						'type' => 'svg-icon-size',
					),
					'section'               => $this->section,
					'conditional_header'    => true,
				]
			);

			SettingsManager::get_instance()->add(
				[
					'id'                    => self::COLOR_ID,
					'group'                 => self::COMPONENT_ID,
					'tab'                   => SettingsManager::TAB_STYLE,
					'transport'             => 'postMessage',
					'sanitize_callback'     => 'sanitize_hex_color',
					'label'                 => __( 'Color', 'neve' ),
					'type'                  => '\Neve\Customizer\Controls\React\Color',
					'section'               => $this->section,
					'live_refresh_selector' => $this->default_selector . ' svg',
					'live_refresh_css_prop' => array(
						'prop'     => 'fill',
						'fallback' => '',
					),
					'conditional_header'    => true,
				]
			);

			SettingsManager::get_instance()->add(
				[
					'id'                    => self::HOVER_COLOR_ID,
					'group'                 => self::COMPONENT_ID,
					'tab'                   => SettingsManager::TAB_STYLE,
					'transport'             => 'postMessage',
					'sanitize_callback'     => 'sanitize_hex_color',
					'label'                 => __( 'Hover Color', 'neve' ),
					'type'                  => '\Neve\Customizer\Controls\React\Color',
					'section'               => $this->section,
					'live_refresh_selector' => $this->default_selector . ':hover svg',
					'live_refresh_css_prop' => array(
						'prop' => 'fill',
					),
					'conditional_header'    => true,
				]
			);
		}
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
		$size        = SettingsManager::get_instance()->get( $this->get_id() . '_' . self::SIZE_ID );
		$color       = SettingsManager::get_instance()->get( $this->get_id() . '_' . self::COLOR_ID );
		$color_hover = SettingsManager::get_instance()->get( $this->get_id() . '_' . self::HOVER_COLOR_ID );

		if ( ! empty( $size ) ) {
			$css_array[ $this->default_selector . ' > svg' ]['width']  = $size . 'px';
			$css_array[ $this->default_selector . ' > svg' ]['height'] = $size . 'px';
		}

		if ( ! empty( $color ) ) {
			$css_array[ $this->default_selector . ' > svg' ]['fill'] = $color;
		}

		if ( ! empty( $color_hover ) ) {
			$css_array[ $this->default_selector . ':hover > svg' ]['fill'] = $color_hover;
		}

		return parent::add_style( $css_array );
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-wish-list' );
	}
}
