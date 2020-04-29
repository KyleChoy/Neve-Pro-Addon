<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-01-28
 *
 * @package Neve Pro Addon
 */

add_action( 'admin_notices', 'neve_pro_not_theme_notice' );

if ( ! function_exists( 'neve_pro_not_theme_notice' ) ) {
	/**
	 * Notice displayed if the theme is not neve.
	 *
	 * @since 0.0.1
	 */
	function neve_pro_not_theme_notice() {
		$plugin_name = __( 'Neve Pro Addon', 'neve' );
		$message     = __( 'is not a WordPress theme. Please install it as a plugin to work properly.', 'neve' );

		printf(
			'<div class="error"><p><b>%1$s</b> %2$s</p></div>',
			esc_html( $plugin_name ),
			esc_html( $message )
		);
	}
}
