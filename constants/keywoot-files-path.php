<?php
/**
 * Defines the Constant class used throughout the plugin.
 *
 * @package keywoot-saml-sso\assets\lib
 */
// namespace KWSSO_CORE\Constants;

// use KWSSO_CORE\Constants\KwBaseConstant;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound -- Disabling this to define multiple constant classes in the same file.

require_once KWSSO_CONST_BASIC;

/**
 * Defines constants of the plugin files path required in the plugin.
 */
class KWSSO_FilePath extends KwBaseConstant {

	const SETTINGS_HELPER       = KWSSO_PLUGIN_DIR . 'src/main/helper/kwsso-functions.php';
	const KEYWOOT_UTILITIES     = KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-helper.php';
	const KEYWOOT_DISPLAY       = KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-display.php';
	const KWSSO_DEACTIVATE_FORM = KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-deactivate-form.php';
	const XPATH                   = KWSSO_PLUGIN_DIR . 'assets/packages/xml-sec/Utils/KWSSO_XPath.php';
	const PLUGIN_MAIN_FILE        = KWSSO_PLUGIN_DIR . 'keywoot-saml-sso.php';
	const WP_ADMIN_FILE           = ABSPATH . '/wp-admin/assets/file.php';
	const SSO_SERVICE             = KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwssosettings.php';
	const ADMIN_ACTION            = KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwadminactionservice.php';
	const ELEMENTS                = KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-elements.php';
	const KWSSO_ACTIVATION        = KWSSO_PLUGIN_DIR . 'src/main/admin/class-kwactivation.php';
	const KWSSO_UTILITY           = KWSSO_PLUGIN_DIR . 'src/main/helper/class-kwsso-utility.php';
	const KWSSO_ONLOAD            = KWSSO_PLUGIN_DIR . 'src/class-keywootonload.php';

}
