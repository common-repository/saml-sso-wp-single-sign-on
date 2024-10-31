<?php
/**
 * Plugin Name: Single Sign On for WP | Login With SSO
 * Plugin URI: http://keywoot.com
 * Description: KeyWoot SAML plugin allows sso/login using Azure, Azure B2C, Okta, ADFS, Keycloak, Onelogin, Salesforce, Google Apps (Gsuite), Salesforce, Shibboleth, Centrify, Ping, Auth0 and other Identity Providers. It acts as a SAML Service Provider which can be configured to establish a trust between the plugin and IDP to securely authenticate and login the user to WordPress site.
 * Version: 1.3.4
 * Author: keywoot
 * Author URI: http://keywoot.com
 * Text Domain: keywoot-saml-sso
 * Domain Path: /lang
 * WC requires at least: 2.0.0
 * WC tested up to: 8.3.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package keywoot-saml-sso
 */

use KWSSO_CORE\Src\KeywootOnload;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'before_woocommerce_init',
	function() {

		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );

		}

	}
);

define( 'KWSSO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KWSSO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KWSSO_PLUGIN_NAME', plugin_basename( __FILE__ ) );
define( 'KWSSO_DIR_NAME', substr( KWSSO_PLUGIN_NAME, 0, strpos( KWSSO_PLUGIN_NAME, '/' ) ) );
require_once 'src/kwsso-initializer.php';

