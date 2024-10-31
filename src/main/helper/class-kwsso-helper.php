<?php
/**
 * This file is part of keywoot SAML plugin.
 *
 * KeyWoot SAML plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * keywoot SAML plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with keywoot SAML plugin.  If not, see <http://www.gnu.org/licenses/>.
 */
use \RobRichards\XMLSecLibs\XMLSecurityKey;
use \RobRichards\XMLSecLibs\XMLSecurityDSig;
use \RobRichards\XMLSecLibs\XMLSecEnc;



/**
 * This class contains collections of various static functions used across the plugin.
 */

class KWSSO_Helper {

		/**
		 * Used for appending additional parameters in case of redirect binding.
		 *
		 * @param Array $request_object $_REQUEST Object.
		 * @return string
		 */
	public static function kwsso_append_redirect_binding_params( $request_object ) {
		$params = '';
		foreach ( $request_object as $key => $value ) {
			if ( 'option' !== $key && 'redirect_to' !== $key ) {
				$params .= '&' . $key . '=' . urlencode( $value );
			}
		}
		return $params;
	}


	/**
	 * Used for appending additional parameters in case of post binding.
	 *
	 * @param Array $request_object $_REQUEST Object.
	 * @return string
	 */
	public static function kwsso_append_post_binding_params( $request_object ) {
		$html = '';
		if ( ! empty( $request_object ) ) {

			foreach ( $request_object as $key => $value ) {
				if ( 'option' !== $key && 'redirect_to' !== $key ) {
					$html .= '<input type="hidden" name="' . $key . '" value="' . esc_attr( $value ) . '" />';
				}
			}
		}
		return $html;
	}

	public static function generate_saml_authentication_request( $acs_url, $issuer_id, $destination_url, $force_auth = 'false', $binding_type = 'HttpRedirect', $name_id_format = '' ) {
		$name_id_format_full = 'urn:oasis:names:tc:SAML:' . $name_id_format;
		$authn_request_xml   = '<?xml version="1.0" encoding="UTF-8"?>' .
						   '<samlp:AuthnRequest xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol" xmlns="urn:oasis:names:tc:SAML:2.0:assertion" ID="' . KWSSO_Utils::create_distinct_id() .
						   '" Version="2.0" IssueInstant="' . KWSSO_Utils::kwsso_create_timestamp() . '"';
		$authn_request_xml  .= ' ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" AssertionConsumerServiceURL="' . $acs_url .
						  '" Destination="' . htmlspecialchars( $destination_url ) . '"><saml:Issuer xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion">' . $issuer_id . '</saml:Issuer><samlp:NameIDPolicy AllowCreate="true" Format="' . $name_id_format_full . '"/></samlp:AuthnRequest>';

		$authn_request_xml = self::process_authn_request_xml( $binding_type, $authn_request_xml );
		return $authn_request_xml;
	}
	public static function process_authn_request_xml( $binding_type, $authn_request_xml ) {
		if ( empty( $binding_type ) || 'HttpRedirect' === $binding_type ) {
			$compressed_xml  = gzdeflate( $authn_request_xml );
			$encoded_xml_str = base64_encode( $compressed_xml );
			update_kwsso_option( KwConstants::SAML_REQUEST, $encoded_xml_str );
			$url_encoded_xml   = urlencode( $encoded_xml_str );
			$authn_request_xml = $url_encoded_xml;
		} else {
			$compressed_xml  = gzdeflate( $authn_request_xml );
			$encoded_xml_str = base64_encode( $compressed_xml );
			update_kwsso_option( KwConstants::SAML_REQUEST, $encoded_xml_str );
		}
		return $authn_request_xml;
	}

	/**
	 * Validates the signature in saml response.
	 *
	 * @param  array          $info Contains the signature Data.
	 * @param  XMLSecurityKey $key Used to verify the signature.
	 * @return void
	 */
	public static function kwsso_validate_signature( array $info, XMLSecurityKey $key ) {
		$xml_security = $info['Signature'];

		$signature_method = KWSSO_XMLHelper::keywoot_xpath_query( $xml_security->sigNode, './ds:SignedInfo/ds:SignatureMethod' );
		if ( empty( $signature_method ) ) {
			kwsso_exit_with_error( 'Missing SignatureMethod element' );
		}

		$sig_method = $signature_method[0];
		if ( ! $sig_method->hasAttribute( 'Algorithm' ) ) {
			kwsso_exit_with_error( 'Missing Algorithm-attribute on SignatureMethod element.' );
		}

		$algorithm = $sig_method->getAttribute( 'Algorithm' );

		if ( $key->type === XMLSecurityKey::RSA_SHA1 && $algorithm !== $key->type ) {
			$key = self::generate_key_with_algorithm( $key, $algorithm );
		}

		if ( ! $xml_security->verify( $key ) ) {
			kwsso_exit_with_error( 'Unable to validate Signature' );
		}
	}

