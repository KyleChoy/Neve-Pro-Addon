<?php
/**
 * My Account Component class for Header Footer Grid.
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Main;

/**
 * Class My_Account
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class My_Account extends Abstract_Component {

	/**
	 * Component id.
	 */
	const COMPONENT_ID = 'my_account';

	/**
	 * Icon selector.
	 */
	const ICON_SELECTOR = 'icon_selector';

	/**
	 * Enable register.
	 */
	const ENABLE_REGISTER = 'enable_register';

	/**
	 * Label text id.
	 */
	const LABEL_TEXT = 'label_text';

	/**
	 * Register text id.
	 */
	const REGISTER_TEXT = 'register_text';

	/**
	 * Label text id.
	 */
	const STYLE_ID = 'my_account_appearance';

	/**
	 * Icon size.
	 */
	const ICON_SIZE_ID = 'icon_size';

	/**
	 * Label size.
	 */
	const LABEL_SIZE_ID = 'label_size';

	/**
	 * Woo dropdown.
	 */
	const ENABLE_DROPDOWN = 'enable_dropdown';

	/**
	 * Method to filter component loading if needed.
	 *
	 * @return bool
	 * @since   1.0.1
	 * @access  public
	 */
	public function is_active() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}
		$enable_registration = get_theme_mod( 'my_account_enable_register', false );
		if ( $enable_registration !== true && ! is_user_logged_in() ) {
			return false;
		}
		if ( version_compare( NEVE_VERSION, '2.6.0', '<=' ) ) {
			return false;
		}

		return parent::is_active();
	}

	/**
	 * Account Component constructor.
	 *
	 * @return bool
	 * @since   1.1.7
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'My Account', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 2 );
		$this->set_property( 'section', 'my_account' );
		$this->set_property( 'icon', 'admin-users' );
		$this->set_property( 'default_selector', '.builder-item--' . $this->get_id() . ' .my-account-wrapper' );
		$this->set_property( 'is_auto_width', true );
		$this->set_property(
			'default_padding_value',
			array_merge(
				$this->default_padding_value,
				array(
					'mobile'  => array(
						'top'    => 3,
						'right'  => 5,
						'bottom' => 3,
						'left'   => 5,
					),
					'tablet'  => array(
						'top'    => 3,
						'right'  => 5,
						'bottom' => 3,
						'left'   => 5,
					),
					'desktop' => array(
						'top'    => 3,
						'right'  => 5,
						'bottom' => 3,
						'left'   => 5,
					),
				)
			)
		);

		return true;
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.1.7
	 * @access  public
	 */
	public function add_settings() {
		if ( ! $this->is_active() ) {
			return false;
		}
		SettingsManager::get_instance()->add(
			[
				'id'                 => self::ICON_SELECTOR,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'wp_filter_nohtml_kses',
				'default'            => 'user_avatar',
				'label'              => __( 'Select Icon', 'neve' ),
				'type'               => '\Neve\Customizer\Controls\React\Radio_Buttons',
				'options'            => [
					'priority'      => 10,
					'is_for'        => 'account_component',
					'large_buttons' => false,
				],
				'section'            => $this->section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => self::ENABLE_REGISTER,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'refresh',
				'sanitize_callback'  => 'absint',
				'default'            => 0,
				'label'              => __( 'Show "Register" for Non-Logged Users', 'neve' ),
				'type'               => 'neve_toggle_control',
				'section'            => $this->section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => self::LABEL_TEXT,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'sanitize_text_field',
				'default'            => __( 'My Account', 'neve' ),
				'label'              => __( 'Account text', 'neve' ),
				'type'               => 'text',
				'section'            => $this->section,
				'conditional_header' => true,
				'use_dynamic_fields' => array( 'custom_user' ),
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => self::REGISTER_TEXT,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'sanitize_text_field',
				'default'            => __( 'Register', 'neve' ),
				'label'              => __( 'Register label', 'neve' ),
				'type'               => 'text',
				'options'            => [
					'active_callback' => array( $this, 'register_active_callback' ),
				],
				'section'            => $this->section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => self::ENABLE_DROPDOWN,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'absint',
				'default'            => 0,
				'label'              => __( 'Enable WooCommerce Account Links Dropdown', 'neve' ),
				'type'               => 'neve_toggle_control',
				'section'            => $this->section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::ICON_SIZE_ID,
				'group'                 => $this->get_id(),
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'absint',
				'default'               => 15,
				'label'                 => __( 'Icon Size', 'neve' ),
				'type'                  => 'neve_range_control',
				'live_refresh_selector' => $this->default_selector . ' .my-account-icon svg, html ' . $this->default_selector . ' .my-account-icon img',
				'live_refresh_css_prop' => array(
					'type' => 'svg-icon-size',
				),
				'section'               => $this->section,
				'conditional_header'    => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                    => self::LABEL_SIZE_ID,
				'group'                 => $this->get_id(),
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'absint',
				'default'               => 15,
				'label'                 => __( 'Font Size', 'neve' ),
				'type'                  => 'neve_range_control',
				'live_refresh_selector' => $this->default_selector . ' .my-account-label, .builder-item--' . $this->get_id() . ' .sub-menu li a',
				'live_refresh_css_prop' => array(
					'type' => 'font-size',
				),
				'section'               => $this->section,
				'conditional_header'    => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => self::STYLE_ID,
				'group'              => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'                => SettingsManager::TAB_STYLE,
				'transport'          => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback'  => 'neve_sanitize_button_appearance',
				'label'              => __( 'My Account Appearance', 'neve' ),
				'type'               => 'neve_button_appearance',
				'options'            => [
					'type'     => 'neve_button_appearance',
					'priority' => 30,
				],
				'section'            => $this->section,
				'conditional_header' => true,
			]
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
		$icon_size = SettingsManager::get_instance()->get( $this->get_id() . '_' . self::ICON_SIZE_ID );
		if ( ! empty( $icon_size ) ) {
			$css_array[ $this->default_selector . ' .my-account-icon svg' ]['width']  = $icon_size . 'px';
			$css_array[ $this->default_selector . ' .my-account-icon svg' ]['height'] = $icon_size . 'px';
			$css_array[ $this->default_selector . ' .my-account-icon img' ]['width']  = $icon_size . 'px';
			$css_array[ $this->default_selector . ' .my-account-icon img' ]['height'] = $icon_size . 'px';
		}

		$label_size = SettingsManager::get_instance()->get( $this->get_id() . '_' . self::LABEL_SIZE_ID );
		if ( ! empty( $label_size ) ) {
			$css_array[ $this->default_selector . ' .my-account-label' ]['font-size'] = $label_size . 'px';
			$css_array[ '.builder-item--' . $this->get_id() . ' li a' ]['font-size']  = $label_size . 'px';
		}

		$style = SettingsManager::get_instance()->get( $this->get_id() . '_' . self::STYLE_ID );
		if ( ! empty( $style ) ) {
			if ( ! empty( $style['background'] ) ) {
				$css_array[ $this->default_selector ]['background-color']                               = $style['background'];
				$css_array[ '.builder-item--' . $this->get_id() . ' .sub-menu li' ]['background-color'] = $style['background'];
			}
			if ( ! empty( $style['backgroundHover'] ) ) {
				$css_array[ $this->default_selector . ':hover' ]['background-color']                          = $style['backgroundHover'];
				$css_array[ '.builder-item--' . $this->get_id() . ' .sub-menu li:hover' ]['background-color'] = $style['backgroundHover'];
			}
			if ( ! empty( $style['text'] ) ) {
				$css_array[ '.builder-item--' . $this->get_id() . ' .my-account-component .sub-menu > li > a' ]['color'] = $style['text'];
				$css_array[ $this->default_selector . ' .my-account-label' ]['color']                                    = $style['text'];
				$css_array[ $this->default_selector . ' .my-account-icon svg' ]['fill']                                  = $style['text'];
			}
			if ( ! empty( $style['textHover'] ) ) {
				$css_array[ '.builder-item--' . $this->get_id() . ' .my-account-component .sub-menu > li:hover > a' ]['color'] = $style['textHover'];
				$css_array[ $this->default_selector . ':hover .my-account-label' ]['color']                                    = $style['textHover'];
				$css_array[ $this->default_selector . ':hover .my-account-icon svg' ]['fill']                                  = $style['textHover'];
			}
			if ( isset( $style['borderRadius'] ) ) {
				if ( is_array( $style['borderRadius'] ) ) {
					$css_array[ $this->default_selector ]['border-top-left-radius']     = $style['borderRadius']['top'] . 'px';
					$css_array[ $this->default_selector ]['border-top-right-radius']    = $style['borderRadius']['right'] . 'px';
					$css_array[ $this->default_selector ]['border-bottom-right-radius'] = $style['borderRadius']['bottom'] . 'px';
					$css_array[ $this->default_selector ]['border-bottom-left-radius']  = $style['borderRadius']['left'] . 'px';
				} else {
					$css_array[ $this->default_selector ]['border-radius'] = $style['borderRadius'] . 'px';
				}
			}

			if ( isset( $style['type'] ) ) {
				if ( $style['type'] === 'outline' ) {
					$css_array[ $this->default_selector ]['border-style']                          = 'solid';
					$css_array[ '.builder-item--' . $this->get_id() . ' .sub-menu' ]['margin-top'] = '-1px';
					if ( ! empty( $style['text'] ) ) {
						$css_array[ $this->default_selector ]['border-color'] = $style['text'];
					}
					if ( ! empty( $style['textHover'] ) ) {
						$css_array[ $this->default_selector . ':hover' ]['border-color'] = $style['textHover'];
					}
					if ( ! empty( $style['borderWidth'] ) ) {
						if ( is_array( $style['borderWidth'] ) ) {
							$css_array[ $this->default_selector ]['border-style'] = 'solid';
							foreach ( $style['borderWidth'] as $k => $v ) {
								$css_array[ $this->default_selector ][ 'border-' . $k . '-width' ] = $v . 'px';
							}
						} else {
							$css_array[ $this->default_selector ]['border-width'] = $style['borderWidth'] . 'px';
						}
					}
				} else {
					$css_array[ $this->default_selector ]['border'] = 'none';
				}
			}
		}

		return parent::add_style( $css_array );
	}

	/**
	 * Render My Account component
	 *
	 * @return mixed|void
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-my-account' );
	}

	/**
	 * Sanitize my account icon control.
	 *
	 * @param string $value the value.
	 *
	 * @return string
	 */
	public function sanitize_gallery_layout( $value ) {
		$allowed_values = array( 'none', 'image', 'plain', 'fill', 'fill-round', 'outline', 'outline-round' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'none';
		}

		return esc_html( $value );
	}

	/**
	 * Label active callback function.
	 *
	 * @return bool
	 */
	public function label_active_callback() {
		return get_theme_mod( 'my_account_label_toggle', false );
	}

	/**
	 * Register active callback function.
	 *
	 * @return bool
	 */
	public function register_active_callback() {
		return get_theme_mod( 'my_account_enable_register', false );
	}

	/**
	 * Get account links
	 *
	 * @return string
	 */
	public static function get_account_links() {
		$html = '';
		if ( function_exists( 'wc_get_account_menu_items' ) ) {
			foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
				$html .= '<li class="' . wc_get_account_menu_item_classes( $endpoint ) . '">';
				if ( $endpoint === 'dashboard' ) {
					$html .= '<a href="' . esc_url( wc_get_account_endpoint_url( $endpoint ) ) . '">';
					$html .= esc_html( $label );
					$html .= '</a>';
				} else {
					$html .= '<a href="' . esc_url( wc_get_endpoint_url( $endpoint, '', wc_get_page_permalink( 'myaccount' ) ) ) . '">';
					$html .= esc_html( $label );
					$html .= '</a>';
				}
				$html .= '</li>';
			}
		}

		return $html;
	}

}

