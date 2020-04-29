<?php
/**
 * Class that changes the product markup for different options,
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

/**
 * Class Abstract_Shop_Product
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
abstract class Abstract_Shop_Product {

	/**
	 * Decide if we should load the layout.
	 *
	 * @return bool
	 */
	protected function should_load() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Init actions.
	 */
	public function init() {
		if ( ! $this->should_load() ) {
			return;
		}
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @return mixed
	 */
	public abstract function register_hooks();

}
