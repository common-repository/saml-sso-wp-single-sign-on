<?php
/**
 * Defines the Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const KWSSO_METADATA_URL            = 'metadata_url';
const KWSSO_IDP_METADATA_FILE       = 'idp_metadata_file';
const KWSSO_SAML_IDP_NAME           = 'saml_identity_provider_name';
const KWSSO_SAML_IDP_KEY            = 'kwsso_saml_idp_key';
const KWSSO_MANAGE_OPTIONS          = 'manage_options';
const KWSSO_TMP_NAME                = 'tmp_name';
const KWSSO_RELAY_STATE             = 'RelayState';
const KWSSO_SESSION_INDEX           = 'SessionIndex';
const KWSSO_SUCCESS                 = 'success';
const KWSSO_ERROR                   = 'error';
const KWSSO_SESSION_NOT_ON_OR_AFTER = 'SessionNotOnOrAfter';
const KWSSO_AUTHN_INSTANT           = 'AuthnInstant';
const KWSSO_NAME_FORMAT             = 'NameFormat';
const KWSSO_TEST_CONFIG_OPTION      = 'kw-test-idp-config';
const KWSSO_SAMLRESPONSE            = 'SAMLResponse';
const KWSSO_IDP_SSO_DESCRIPTOR      = './saml_metadata:IDPSSODescriptor';
const KWSSO_METADATA_EXTENTIONS     = './saml_metadata:Extensions';
const KWSSO_MDUI_DISPLAYNAME        = ' . / mdui:UIInfo / mdui:DisplayName';
const KWSSO_METADATA_SSO_SERVICE    = ' . / saml_metadata:SingleSignOnService';
const KWSSO_METADATA_NAMEIDFORMAT   = ' . / saml_metadata:NameIDFormat';
const KWSSO_METADATA_KEY_DESCRIPTOR = ' . / saml_metadata:KeyDescriptor';
