<?php
/**Load Main File KeywootOnload
 *
 * @package keywoot-saml-sso-single-sign-on
 */

namespace KWSSO_CORE\Src;

use KWSSO_CORE\Src\Utility\KWSSO_Menu;

use KWSSO_CORE\Src\Utility\KWSSO_Page;
use KWSSO_CORE\Src\Utility\KWSSO_PluginTabs;
use KWSSO_CORE\Traits\Instance;
use KWSSO_CORE\Helper\KWSSO_CurlCall;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'KeywootOnload' ) ) {
	/**
	 * Final class that runs base functionalities of the plugin.
	 * It initializes some of the common helper and service for the plugin
	 * classes.
	 */
	final class KeywootOnload {

		use Instance;
		/** Constructor */
		private function __construct() {
			$this->kwsso_load_hooks();
		}


		/**
		 * Initialize all the main hooks needed for the plugin
		 */
		private function kwsso_load_hooks() {
			add_action( 'plugins_loaded', array( $this, 'kwsso_load_textdomain' ) );
			add_action( 'admin_menu', array( $this, 'load_keywoot_menu' ) );

			// // add_filter( 'plugin_row_meta', array( $this, 'kwsso_meta_links' ), 10, 2 );
			add_action( 'plugin_action_links_' . KWSSO_PLUGIN_NAME, array( $this, 'kwsso_plugin_action_links' ), 10, 1 );
			$this->kwsso_enqueue();

		}
		/**
		 * Initialize all the main hooks needed for the plugin
		 */
		private function kwsso_enqueue() {
			add_action(
				'admin_enqueue_scripts',
				function() {
					$this->kwsso_enqueue_plugin_settings_css();
					$this->kwsso_enqueue_plugin_settings_script();
				}
			);
			add_action( 'wp_enqueue_scripts', array( $this, 'kwsso_load_jquery' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_script' ), 99 );
			add_action( 'login_enqueue_scripts', array( $this, 'load_frontend_script' ), 99 );
		}

		/**
		 * Function to check if jQuery library is included, if not present then insert it.
		 * This was added to avoid conflicts with other scripts in WordPress all the while
		 * making sure our plugin is working as intended.
		 */
		public function kwsso_load_jquery() {
			if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery' );
			}
		}


		/**
		 * This function hooks into the admin_menu WordPress hook to generate
		 * WordPress menu items. You define all the options and links you want
		 * to show to the admin in the WordPress sidebar.
		 */
		public function load_keywoot_menu() {
			KWSSO_Menu::instance();
		}


		/**
		 * The main callback function for each of the menu links. This function
		 * is called when user visits any one of the menu URLs.
		 */
		public function kwsso_options() {
			include KWSSO_PLUGIN_DIR . 'src/public/middleware/kwsso-main-middleware.php';
		}


		/**
		 * This function is called to append our CSS file
		 * in the backend and frontend. Uses the admin_enqueue_scripts
		 * and enqueue_scripts WordPress hook.
		 */
		public function kwsso_enqueue_plugin_settings_css() {
			wp_enqueue_style( 'kwsso_admin_settings_style', KWSSO_CSS_URL, array(), KWSSO_PLUGIN_VERSION );
			$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the form name, doesn't require nonce verification.
			if ( strpos( $page, 'kwsso' ) === 0 || strpos( $_SERVER['SCRIPT_NAME'], 'plugins.php' ) !== false ) {
				wp_enqueue_style( 'kwsso_main_style', KWSSO_MAIN_CSS, array(), KWSSO_PLUGIN_VERSION );
			}
		}


		/**
		 * This function is called to append our CSS file
		 * in the backend and frontend. Uses the admin_enqueue_scripts
		 * and enqueue_scripts WordPress hook.
		 */
		public function kwsso_enqueue_plugin_settings_script() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'kwsso_admin_settings_color_script', KWSSO_PLUGIN_URL . 'assets/js/jscolor/jscolor.js', array(), KWSSO_PLUGIN_VERSION, false );
			wp_enqueue_script( 'kwsso_admin_settings_script', KWSSO_JS_URL, array( 'jquery' ), KWSSO_PLUGIN_VERSION, false );
		}


		/**
		 * This function is called to append certain javascripts
		 * to the frontend. Mostly used for the appending a country
		 * code dropdown to the phone number field.
		 */
		public function load_frontend_script() {
			wp_enqueue_script( 'jquery' );
		}

		/**
		 * Function tells where to look for translations.
		 * <b>PLEASE NOTE:</b> Dont be clever and try to replace the Text domain 'keywoot-saml-sso-single-sign-on'
		 * with a constant value. Its kept as string for a reason. Its so that other automated
		 * tools can read it and use it for automatic translation.
		 */
		public function kwsso_load_textdomain() {
			load_plugin_textdomain( 'keywoot-saml-sso', false, dirname( KWSSO_PLUGIN_NAME ) . '/lang/' );
		}


		/**
		 * Function hooks into the plugin_row_meta link to add custom
		 * links to the plugin's page.
		 *
		 * @param object $meta_fields .
		 * @param object $file .
		 * @return array
		 */
		public function kwsso_meta_links( $meta_fields, $file ) {
			if ( KWSSO_PLUGIN_NAME === $file ) {
				$meta_fields[] = "<span class='dashicons dashicons-sticky'></span>
            <a  target='_blank'>" . kwsso_lang_( 'FAQs' ) . '</a>';
			}
			return $meta_fields;
		}


		/**
		 * Add action links to the plugin list page for easy navigation
		 * after plugin activation.
		 *
		 * @param string $links .
		 * @return array
		 */
		public function kwsso_plugin_action_links( $links ) {
			$tab_details = KWSSO_PluginTabs::instance();
			$sp_tab      = $tab_details->tab_details['Service Provider'];
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			if ( is_plugin_active( KWSSO_PLUGIN_NAME ) ) {
				$links = array_merge(
					array(
						'<a href="' . esc_url( admin_url( 'admin.php?page=' . $sp_tab->kwsso_menu_slug ) ) . '">' .
							kwsso_lang_( 'Settings' )
						. '</a>',
					),
					$links
				);
			}
			return $links;
		}
	}
}
