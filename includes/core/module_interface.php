<?php
/**
 * Interface that all modules should implement
 *
 * @package Neve_Pro\Core
 */

namespace Neve_Pro\Core;

/**
 * Interface Module_Interface
 *
 * @package Neve_Pro\Core
 */
interface Module_Interface {

	/**
	 * Init module function.
	 *
	 * @return void
	 */
	public function init();

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	public function should_load();
}
