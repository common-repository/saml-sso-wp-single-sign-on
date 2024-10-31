<?php
/**Load adminstrator changes for KWSSO_Menu
 *
 * @package keywoot-saml-sso/helper
 */

namespace KWSSO_CORE\Src\Utility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use KWSSO_CORE\Src\KeywootOnload;
use KWSSO_CORE\Src\Utility\KWSSO_PluginTabs;
use KWSSO_CORE\Traits\Instance;
use KWSSO_CORE\Constants;
/**
 * This class simply adds menu items for the plugin
 * in the WordPress dashboard.
 */
if ( ! class_exists( 'KWSSO_Menu' ) ) {
	/**
	 * KWSSO_Menu class
	 */
	final class KWSSO_Menu {

		use Instance;

		/**
		 * The URL for the plugin kwsso_tab_icon to be shown in the dashboard
		 *
		 * @var string
		 */
		private $callback;

		/**
		 * The call back function for the menu items
		 *
		 * @var string
		 */
		private $kwsso_menu_slug;

		/**
		 * The slug for the main menu
		 *
		 * @var string
		 */
		private $menu_logo;

		/**
		 * Array of KWSSO_Page Object detailing
		 * all the page menu options.
		 *
		 * @var array $tab_details
		 */
		private $tab_details;

		/**
		 * KWSSO_Menu constructor.
		 */
		private function __construct() {
			$this->kwsso_initialize_properties();
			$this->kwsso_add_main_menu();
			$this->kwsso_add_sub_menus();
		}
		/**
		 * Initialize Properties.
		 */
		private function kwsso_initialize_properties() {
			$this->callback        = array( KeywootOnload::instance(), 'kwsso_options' );
			$this->menu_logo       = KWSSO_ICON;
			$tab_details           = KWSSO_PluginTabs::instance();
			$this->tab_details     = $tab_details->tab_details;
			$this->kwsso_menu_slug = $tab_details->parent_slug;
		}
		/**
		 * Adding MainMenu.
		 */
		private function kwsso_add_main_menu() {
			add_menu_page(
				'WP-SSO',
				'WP-SSO',
				KWSSO_MANAGE_OPTIONS,
				$this->kwsso_menu_slug,
				$this->callback,
				'dashicons-shield',
			);
		}
		/**
		 * Adding MainMenu.
		 */
		private function kwsso_add_sub_menus() {
			foreach ( $this->tab_details as $tab_detail ) {
				add_submenu_page(
					$this->kwsso_menu_slug,
					$tab_detail->kwsso_page_name,
					$tab_detail->kwsso_menu_name,
					KWSSO_MANAGE_OPTIONS,
					$tab_detail->kwsso_menu_slug,
					$this->callback
				);
			}
		}
	}
}
