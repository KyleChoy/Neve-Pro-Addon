<?php
/**
 * Add inline style for shop page.
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Inline
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Inline;

use Neve\Views\Inline\Base_Inline;

/**
 * Class Shop_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Inline
 */
class Shop_Page extends Base_Inline {

	/**
	 * Init function.
	 *
	 * @return mixed|void
	 */
	public function init() {
		$this->same_image_height();
		$this->sale_tag();
		$this->box_shadow();
	}

	/**
	 * Add style for force same image height.
	 */
	private function same_image_height() {
		$same_image_height = get_theme_mod( 'neve_force_same_image_height' );
		if ( $same_image_height === false ) {
			return;
		}

		$image_height    = get_theme_mod( 'neve_image_height', 230 );
		$image_selectors = '.woocommerce ul.products li.product .nv-product-image.nv-same-image-height';
		$this->add_style(
			array(
				array(
					'css_prop' => 'height',
					'value'    => $image_height,
					'suffix'   => 'px',
				),
			),
			$image_selectors
		);
	}


	/**
	 * Add style for sale tag.
	 */
	private function sale_tag() {
		$color      = get_theme_mod( 'neve_sale_tag_color' );
		$text_color = get_theme_mod( 'neve_sale_tag_text_color' );
		$selector   = '.woocommerce span.onsale';

		$this->add_style(
			array(
				array(
					'css_prop' => 'background-color',
					'value'    => $color,
				),
			),
			$selector
		);

		$this->add_style(
			array(
				array(
					'css_prop' => 'color',
					'value'    => $text_color,
				),
			),
			$selector
		);

		$radius = get_theme_mod( 'neve_sale_tag_radius' );
		if ( empty( $radius ) ) {
			return;
		}
		$this->add_style(
			array(
				array(
					'css_prop' => 'border-radius',
					'value'    => $radius,
					'suffix'   => '%',
				),
			),
			$selector
		);
	}

	/**
	 * Inline style for box shadow.
	 */
	private function box_shadow() {
		$box_shadow = get_theme_mod( 'neve_box_shadow_intensity', 0 );
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
			'.woocommerce ul.products li .nv-card-content-wrapper'
		);

		$image_width = get_option( 'woocommerce_thumbnail_image_width' );
		$this->add_style(
			array(
				array(
					'css_prop' => 'flex-basis',
					'value'    => $image_width . 'px',
				),
			),
			'.woocommerce .nv-list ul.products.columns-neve li.product .nv-product-image.nv-same-image-height'
		);

		$this->add_style(
			array(
				array(
					'css_prop' => 'padding',
					'value'    => '15px',
				),
			),
			'.nv-product-content '
		);
	}

}
