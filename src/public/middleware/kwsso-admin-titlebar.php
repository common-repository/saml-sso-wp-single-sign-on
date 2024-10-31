<?php
/**
 * Titlebar controller.
 *
 * @package keywoot-saml-sso/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




$kwsso_request_uri         = remove_query_arg( array( 'addon', 'form', 'subpage' ), isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ); // phpcs:ignore -- false positive.
$kwstatus       = kwsso_check_if_sp_configured() ? 'activebtn' : 'notactivebtn';
$statustext     = ( 'activebtn' === $kwstatus ) ? 'Active' : 'Not Active';
$statusicon     = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M8 12L10.5347 14.2812C10.9662 14.6696 11.6366 14.6101 11.993 14.1519L16 9M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#28303F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';

