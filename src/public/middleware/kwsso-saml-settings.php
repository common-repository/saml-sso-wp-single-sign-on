<?php
/**
 * Load admin view for settings tab.
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



$subtab = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : 'attr-mapping'; //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.

$attr_mapping_hidden     = 'attr-mapping' !== $subtab ? 'hidden' : '';
$role_mapping_hidden     = 'role-mapping' !== $subtab ? 'hidden' : '';
$disabled                = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-mapping.php' ) ? 'disabled' : '';
$show_notice             = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwmapping.php' ) ? true : '';

require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-attr-role-mapping.php';


