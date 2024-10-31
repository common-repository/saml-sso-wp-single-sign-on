<?php
/**
 * Defines the Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once KWSSO_CONST_BASIC;

/**
 * Defines constants for Redirection SSO Links tab.
 */
class KwConstants extends KwBaseConstant {


	const CUSTOM_LOGIN_BUTTON       = 'kwsso_custom_login_text';
	const ADD_SSO_BUTTON            = 'kwsso_add_sso_button_wp';
	const USE_BUTTON_AS_SHORTCODE   = 'kwsso_use_button_as_shortcode';
	const USE_BUTTON_AS_WIDGET      = 'kwsso_use_button_as_widget';
	const SSO_BUTTON_SIZE           = 'kwsso_button_size';
	const SSO_BUTTON_WIDTH          = 'kwsso_button_width';
	const SSO_BUTTON_HEIGHT         = 'kwsso_button_height';
	const SSO_BUTTON_CURVE          = 'kwsso_button_curve';
	const SSO_BUTTON_COLOR          = 'kwsso_button_color';
	const SSO_BUTTON_TEXT           = 'kwsso_button_text';
	const SSO_BUTTON_THEME          = 'kwsso_button_theme';
	const SSO_BUTTON_FONT_COLOR     = 'kwsso_font_color';
	const SSO_BUTTON_FONT_SIZE      = 'kwsso_font_size';
	const SSO_BUTTON_POSITION       = 'kwsso_login_form_button_position';
	const METADATA_SYNC_URL         = 'kwsso_metadata_url_for_sync';
	const METADATA_SYNC_INTERVAL    = 'kwsso_metadata_sync_interval';
	const METADATA_SYNC_CRON_ACTION = 'kwsso_metadata_sync_cron_action';
	const ADMIN_NOTICE_FLAG         = 'kwsso_show_setting_status_notice';
	const ADMIN_NOTICES_MESSAGE     = 'kwsso_message';
	const IDP_CONFIG_COMPLETE       = 'kwsso_idp_config_complete';

	const ENCODING_CP1252 = 'CP1252';
	const ENCODING_UTF_8  = 'UTF-8';

	const SAML_REQUEST          = 'kwsso_request';
	const SAML_RESPONSE         = 'kwsso_response';
	const TEST_CONFIG_ERROR_LOG = 'kwsso_test';
	const TEST_CONFIG_ATTIBUTES = 'kwsso_test_config_attrs';

	const KEY_USER_TYPE        = 'kwsso_user_type';
	const SAML_RESPONSE_SIGNED = 'kwsso_saml_response_signed';
	const NEW_CERT_TEST        = 'kwsso_new_cert_test';
	const HOME                 = 'home';

	const SP_BASE_URL  = 'kwsso_sp_base_url';
	const SP_ENTITY_ID = 'kwsso_sp_entity_id';

	const KWSSO_IDP_NAME            = 'kwsso_saml_identity_name';
	const KWSSO_IDP_KEY             = 'kwsso_saml_idp_key';
	const IDP_IDENTIFIER_NAME       = 'kwsso_idp_identifier_name';
	const ASSERTION_SIGNED          = 'kwsso_saml_assertion_signed';
	const LOGIN_BINDING_TYPE        = 'kwsso_saml_login_binding_type';
	const LOGIN_URL                 = 'kwsso_saml_login_url';
	const ISSUER                    = 'kwsso_saml_issuer';
	const X509_CERTIFICATE          = 'kwsso_saml_x509_certificate';
	const REQUEST_SIGNED            = 'kwsso_saml_request_signed';
	const NAMEID_FORMAT             = 'kwsso_saml_nameid_format';
	const IS_ENCODING_ENABLED       = 'kwsso_encoding_enabled';
	const ASSERTION_TIME_VALIDATION = 'kwsso_assertion_time_validity';

	const TEST_IDP_CONF  = 'kwsso-test-idp-validation';
	const IS_IDP_ENABLED = 'kwsso_is_idp_enabled';

	const ATTRIBUTE_USERNAME            = 'kwsso_mapping_username';
	const ATTRIBUTE_EMAIL               = 'kwsso_mapping_email';
	const ATTRIBUTE_FIRST_NAME          = 'kwsso_mapping_first_name';
	const ATTRIBUTE_LAST_NAME           = 'kwsso_mapping_last_name';
	const ATTRIBUTE_NICKNAME            = 'kwsso_mapping_nickname';
	const ATTRIBUTE_CUSTOM_MAPPING      = 'kwsso_custom_attrs_mapping';
	const ATTRIBUTE_DISPLAY_NAME        = 'kwsso_mapping_display_name';
	const ATTRIBUTE_SHOW_IN_USER_MENU   = 'kwsso_saml_user_attribute_show';
	const ATTRIBUTE_UPDATE_DISPLAY_NAME = 'kwsso_mapping_update_display_name';
	const ATTRIBUTE_GROUP_NAME          = 'kwsso_mapping_group_name';

	const ROLE_UPDATE_ADMIN_USER_ROLE      = 'kwsso_mapping_update_admin_users_role';
	const ROLE_DEFAULT_ROLE                = 'kwsso_mapping_default_user_role';
	const ROLE_MAPPING                     = 'kwsso_mapping_role_mapping';
	const ROLE_DO_NOT_ASSIGN_ROLE_UNLISTED = 'kwsso_mapping_dont_allow_unlisted_user_role';
	const ASSIGN_DEFAULT_ROLE              = 'kwsso_mapping_assign_default_role';
	const ROLE_DO_NOT_AUTO_CREATE_USERS    = 'kwsso_dont_create_user_if_role_not_mapped';

	const URN_SAML20_ASSERTION      = 'urn:oasis:names:tc:SAML:2.0:assertion';
	const DATE_FORMAT               = 'Y-m-d\TH:i:s\Z';
	const NAMEID_FORMAT_UNSPECIFIED = 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified';
	const NAMEFORMAT_UNSPECIFIED    = 'urn:oasis:names:tc:SAML:2.0:attrname-format:unspecified';
	const URN_SAML20_PROTOCOL       = 'urn:oasis:names:tc:SAML:2.0:protocol';
	const W3_XML_SCHEMA_INST        = 'http://www.w3.org/2001/XMLSchema-instance';
	const W3_XML_SCHEMA             = 'http://www.w3.org/2001/XMLSchema';
	const W3_XML_ELEMENT            = 'http://www.w3.org/2001/04/xmlenc#Element';
	const URN_SAML_METADATA         = 'urn:oasis:names:tc:SAML:2.0:metadata';
	const KEY_INFO_X509_CERT        = ' . / ds:KeyInfo / ds:X509Data / ds:X509Certificate';
	const W3_XML_DSIG               = 'http://www.w3.org/2000/09/xmldsig#';
	const W3_XML_ENC                = 'http://www.w3.org/2001/04/xmlenc#';
	const XML_SOAP_ENV              = 'http://schemas.xmlsoap.org/soap/envelope/';
}

// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound

