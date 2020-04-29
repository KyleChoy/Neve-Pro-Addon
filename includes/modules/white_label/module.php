<?php
/**
 * Author:          Stefan Cotitosu <stefan@themeisle.com>
 * Created on:      2019-02-27
 *
 * @package Neve Pro
 */

namespace Neve_Pro\Modules\White_Label;

use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module  - main class for the module
 * Enqueue scripts, style
 * Render functions
 *
 * @package Neve_Pro\Modules\Blog_Pro
 */
class Module extends Abstract_Module {

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug        The slug of the module.
	 * @property string  $this->name        The pretty name of the module.
	 * @property string  $this->description The description of the module.
	 * @property string  $this->order       Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active      Optional. Default `false`. The state of the module by default.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug            = 'white_label';
		$this->name            = __( 'White Label', 'neve' );
		$this->description     = __( 'For any developer or agency out there building websites for their own clients, we\'ve made it easy to present the theme as your own.', 'neve' );
		$this->links           = array(
			array(
				'url'   => admin_url( '?page=ti-white-label' ),
				'label' => __( 'Settings', 'neve' ),
			),
		);
		$this->documentation   = array(
			'url'   => 'https://docs.themeisle.com/article/1061-white-label-module-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);
		$this->min_req_license = 3;
		$this->order           = 6;
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	public function should_load() {
		return $this->settings->is_module_active( $this->slug );
	}

	/**
	 * Run Blog Pro Module
	 */
	public function run_module() {
		add_filter( 'ti_white_label_filter_should_load', array( $this, 'should_load' ) );

		if ( class_exists( '\Ti_White_Label' ) ) {
			\Ti_White_Label::instance( NEVE_PRO_BASEFILE );
		}
	}
}
