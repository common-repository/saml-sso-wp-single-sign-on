<?php
/**
 * Deletes options saved in the plugin.
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$option_array = array(
	'kwsso_enabled_sso',
);
foreach ( $option_array as $option ) {
	delete_site_option( $option );
}
