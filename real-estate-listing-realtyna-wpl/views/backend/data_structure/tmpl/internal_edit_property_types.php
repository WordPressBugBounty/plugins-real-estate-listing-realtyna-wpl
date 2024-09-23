<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
	<h2><?php wpl_esc::html_t($property_type_data->name ?? 'Add new property type'); ?></h2>
	<div class="fanc-body">
		<div class="fanc-row">
			<label for="wpl_title<?php wpl_esc::attr($property_type_id); ?>"><?php wpl_esc::html_t('Name'); ?></label>
			<input class="text_box" type="text" id="wpl_name<?php wpl_esc::attr($property_type_id); ?>"
				   value="<?php wpl_esc::attr($property_type_data->name ?? ''); ?>"
				   onchange="wpl_ajax_save_property_type('name', this, '<?php wpl_esc::attr($property_type_id); ?>');"
				   autocomplete="off"/>
			<span class="ajax-inline-save" id="wpl_name<?php wpl_esc::attr($property_type_id); ?>_ajax_loader"></span>
		</div>
		<div class="fanc-row">
			<label for="wpl_parent<?php wpl_esc::attr($property_type_id); ?>"><?php wpl_esc::html_t('Category'); ?></label>
			<select class="text_box" id="wpl_parent<?php wpl_esc::attr($property_type_id); ?>"
					onchange="wpl_ajax_save_property_type('parent', this, '<?php wpl_esc::attr($property_type_id); ?>');"
					autocomplete="off">
				<?php foreach ($property_types_category as $property_types_category_item): ?>
					<option <?php if (isset($property_type_data->parent) and $property_types_category_item["id"] == $property_type_data->parent): ?> selected="selected" <?php endif; ?>
							value="<?php wpl_esc::attr($property_types_category_item["id"]); ?>"><?php wpl_esc::attr($property_types_category_item["name"]); ?></option>
				<?php endforeach; ?>
			</select>
			<span class="ajax-inline-save" id="wpl_parent<?php wpl_esc::attr($property_type_id); ?>_ajax_loader"></span>
		</div>
		<?php if (wpl_global::check_addon('demographic')): ?>
			<div class="fanc-row">
				<label for="wpl_show_marker<?php wpl_esc::attr($property_type_id); ?>"><?php wpl_esc::html_t('Show Marker'); ?></label>
				<select class="text_box" id="wpl_show_marker<?php wpl_esc::attr($property_type_id); ?>"
						onchange="wpl_ajax_save_property_type('show_marker', this, '<?php wpl_esc::attr($property_type_id); ?>');"
						autocomplete="off">
					<option value="1" <?php wpl_esc::e((isset($property_type_data->show_marker) and $property_type_data->show_marker == 1) ? 'selected="selected"' : ''); ?>>
						<?php wpl_esc::html_t('Yes'); ?>
					</option>
					<option value="0" <?php wpl_esc::e((isset($property_type_data->show_marker) and $property_type_data->show_marker == 0) ? 'selected="selected"' : ''); ?>>
						<?php wpl_esc::html_t('No'); ?>
					</option>
				</select>
				<span class="ajax-inline-save" id="wpl_show_marker<?php wpl_esc::attr($property_type_id); ?>_ajax_loader"></span>
			</div>
		<?php endif; ?>
		<div class="fanc-row">
			<label for="wpl_title<?php wpl_esc::attr($property_type_id); ?>"><?php wpl_esc::html_t('Color'); ?></label>
			<input class="wpl-color-field" type="text" id="wpl_color<?php wpl_esc::attr($property_type_id); ?>"
				   value="<?php wpl_esc::attr($property_type_data->color ?? ''); ?>"
				   autocomplete="off"/>
			<button onclick="wpl_property_type_color_save()" class="wpl-button button-1">Save Color</button>
			<span class="ajax-inline-save" id="wpl_color<?php wpl_esc::attr($property_type_id); ?>_ajax_loader"></span>
		</div>
		<?php if ($property_type_id === 10000): ?>
			<div class="fanc-row">
				<label></label>
				<input type="button" class="wpl-button button-1"
					   onclick="wpl_ajax_insert_property_type(<?php wpl_esc::attr($property_type_id); ?>);"
					   value="<?php wpl_esc::attr_t('Save'); ?>"/>
			</div>
		<?php endif; ?>
		<div class="wpl_show_message<?php wpl_esc::attr($property_type_id); ?>" style="margin: 0 10px;"></div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		wplj('.wpl-color-field').wpColorPicker();
	});

	function wpl_property_type_color_save() {
		var input = document.querySelector('#wpl_color<?php wpl_esc::attr($property_type_id); ?>');
		wpl_ajax_save_property_type('color', input, '<?php wpl_esc::attr($property_type_id); ?>');
	}
</script>