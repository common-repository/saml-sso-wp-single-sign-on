<?php


/**
 * Displays the general login page for Keycloak SSO with options for redirection, buttons, and links.
 *
 * This function retrieves the Single Sign-On (SSO) settings and displays the login page accordingly.
 * If the customer is registered with SAML, it displays redirection, button settings, and links.
 *
 * @param string $redirection_settings_hidden The CSS class for hiding/showing redirection settings.
 * @param string $button_settings_hidden       The CSS class for hiding/showing button settings.
 * @param string $links_settings_hidden        The CSS class for hiding/showing links settings.
 * @param bool   $is_prem                      Flag indicating whether the user has premium features.
 * @param bool   $disabled                     Flag indicating whether the SSO is disabled.
 *
 * @return void
 */

function kwsso_general_login_page( $redirection_settings_hidden, $button_settings_hidden, $links_settings_hidden, $is_prem, $disabled ) {
	$sp_base_url = get_kwsso_option( KwConstants::SP_BASE_URL );

	if ( empty( $sp_base_url ) ) {
		$sp_base_url = home_url();
	}
}

/**
 * Displays the link and widget view for configuring using shortcode and widget.
 *
 * This function outputs HTML elements for configuring the SSO using shortcode and widget.
 *
 * @param string $links_settings_hidden The CSS class for hiding/showing links settings.
 *
 * @return void
 */
