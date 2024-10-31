<?php
/**
 * Comman service to check the registration.
 *
 * @package keywoot-saml-sso/service
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use KWSSO_CORE\Src\Main\Helper\KWSSO_CurlCall;

use KWSSO_CORE\Traits\Instance;

/**
 * KWSSO_Activation class
 */
class KWSSO_Activation {

	use Instance;
	/**
	 * Initializes values
	 */
	protected function __construct() {
		add_action( 'admin_init', array( $this, 'kwsso_handle_admin_actions' ), 1 );
		add_action( 'admin_init', array( $this, 'kwsso_redirect_after_activation' ) );
		add_action( 'admin_notices', array( $this, 'show_plugin_main_notice' ) );
		add_action( 'wp_ajax_kwsso-feedback_send_deactivation', array( $this, 'kwsso_feedback_send_deactivation' ) );
		add_action( 'wp_ajax_kwsso_dismiss_notice', array( $this, 'kwsso_dismiss_main_admin_notice' ) );
		register_activation_hook( KWSSO_FilePath::PLUGIN_MAIN_FILE, array( $this, 'kwsso_plugin_activate_actions' ) );
		register_deactivation_hook( KWSSO_FilePath::PLUGIN_MAIN_FILE, array( $this, 'kwsso_plugin_deactivate' ) );
	}

