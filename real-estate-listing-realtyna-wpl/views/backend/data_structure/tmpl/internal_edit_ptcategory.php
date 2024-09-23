<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
	<h2><?php wpl_esc::html_t($ptcategory_data->name ?? 'Add new category'); ?></h2>
	<div class="fanc-body">
		<div class="fanc-row">
			<label for="wpl_title<?php wpl_esc::attr($ptcategory_id); ?>"><?php wpl_esc::html_t('Name'); ?></label>
			<input class="text_box" type="text" id="wpl_name<?php wpl_esc::attr($ptcategory_id); ?>"
				   value="<?php wpl_esc::attr($ptcategory_data->name ?? ''); ?>"
				   onchange="wpl_ajax_save_property_type('name', this, '<?php wpl_esc::attr($ptcategory_id); ?>');"
				   autocomplete="off"/>
			<span class="ajax-inline-save" id="wpl_name<?php wpl_esc::attr($ptcategory_id); ?>_ajax_loader"></span>
		</div>
		<?php if ($ptcategory_id === 10000): ?>
			<div class="fanc-row">
				<label></label>
				<input type="button" class="wpl-button button-1"
					   onclick="wpl_ajax_insert_ptcategory(<?php wpl_esc::attr($ptcategory_id); ?>);"
					   value="<?php wpl_esc::attr_t('Save'); ?>"/>
			</div>
		<?php endif; ?>
		<div class="wpl_show_message<?php wpl_esc::attr($ptcategory_id); ?>" style="margin: 0 10px;"></div>
	</div>
</div>