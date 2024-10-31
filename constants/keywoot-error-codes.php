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
 * Defines Error Codes used in the plugin.
 */
class KwErrorCodes extends KwBaseConstant {
	const ERROR_MESSAGE = 'Unable to process your request.';
	/**
	 * A map for error codes and their description.
	 *
	 * @var array
	 */
	public static $error_codes = array();
}