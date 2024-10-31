<?php
/**Load Interface KWSSO_Page
 *
 * @package keywoot-saml-sso/utility
 */

namespace KWSSO_CORE\Src\Utility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 *  This class is used to generate notification settings
	 *  specific to email or sms settings. These settings are then passed
	 *  to the cURL function to send notifications.
	 */
class KWSSO_Page {

	/**
	 * The page title
	 *
	 * @var string $kwsso_page_name
	 */
	public $kwsso_page_name;

	/**
	 * The menuSlug
	 *
	 * @var string $kwsso_menu_slug
	 */
	public $kwsso_menu_slug;


	/**
	 * The menu title
	 *
	 * @var string $kwsso_menu_name
	 */
	public $kwsso_menu_name;

	/**
	 * Tab Name
	 *
	 * @var String $kwsso_tab_name
	 */
	public $kwsso_tab_name;

	/**
	 * URL of the NavBar
	 *
	 * @var String $url
	 */
	public $url;

	/**
	 * The php page having the kwsso_layout
	 *
	 * @var String $kwsso_layout
	 */
	public $kwsso_layout;

	/**
	 * The php page having the kwsso_tab_icon
	 *
	 * @var String $kwsso_tab_icon
	 */
	public $kwsso_tab_icon;

	/**
	 * The ID attribute of the Tab
	 *
	 * @var String $id
	 */
	public $id;

	/**
	 * The Attribute which decides if this page should be shown
	 * in the Navbar
	 *
	 * @var bool $navbar_display
	 */
	public $navbar_display;

	/**
	 * The inline css to be applied to the main-navbar
	 *
	 * @var String
	 */
	public $css;
	/**
	 * Constructor.
	 *
	 * @param string $kwsso_page_name page title param.
	 * @param string $kwsso_menu_slug menu slug param.
	 * @param string $kwsso_menu_name meny title param.
	 * @param string $kwsso_tab_name tab name param.
	 * @param string $kwsso_tab_icon tab kwsso_tab_icon.
	 * @param string $kwsso_request_uri request url.
	 * @param string $kwsso_layout kwsso_layout page details.
	 * @param string $id id of page.
	 * @param string $css css of page .
	 * @param string $navbar_display check if need to shown in main-navbar.
	 */
	public function __construct( $kwsso_page_name, $kwsso_menu_slug, $kwsso_menu_name, $kwsso_tab_name, $kwsso_tab_icon, $kwsso_request_uri, $kwsso_layout, $id, $css = '', $navbar_display = true ) {
		$this->kwsso_page_name = $kwsso_page_name;
		$this->kwsso_menu_slug = $kwsso_menu_slug;
		$this->kwsso_menu_name = $kwsso_menu_name;
		$this->kwsso_tab_name  = $kwsso_tab_name;
		$this->kwsso_tab_icon  = $kwsso_tab_icon;
		$this->url             = add_query_arg( array( 'page' => $this->kwsso_menu_slug ), $kwsso_request_uri );
		$this->url             = remove_query_arg( array( 'subpage' ), $this->url );
		$this->kwsso_layout    = $kwsso_layout;
		$this->id              = $id;
		$this->navbar_display  = $navbar_display;
		$this->css             = $css;
	}
}

