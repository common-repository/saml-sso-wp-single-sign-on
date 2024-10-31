<?php
/**Load adminstrator changes for KWSSO_Utils
 *
 * @package keywoot-saml-sso/helper
 */

//  namespace KWSSO_CORE\Src\Main\Helper;

use KWSSO_CORE\Src\Utility\KWSSO_PluginTabs;
use KWSSO_CORE\Traits\Instance;



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This is the main Utility class of the plugin.
 * Lists down all the necessary common utility
 * functions being used in the plugin.
 */
if ( ! class_exists( 'KWSSO_Utils' ) ) {
	/**
	 * KWSSO_Utils class
	 */
	class KWSSO_Utils {
		use Instance;
		
		/**
		 * Returns the SP Entity ID value configured in the plugin.
		 * If the SP Entity ID value is not specifically configured, this function creates the SP Entity ID using the base Url.
		 *
		 * @param string $sp_base_url The Base URL of the site.
		 * @return string
		 */
		public static function kwsso_get_sp_entity_id( $sp_base_url ) {
			$sp_entity_id = get_kwsso_option( 'kwsso_sp_entity_id' );
			if ( empty( $sp_entity_id ) ) {
				$sp_entity_id = $sp_base_url . '/wp-content/plugins/keywoot-saml-sso/';
			}
			return $sp_entity_id;
		}

		/**
		 * Function to get the Base URL of the site.
		 *
		 * @return string
		 */
		public static function kwsso_get_sp_base_url() {
			$sp_base_url = get_kwsso_option( 'kwsso_sp_base_url' );
			if ( empty( $sp_base_url ) ) {
				$sp_base_url = home_url();
			}
			return $sp_base_url;
		}

		/**
		 * Generates a timestamp.
		 *
		 * @param int|null $timestamp Unix timestamp, optional.
		 * @return string Formatted timestamp.
		 */
		public static function kwsso_create_timestamp( $timestamp = null ) {
			if ( null === $timestamp ) {
				$timestamp = time();
			}
			return gmdate( 'Y-m-d\TH:i:s\Z', $timestamp );
		}




		/**Sanitizing array
		 *
		 * @param array $data data array to be sanitized.
		 * @return string
		 */
		public static function kwsso_sanitize_array( $data ) {
			$sanitized_data = array();
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_data[ $key ] = self::kwsso_sanitize_array( $value );
				} else {
					$sanitized_data[ $key ] = sanitize_text_field( $value );
				}
			}
			return $sanitized_data;
		}

		/**
		 * Process the value being passed and checks if it is empty or null
		 *
		 * @param string $value - the value to be checked.
		 *
		 * @return bool
		 */
		public static function is_blank( $value ) {
			return ! isset( $value ) || empty( $value );
		}

		/**
		 * Process the plugin name is being passed and checks if it plugin is active or not
		 *
		 * @param string $plugin - the plugin name to be checked.
		 *
		 * @return bool
		 */

		public static function is_plugin_installed( $plugin ) {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			return is_plugin_active( $plugin );
		}


		/**
		 * Creates and returns the JSON response.
		 *
		 * @param string $message - the message.
		 * @param string $type - the type of result ( success or error ).
		 * @return array
		 */
		public static function create_json( $message, $type ) {
			return array(
				'message' => $message,
				'result'  => $type,
			);
		}

		public static function kwsso_get_current_page_url() {
			$http_host = $_SERVER['HTTP_HOST'];
			if ( substr( $http_host, -1 ) == '/' ) {
				$http_host = substr( $http_host, 0, -1 );
			}
			$kwsso_request_uri = $_SERVER['REQUEST_URI'];
			if ( substr( $kwsso_request_uri, 0, 1 ) == '/' ) {
				$kwsso_request_uri = substr( $kwsso_request_uri, 1 );
			}

			$is_https       = ( ! empty( $_SERVER['HTTPS'] ) && strcasecmp( $_SERVER['HTTPS'], 'on' ) == 0 );
			$kwsso_relay_state = 'http' . ( $is_https ? 's' : '' ) . '://' . $http_host . '/' . $kwsso_request_uri;
			return $kwsso_relay_state;
		}

		/**
		 * The function retrieves the domain part of the email
		 *
		 * @param string $email - the email whose domain has to be validated.
		 *
		 * @return bool|string
		 */
		public static function get_domain( $email ) {
			$domain_name = substr( strrchr( $email, '@' ), 1 );
			return $domain_name;
		}
		public static function create_distinct_id() {
			$byte_string = openssl_random_pseudo_bytes( 21 );
			$hex_string  = bin2hex( $byte_string );
			return '_' . $hex_string;
		}
		public static function kwsso_check_if_admin_user( $user ) {
			return ( ! is_null( $user->roles ) && in_array( 'administrator', $user->roles, true ) );
		}


		public static function kwsso_add_link( $title, $link ) {
			$html = '<a href="' . $link . '">' . $title . '</a>';
			return $html;
		}

		/**
		 * Returns TRUE or FALSE depending on if the POLYLANG plugin is active.
		 * This is used to check if the translation should use the polylang
		 * function or the default local translation.
		 *
		 * @return boolean
		 */
		public static function is_polylang_installed() {
			return function_exists( 'pll__' ) && function_exists( 'pll_register_string' );
		}

		public static function determine_node_type( $value ) {
			if ( is_string( $value ) ) {
				return 'xs:string';
			} elseif ( is_int( $value ) ) {
				return 'xs:integer';
			} else {
				return null;
			}
		}
		/**
		 * Take an array of string having the keyword to replace
		 * and the keyword to be replaced. This is used to modify
		 * the SMS templates that the user might have saved in the
		 * settings or the default ones by the plugin.
		 *
		 * @param array  $replace the array containing search and replace keywords.
		 * @param string $string entire string to be modified.
		 *
		 * @return mixed
		 */
		public static function replace_string( array $replace, $string ) {
			foreach ( $replace as $key => $value ) {
				$string = str_replace( '{' . $key . '}', $value, $string );
			}

			return $string;
		}
		public static function check_if_test_configuration( $kwsso_relay_state ) {
			return $kwsso_relay_state === 'kwsso-test-idp-validation';
		}

		/**
		 * Used to display exceptions, if the exception has a non 0 code this function fetches the error code defined by plugin.
		 *
		 * @param Exception $exception Exception object.
		 * @param bool      $is_notice Optional. Determines if the thrown exception should be shown as an admin notice. Default false.
		 * @return void
		 */
		public static function kwsso_thrw( $exception, $is_notice = false ) {
			$code       = $exception->getCode();
			$error_code = 'KWSSOERR';
			if ( 0 !== $code ) {
				$formatted_code = str_pad( $code, 3, '0', STR_PAD_LEFT );
				$error_code    .= $formatted_code;
				$error_message  = KwErrorCodes::$error_codes[ $error_code ] ?? null;
				if ( $error_message ) {
					$is_notice ? KWSSO_Display::kwsso_show_exception( $error_message ) : KWSSO_Display::kwsso_die_and_display_error( $error_message );
				}
			}
		}
		/**
		 * Check if there is an existing value in the array/buffer and return the value
		 * that exists against that key otherwise return false.
		 * <p></p>
		 * The function also makes sure to sanitize the values being fetched.
		 * <p></p>
		 * If the buffer to fetch the value from is not an array then return buffer as it is.
		 *
		 * @param string       $key    the key to check against.
		 * @param   string|array $buffer the post/get or array.
		 * @return string|bool|array
		 */
		public static function kwsso_sanitize_value_from_array( $key, $buffer ) {
			if ( ! is_array( $buffer ) ) {
				return $buffer;
			}
			$value = ! array_key_exists( $key, $buffer ) || self::is_blank( $buffer[ $key ] ) ? false : $buffer[ $key ];
			return is_array( $value ) ? $value : sanitize_text_field( $value );
		}

		/**
		 * Check if the phone number is empty and return error.
		 *
		 * @param string $option_name phone number of the user.
		 */
		public static function kwsso_check_admin_nonce( $option_name ) {
			return ( ! empty( $_POST['option'] ) && $_POST['option'] === $option_name && check_admin_referer( $option_name ) );
		}
		public static function kwsso_update_array_options( $options_array ) {
			foreach ( $options_array as $option_key => $option_value ) {
				update_kwsso_option( $option_key, $option_value );
			}
		}

		/**
		 * Checks if keywoot sso plugin is active.
		 *
		 * @param string $plugin_name Name of the plugin to check.
		 * @return bool
		 */
		public static function is_keywoot_sso_plugin_active( $plugin_name ) {
			$active_plugins = get_kwsso_option( 'active_plugins' );
			return in_array( $plugin_name, (array) $active_plugins );
		}

			/**
			 * Retrieves the value from an array using a specified key.
			 *
			 * @param string  $key Key to access the value in the array.
			 * @param array   $array Array from which to retrieve the value.
			 * @param boolean $require_array Indicates if an array is required as the return value.
			 * @return mixed
			 */
		public static function kwsso_get_array_value_by_key( $key, $array, $require_array = false ) {
			if ( ! empty( $array ) && isset( $array[ $key ] ) ) {
				$value = $array[ $key ];
			} else {
				$value = $require_array ? array() : '';
			}
			return $value;
		}
		/**
		 * Sanitizes each element of an associative array, including nested arrays.
		 *
		 * This function iterates over an associative array and applies the `sanitize_text_field` function
		 * to each value. It supports nested arrays.
		 *
		 * @param array $raw_array The unsanitized associative array.
		 * @return array Sanitized associative array.
		 */
		public static function kwsso_sanitize_associative_array( $raw_array ) {
			$sanitized_array = array();
			foreach ( $raw_array as $key => $value ) {
				if ( is_array( $value ) ) {
					$temp_value = array();
					foreach ( $value as $inner_key => $inner_value ) {
						$temp_value[ $inner_key ] = sanitize_text_field( $inner_value );
					}
					$sanitized_array[ $key ] = $temp_value;
				} else {
					$sanitized_array[ $key ] = sanitize_text_field( $value );
				}
			}
			return $sanitized_array;
		}

		public static function kwsso_get_button_styles() {
			// Set default values
			$defaults = array(
				'width'      => '270',
				'height'     => '35',
				'size'       => '55',
				'curve'      => '4',
				'color'      => '#2563EB',
				'theme'      => 'longbutton',
				'text'       => 'Login',
				'font_color' => '#ffffff',
				'font_size'  => '13',
				'position'   => 'above',
			);

				 // Get options or use defaults
				 $options = array(
					 'width'         => get_kwsso_option( KwConstants::SSO_BUTTON_WIDTH, $defaults['width'] ),
					 'height'        => get_kwsso_option( KwConstants::SSO_BUTTON_HEIGHT, $defaults['height'] ),
					 'size'          => get_kwsso_option( KwConstants::SSO_BUTTON_SIZE, $defaults['size'] ),
					 'curve'         => get_kwsso_option( KwConstants::SSO_BUTTON_CURVE, $defaults['curve'] ),
					 'color'         => get_kwsso_option( KwConstants::SSO_BUTTON_COLOR, $defaults['color'] ),
					 'theme'         => get_kwsso_option( KwConstants::SSO_BUTTON_THEME, $defaults['theme'] ),
					 'text'          => get_kwsso_option( KwConstants::SSO_BUTTON_TEXT, $defaults['text'] ),
					 'font_color'    => get_kwsso_option( KwConstants::SSO_BUTTON_FONT_COLOR, $defaults['font_color'] ),
					 'font_size'     => get_kwsso_option( KwConstants::SSO_BUTTON_FONT_SIZE, $defaults['font_size'] ),
					 'position'      => get_kwsso_option( KwConstants::SSO_BUTTON_POSITION, $defaults['position'] ),
					 'identity_name' => get_kwsso_option( KwConstants::KWSSO_IDP_NAME ),
				 );

				 // Determine button text
				 $button_text = $options['text'];
				 if ( $options['identity_name'] ) {
					 $button_text = 'Login with ' . $options['identity_name'];
				 }

				 // Set common style properties
				 $style  = 'width:' . $options['width'] . 'px;';
				 $style .= 'height:' . $options['height'] . 'px;';
				 $style .= 'background-color:' . $options['color'] . ';';
				 $style .= 'border-color:transparent;';
				 $style .= 'color:' . $options['font_color'] . ';';
				 $style .= 'font-size:' . $options['font_size'] . 'px;';
				 $style .= 'cursor:pointer;';

				 switch ( $options['theme'] ) {
					 case 'longbutton':
						 $style .= 'border-radius:' . $options['curve'] . 'px;';
						 break;
					 case 'circle':
					 case 'oval':
						 $style .= 'width:' . $options['size'] . 'px;';
						 $style .= 'height:' . $options['size'] . 'px;';
						 $style .= ( $options['theme'] === 'circle' ) ? 'border-radius:999px;' : 'border-radius:5px;';
						 break;
					 case 'square':
						 $style .= 'width:' . $options['size'] . 'px;';
						 $style .= 'height:' . $options['size'] . 'px;';
						 $style .= 'border-radius:0px;';
						 $style .= 'padding:0px;';
						 break;
				 }

				 // Construct the button HTML
				 $login_button = '<input type="button" name="kwsso_wp_sso_button" value="' . $button_text . '" style="' . $style . '"/>';
				 return $login_button;
		}
		/**
		 * Generate SAML Authentication Request.
		 *
		 * @param string $acs_url The Assertion Consumer Service (ACS) URL.
		 * @param string $issuer_id The issuer identifier.
		 * @param string $destination_url The destination URL.
		 * @param string $force_auth Flag to force authentication.
		 * @param string $binding_type SSO binding type.
		 * @param string $name_id_format SAML name ID format.
		 * @return string
		 */
		/**
		 * Checks the version of the plugin active with the mentioned name.
		 *
		 * @param string  $plugin_name     -   Plugin Name.
		 * @param integer $sequence       -   index of the version digit to get.
		 * @return integer  Version number.
		 */
		public static function get_active_plugin_version( $plugin_name, $sequence = 0 ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$all_plugins   = get_plugins();
			$active_plugin = get_kwsso_option( 'active_plugins' );
			foreach ( $all_plugins as $key => $value ) {
				if ( strcasecmp( $value['Name'], $plugin_name ) === 0 ) {
					if ( in_array( $key, $active_plugin, true ) ) {
						return (int) $value['Version'][ $sequence ];
					}
				}
			}
			return null;
		}
	}
}
