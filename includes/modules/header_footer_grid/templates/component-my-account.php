<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Templates;

use function HFG\component_setting;
use function HFG\parse_dynamic_tags;
use Neve_Pro\Modules\Header_Footer_Grid\Components\Icons;
use Neve_Pro\Modules\Header_Footer_Grid\Components\My_Account;

$my_account_page = get_option( 'woocommerce_myaccount_page_id' );
if ( empty( $my_account_page ) && current_user_can( 'manage_options' ) ) {
	echo sprintf(
		/* translators: %s  is WooCommerce page link settings */
		__( 'You need to create the "My Account Page" in %s' ),
		sprintf(
			/* translators: %s is WooCommerce page label */
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=advanced' ) . '">%s</a>',
			__( 'WooCommerce page settings.', 'neve' )
		)
	);
}

$enable_registration = component_setting( My_Account::ENABLE_REGISTER, 0 );
if ( ! empty( $my_account_page ) && ( (bool) $enable_registration === true || is_user_logged_in() ) ) {

	$icon_size = component_setting( My_Account::ICON_SIZE_ID, 20 );
	$icon      = component_setting( My_Account::ICON_SELECTOR, 'user_avatar' );
	$user_id   = get_current_user_id();

	$icon_code = get_avatar( $user_id, $icon_size );
	if ( $icon !== 'user_avatar' ) {
		$icon_instance = new Icons();
		$icon_code     = $icon_instance->get_single_icon( $icon );
	}

	$label = parse_dynamic_tags( component_setting( My_Account::LABEL_TEXT ) );
	if ( ! is_user_logged_in() ) {
		$label = component_setting( My_Account::REGISTER_TEXT, __( 'Register', 'neve' ) );
	}

	$button_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
	$dropdown    = component_setting( My_Account::ENABLE_DROPDOWN, 0 );

	echo '<div class="component-wrap my-account-component ' . ( (bool) $dropdown === true ? 'my-account-has-dropdown' : '' ) . '">';
	echo '<div class="my-account-container">';
	echo '<a href="' . esc_url( $button_link ) . '" class="my-account-wrapper ' . ( ! empty( $label ) ? 'has-label' : '' ) . '">';
	if ( is_user_logged_in() && ! empty( $icon_code ) ) {
		echo '<span class="my-account-icon">' . $icon_code . '</span>';
	}
	echo '<span class="my-account-label">' . esc_html( $label ) . '</span>';
	echo '</a>';
	if ( (bool) $dropdown === true && is_user_logged_in() ) {
		echo '<ul class="sub-menu">';
		echo My_Account::get_account_links();
		echo '</ul>';
	}
	echo '</div>';
	echo '</div>';
}

