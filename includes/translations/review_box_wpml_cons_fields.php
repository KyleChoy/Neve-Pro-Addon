<?php
/**
 * This class is needed to be able to translate the cons repeater fields of the Review Box widget with WPML Plugin.
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Translations;

use \WPML_Elementor_Module_With_Items;

/**
 * Class Review_Box_Wpml_Cons_Fields
 */
class Review_Box_Wpml_Cons_Fields extends WPML_Elementor_Module_With_Items {

	/**
	 * Get the name of the control.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'cons_list';
	}

	/**
	 * Get control properties
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'text',
		);
	}

	/**
	 * Get properties titles.
	 *
	 * @param string $field Field name.
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		return __( 'Review box: cons item', 'neve' );
	}

	/**
	 * Get field type.
	 *
	 * @param string $field Field name.
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		return 'LINE';
	}

}
