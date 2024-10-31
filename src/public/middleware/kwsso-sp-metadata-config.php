<?php
/**
 * Load view for premium SMS Notifications List
 *
 * @package keywoot-saml-sso/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$sp_base_url        = get_kwsso_option( KwConstants::SP_BASE_URL ) ? get_kwsso_option( KwConstants::SP_BASE_URL ) : home_url();
$sp_entity_id       = get_kwsso_option( KwConstants::SP_ENTITY_ID ) ? get_kwsso_option( KwConstants::SP_ENTITY_ID ) : $sp_base_url . '/wp-content/plugins/keywoot-saml-sso/';
$kwsso_saml_nameid_format = get_kwsso_option( KwConstants::NAMEID_FORMAT ) ? get_kwsso_option( KwConstants::NAMEID_FORMAT ) : '1.1:nameid-format:emailAddress';
$kwsso_request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // phpcs:ignore -- false positive.

$metadata_url           = $sp_base_url . '/?option=kw-metadata';

require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-sp-metadata-config.php';
