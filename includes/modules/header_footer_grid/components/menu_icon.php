<?php
/**
 * Menu Icon Component Wrapper class extends Header Footer Grid Component.
 *
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\MenuIcon as CoreMenuIcon;

/**
 * Class Menu_Icon
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Menu_Icon extends CoreMenuIcon {
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
	 * Button constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
		$this->set_property( 'section', $this->get_class_const( 'COMPONENT_ID' ) );
	}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @return bool
	 * @since   1.0.1
	 * @access  public
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
	 * @param string $const Name of the constant.
	 *
	 * @return mixed
	 * @since   1.0.0
	 * @access  protected
	 */
	protected function get_class_const( $const ) {
		return constant( 'static::' . $const ) . '_' . $this->instance_number;
	}

	/**
	 * Additional instances should not style sidebar close button.
	 *
	 * @param array $appearance_array the button appearance control value.
	 *
	 * @return array
	 */
	protected function get_close_button_style( $appearance_array ) {
		return [];
	}
}
