<?php
/**
 * Load admin view for Account Tab.
 *
 * @package keywoot-saml-sso/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '	 
<div id="tab" class="kw-sidenav-container kw-main-content">';

foreach ( $tab_details->tab_details as $kwtabs ) {
	if ( $kwtabs->navbar_display ) {
		echo '<a  class="kw-sidenav-item 
                        ' . ( $active_tab === $kwtabs->kwsso_menu_slug ? 'kw-sidenav-item-active' : '' ) . '" 
                        href="' . esc_url( $kwtabs->url ) . '"
                        id="' . esc_attr( $kwtabs->id ) . '">
                        ' . esc_attr( $kwtabs->kwsso_tab_name ) . '
                    </a>';
	}
}
echo '
        </div>';
