<?php
/**
 * Defines the NameID Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once KWSSO_CONST_BASIC;
/**
 * Defines constants for NameID Formats in Service Provider Setup tab.
 */
class KwNameIdFormatConst extends KwBaseConstant {
	static $prefix    = 'urn:oasis:names:tc:SAML:';
	const EMAIL       = '1.1:nameid-format:emailAddress';
	const UNSPECIFIED = '1.1:nameid-format:unspecified';
	const TRANSIENT   = '2.0:nameid-format:transient';
	const PERSISTENT  = '2.0:nameid-format:persistent';
}
