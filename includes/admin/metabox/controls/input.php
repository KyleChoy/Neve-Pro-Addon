<?php
/**
 * Index metabox control.
 *
 * @package Neve_Pro\Admin\Metabox\Controls
 */

namespace Neve_Pro\Admin\Metabox\Controls;

use Neve\Admin\Metabox\Controls\Control_Base;

/**
 * Class Index
 *
 * @package Neve_Pro\Admin\Metabox\Controls
 */
class Input extends Control_Base {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'input';

	/**
	 * Render control.
	 *
	 * @param int $post_id the post ID.
	 *
	 * @return void
	 */
	public function render_content( $post_id ) {
		$value      = $this->get_value( $post_id );
		$class      = 'neve-input ';
		$dependency = '';
		if ( $this->settings['hidden'] === true ) {
			$class .= ' neve-hidden';
		}
		if ( isset( $this->settings['depends_on'] ) ) {
			$dependency .= ' data-depends=' . esc_attr( $this->settings['depends_on'] );
			$class      .= ' neve-dependent';
		}

		$markup = '<style>.neve-input input{width:100%;} small.nv-description{margin-bottom:10px;display:block;font-size:12px;color:#898989}</style>';

		$markup .= '<p class="' . esc_attr( $class ) . '" ' . esc_attr( $dependency ) . ' >';

		if ( isset( $this->settings['description'] ) ) {
			$markup .= '<small class="nv-description">' . $this->settings['description'] . '</small>';
		}

		$markup .= '<input 
		value="' . esc_attr( $value ) . '" 
		id="' . esc_attr( $this->id ) . '-input' . '"
		placeholder="' . esc_html( $this->settings['placeholder'] ) . '"
		class="nv-input" 
		name="' . esc_attr( $this->id ) . '">';
		$markup .= '</p>';

		echo $markup;
	}
}
