<?php
/**
 * Load view for subtabs
 *
 * @package keywoot-saml-sso/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}




$kwsso_request_uri    = remove_query_arg( array( 'addon', 'form', 'subpage' ), isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ); // phpcs:ignore -- false positive.
$active_tab     = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.

require KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-subtabs.php';
