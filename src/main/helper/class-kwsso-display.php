<?php
/**Load adminstrator changes for KWSSO_CurlCall
 *
 * @package keywoot-saml-sso/helper
 */

// namespace KWSSO_CORE\Src\Main\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class denotes all the cURL related functions to make API calls
 *.
 */
if ( ! class_exists( 'KWSSO_Display' ) ) {
	/**
	 * KWSSO_CurlCall class
	 */
	class KWSSO_Display {
		/**
		 * Updates success message.
		 */
		public static function update_success_notice_status() {
			update_kwsso_option( KwConstants::ADMIN_NOTICE_FLAG, 'success' );
		}

		/**
		 * Updates an error message.
		 */
		public static function update_error_notice_status() {
			update_kwsso_option( KwConstants::ADMIN_NOTICE_FLAG, 'error' );
		}
		/**
		 * Display the Admin Notice
		 *
		 * @param string $message
		 * @param sting  $status
		 * @return void
		 */
		public static function c( $message, $status ) {
			update_kwsso_option( KwConstants::ADMIN_NOTICES_MESSAGE, $message );
			if ( $status == 'success' ) {
				self::update_success_notice_status();
			} else {
				self::update_error_notice_status();
			}
		}
		/**
		 * Displays a status notice based on the 'kwsso_show_setting_status_notice' option.
		 *
		 * This function retrieves the status notice setting and the message from the options,
		 * and then displays a corresponding HTML message based on the setting.
		 */
		public static function display_status_notice() {
			$kwsso_show_setting_status_notice = get_kwsso_option( KwConstants::ADMIN_NOTICE_FLAG );
			$status_result_string       = get_kwsso_option( KwConstants::ADMIN_NOTICES_MESSAGE );

			if ( $kwsso_show_setting_status_notice === 'success' || $kwsso_show_setting_status_notice === 'error' ) {
				$alert_class = $kwsso_show_setting_status_notice === 'success' ? 'kw-alert-success' : 'kw-alert-error';

				echo '<div id="status_result_notice" class="kw-alert-container ' . esc_attr( $alert_class ) . '">
                        <span>' . wp_kses(
					$status_result_string,
					array(
						'a' => array( 'href' => array() ),
						'i' => array( 'href' => array() ),
						'u' => array( 'href' => array() ),
					)
				) . '</span>
                      </div>';

				update_kwsso_option( KwConstants::ADMIN_NOTICE_FLAG, false );
			}
		}
		public static function kwsso_display_admin_notice( $message, $status ) {
			update_kwsso_option( KwConstants::ADMIN_NOTICES_MESSAGE, $message );
			if ( $status == 'success' ) {
				self::update_success_notice_status();
			} else {
				self::update_error_notice_status();
			}
		}

		public static function kwsso_display_status_error( $kwsso_status_code, $kwsso_relay_state, $statusmessage ) {
			$kwsso_status_code = wp_strip_all_tags( $kwsso_status_code );
			$statusmessage  = wp_strip_all_tags( $statusmessage );
			$error_code     = KwErrorCodes::$error_codes['KWSSOERR006'];
			if ( KWSSO_Utils::check_if_test_configuration( $kwsso_relay_state ) ) {
				kwsso_display_test_error( $error_code, '', $statusmessage );
			} else {
				self::kwsso_die_and_display_error( $error_code );
			}
		}

		/**
		 * Displays the error message to admins via admin notice.
		 *
		 * @param array $error_code An array containing the error code details: code, fix, cause and description.
		 * @return void
		 */
		public static function kwsso_show_exception( $error_code ) {
			$message = 'Exception Occured';
			self::kwsso_display_admin_notice( $message, KWSSO_ERROR );
		}
		/**
		 * Displays the error message to end users along with the provided error code.
		 *
		 * @param array $error_code An array containing the error code details: code, fix, cause and description.
		 * @return void
		 */
		public static function kwsso_die_and_display_error( $error_code ) {
			wp_die( 'Error Occured' );
		}

	}
}
