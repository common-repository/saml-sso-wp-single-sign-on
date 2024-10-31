<?php

	echo '<div id="advance-settings-container" class="kw-subpage-container ">
	<div class="kw-header">
				<p class="kw-heading flex-1">Advance Settings</p>
				<input type="submit" value="Save Settings" ' . esc_attr( $disabled ) . '  class="kw-button primary"  />
	</div>';

show_premium_feature_notice( $show_notice );

	echo '<div class="flex flex-col gap-kw-6 ">
	<div class="w-full">
	<div class="p-kw-4 px-kw-8 border-b">	  
		<div class="kw-ad-checkbox-container">
			<div class="kw-ad-label-width">
				<b>Do not create new users</b>
			</div>
			<div>
				<label class="kw-switch">
				<input type="checkbox" ' . esc_attr( $disabled ) . ' value="checked"/>
				<span class="kw-slider"></span>
				</label>
			</div>
		</div>

		<div class="kw-ad-checkbox-container">
			<div class="kw-ad-label-width">
				<b>Do not update existing user\'s roles</b>
			</div>
			<div>
				<label class="kw-switch">
				<input type="checkbox" ' . esc_attr( $disabled ) . ' value="checked"  />				
                <span class="kw-slider"></span>
				</label>
			</div>
		</div>
	</div>
</div>';
