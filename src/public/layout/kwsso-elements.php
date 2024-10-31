<?php
/**
 * Load user view for admin panel.
 *
 * @package keywoot-saml-sso/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use KWSSO_CORE\Src\Utility\KWSSO_PluginTabs;

/**
 * Display a tooltip with the appropriate header and message on the page
 *
 * @param  string $header  - the header of the tooltip.
 * @param  string $message - the body of the tooltip message.
 */
function kwsso_draw_tooltip( $header, $message ) {
	echo '<span class="tooltip">
            <span class="dashicons dashicons-editor-help"></span>
            <span class="tooltiptext">
                <span class="header"><b><i>' . esc_html( kwsso_lang_( $header ) ) . '</i></b></span><br/>
                <span class="body">' . esc_html( kwsso_lang_( $message ) ) . '</span>
            </span>
          </span>';
}
/**
 * Genertates metadata Skeleton
 *
 * @return void
 */
function get_metadata_xml_skeleton() {
	$xml_skeleton = '<?xml version="1.0"?>
						<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="{{VALID_UNTILL}}" cacheDuration="PT1446808792S" entityID="{{SP_ENTITY_ID}}">
							<md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
								<md:SingleLogoutService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="{{KWSSO_SP_BASE_URL}}"/>
								<md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
								<md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</md:NameIDFormat>
								<md:NameIDFormat>urn:oasis:names:tc:SAML:2.0:nameid-format:persistent</md:NameIDFormat>
								<md:NameIDFormat>urn:oasis:names:tc:SAML:2.0:nameid-format:transient</md:NameIDFormat>
								<md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="{{KWSSO_SP_BASE_URL}}" index="1"/>
							</md:SPSSODescriptor>
							<md:Organization>
								<md:OrganizationName xml:lang="en-US">keywoot</md:OrganizationName>
								<md:OrganizationDisplayName xml:lang="en-US">keywoot</md:OrganizationDisplayName>
								<md:OrganizationURL xml:lang="en-US">https://keywoot.com</md:OrganizationURL>
							</md:Organization>
							<md:ContactPerson contactType="support">
								<md:GivenName>keywoot</md:GivenName>
								<md:EmailAddress>info@keywoot.com</md:EmailAddress>
							</md:ContactPerson>
						</md:EntityDescriptor>';
	return $xml_skeleton;
}
/**
 * HTML for Premium feature notice
 *
 * @param bool $show_notice
 * @return void
 */
function show_premium_feature_notice( $show_notice ) {
	if ( $show_notice ) {
		echo '
<div class="p-kw-6 m-kw-2 rounded flex items-center bg-amber-50 justify-between">
  <div class="flex gap-kw-4 items-center">
    <svg width="48" height="48">
      <path d="M34.43 36H13.57a3.33 3.33 0 0 1-3.31-2.89L8 16.43a2 2 0 0 1 3.35-1.73l3.73 3.45A2 2 0 0 0 18 18l4.46-5.26a2 2 0 0 1 3.06 0L30 18a2 2 0 0 0 2.89.17l3.73-3.45A2 2 0 0 1 40 16.43L37.74 33.1a3.33 3.33 0 0 1-3.31 2.9Z"></path>
    </svg>
    <div>
      <p class="font-bold m-kw-0">This is a Premium Feature</p>
      <p class="m-kw-0">Please Upgrade to our premium plan or reach out to us for enabling this feature.</p>
    </div>
  </div>
  <div class="flex gap-kw-4">
    <a class="kw-button primary " target="_blank" href="https://keywoot.com/" style="cursor:pointer;">Buy Premium</a>
  </div>
</div>';
	}
}

function displayUserAttributes( $user_email, $attrs ) {
	echo '
    <style>
        .kw-user-attribute-container {
            overflow-x: auto;
            max-width: 100%;
            margin-top: 20px;
        }
        .kw-user-attribute-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }
        .kw-user-attribute-header {
            background-color: #6cb2eb;
            font-weight: bold;
            color: #fff;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .kw-user-attribute-cell {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>';

	echo '<div class="kw-user-attribute-container">
        <table class="kw-user-attribute-table">';

	if ( ! empty( $attrs ) ) {
		echo '
        <tr>
            <th class="kw-user-attribute-header">Attribute Name</th>
            <th class="kw-user-attribute-header">Attribute Value</th>
        </tr>';

		foreach ( $attrs as $key => $value ) {
			echo "<tr> 
			<td class='kw-user-attribute-cell'>" . esc_attr( $key ) . "</td>
			<td class='kw-user-attribute-cell'>" . implode( '<hr/>', $value ) . '</td></tr>';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- html from plugin.
		}
	} else {
		echo 'No Attributes Received.';
	}
	echo '</table></div>';
}


