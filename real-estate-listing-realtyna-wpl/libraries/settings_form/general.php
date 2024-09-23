<?php /** @noinspection PhpUndefinedVariableInspection */
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'checkbox' and !$done_this) {
	?>
	<div class="prow wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="checkbox-wp">

			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::attr($setting_title); ?></label>
			<label class="wpl-switch">
				<input type="hidden" name="wpl_st_form_val" value="off"/>
				<input type="checkbox" name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					   id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					   autocomplete="off" <?php if ($value) wpl_esc::e('checked="checked"'); ?>
					   onchange="wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"/>
				<span class="wpl-slider wpl-round"></span>
			</label>
			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
                <span class="wpl_help_description"
					  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
            </span>
			<?php endif; ?>
			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>

		</div>
	</div>


	<?php
	$done_this = true;
} elseif ($type == 'text' and !$done_this) {
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="text-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::attr($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<input class="<?php wpl_esc::attr($params['html_class'] ?? ''); ?>" type="text"
				   name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
				   id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
				   value="<?php wpl_esc::attr($setting_record->setting_value ?? ""); ?>"
				   placeholder="<?php wpl_esc::e((isset($params['placeholder']) and $params['placeholder']) ? wpl_esc::return_attr_t($params['placeholder']) : ''); ?>"
				   onchange="<?php if (isset($options['show_shortcode']) and $options['show_shortcode']): ?>wpl_setting_show_shortcode('<?php wpl_esc::attr($setting_record->id) ?>', '<?php wpl_esc::attr($options['shortcode_key']); ?>', this.value);<?php endif; ?> wpl_setting_save('<?php wpl_esc::attr($setting_record->id) ?>', '<?php wpl_esc::attr($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::attr($setting_record->category); ?>');"
				   autocomplete="off" <?php wpl_esc::e(isset($params['readonly']) ? 'readonly="readonly"' : ''); ?> />

			<?php if (isset($options['show_shortcode'])): ?>
				<div class="shortcode-wp"
					 id="wpl_setting_form_shortcode_container<?php wpl_esc::attr($setting_record->id) ?>">
					<span title="<?php wpl_esc::attr_t('Shortcode'); ?>"
						  id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>_shortcode_value"><?php wpl_esc::e($options['shortcode_key'] . '="' . wpl_esc::return_html($value) . '"'); ?></span>
				</div>
			<?php endif; ?>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'separator' and !$done_this) {
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<h3 class="separator-name"><?php wpl_esc::html($setting_title); ?></h3>
		<hr/>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'select' and !$done_this) {
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$show_shortcode = isset($options['show_shortcode']) ? $options['show_shortcode'] : NULL;
	$values = isset($options['query']) ? wpl_db::select($options['query'], 'loadAssocList') : $options['values'];
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="select-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<select name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					onchange="<?php if ($show_shortcode): ?>wpl_setting_show_shortcode('<?php wpl_esc::attr($setting_record->id) ?>', '<?php wpl_esc::attr($options['shortcode_key']); ?>', this.value);<?php endif; ?> wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
					<?php if (isset($params['width'])): ?>data-chosen-opt="width: <?php wpl_esc::attr($params['width']); ?>"<?php endif; ?>
					autocomplete="off">
				<?php if ($show_empty): ?>
					<option value="">---</option><?php endif; ?>
				<?php foreach ($values as $value_array): ?>
					<option value="<?php wpl_esc::attr($value_array['key']); ?>" <?php if ($value_array['key'] == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html($value_array['value']); ?></option>
				<?php endforeach; ?>
			</select>

			<?php if ($show_shortcode): ?>
				<div class="shortcode-wp"
					 id="wpl_setting_form_shortcode_container<?php wpl_esc::attr($setting_record->id) ?>">
					<span title="<?php wpl_esc::attr_t('Shortcode'); ?>"
						  id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>_shortcode_value"><?php wpl_esc::e($options['shortcode_key'] . '="' . wpl_esc::return_html($value) . '"'); ?></span>
				</div>
			<?php endif; ?>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'sort_option' and !$done_this) {
	$kind = trim($options['kind'] ?? '') != '' ? $options['kind'] : 1;

	_wpl_import('libraries.sort_options');
	$sort_options = wpl_sort_options::render(wpl_sort_options::get_sort_options($options['kind'], 1)); /** getting enaled sort options **/
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="select-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				<span class="wpl_st_citation">:</span></label>
			<select name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					onchange="wpl_setting_show_shortcode('<?php wpl_esc::attr($setting_record->id) ?>', '<?php wpl_esc::attr($options['shortcode_key']); ?>', this.value);
							wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
					autocomplete="off">
				<?php foreach ($sort_options as $value_array): ?>
					<option value="<?php wpl_esc::attr($value_array['field_name']); ?>" <?php if ($value_array['field_name'] == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html($value_array['name']); ?></option>
				<?php endforeach; ?>
			</select>

			<?php if (isset($options['show_shortcode'])): ?>
				<div class="shortcode-wp"
					 id="wpl_setting_form_shortcode_container<?php wpl_esc::attr($setting_record->id) ?>">
					<span title="<?php wpl_esc::attr_t('Shortcode'); ?>"
						  id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>_shortcode_value"><?php wpl_esc::e($options['shortcode_key'] . '="' . wpl_esc::return_html($value) . '"'); ?></span>
				</div>
			<?php endif; ?>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'wppages' and !$done_this) {
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$wp_pages = wpl_global::get_wp_pages();
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="select-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<select name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					onchange="wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
					autocomplete="off">
				<?php if ($show_empty): ?>
					<option value="">---</option><?php endif; ?>
				<?php foreach ($wp_pages as $wp_page): ?>
					<option value="<?php wpl_esc::attr($wp_page->ID); ?>" <?php if ($wp_page->ID == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html($wp_page->post_title); ?></option>
				<?php endforeach; ?>
			</select>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'upload' and !$done_this) {
	$src = wpl_global::get_wpl_asset_url('img/system/' . $setting_record->setting_value);
	$activity_params = array('html_element_id' => $params['html_element_id'], 'html_ajax_loader' => '#wpl_ajax_loader_' . $setting_record->id, 'request_str' => $params['request_str'] . '&_wpnonce=' . $nonce);
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="upload-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				<span class="wpl_st_citation">:</span></label>
			<?php wpl_global::import_activity('ajax_file_upload', '', $activity_params); ?>
			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
			<?php if ($setting_record->setting_value): ?>
				<div class="upload-preview wpl-upload-setting">
					<img id="wpl_upload_image<?php wpl_esc::attr($setting_record->id) ?>" src="<?php wpl_esc::url($src); ?>"/>
					<div class="preview-remove-button">
						<span class="action-btn icon-recycle"
							  onclick="wpl_remove_upload<?php wpl_esc::attr($setting_record->id) ?>();"></span>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<script type="text/javascript">
		function wpl_remove_upload<?php wpl_esc::attr($setting_record->id) ?>() {
			request_str = 'wpl_format=b:settings:ajax&wpl_function=remove_upload&setting_name=<?php wpl_esc::attr($setting_record->setting_name); ?>&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

			/** run ajax query **/
			wplj.ajax({
				type: 'POST',
				dataType: 'JSON',
				url: '<?php wpl_esc::current_url(); ?>',
				data: request_str,
				success: function (data) {
					if (data.success == 1) {
						wplj("#wpl_st_<?php wpl_esc::attr($setting_record->id) ?> .upload-preview").remove();
					}
				}
			});
		}
	</script>
	<?php
	$done_this = true;
} elseif ($type == 'textarea' and !$done_this) {
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="text-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<textarea class="long" name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					  id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					  onchange="wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"><?php wpl_esc::html($setting_record->setting_value); ?></textarea>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'pattern' and !$done_this) {
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="text-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<textarea class="long" style="height: 60px;" name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					  id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					  placeholder="<?php wpl_esc::e((isset($params['placeholder']) and $params['placeholder']) ? wpl_esc::return_attr_t($params['placeholder']) : ''); ?>"
					  onchange="wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
					  autocomplete="off"><?php wpl_esc::html($setting_record->setting_value); ?></textarea>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'multiple' and !$done_this) {
	$items = json_decode($setting_record->setting_value ?? '', true);
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="text-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>

			<input class="<?php wpl_esc::attr($params['html_class'] ?? ''); ?>" type="text"
				   name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
				   id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
				   placeholder="<?php wpl_esc::e((isset($params['placeholder']) and $params['placeholder']) ? wpl_esc::return_attr_t($params['placeholder']) : ''); ?>"
				   onchange="wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
				   autocomplete="off" value="<?php wpl_esc::attr($setting_record->setting_value); ?>"
				   data-realtyna-tagging/>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'colorpicker' and !$done_this) {
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="color-picker-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<input class="wpl-color-picker-field <?php wpl_esc::attr($params['html_class'] ?? ''); ?>"
				   type="text"
				   data-default-color="<?php wpl_esc::attr($setting_record->setting_value); ?>"
				   name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
				   id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
				   value="<?php wpl_esc::attr($setting_record->setting_value); ?>"
				   autocomplete="off" <?php wpl_esc::e(isset($params['readonly']) ? 'readonly="readonly"' : ''); ?>
			/>
			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
                <span class="wpl_help_description"
					  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
            </span>
			<?php endif; ?>
			<input class="wpl-button button-1 wpl-save-btn wpl-color-picker-save" type="button"
				   onclick="wpl_color_picker_save<?php wpl_esc::attr($setting_record->id) ?>();"
				   value="<?php wpl_esc::attr_t('Save'); ?>"/>
		</div>
	</div>
	<script type="text/javascript">
		wplj('document').ready(function () {
			wplj('#wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>').wpColorPicker();
		});

		function wpl_color_picker_save<?php wpl_esc::attr($setting_record->id) ?>() {
			wpl_setting_save('<?php wpl_esc::attr($setting_record->id) ?>', '<?php wpl_esc::attr($setting_record->setting_name); ?>', wplj("#wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>").val(), '<?php wpl_esc::js($setting_record->category); ?>');
		}
	</script>
	<?php
	$done_this = true;
} elseif ($type == 'currency' and !$done_this) {
	$values = wpl_units::get_units();
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="select-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<select name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					onchange="wpl_setting_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
					autocomplete="off">
				<?php foreach ($values as $value_array): ?>
					<option value="<?php wpl_esc::attr($value_array['extra']); ?>" <?php if ($value_array['extra'] == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html($value_array['extra']); ?></option>
				<?php endforeach; ?>
			</select>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'imagepicker' and !$done_this) {
	$images = isset($options['images']) ? $options['images'] : array();
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="select-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>

			<div class="wpl-imagepicker-images-wp" id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>">

				<?php foreach ($images as $image): ?>
					<div id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>_val_<?php wpl_esc::attr($image['value']); ?>"
						 class="wpl-imagepicker-image-wp <?php wpl_esc::e($image['value'] == $value ? 'wpl-imagepicker-active' : ''); ?>">

						<?php if (isset($image['path'])): ?>
							<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url($image['path'])); ?>"
								 onclick="wpl_imagepicker_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', '<?php wpl_esc::js($image['value']); ?>', '<?php wpl_esc::js($setting_record->category); ?>');">
						<?php elseif (isset($image['url'])): ?>
							<img src="<?php wpl_esc::url($image['url']); ?>"
								 onclick="wpl_imagepicker_save('<?php wpl_esc::js($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', '<?php wpl_esc::js($image['value']); ?>', '<?php wpl_esc::js($setting_record->category); ?>');">
						<?php endif; ?>

					</div>
				<?php endforeach; ?>

			</div>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

		</div>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'advanced_markers' and !$done_this) {
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');

	$listing_types = wpl_global::get_listings();
	$property_types = wpl_global::get_property_types();

	$icons = wpl_global::get_property_type_icons();

	$advanced_markers = json_decode((trim($value ?? '') ? $value : '{}'), true);
	if (!is_array($advanced_markers)) $advanced_markers = array();
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="advanced-markers-wp">

			<p><?php wpl_esc::html_t("You can enable advanced map markers simply here. You just need to configure the color and icons and then check Google Maps on your website! Don't forget to click save button below."); ?></p>

			<div class="wpl-advanced-markers-status-wp">
				<label for="wpl_am_status"><?php wpl_esc::html_t('Status'); ?></label>
				<select name="wpl_advanced_markers[status]" id="wpl_am_status">
					<option value="0" <?php wpl_esc::e((isset($advanced_markers['status']) and $advanced_markers['status'] == '0') ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
					<option value="1" <?php wpl_esc::e((isset($advanced_markers['status']) and $advanced_markers['status'] == '1') ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
				</select>
			</div>

			<div class="wpl-advanced-markers-wp" id="wpl_advanced_markers_options_wp">

				<div class="wpl-advanced-markers-listing-types-wp">
					<?php foreach ($listing_types as $listing_type): $color = (isset($advanced_markers['listing_types']) and isset($advanced_markers['listing_types'][$listing_type['id']]) and trim($advanced_markers['listing_types'][$listing_type['id']] ?? '')) ? $advanced_markers['listing_types'][$listing_type['id']] : '#29a9df'; ?>
						<div>
							<label for="wpl_am_lt_<?php wpl_esc::attr($listing_type['id']); ?>"><?php wpl_esc::html($listing_type['name']); ?></label>
							<input type="text"
								   name="wpl_advanced_markers[listing_types][<?php wpl_esc::attr($listing_type['id']); ?>]"
								   value="<?php wpl_esc::attr($color); ?>" class="wpl-color-field"
								   data-default-color="<?php wpl_esc::attr($color); ?>"
								   id="wpl_am_lt_<?php wpl_esc::attr($listing_type['id']); ?>"/>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="wpl-advanced-markers-property-types-wp">
					<?php foreach ($property_types as $property_type): $current_icon = (isset($advanced_markers['property_types']) and isset($advanced_markers['property_types'][$property_type['id']]) and trim($advanced_markers['property_types'][$property_type['id']] ?? '')) ? $advanced_markers['property_types'][$property_type['id']] : ''; ?>
						<div class="">
							<label for="wpl_am_pt_<?php wpl_esc::attr($property_type['id']); ?>"><?php wpl_esc::html($property_type['name']); ?></label>
							<input type="hidden"
								   name="wpl_advanced_markers[property_types][<?php wpl_esc::attr($property_type['id']); ?>]"
								   value="<?php wpl_esc::attr($current_icon); ?>"
								   id="wpl_am_pt_<?php wpl_esc::attr($property_type['id']); ?>"/>

							<div class="wpl-advanced-markers-property-types-images wpl-setting-select-img"
								 id="wpl_am_pt_icons<?php wpl_esc::attr($property_type['id']); ?>">
								<?php foreach ($icons as $icon): ?>
									<img src="<?php wpl_esc::url($icon['url']); ?>"
										 class="wpl-am-pt-icon <?php wpl_esc::attr(($current_icon == $icon['icon']) ? 'wpl-am-pt-icon-active' : ''); ?>"
										 data-icon="<?php wpl_esc::attr($icon['icon']); ?>"
										 data-pt-id="<?php wpl_esc::attr($property_type['id']); ?>">
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
			<div class="wpl-advanced-markers-save-button-wp">
				<label></label>
				<button onclick="wpl_advanced_markers_save(<?php wpl_esc::attr($setting_record->id) ?>);"
						class="wpl-button button-1">
					<?php wpl_esc::html_t('Save'); ?>
				</button>
				<div id="wpl_advanced_markers_show_message" class="wpl-notify-msg"></div>
			</div>

		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			wplj('#wpl_st_<?php wpl_esc::attr($setting_record->id) ?> .wpl-color-field').wpColorPicker();
		});
	</script>
	<?php
	$done_this = true;
} elseif ($type == 'usertypes' and !$done_this) {
	$show_empty = isset($options['show_empty']) ? $options['show_empty'] : NULL;
	$user_types = wpl_users::get_user_types();
	?>
	<div class="prow wpl_setting_form_container wpl_st_type<?php wpl_esc::attr($setting_record->type); ?> wpl_st_<?php wpl_esc::attr($setting_record->setting_name); ?>"
		 id="wpl_st_<?php wpl_esc::attr($setting_record->id) ?>">
		<div class="select-wp">
			<label for="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"><?php wpl_esc::html($setting_title); ?>
				&nbsp;<span class="wpl_st_citation">:</span></label>
			<select name="wpl_st_form<?php wpl_esc::attr($setting_record->id) ?>"
					id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id) ?>"
					onchange="wpl_setting_save('<?php wpl_esc::attr($setting_record->id) ?>', '<?php wpl_esc::js($setting_record->setting_name); ?>', this.value, '<?php wpl_esc::js($setting_record->category); ?>');"
					autocomplete="off">
				<?php if ($show_empty): ?>
					<option value="">---</option><?php endif; ?>
				<?php foreach ($user_types as $user_type): ?>
					<option value="<?php wpl_esc::attr($user_type->id); ?>" <?php if ($user_type->id == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html($user_type->name); ?></option>
				<?php endforeach; ?>
			</select>

			<?php if (isset($params['tooltip'])): ?>
				<span class="wpl_setting_form_tooltip wpl_help"
					  id="wpl_setting_form_tooltip_container<?php wpl_esc::attr($setting_record->id) ?>">
            <span class="wpl_help_description"
				  style="display: none;"><?php wpl_esc::html_t($params['tooltip']); ?></span>
        </span>
			<?php endif; ?>

			<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id) ?>"></span>
		</div>
	</div>
	<?php
	$done_this = true;
}
