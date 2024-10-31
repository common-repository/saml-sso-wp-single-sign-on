<?php
/**Load Abstract Class KWSSO_PluginSubTabs
 *
 * @package keywoot-saml-sso/utility
 */

namespace KWSSO_CORE\Src\Utility;

use KWSSO_CORE\Traits\Instance;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Subtab details class.
 */
	/**
	 * KWSSO_PluginSubTabs class
	 */
final class KWSSO_PluginSubTabs {

	use Instance;

	/**
	 * Array of KWSSO_SubPage Object detailing
	 * all the page menu options.
	 *
	 * @var array[KWSSO_SubPage] $sub_tab_details
	 */
	public $sub_tab_details;
	/**
	 * Array of KWSSO_SubPage Object detailing
	 * all the page menu options.
	 *
	 * @var array[KWSSO_SubPage] $settings_sub_tab_details
	 */
	public $settings_sub_tab_details;

	/**
	 * Array of KWSSO_SubPage Object detailing
	 * all the page menu options.
	 *
	 * @var array[KWSSO_SubPage] $settings_sub_tab_details
	 */
	public $saml_settings_sub_tab_details;


	/**
	 * The parent menu slug
	 *
	 * @var string $parent_slug
	 */
	public $parent_slug;

	/** Private constructor to avoid direct object creation */
	private function __construct() {
		$this->parent_slug = 'kwsso-main-settings';

		$sub_tab_list                        = array(
			'ATTRIBUTE_MAPPING' => 'attribute_mapping',
			'ROLE_MAPPING'      => 'role_mapping',
		);
		$this->saml_settings_sub_tab_details = array(
			$sub_tab_list['ATTRIBUTE_MAPPING'] => new KWSSO_SubPage(
				'Attribute Mapping',
				'Attribute Mapping',
				'Attribute Mapping',
				'kwsso-saml-settings.php',
				'attr-mapping',
				'background:#D8D8D8'
			),
			$sub_tab_list['ROLE_MAPPING']      => new KWSSO_SubPage(
				'Role Mapping',
				'Role Mapping',
				'Role Mapping',
				'kwsso-saml-settings.php',
				'role-mapping',
				'background:#D8D8D8'
			),
		);
		$this->sub_tab_details               = array(
			'kwsso-saml-settings' => $this->saml_settings_sub_tab_details,
		);
	}
}

