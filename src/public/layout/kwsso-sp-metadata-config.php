<?php
/**
 * Load admin view for settings of Configured Forms.
 *
 * @package keywoot-saml-sso/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	echo '
		<div class="kw-header">
			<p class="kw-heading flex-1">Configure your Identity Provider</p>
		</div>

		<div class="border-b px-kw-4" >
			<div class="w-full flex m-kw-4">
				<div class="" style="width:45% !important">
					<h5 class="kw-title">Provide this metadata URL to your Identity Provider</h5>
					<div id="blocked_email_settings" class="w-[95%] py-kw-4 pr-kw-2">
					    <div class="">
							<div type="text" dissabled class="linkbtn flex w-[125%]" name="kwsso_sp_base_url" ><a class=" p-kw-2 w-full kw-rounded-smooth" id="metadata_url" target="_blank" href="' . esc_url( $metadata_url ) . '"  >' . esc_url( $sp_base_url ) . '/?option=kw-metadata</a><span class="kw-copyicon" id="metadata_url_copy" onclick="kwCopyToClipboard(this, \'#metadata_url\', \'#metadata_url_copy\');" ><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
							<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
							</svg>
							<span hidden id="showcopied">Copied</span>
							</span>
							</div>
						</div>
					</div>
				</div>

				<div class="svg-icon">
  					<svg width="40" height="40" >
  					  <circle cx="20" cy="20" r="18" fill="black" />
   						 <text x="50%" y="50%" text-anchor="middle" alignment-baseline="central" font-family="Arial" font-size="16" fill="white">or</text>
  					</svg>
				</div>

				<div class="" style="width:50% !important">
					<div id="blocked_email_settings" class="pl-kw-8">
						<h5 class="kw-title">Download the Plugin XML metadata and upload it on your Identity Provider</h5>
						<form class="py-kw-4" method="post" action="">';
						wp_nonce_field( 'kwsso_sp_metadata_dowload' );
						echo '<input type="hidden" name="option" value="kwsso_sp_metadata_dowload"/> 
							<button type="submit" class="kw-button primary"  value="Download XML Metadata"  />
							<svg class="w-kw-icon h-kw-icon" aria-hidden="true"  fill="currentColor" viewBox="0 0 20 20">
								<path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
								<path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
							</svg>Download XML Metadata
							<svg name="button-loader" style="display:none" width="18" height="18" aria-hidden="true" role="status" class="inline me3 text-white animate-spin" viewBox="0 0 100 101" fill="none" >
							<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
							<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
						    </svg>
							<button>
						</form>
					</div>
				</div>
			</div>	
		</div>
		<input type="hidden" name="option" value="kwsso_idp_config" />';

echo '<div class=" flex flex-col gap-kw-6 px-kw-4">
			<div class="w-full flex m-kw-4 ">
				<div class="flex-1">
					<h5 class="kw-title "> Provide the following information to your Identity Provider Manually.</h5>
				</div>
				<div class="flex-1">
				</div>
			</div>
	  </div>				
	<div class="div-table-body">
		<div class="kw-table">
			<div class="kw-row">
				<div class="kw-cell kw-name"><a class="kw-title text-primary text-blue-600">SP-EntityID / Issuer</a></div>
				<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="entity_id">' . esc_html( $sp_entity_id ) . '</span></div>
				<div class="kw-cell kw-copy-icon copy-button">
					
					<button class="kw-button secondary" id="entity_id_copy" onclick="kwCopyToClipboard(this, \'#entity_id\', \'#entity_id_copy\');" ><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
</svg>
Copy
					</button>
					<span class="tooltiptext">Copy To Clipboard</span>
				</div>
			</div>
			<div class="kw-row">
				<div class="kw-cell kw-name"><a class="kw-title text-primary text-blue-600">ACS (AssertionConsumerService) URL</a></div>
				<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="base_url">' . esc_url( $sp_base_url ) . '/</span></div>
				<div class="kw-cell kw-copy-icon copy-button">
					
					<button class="kw-button secondary" id="base_url_copy" onclick="kwCopyToClipboard(this, \'#base_url\', \'#base_url_copy\');"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
</svg>
Copy
					</button>
					<span class="tooltiptext">Copy To Clipboard</span>
				</div>
			</div>
			<div class="kw-row">
				<div class="kw-cell kw-name"><a class="kw-title text-primary text-blue-600">Single Logout URL</a></div>
				<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="slo_url">' . esc_url( $sp_base_url ) . '/</span></div>
				<div class="kw-cell kw-copy-icon copy-button">
					
					<button class="kw-button secondary" id="slo_url_copy" onclick="kwCopyToClipboard(this, \'#slo_url\', \'#slo_url_copy\');"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
</svg>
Copy
					</button>
					<span class="tooltiptext">Copy To Clipboard</span>
				</div>
			</div>
			<div class="kw-row">
				<div class="kw-cell kw-name"><a class="kw-title text-primary text-blue-600">Audience URI</a></div>
				<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="audience_uri">' . esc_html( $sp_entity_id ) . '</span></div>
				<div class="kw-cell kw-copy-icon copy-button">
					
					<button class="kw-button secondary " id="audience_uri_copy" onclick="kwCopyToClipboard(this, \'#audience_uri\', \'#audience_uri_copy\');"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
</svg>
Copy
					</button>
					<span class="tooltiptext">Copy To Clipboard</span>
				</div>
			</div>
			<div class="kw-row">
				<div class="kw-cell kw-name"><a class="kw-title text-primary text-blue-600">NameID format</a></div>
				<div class="kw-cell kw-url"><span class="kw-link-wrapper" id="nameid">urn:oasis:names:tc:SAML:' . esc_html( $kwsso_saml_nameid_format ) . '</span></div>
				<div class="kw-cell kw-copy-icon copy-button">
					
					<button class="kw-button secondary" id="nameid_copy" onclick="kwCopyToClipboard(this, \'#nameid\', \'#nameid_copy\');"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" >
<path d="M17 6L17 14C17 16.2091 15.2091 18 13 18H7M17 6C17 3.79086 15.2091 2 13 2L10.6569 2C9.59599 2 8.57857 2.42143 7.82843 3.17157L4.17157 6.82843C3.42143 7.57857 3 8.59599 3 9.65685L3 14C3 16.2091 4.79086 18 7 18M17 6C19.2091 6 21 7.79086 21 10V18C21 20.2091 19.2091 22 17 22H11C8.79086 22 7 20.2091 7 18M9 2L9 4C9 6.20914 7.20914 8 5 8L3 8" stroke="#28303F" stroke-width="1.5" stroke-linejoin="round"/>
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
	<div class="my-kw-6 border-t">			
		<form width="98%" border="0" method="post" id="kwsso_update_idp_settings_form" action="">';
			wp_nonce_field( 'kwsso_sp_core_config' );
echo '      <input type="hidden" name="option" value="kwsso_sp_core_config" />
				<div class=" flex flex-col gap-kw-6 mt-kw-4 px-kw-4">
					<div class="w-full flex m-kw-4">
						<div class="" style="width:40%">
						<p class="kw-title">Service Provider Endpoint</p>
						<div class=" mr-kw-16"><i><b>Note:</b> If you have already shared the above URLs or Metadata with your IdP, do <b>NOT</b> change SP EntityID. It might break your existing login flow.</i>
							</div>
						</div>
						<div class="flex-1">
							<div id="blocked_email_settings" class="w-[95%] py-kw-4 pr-kw-4">
								<div class="kw-input-wrapper">
								<label class="">SP Base URL:</label>
								<input type="text" class="kw-input w-full" name="kwsso_sp_base_url" style="width: 90%;" placeholder="You site base URL"  value="' . esc_url( $sp_base_url ) . '" required />
								</div> 

								<div class="kw-input-wrapper mt-kw-4">
								<label class="">SP EntityID / Issuer:</label>
								<input type="text" class="kw-input w-full" name="kwsso_sp_entity_id" placeholder="You site base URL" style="width: 90%;" value="' . esc_url( $sp_entity_id ) . '" required />
											</div>	
								<div class="kw-input-wrapper mt-kw-4">
									<input class="kw-button primary" type="submit" name="submit" style="width:100px;" value="Update" class="kw-button inverted" />
								</div>		
							</div>
						</div>
					</div>
				</div>
		</form>
	</div>';


