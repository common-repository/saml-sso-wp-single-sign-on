<?php

use \RobRichards\XMLSecLibs\XMLSecurityKey;
use \RobRichards\XMLSecLibs\XMLSecurityDSig;
use \RobRichards\XMLSecLibs\XMLSecEnc;
use KWSSO_CORE\Helper\KwMapping;
use KWSSO_CORE\Traits\Instance;

/**
 * The plugin setting class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class KWSSO_SettingsService {

	/**
	 * Stores KwUserLogoutHander object
	 *
	 * @var kwsso_user_logout_hander
	 */
	private $kwsso_user_logout_hander;
	 /**
	 * Stores KWSSO_SettingsService object.
	 *
	 * @var object
	 */

	use Instance;
	/**
	 * The Constructor for the main class.
	 */
	public function __construct() {
		$this->initialize_hooks();

	}
	/**
	 * This takes care of initializing all the hooks used by the plugin.
	 *
	 * @return void
	 */
	private function initialize_hooks() {
		add_action( 'wp_authenticate', array( $this, 'handle_kwsso_saml_user_authentication' ) );
		add_action( 'login_footer', array( $this, 'kwsso_add_sso_button_on_wp_login' ) );
		add_shortcode( 'KWSSO_SAML_SSO', array( $this, 'kwsso_use_sso_shortcode' ) );
	}



	/**
	 * Handles user authentication using KW SAML.
	 *
	 * This function checks if the user is logged in. If the user is logged in, it checks
	 * if the redirect URL is an admin URL or the login URL. If not, it safely redirects
	 * the user to the specified redirect URL. If the user is not logged in, it redirects
	 * the user to the site URL.
	 *
	 * @return void
	 */
	public function handle_kwsso_saml_user_authentication() {
		$redirect_to = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : '';// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the addon name, doesn't require nonce 
		if ( is_user_logged_in() ) {
			$is_admin_url = ( admin_url() === $redirect_to || wp_login_url() === $redirect_to );
			$redirect_url = ! empty( $redirect_to ) && ! $is_admin_url ? $redirect_to : site_url();
			wp_safe_redirect( $redirect_url );
			exit();
		}
	}


	/**
	 * Function is used to add SSO button on WP login page.
	 *
	 * @return void
	 */
	public function kwsso_add_sso_button_on_wp_login() {
		if ( kwsso_check_if_user_logged_in() ) {
			return;
		}
		if ( get_kwsso_option( KwConstants::ADD_SSO_BUTTON ) == 'true' && get_kwsso_option( KwConstants::IS_IDP_ENABLED ) ) {
			$sp_base_url  = empty( get_kwsso_option( KwConstants::SP_BASE_URL ) ) ? home_url() : get_kwsso_option( KwConstants::SP_BASE_URL );
			$login_button = KWSSO_Utils::kwsso_get_button_styles();
			$redirect_to  = isset( $_GET['redirect_to'] ) ? rawurlencode( $_GET['redirect_to'] ) : '';// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the addon name, doesn't require nonce 
			$button_html  = '<a href="' . $sp_base_url . '/?option=kwsso_sso_user_login&redirect_to=' . $redirect_to . '" style="text-decoration:none;">' . $login_button . '</a>';
			$button_html  = '<div >' . $button_html . '</div>';
			$button_html  = '<div id="kwsso_wp_login_sso_button" style="text-align:center"><div style="padding: 45px 10px 10px 10px;font-size:14px;"><b>OR</b></div>' . $button_html . '</div>';
			echo '<script>jQuery(".submit").append(\'' . $button_html . '\');</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
		}
	}


	/**
	 * Function is used to add sso using shortcode.
	 *
	 * @return void
	 */
	public function kwsso_use_sso_shortcode( $attrs ) {
		if ( kwsso_check_if_user_logged_in() ) {
			return '';
		}
		if ( ! kwsso_check_if_sp_configured() ) {
			return 'SP is not configured.';
		}
		if ( get_kwsso_option( KwConstants::IS_IDP_ENABLED ) != 'true' ) {
			return 'IDP is not Enabled.';
		}
		$sp_base_url = get_kwsso_option( KwConstants::SP_BASE_URL, home_url() );
		$idp         = isset( $attrs['idp'] ) ? $attrs['idp'] : get_kwsso_option( KwConstants::KWSSO_IDP_NAME );

		$login_title = get_kwsso_option( KwConstants::CUSTOM_LOGIN_BUTTON, 'Login with ' . get_kwsso_option( KwConstants::KWSSO_IDP_NAME ) );
		$login_title = str_replace( '##IDP##', $idp, $login_title );
		$use_button  = ( get_kwsso_option( KwConstants::USE_BUTTON_AS_SHORTCODE ) == 'true' );
		if ( $use_button ) {
			$login_title = KWSSO_Utils::kwsso_get_button_styles();
		}
		$redirect_to = urlencode( KWSSO_Utils::kwsso_get_current_page_url() );
		$html        = '<a style="display:block" href="' . $sp_base_url . '/?option=kwsso_sso_user_login';
		$html       .= ! empty( $idp ) ? '&idp=' . $idp : '';
		$html       .= '&redirect_to=' . $redirect_to . '"';
		$html       .= $use_button ? ' style="text-decoration:none;"' : '';
		$html       .= '>' . $login_title . '</a>';

		return $html;
	}

}


