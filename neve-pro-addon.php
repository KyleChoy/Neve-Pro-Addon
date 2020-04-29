<?php
/**
 * Plugin Name:       Neve Pro Addon
 * Description:       This plugin is an add-on to Neve WordPress theme which offers exclusive premium features, specially designed for Neve, to enhance your overall WordPress experience.
 * Version:           1.1.10
 * Author:            ThemeIsle
 * Author URI:        https://themeisle.com
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       neve
 * Domain Path:       /languages
 * WordPress Available:  no
 * Requires License:    yes
 *
 * @package Neve Pro Addon
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$theme = get_template();

if ( $theme !== 'neve' ) {
	add_action( 'admin_notices', 'neve_pro_display_wrong_theme_notice' );
	add_action( 'admin_init', 'neve_pro_disable_wrong_theme_notice' );

	return;
}

/**
 * Notice displayed if the theme is not Neve or a child theme of Neve.
 *
 * @since 0.0.1
 */
function neve_pro_display_wrong_theme_notice() {

	global $current_user;
	$user_id        = $current_user->ID;
	$ignored_notice = get_user_meta( $user_id, 'neve_pro_nag_ignore_theme_notice' );
	if ( ! empty( $ignored_notice ) ) {
		return;
	}

	$dismiss_button = sprintf(
		'<a href="%s" class="notice-dismiss" style="text-decoration:none;"></a>',
		'?neve_pro_nag_ignore_theme_notice=ignore'
	);

	$strings = array(
		'errOccured'      => __( 'An error occurred. Please refresh the page and try again.', 'neve' ),
		'activating'      => __( 'Activating...', 'neve' ),
		'activate'        => __( 'Activate Neve', 'neve' ),
		'installActivate' => __( 'Install and Activate Neve' ),
	);

	$themes      = wp_get_themes();
	$button_text = $strings['installActivate'];
	$action      = 'install';
	$url         = esc_url( admin_url( 'update.php?action=install-theme&theme=neve&_wpnonce=' . wp_create_nonce( 'install-theme_neve' ) ) );
	if ( isset( $themes['neve'] ) ) {
		$url         = esc_url( admin_url( 'themes.php?action=activate&amp;template=neve&amp;stylesheet=neve&_wpnonce=' . wp_create_nonce( 'switch-theme_neve' ) ) );
		$button_text = $strings['activate'];
		$action      = 'activate';
	}

	/* translators: %1$s - plugin name, %2$s - theme name, %3$s - call to action */
	$message = sprintf( __( '%1$s requires the %2$s theme to be activated to work. %3$s', 'neve' ), sprintf( '<strong>%s</strong>', 'Neve Pro' ), sprintf( '<strong>%s</strong>', 'Neve' ), sprintf( '<br/><a class="install-activate-neve theme-install button button-primary" data-action="%4$s" data-name="Neve" data-slug="neve" href="%1$s" aria-label="%3$s">%2$s</a>', $url, $button_text, $button_text, $action ) );

	printf(
		'<div class="notice notice-error install-neve" style="position:relative;">%1$s<p>%2$s</p></div>',
		$dismiss_button,
		$message
	);
	?>
	<style>
		.install-neve .error-message {
			background-color: #F56E28;
			color: #fff;
		}

		.install-neve .install-activate-neve {
			margin-top: 10px;
		}
	</style>
	<script type="application/javascript">
			( function($) {
				let button = $( '.install-activate-neve' );

				$( button ).on( 'click', function(e) {
					if ( $( this ).data( 'action' ) === 'activate' ) {
						$( button ).html( '<?php echo esc_html( $strings['activating'] ); ?>' );
						$( button ).addClass( 'updating-message' );
						return;
					}
					e.preventDefault();
					wp.updates.installTheme( {
						slug: 'neve',
						success: function(response) {
							if ( response.activateUrl ) {
								$( button ).html( '<?php echo esc_html( $strings['activating'] ); ?>' );
								location.href = response.activateUrl;
							}
						},
						error: function(error) {
							var message;
							if ( error.errorMessage ) {
								message = error.errorMessage;
							} else {
								message = '<?php echo esc_html( $strings['errOccured'] ); ?>';
							}
							$( button ).replaceWith( '<code class="error-message">Error: ' + message + '</code>' );
						}
					} );
				} );
			} )( jQuery );
	</script>
	<?php
}

/**
 * Disable the notice that appears if the theme is not Neve or a child theme of Neve.
 *
 * @since 0.0.1
 */
function neve_pro_disable_wrong_theme_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset( $_GET['neve_pro_nag_ignore_theme_notice'] ) && 'ignore' === $_GET['neve_pro_nag_ignore_theme_notice'] ) {
		add_user_meta( $user_id, 'neve_pro_nag_ignore_theme_notice', 'true', true );
	}
}

define( 'NEVE_PRO_NAME', 'Neve Pro Addon' );
define( 'NEVE_PRO_REST_NAMESPACE', 'neve_pro/v1' );
define( 'NEVE_PRO_VERSION', '1.1.10' );
define( 'NEVE_PRO_NAMESPACE', 'neve_pro' );

define( 'NEVE_PRO_URL', plugin_dir_url( __FILE__ ) );
define( 'NEVE_PRO_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
define( 'NEVE_PRO_INCLUDES_URL', plugin_dir_url( __FILE__ ) . 'includes/' );

define( 'NEVE_PRO_PATH', plugin_dir_path( __FILE__ ) );
define( 'NEVE_PRO_SPL_ROOT', plugin_dir_path( __FILE__ ) . 'includes/' );
define( 'NEVE_PRO_BASEFILE', __FILE__ );

/**
 * Load the localisation file.
 *
 * @access  public
 * @since   0.0.1
 */
function neve_pro_load_textdomain() {
	load_plugin_textdomain( 'neve', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'init', 'neve_pro_load_textdomain' );

add_filter( 'themeisle_sdk_products', 'neve_pro_load_sdk' );
add_filter( 'themesle_sdk_namespace_' . md5( __FILE__ ), 'neve_pro_load_namespace' );

/**
 * Filter products array.
 *
 * @param array $products products array.
 *
 * @return array
 */
function neve_pro_load_sdk( $products ) {
	$products[] = __FILE__;

	return $products;
}

/**
 * Define cli namespace for sdk.
 *
 * @return string CLI namespace.
 */
function neve_pro_load_namespace() {
	return 'neve';
}
/**
 * Actions that are running on plugin deactivate.
 */
function run_uninstall_actions() {
	/**
	 * Disable white label and make sure that the module is visible again in dashboard.
	 */
	$white_label_settings                = get_option( 'ti_white_label_inputs' );
	$white_label_settings                = json_decode( $white_label_settings, true );
	$white_label_settings['white_label'] = false;
	update_option( 'ti_white_label_inputs', json_encode( $white_label_settings ) );
}

register_deactivation_hook( __FILE__, 'run_uninstall_actions' );

/**
 * Require package autoload
 */
function neve_pro_run() {
	$vendor_file = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';
	if ( is_readable( $vendor_file ) ) {
		require_once $vendor_file;
	}
}

neve_pro_run();
