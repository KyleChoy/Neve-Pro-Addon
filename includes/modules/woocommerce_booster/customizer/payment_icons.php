<?php
/**
 * Payment icons WooCommerce Booster Module
 *
 * @package WooCommerce Booster
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;
use Neve\Customizer\Types\Partial;
use Neve\Customizer\Types\Section;

/**
 * Class Payment_Icons
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Customizer
 */
class Payment_Icons extends Base_Customizer {

	/**
	 * All payment options.
	 *
	 * @var array
	 */
	private $payment_options;

	/**
	 * Default payment options.
	 *
	 * @var array
	 */
	public static $order_default_payment_options = array(
		'visa',
		'mastercard',
		'paypal',
		'stripe',
	);

	/**
	 * Payment_Icons constructor.
	 */
	public function __construct() {
		$this->payment_options = apply_filters(
			'neve_payment_options',
			array(
				'visa'             => __( 'Visa', 'neve' ),
				'visa-electron'    => __( 'Visa Electron', 'neve' ),
				'paypal'           => __( 'PayPal', 'neve' ),
				'stripe'           => __( 'Stripe', 'neve' ),
				'mastercard'       => __( 'Mastercard', 'neve' ),
				'cash-on-delivery' => __( 'Cash on Delivery', 'neve' ),
				'amazon'           => __( 'Amazon', 'neve' ),
				'american-express' => __( 'American Express', 'neve' ),
				'apple-pay'        => __( 'Apple Pay', 'neve' ),
				'bank-transfer'    => __( 'Bank Transfer', 'neve' ),
				'google-pay'       => __( 'Google Pay', 'neve' ),
				'google-wallet'    => __( 'Google Wallet', 'neve' ),
				'maestro'          => __( 'Maestro', 'neve' ),
				'pay-u'            => __( 'Pay U', 'neve' ),
				'western-union'    => __( 'Western Union', 'neve' ),
			)
		);
	}

	/**
	 * Base initialization.
	 */
	public function init() {
		parent::init();
		add_action( 'customize_controls_print_styles', array( $this, 'hide_payment_icons_section' ), 999 );
	}

	/**
	 * Add customizer controls
	 */
	public function add_controls() {
		$this->add_payment_icons_section();
		$this->add_payment_icons_controls();
		$this->partial_refresh();
	}

	/**
	 * Hide the payment icons section
	 */
	public function hide_payment_icons_section() {
		echo '<style>';
		echo '#accordion-section-neve_payment_icons { display: none!important }';
		echo '</style>';
	}

	/**
	 * Add payment icons section
	 */
	public function add_payment_icons_section() {
		$this->add_section(
			new Section(
				'neve_payment_icons',
				array(
					'priority' => 70,
					'title'    => esc_html__( 'Payment Icons', 'neve' ),
					'panel'    => 'woocommerce',
				)
			)
		);
	}

	/**
	 * Add Payment Controls
	 */
	private function add_payment_icons_controls() {

		$this->add_control(
			new Control(
				'neve_enable_payment_icons',
				array(
					'default'           => false,
					'sanitize_callback' => 'neve_sanitize_checkbox',
				),
				array(
					'label'       => esc_html__( 'Enable Payment Icons', 'neve' ),
					'description' => sprintf(
						/* translators: %s is link to section */
						esc_html__( 'Click %s to edit payment icons', 'neve' ),
						sprintf(
							/* translators: %s is link label */
							'<span class="quick-links"><a href="#" data-control-focus="neve_payment_icons">%s</a></span>',
							esc_html__( 'here', 'neve' )
						)
					),
					'section'     => 'neve_cart_page_layout',
					'type'        => 'neve_toggle_control',
					'priority'    => 40,
				)
			)
		);

		$this->add_control(
			new Control(
				'neve_payment_icons',
				array(
					'default'           => json_encode( self::$order_default_payment_options ),
					'sanitize_callback' => array( $this, 'sanitize_payment_icons_ordering' ),
					'transport'         => 'postMessage',
				),
				array(
					'label'      => esc_html__( 'Payment Icons Order', 'neve' ),
					'section'    => 'neve_payment_icons',
					'type'       => 'ordering',
					'priority'   => 10,
					'components' => $this->payment_options,
				),
				'\Neve\Customizer\Controls\Ordering'
			)
		);
	}

	/**
	 * Partial refresh
	 */
	private function partial_refresh() {
		$this->add_partial(
			new Partial(
				'neve_payment_icons',
				array(
					'selector'            => '.nv-payment-icons-wrapper',
					'settings'            => array(
						'neve_payment_icons',
					),
					'render_callback'     => '\Neve_Pro\Modules\Woocommerce_Booster\Views\Payment_Icons::render_payment_icons',
					'container_inclusive' => true,
				)
			)
		);
	}

	/**
	 * Sanitize Payment Icons control
	 *
	 * @param string $value Control value.
	 * @return string.
	 */
	public function sanitize_payment_icons_ordering( $value ) {

		if ( empty( $value ) ) {
			return json_encode( self::$order_default_payment_options );
		}

		$decoded = json_decode( $value, true );

		foreach ( $decoded as $val ) {
			if ( ! array_key_exists( $val, $this->payment_options ) ) {
				return json_encode( self::$order_default_payment_options );
			}
		}

		return $value;
	}

	/**
	 * Active callback for displaying the shortcut to payment icons control.
	 *
	 * @return bool
	 */
	public function show_edit_payment_icons_shortcut() {
		return get_theme_mod( 'neve_enable_payment_icons', false );
	}
}
