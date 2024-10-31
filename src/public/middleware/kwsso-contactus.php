<?php
/**
 * Loads contact us form.
 *
 * @package keywoot-saml-sso
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$current_user = wp_get_current_user();
$user_email   = $current_user->user_email;

