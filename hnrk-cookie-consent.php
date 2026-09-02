<?php
/**
 * Plugin Name: HNRK Cookie Consent
 * Plugin URI:  https://github.com/hnrk75/hnrk-cookie-concent
 * Description: Cookiebanner med stöd för Google Consent Mode v2. Hanterar kategorierna Nödvändiga, Analys, Funktionella och Marknadsföring.
 * Version:     1.0.0
 * Author:      Henrik Pettersson
 * Author URI:  https://github.com/hnrk75
 * License:     GPL-2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hnrk-cookie-consent
 * Requires at least: 6.4
 * Requires PHP: 8.1
 *
 * @package HNRK_Cookie_Consent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HNRK_COOKIE_VERSION', get_file_data( __FILE__, array( 'Version' => 'Version' ) )['Version'] );

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/settings.php';

add_action(
	'init',
	function () {
		$update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
			'https://github.com/hnrk75/hnrk-cookie-concent',
			__FILE__,
			'hnrk-cookie-consent'
		);
		$update_checker->setBranch( 'main' );
	}
);

/**
 * Load plugin text domain for translations.
 */
function hnrk_cookie_load_textdomain() {
	load_plugin_textdomain(
		'hnrk-cookie-consent',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'hnrk_cookie_load_textdomain' );

/**
 * Enqueue banner stylesheet and consent script.
 */
function hnrk_cookie_enqueue() {
	wp_enqueue_style(
		'hnrk-cookie-consent',
		plugin_dir_url( __FILE__ ) . 'assets/css/cookie-banner.css',
		array(),
		HNRK_COOKIE_VERSION
	);

	wp_enqueue_script(
		'hnrk-cookie-consent',
		plugin_dir_url( __FILE__ ) . 'assets/js/consent.js',
		array(),
		HNRK_COOKIE_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	$settings = hnrk_cookie_get_settings();
	wp_localize_script(
		'hnrk-cookie-consent',
		'hnrkCookieSettings',
		array(
			'cookieDays'       => (int) $settings['cookie_days'],
			'enableAnalytics'  => (bool) $settings['enable_analytics'],
			'enableFunctional' => (bool) $settings['enable_functional'],
			'enableMarketing'  => (bool) $settings['enable_marketing'],
			'enableThirdparty' => (bool) $settings['enable_thirdparty'],
		)
	);
}
add_action( 'wp_enqueue_scripts', 'hnrk_cookie_enqueue' );

/**
 * Render the cookie banner in the footer.
 */
function hnrk_cookie_banner() {
	require plugin_dir_path( __FILE__ ) . 'templates/cookie-banner.php';
}
add_action( 'wp_footer', 'hnrk_cookie_banner', 100 );

/**
 * Enqueue admin stylesheet for the plugin settings page.
 *
 * @param string $hook Current admin page hook suffix.
 * @return void
 */
function hnrk_cookie_admin_assets( $hook ) {
	if ( 'toplevel_page_hnrk-cookie-consent' !== $hook ) {
		return;
	}

	wp_enqueue_style(
		'hnrk-cookie-admin',
		plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
		array(),
		HNRK_COOKIE_VERSION
	);

	wp_enqueue_script(
		'hnrk-cookie-admin',
		plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
		array(),
		HNRK_COOKIE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'hnrk_cookie_admin_assets' );

/**
 * Shortcode [hnrk_manage_cookies text="Hantera cookies"].
 * Outputs a link that reopens the consent banner.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function hnrk_cookie_manage_shortcode( $atts ) {
	$atts = shortcode_atts(
		array( 'text' => __( 'Hantera cookies', 'hnrk-cookie-consent' ) ),
		$atts,
		'hnrk_manage_cookies'
	);
	return '<a href="#manage-cookies" class="hnrk-manage-cookies-link">' . esc_html( $atts['text'] ) . '</a>';
}
add_shortcode( 'hnrk_manage_cookies', 'hnrk_cookie_manage_shortcode' );

/**
 * Render the floating reopen button if enabled in Settings.
 */
function hnrk_cookie_floating_btn() {
	$settings = hnrk_cookie_get_settings();
	if ( 'floating' !== $settings['manage_cookies_trigger'] ) {
		return;
	}
	$label    = ! empty( $settings['manage_cookies_label'] )
		? $settings['manage_cookies_label']
		: __( 'Hantera cookies', 'hnrk-cookie-consent' );
	$position = isset( $settings['manage_cookies_position'] ) && 'left' === $settings['manage_cookies_position']
		? 'hnrk-cookie-reopen hnrk-cookie-reopen--left'
		: 'hnrk-cookie-reopen';
	?>
	<button
		type="button"
		class="<?php echo esc_attr( $position ); ?>"
		data-consent-reopen
		aria-label="<?php echo esc_attr( $label ); ?>"
	>
		<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
			<path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5M8.5 8.5v.01M16 15.5v.01M12 12v.01M11 17v.01M7 14v.01"/>
		</svg>
		<span class="hnrk-cookie-reopen__label"><?php echo esc_html( $label ); ?></span>
	</button>
	<?php
}
add_action( 'wp_footer', 'hnrk_cookie_floating_btn', 99 );