	/**
	 * Return new Key with required algorithm and type, if needed.
	 *
	 * @param  XMLSecurityKey $key Instance of MoXMLSecurityKey.
	 * @param  string         $algorithm Contains Algorithm.
	 * @param  string         $type Algorithm type.
	 * @return Object
	 */
	public static function generate_key_with_algorithm( XMLSecurityKey $key, $algorithm, $type = 'public' ) {
		if ( $key->type === $algorithm ) {
			return $key;
		}
		$key_info = openssl_pkey_get_details( $key->key );
		if ( $key_info === false ) {
			kwsso_exit_with_error( KeywootMessage::NO_KEY_DETAIL );
		}
		if ( empty( $key_info['key'] ) ) {
			kwsso_exit_with_error( KeywootMessage::MISSING_KEY_DETAIL );
		}
		$new_key = new XMLSecurityKey( $algorithm, array( 'type' => $type ) );
		$new_key->loadKey( $key_info['key'] );

		return $new_key;
	}

	public static function check_assertion_response_validity( $current_url, $certificate_fingerprint, $kwsso_signature_details,
		KwResponseService $response, $kwsso_certificate_number, $kwsso_relay_state ) {

		$assertion                 = current( $response->kwsso_get_assertions() );
		$assertion_time_validation = get_kwsso_option( KwConstants::ASSERTION_TIME_VALIDATION );

		self::check_assertion_time_validity( $assertion, $assertion_time_validation );

		/* Validate Response-element destination. */
		$response_destination = $response->kwsso_get_destination();
		if ( substr( $response_destination, -1 ) == '/' ) {
			$response_destination = substr( $response_destination, 0, -1 );
		}
		if ( substr( $current_url, -1 ) == '/' ) {
			$current_url = substr( $current_url, 0, -1 );
		}

		if ( $response_destination !== null && $response_destination !== $current_url ) {
			echo 'Destination in response doesn\'t match the current URL. Destination is "' .
				 esc_attr( $response_destination ) . '", current URL is "' . esc_attr( $current_url ) . '".';
			exit;
		}

		$kwsso_signed_response = self::check_certificate_and_verify_signature( $certificate_fingerprint, $kwsso_signature_details, $kwsso_certificate_number, $kwsso_relay_state );

		/* Returning boolean $kwsso_signed_response */
		return $kwsso_signed_response;
	}

	public static function check_assertion_time_validity( $assertion, $assertion_time_validation ) {

		if ( empty( $assertion_time_validation ) || $assertion_time_validation == 'checked' ) {
			$kwsso_not_before_timestamp_restriction = $assertion->kwsso_get_not_before_timestamp_restriction();
			if ( $kwsso_not_before_timestamp_restriction !== null && $kwsso_not_before_timestamp_restriction > time() + 60 ) {
				KWSSO_Display::kwsso_die_and_display_error( KwErrorCodes::$error_codes['KWSSOERR007'] );
			}

			$kwsso_exipration_time_of_assertion = $assertion->get_exipration_time_of_assertion();
			if ( $kwsso_exipration_time_of_assertion !== null && $kwsso_exipration_time_of_assertion <= time() - 60 ) {
				KWSSO_Display::kwsso_die_and_display_error( KwErrorCodes::$error_codes['KWSSOERR008'] );
			}

			$kwsso_session_restriction_timestamp = $assertion->kwsso_get_session_restriction_timestamp();
			if ( $kwsso_session_restriction_timestamp !== null && $kwsso_session_restriction_timestamp <= time() - 60 ) {
				KWSSO_Display::kwsso_die_and_display_error( KwErrorCodes::$error_codes['KWSSOERR008'] );
			}
		}

	}

