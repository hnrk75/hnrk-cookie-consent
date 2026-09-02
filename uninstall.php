<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package HNRK_Cookie_Consent
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'hnrk_cookie_settings' );