function displayTestSuccess() {
	echo '
    <style>
        .test-success-container {
            display: inline-flex;
            align-items: center;
            background-color: #6cb2eb;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #ffffff;
            min-height: 60px;
            width: 100%;
        }
        .test-success-icon {
            fill: #ffffff;
            margin-right: 10px;
        }
        .test-success-icon path {
            fill: #6cb2eb;
        }
        .test-success-icon polyline {
            fill: none;
            stroke: #ffffff;
            stroke-miterlimit: 10;
            stroke-width: 4;
        }
        .test-success-text {
            font-size: 18px;
        }
    </style>
    <div class="test-success-container">
        <svg class="test-success-icon" width="40" height="40" viewBox="0 0 48 48">
            <path d="M44,24c0,11-9,20-20,20S4,35,4,24S13,4,24,4S44,13,44,24z"></path>
            <polyline points="14,24 21,31 36,16"></polyline>
        </svg>
        <span class="test-success-text">Test Successful</span>
    </div>';
}


function displayTestFailure() {
	echo '
    <style>
        .test-failure-container {
            display: inline-flex;
            align-items: center;
            background-color: #ff8383;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #ffffff;
            min-height: 60px;
            width: 100%;
        }
        .test-failure-icon {
            fill: #ffffff;
            margin-right: 10px;
        }
        .test-failure-icon path:first-child {
            fill: #f78f8f;
        }
        .test-failure-icon path:nth-child(2) {
            fill: #c74343;
        }
        .test-failure-icon path:nth-child(3),
        .test-failure-icon path:nth-child(4) {
            fill: #ffffff;
        }
        .test-failure-text {
            font-size: 18px;
        }
    </style>
    <div class="test-failure-container">
        <svg class="test-failure-icon" width="40" height="40" viewBox="0 0 40 40">
            <path d="M20,38.5C9.799,38.5,1.5,30.201,1.5,20S9.799,1.5,20,1.5S38.5,9.799,38.5,20S30.201,38.5,20,38.5z"></path>
            <path d="M20,2c9.925,0,18,8.075,18,18s-8.075,18-18,18S2,29.925,2,20S10.075,2,20,2 M20,1 C9.507,1,1,9.507,1,20s8.507,19,19,19s19-8.507,19-19S30.493,1,20,1L20,1z"></path>
            <path d="M18.5 10H21.5V30H18.5z" transform="rotate(-134.999 20 20)"></path>
            <path d="M18.5 10H21.5V30H18.5z" transform="rotate(-45.001 20 20)"></path>
        </svg>
        <span class="test-failure-text">TEST FAILED</span>
    </div>';
}

function kwsso_show_test_result( $user_email, $attrs, $kwsso_relay_state ) {
	ob_end_clean();
	echo '<div style="font-family:Calibri;padding:0 3%;max-width: 100%;padding: 40px;background-color: #fff;border-radius: 10px;box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);">';
	if ( ! empty( $user_email ) ) {
		update_kwsso_option( KwConstants::TEST_CONFIG_ATTIBUTES, $attrs );
		update_kwsso_option( KwConstants::IS_IDP_ENABLED, 'true' );
		displayTestSuccess();
		// update_kwsso_option(KwConstants::KWSSO_TEST_SUCCESFUL, true);
	} else {
		displayTestFailure();
	}
	checkLongUsernameAttribute( $attrs );

	echo '  <div class="username" style="font-size: 20px; color: #444; margin-bottom: 20px;">
				<p>User: ' . esc_attr( $user_email ) . '</p>
	  		</div>
			<p style="font-weight:bold;font-size:14pt;">ATTRIBUTES RECEIVED:</p>';
			displayUserAttributes( $user_email, $attrs );
			display_action_buttons();
		exit;
}

function display_action_buttons() {
	 echo '<div style="margin:3%;display:block;text-align:center;">
	<input type="button" style="border: none; outline: none; cursor: pointer; width:250px; padding: 13px 22px; border-radius: 5px; font-size: 15px; color: #fff; background-color: #3498db; transition: background-color 0.3s ease; " onmouseover="this.style.backgroundColor=\'#2980b9\';" onmouseout="this.style.backgroundColor=\'#3498db\';" onmousedown="this.style.backgroundColor=\'#1f618d\';" onmouseup="this.style.backgroundColor=\'#2980b9\';"  value="Configure Attribute/Role Mapping" onClick="close_and_redirect();" > 
	<input type="button" style="border: none; outline: none; cursor: pointer; padding: 13px 22px; border-radius: 5px; font-size: 15px; color: #fff; background-color: #3498db; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor=\'#2980b9\';" onmouseout="this.style.backgroundColor=\'#3498db\';" onmousedown="this.style.backgroundColor=\'#1f618d\';" onmouseup="this.style.backgroundColor=\'#2980b9\';"  "type="button" value="Done" onClick="self.close();" >
	<style>
		.kw-sso-test-result-svg-images{
			display:block;
			text-align:center;
			margin-bottom:4%;
		}
	</style>
	<script>
		 function close_and_redirect(){
			 window.opener.redirect_to_attribute_mapping();
			 self.close();
		 }   
	</script>';
}
/**
 * Generates an SVG image element with the provided base64-encoded PNG data.
 *
 * This function creates an SVG image element containing a base64-encoded PNG image
 * specified by the provided `$encoded_data`.
 *
 * @param string $encoded_data Base64-encoded PNG image data to embed in the SVG.
 * @return string The generated SVG image element as a string.
 */