	public static function check_certificate_and_verify_signature( $certificate_fingerprint, $kwsso_signature_details, $kwsso_certificate_number, $kwsso_relay_state ) {
		$certificates = $kwsso_signature_details['Certificates'];

		if ( count( $certificates ) === 0 ) {
			$idp_saved_certificate = maybe_unserialize( get_kwsso_option( KwConstants::X509_CERTIFICATE ) );
			$certificate           = $idp_saved_certificate[ $kwsso_certificate_number ];
		} else {
			$certificate = self::find_and_fetch_certificate( array( $certificate_fingerprint ), $certificates, $kwsso_relay_state );
			if ( $certificate == false ) {
				return false;
			}
		}
		$key = new XMLSecurityKey( XMLSecurityKey::RSA_SHA1, array( 'type' => 'public' ) );
		$key->loadKey( $certificate );
		try {
			self::kwsso_validate_signature( $kwsso_signature_details, $key );
			return true;
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	/**
	 * This function checks if the received SAML Response contains the valid Audience URI.
	 *
	 * @param KwResponseService $saml_response The received SAML Response object.
	 * @param string            $sp_entity_id  The SP Entity ID value configured in the plugin.
	 * @return boolean
	 */
	public static function kwsso_is_valid_audience( $saml_response, $sp_entity_id ) {

		$assertion = current( $saml_response->kwsso_get_assertions() );
		$audiences = $assertion->kwsso_get_allowed_audiences();

		if ( ! empty( $audiences ) ) {
			if ( is_array( $audiences ) && in_array( $sp_entity_id, $audiences, true ) ) {
				return true;
			}
			return false;
		}
		return true;
	}



	public static function check_and_validate_issuer( $kwsso_saml_response, $actual_issuer, $kwsso_relay_state ) {
		$current_issuer = current( $kwsso_saml_response->kwsso_get_assertions() )->kwsso_get_saml_issuer();
		if ( strcmp( $actual_issuer, $current_issuer ) !== 0 ) {
			$error_code = KwErrorCodes::$error_codes['KWSSOERR010'];
			if ( KWSSO_Utils::check_if_test_configuration( $kwsso_relay_state ) ) {
				$display_metadata_mismatch = '<p><strong>Entity ID in SAML Response: </strong>' . esc_html( $current_issuer ) . '<p>
				<p><strong>Entity ID configured in the plugin: </strong>' . esc_html( $actual_issuer ) . '</p>';
				kwsso_display_test_error( $error_code, $display_metadata_mismatch );
			} else {
				KWSSO_Display::kwsso_die_and_display_error( $error_code );
			}
		}
	}

	private static function find_and_fetch_certificate( array $certificate_fingerprints, array $certificates, $kwsso_relay_state ) {

		$candidates = array();
		$error_code = KwErrorCodes::$error_codes['KWSSOERR013'];
		foreach ( $certificates as $cert ) {
			$fp = strtolower( sha1( base64_decode( $cert ) ) );
			if ( ! in_array( $fp, $certificate_fingerprints, true ) ) {
				$candidates[] = $fp;
				return false;
			}

			/* We have found a matching fingerprint. */
			$pem = "-----BEGIN CERTIFICATE-----\n" .
				chunk_split( $cert, 64 ) .
				"-----END CERTIFICATE-----\n";

			return $pem;
		}
		if ( KWSSO_Utils::check_if_test_configuration( $kwsso_relay_state ) ) {
			kwsso_display_test_error( $error_code );
		} else {
			KWSSO_Display::kwsso_die_and_display_error( $error_code );
		}
	}


	/**
	 * Posts the SAMLRequest if HTTP POST binding is selected.
	 *
	 * This method creates and auto-submits an HTML form with SAMLRequest,
	 * RelayState, and optionally the IdP name, to the specified URL. It's used
	 * when the HTTP POST binding is selected for SAML.
	 *
	 * @param string $url           The URL to post the SAML request.
	 * @param string $saml_request_xml The encoded SAMLRequest.
	 * @param string $kwsso_relay_state    The RelayState URL.
	 * @param array  $request_object Additional request parameters.
	 * @param string $idp_name       The name of the identity provider (IdP).
	 * @return void
	 */
	public static function kwsso_post_saml_request( $url, $saml_request_xml, $kwsso_relay_state, $request_object = array(), $idp_name = '' ) {
		echo '
		<html>
			<body>Please wait...
				<form action="' . esc_url( $url ) . '" method="post" id="saml-request-form">
					<input type="hidden" name="SAMLRequest" value="' . esc_attr( $saml_request_xml ) . '" />
					<input type="hidden" name="RelayState" value="' . esc_attr( $kwsso_relay_state ) . '" />';
					echo self::kwsso_append_post_binding_params( $request_object ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
					self::append_idp_name_field( $idp_name );
		echo '</form>
				<script>document.getElementById(\'saml-request-form\').submit();</script>
			</body>
		</html>';
		exit();
	}

	public static function append_idp_name_field( $idp_name ) {
		if ( ! empty( $idp_name ) ) {
			echo '	<input type="hidden" name="userName" value="' . esc_attr( $idp_name ) . '" />';
		}
	}

	/**
	 * Sanitizes a given SSL certificate string.
	 *
	 * This function performs several operations to prepare a raw SSL certificate string for usage.
	 * It trims whitespace from the certificate, removes any carriage return and newline characters,
	 * strips out any dashes, and removes the 'BEGIN CERTIFICATE' and 'END CERTIFICATE' labels.
	 * It also removes any spaces, and then reformats the certificate by chunking it into 64-character
	 * lines, finally re-adding the 'BEGIN CERTIFICATE' and 'END CERTIFICATE' labels appropriately.
	 *
	 * @param string $certificate The raw SSL certificate string to be sanitized.
	 * @return string The sanitized and properly formatted SSL certificate string.
	 */
	public static function kwsso_sanitize_certificate( $certificate ) {
		$certificate = trim( $certificate );
		$certificate = preg_replace( "/[\r\n]+/", '', $certificate );
		$certificate = str_replace( '-', '', $certificate );
		$certificate = str_replace( 'BEGIN CERTIFICATE', '', $certificate );
		$certificate = str_replace( 'END CERTIFICATE', '', $certificate );
		$certificate = str_replace( ' ', '', $certificate );
		$certificate = chunk_split( $certificate, 64, "\r\n" );
		$certificate = "-----BEGIN CERTIFICATE-----\r\n" . $certificate . '-----END CERTIFICATE-----';
		return $certificate;
	}
	public static function kwsso_xs_datetime_to_timestamp( $datetime ) {
		$matches = array();
		$regex   = '/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(?:\.\d+)?Z$/D';
		if ( preg_match( $regex, $datetime, $matches ) === 0 ) {
			wp_die( KeywootMessage::INVALID_TIMESTAMP . esc_html( $datetime ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
		}
		$variables = array( 'year', 'month', 'day', 'hour', 'minute', 'second' );
		foreach ( $variables as $index => $variable ) {
			${$variable} = intval( $matches[ $index + 1 ] );
		}
		$datetime_obj = DateTimeImmutable::createFromFormat( 'Y-m-d H:i:s', sprintf( '%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second ), new DateTimeZone( 'UTC' ) );
		return $datetime_obj->getTimestamp();
	}

	/**
	 * Makes a remote call using WordPress HTTP API.
	 *
	 * @param string $url The URL to which the request is to be sent.
	 * @param array  $args Optional. Request arguments.
	 * @param bool   $is_get Optional. Whether the request is a GET request. Default false.
	 *
	 * @return mixed The response body on success, false on failure.
	 */
	public static function keywoot_wp_remote_call( $url, $args = array(), $is_get = false ) {
		// Uncomment the following two lines while pointing to TEST
		// $array = array('sslverify' => false);
		// $args = array_merge($args, $array);
		if ( ! $is_get ) {
			$response = wp_remote_post( $url, $args );
		} else {
			$response = wp_remote_get( $url, $args );
		}

		if ( ! is_wp_error( $response ) ) {
			return $response['body'];
		} else {
			KWSSO_Display::kwsso_display_admin_notice( KeywootMessage::NO_INTERNET, KWSSO_ERROR );
			return false;
		}
	}

	public static function kwsso_validate_saml_response_for_reply_attack( $saml_response ) {
		$assertion = current( $saml_response->kwsso_get_assertions() );

		$not_on_or_after = current( $saml_response->kwsso_get_assertions() )->get_exipration_time_of_assertion();
		$assertion_id    = current( $saml_response->kwsso_get_assertions() )->kwsso_get_identifier();
		$expiry          = $not_on_or_after ? ( $not_on_or_after - time() ) + 300 : 15 * MINUTE_IN_SECONDS;
		if ( false === get_transient( $assertion_id ) ) {
			set_transient( $assertion_id, 'existed', $expiry );
		} else {
			throw new KwDuplicateSAMLResponseException( KeywootMessage::DUPLICATE_RESPONSE );// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception from plugin, escaping not needed.
		}
	}


}
