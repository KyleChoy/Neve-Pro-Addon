<?php
/**
 * Add inline style for course / membership page.
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
class Course_Membership extends Base_Inline {

	/**
	 * Init function.
	 *
	 * @return mixed|void
	 */
	public function init() {
		$this->box_shadow();
	}

	/**
	 * Inline style for box shadow.
	 */
	private function box_shadow() {
		$theme_mod = '';
		$context   = '';
		if ( is_memberships() ) {
			$theme_mod = 'neve_membership_box_shadow_intensity';
			$context   = 'membership';
		}

		if ( is_courses() ) {
			$theme_mod = 'neve_course_box_shadow_intensity';
			$context   = 'course';
		}
		if ( empty( $theme_mod ) || empty( $context ) ) {
			return;
		}

		$box_shadow = get_theme_mod( $theme_mod, 0 );
		if ( $box_shadow === 0 ) {
			return;
		}
		$shadow_value = '0px 1px 20px ' . ( $box_shadow - 20 ) . 'px rgba(0, 0, 0, 0.12)';

		$this->add_style(
			array(
				array(
					'css_prop' => 'box-shadow',
					'value'    => $shadow_value,
				),
			),
			'.llms-' . $context . '-list .llms-loop-item .llms-loop-item-content'
		);

	}

}
