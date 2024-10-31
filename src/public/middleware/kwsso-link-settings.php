<?php
/**
 * Loads general settings tab.
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-general-settings-view.php';
require_once KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-button-settings-page.php';



$disabled                    = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwgeneralsettings.php' ) ? 'disabled' : '';
$is_prem                     = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwgeneralsettings.php' ) ? true : '';


$sp_base_url = get_kwsso_option( KwConstants::SP_BASE_URL );
if ( empty( $sp_base_url ) ) {
	$sp_base_url = home_url();
}
kwsso_link_and_widget_view();