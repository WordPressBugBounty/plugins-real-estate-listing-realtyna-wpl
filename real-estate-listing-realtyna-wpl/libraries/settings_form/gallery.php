<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

// Watermark Size & Unit
if ($setting_record->setting_name == 'watermark_size' and !$done_this) {
	?>

	<div class="prow wpl_setting_form_container wpl_st_typetext wpl_st_watermark_size" id="wpl_st_<?php wpl_esc::attr($setting_record->id); ?>">
	<div class="text-wp">
	<label for="wpl_st_form_element10">Watermark Size&nbsp;<span class="wpl_st_citation">:</span></label>
	<input class="" type="text" name="wpl_st_form<?php wpl_esc::attr($setting_record->id); ?>"
		   id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id); ?>"
		   value="<?php wpl_esc::attr($setting_record->setting_value); ?>" placeholder=""
		   onchange=" wpl_setting_save('<?php wpl_esc::attr($setting_record->id); ?>', 'watermark_size', this.value, '<?php wpl_esc::attr($setting_record->category); ?>');"
		   style="width: 50px" autocomplete="off">
	<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id); ?>"></span>
	<?php
	$done_this = true;

} elseif ($setting_record->setting_name == 'watermark_size_unit' and !$done_this) {
	$unit_types = array('px', '%');
	?>
	<select name="wpl_st_form<?php wpl_esc::attr($setting_record->id); ?>"
			id="wpl_st_form_element<?php wpl_esc::attr($setting_record->id); ?>"
			onchange=" wpl_setting_save('<?php wpl_esc::attr($setting_record->id); ?>', 'watermark_size_unit', this.value, '<?php wpl_esc::attr($setting_record->category); ?>');"
			class="wpl-chosen-inited">
		<?php for ($i = 0; $i < count($unit_types); $i++): ?>
			<option value="<?php wpl_esc::attr($unit_types[$i]); ?>"<?php wpl_esc::e(((isset($setting_record->setting_value) and $setting_record->setting_value == $unit_types[$i]) or (!isset($setting_record->setting_value) and 'px' == $unit_types[$i])) ? ' selected="selected"' : ''); ?>><?php wpl_esc::attr($unit_types[$i]); ?></option>
		<?php endfor; ?>
	</select>
	<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($setting_record->id); ?>"></span>
	</div>
	</div>
	<?php
	$done_this = true;
}