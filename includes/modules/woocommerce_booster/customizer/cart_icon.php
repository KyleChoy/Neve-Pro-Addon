<?php
/**
 * Cart Icon customizer controls class.
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Customizer;

use HFG\Core\Components\CartIcon;
use HFG\Core\Settings\Manager as SettingsManager;

/**
 * Class Cart_Icon
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Cart_Icon {

	/**
	 * Init function.
	 */
	public function init() {
		add_action( 'nv_cart_icon_component_controls', array( $this, 'add_cart_icon_features' ) );
	}

	/**
	 * Add cart icon features in pro.
	 */
	public function add_cart_icon_features() {

		$default_selector = '.builder-item--' . CartIcon::COMPONENT_ID;
		$section          = CartIcon::COMPONENT_ID;

		SettingsManager::get_instance()->add(
			[
				'id'                    => CartIcon::LABEL_SIZE_ID,
				'group'                 => CartIcon::COMPONENT_ID,
				'tab'                   => SettingsManager::TAB_STYLE,
				'transport'             => 'postMessage',
				'sanitize_callback'     => 'absint',
				'default'               => 15,
				'label'                 => __( 'Label Size', 'neve' ),
				'type'                  => 'neve_range_control',
				'live_refresh_selector' => $default_selector . ' .cart-icon-label',
				'live_refresh_css_prop' => array(
					'type' => 'font-size',
				),
				'section'               => $section,
				'conditional_header'    => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => CartIcon::ICON_SELECTOR,
				'group'              => CartIcon::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . CartIcon::COMPONENT_ID,
				'sanitize_callback'  => 'wp_filter_nohtml_kses',
				'default'            => 'cart-icon-style1',
				'label'              => __( 'Select Icon', 'neve' ),
				'type'               => '\Neve\Customizer\Controls\React\Radio_Buttons',
				'options'            => [
					'priority'      => 10,
					'is_for'        => 'cart_component',
					'large_buttons' => false,
				],
				'section'            => $section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => CartIcon::CART_FOCUS,
				'group'              => CartIcon::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . CartIcon::COMPONENT_ID,
				'sanitize_callback'  => 'absint',
				'default'            => 1,
				'label'              => __( 'Open Mini-Cart when the product is added', 'neve' ),
				'type'               => 'neve_toggle_control',
				'options'            => [
					'active_callback' => array( $this, 'cart_focus_active_callback' ),
				],
				'section'            => $section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => CartIcon::CART_LABEL,
				'group'              => CartIcon::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . CartIcon::COMPONENT_ID,
				'sanitize_callback'  => 'sanitize_text_field',
				'label'              => __( 'Cart label', 'neve' ),
				'type'               => 'text',
				'section'            => $section,
				'use_dynamic_fields' => array( 'custom_cart' ),
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => CartIcon::MINI_CART_STYLE,
				'group'              => CartIcon::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'refresh',
				'sanitize_callback'  => 'sanitize_text_field',
				'label'              => __( 'Mini Cart Style', 'neve' ),
				'type'               => 'select',
				'default'            => 'dropdown',
				'options'            => [
					'choices' => [
						'dropdown'   => __( 'Dropdown', 'neve' ),
						'off-canvas' => __( 'Off Canvas', 'neve' ),
						'link'       => __( 'Link', 'neve' ),
					],
				],
				'section'            => $section,
				'conditional_header' => true,
			]
		);

		SettingsManager::get_instance()->add(
			[
				'id'                 => CartIcon::AFTER_CART_HTML,
				'group'              => CartIcon::COMPONENT_ID,
				'tab'                => SettingsManager::TAB_GENERAL,
				'transport'          => 'post' . CartIcon::COMPONENT_ID,
				'sanitize_callback'  => 'sanitize_text_field',
				'label'              => __( 'Custom HTML After Cart', 'neve' ),
				'type'               => 'textarea',
				'section'            => $section,
				'conditional_header' => true,
			]
		);
	}

	/**
	 * Active Callback function for cart focus control.
	 */
	public function cart_focus_active_callback() {
		$seamless_add_to_cart = get_theme_mod( 'neve_enable_seamless_add_to_cart', false );
		if ( $seamless_add_to_cart === false ) {
			return false;
		}

		$mini_cart_style = get_theme_mod( CartIcon::COMPONENT_ID . '_' . CartIcon::MINI_CART_STYLE, 'dropdown' );
		if ( $mini_cart_style === 'link' ) {
			return false;
		}

		return true;
	}
}
