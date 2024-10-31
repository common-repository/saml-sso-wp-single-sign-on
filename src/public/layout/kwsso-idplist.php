<?php
/**
 * Load admin view for settings of Configured Forms.
 *
 * @package keywoot-saml-sso/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once 'kwsso-setup-idp-config.php';

echo '<div class="kwsso_divided_layout w-full" id="idpform" style="" >
    <div id="subtab" class="kw-subtabs-container">
        <div class="kw-subtab-item kw-subtab-item">
            <span class="kw-subtab-title" id="auto-config">
               Fetch Or Upload IDP Metadata
            </span>
        </div>
        <div class="kw-subtab-item">
            <span class="kw-subtab-title" id="manual-config">
            Configure Manually
            </span>
        </div>


        <button type="button" class="kw-button primary" name="test" title="You can only test your Configuration after saving your Service Provider Settings." onclick="showTestWindow();"';
if ( ! kwsso_check_if_sp_configured() || ! get_kwsso_option( KwConstants::X509_CERTIFICATE ) || get_kwsso_option( KwConstants::IS_IDP_ENABLED )!='true' ) {
	echo ' disabled';
}
echo '              value="Test configuration" >
        Test Configuration
    </button>
    </div>
           <div style="width:100%">';
echo '     
        <div class="my-kw-4">	
';

kwsso_apps_config_saml( $auto_config_hidden, $manual_config_hidden );

echo '
        </div>
    </div>';
require KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-configured-idp-list.php';
require KWSSO_PLUGIN_DIR . 'src/public/layout/kwsso-idplistgrid.php';


