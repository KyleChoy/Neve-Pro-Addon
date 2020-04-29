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
use HFG\Main;

/**
 * Class Language_Switcher
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Language_Switcher extends Abstract_Component {
	const COMPONENT_ID = 'language_switcher';

	/**
	 * Language Switcher constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Language Switcher', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 2 );
		$this->set_property( 'section', 'language_switcher' );
		$this->set_property( 'icon', 'translation' );
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
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
		$this->default_selector = '.builder-item--' . $this->get_id() . ' > .component-wrap > :first-child';

		return parent::add_style( $css_array );
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-language-switcher' );
	}

	/**
	 * Check if component should be active.
	 *
	 * @return bool
	 */
	public function is_active() {
		$plugins = array(
			'wpml'           => defined( 'ICL_SITEPRESS_VERSION' ),
			'translatepress' => defined( 'TRP_PLUGIN_VERSION' ),
			'polylang'       => defined( 'POLYLANG_VERSION' ),
		);
		foreach ( $plugins as $plugin_status ) {
			if ( $plugin_status === true ) {
				return true;
			}
		}

		return false;
	}
}
