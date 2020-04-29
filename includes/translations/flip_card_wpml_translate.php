<?php
/**
 * Add integration with WPML for Flip Card Elementor custom widget from Elementor Booster module.
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Views;

/**
 * Class WPML_Elementor_Slides
 */
class Flip_Card_Wpml_Translate extends \WPML_Elementor_Module_With_Items {

	/**
	 * Get the name of the control.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'buttons';
	}

	/**
	 * Get control properties
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'text',
			'link' => array( 'url' ),
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
		switch ( $field ) {
			case 'text':
				return esc_html__( 'Flip card: back side button text', 'neve' );

			case 'url':
				return esc_html__( 'Flip card: back side button url', 'neve' );

			default:
				return '';
		}
	}

	/**
	 * Get field type.
	 *
	 * @param string $field Field name.
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'text':
				return 'LINE';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
