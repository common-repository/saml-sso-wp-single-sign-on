<?php
/**
 * Defines the Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound -- Disabling this to define multiple constant classes in the same file.

require_once KWSSO_CONST_BASIC;

/**
 * Defines constants of the plugin files path required in the plugin.
 */
class KeywootMessage extends KwBaseConstant {
	const INVALID_OPERATION                      = 'Invalid operation. Please try again.';
	const MISSING_IDPSSODESCRIPTOR               = 'Required <IDPSSODescriptor> missing in <EntityDescriptor>.';
	const MORE_IDPSSODESCRIPTOR                  = 'Multiple <IDPSSODescriptor> elements found in <EntityDescriptor>.';
	const ALREADY_REGISTERED_AUTHNCONTEXTDECLREF = 'AuthnContextDeclRef is already registered! Only a Decl or a DeclRef is allowed, not both.';
	const IDP_CONF_SAVED                         = 'Identity provider details fetched and saved successfully.';
	const CURL_DISABLED                          = 'PHP cURL extension is not installed or enabled. Please enable it by editing php.ini (usually located in /etc/ or in the PHP folder on the server). Look for extension=php_curl.dll, remove the semicolon (;) in front of it, and restart the Apache server.';
	const PROVIDE_METADATA_FILE                  = 'Please provide a valid metadata file.';
	const INVALID_FILE_OR_URL                    = 'Please provide a valid metadata file or URL.';
	const MISSING_ISSUER                         = 'Missing <saml:Issuer> in assertion.';
	const MISSING_ID_ATTR                        = 'Missing ID attribute in SAML assertion.';
	const AUTHNCONTEXTDECL_REGISTERED            = 'AuthnContextDecl is already registered! Only a Decl or a DeclRef is allowed, not both.';
	const DECRYPT_NAMEID_BEFORE_RETRIEVE         = 'Attempted to retrieve encrypted NameID without decrypting it first.';
	const ERR_FORM_SUB                           = 'Error submitting the form. Please try again.';
	const QUERY_SUBMITTED                        = 'Thank you for submitting your query. We will reach out to you as soon as possible.';
	const INVALID_CERTIFICATE                    = 'Invalid certificate. Please provide a valid certificate.';
	const IDP_SAVED                              = 'Identity provider details saved successfully.';
	const ATTR_LIST_CLR                          = 'Attribute list cleared successfully.';
	const SSO_BUTTON_ADDED                       = 'SSO button added to the WordPress login form.';
	const SHRTCD_AS_BUTTON_ADDED                 = '[KWSSO_SAML_SSO] shortcode can now be used as an SSO button anywhere.';
	const WID_AS_BUTTON_ADDED                    = 'Widget can now be used as an SSO button anywhere.';
	const SETTINGS_UPDATED                       = 'Settings updated successfully.';
	const IDP_ENABLED                            = 'Configuration has been saved.';
	const IDP_REMOVED                            = 'Identity provider configuration has been removed.';

	const INVALID_IDP_NAME         = 'Please enter a valid Identity Provider name that matches the required format. The name should only contain letters, numbers, and underscores.';
	const REQUIRED_FIELDS          = 'All fields are required. Please enter valid entries.';
	const INVALID_TIMESTAMP        = 'Invalid SAML2 timestamp passed to xsDateTimeToTimestamp:';
	const FAILED_DIGEST_VALIDATION = 'XMLSec: Digest validation failed.';
	const ROOT_ELE_UNSINGED        = 'XMLSec: The root element is not signed.';
	const EXCEED_SIG_ELE           = 'XMLSec: More than one signature element in the root.';
	const DUPLICATE_RESPONSE       = 'Duplicate SAML response.';
	const NO_INTERNET              = 'Unable to connect to the internet. Please try again.';
	const NO_KEY_DETAIL            = 'Unable to retrieve key details from XMLSecurityKey.';
	const MISSING_KEY_DETAIL       = 'Missing key in public key details.';
	const CONTACT_US_MAPPING       = 'Hello Keywoot Team, I would like to use the Attribute and Role Mapping feature. My query is:';
	const NEED_HELP_MESSAGE        = 'Hello Keywoot Team, I need assistance regarding the plugin.';
}

