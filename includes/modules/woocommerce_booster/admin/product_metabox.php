<?php
/**
 * Handles single product featured video.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-02-11
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Admin;

use Neve\Admin\Metabox\Controls_Base;
use Neve_Pro\Admin\Metabox\Controls\Input;

/**
 * Class Product_Metabox
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Admin
 */
class Product_Metabox extends Controls_Base {

	/**
	 * Add controls
	 */
	public function add_controls() {
		$this->add_control(
			new Input(
				'neve_meta_product_video_link',
				array(
					'default'         => '',
					'hidden'          => false,
					'label'           => __( 'Featured Video', 'neve' ),
					'placeholder'     => __( 'MP4 / Youtube / Vimeo Link ', 'neve' ),
					'active_callback' => array( $this, 'show_on_single_product' ),
					'description'     => __( 'Add a featured video to your product. This displays as the first item in the product gallery.', 'neve' ),
					'priority'        => 5,
				)
			)
		);
	}

	/**
	 * Callback to only show setting on single product.
	 *
	 * @return bool
	 */
	public function show_on_single_product() {
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' ) {
			return true;
		}

		if ( ! isset( $_GET['post'] ) ) {
			return false;
		}

		$post_type = get_post_type( $_GET['post'] );

		if ( $post_type === 'product' ) {
			return true;
		}

		return false;
	}
}
