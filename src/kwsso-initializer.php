<?php
/**
 * Initializes plugin data.
 * Contains defination of common functions.
 *
 * @package keywoot-saml-sso
 */

use KWSSO_CORE\Src\AutoClassLoader;
use KWSSO_CORE\Src\KeywootOnload;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


kwsso_get_autoclassloader();

$plugin_data = array(
	'KWSSO_PLUGIN_VERSION' => '1.0',
	'KWSSO_TYPE'           => 'free',
	'KWSSO_SSL_VERIFY'     => false,
	'KWSSO_USE_POLYLANG'   => true,
	'KWSSO_HOST'           => 'https://keywoot.com',
	'KWSSO_CONST_BASIC'    => 'kwsso-base-constant.php',
);

$assets_const = array(
	'KWSSO_CSS_URL'      => 'assets/css/kw-custom-style.min.css',
	'KWSSO_JS_URL'       => 'assets/js/ssosettings.min.js',
	'KWSSO_ICON'         => 'assets/images/kw-icon-png.svg',
	'KEYWOOT_LOGO_WHITE' => 'assets/images/kw-logo-white.png',
	'KWSSO_MAIN_CSS'     => 'assets/css/kw-main.min.css',
);

$constants_files = array(
	'keywoot-sso-constants.php',
	'keywoot-sso-message-strings.php',
	'keywoot-error-codes.php',
	'keywoot-nameid-format.php',
	'keywoot-files-path.php',
	'keywoot-extra-constants.php',
);


foreach ( $plugin_data as $constant_name => $constant_value ) {
	define( $constant_name, $constant_value );
}
foreach ( $assets_const as $constant => $url ) {
	define( $constant, KWSSO_PLUGIN_URL . $url . '?version=' . KWSSO_PLUGIN_VERSION );
}

foreach ( $constants_files as $file ) {
	require_once KWSSO_PLUGIN_DIR . 'constants' . DIRECTORY_SEPARATOR . $file;
}

$file_path_array = array(
	KWSSO_FilePath::KEYWOOT_UTILITIES,
	KWSSO_FilePath::KWSSO_DEACTIVATE_FORM,
	KWSSO_FilePath::SETTINGS_HELPER,
	KWSSO_FilePath::SSO_SERVICE,
	KWSSO_FilePath::ADMIN_ACTION,
	KWSSO_FilePath::ELEMENTS,
	KWSSO_FilePath::KWSSO_ACTIVATION,
	KWSSO_FilePath::KWSSO_UTILITY,
	KWSSO_FilePath::KEYWOOT_DISPLAY,
	KWSSO_FilePath::KWSSO_ONLOAD,
);

foreach ( $file_path_array as $file_path ) {
	require_once $file_path;
}

/**
 * Get The AutoClassLoader
 *
 * @return void
 */
function kwsso_get_autoclassloader() {
	require KWSSO_PLUGIN_DIR . 'src/class-autoclassloader.php';

	$keywoot_class_loader = new AutoClassLoader( 'KWSSO_CORE', realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' ) );
	$keywoot_class_loader->register();
}
KeywootOnload::instance();
KWSSO_Activation::instance();
KWSSO_Utils::instance();

