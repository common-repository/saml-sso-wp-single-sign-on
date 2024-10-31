<?php
/**
 * Comman Service .
 *
 * @package keywoot-saml-sso/service
 */

// namespace KWSSO_CORE\Service;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use KWSSO_CORE\Src\Main\Helper\KWSSO_CurlCall;

use KWSSO_CORE\Src\Utility\KWSSO_PluginTabs;
use KWSSO_CORE\Traits\Instance;



/**
 * This class handles all the Admin related actions of the user related to the
 * OTP Verification Plugin.
 */
if ( ! class_exists( 'KWSSO_AdminActionService' ) ) {
	/**
	 * KWSSO_AdminActionService class
	 */
	class KWSSO_AdminActionService {

		use Instance;
		/**
		 * Initializes values
		 */

		protected function __construct() {
			$this->initialize_hooks();
		}

		/**
		 * Initialize Hooks
		 *
		 * @return void
		 */
		public function initialize_hooks() {
			add_action( 'admin_init', array( $this, 'kwsso_handle_admin_form_actions' ) );
		}


		/**
		 * Handles the download of metadata based on the provided referer check.
		 */
		public function kwsso_handle_metadata_download() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_sp_metadata_dowload' ) ) {
				return;
			}

			keywoot_sp_metadata_generator( true );
		}
		/**
		 * Handles various admin form actions based on the submitted 'option' parameter.
		 */
		public function kwsso_handle_admin_form_actions() {
			if ( ! current_user_can( KWSSO_MANAGE_OPTIONS ) ) {
				return;
			}
			switch ( get_value_from_post( 'option' ) ) {
				case 'kwsso_save_idp_configurations':
					$this->handle_save_idp_configurations();
					break;
				case 'kwsso_clear_attributes':
					$this->kwsso_clear_attributes();
					break;
				case 'kwsso_sp_core_config':
					$this->handle_save_sp_core_config();
					break;
				case 'kwsso_upload_or_fetch_metadata':
					$this->handleUploadOrFetchMetadata();
					break;
				case 'kwsso_add_sso_button_option':
					$this->kwsso_add_sso_button_on_wplogin();
					break;
				case 'kwsso_enable_diable_idp':
					$this->kwsso_enable_disable_idp();
					break;
				case 'kwsso_sp_metadata_dowload':
					$this->kwsso_handle_metadata_download();
					break;
				case 'kwsso_button_as_shortcode_option':
					$this->kwsso_save_button_as_shortcode();
					break;
				case 'kwsso_confirm_remove_idp_conf':
					$this->kwsso_confirm_remove_idp_config();
				default:
					break;
			}
		}

		/**
		 * Handles the saving of Identity Provider (IDP) configurations from the admin form.
		 *
		 * This function validates and saves IDP settings including name, URL, binding type, issuer,
		 * x509 certificate, and other related options.
		 */
		private function handle_save_idp_configurations() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_save_idp_configurations' ) ) {
				return;
			}
			if ( ! kwsso_is_extension_installed( 'curl' ) ) {
				KWSSO_Display::kwsso_display_admin_notice( 'ERROR:' . KeywootMessage::CURL_DISABLED, KWSSO_ERROR );
				return;
			}

			if ( ! $this->check_fields_validation() ) {
				return;
			}


			$kwsso_saml_identity_name      = htmlspecialchars( trim( get_value_from_post( KwConstants::KWSSO_IDP_NAME ) ) );
			$kwsso_saml_login_url                = htmlspecialchars( trim( get_value_from_post( KwConstants::LOGIN_URL ) ) );
			$kwsso_saml_login_binding_type = htmlspecialchars( get_value_from_post( KwConstants::LOGIN_BINDING_TYPE ) );
			$kwsso_saml_issuer                   = htmlspecialchars( trim( get_value_from_post( KwConstants::ISSUER ) ) );
			$kwsso_saml_x509_certificate   = get_value_from_post( KwConstants::X509_CERTIFICATE );
			$kwsso_saml_nameid_format      = isset( $_POST[ KwConstants::NAMEID_FORMAT ] ) ? htmlspecialchars( $_POST[ KwConstants::NAMEID_FORMAT ] ) : KwNameIdFormatConst::UNSPECIFIED;// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verifing noce in the top of the function.

			$options_to_update = array(
				KwConstants::KWSSO_IDP_NAME     => $kwsso_saml_identity_name,
				KwConstants::LOGIN_BINDING_TYPE => $kwsso_saml_login_binding_type,
				KwConstants::LOGIN_URL          => $kwsso_saml_login_url,
				KwConstants::ISSUER             => $kwsso_saml_issuer,
				KwConstants::NAMEID_FORMAT      => $kwsso_saml_nameid_format,
				KwConstants::REQUEST_SIGNED     => 'unchecked',
				KwConstants::IS_IDP_ENABLED     => 'true',
			);

			KWSSO_Utils::kwsso_update_array_options( $options_to_update );
			$this->check_and_update_x509_certifciate( $kwsso_saml_x509_certificate );
			$this->save_optional_details();
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::IDP_SAVED, KWSSO_SUCCESS );
		}

		private function kwsso_confirm_remove_idp_config() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_confirm_remove_idp_conf' ) ) {
				return;
			}
			$options_to_delete = array(
				KwConstants::KWSSO_IDP_NAME,
				KwConstants::LOGIN_BINDING_TYPE,
				KwConstants::LOGIN_URL,
				KwConstants::ISSUER,
				KwConstants::NAMEID_FORMAT,
				KwConstants::REQUEST_SIGNED,
				KwConstants::ASSERTION_TIME_VALIDATION,
				KwConstants::IS_ENCODING_ENABLED,
				KwConstants::ASSERTION_SIGNED,
				KwConstants::SAML_RESPONSE_SIGNED,
				KwConstants::X509_CERTIFICATE,
				KwConstants::IS_IDP_ENABLED,
			);

			foreach ( $options_to_delete as $option ) {
				delete_kwsso_option( $option );
			}
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::IDP_REMOVED, KWSSO_SUCCESS );

		}
		/**
		 * Checks the validation of fields for idp settings.
		 * It checks if essential fields are not empty and if the Identity Provider Name matches a specific format.
		 *
		 * @return bool Returns true if all fields pass validation; otherwise, false.
		 */
		private function check_fields_validation() {
			if ( empty( get_value_from_post( KwConstants::KWSSO_IDP_NAME ) ) || empty( get_value_from_post( KwConstants::LOGIN_URL ) ) || empty( get_value_from_post( KwConstants::ISSUER ) ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::REQUIRED_FILEDS, KWSSO_ERROR );
				return false;
			} elseif ( ! preg_match( '/^\w*$/', get_value_from_post( KwConstants::KWSSO_IDP_NAME ) ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::INVALID_IDP_NAME, KWSSO_ERROR );
				return false;
			}
			return true;
		}
		/**
		 * Validates and updates the X.509 certificate received for the Identity Provider (IDP).
		 *
		 * This function sanitizes and checks the validity of the provided X.509 certificate before
		 * updating the option with the serialized certificate array.
		 *
		 * @param array $kwsso_saml_x509_certificate The array of certificate data to be processed.
		 */
		private function check_and_update_x509_certifciate( $kwsso_saml_x509_certificate ) {
			foreach ( $kwsso_saml_x509_certificate as $key => $value ) {
				if ( empty( $value ) ) {
					unset( $kwsso_saml_x509_certificate[ $key ] );
				} else {
					$kwsso_saml_x509_certificate[ $key ] = KWSSO_Helper::kwsso_sanitize_certificate( $value );
					if ( ! @openssl_x509_read( $kwsso_saml_x509_certificate[ $key ] ) ) {
						KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::INVALID_CERTIFICATE, KWSSO_ERROR );
						delete_kwsso_option( KwConstants::X509_CERTIFICATE );
						return;
					}
				}
			}
			if ( empty( $kwsso_saml_x509_certificate ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::INVALID_CERTIFICATE, KWSSO_ERROR );
				return;
			}
			update_kwsso_option( KwConstants::X509_CERTIFICATE, maybe_serialize( $kwsso_saml_x509_certificate ) );
		}
		/**
		 * Saves optional details/settings related to SAML responses and assertions.
		 *
		 * This function updates various SAML-related options based on form submissions,
		 * including response signing, assertion signing, encoding settings, and time validation.
		 */
		private function save_optional_details() {
			$options = array(
				KwConstants::SAML_RESPONSE_SIGNED      => array(
					'default'       => 'Yes',
					'checked_value' => 'checked',
				),
				KwConstants::ASSERTION_SIGNED          => array(
					'default'       => 'Yes',
					'checked_value' => 'checked',
				),
				KwConstants::IS_ENCODING_ENABLED       => array(
					'default'       => 'unchecked',
					'checked_value' => 'checked',
				),
				KwConstants::ASSERTION_TIME_VALIDATION => array(
					'default'       => 'unchecked',
					'checked_value' => 'checked',
				),
			);
			foreach ( $options as $option_key => $option_data ) {
				$default_value   = $option_data['default'];
				$checked_value   = $option_data['checked_value'];
				$value_to_update = isset( $_POST[ $option_key ] ) ? $checked_value : $default_value; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce already Verified.
				update_kwsso_option( $option_key, $value_to_update );
			}
		}

		/**
		 * Handles the action to clear specific attributes from the configuration.
		 *
		 * This function removes test configuration attributes based on admin form submission.
		 */
		private function kwsso_clear_attributes() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_clear_attributes' ) ) {
				return;
			}
			delete_kwsso_option( KwConstants::TEST_CONFIG_ATTIBUTES );
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::ATTR_LIST_CLR, KWSSO_SUCCESS );
		}
		/**
		 * Handles saving of Service Provider (SP) core configuration settings.
		 *
		 * This function validates and updates SP base URL and entity ID options from the admin form.
		 *
		 * @return void
		 */
		private function handle_save_sp_core_config() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_sp_core_config' ) ) {
				return;
			}
			if ( ! empty( get_value_from_post( KwConstants::SP_BASE_URL ) ) && ! empty( get_value_from_post( KwConstants::SP_ENTITY_ID ) ) ) {
				$sp_base_url  = htmlspecialchars( get_value_from_post( KwConstants::SP_BASE_URL ) );
				$sp_entity_id = htmlspecialchars( get_value_from_post( KwConstants::SP_ENTITY_ID ) );
				if ( substr( $sp_base_url, -1 ) == '/' ) {
					$sp_base_url = substr( $sp_base_url, 0, -1 );
				}
				update_kwsso_option( KwConstants::SP_BASE_URL, $sp_base_url );
				update_kwsso_option( KwConstants::SP_ENTITY_ID, $sp_entity_id );
			}
			KWSSO_Display::kwsso_display_admin_notice( 'SP Settings updated successfully.', KWSSO_SUCCESS );
		}

		private function show_notice_if_sp_not_configured() {
			if ( ! kwsso_check_if_sp_configured() ) {
				KWSSO_Display::kwsso_display_admin_notice( 'Please complete ' . KWSSO_Utils::kwsso_add_link( 'Service Provider', add_query_arg( array( 'tab' => 'save' ), $_SERVER['REQUEST_URI'] ) ) . ' configuration first.', KWSSO_ERROR );
				return true;
			}
		}
		/**
		 * Saves the option to use the button as a shortcode.
		 * This function checks the admin nonce, updates the 'Use Button as Shortcode' option based on form submission,
		 */
		private function kwsso_save_button_as_shortcode() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_button_as_shortcode_option' ) || $this->show_notice_if_sp_not_configured() ) {
				return;
			}
			$use_button_shortcode = isset( $_POST[ KwConstants::USE_BUTTON_AS_SHORTCODE ] ) ? htmlspecialchars( $_POST[ KwConstants::USE_BUTTON_AS_SHORTCODE ] ) : 'false';// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce already Verified.
			$message              = $use_button_shortcode == 'true' ? KeywootMessage::SHRTCD_AS_BUTTON_ADDED : KeywootMessage::SETTINGS_UPDATED;
			update_kwsso_option( KwConstants::USE_BUTTON_AS_SHORTCODE, $use_button_shortcode );
			KWSSO_Display::kwsso_display_admin_notice( $message, KWSSO_SUCCESS );
		}
		/**
		 * Adds the SSO button on the WordPress login page.
		 *
		 * This function checks the admin nonce, updates the 'Add SSO Button on WP Login' option based on form submission,
		 * and displays relevant admin notices.
		 */
		private function kwsso_add_sso_button_on_wplogin() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_add_sso_button_option' ) || $this->show_notice_if_sp_not_configured() ) {
				return;
			}
				$add_button_on_wp = isset( $_POST['kwsso_add_sso_button_wp'] ) ? htmlspecialchars( $_POST['kwsso_add_sso_button_wp'] ) : 'false'; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce already Verified.
				$message          = $add_button_on_wp == 'true' ? KeywootMessage::SSO_BUTTON_ADDED : KeywootMessage::SETTINGS_UPDATED;
				update_kwsso_option( KwConstants::ADD_SSO_BUTTON, $add_button_on_wp );
				KWSSO_Display::kwsso_display_admin_notice( $message, KWSSO_SUCCESS );
		}

			/**
			 * Adds the SSO button on the WordPress login page.
			 *
			 * This function checks the admin nonce, updates the 'Add SSO Button on WP Login' option based on form submission,
			 * and displays relevant admin notices.
			 */
		private function kwsso_enable_disable_idp() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_enable_diable_idp' ) || $this->show_notice_if_sp_not_configured() ) {
				return;
			}
			$kwsso_is_idp_enabled = isset( $_POST['kwsso_enable_diable_idp'] ) ? htmlspecialchars( $_POST['kwsso_enable_diable_idp'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce already Verified.
			$message              = $kwsso_is_idp_enabled == 'true' ? KeywootMessage::IDP_ENABLED : KeywootMessage::IDP_ENABLED;
			update_kwsso_option( KwConstants::IS_IDP_ENABLED, $kwsso_is_idp_enabled );
			KWSSO_Display::kwsso_display_admin_notice( $message, KWSSO_SUCCESS );
		}
		/**
		 * Handles the upload or fetching of SSO metadata.
		 * This function checks the admin nonce and processes the upload or fetching of metadata based on form submission.
		 */
		private function handleUploadOrFetchMetadata() {
			if ( ! KWSSO_Utils::kwsso_check_admin_nonce( 'kwsso_upload_or_fetch_metadata' ) ) {
				return;
			}
			if ( empty( get_value_from_post( KWSSO_SAML_IDP_NAME ) ) ) {
				KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::INVALID_IDP_NAME, KWSSO_ERROR );
				return;
			}
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once KWSSO_FilePath::WP_ADMIN_FILE;
			}
			$kwsso_metadata_import_service = KwMetadataHandlerService::kwsso_get_instance();
			$kwsso_metadata_import_service->kwsso_handle_upload_or_fetch_metadata();
		}
	}
}

