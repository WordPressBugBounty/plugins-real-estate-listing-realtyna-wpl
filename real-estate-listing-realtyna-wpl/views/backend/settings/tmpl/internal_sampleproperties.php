<?php defined('_WPLEXEC') or die('Restricted access'); /** no direct access **/ ?>

<div class="wpl-sample-properties">
    <div class="wpl_show_message"></div>

	<label class="wpl-gen-panel-label"><?php wpl_esc::html_t('Add Samples'); ?>: </label>
    <input type="button" id="wpl_add_sample_properties_btn" class="wpl-button button-1" onclick="wpl_add_sample_properties();" value="<?php wpl_esc::attr_t('Add Sample Properties'); ?>"/>
			
    <span id="wpl_add_sample_properties_ajax_loader"></span>
	<div class="wpl-util-panel-note wpl-sample-properties-note"><?php wpl_esc::html_t('Click here to add up to six sample properties.'); ?></div>
</div>