function generate_svg_image_element( $path ) {

	$svg_image = '<svg width="50" height="50" version="1.1" viewBox="0 0 50 50"><image width="50" height="50" xlink:href="data:image/png;base64,' . $path . '"/></svg>';
	// return '<svg width="50" height="50" version="1.1" viewBox="0 0 50 50"><image width="50" height="50" xlink:href="data:image/png;base64,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"></image></svg>';
	return $path;
}



/**
 * This function displays test configuration error.
 *
 * @param string $error_code error code.
 *
 * @param string $display_metadata_mismatch The metadata recieved in SMAL response and stored in plugin if the error corresponds to a mismatched metadata.
 *
 * @param string $status_message The status sent by Identity Provider.
 */
function kwsso_display_test_error( $error_code, $display_metadata_mismatch = '', $status_message = '' ) {
	$error_fix     = $error_code['fix'];
	$error_cause   = $error_code['cause'];
	$error_message = $error_code['test_config_msg'];

	echo '<div class="kw-error-container">';
	echo '<div class="kw-error-header">ERROR</div>';
	echo '<div class="kw-error-body">';
	echo '<p><strong>Error: </strong>' . esc_attr( $error_cause ) . '</p>';
	echo '<p><strong>Possible Cause: </strong>' . esc_attr( $error_message ) . '</p>';
	echo '<p><b>Please contact your administrator and report the following error</b></p>';
	echo '<div class="kw-error-footer">';
	echo '<input class="kw-error-button" type="button" value="Done" onClick="self.close();">';
	echo '</div>';
	echo '</div>';
	echo '<style>
			.kw-error-container{font-family:Calibri,sans-serif;padding:2%;max-width:600px;margin:50px auto;background-color:#fff;border:1px solid #ebebeb;border-radius:8px;box-shadow:0 4px 6px rgba(0,0,0,.1)}.kw-error-body,.kw-error-header{color:#721c24;margin-bottom:20px}.kw-error-header{background-color:#f8d7da;padding:20px;text-align:center;border-bottom:1px solid #f5c6cb;font-size:24px;font-weight:700;border-top-left-radius:8px;border-top-right-radius:8px}.kw-error-body{font-size:16px;line-height:1.5;padding:0 20px}.kw-error-body p{margin-bottom:1em}.kw-error-footer{text-align:center;padding:20px;border-top:1px solid #ebebeb}.kw-error-button{padding:10px 20px;background-color:#007bff;color:#fff;font-size:16px;border:none;border-radius:4px;cursor:pointer;transition:background-color .3s}.kw-error-button:hover{background-color:#0056b3}
		</style>';
	exit;
}





function checkLongUsernameAttribute( $attrs ) {
	$username_attr = get_kwsso_option( KwConstants::ATTRIBUTE_USERNAME );

	if ( ! empty( $attrs[ $username_attr ] ) ) {
		$username_value = $attrs[ $username_attr ][0];

		if ( strlen( $username_value ) > 60 ) {
			echo '<p style="color:red;">NOTE: This user will not be able to login as the username value is more than 60 characters long.<br/>Please try changing the mapping of the Username field in <a href="#" onClick="close_and_redirect();">Attribute/Role Mapping</a> tab.</p>';
		}
	}
}
/**
 * Generates a select drop down to display options for cron interval selection.
 *
 * @param string $sync_interval Selected sync interval.
 * @return string
 */
function kwsso_display_dropdown_for_cron_interval_selection( $sync_interval ) {
	$cron_intervals = array(
		'hourly'     => 'Hourly',
		'twicedaily' => 'Twice Daily',
		'daily'      => 'Daily',
		'weekly'     => 'Weekly',
		'monthly'    => 'Monthly',
	);
	$html           = '<select style="margin-right:10px" name="sync_interval">';
	foreach ( $cron_intervals as $interval_key => $interval_display_name ) {
		$selected = ( $sync_interval === $interval_key ) ? 'selected' : '';
		$html    .= "<option value='$interval_key' $selected >$interval_display_name</option>";
	}
	$html .= '</select><span class="ml-kw-4"><b>Choose the frequency at which you\'d like to retrieve metadata from the IDP</b></span>';
	return $html;
}