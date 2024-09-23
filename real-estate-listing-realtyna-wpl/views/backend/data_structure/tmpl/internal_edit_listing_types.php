<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
	<h2><?php wpl_esc::html_t($listing_type_data->name ?? 'Add new listing type'); ?></h2>
	<div class="fanc-body">
		<div class="fanc-row">
			<label for="wpl_title<?php wpl_esc::attr($listing_type_id); ?>"><?php wpl_esc::html_t('Name'); ?></label>
			<input class="text_box" type="text" id="wpl_name<?php wpl_esc::attr($listing_type_id); ?>"
				   value="<?php wpl_esc::attr($listing_type_data->name ?? ''); ?>"
				   onchange="wpl_ajax_save_listing_type('name', this, '<?php wpl_esc::attr($listing_type_id); ?>');"
				   autocomplete="off"/>
			<span class="ajax-inline-save" id="wpl_name<?php wpl_esc::attr($listing_type_id); ?>_ajax_loader"></span>
		</div>
		<div class="fanc-row">
			<label for="wpl_parent<?php wpl_esc::attr($listing_type_id); ?>"><?php wpl_esc::html_t('Category'); ?></label>
			<select class="text_box" id="wpl_parent<?php wpl_esc::attr($listing_type_id); ?>"
					onchange="wpl_ajax_save_listing_type('parent', this, '<?php wpl_esc::attr($listing_type_id); ?>');"
					autocomplete="off">
				<option value="">-----</option>
				<?php foreach ($listing_types_category as $listing_types_category_item): ?>
					<option <?php if (isset($listing_type_data->parent) and $listing_types_category_item['id'] == $listing_type_data->parent): ?> selected="selected" <?php endif; ?>
							value="<?php wpl_esc::attr($listing_types_category_item['id']); ?>"><?php wpl_esc::attr($listing_types_category_item['name']); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="ajax-inline-save" id="wpl_parent<?php wpl_esc::attr($listing_type_id); ?>_ajax_loader"></span>
		</div>
		<div class="fanc-row">
			<label for="wpl_gicon<?php wpl_esc::attr($listing_type_id); ?>"><?php wpl_esc::html_t('Google Icon'); ?></label>
			<select class="text_box" id="wpl_gicon<?php wpl_esc::attr($listing_type_id); ?>"
					onchange="wpl_ajax_save_listing_type('gicon', this, '<?php wpl_esc::attr($listing_type_id); ?>');"
					autocomplete="off">
				<option value="">-----</option>
				<?php foreach ($listing_gicons as $listing_gicon): ?>
					<option <?php if (isset($listing_type_data->gicon) and $listing_gicon == $listing_type_data->gicon): ?> selected="selected" <?php endif; ?>
							value="<?php wpl_esc::attr($listing_gicon) ?>"><?php wpl_esc::attr($listing_gicon); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="ajax-inline-save" id="wpl_gicon<?php wpl_esc::attr($listing_type_id); ?>_ajax_loader"></span>
		</div>
		<div class="fanc-row">
			<label for="wpl_title<?php wpl_esc::attr($listing_type_id); ?>"><?php wpl_esc::html_t('Color'); ?></label>
			<input class="wpl-color-field" type="text" id="wpl_color<?php wpl_esc::attr($listing_type_id); ?>"
				   value="<?php wpl_esc::attr($listing_type_data->color ?? ''); ?>"
				   autocomplete="off"/>
			<button onclick="wpl_listing_type_color_save()" class="wpl-button button-1">Save Color</button>
			<span class="ajax-inline-save" id="wpl_color<?php wpl_esc::attr($listing_type_id); ?>_ajax_loader"></span>
		</div>
		<?php if ($listing_type_id === 10000) { ?>
			<div class="fanc-row">
				<label></label><input type="button" class="wpl-button button-1"
									  onclick="wpl_ajax_insert_listing_type(<?php wpl_esc::attr($listing_type_id); ?>);"
									  value="<?php wpl_esc::html_t('Save'); ?>"/>
			</div>
		<?php } ?>
		<div class="fanc-row">
		</div>
		<div class="wpl_show_message<?php wpl_esc::attr($listing_type_id); ?>" style="margin: 0 10px;"></div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		wplj('.wpl-color-field').wpColorPicker();
	});

	function wpl_listing_type_color_save() {
		var input = document.querySelector('#wpl_color<?php wpl_esc::attr($listing_type_id); ?>');
		wpl_ajax_save_listing_type('color', input, '<?php wpl_esc::attr($listing_type_id); ?>');
	}
</script>