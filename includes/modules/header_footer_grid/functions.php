<?php
/**
 * HFG Stubs & Functions
 *
 * @package Neve Pro Addon
 */

namespace HFG;

if ( ! function_exists( '\\HFG\\parse_dynamic_tags' ) && version_compare( NEVE_VERSION, '2.5.4', '<' ) ) {
	/**
	 *  Stub used in case plugin is upgraded before the theme.
	 *
	 * @param string $input the input string.
	 *
	 * @since Neve 2.5.4
	 * @since Neve Pro Addon - 1.1.4
	 *
	 * @return mixed
	 */
	function parse_dynamic_tags( $input ) {
		return $input;
	}
}

