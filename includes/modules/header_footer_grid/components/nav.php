<?php
/**
 * Nav Component Wrapper class extends Header Footer Grid Component.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\SecondNav as CoreSecondNav;
use HFG\Main;

/**
 * Class Nav
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Nav extends CoreSecondNav {
	/**
	 * Holds the instance count.
	 * Starts at 1 since the base component is not altered.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected static $instance_count = 1;
	/**
	 * Holds the current instance count.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $instance_number;
	/**
	 * The maximum allowed instances of this class.
	 * This refers to the global scope, across all builders.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $max_instance = 2;

	/**
	 * Html constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
		$this->set_property( 'section', 'secondary_menu_primary' . '_' . $this->instance_number );
	}

	/**
	 * Button init.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		parent::init();
		if ( $this->instance_number > 1 ) {
			$this->set_property( 'label', __( 'Page Header Menu', 'neve' ) );
		}
	}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @since   1.0.1
	 * @access public
	 * @return bool
	 */
	public function is_active() {
		if ( $this->max_instance < $this->instance_number ) {
			return false;
		}
		return parent::is_active();
	}

	/**
	 * Allow for constant changes in pro.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @param string $const Name of the constant.
	 *
	 * @return mixed
	 */
	protected function get_class_const( $const ) {
		return constant( 'static::' . $const ) . '_' . $this->instance_number;
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'components/page-header-nav' );
	}
}
