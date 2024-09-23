<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
	<h2><?php wpl_esc::html_t($ltcategory_data->name ?? 'Add new category'); ?></h2>
	<div class="fanc-body">
		<div class="fanc-row">
			<label for="wpl_title<?php wpl_esc::attr($ltcategory_id); ?>"><?php wpl_esc::html_t('Name'); ?></label>
			<input class="text_box" type="text" id="wpl_name<?php wpl_esc::attr($ltcategory_id); ?>"
				   value="<?php wpl_esc::attr($ltcategory_data->name ?? ''); ?>"
				   onchange="wpl_ajax_save_listing_type('name', this, '<?php wpl_esc::attr($ltcategory_id); ?>');"
				   autocomplete="off"/>
			<span class="ajax-inline-save" id="wpl_name<?php wpl_esc::attr($ltcategory_id); ?>_ajax_loader"></span>
		</div>
		<?php if ($ltcategory_id === 10000): ?>
			<div class="fanc-row">
				<label></label>
				<input type="button" class="wpl-button button-1"
					   onclick="wpl_ajax_insert_ltcategory(<?php wpl_esc::attr($ltcategory_id); ?>);"
					   value="<?php wpl_esc::attr_t('Save'); ?>"/>
			</div>
		<?php endif; ?>
		<div class="wpl_show_message<?php wpl_esc::attr($ltcategory_id); ?>" style="margin: 0 10px;"></div>
	</div>
</div>