function kwsso_link_and_widget_view() {
	$sp_base_url = get_kwsso_option( KwConstants::SP_BASE_URL ) ? get_kwsso_option( KwConstants::SP_BASE_URL ) : home_url();
	echo '	

<div id="links-settings-container" class="kw-subpage-container">
	<div class="kw-header">
	<p class="kw-heading flex-1">Links And Shortcodes</p>
</div>
<div class="border-b flex flex-col gap-kw-6 pb-kw-4 px-kw-4 ">         
	<div class="w-full"> 
	<h5 class="kw-title  m-kw-4"><b>Configure using shortcode</b></h5>

<div class="div-table-body">
<div class="kw-table">
	<div class="kw-row">
		<div class="kw-cell kw-link-name"><a class="kw-title text-primary text-blue-600">PHP Code</a></div>
		<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="config_sso_do_shortcode">do_shortcode(\'[KWSSO_SAML_SSO]\')</span></div>
		<div class="kw-cell kw-copy-icon copy-button">
			
			<button class="kw-button secondary" id="config_sso_do_shortcode_copy" onclick="kwCopyToClipboard(this, \'#config_sso_do_shortcode\', \'#config_sso_do_shortcode_copy\');"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"></path>
</svg>
Copy
			</button>
			<span class="tooltiptext">Copy To Clipboard</span>
		</div>
	</div>
	<div class="kw-row">
		<div class="kw-cell kw-link-name"><a class="kw-title text-primary text-blue-600">Shortcode for SSO</a></div>
		<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="config_sso_shortcode">[KWSSO_SAML_SSO]</span></div>
		<div class="kw-cell kw-copy-icon copy-button">
			<button class="kw-button secondary" id="config_sso_shortcode_copy" onclick="kwCopyToClipboard(this, \'#config_sso_shortcode\', \'#config_sso_shortcode_copy\');"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"></path>
</svg>
Copy
			</button>
			<span class="tooltiptext">Copy To Clipboard</span>
		</div>
	</div>
	  
<!-- Add more rows as needed -->
</div>
</div>
</div>
</div>

			</div>';
}


function kwsso_custom_sso_redirection_view( $is_prem, $disabled ) {
	$sp_base_url = get_kwsso_option( KwConstants::SP_BASE_URL ) ? get_kwsso_option( KwConstants::SP_BASE_URL ) : home_url();

	 echo '<div id="redirection-settings-container" class=" kw-subpage-container">
	 <div class="kw-header">
			<p class="kw-heading flex-1">Redirection Settings</p>
		</div>
	  <form id="kwsso_relay_state_form" method="post" action="">
	';
	wp_nonce_field( 'kwsso_relay_state_option' ); echo '<input type="hidden" name="option" value="kwsso_relay_state_option" />
	';

	show_premium_feature_notice( $is_prem );

	echo '
	
	<div class="border-b flex flex-col gap-kw-6 px-kw-4">
		<div class="w-full flex m-kw-4">
			<div class="flex-1 my-kw-6">
				<h5 class="kw-title">Redirection URLs</h5>
				<div class="">
						Enable the following option to send absolute URL in the Relay State (<i>For example: https://wp-site-url/sample-page</i>). <br />
						By default, the plugin sends relative URL in the Relay State (<i>For example: /sample-page</i>). <br />
						<br />
						<b>NOTE: </b> You can enable this option if you want to deep-link into a third party application.
				</div>
			</div>
			<div class="flex-1 px-kw-8 my-kw-6">
				<div class="my-kw-4 py-kw-2">
				<label class="kw-switch">
				<input type="checkbox"  >			
				<span class="kw-slider"></span>
			</label>
			<span class="px-kw-4"><b>Use Custom URLs for Redirection</b></span>
				</div>
				<div class="kw-input-wrapper my-kw-4">
					<label class="">Redirection Url After SSO</label>
					<input class="kw-input" type="url" style="width:90%;" placeholder="Enter a valid URL(Example : ' . esc_url( $sp_base_url ) . ')" value=""/>
				</div>
				<div class=" my-kw-4">
					Users will always be redirected to this URL after SSO.<br />
					When left blank, the users will be redirected to the same page from where the SSO was initiated.
				</div>
				<div class="kw-input-wrapper my-kw-4">
					<label class="">Redirection Url After Logout</label>
					<input type="url" class="kw-input" style="width:90%;" placeholder="Enter a valid URL (Example : ' . esc_url( $sp_base_url ) . ')" value=""/>
				</div>
				<div class=" my-kw-4">
					Users will always be redirected to this URL after logging out.<br />
					When left blank, the users will be redirected to the same page from where the SLO was initiated.
				</div>
				<input type="submit" class="kw-button primary" value="Update" ' . esc_attr( $disabled ) . '   class="button button-primary button-large"/>
			</div>
		</div>
	</div>
</form>';
	auto_redirection_from_site_view( $is_prem, $disabled );
	auto_redirection_from_admin_or_login_view( $is_prem, $disabled );
	echo ' </div>';

}


function auto_redirection_from_site_view( $is_prem, $disabled ) {
	echo '
	<div id="chosenOtpType" class="border-b px-kw-4">
			<div class="w-full flex m-kw-4">
				<div class="flex-1">
					<h5 class="kw-title">Auto-Redirection from site</h5>
					<div class=" mr-kw-16">Enable this option if you want to restrict your site to only logged in users. The users will be redirected to your IdP if logged in session is not found.</div>
					<div class="flex flex-col my-kw-6">       
					<h5 class="kw-title">Force authentication with your IdP on each login attempt</h5>
					<div class=" mr-kw-16">It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</div>
				</div>
					</div>
				<div class="flex-1">   
				<div class="flex flex-col gap-kw-8 py-kw-4"> 
				<form id="kwsso_registered_only_access_form" method="post" action="">';
				wp_nonce_field( 'kwsso_registered_only_access_option' );
				echo '<input type="hidden" name="option" value="kwsso_registered_only_access_option"/>
					<label class="kw-switch">
						<input type="checkbox" ' . esc_attr( $disabled ) . ' value="true" ';
												echo ' onchange="document.getElementById(\'kwsso_registered_only_access_form\').submit();"/>
						<span class="kw-slider"></span>
					</label>
					<span class="px-kw-4"><b>Redirect to IdP if user not logged in.</b></span>
				</form>
				</div>
				<div class="flex flex-col gap-kw-8 mb-kw-12"> 
				<form id="kwsso_registered_only_access_form" method="post" action="">';
				wp_nonce_field( 'kwsso_registered_only_access_option' );
				echo '<input type="hidden" name="option" value="kwsso_registered_only_access_option"/>
					<label class="kw-switch">
						<input type="checkbox" ' . esc_attr( $disabled ) . ' value="true" ';
												echo ' onchange="document.getElementById(\'kwsso_registered_only_access_form\').submit();"/>
						<span class="kw-slider"></span>
					</label>
					<span class="px-kw-4"><b>Always Redirect user to WP Login page if user not logged in</b></span>
				</form>
				</div>
				
				<div class="flex flex-col gap-kw-2 my-kw-8 py-kw-4">       
				<div class="">
				<form id="kwsso_force_authentication_form" method="post" action="">';
				wp_nonce_field( 'kwsso_force_authentication_option' );
				echo '<input type="hidden" name="option" value="kwsso_force_authentication_option"/>
						<label class="kw-switch">
						<input type="checkbox" ' . esc_attr( $disabled ) . ' name="kwsso_force_authentication" ';
								echo ' onchange="document.getElementById(\'kwsso_force_authentication_form\').submit();"/>
							<span class="kw-slider"></span>
						</label>
						<span class="px-kw-4"><b>Force authentication with your IdP on each login attempt</b></span>
				</form>
			</div>
							<div class="">
							<form id="kwsso_enable_rss_access_form" method="post" action="">';
				wp_nonce_field( 'kwsso_enable_rss_access_option' );
				echo '<input type="hidden" name="option" value="kwsso_enable_rss_access_option"/>
						<label class="kw-switch">
						<input type="checkbox" ' . esc_attr( $disabled ) . ' ';
											echo ' onchange="document.getElementById(\'kwsso_enable_rss_access_form\').submit();"/>
							<span class="kw-slider"></span>
						</label>
						<span class="px-kw-4"><b>Enable access to RSS Feeds</b></span>						
						</form></div>
			</div>     
			</div>
		</div>
	</div>';
}




function auto_redirection_from_admin_or_login_view( $is_prem, $disabled ) {
	echo '
	<div id="chosenOtpType" class="border-b px-kw-4">
	<div class="w-full flex m-kw-4">
        <div class="flex-1">
            <h5 class="kw-title">Auto Redirect From WordPress login Page</h5>
            <p class=" mr-kw-16">Enable this option if you want the users visiting any of the following URLs to get redirected to your configured IdP for authentication:
				</p>
        </div>
        <div class="flex-1">
		<div class="flex flex-col gap-kw-4 my-kw-4">
					<form id="kwsso_enable_redirect_form" method="post" action="">';
					wp_nonce_field( 'kwsso_enable_login_redirect_option' );
					echo '<input type="hidden" name="option" value="kwsso_enable_login_redirect_option"/>
					<label class="kw-switch">
					<input type="checkbox" ' . esc_attr( $disabled ) . ' ';
					echo ' onchange="document.getElementById(\'kwsso_enable_redirect_form\').submit();"/>
					<span class="kw-slider"></span>
					</label>
					<span class="px-kw-4"><b>Redirect to IdP from WordPress Login Page</b></span>
				</form>

				<form id="kwsso_allow_wp_signin_form" method="post" action="">';
					wp_nonce_field( 'kwsso_allow_wp_signin_option' );
					echo '<input type="hidden" name="option" value="kwsso_allow_wp_signin_option"/>
					<label class="kw-switch">
					<input type="checkbox" ' . esc_attr( $disabled ) . ' ';
						echo ' onchange="document.getElementById(\'kwsso_allow_wp_signin_form\').submit();"/>
					<span class="kw-slider"></span>
					</label><span class="px-kw-4"><b>Enable backdoor login</b></span>
					<div class="pr-kw-8 py-kw-4">
						<b>Backdoor URL:</b>
										<div class="">
						<div type="text" dissabled="" class="linkbtn flex"><p>' . site_url() . '/wp-login.php?saml_sso=<input style="width:120px" type="text" id="backdoor_url" name="kwsso_backdoor_url" disabled
						 value=""></p>
							</div>
						</div>
							</div>
										<div class="flex pr-kw-4 py-kw-4">
										<input type="submit" value="Update" ' . esc_attr( $disabled ) . ' class="kw-button primary" ';
	if ( ! kwsso_check_if_sp_configured() ) {
		echo ' disabled ';
	}
									echo '> 							
									<div class=" px-kw-4 kw-copy-icon copy-button">
									<i class="fas fa-copy"></i>
									<button type="button" class="kw-button secondary"  ' . esc_attr( $disabled ) . ' id="backdoor_url_copy" onclick="kwBackdoorCopy(this);" ><svg class="w-kw-icon h-kw-icon text-gray-800 " aria-hidden="true"  fill="none" viewBox="0 0 18 20">
									<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7.708 2.292.706-.706A2 2 0 0 1 9.828 1h6.239A.97.97 0 0 1 17 2v12a.97.97 0 0 1-.933 1H15M6 5v4a1 1 0 0 1-1 1H1m11-4v12a.97.97 0 0 1-.933 1H1.933A.97.97 0 0 1 1 18V9.828a2 2 0 0 1 .586-1.414l2.828-2.828A2 2 0 0 1 5.828 5h5.239A.97.97 0 0 1 12 6Z"/>
									</svg>Copy
									</button>
									<span class="tooltiptext">Copy To Clipboard</span>
								</div></div>
					</div>
					</form>
					<script>
					function kwBackdoorCopy(copyButton)
					{
						var temp = jQuery("<input>");
						jQuery("body").append(temp);
						temp.val("' . esc_url( site_url() ) . '/wp-login.php?saml_sso=" + jQuery("#backdoor_url").val()).select();
						document.execCommand("copy");
						temp.remove();
						jQuery(".tooltiptext").text("Copied");
						jQuery(copyButton).mouseout(function(){
							setTimeout(() => jQuery(".tooltiptext").text("Copy to Cipboard"), 100)
						});
	
					}
					</script>
              </div>     
        </div>
  </div>
	</div>';
}


