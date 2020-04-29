<?php
/**
 * Custom Checkbox Control.
 *
 * Name:    Header Footer Grid Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Customizer;

use Neve\Customizer\Controls\Checkbox;

/**
 * Class Custom_Checkbox
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Customizer
 */
class Custom_Checkbox extends Checkbox {
	/**
	 * Flag to void content.
	 *
	 * @access public
	 * @var bool
	 */
	public $void_content = false;
	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'neve-checkbox-toggle';
	/**
	 * Send to _js json.
	 *
	 * @return array
	 */
	public function json() {
		$json         = parent::json();
		$json['id']   = $this->id;
		$json['link'] = $this->get_link();

		if ( $this->void_content ) {
			$json['content'] = '';
		}

		return $json;
	}
}
