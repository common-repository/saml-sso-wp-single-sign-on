<?php
/**Load adminstrator changes for KWSSO_CurlCall
 *
 * @package keywoot-saml-sso/helper
 */

namespace KWSSO_CORE\Src\Main\Helper;

use KWSSO_CORE\Src\Utility\NotificationSettings;
use KWSSO_CORE\Src\Utility\VerificationType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class denotes all the API related functions to make API calls
 */
class KWSSO_CurlCall {


	/**
	 * Makes an HTTP request to the given URL.
	 *
	 * @param string $url URL to post to.
	 * @param array  $body The post body data.
	 * @param array  $headers Optional. HTTP headers.
	 * @param string $method Optional. HTTP method. Default 'POST'.
	 * @return string|false The response body on success, false on failure.
	 */
	public static function kwsso_curl_call( $url, $body, $headers = array(), $method = 'POST' ) {
	}

}
