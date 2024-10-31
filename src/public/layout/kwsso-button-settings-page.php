<?php
/**
 * Loads the UI for Button settings tab
 *
 * @param string $button_settings_hidden tab hidden var.
 * @return void
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function kwsso_load_button_settings_page( $is_prem, $disabled ) {

	$button_theme  = 'longbutton';
	$button_size   = '50';
	$button_width  = '270';
	$button_height = '30';
	$button_curve  = '3';

	$saml_idp_name       = get_kwsso_option( KwConstants::KWSSO_IDP_NAME );
	$button_text         = get_kwsso_option( KwConstants::SSO_BUTTON_TEXT ) ? get_kwsso_option( KwConstants::SSO_BUTTON_TEXT ) : ( $saml_idp_name ? 'Login with ' . $saml_idp_name . '' : 'Login' );
	$button_color        = '#2563EB';
	$font_size           = '14';
	$font_color          = '#ffffff';
	$sso_button_position = 'above';

	echo ' <div id="button-settings-container" class="kw-subpage-container">
    <div class="kw-header">
			<p class="kw-heading flex-1">Button and Customization</p>
		</div>
    <div class="border-b px-kw-8 mt-kw-4">
        <div class="w-full flex my-kw-4">
            <div class="flex-1">
                <h5 class="kw-title">Login Button Settings</h5>
                     <div class="flex flex-col gap-kw-4 my-kw-4">
                    <div>
                        <form id="kwsso_add_sso_button_wp_form" method="post" action="">';
						wp_nonce_field( 'kwsso_add_sso_button_option' );
						echo '<input type="hidden" name="option" value="kwsso_add_sso_button_option"/>
                            <p>
                                <label class="kw-switch">
                                    <input type="checkbox" name="kwsso_add_sso_button_wp"  value="true"';
									checked( get_kwsso_option( KwConstants::ADD_SSO_BUTTON ) == 'true' );
									echo ' onchange="document.getElementById(\'kwsso_add_sso_button_wp_form\').submit();"/>
                                    <span class="kw-slider"></span>
                                </label>
                                <span class="px-kw-4"><b>Add a Single Sign on button on the WordPress login page</b></span>
                            </p>
                        </form>
                    </div>
                    <div>
                        <form id="kwsso_use_button_as_shortcode_form" method="post" action="">';
							wp_nonce_field( 'kwsso_button_as_shortcode_option' );
							echo '<input type="hidden" name="option"  value="kwsso_button_as_shortcode_option"/>
                            <p>
                                <label class="kw-switch">
                                    <input type="checkbox" name="kwsso_use_button_as_shortcode"  value="true"';
									checked( get_kwsso_option( KwConstants::USE_BUTTON_AS_SHORTCODE ) == 'true' );
									echo ' onchange="document.getElementById(\'kwsso_use_button_as_shortcode_form\').submit();"/>
                                    <span class="kw-slider"></span>
                                </label>
                                <span class="px-kw-4"><b>Use button as ShortCode</b></span>
                            </p>
                        </form>
                    </div>
                    <div>
                    </div>
                </div>    
            </div>
            <div class="flex-1 mt-kw-4">
            
            </div>
        </div>
    </div>';
}