	/**
	 * This function hooks into the admin_init WordPress hook. This function
	 * checks the form being posted and routes the data to the correct function
	 * for processing. The 'option' value in the form post is checked to make
	 * the diversion.
	 */
	public function kwsso_handle_admin_actions() {
		$option = filter_input( INPUT_POST, 'option', FILTER_SANITIZE_STRING );
		if ( ! $option || $option == null ) {
			return;
		}
		switch ( $option ) {
			case 'kwsso_contact_us_query':
				if ( ! current_user_can( KWSSO_MANAGE_OPTIONS ) || ! check_admin_referer( 'kwsso_contact_us_query' ) ) {
					wp_die( esc_attr( KeywootMessage::INVALID_OPERATION ) );
				}
				$this->kwsso_send_email_support_query();
				break;
			case 'kwsso-feedback-deactivation-form-option':
				if ( ! current_user_can( KWSSO_MANAGE_OPTIONS ) || ! check_admin_referer( 'kwsso-feedback-deactivation-form' ) ) {
					wp_die( esc_attr( KeywootMessage::INVALID_OPERATION ) );
				}
				$this->kwsso_deactivate_current_plugin();
				break;
			case 'kwsso-email-activation-form':
				if ( ! current_user_can( KWSSO_MANAGE_OPTIONS ) || ! check_admin_referer( 'kwsso-email-activation-form' ) ) {
					wp_die( esc_attr( KeywootMessage::INVALID_OPERATION ) );
				}
				$this->kwsso_send_new_activation_mail();
				break;
			case 'kwsso-set-first-activation':
				if ( ! current_user_can( KWSSO_MANAGE_OPTIONS ) || ! check_admin_referer( 'kwsso-set-first-activation' ) ) {
					wp_die( esc_attr( KeywootMessage::INVALID_OPERATION ) );
				}
				$this->kwsso_set_first_activation();
				break;
		}
	}
	public function kwsso_deactivate_current_plugin() {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$email     = filter_input( INPUT_POST, 'feedback_query_email', FILTER_SANITIZE_EMAIL );
		$reason    = filter_input( INPUT_POST, 'deactivation_details', FILTER_SANITIZE_STRING );

		$plugin = KWSSO_PLUGIN_NAME;
		deactivate_plugins( $plugin );
	}
	/**
	 * This function runs on the submission of contact us form.
	 *
	 * @param array $post_data .
	 */
	private function kwsso_send_email_support_query() {
		$email = filter_input( INPUT_POST, 'query_email', FILTER_SANITIZE_EMAIL );
		$query = filter_input( INPUT_POST, 'query', FILTER_SANITIZE_STRING );

		if ( ! $email || ! $query || $email == null || $query == null ) {
			KWSSO_Display::kwsso_display_admin_notice( 'Please fill the required Fields', KWSSO_ERROR );
			return;
		}

		KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::ERR_FORM_SUB, KWSSO_ERROR );

	}

	/**
	 * This function shows the Enterprise plan notificaton on the admin site only at once.
	 * Once you click on the close notice it will not displayed again.
	 * After deactivation of plugin again the notification will get display.
	 **/
	public function show_plugin_main_notice() {
		$license_page_url = admin_url() . 'admin.php?page=kwsso-premium';
		$query_string     = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : ''; //phpcs:ignore -- false positive.
		$current_url      = admin_url() . 'admin.php?' . $query_string;
		$is_notice_closed = get_kwsso_option( 'kwsso_hide_notice' );
		if ( 'kwsso_hide_notice' !== $is_notice_closed ) {
			if ( $current_url !== $license_page_url ) {
				$mainText       = __( 'We provide Single Sign-On (SSO) integration with over 30 Identity Providers (IDP), including Azure, Azure B2C, Okta, ADFS, Keycloak, OneLogin, Salesforce, Google Workspace (formerly G Suite), Shibboleth, Centrify, Ping, Auth0, and many more.', 'keywoot-saml-sso' );
				$note           = __( 'NOTE', 'keywoot-saml-sso' );
				$textForPremium = __( 'Our premium plans include advanced features such as attribute mapping, role mapping, post-login redirection, customizable SSO buttons, and more.', 'keywoot-saml-sso' );
				$note_html      = '<div><p><b>' . $mainText . '</p><br><p class="mt-kw-4"><u>' . $note . '</u> ' . $textForPremium . '</p><br></b></div>';
				echo '
		 <div id="kw-admin-notice-container" class="kw-admin-notice-container is-dismissible">
					<div class="kw-admin-notice">
						<div class="kw-logo-container">
							<img class="" style="width: 100%;margin-bottom: -1%;" src="' . esc_url( KEYWOOT_LOGO_WHITE ) . '" alt="Logo">
						</div>
						<div class="kw-notice-content">' . $note_html . '
						<div class="flex gap-kw-8">
						<a href=' . esc_url( $license_page_url ) . ' class="kw-button primary  w-full ">Check Out Premium Plans</a>
						<button id="kw-dismiss-main-admin-notice" class="kw-button secondary">Dismiss</button>
					 </div></div>
						
					</div>
				</div>';
			}
		}

	}



	/**
	 * This function we used to update the value on click of hide admin notice.
	 * This is the check for notification on click of close notification.
	 */
	public function kwsso_dismiss_main_admin_notice() {
		if ( current_user_can( KWSSO_MANAGE_OPTIONS ) ) {
			update_kwsso_option( 'kwsso_hide_notice', 'kwsso_hide_notice' );
		}
	}
	/**
	 * Actions to be performed upon plugin activation.
	 *
	 * This function executes actions required upon activation of the plugin,
	 * such as checking OpenSSL extension, and enabling keeping settings intact.
	 */
	public function kwsso_plugin_activate_actions() {
		$this->kwsso_check_openssl();
		set_transient( 'kwsso_activation_redirect', true, 30 );
	}

	function kwsso_redirect_after_activation() {
		// Check if the transient is set
		if ( get_transient( 'kwsso_activation_redirect' ) ) {
			// Delete the transient to prevent the redirect from happening again
			delete_transient( 'kwsso_activation_redirect' );

			// Ensure this is not a multisite activation
			if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
				wp_safe_redirect( admin_url( 'admin.php?page=kwsso-main-settings' ) );
				exit;
			}
		}
	}

	/**
	 * Checks if the OpenSSL extension is installed and displays error message if not.
	 */
	public function kwsso_check_openssl() {
		if ( ! kwsso_is_extension_installed( 'openssl' ) ) {
			KWSSO_Display::kwsso_die_and_display_error( KwErrorCodes::$error_codes['KWSSOERR020'] );
		}
	}

	private function kwsso_set_first_activation() {
		 update_kwsso_option( 'kwsso_user_first_activation', 'done' );
	}
	/**
	 * Send Email On new Activation
	 *
	 * @return void
	 */
	private function kwsso_send_new_activation_mail() {
			$user_email = filter_input( INPUT_POST, 'activation_query_email', FILTER_SANITIZE_EMAIL );
			$query      = 'Plugin Installed';
	}

	/**
	 * Get runs on deactivation and remove plugin options
	 *
	 * @return void
	 */
	public function kwsso_plugin_deactivate() {
		// code to add
	}
}
