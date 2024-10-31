<?php


/**
 * Check if a PHP extension is loaded.
 *
 * @param string $extension_name The name of the PHP extension.
 * @return int Returns 1 if the extension is loaded, 0 otherwise.
 */
function kwsso_is_extension_installed( $extension_name ) {
	return extension_loaded( $extension_name ) ? 1 : 0;
}

/**
 * Generate the URL for the attribute mapping tab.
 *
 * This function appends the 'tab=attribute_role' query parameter to the current request URI.
 *
 * @return string The URL with the 'tab=attribute_role' parameter added.
 */
function kwsso_get_attribute_mapping_url() {
	return add_query_arg( array( 'tab' => 'attribute_role' ), $_SERVER['REQUEST_URI'] );
}


/**
 * Returns the URL for testing single sign-on (SSO) configuration.
 *
 * @param bool $new_cert Whether to include the 'newcert=true' parameter in the URL.
 * @return string The URL for testing SSO configuration.
 */
function kwsso_get_test_url( $new_cert = false ) {
	$url          = home_url() . '/?option=kw-test-idp-config';
	$new_cert_url = $new_cert ? $url . '&newcert=true' : $url;
	return $new_cert_url;
}
/**
 * Checks if the Service Provider (SP) is configured for single sign-on (SSO).
 *
 * @param bool $html_element Whether to return the HTML attribute for a disabled element.
 * @return int|string If $html_element is true, returns a disabled HTML attribute string.
 *                   Otherwise, returns 1 if SP is configured, 0 if not.
 */
function kwsso_check_if_sp_configured( $html_element = false ) {
		$kwsso_saml_login_url        = get_kwsso_option( KwConstants::LOGIN_URL );
		$kwsso_saml_x509_certificate = get_kwsso_option( KwConstants::X509_CERTIFICATE );
		$kwsso_saml_x509_certificate = is_array( $kwsso_saml_x509_certificate ) ? $kwsso_saml_x509_certificate : array( 0 => $kwsso_saml_x509_certificate );

	if ( ! empty( $kwsso_saml_login_url ) && ! empty( $kwsso_saml_x509_certificate ) ) {
		return $html_element ? '' : 1;
	}
	return $html_element ? 'disabled title="Disabled. Configure your Service Provider"' : 0;
}

/**
 * Helper function to exit script with an error message.
 *
 * @param string $message
 */
function kwsso_exit_with_error( $message ) {
	echo sprintf( '%s', esc_attr( $message ) );
	exit;
}

/**
 * Retrieves a value from the $_POST superglobal based on a given key.
 *
 * @param string $key The key to retrieve from $_POST.
 * @return mixed|null The value from $_POST if it exists, otherwise null.
 */
function get_value_from_post( $key ) {
	return isset( $_POST[ $key ] ) ? $_POST[ $key ] : '';// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verifing nonce in the fucntion where this helper fuction is called.
}
/**
 * Checks if the provided XML element's local name is 'EncryptedAssertion'.
 *
 * @param SimpleXMLElement $xml The XML element to check.
 * @return bool True if the local name is 'EncryptedAssertion', false otherwise.
 */
function check_if_localname_encrypted_assertion( $xml ) {
	return $xml->localName === 'EncryptedAssertion';
}
/**
 * Retrieves the Service Provider (SP) base URL.
 *
 * @return string The SP base URL.
 */
function get_sp_base_url() {
	$sp_base_url = get_kwsso_option( KwConstants::SP_BASE_URL );
	if ( empty( $sp_base_url ) ) {
		$sp_base_url = home_url();
	}
	return rtrim( $sp_base_url, '/' );
}
/**
 * Retrieves the Service Provider (SP) entity ID.
 *
 * @param string $sp_base_url The base URL of the SP.
 * @return string The SP entity ID.
 */
function get_sp_entity_id( $sp_base_url ) {
	$sp_entity_id = get_kwsso_option( KwConstants::SP_ENTITY_ID );
	if ( empty( $sp_entity_id ) ) {
		$sp_entity_id = $sp_base_url . '/wp-content/plugins/keywoot-saml-sso/';
	}
	return $sp_entity_id;
}
/**
 * Generates and outputs the Service Provider (SP) metadata XML.
 *
 * @param bool $download Whether to force download the metadata XML.
 * @param bool $new_cert Whether to include new certificate details in the metadata.
 */
function keywoot_sp_metadata_generator( $download = false, $new_cert = false ) {
	$sp_base_url  = get_sp_base_url();
	$sp_entity_id = get_sp_entity_id( $sp_base_url );
	if ( ob_get_contents() ) {
		ob_clean();
	}
	header( 'Content-Type: text/xml' );
	if ( $download ) {
		header( 'Content-Disposition: attachment; filename="kw-sso-sp-metadata.xml"' );
	}

	$sp_base_url = $sp_base_url . '/';

	$xml_skeleton = get_metadata_xml_skeleton();
	$xml_skeleton = str_replace( '{{VALID_UNTILL}}', '2029-10-22T10:07:10Z', $xml_skeleton );
	$xml_skeleton = str_replace( '{{SP_ENTITY_ID}}', $sp_entity_id, $xml_skeleton );
	$xml_skeleton = str_replace( '{{KWSSO_SP_BASE_URL}}', $sp_base_url, $xml_skeleton );
	echo $xml_skeleton; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
	exit;
}
/**
 * Returns admin post url.
 */
function admin_post_url() {
	return admin_url( 'admin-post.php' ); }

/**
 * Returns wp ajax url.
 */
function wp_ajax_url() {
	return admin_url( 'admin-ajax.php' ); }

/**
 * Used for transalating the string
 *
 * @param string $string - option name to be deleted.
 */
function kwsso_lang_( $string ) {

	$string = preg_replace( '/\s+/S', ' ', $string );
	return $string;
}

/**
 * Updates the option set in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $type - value of the option.
 */
function kwsso_esc_string( $string, $type ) {

	if ( 'attr' === $type ) {
		return esc_attr( $string );
	} elseif ( 'url' === $type ) {
		return esc_url( $string );
	}
	return esc_attr( $string );

}

/**
 * Deletes the option set in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $prefix - prefix of the option.
 */
function delete_kwsso_option( $string, $prefix = null ) {
	delete_site_option( $string );
}
/**
 * Retrieved the value of the option in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $prefix - prefix of the option.
 */
function get_kwsso_option( $string, $default = false, $prefix = null ) {
	return get_site_option( $string, $default );
}

/**
 * Updates the option set in the wp_option table.
 *
 * @param string $string - option name to be deleted.
 * @param string $value - value of the option.
 * @param string $prefix - prefix of the option.
 */
function update_kwsso_option( $string, $value, $prefix = null ) {
	update_site_option( $string, $value );
}
	/**
	 * Chceks if a user is logged in or not, additional checks for guest login.
	 *
	 * @return bool true if user is logged in, false if not logged in.
	 */
function kwsso_check_if_user_logged_in() {
	if ( is_user_logged_in() ) {
		return true;
	}
	return false;
}



