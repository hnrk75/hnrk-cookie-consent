<?php
/**
 * Plugin Name: HNRK Cookie Consent
 * Plugin URI:  https://github.com/hnrk75/hnrk-cookie-consent
 * Description: Cookie consent banner with support for Google Consent Mode v2. Manages the categories Necessary, Analytics, Functional and Marketing.
 * Version:     1.3.0
 * Author:      Henrik Pettersson
 * Author URI:  https://github.com/hnrk75
 * License:     GPL-2.0-or-later
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

require_once plugin_dir_path( __FILE__ ) . 'inc/settings.php';

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
			'consentVersion'   => hnrk_cookie_consent_version( $settings ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'hnrk_cookie_enqueue' );

/**
 * Changes when a category is turned on or off, so visitors are asked again.
 *
 * @param array $settings Plugin settings.
 * @return string
 */
function hnrk_cookie_consent_version( $settings ) {
	$categories = array();

	foreach ( array( 'analytics', 'functional', 'marketing', 'thirdparty' ) as $category ) {
		if ( ! empty( $settings[ 'enable_' . $category ] ) ) {
			$categories[] = $category;
		}
	}

	return substr( md5( implode( ',', $categories ) ), 0, 8 );
}

/**
 * Google Consent Mode v2 default (all denied), before any Google tag loads.
 */
function hnrk_cookie_consent_mode_default() {
	if ( ! apply_filters( 'hnrk_cookie_consent_mode', true ) ) {
		return;
	}

	$script = <<<'JS'
window.dataLayer = window.dataLayer || [];
window.gtag = window.gtag || function () { window.dataLayer.push(arguments); };
gtag('consent', 'default', {
	ad_storage: 'denied',
	ad_user_data: 'denied',
	ad_personalization: 'denied',
	analytics_storage: 'denied',
	functionality_storage: 'denied',
	personalization_storage: 'denied',
	security_storage: 'granted',
	wait_for_update: 500
});
(function () {
	var match = document.cookie.match(/(?:^|; )hnrk-consent=([^;]*)/);
	if (!match) return;
	try {
		var c = JSON.parse(decodeURIComponent(match[1]));
		gtag('consent', 'update', {
			analytics_storage: c.analytics ? 'granted' : 'denied',
			ad_storage: c.marketing ? 'granted' : 'denied',
			ad_user_data: c.marketing ? 'granted' : 'denied',
			ad_personalization: c.marketing ? 'granted' : 'denied',
			functionality_storage: c.functional ? 'granted' : 'denied',
			personalization_storage: c.functional ? 'granted' : 'denied'
		});
	} catch (e) {}
})();
JS;

	wp_print_inline_script_tag( $script, array( 'id' => 'hnrk-consent-mode' ) );
}
add_action( 'wp_head', 'hnrk_cookie_consent_mode_default', 1 );

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

	wp_localize_script(
		'hnrk-cookie-admin',
		'hnrkCookieAdmin',
		array(
			'close' => __( 'Close', 'hnrk-cookie-consent' ),
			/* translators: %s: number of days */
			'days'  => __( '%s days', 'hnrk-cookie-consent' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'hnrk_cookie_admin_assets' );

/**
 * Whether the theme prints the "Manage cookies" link in its footer.
 *
 * @return bool
 */
function hnrk_cookie_footer_supported() {
	return current_theme_supports( 'hnrk-cookie-consent-footer' );
}

/**
 * How visitors reopen the banner: 'floating' or 'footer'. Falls back to the floating
 * button when the theme does not support the footer link, so there is always a way.
 *
 * @return string
 */
function hnrk_cookie_manage_trigger() {
	$trigger = hnrk_cookie_get_settings()['manage_cookies_trigger'];

	return ( 'footer' === $trigger && hnrk_cookie_footer_supported() ) ? 'footer' : 'floating';
}

/**
 * A link that reopens the banner, for themes and the shortcode.
 *
 * @param array $args { text, class }.
 * @return string
 */
function hnrk_cookie_manage_link( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'  => hnrk_cookie_get_settings()['manage_cookies_label'],
			'class' => 'hnrk-manage-cookies-link',
		)
	);

	return '<a href="#manage-cookies" class="' . esc_attr( $args['class'] ) . '" data-consent-reopen role="button">' . esc_html( $args['text'] ) . '</a>';
}

/**
 * Shortcode [hnrk_manage_cookies text="Cookie settings"]: a link that reopens the banner.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function hnrk_cookie_manage_shortcode( $atts ) {
	$atts = shortcode_atts(
		array( 'text' => hnrk_cookie_get_settings()['manage_cookies_label'] ),
		$atts,
		'hnrk_manage_cookies'
	);

	return hnrk_cookie_manage_link( array( 'text' => $atts['text'] ) );
}
add_shortcode( 'hnrk_manage_cookies', 'hnrk_cookie_manage_shortcode' );

/**
 * Render the floating reopen button.
 */
function hnrk_cookie_floating_btn() {
	if ( 'floating' !== hnrk_cookie_manage_trigger() ) {
		return;
	}

	$settings = hnrk_cookie_get_settings();
	$label    = $settings['manage_cookies_label'];
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
