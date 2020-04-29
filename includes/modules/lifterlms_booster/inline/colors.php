<?php
/**
 * Add inline style for colors.
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Inline
 */

namespace Neve_Pro\Modules\LifterLMS_Booster\Inline;

use Neve\Views\Inline\Base_Inline;

/**
 * Class Course_Membership
 *
 * @package Neve_Pro\Modules\LifterLMS_Booster\Inline
 */
class Colors extends Base_Inline {

	/**
	 * Main color elements selectors.
	 *
	 * @var array
	 */
	private $main_color_selectors = array(
		'border-color' =>
			'.llms-instructor-info .llms-instructors .llms-author,
			.llms-instructor-info .llms-instructors .llms-author .avatar,
			.llms-notification,
			.llms-checkout-section',
		'color'        =>
			'.llms-lesson-preview.is-complete .llms-lesson-complete,
			.llms-loop-item-content .llms-loop-title:hover',
		'background'   =>
			'.llms-instructor-info .llms-instructors .llms-author .avatar,
			.llms-access-plan-title,
			.llms-checkout-wrapper .llms-form-heading',

	);

	/**
	 * Init function.
	 *
	 * @return mixed|void
	 */
	public function init() {
		$this->add_colors_style();
	}

	/**
	 * Add inline style for lifter colors.
	 */
	private function add_colors_style() {
		$primary_color = get_theme_mod( 'neve_lifter_primary_color' );
		if ( empty( $primary_color ) ) {
			return;
		}

		foreach ( $this->main_color_selectors as $prop => $selectors ) {
			$this->add_style(
				array(
					array(
						'css_prop' => $prop,
						'value'    => $primary_color,
					),
				),
				$selectors
			);
		}

	}
}
