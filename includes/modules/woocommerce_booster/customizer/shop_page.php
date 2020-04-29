<?php
/**
 * Shop page WooCommerce Booster Module
 *
 * @package WooCommerce Booster
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;

/**
 * Class Shop_Page
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Shop_Page extends Base_Customizer {

	/**
	 * Init function sooner than WooCommerce.
	 */
	public function init() {
		add_action( 'customize_register', array( $this, 'register_controls_callback' ), 9 );
		add_filter( 'neve_last_menu_item_components', array( $this, 'add_wish_list_menu_option' ) );
		add_filter( 'neve_sidebar_layout_choices', array( $this, 'add_off_canvas_option' ), 10, 2 );
		add_action( 'customize_controls_print_styles', array( $this, 'elements_ordering_inline_alignment_style' ) );
		add_action( 'customize_controls_print_scripts', array( $this, 'elements_ordering_inline_alignment_script' ) );
	}

	/**
	 * Hide price in Card content elements order in customizer.
	 */
	public function elements_ordering_inline_alignment_style() {
		$alignment = get_theme_mod( 'neve_product_content_alignment', 'left' );
		if ( $alignment !== 'inline' ) {
			return false;
		}
		echo '<style>';
		echo '#customize-control-neve_layout_product_elements_order .order-component[data-id="price"] { display: none; }';
		echo '</style>';

		return true;
	}

	/**
	 * Handle the customizer display when alignment settings are changing.
	 */
	public function elements_ordering_inline_alignment_script() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				wp.customize.bind('ready', function () {
					var value = wp.customize('neve_product_content_alignment').get();
						if (value === 'inline') {
							jQuery('#customize-control-neve_layout_product_elements_order .order-component[data-id="title"]').find('p').text('<?php echo esc_html__( 'Title + Price', 'neve' ); ?>');
						}
				});

				wp.customize('neve_product_content_alignment', function (value) {
					value.bind(function (newval) {
						if (newval !== 'inline') {
							jQuery('#customize-control-neve_layout_product_elements_order .order-component[data-id="title"]').find('p').text('<?php echo esc_html__( 'Title', 'neve' ); ?>');
							jQuery('#customize-control-neve_layout_product_elements_order .order-component[data-id="price"]').css('display', 'flex');
						}
						if (newval === 'inline') {
							jQuery('#customize-control-neve_layout_product_elements_order .order-component[data-id="title"]').find('p').text('<?php echo esc_html__( 'Title + Price', 'neve' ); ?>');
							jQuery('#customize-control-neve_layout_product_elements_order .order-component[data-id="price"]').hide();
						}
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Add wish list item in last menu items options.
	 *
	 * @param array $items Last menu items options.
	 *
	 * @return mixed
	 */
	public function add_wish_list_menu_option( $items ) {
		$items['wish_list'] = __( 'Wish List', 'neve' );

		return $items;
	}

	/**
	 * Add customizer controls.
	 */
	public function add_controls() {
		$this->group_controls();
		$this->add_page_layout_controls();
		$this->add_product_card_layout_controls();
		$this->add_card_image_controls();
		$this->add_card_content_controls();
		$this->add_sale_tag_controls();
	}

	/**
	 * Add control groups to better organize the customizer.
	 */
	private function group_controls() {
		$this->add_control(
			new Control(
				'neve_woo_shop_settings_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'General', 'neve' ),
					'section'          => 'woocommerce_product_catalog',
					'priority'         => 0,
					'class'            => 'woo-shop-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 3,
					'expanded'         => true,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_shop_page_layout_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Page Layout', 'neve' ),
					'section'          => 'woocommerce_product_catalog',
					'priority'         => 100,
					'class'            => 'page-layout-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 5,
					'expanded'         => true,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_product_card_layout_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Product Card', 'neve' ),
					'section'          => 'woocommerce_product_catalog',
					'priority'         => 200,
					'class'            => 'card-layout-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 5,
					'expanded'         => false,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_card_image_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Card Image', 'neve' ),
					'section'          => 'woocommerce_product_catalog',
					'priority'         => 300,
					'class'            => 'card-image-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 3,
					'expanded'         => false,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_card_content_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Card Content', 'neve' ),
					'section'          => 'woocommerce_product_catalog',
					'priority'         => 400,
					'class'            => 'card-content-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 3,
					'expanded'         => false,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_tag_ui_heading',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'            => esc_html__( 'Sale Tag', 'neve' ),
					'section'          => 'woocommerce_product_catalog',
					'priority'         => 500,
					'class'            => 'sale-tag-accordion',
					'accordion'        => true,
					'controls_to_wrap' => 8,
					'expanded'         => false,
				),
				'Neve\Customizer\Controls\Heading'
			)
		);
	}

	/**
	 * Controls that refer to page layout.
	 */
	private function add_page_layout_controls() {
		$this->add_control(
			new Control(
				'neve_products_per_row',
				array(
					'sanitize_callback' => 'neve_sanitize_range_value',
					'default'           => json_encode(
						array(
							'desktop' => 4,
							'tablet'  => 2,
							'mobile'  => 2,
						)
					),
				),
				array(
					'label'           => esc_html__( 'Products per row', 'neve' ),
					'section'         => 'woocommerce_product_catalog',
					'units'           => array(
						'items',
					),
					'input_attr'      => array(
						'mobile'  => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 1,
						),
						'tablet'  => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 2,
						),
						'desktop' => array(
							'min'     => 1,
							'max'     => 6,
							'default' => 4,
						),
					),
					'input_attrs'     => [
						'step'       => 1,
						'min'        => 1,
						'max'        => 6,
						'defaultVal' => [
							'mobile'  => 1,
							'tablet'  => 2,
							'desktop' => 4,
						],
					],
					'priority'        => 110,
					'responsive'      => true,
					'active_callback' => array( $this, 'products_per_row_active_callback' ),
				),
				version_compare( NEVE_VERSION, '2.6.3', '>=' ) ? 'Neve\Customizer\Controls\React\Responsive_Range' : 'Neve\Customizer\Controls\Responsive_Number'
			)
		);

		$rows    = get_theme_mod( 'woocommerce_catalog_rows' );
		$cols    = get_theme_mod( 'woocommerce_catalog_columns' );
		$default = ( $rows * $cols ) > 0 ? $rows * $cols : 12;
		$this->add_control(
			new Control(
				'neve_products_per_page',
				array(
					'default' => $default,
				),
				array(
					'label'       => esc_html__( 'Products per page', 'neve' ),
					'section'     => 'woocommerce_product_catalog',
					'priority'    => 120,
					'type'        => 'number',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
					),
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_shop_pagination_type',
				array(
					'default'           => 'number',
					'sanitize_callback' => array( $this, 'sanitize_pagination_type' ),
				),
				array(
					'label'    => esc_html__( 'Pagination', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 130,
					'type'     => 'select',
					'choices'  => array(
						'number'   => esc_html__( 'Number', 'neve' ),
						'infinite' => esc_html__( 'Infinite Scroll', 'neve' ),
					),
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_enable_product_filter',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => true,
				),
				array(
					'label'    => esc_html__( 'Products filtering', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_toggle_control',
					'priority' => 140,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_enable_product_layout_toggle',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'    => esc_html__( 'Layout toggle', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_toggle_control',
					'priority' => 150,
				)
			)
		);
	}

	/**
	 * Controls that refer to produt card layout.
	 */
	private function add_product_card_layout_controls() {

		$this->add_control(
			new Control(
				'neve_product_card_layout',
				array(
					'default'           => 'grid',
					'sanitize_callback' => array( $this, 'sanitize_shop_layout' ),
				),
				array(
					'label'    => esc_html__( 'Layout', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 210,
					'choices'  => array(
						'grid' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAEtElEQVR42u3cIXPjOhSG4f3/9KOGgoFmhmZlspvUabITsp5LjMyMzgWSHadNE7fJve1G74fSmbTgGUk+OpL7y8hn8gsCvPDCCy+8CF544YUXXngRvPDCCy+8yP/g9c/vbwteeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeP0Ur9dbsk/Ma9/U1U2pX1Py2j9XN6dJyOsOXFW1TcZrew+uqk7Ga3MXrwsD7MG87sN1YQV7SK96+9VqYpOk1/rL5egrXnjhhddP8drjtdxrv6mrqm72eC3y2sWd9/MeryVeU6Nig9cCr9leco/Xda/N5f3NS1Xv8Jp5rS96barqDFjKXi+X+g1h8L0DY/06v36Nc/UtWNLPx6nX+vJxm+wNWNJeYy9/c6mreAqWeH3fPFfVenu5CXsCxn77es96DobXghb/DAyvJSciRzC8Fh0gTWB47ep6e/28bQRL3mtXz8v7j48n6z1exxbY9vpp7itex45hALt4+I3XjKuqttfuCuA156qq7ZWrFXj9bj5zZQIvvPDCCy+8EvHarj+RHV7cz8ELL7zwwguv+fsK66+mTsyr5v2OT3nd6f2hXSpeu7twrdN5/7G5A1e9S+h95OY/5XrA9913m5sW/efL1/Ufz4v/p4AXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXkl6dX++LX+l1yMGL7zwwgsvvAheeOGFF1543T9OMfnh+pe9lOM1przdq1zyVx7FSx6vJV65mVmTSSu8FnuZlzSY937ocpVmvZck34evNSvJNee9vJNUHhLzaiT1JqmRVFo7TtLRQZIyKTeT1JpZL6k36920/MVvKaHxFVBU9mE585K6YHkIau+8nFSYHTKpSckrrl+SVA5mRZxYpVSYufgsyN97NZIzM3uSigSfj4p8WZyIraShD6NsXO9PvArpKdF6ojQzSc1xrsUP7TjHmvdeLvxCel6hvj9dm/D6eL0PiRan83E4Ox87Sb3lcT723vuEvWbrfW62iuv9avTyUwHi43rv01nvz3l17+oJP9UTK8n11jlJvQ2xngi/WP6IDfk3eNnhXL0axlcTfghewW18XPh06tU3Xmf3Q733jZkdVpL8MD4Twn6onareh/Z6xOCFF1544YUXwQsvvPDC6+b4HC+8bkyzmvUhfCa5cC+gfZo3KPCKyWP7qrXj2avrx1bW2ADDK+ZpOh7qzVbjZ3fsF8YmF15mFg55ijCYnsa2cyHpUEiu83nvfkRH/sd4jccVhVRMhxyZ5J3kzefmpQyvKWU8DiulwlxcrJzkV9GL5+NJDt63I5F57/s4RzsvqcgdXh8NMzfEz8NKKqYHQeYHvN6ky0MJEUbceGLUxYoia/F6u+ariKNoKOb3WL3zkrIer1lFsZLcWJO2mZR38/3Q4BbcA07Iq8uOg2sswCzcBxjM52Y5XrMM2awebXW8nJTFeqLP8Dq7HZI7boekMtYTrF+nyWZeneZ3DfPpM8/HY2ZErj3xsiY0K8q/r6FDvxCvh/T6S4MXXnjhhRdeBC+88MILL7wIXnjhhdcj51987R/KzzMKWAAAAABJRU5ErkJggg==',
						),
						'list' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAE0UlEQVR42u3csVPqTBTG4fv/t2+bckvKdCnT2W2iGASH5ma+JlW6VOcrdkOCIKBBwJvfW+GMOvLM7ubsctY/Rr6SPxDghRdeeOFF8MILL7zwwovghRdeeOFF8MILL7zwwovghRdeeOGFF8ELL7zwwovghRdeeOGFF8ELL7zwwovg9RNe/73fLXjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjh9Sheb1OymZnXpiqLSSnf5uS1eS4mp5qR1xW4imL1bS+nmHR7/n16Kb2z1+oaXEU53UvKp3vll/yWSV7Lq3idGGCXe8n/Aq/rcJ1Ywc57pWZmVSItfo1XufpuNbG8kpd5SZ1577smVW7Weknybfi2aiG56riXd5Ly7S29Xr5djr5dy6uS1JqkSlJudT9JewdJSqTUTFJtZq2k1qx1u+Uvfpdm4RXGV0BR3oblzEtqguU2qB14OSkz2yZSNSevuH5JUt6ZZXFi5VJm5uKzID30qiRnZvYkZTOZj+PnoyJfEidiLalrwyjr1/s9r0x6uv16P/La3M0rNzNJ1TDX4ou6n2PVoZcLP3Anr82yLIqy2tzBK9T3+2vTo3ut4877eXNLr3GJEC3252N3dD42klpL43xsvfc399odVCzv7TVa71OzRVzvF72X3xUgPq73/g7r/WgvubmzV3NQT/hdPbGQXGuNk9RaF+uJ8IP5ZRvyK3ktT+9vXotyfSsv2x6rV8P4qsIXwSu49Y8Lf9P66+Wk17IojoD9lNfR/VDrfWVm24Uk3/XPhLAfqndV7828Xk+dN4TBdwA21esuucH61c/Vj2Bz9hrOWl8/4zoAm7VXf5a//JzrI9isvd431XNRvKxOcX0Am7fX8Xw8sx6D4XWWaw8Mr/NcYzC8LuAageG1LsvVOa4BbPZe63Jc3n/+8WS5wet9dwS2OsdVFG94DSeGAezkh994jbiKYnWuVwCvMVdRrM60VuD1Xn2lZQIvvPDCCy+8ZuK1evlC1nh9I3jhhddMvHyKF14TvarFqE/CJ5IL9xbqp3EDxWN5FS/fTTnVK43tNbUNveGu7Vtt+gadh/GaeJdv8v2Op137amu26F+7oZ8pNuE8iteV7g+tv+nVSsrCYHrq2+IySdtMco1PW3dZx+DNvNZX4Tqx/p1Z0WM7ZSZluybMRPJO8uZT81LySF5f2zOe+XDt6155bNfNpcxcXKyc5BfR69Gej9cAO8V1xmvrfd0Tmfe+jXO08ZKy1D2g1/t6OWnRfz7drn/RG8gl18XX3ULKdg+CxHeP5vWzueDPb9JQQoQR13e0NrGiSGq8Pq75yuIo6rLxPVvvvKSkxWtUUSwkt41f1ImUNoNkap274J7yjLyaZBhcfQFm4b5CZz41S/EapUtG9Wit4fJUEuuJNsFrlGE7JDdsh6Q81hOsX/tJRl6Nxnch091rno9DRkSu3vOyKhxW5D9woPOv/j+rh9wP4fXPeP1U8MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILrwlezd+75Vd6EbzwwgsvvPAieOGFF154EbzwwgsvvPAieOGFF1544UXwwgsvvPAieOGFF1544UXwwgsvvPAieOGFF1544UXwmpz/AdpnH8pqwZaBAAAAAElFTkSuQmCC',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);

		$this->add_control(
			new Control(
				'neve_box_shadow_intensity',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 0,
				),
				array(
					'label'       => esc_html__( 'Box shadow (px)', 'neve' ),
					'section'     => 'woocommerce_product_catalog',
					'type'        => 'neve_range_control',
					'step'        => 1,
					'input_attrs' => array(
						'min'        => 0,
						'max'        => 30,
						'defaultVal' => 0,
					),
					'priority'    => 220,
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_add_to_cart_display',
				array(
					'default'           => 'none',
					'sanitize_callback' => array( $this, 'sanitize_add_to_cart_display' ),
				),
				array(
					'label'    => esc_html__( 'Add to Cart Button', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 230,
					'choices'  => array(
						'none'     => array(
							'url' => 'data:image/png;base64,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',
						),
						'after'    => array(
							'url' => 'data:image/png;base64,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',
						),
						'on_image' => array(
							'url' => 'data:image/png;base64,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',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);

		$this->add_control(
			new Control(
				'neve_quick_view',
				array(
					'default'           => 'none',
					'sanitize_callback' => array( $this, 'sanitize_button_position' ),
				),
				array(
					'label'    => esc_html__( 'Quick view', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 240,
					'choices'  => array(
						'none'   => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYBAMAAABIEHj+AAAAJ1BMVEXS0tLT09PU1NTV1dXW1tbX19fY2NjZ2dna2trb29vc3Nzd3d3///8EnYHVAAAC40lEQVR42u3bP2/TQBjH8cfFFCGWTEhplwyIhcWoggGWwsrEiFgoAwtLBxZgCUxAJlizUE8gb7S0jm/hBfRFMeS/YzvFl+fuUL/PWKnSRz+71+eePJHzMOuPAAMGDBgwYP8BzGgXMGDAgAEDBgwYMGDAgAEDBgwYMGDAgAEDBgwYMGAOYD8/tavPmSps9FTaVvRMEVYkYlEP9GAfbFwSDbRgZ2JX17RgfUuYDHRgRccWtqMDO7F1yVUd2HdrmGQqsBf2sPcqsMQe9kQF1rGH3VGB2btkVxG29apNvRz/8g1F2G6r/mZyBmrCHrbrvBJgwIABCw9WhAnLH0t0PwsPNkpERG6GB+v/4xvnCpZP+pgrocGOph3W88Bg+1PY9bBgRf2NzC/sdD6QqEDkb73BTuqvisYUvYo3zxHsV8M8wqQicYiwoldxgQwBlopUROYIdlz/jhW9yju3/7/KdPzz2Pc5FlcHthKZq5M/qTv50xqx7/+Vs8DKkbmCndV0F6nUPGRn/dhh5ex+IbBSZO462J6IyHZ9YKXIHPb8j0RuZw2BLUfm9JaUmabAliPzeq8sBbYUmVdYWp5sxmHAVgJbjMwnLF0dBschwCoCW4jMIyytmp/HfmA/vqwJbB6ZU1jeiYbNgc0jcwrri3TXBDaLzCUsF5FZZOmazyhdwvois8hqA5s2Rg5h44HPJLLawDzAJhOybnNg7mHTCVk0bAzMPWy269BtDMw5LJ/fLIdNgTmHLSyHdJsCcw3LF3d2Pko4sOMLfwwODBgwYMCAXU7Y772L1j3WG4ABAwYM2CWD7bRyOdgcjvba1F39XesAt9OD3effD/UbEIf2sIEK7MjatbI9shnYqTVsWwdmept+9zcF+2r7JDMl2MjywOgaJZj5ZuXaytRg5sDmQb42erDiTeuneeudUYRtuoABAwYMGDBgwIABAwYMGDBgwIABAwYMGDBgwIABA+Yb5rGAAQMGDJgW7C8Yx9yATyLXTgAAAABJRU5ErkJggg==',
						),
						'top'    => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAMAAACN4JX/AAAAVFBMVEU/xPtTyPtkzPx00fyD1fyR2fye3f2q4f225f3B6f3M7P7S0tLT09PU1NTV1dXW1tbX19fX8P7Y2NjZ2dna2trb29vc3Nzd3d3i9P7s+P71+/////8xOQAQAAAD7UlEQVR4AezUQWrDMBBA0exnrARHVha9/z27KpRSaAzF0sD7R3iLf/vQmXjx4sWLFy+d9BIvXrx48XqVjxcvXrx48eLFixcvXrx48eLFixcvXrx48eLFixcvXrx48eLFixcvXrx48eLFixcvXrx48eLFixevK+PFaxz92o5R1+t4ZFxfPo6SXqPFrNqo59Uz5pW9mtcz5vas5dVjdr2S18iYXY5CXveY36OO14gVGmW89lihZxmvFit0L+OVsUJbGa9Yoqzpte3XtcX3Snrl68JGlvdqrytrvHjx4sWLFy9ev8VrvA3Aq9+3iMi2v4HG62jxVe68/vLqceJtvHrETzBet2Xi9Z9gvHjx4sWLFy9evHjx4sWLFy9en+yY0c6jIBSECyggd9wh8/7vuQ4y/0pDN3tL/k5STj0gtl+YIyq5CKCEL68LReDQ6F7UAXh9Cb29FHGrGHVd8gCOV1NmigrA+fdc6XUCOy8FwNyj8qq8hALxM68AqaiLuBhvefZQCQgTXhGIfZ5do1bkJRQCNudlAWR3o/VKCleTAWB7dBNe+80z9asUjl6Sl1BsGYCd82JbBDf15CZcgyG5zoZzpQoYtq3fsF2TV4R++sliNOdV6SLKhNCTIy6S1AIKU16JUzigcduBuCivIhRcGuecl72rtMRkBlcaNRiSrZvyCjxgw+tFNmvyul0oZ855bSQ58qJ44mhIlf2xfrUZHfkmVMOlVcj/l/E6iWY0ZG52nPKip+v1SRerovK1th9ZXP7bj/C2AvHdkJX0p34kSs9E7HHter8V97j3UWlS70muGiaj9lKDITMnm/BSeWRx23uklt1PmAxqa/ySbpf+X/uJBqjapyGp8ImXYW/9iavyIiApPTbtx/NO5xpUIiFTgaD78hsQ2IGXtN3OR3rEtZ+HxMuckLL++dsYJcVWytAynPKKHK249PN2oxF9ReFRnT1aRzH8SVJpKGFBIOa8dgCuR7syL8lly2DCSTBh7NwOIvTf9znf94V/2ruDGwthIIiCATA2X7CY/DPdGJAQtFF1CHXwzfN48eLFixcvXrx48eLF66Xx4sXr5HXXf76+3L+e8Z+PV4DXr9+/3xe83FPgxYsXL168ePHixYsXrxru+17xWqo/t1oCvNwnd/8+y2vVV7jktUd4/U3jNSrp+cr3igjEbBN5nS3qtc/3OiogB5blFQ1Wx2z9x6O9yNXGhD3WrV7Sqm3Ofu3Y1+fJ2roPPWn9bV68ePHixYsXL168ePHixYsXL168ePHixYsXL168ePHixYsXL168ePHixYsXL16TjxcvXrx48eLFixcvXryMFy9evL4yXrx48eJl/5T98moYltDRAAAAAElFTkSuQmCC',
						),
						'bottom' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAMAAACN4JX/AAAAVFBMVEU/xPtTyPtkzPx00fyD1fyR2fye3f2q4f225f3B6f3M7P7S0tLT09PU1NTV1dXW1tbX19fX8P7Y2NjZ2dna2trb29vc3Nzd3d3i9P7s+P71+/////8xOQAQAAAEvklEQVR4AezdQY7bMAxG4dmTlg1bohe9/z3bTYH5kRQaDQrb1Lx3g3wLJREB8eMXjYQXXnjhhRdehBdeeOGFF16DnenDCy+88MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDC68rwwgsvvPDCCy+88MILL7zwwguvaPXaWuT1apvb9fnWUnpFsbsqkc+rut2X12xeh93bkcur2t3VTF7ht3t5JPJa7f62PF5hTyjSeO2P8DrSeJVHeK1pvPwRXksaL3tEntNr2a9rsc+l9PLzwsLTe5XzygpeeOGFF1544YXXu/AKvL7sVdfFzLzsX0DDqxX7m+949byqDZxteAlXDwyv8JGhBF6rvdTw+qdXDE298NqHLo21erQf57UOTAm1WMxs+2leyzuv+lWu/oAML+Hqg+ElXAo2v1cZP7+Eqw/G96Nw9cGm//219rm0dX4v/TRaG+QSMP4/9rkUbG6v8zCtjHMp2NReL2AlxrkUbP771U8G+9nn6oPNf3/v9qdlj3EuBZvbSyzEaoBLwJg/drgEDK8+l4Dh1ecSMLz6XAKGV59LwPASrnGweb2i1uhxjYNN61XdzNv3uRRseq9qZgo2zqVgE3oJl4CNcWnb1F7CpWDf47Iyt5dwKZhy4aVcCqZceCmXgr3nwku5FOyVCy/lUrBXLryUS8FeufBSLgGr77jwKtYPL7zwwgsvvPDCCy+88MILL7y28v/bZvDiPQW88MILL7zwwgsvvPDCCy8P3vcd8TIv1+WWy4v3yXn//gKvlf0KQ17HI7xqGq/wBMfX/V7PWhCzJ/I6lwec9pm8mt/M5ZHI634wb9n2P7blRq4lEu5j3f0mLd9z7q+NY72ebFmPOB/o9ZE+vPD6bnjhhRdeeOGFF1544YUXXnjhhddvdsygOVIQCsIgCgw3bkj///+5Q0s7xGK39kpluio88wIYvnqNqIsASvjyeqMIABAdf3kBXheht29FXCrWfJIewMtQuadMAM7PWMmcwNFuBcBevfKqvIQC8e+8AqTy4eUhSLwuvEhAmPCKQOzzHOq1Ii+hELA5rw1Adhdar6RwURbA1qOb8DounqnfpQDbmryEYs9c8YwX2yK4qSd34RoMqTobxkoVsK3l3y3bJXnF+18/gdeUF5d5MGlD6EnhksJdQGHKK7UpHEBuBxAX5VWEwnjgnPPauEtLTGaw0iQZknac8gpAYNPuF1uzJi8u83bmnNdOkiMvSgMHQ9KOw1iKM7rGN6FaIJoC2N/G6ySaH4bMtOOcl6mo759kCgq3r+X96ID6336E3yoQn4aspD/zY0PpWyL2uPZ+vxc3PPua0my/34BqWzLeZ6nRkJmTzXl5lqQzR49m5fOEzWjayS/pcen/dZ4goLo9D3Jhxkv1h3rHRXkRkJSGQ/trfNI5Qr2Q7DeI5r78AIJt5HVrv5yPNMS134fEy56Qslb+6KOk2EoZKsMpr8jeiiu/b5NG9JWrdXX2ah3FcEya9GMLCwIx53UAcD1uK/OSXOYybDgbmEcJ7K+G0H+/53y/F/5phw5IAAAAGAb1b/0Yh6ER9OXLly9fvv58+fLly5cvX758+fKFL1++fPnCly9fjS8GXCJVTdhaACgAAAAASUVORK5CYII=',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);

		$this->add_control(
			new Control(
				'neve_wish_list',
				array(
					'default'           => 'none',
					'sanitize_callback' => array( $this, 'sanitize_button_position' ),
				),
				array(
					'label'    => esc_html__( 'Wish List', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 250,
					'choices'  => array(
						'none'   => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYBAMAAABIEHj+AAAAJ1BMVEXS0tLT09PU1NTV1dXW1tbX19fY2NjZ2dna2trb29vc3Nzd3d3///8EnYHVAAAC40lEQVR42u3bP2/TQBjH8cfFFCGWTEhplwyIhcWoggGWwsrEiFgoAwtLBxZgCUxAJlizUE8gb7S0jm/hBfRFMeS/YzvFl+fuUL/PWKnSRz+71+eePJHzMOuPAAMGDBgwYP8BzGgXMGDAgAEDBgwYMGDAgAEDBgwYMGDAgAEDBgwYMGAOYD8/tavPmSps9FTaVvRMEVYkYlEP9GAfbFwSDbRgZ2JX17RgfUuYDHRgRccWtqMDO7F1yVUd2HdrmGQqsBf2sPcqsMQe9kQF1rGH3VGB2btkVxG29apNvRz/8g1F2G6r/mZyBmrCHrbrvBJgwIABCw9WhAnLH0t0PwsPNkpERG6GB+v/4xvnCpZP+pgrocGOph3W88Bg+1PY9bBgRf2NzC/sdD6QqEDkb73BTuqvisYUvYo3zxHsV8M8wqQicYiwoldxgQwBlopUROYIdlz/jhW9yju3/7/KdPzz2Pc5FlcHthKZq5M/qTv50xqx7/+Vs8DKkbmCndV0F6nUPGRn/dhh5ex+IbBSZO462J6IyHZ9YKXIHPb8j0RuZw2BLUfm9JaUmabAliPzeq8sBbYUmVdYWp5sxmHAVgJbjMwnLF0dBschwCoCW4jMIyytmp/HfmA/vqwJbB6ZU1jeiYbNgc0jcwrri3TXBDaLzCUsF5FZZOmazyhdwvois8hqA5s2Rg5h44HPJLLawDzAJhOybnNg7mHTCVk0bAzMPWy269BtDMw5LJ/fLIdNgTmHLSyHdJsCcw3LF3d2Pko4sOMLfwwODBgwYMCAXU7Y772L1j3WG4ABAwYM2CWD7bRyOdgcjvba1F39XesAt9OD3effD/UbEIf2sIEK7MjatbI9shnYqTVsWwdmept+9zcF+2r7JDMl2MjywOgaJZj5ZuXaytRg5sDmQb42erDiTeuneeudUYRtuoABAwYMGDBgwIABAwYMGDBgwIABAwYMGDBgwIABA+Yb5rGAAQMGDJgW7C8Yx9yATyLXTgAAAABJRU5ErkJggg==',
						),
						'top'    => array(
							'url' => 'data:image/png;base64,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',
						),
						'bottom' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAMAAACN4JX/AAABHVBMVEU/xPtCxPpDxfpHxflIxflLxfhMxvlPx/hTyPtXxvRXx/VXyPZYxvRYx/VYyPZZyPZayPZfyfVgyfVkyPFkzPxnyvNqy/NryO9syO5sy/Jty/JuyO5uy/JvzPJwzPJxzPFxzPJ00fx9yup+yumBzu6Bzu+Dz+6D1fyEy+iHz+2I0O2J0O2czeGezeCe3f2gzeCj1Oil1Oimzt6m1Oen0OGn1Oeo1eeq1eeq4f2t1ea25f2/0Ne/2OPA0NfB6f3E0dbE2eLI2uHJ2uHM0dTM7P7N0tPN2uDO2+DQ0tLR0tLS0tLT09PU1NTV1dXW1tbX19fX3N7X8P7Y2NjY3N7Z2dna2trb29vb3d3c3Nzc3d3d3d3i9P7s+P71+/////8yEh5nAAAEv0lEQVR4AezcXcqdOhyF8TO+f4yCmBC8OPMfRntTeBfuvktpUZM+zxB+7O1HFvjf/3QlvPDC62J44YUXXoQXXnjhhdfefXjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhdWd44dVqubfa+vWqS4r7S0vt0qvleKrc+vMqKZ4rld68tni2rS+vEk9XevJqKZ4utY685ni+pR+vFm+odeO1xhvauvHK8YbmbrxSvKGpG694RalPr2m9rym+1qVX2m+spe698n5nGS+88MILL7zwwutTeLXTAHiVeYqIlNcTaHjVHL9KK17Oq8Q3lhJeyuXB8GrpyiiB1xyHKl6/9WqXVi+81jiWTkKUrf5zXvOVlVBqk/kpDuk1xYfKSS4ZyPDyXAqGl+dSsPG9srl+GS4B4/7ouTzY+M9fs+fS5uG9vv1DVsdlhHl/NFwCNrjXvoWWPZcBG9jrCJab5TJg45+vfjFYd8/lwcY/v0/xs2ltnsuAjeylFmJluAwY+6NwGTC8hMuA4SVcBgwv4fJgeAmXB8PLcBmwgb1aKc1wnQcb36ukiFQN11mw8b1KRAiY4TJgo3opl4Kd59KWQb2US8D+hCvy0F7KpWDKhZdyKZhy4aVcCiZceB25jmDKhZdyHcGUCy/lOoIpF17KdQQrn7jwyuHDCy+88MILL7zwwgsvvPDCC68l//2WQb34ngJeeOGFF1544YUXXnj9aO/ulpoGwjCOP0iwwY9GMIhKQaqIQhSNaD+w1lqrxVEZYhlDN2Xv/zKcqdbmbZuQHLCznX1/Zzn9T5psN5vNx5fPN5aXFrCwtLzh+V3ulabl3QBV9Fpz1uv7uVCjX13FLKvVvva9SLCfSry9jSROXf9eir1fR5pSm/e/j3uxiHTWa517naitdfwYl9sJ9e11pjTXlw1k8ainbS+lnx/6fBfZrJ3q2kvlB2KO7yOrh6GuvcQPZb2eILtdbXv9VvWLfIU8Knr1Uh/sg4U8rLZevehGEwqsI59NvXoRv67+FHuDvOr69hLnZydXm+zbLeR1M9Sml3pV5HdocK9V5Ldibq8GptiuizFyMNIwttceJtQiKeVF82+lcudCShkdYMK+sb2KINyB/KcGoDk6iGwQRVN7fQVhD+R/QTkaH0Sguob28kEcySQHIA4N7eWBkIkiEJ6hvbYR58pkILYM7eUg7plMZiPOMbTXdcSVM/cqGNrrGuJsmWgAYtHQXqACmaQJis+v9AuYC2KRr19DHTnbEagC3x/pAJ8KMMT3xy1McGkw+veRx18eMgQLRrl4fO9jih1N5sI0n+cnaLD0XOjy/FdCsA5mKPL8KgkWTI1TqT2ev6eC1Fxo8POhCUFarhV+/jilOZzDn803uFf/TmKwGj/fnqE+3+sn1Cshn5Iwu1d7vtd/qVdBHu+E6b3Ebq71q9wrfICs7oWCe4nTtblef69ebxNZbPUE9xoKd3C5p6HgXiMVC+msihDca6xdQprSJ8G9qLqj//u1OfD72xpqeUV99wfQU9f3tp2CBavgbO/rtv+E/rgX9+JeCnAv7sW9GPfiXtyLe3Ev7sW4F/fSuRf7AxCO2FvACCTjAAAAAElFTkSuQmCC',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);
	}

	/**
	 * Controls that change the way that product image looks.
	 */
	private function add_card_image_controls() {

		$this->add_control(
			new Control(
				'neve_force_same_image_height',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'    => esc_html__( 'Force Same Image Height', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_toggle_control',
					'priority' => 310,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_image_height',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 230,
				),
				array(
					'label'           => esc_html__( 'Image height (px)', 'neve' ),
					'section'         => 'woocommerce_product_catalog',
					'step'            => 1,
					'input_attrs'     => array(
						'min'        => 100,
						'max'        => 500,
						'defaultVal' => 230,
					),
					'priority'        => 320,
					'active_callback' => array( $this, 'same_image_height_active_callback' ),
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_image_hover',
				array(
					'default'           => 'none',
					'sanitize_callback' => array( $this, 'sanitize_image_hover' ),
				),
				array(
					'label'    => esc_html__( 'Image style', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 330,
					'type'     => 'select',
					'choices'  => array(
						'none'      => esc_html__( 'None', 'neve' ),
						'zoom'      => esc_html__( 'Zoom', 'neve' ),
						'swipe'     => esc_html__( 'Swipe Next Image', 'neve' ),
						'blur'      => esc_html__( 'Blur', 'neve' ),
						'fadein'    => esc_html__( 'Fade In', 'neve' ),
						'fadeout'   => esc_html__( 'Fade Out', 'neve' ),
						'glow'      => esc_html__( 'Glow', 'neve' ),
						'colorize'  => esc_html__( 'Colorize', 'neve' ),
						'grayscale' => esc_html__( 'Grayscale', 'neve' ),
					),
				)
			)
		);
	}

	/**
	 * Controls for card content.
	 */
	private function add_card_content_controls() {

		$order_default_components = array(
			'title',
			'reviews',
			'price',
		);

		$components = array(
			'category'          => __( 'Category', 'neve' ),
			'title'             => __( 'Title', 'neve' ),
			'short-description' => __( 'Short description', 'neve' ),
			'reviews'           => __( 'Reviews', 'neve' ),
			'price'             => __( 'Price', 'neve' ),
		);

		$this->add_control(
			new Control(
				'neve_layout_product_elements_order',
				array(
					'sanitize_callback' => array( $this, 'sanitize_product_elements_ordering' ),
					'default'           => json_encode( $order_default_components ),
				),
				array(
					'label'      => esc_html__( 'Elements Order', 'neve' ),
					'section'    => 'woocommerce_product_catalog',
					'type'       => 'ordering',
					'components' => $components,
					'priority'   => 410,
				),
				'Neve\Customizer\Controls\Ordering'
			)
		);

		$this->add_control(
			new Control(
				'neve_product_content_alignment',
				array(
					'default'           => 'left',
					'sanitize_callback' => array( $this, 'sanitize_product_content_alignment' ),
				),
				array(
					'label'    => esc_html__( 'Alignment', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 420,
					'choices'  => array(
						'left'   => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAEtElEQVR42u3cIXPjOhSG4f3/9KOGgoFmhmZlspvUabITsp5LjMyMzgWSHadNE7fJve1G74fSmbTgGUk+OpL7y8hn8gsCvPDCCy+8CF544YUXXngRvPDCCy+8yP/g9c/vbwteeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeP0Ur9dbsk/Ma9/U1U2pX1Py2j9XN6dJyOsOXFW1TcZrew+uqk7Ga3MXrwsD7MG87sN1YQV7SK96+9VqYpOk1/rL5egrXnjhhddP8drjtdxrv6mrqm72eC3y2sWd9/MeryVeU6Nig9cCr9leco/Xda/N5f3NS1Xv8Jp5rS96barqDFjKXi+X+g1h8L0DY/06v36Nc/UtWNLPx6nX+vJxm+wNWNJeYy9/c6mreAqWeH3fPFfVenu5CXsCxn77es96DobXghb/DAyvJSciRzC8Fh0gTWB47ep6e/28bQRL3mtXz8v7j48n6z1exxbY9vpp7itex45hALt4+I3XjKuqttfuCuA156qq7ZWrFXj9bj5zZQIvvPDCCy+8EvHarj+RHV7cz8ELL7zwwguv+fsK66+mTsyr5v2OT3nd6f2hXSpeu7twrdN5/7G5A1e9S+h95OY/5XrA9913m5sW/efL1/Ufz4v/p4AXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXkl6dX++LX+l1yMGL7zwwgsvvAheeOGFF1543T9OMfnh+pe9lOM1przdq1zyVx7FSx6vJV65mVmTSSu8FnuZlzSY937ocpVmvZck34evNSvJNee9vJNUHhLzaiT1JqmRVFo7TtLRQZIyKTeT1JpZL6k36920/MVvKaHxFVBU9mE585K6YHkIau+8nFSYHTKpSckrrl+SVA5mRZxYpVSYufgsyN97NZIzM3uSigSfj4p8WZyIraShD6NsXO9PvArpKdF6ojQzSc1xrsUP7TjHmvdeLvxCel6hvj9dm/D6eL0PiRan83E4Ox87Sb3lcT723vuEvWbrfW62iuv9avTyUwHi43rv01nvz3l17+oJP9UTK8n11jlJvQ2xngi/WP6IDfk3eNnhXL0axlcTfghewW18XPh06tU3Xmf3Q733jZkdVpL8MD4Twn6onareh/Z6xOCFF1544YUXwQsvvPDC6+b4HC+8bkyzmvUhfCa5cC+gfZo3KPCKyWP7qrXj2avrx1bW2ADDK+ZpOh7qzVbjZ3fsF8YmF15mFg55ijCYnsa2cyHpUEiu83nvfkRH/sd4jccVhVRMhxyZ5J3kzefmpQyvKWU8DiulwlxcrJzkV9GL5+NJDt63I5F57/s4RzsvqcgdXh8NMzfEz8NKKqYHQeYHvN6ky0MJEUbceGLUxYoia/F6u+ariKNoKOb3WL3zkrIer1lFsZLcWJO2mZR38/3Q4BbcA07Iq8uOg2sswCzcBxjM52Y5XrMM2awebXW8nJTFeqLP8Dq7HZI7boekMtYTrF+nyWZeneZ3DfPpM8/HY2ZErj3xsiY0K8q/r6FDvxCvh/T6S4MXXnjhhRdeBC+88MILL7wIXnjhhdcj51987R/KzzMKWAAAAABJRU5ErkJggg==',
						),
						'center' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAEtElEQVR42u3cLXPjPBSG4f3/9KGGgoFmhmZlspvUabITsp6XGJkZnRdIdpw2X91ku9noflA6kxZcI8lHR3J/GPlKfkCAF1544YUXwQsvvPDCCy+CF1544YUX+fNe//38a8ELL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILr4fxer8l28S8tk1d3ZT6PSWv7Wt1c5qEvO7AVVXrZLzW9+Cq6mS8VnfxOj3Ans3rPlynV7Dn9KrXv1tNrJL0Wv52PfqOF1544fUoXlu8rvfaruqqqpstXld5beLO+3WL1zVeU6NihdcVXrO95Bavy16r8/ubt6re4DXzWp71WlXVEbCUvd7O9RvC4PsExvp1fP0a5+pHsKSfj1Ov9e10m+wDWNJeYy9/da6reAiWeH3fvFbVcn2+CXsAxn77cs96DobXFS3+GRhe15yI7MHwuuoAaQLDa1PX68vnbSNY8l6bel7enz6erLd47Vtg68unue947TuGAezs4TdeM66qWl+6K4DXnKuq1heuVuD1s/nKlQm88MILL7zwSsRrvfxCNnhxPwcvvPDCCy+85u8rLH83dWJeNe93fMnrTu8PbVLx2tyFa5nO+4/NHbjqTULvIzd/kusZ33ffrG5a9F/PXtd/Qi/+nwJeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeKXo1f36a/knvZ4yeOGFF1544UXwwgsvvPDC61viFJPvLn/ZSzleY8rbvcpr/sqzeMnjdY1XbmbWZNICr6u9zEsazHs/dLlKs95Lku/D15qF5JrjXt5JKneJeTWSepPUSCqtHSfp6CBJmZSbSWrNrJfUm/VuWv7it5TQ+AooKvuwnHlJXbDcBbVPXk4qzHaZ1KTkFdcvSSoHsyJOrFIqzFx8FuSfvRrJmZm9SEWCz0dFvixOxFbS0IdRNq73B16F9JJoPVGamaRmP9fih3acY81nLxd+IT2vUN8frk14nV7vQ6LF4Xwcjs7HTlJveZyPvfc+Ya/Zep+bLeJ6vxi9/FSA+Lje+3TW+2Ne3ad6wk/1xEJyvXVOUm9DrCfCL5bfvyF/DC/bHatXw/hqwg/BK7iNjwufTr36wevofqj3vjGz3UKSH8ZnQtgPtVPV+9Re/3zwwgsvvPDCi+CFF1544fWd8TleeN2YZjHrT/hMcuG+QPsyb1zgFZPHtlZr+zNZ148trrExhlfMy3Rs1Jstxs9u30eMzS+8zCwc/hRhML2M7ehC0q6QXOfz3n1/p/6RvcZjjEIqpsOPTPJO8uZz81KG15QyHpOVUmEuLlZO8ovoxfPxIDvv25HIvPd9nKOdl1TkDq9Tw8wN8fOwkIrpQZD5Aa8P6fJQQoQRN54kdbGiyFq8Pq75KuIoGor5/VbvvKSsx2tWUSwkN9akbSbl3Xw/NLgr7gcn5NVl+8E1FmAW7gkM5nOzHK9ZhmxWj7baX1rKYj3RZ3gd3Q7J7bdDUhnrCdavw2Qzr07zO4j59Jnn4z4zItceeFkTmhXlgzR06BfilbrXIwUvvPDCCy+8CF544YUXXngRvPDCC69nzv/F6x/Kmshd1QAAAABJRU5ErkJggg==',
						),
						'right'  => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAAAAACfVToRAAAEs0lEQVR42u3cLXPrOhSF4fP/6aKGgoFmhmZlspvUaXIm5HguMTIz2hdIdpw2X23SaRu9C6UzacEzkry1JfePkY/kDwR44YUXXngRvPDCCy+88CJ44YUXXniRr/f67++3BS+88MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+88MILL7zwwgsvvPDCCy+8fozX6y3ZJua1berqptSvKXltn6ub0yTkdQeuqlon47W+B1dVJ+O1uovX6QH2aF734Tq9gj2mV73+bDWxStJr+el69BUvvPDC66d4bfG63mu7qquqbrZ4XeW1iTvv5y1e13hNjYoVXld4zfaSW7wue63O729eqnqD18xredZrVVVHwFL2ejnXbwiD7x0Y69fx9Wucq2/Bkn4+Tr3Wl9NtsjdgSXuNvfzVua7iIVji9X3zXFXL9fkm7AEY++3LPes5GF5XtPhnYHhdcyKyB8PrqgOkCQyvTV2vL5+3jWDJe23qeXl/+niy3uK1b4GtL5/mvuK17xgGsLOH33jNuKpqfemuAF5zrqpaX7hagdff5iNXJvDCCy+88MIrEa/18gPZ4MX9HLzwwgsvvPCav6+w/GzqxLxq3u/4kNed3h/apOK1uQvXMp33H5s7cNWbhN5Hbr6S6xHfd9+sblr0n89e139AL/6fAl544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF154pejV/fu2/EqvhwxeeOGFF154EbzwwgsvvPD6rjjF5LvLX/ZSjteY8nav8pq/8ihe8nhd45WbmTWZtMDrai/zkgbz3g9drtKs95Lk+/C1ZiG55riXd5LKXWJejaTeJDWSSmvHSTo6SFIm5WaSWjPrJfVmvZuWv/gtJTS+AorKPixnXlIXLHdB7Z2XkwqzXSY1KXnF9UuSysGsiBOrlAozF58F+XuvRnJmZk9SkeDzUZEvixOxlTT0YZSN6/2BVyE9JVpPlGYmqdnPtfihHedY897LhV9IzyvU94drE16n1/uQaHE4H4ej87GT1Fse52PvvU/Ya7be52aLuN4vRi8/FSA+rvc+nfX+mFf3rp7wUz2xkFxvnZPU2xDrifCL5RdvyH+sl+2O1athfDXhh+AV3MbHhU+nXn3jdXQ/1HvfmNluIckP4zMh7Ifaqep9aK/fFbzwwgsvvPAieOGFF154/dD4HC+8bkyzmPUtfCa5cI+gfZo3NPCKyWO7q7X9Wa3rx9bX2DDDK+ZpOk7qzRbjZ7fvL8amGF5mFg6FijCYnsY2dSFpV0iu83nvvrSD/+u8xuONQiqmQ5FM8k7y5nPzUobXlDIen5VSYS4uVk7yi+jF8/EgO+/bkci8932co52XVOQOr1PDzA3x87CQiulBkPkBrzfp8lBChBE3njB1saLIWrzervkq4igaivm9V++8pKzHa1ZRLCQ31qRtJuXdfD80uCvuDSfk1WX7wTUWYBbuDwzmc7Mcr1mGbFaPttpfZspiPdFneB3dDsntt0NSGesJ1q/DZDOvTvO7ifn0mefjPjMi1x54WROaFeVXNnToF+KF188JXnjhhRdeeBG88MILL7zwInjhhRdej5z/AdA+H8o+3vutAAAAAElFTkSuQmCC',
						),
						'inline' => array(
							'url' => 'data:image/png;base64,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',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);

		$this->add_control(
			new Control(
				'neve_advanced_reviews',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'    => esc_html__( 'Advanced reviews', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_toggle_control',
					'priority' => 430,
				)
			)
		);
	}

	/**
	 * Add sale tag customizer controls.
	 */
	private function add_sale_tag_controls() {

		$this->add_control(
			new Control(
				'neve_sale_tag_position',
				array(
					'default'           => 'inside',
					'sanitize_callback' => array( $this, 'sanitize_sale_tag_position' ),
				),
				array(
					'label'    => esc_html__( 'Position', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 510,
					'choices'  => array(
						'inside'  => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAMAAACN4JX/AAAAVFBMVEU/xPtTyPtkzPx00fyD1fyR2fye3f2q4f225f3B6f3M7P7S0tLT09PU1NTV1dXW1tbX19fX8P7Y2NjZ2dna2trb29vc3Nzd3d3i9P7s+P71+/////8xOQAQAAADLElEQVR4AezQMQ0AMAwDsLDYMf48i6Gq8tkQnL+BL1++fPnyhS9fvnz58oUvX758+cKXL1++fPnCly9fvnz5ov3lC1++fPnKwWvx5cuXL1++fPny5cuXL1++fPny5cuXL1++fPnyNezXsW7EIAyAYRPiM8eWjfh///dsiNOrVJW1k/8BmfUTIPEPXsUc3GJTwe/h5JTo5GlPr8n1eIwydw3Q9GLt1UDv1SaeA/2XV0xRevW4gM77Wl/QoKTX2stgWL2GqXTQC1h6rb3kYNb1GjdQ6Yw/368jvaLdHMBEDBdRqOm19IrMoXxoWt7HlVeBdoPBtvPk6bU8Xwe8ROrA5R0yCppeK6/qRPp91MTpP6/WZ5qsUf6HoFUx2GXWYftqx25zrIZhMAovIElTtfnY/05B/LhKecHcMEMYxefsoI/qSPb/8mLfxuv42/LVPHqFD5QbXlPFgtdcBa+pYsNrqozXXA2vqW68pjrxmiqzD73R+fI68HqjhhdeeOGFF1544YUXXu/Wf5l+UW8xvEp/NMEryeJnhNcdHlWbBK8klwUjvFp4Fk2QI8Tbt1cNP2VxpfC9C68xm0vBmEebS8FcefX49DosLgFz6HU+vW6DS8A8erU4QiSDS8BcevUyMMRqcAmYT69+xwkuAfPn1Vv+IRbPZnEJmDevsVpK7d3mEjDHXppyKRheE1wChpfJJWB42VwChpdyCZhbr5LSNcElYM68ilxXDS4Bc+AlXAOYxSVVH17CNYJNcIXiw0u4RjDhwku4RjDhwku4BjDhwku4BjCDC6+iAvn3XHjlGRu88MILL7zwwgsvvPBa7HXnD1a538+HF1544YUXXnjhZYdXbJ/vVTb2CvH49MKrvItXDEs6d/FKa7yuXbzONV51F6+2hOvoG3gt/MHqPl49rXi9NvJqaQHXBl6rRjKWvplXb2eK/wjruHvfwGthG3jhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOFFX9YLL7wIL7zwwgsvvAgvvPDCCy+8CC+88MILL8ILL7zwwgsvwgsvvPDCi74BKevIZRVAXf8AAAAASUVORK5CYII=',
						),
						'outside' => array(
							'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAS8AAADYCAMAAACN4JX/AAAAVFBMVEU/xPtTyPtkzPx00fyD1fyR2fye3f2q4f225f3B6f3M7P7S0tLT09PU1NTV1dXW1tbX19fX8P7Y2NjZ2dna2trb29vc3Nzd3d3i9P7s+P71+/////8xOQAQAAADR0lEQVR4AezXMWvDMBBA4XNsRY62THX0/v//bA8lKQQ0lIZ2eW8w5/XDZ7jAfpJeeumll156mV566fWW9NJLL71ML7300kuv+EV6PdJLL7300ksvvfTSSy+99NJLL7300ksvvT7+ML30eld66aXXUjv0Ol5W6JHduMXoxr1Nr+S6exxLfLUDRa+5VwqVfFITrwPtxWtMI73aWMDOJSLOsMOi19yrwlHXiEilK22BqtfUK42yViLiBCUax4vX6KrXaKsdoEZUekSBVa+p1yjJlifNPttHvZbUSTA4bdzres284grniPWgx2XIFCh6zbzWzqg8PrXotO+/1nNK1pH3EOxrVNgia3DS67/vbb300ksvvfTSSy+99NJLL7300ksvvfTSSy+9Ptu511y3QSiKwgPggWXzmP9MW/UPanfvaaISdHVYawif4sTaKOSFlbu79wprKx2vt4p1v9eCxhdd4eNVR15P+Hyxu/HqMWyouPG6w5a6F6+8x+vx4hX3eF1evMKeij+vNpalryrZnVce6+t44YUXXnjhhde5XrWkGNPVXkHBq2frtXyGl0w8Ca9/eqWXliu8/j64Nrxsr2QsCxJePfxeNEFyiM/ZXi38kcWVws9uwwsv4TLAeB6FS8AO85JFP1tcAnaal553PwaXgJ3mpSe4yeASsCO9Rg2z2AwuATvTazzxFS4BO9Zr9PJLLF7d5lKwY/fCVmuzfxltMPZVm0vB8LK5BAwvm0vA8LK5BAwv5RKwY71qSrfBZYAd6VV1XbW5FMy3l3BNMJNLakd4CdcEs7mkeo6XcE0w5cJLuCaYcuElXBNMuPASrglmcOFVVaB8zYVXeRlmvxdeeOGFF1544YUXXng95T9rq734fwdeeOGFF1544YUXXrGv96qOvULMy3N4Hwz3Db3nlfZ43V68rj1ezYtX38KVhxevPR+w5sdrpJ3fXg68etrF5cBrwyMZ63DmNfqV4oew8jOGK6/94YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjhhRdeeOGFF1544YUXXnjh5cCL8MILL7zwwovwwgsvvPDCi/DCCy+88CK88MILL7zwIrzwwgsvvPAivPD6Lv0ALgqZQ3JkuCMAAAAASUVORK5CYII=',
						),
					),
				),
				'Neve\Customizer\Controls\Radio_Image'
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_tag_alignment',
				array(
					'default'           => 'left',
					'sanitize_callback' => array( $this, 'sanitize_sale_tag_alignment' ),
				),
				array(
					'label'    => esc_html__( 'Alignment', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 520,
					'type'     => 'select',
					'choices'  => array(
						'left'  => esc_html__( 'Left', 'neve' ),
						'right' => esc_html__( 'Right', 'neve' ),
					),
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_tag_radius',
				array(
					'sanitize_callback' => 'absint',
					'default'           => 0,
				),
				array(
					'label'       => esc_html__( 'Border radius (%)', 'neve' ),
					'section'     => 'woocommerce_product_catalog',
					'type'        => 'neve_range_control',
					'step'        => 1,
					'input_attr'  => array(
						'min'     => 0,
						'max'     => 50,
						'default' => 0,
					),
					'input_attrs' => array(
						'min'        => 0,
						'max'        => 50,
						'defaultVal' => 0,
					),
					'priority'    => 530,
				),
				class_exists( 'Neve\Customizer\Controls\React\Range' ) ? 'Neve\Customizer\Controls\React\Range' : 'Neve\Customizer\Controls\Range'
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_tag_text',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'label'    => esc_html__( 'Text', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'priority' => 540,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_enable_sale_percentage',
				array(
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'    => esc_html__( 'Enable sale percentage', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_toggle_control',
					'priority' => 550,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_percentage_format',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => '{value}%',
				),
				array(
					'label'           => esc_html__( 'Sale tag format', 'neve' ),
					'description'     => esc_html__( 'How the discount should be displayed. e.g. {value}%', 'neve' ),
					'section'         => 'woocommerce_product_catalog',
					'type'            => 'text',
					'priority'        => 555,
					'active_callback' => array( $this, 'neve_sale_percentage_format_active_callback' ),
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_tag_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => '#2dce89',
				),
				array(
					'label'    => esc_html__( 'Background Color', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_color_control',
					'priority' => 560,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_sale_tag_text_color',
				array(
					'sanitize_callback' => 'neve_sanitize_colors',
					'default'           => '#ffffff',
				),
				array(
					'label'    => esc_html__( 'Text Color', 'neve' ),
					'section'  => 'woocommerce_product_catalog',
					'type'     => 'neve_color_control',
					'priority' => 570,
				)
			)
		);
	}

	/**
	 * Sanitize the shop layout value.
	 *
	 * @param string $value Value from the control.
	 *
	 * @return bool
	 */
	public function sanitize_shop_layout( $value ) {
		$allowed_values = array( 'list', 'grid' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'grid';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize the pagination type
	 *
	 * @param string $value value from the control.
	 *
	 * @return bool
	 */
	public function sanitize_pagination_type( $value ) {
		$allowed_values = array( 'number', 'infinite' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'number';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize add to cart position control.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_add_to_cart_display( $value ) {
		$allowed_values = array( 'none', 'after', 'on_image' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'number';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize content order control.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_product_elements_ordering( $value ) {
		$allowed = array(
			'category',
			'title',
			'short-description',
			'reviews',
			'price',
		);

		if ( empty( $value ) ) {
			return json_encode( $allowed );
		}

		$decoded = json_decode( $value, true );

		foreach ( $decoded as $val ) {
			if ( ! in_array( $val, $allowed, true ) ) {
				return json_encode( $allowed );
			}
		}

		return $value;
	}

	/**
	 * Sanitize button position.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_button_position( $value ) {
		$allowed_values = array( 'none', 'top', 'bottom' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'none';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize sale bubble position.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_sale_tag_position( $value ) {
		$allowed_values = array( 'inside', 'outside' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'inside';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize sale tag alignment.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_sale_tag_alignment( $value ) {
		$allowed_values = array( 'left', 'right' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'left';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize product content alignment.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_product_content_alignment( $value ) {
		$allowed_values = array( 'left', 'right', 'center', 'inline' );
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'left';
		}

		return esc_html( $value );
	}

	/**
	 * Sanitize image hover control.
	 *
	 * @param string $value value from the control.
	 *
	 * @return string
	 */
	public function sanitize_image_hover( $value ) {
		$allowed_values = array(
			'none',
			'swipe',
			'zoom',
			'blur',
			'fadein',
			'fadeout',
			'glow',
			'colorize',
			'grayscale',
		);
		if ( ! in_array( $value, $allowed_values, true ) ) {
			return 'none';
		}

		return esc_html( $value );
	}

	/**
	 * Decide if image height control should be visible based on Force Same Image Height control.
	 *
	 * @return bool
	 */
	public function same_image_height_active_callback() {
		return get_theme_mod( 'neve_force_same_image_height' );
	}

	/**
	 * Decide if products per row should be visible based on Layout control.
	 *
	 * @return bool
	 */
	public function products_per_row_active_callback() {
		$layout_toggle = get_theme_mod( 'neve_enable_product_layout_toggle', false );
		if ( $layout_toggle === true ) {
			return true;
		}

		return get_theme_mod( 'neve_product_card_layout', 'grid' ) === 'grid';
	}

	/**
	 * Add off canvas option for shop layout.
	 *
	 * @param array  $current_settings Current control settings.
	 * @param string $control_id Current control id.
	 *
	 * @return array
	 */
	public function add_off_canvas_option( $current_settings, $control_id ) {
		if ( $control_id !== 'neve_shop_archive_sidebar_layout' ) {
			return $current_settings;
		}
		$current_settings['off-canvas'] = array(
			'url' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABqCAMAAABpj1iyAAAACVBMVEX///8+yP/V1dXG9YqxAAAACXBIWXMAAAsTAAALEwEAmpwYAAAG0mlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoTWFjaW50b3NoKSIgeG1wOkNyZWF0ZURhdGU9IjIwMTktMTItMTlUMTA6NDQ6MTkrMDI6MDAiIHhtcDpNb2RpZnlEYXRlPSIyMDE5LTEyLTE5VDExOjE5OjI0KzAyOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE5LTEyLTE5VDExOjE5OjI0KzAyOjAwIiBkYzpmb3JtYXQ9ImltYWdlL3BuZyIgcGhvdG9zaG9wOkNvbG9yTW9kZT0iMiIgcGhvdG9zaG9wOklDQ1Byb2ZpbGU9InNSR0IgSUVDNjE5NjYtMi4xIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjliNzJmY2RjLWU4MjgtNDQyMC1iOTBmLTJmNWQ4ZGRmOTkxMiIgeG1wTU06RG9jdW1lbnRJRD0iYWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOmJmMGE1MTJlLTg1NzctMGY0My1iMzY3LTQ1ZDU2NTZiN2M3ZSIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjE1YWZmYmE0LWZjNjItNGU2Yi05ZGI3LTNmNzYxZGQ4MTE5NSI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MTVhZmZiYTQtZmM2Mi00ZTZiLTlkYjctM2Y3NjFkZDgxMTk1IiBzdEV2dDp3aGVuPSIyMDE5LTEyLTE5VDEwOjQ0OjE5KzAyOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoTWFjaW50b3NoKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6Mjk3NDU4ZDktY2M0YS00M2M2LWIxZmEtMjRkMTNmMTVlNTM1IiBzdEV2dDp3aGVuPSIyMDE5LTEyLTE5VDEwOjU0OjUzKzAyOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoTWFjaW50b3NoKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6OWI3MmZjZGMtZTgyOC00NDIwLWI5MGYtMmY1ZDhkZGY5OTEyIiBzdEV2dDp3aGVuPSIyMDE5LTEyLTE5VDExOjE5OjI0KzAyOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoTWFjaW50b3NoKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5LRiqyAAAA6klEQVRoge3UQQ7CMBBD0WHE/Y88sCBUjRoCRil48d8GhUbUZNxGAAAAAJi6vL6U3apODtK7fr41I6IiK7LaKmr/sTK4EKu/b0v3/LKirdfI91v+YXJahz+fozm18eXPhnio/PC+2xCXnvu6H6uVuZTKb/bP3epn8MH0vQXFZIjDS5P93xmXw/R1qp7W7awgPdPTMo1F5RVUXmEai8orqLzCNBaVV1B5hWksKq+g8grTWFReQeUVprGovILKK0xjUXkFlVeYxqLyCiqvMI1F5RVUXmEai8orqLzCNBaVV1B5hWksKg8AAHCuO0cqMnC+e7cbAAAAAElFTkSuQmCC',
		);

		return $current_settings;
	}

	/**
	 * Active callback for sale percentage format control
	 *
	 * @return bool
	 */
	public function neve_sale_percentage_format_active_callback() {
		return get_theme_mod( 'neve_enable_sale_percentage', false ) === true;
	}

}
