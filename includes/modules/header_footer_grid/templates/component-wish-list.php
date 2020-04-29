<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Templates;

use Neve_Pro\Modules\Woocommerce_Booster\Views\Wish_List;

$wish_list_position = get_theme_mod( 'neve_wish_list', 'none' );
if ( is_customize_preview() && $wish_list_position === 'none' ) {
	echo sprintf(
		/* translators: %s - path to wish list control */
		esc_html__( 'Activate your wish list from %s', 'neve' ),
		sprintf(
			'<strong>%s</strong>',
			esc_html__( 'Customizer > WooCommerce > Product Catalog > Product Card > Wish List', 'neve' )
		)
	);
} else {
	if ( $wish_list_position !== 'none' ) {
		$wish_list_instance = new Wish_List();
		$settings           = array(
			'tag'   => 'div',
			'class' => 'wish-list-component',
		);
		echo $wish_list_instance->render_wish_list_icon( $settings );
	}
}
