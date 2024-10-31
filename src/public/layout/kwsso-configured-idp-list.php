<?php

echo ' <div id="configured-idp-container" style="" class="kw-subpage-container">
	<div class="kw-header">
	<p class="kw-heading flex-1">Configured Identity Provider(IDP)</p>
  
</div>
<div class="border-b flex flex-col gap-kw-6 pb-kw-4 px-kw-4 ">         
	<div class="w-full"> 

<div class="div-table-body">
<div class="kw-table">
<div class="bg-card ">
 	<div class="kw-row">
    	<div class="kw-cell  flex kw-link-name"><a class="kw-title mx-kw-8 text-primary">Icon</a></div>
		<div class="kw-cell flex kw-link-name"> <a class="kw-title text-primary "> Identity Provider</a></div>
		<div class="kw-cell kw-link-name "><a class="kw-title text-primary">Enable/Disable</a></div>
		<div class="kw-cell kw-button-wrapper-name flex justify-center"><a class="kw-title text-primary">Actions</a>
		</div>
  </div>
</div>';
echo '    </div>
</div>
          </div>



    </div>

<div id="kwdeleteIDPModal" aria-hidden="true" tabindex="-1" style="display:none" class="kw-popup-modal">
    <div class="kw-popup-modal-wrapper ">
        <div class="relative p-kw-4 text-center bg-primary-bg rounded-smooth shadow sm:p-kw-5">
            <button type="button"  name="hide_remove_idp_conf_alert" class="text-secondary-txt absolute rounded-full top-kw-2.5 right-kw-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-smooth text-caption p-kw-1.5 ml-auto inline-flex items-center data-modal-toggle="deleteModal">
                <svg aria-hidden="true" class="w-kw-icon h-kw-icon" fill="currentColor" viewBox="0 0 20 20" ><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <svg class="text-kw-inactive-ic w-kw-11 h-kw-11 mb-kw-3.5 mx-auto" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" ><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <p class="mb-kw-4 font-semibold text-kw-secondary-txt">Are you sure you want to remove the confguration for this IDP?</p>
            <div class="flex justify-center items-center space-x-kw-4">
                <button data-modal-toggle="deleteModal" name="hide_remove_idp_conf_alert" type="button" class="kw-button secondary">
                    No, cancel
                </button>
				<form id="kwsso_confirm_remove_idp_conf" method="post" action="">';
						wp_nonce_field( 'kwsso_confirm_remove_idp_conf' );
						echo '<input type="hidden" name="option" value="kwsso_confirm_remove_idp_conf"/>
					    <button type="submit" class="kw-button primary">
                    		Yes, I\'m sure
                		</button>	
				</form>
            </div>
        </div>
    </div>
</div>
 </div>';

function findIdpNameByKey( $idp_data, $searchKey ) {
	foreach ( $idp_data as $idp ) {
		if ( isset( $idp['key'] ) && $idp['key'] === $searchKey ) {
			return $idp['name'];
		}
	}
	return null; // Return null if the key is not found
}
