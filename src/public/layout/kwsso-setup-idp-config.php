<?php

	/**
	 * Displays UI for service provider setup tab.
	 *
	 * @return void
	 */
function kwsso_apps_config_saml( $auto_config_hidden, $manual_config_hidden ) {
	$kwsso_saml_identity_name = get_kwsso_option( KwConstants::KWSSO_IDP_NAME );
	$kwsso_saml_idp_key = get_kwsso_option( KwConstants::KWSSO_IDP_KEY )?get_kwsso_option( KwConstants::KWSSO_IDP_KEY ):'custom-idp';


	$sync_interval                  = get_kwsso_option( KwConstants::METADATA_SYNC_INTERVAL );
	$sync_url                       = get_kwsso_option( KwConstants::METADATA_SYNC_URL );
	$sync_selected                  = ! empty( $sync_interval ) ? 'checked' : '';
	$hidden                         = empty( $sync_interval ) ? 'hidden' : '';
	$kwsso_saml_login_binding_type        = get_kwsso_option( KwConstants::LOGIN_BINDING_TYPE );
	$kwsso_saml_login_url                 = get_kwsso_option( KwConstants::LOGIN_URL );
	$kwsso_saml_login_url                 = ! is_array( $kwsso_saml_login_url ) ? htmlspecialchars_decode( $kwsso_saml_login_url ) : $kwsso_saml_login_url;
	$kwsso_saml_issuer                    = get_kwsso_option( KwConstants::ISSUER );
	$kwsso_saml_x509_certificate          = get_kwsso_option( KwConstants::X509_CERTIFICATE );
	$kwsso_saml_x509_certificate          = ! is_array( $kwsso_saml_x509_certificate ) ? maybe_unserialize( htmlspecialchars_decode( $kwsso_saml_x509_certificate ) ) : $kwsso_saml_x509_certificate;
	$kwsso_saml_x509_certificate          = is_array( $kwsso_saml_x509_certificate ) ? $kwsso_saml_x509_certificate : array( 0 => $kwsso_saml_x509_certificate );
	$kwsso_saml_request_signed            = ! empty( get_kwsso_option( KwConstants::REQUEST_SIGNED ) ) ? get_kwsso_option( KwConstants::REQUEST_SIGNED ) : 'unchecked';
	$kwsso_saml_nameid_format             = get_kwsso_option( KwConstants::NAMEID_FORMAT );
	$saml_is_encoding_enabled       = ! empty( get_kwsso_option( KwConstants::IS_ENCODING_ENABLED ) ) ? get_kwsso_option( KwConstants::IS_ENCODING_ENABLED ) : 'checked';
	$saml_assertion_time_validation = get_kwsso_option( KwConstants::ASSERTION_TIME_VALIDATION, 'checked' );

	if ( 'checked' === $saml_assertion_time_validation ) {
		$saml_assertion_time_validation = 'checked';
		update_kwsso_option( KwConstants::ASSERTION_TIME_VALIDATION, 'checked' );
	} else {
		$saml_assertion_time_validation = 'unchecked';
		update_kwsso_option( KwConstants::ASSERTION_TIME_VALIDATION, 'unchecked' );
	}
	echo '	<form name="saml_form" id="auto-config-container" method="post" action="" class="kw-subpage-container ' . esc_attr( $auto_config_hidden ) . '" enctype="multipart/form-data" >
				<div class=" flex flex-col gap-kw-6 px-kw-4">
					<div class="w-full flex m-kw-4">
						<div class="" style="width:60%">
<div class="flex-1">
						<div  class="w-[95%] py-kw-4 pr-kw-4">
						<input class="w-full kw-input hidden" type="text" name="kwsso_saml_idp_key" required  style="width: 95%;" value="' . esc_attr( $kwsso_saml_idp_key ) . '" />

							<div class="kw-input-wrapper">
								<label class="">' . __( 'IDP Name', 'keywoot-saml-sso' ) . '</label>
								<input class="w-full kw-input" type="text" name="saml_identity_provider_name" required placeholder="Identity Provider name like ADFS, SimpleSAML" style="width: 95%;" value="' . esc_attr( $kwsso_saml_identity_name ) . '" />
							</div>';
						wp_nonce_field( 'kwsso_upload_or_fetch_metadata' );
					echo ' <input type="hidden" name="option" value="kwsso_upload_or_fetch_metadata" />
							<input type="hidden" name="action" value="fetch_metadata" />
							
							<div class="kw-input-wrapper mt-kw-6">
								<label class="">' . __( 'Enter IDP MetaData URL', 'keywoot-saml-sso' ) . '</label>
								<input class="w-full kw-input" type="url" name="metadata_url"  placeholder="' . __( 'Enter metadata URL of your IdP.', 'keywoot-saml-sso' ) . '" style="width:95%" value="' . esc_url( $sync_url ) . '" />
							</div>

							<div id="select_time_sync_metadata_1"  ' . esc_html( $hidden ) . '   class="kw-input-wrapper mt-kw-4">' . kwsso_display_dropdown_for_cron_interval_selection( $sync_interval ) . '</div> '; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML from plugin.

					echo '	<div class="kw-input-wrapper mt-kw-6">
								<button class="kw-button primary" type="submit"  value="Fetch & Save Metadata" />
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" ><path d="M3 11H4C8.97056 11 13 15.0294 13 20V21M3 7H4C11.1797 7 17 12.8203 17 20V21M3 3H4C13.3888 3 21 10.6112 21 20V21M8 18.5C8 19.8807 6.88071 21 5.5 21C4.11929 21 3 19.8807 3 18.5C3 17.1193 4.11929 16 5.5 16C6.88071 16 8 17.1193 8 18.5Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
									Fetch & Save Metadata
									<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
										<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
										<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
									</svg>
								</button>
							</div>

							<div class="svg-icon">
								<svg width="40" height="40" >
									<circle cx="20" cy="20" r="18" fill="black" />
									<text x="50%" y="50%" text-anchor="middle" alignment-baseline="central" font-family="Arial" font-size="16" fill="white">or</text>
								</svg>
							</div>
							<input type="hidden" name="option" value="kwsso_upload_or_fetch_metadata" />
							<input type="hidden" name="action" value="upload_metadata" />
							<div class="kw-input-wrapper mt-kw-6">
								<input class="w-full kw-input" id="upload_idp_metadata_actual" hidden type="file"  name="idp_metadata_file" />
							</div>

							<div class="flex items-center kw-select-container py-kw-2 px-kw-4 h-[56px] pr-kw-1" style="width: 95%;">
								<div class="grow">
									<h6 id="choosen_file_id" class="m-kw-0">No file Choosen</h6>
								</div>
								<button type="button" id="upload_idp_metadata" name="kwsso_request_type" class="kw-button secondary"> 
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" ><path d="M9 13L10.5858 11.4142C11.3668 10.6332 12.6332 10.6332 13.4142 11.4142L15 13M12 11V16M22 10V17C22 19.2091 20.2091 21 18 21H6C3.79086 21 2 19.2091 2 17V7C2 4.79086 3.79086 3 6 3H8.66667C9.53215 3 10.3743 3.28071 11.0667 3.8L12.9333 5.2C13.6257 5.71929 14.4679 6 15.3333 6H18C20.2091 6 22 7.79086 22 10Z" stroke="#28303F" stroke-width="2" stroke-linecap="round"/></svg>
									Choose File 
								</button>
							</div>

							<div class="kw-input-wrapper mt-kw-6">
								<button class="kw-button primary" type="submit"  class="button button-primary button-large" value="Upload Metadata File" />
									<svg width="19" height="19" viewBox="0 0 24 24" fill="none" >
										<path d="M9 6L12 3M12 3L15 6M12 3L12 15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M7.5 9L7 9C4.79086 9 3 10.7909 3 13L3 17C3 19.2091 4.79086 21 7 21L17 21C19.2091 21 21 19.2091 21 17L21 13C21 10.7909 19.2091 9 17 9L16.5 9" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg> Upload Metadata File
									<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
									<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
									<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
								</svg>
								</button>
							</div>
						</div>
					</div>

						</div>

					
					
				</div>
			</div>
		</div>
	</form>';
	echo '<div>
		<div id="manual-config-container" class="kw-subpage-container ' . esc_attr( $manual_config_hidden ) . '" >
			<h5 class="kw-title px-kw-6">Configure Identity Provider Manually:</h5>

				<form width="100%" style="padding-left:2rem; padding-right:2rem" name="saml_form" method="post" action="">';
		wp_nonce_field( 'kwsso_save_idp_configurations' );
		echo '<input type="hidden" name="option" value="kwsso_save_idp_configurations" />
					<div class="idp-config-table-wrapper">
											<input class="w-full kw-input hidden" type="text" name="kwsso_saml_idp_key" required  style="width: 95%;" value="' . esc_attr( $kwsso_saml_idp_key ) . '" />

						<table style="width:100%;" id="service_provider_setup" class="box">
								<tr>
									<td class="kw-title text-primary text-blue-600" style="width:200px;">
										Identity Provider Name 
										<span style="color:red;">*</span>:
									</td>
									<td>
										<input class="w-full kw-input" type="text" required name="kwsso_saml_identity_name" placeholder="Identity Provider name like ADFS, SimpleSAML, Salesforce" style="width: 95%;" value="' . esc_attr( $kwsso_saml_identity_name ) . '" required
											pattern="^0[_a-zA-Z0-9]+$|^[_a-zA-Z1-9]+[_a-zA-Z0-9]*$" title="Only alphabets, numbers and underscore are allowed. No whitespace is allowed."  />
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="kw-title text-primary text-blue-600" style="width:200px;">
										IdP Entity ID or Issuer 
											<span style="color:red;">*</span>:
									</td>
									<td>
										<input class="w-full kw-input" type="text" name="kwsso_saml_issuer" placeholder="Identity Provider Entity ID or Issuer" style="width: 95%;" value="' . esc_url( $kwsso_saml_issuer ) . '" required  />
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="kw-title text-primary text-blue-600" style="width:200px;">
										SAML Login URL 
											<span style="color:red;">*</span>:
									</td>
									<td>
										<input class="w-full kw-input" type="url" name="kwsso_saml_login_url" placeholder="Single Sign On Service URL of your IdP" 
											style="width: 95%;" value="' . esc_url( $kwsso_saml_login_url ) . '" required  />
									</td>
								</tr>
								
								<tr>
									<td>&nbsp;</td>
								</tr>		
							<tr>
								<td class="kw-title text-primary text-blue-600" style="width:300px;">
									Choose SSO Binding Type:
								</td>
								<td>';
								echo '
								<input type="radio"  name="kwsso_saml_login_binding_type" id="sso-http-redirect" value="HttpRedirect"';
	if ( $kwsso_saml_login_binding_type == 'HttpRedirect' || empty( $kwsso_saml_login_binding_type ) ) {
		echo ' checked="checked"';
	}
							echo '/>
								<label for="sso-http-redirect">Use HTTP-Redirect Binding for SSO</label>';

							echo '
								<input style="margin-left:15px;" type="radio" name="kwsso_saml_login_binding_type" id="sso-http-post" value="HttpPost"';
	if ( $kwsso_saml_login_binding_type == 'HttpPost' ) {
		echo ' checked="checked"';
	}
							echo '/>
								<label for="sso-http-post">Use HTTP-POST Binding for SSO</label>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>';
	echo'
		
									<tr>
										<td>&nbsp;</td>
										<td>
											<br />
											<div class="flex">
												<button class="kw-button primary" name="submit" type="submit" />
													Save Configurations
													<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
														<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
														<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
													</svg>
												</button>
												<input type="button" class="kw-button secondary" name="test" title="You can only test your Configuration after saving your Service Provider Settings." onclick="showTestWindow();"';
	if ( ! kwsso_check_if_sp_configured() || ! get_kwsso_option( KwConstants::X509_CERTIFICATE ) ) {
		echo 'disabled';
	}
		echo ' value="Test configuration" class="button button-primary button-large" style="margin-left: 4%;width:150px;"/>
											</div>
										</td>
									</tr>
									<tr>';
		echo '
										<tr>
											<td></td>
											<td>
												<br />
											</td>
										</tr>
									</table>
								</div>
								<br/>
							</form>
				</div>
			</div>

	<div>
		<form method="get" target="_blank" action="" id="getIDPguides"></form>
		<form method="post" action="" name="kwsso_export" id="kwsso_export">';
								wp_nonce_field( 'kwsso_export' );
								echo '<input type="hidden" name="option" value="kwsso_export" /></form></div>
		<script>
			function showTestWindow() {
				var myWindow = window.open("' . esc_url( kwsso_get_test_url() ) . '", "TEST SAML IDP", "scrollbars=1 width=800, height=600");
			}

			function redirect_to_attribute_mapping(){
				window.location.href= "' . esc_url( kwsso_get_attribute_mapping_url() ) . '";
			}
		</script>';
}

