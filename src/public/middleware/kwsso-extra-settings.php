<?php

/**
 * Loads contact us form.
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$disabled    = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwmapping.php' ) ? 'disabled' : '';
$show_notice = ! file_exists( KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwmapping.php' ) ? true : '';
$idp_attrs   = maybe_unserialize( get_kwsso_option( 'kwsso_test_config_attrs' ) );

require_once KWSSO_PLUGIN_DIR . '/src/public/layout/kwsso-extra-setting.php';



