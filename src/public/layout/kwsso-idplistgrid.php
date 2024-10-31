<?php
/**
 * Load admin view for header of forms.
 *
 * @package keywoot-saml-sso/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use KWSSO_CORE\Src\Utility\KWSSO_PluginTabs;

$kwsso_saml_identity_name = get_kwsso_option( KwConstants::KWSSO_IDP_NAME );
$kwsso_request_uri        = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
echo '  <div style="display:' . esc_attr( $on_select_idp_page ) . '" id="kwsso_idp_selection_form">

		</div>';



