<?php
/**
 * Payment Icons component class, Header Footer Grid Component.
 *
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use Neve_Pro\Core\Settings;

/**
 * Class Payment_Icons
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Payment_Icons extends Abstract_Component {
	const COMPONENT_ID  = 'payment_icons';
	const ITEM_ORDERING = 'ordering_shortcut';

	/**
	 * Check if component should be active.
	 *
	 * @return bool
	 */
	public function is_active() {
		$settings = new Settings();
		if ( ! $settings->is_module_active( 'woocommerce_booster' ) || ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Payment icons component Constructor
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Payment Icons', 'neve' ) );
		$this->set_property( 'id', $this->get_class_const( 'COMPONENT_ID' ) );
		$this->set_property( 'width', 3 );
		$this->set_property( 'section', 'hfg_payment_icons_component' );
		$this->set_property( 'icon', 'images-alt' );
	}



	/**
	 * The customizer settings for this component are added in WooCommerce Booster module.
	 */
	public function add_settings() {
		$description = sprintf(
			/* translators: %s is link to section */
			esc_html__( 'Click %s to edit payment icons', 'neve' ),
			sprintf(
				/* translators: %s is link label */
				'<span class="quick-links"><a href="#" data-control-focus="neve_payment_icons">%s</a></span>',
				esc_html__( 'here', 'neve' )
			)
		);

		SettingsManager::get_instance()->add(
			[
				'id'                => self::ITEM_ORDERING,
				'group'             => $this->get_class_const( 'COMPONENT_ID' ),
				'tab'               => SettingsManager::TAB_LAYOUT,
				'transport'         => 'post' . $this->get_class_const( 'COMPONENT_ID' ),
				'sanitize_callback' => 'sanitize_text_field',
				'label'             => esc_html__( 'Edit Payment Icons', 'neve' ),
				'description'       => $description,
				'type'              => 'hidden',
				'transport'         => 'refresh',
				'options'           => [
					'priority' => 70,
				],
				'section'           => $this->section,
			]
		);
	}

	/**
	 * Render Payment Icons component.
	 *
	 * @return mixed|void
	 */
	public function render_component() {
		echo \Neve_Pro\Modules\Woocommerce_Booster\Views\Payment_Icons::render_payment_icons();
	}
}
