<?php /** @noinspection PhpUndefinedVariableInspection */
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'boolean' and !$done_this) {
	$true_label = isset($options['true_label']) ? $options['true_label'] : 'Yes';
	$false_label = isset($options['false_label']) ? $options['false_label'] : 'No';
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			name="<?php wpl_esc::attr($field->table_column); ?>"
			onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>'); wpl_field_specific_changed('<?php wpl_esc::attr($field->id); ?>')"
			data-specific="<?php wpl_esc::attr($specified_children); ?>">
		<option value="-1"><?php wpl_esc::html_t('Select'); ?></option>
		<option value="1" <?php if (1 == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($true_label); ?></option>
		<option value="0" <?php if (0 == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($false_label); ?></option>
	</select>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'date' and !$done_this) {
	wp_enqueue_script('jquery-ui-datepicker');

	$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
	$jqdate_format = $date_format_arr[1];

	if (is_array($options) and isset($options['minimum_date']) and $options['minimum_date'] == 'now' or $options['minimum_date'] == 'minimum_date') $options['minimum_date'] = date("Y-m-d");
	if (is_array($options) and isset($options['minimum_date']) and $options['maximum_date'] == 'now') $options['maximum_date'] = date("Y-m-d");

	if (!$value) $value = '0000-00-00';

	$mindate = ((is_array($options) and isset($options['minimum_date'])) ? explode('-', $options['minimum_date']) : array());
	$maxdate = ((is_array($options) and isset($options['maximum_date'])) ? explode('-', $options['maximum_date']) : array());

	$mindate[0] = (array_key_exists(0, $mindate) and $mindate[0]) ? $mindate[0] : '1970';
	$mindate[1] = array_key_exists(1, $mindate) ? intval($mindate[1]) : '01';
	$mindate[2] = array_key_exists(2, $mindate) ? intval($mindate[2]) : '01';

	$maxdate[0] = (array_key_exists(0, $maxdate) and $maxdate[0]) ? $maxdate[0] : date('Y');
	$maxdate[1] = array_key_exists(1, $maxdate) ? intval($maxdate[1]) : date('m');
	$maxdate[2] = array_key_exists(2, $maxdate) ? intval($maxdate[2]) : date('d');
	?>
	<div class="date-wp">
		<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="required-star">*</span><?php endif; ?></label>
		<input type="text" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($field->table_column); ?>"
			   value="<?php wpl_esc::attr(wpl_render::render_date($value)); ?>"
			   onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="ajax-inline-save"></span>
	</div>
	<?php
	wpl_esc::e('<script type="text/javascript">
		jQuery(document).ready( function ()
		{
			wplj("#wpl_c_' . $field->id . '").datepicker(
			{ 
				dayNamesMin: ["' . wpl_esc::return_attr_t('SU') . '", "' . wpl_esc::return_attr_t('MO') . '", "' . wpl_esc::return_attr_t('TU') . '", "' . wpl_esc::return_attr_t('WE') . '", "' . wpl_esc::return_attr_t('TH') . '", "' . wpl_esc::return_attr_t('FR') . '", "' . wpl_esc::return_attr_t('SA') . '"],
				dayNames: 	 ["' . wpl_esc::return_attr_t('Sunday') . '", "' . wpl_esc::return_attr_t('Monday') . '", "' . wpl_esc::return_attr_t('Tuesday') . '", "' . wpl_esc::return_attr_t('Wednesday') . '", "' . wpl_esc::return_attr_t('Thursday') . '", "' . wpl_esc::return_attr_t('Friday') . '", "' . wpl_esc::return_attr_t('Saturday') . '"],
				monthNames:  ["' . wpl_esc::return_attr_t('January') . '", "' . wpl_esc::return_attr_t('February') . '", "' . wpl_esc::return_attr_t('March') . '", "' . wpl_esc::return_attr_t('April') . '", "' . wpl_esc::return_attr_t('May') . '", "' . wpl_esc::return_attr_t('June') . '", "' . wpl_esc::return_attr_t('July') . '", "' . wpl_esc::return_attr_t('August') . '", "' . wpl_esc::return_attr_t('September') . '", "' . wpl_esc::return_attr_t('October') . '", "' . wpl_esc::return_attr_t('November') . '", "' . wpl_esc::return_attr_t('December') . '"],
				dateFormat: "' . $jqdate_format . '",
				gotoCurrent: true,
				minDate: new Date(' . $mindate[0] . ', ' . $mindate[1] . '-1, ' . $mindate[2] . '),
				maxDate: new Date(' . $maxdate[0] . ', ' . $maxdate[1] . '-1, ' . $maxdate[2] . '),
				yearRange: "' . $mindate[0] . ':' . $maxdate[0] . '",
				showOn: "both",
				buttonImage: "' . wpl_global::get_wpl_asset_url('img/system/calendar3.png') . '",
				buttonImageOnly: false,
				buttonImageOnly: true,
				firstDay: 1,
				onSelect: function(dateText, inst) 
				{
					ajax_save("' . $field->table_name . '","' . $field->table_column . '",dateText,' . $item_id . ',' . $field->id . ')
				}
			})
		});
	</script>');

	$done_this = true;
} elseif ($type == 'datetime' and !$done_this) {
	// Add DateTime Picker assets
	wpl_extensions::import_javascript((object)array('param1' => 'jquery.datetimepicker', 'param2' => 'packages/datetimepicker/jquery.datetimepicker.full.min.js'));
	wpl_extensions::import_style((object)array('param1' => 'jquery.datetimepicker.style', 'param2' => 'packages/datetimepicker/jquery.datetimepicker.min.css'));

	$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
	$jqdate_format = $date_format_arr[0];

	if ($options['minimum_date'] == 'now' or $options['minimum_date'] == 'minimum_date') $options['minimum_date'] = date("Y-m-d");
	if ($options['maximum_date'] == 'now') $options['maximum_date'] = date("Y-m-d");

	$mindate = explode('-', $options['minimum_date']);
	$maxdate = explode('-', $options['maximum_date']);

	$mindate[0] = (array_key_exists(0, $mindate) and $mindate[0]) ? $mindate[0] : '1970';
	$mindate[1] = array_key_exists(1, $mindate) ? intval($mindate[1]) : '01';
	$mindate[2] = array_key_exists(2, $mindate) ? intval($mindate[2]) : '01';

	$maxdate[0] = (array_key_exists(0, $maxdate) and $maxdate[0]) ? $maxdate[0] : date('Y');
	$maxdate[1] = array_key_exists(1, $maxdate) ? intval($maxdate[1]) : date('m');
	$maxdate[2] = array_key_exists(2, $maxdate) ? intval($maxdate[2]) : date('d');
	?>
	<div class="date-wp">
		<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="required-star">*</span><?php endif; ?></label>
		<input type="text" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($field->table_column); ?>"
			   value="<?php wpl_esc::attr(wpl_render::render_datetime($value)); ?>"
			   onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="ajax-inline-save"></span>
	</div>
	<?php
	wpl_esc::e('<script type="text/javascript">
		jQuery(document).ready( function ()
		{
			wplj("#wpl_c_' . $field->id . '").datetimepicker(
			{
                i18n:
                {
                    en:
                    {
                        months: ["' . wpl_esc::return_attr_t('January') . '", "' . wpl_esc::return_attr_t('February') . '", "' . wpl_esc::return_attr_t('March') . '", "' . wpl_esc::return_attr_t('April') . '", "' . wpl_esc::return_attr_t('May') . '", "' . wpl_esc::return_attr_t('June') . '", "' . wpl_esc::return_attr_t('July') . '", "' . wpl_esc::return_attr_t('August') . '", "' . wpl_esc::return_attr_t('September') . '", "' . wpl_esc::return_attr_t('October') . '", "' . wpl_esc::return_attr_t('November') . '", "' . wpl_esc::return_attr_t('December') . '"],
                        dayOfWeek: ["' . wpl_esc::return_attr_t('SU') . '", "' . wpl_esc::return_attr_t('MO') . '", "' . wpl_esc::return_attr_t('TU') . '", "' . wpl_esc::return_attr_t('WE') . '", "' . wpl_esc::return_attr_t('TH') . '", "' . wpl_esc::return_attr_t('FR') . '", "' . wpl_esc::return_attr_t('SA') . '"],
                    }
                },
                lang: "en",
				format: "' . $jqdate_format . ' H:i:s",
				minDate: "-' . $mindate[0] . '/' . ($mindate[1] - 1) . '/' . $mindate[2] . '",
				maxDate: "+' . $maxdate[0] . '/' . ($maxdate[1] - 1) . '/' . $maxdate[2] . '",
				onChangeDateTime: function(dp,input)
				{
					ajax_save("' . $field->table_name . '","' . $field->table_column . '",input.val(),' . $item_id . ',;' . $field->id . ';)
				}
			});
		});
	</script>');

	$done_this = true;
} elseif (($type == 'checkbox' or $type == 'tag') and !$done_this) {
	?>
	<div class="checkbox-wp">
		<input type="checkbox" class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>"
			   id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>"
			   value="1" <?php if ($value) wpl_esc::e('checked="checked"'); ?>
			   onchange="if(this.checked) value = 1; else value = 0; ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');wpl_field_specific_changed('<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?>
			   data-specific="<?php wpl_esc::attr($specified_children); ?>"/>
		<label class="checkbox-label"
			   for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="required-star">*</span><?php endif; ?></label>
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="ajax-inline-save"></span>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'feature' and !$done_this) {
	$checked = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? 'checked="checked"' : '';
	$style = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? '' : 'display:none;';
	?>
	<div class="checkbox-wp">
		<input type="checkbox" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($field->table_column); ?>" <?php wpl_esc::attr($checked); ?>
			   onchange="wplj('#wpl_span_feature_<?php wpl_esc::attr($field->id); ?>').slideToggle(400); ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');wpl_field_specific_changed('<?php wpl_esc::attr($field->id); ?>');"
			   data-specific="<?php wpl_esc::attr($specified_children); ?>"/>
		<label class="checkbox-label"
			   for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="required-star">*</span><?php endif; ?></label>
		<?php
		if (is_array($options) and isset($options['type']) and $options['type'] != 'none') {
			// setting the current value
			$value = trim($values[$field->table_column . '_options'] ?? "", ', ');

			if (is_array($options) and isset($options['type']) and $options['type'] == 'single') {
				wpl_esc::e('<div class="options-wp" id="wpl_span_feature_' . $field->id . '" style="' . $style . '">');
				wpl_esc::e('<select id="wpl_cf_' . $field->id . '" name="' . $field->table_column . '_options" onchange="ajax_save(\'' . $field->table_name . '\', \'' . $field->table_column . '_options\', \',\'+this.value+\',\', \'' . $item_id . '\', \'' . $field->id . '\', \'#wpl_cf_' . $field->id . '\');">');
				wpl_esc::e('<option value="0">' . wpl_esc::return_html_t('Select') . '</option>');

				foreach ($options['values'] as $select) {
					if (isset($select['enabled']) and !$select['enabled']) continue;

					$selected = $value == $select['key'] ? 'selected="selected"' : '';
					wpl_esc::e('<option value="' . $select['key'] . '" ' . $selected . '>' . wpl_esc::return_html_t($select['value'] ?? 'Error') . '</option>');
				}

				wpl_esc::e('</select>');
				wpl_esc::e('</div>');
			} elseif (is_array($options) and isset($options['type']) and $options['type'] == 'multiple') {
				$value_array = explode(',', $value);

				wpl_esc::e('<div class="options-wp" id="wpl_span_feature_' . $field->id . '" style="' . $style . '">');
				wpl_esc::e('<select multiple="multiple" id="wpl_cf_' . $field->id . '" name="' . $field->table_column . '_options" onchange="ajax_save(\'' . $field->table_name . '\', \'' . $field->table_column . '_options\', \',\'+wplj(this).val()+\',\', \'' . $item_id . '\', \'' . $field->id . '\', \'#wpl_cf_' . $field->id . '\');">');

				foreach ($options['values'] as $select) {
					if (isset($select['enabled']) and !$select['enabled']) continue;

					$selected = in_array($select['key'], $value_array) ? 'selected="selected"' : '';
					wpl_esc::e('<option value="' . $select['key'] . '" ' . $selected . '>' . wpl_esc::return_html_t($select['value'] ?? 'Error') . '</option>');
				}

				wpl_esc::e('</select>');
				wpl_esc::e('</div>');
			}
		}
		?>
	</div>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'listings' and !$done_this) {
	$listings = wpl_global::get_listings();
	$current_user = wpl_users::get_wpl_user();
	$lrestrict = $current_user->maccess_lrestrict ?? NULL;
	$rlistings = explode(',', $current_user->maccess_listings ?? '');
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			name="<?php wpl_esc::attr($field->table_column); ?>"
			onchange="wpl_listing_changed(this.value); ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');">
		<option value="-1"><?php wpl_esc::html_t('Select'); ?></option>
		<?php foreach ($listings as $listing): if ($lrestrict and !in_array($listing['id'], $rlistings)) continue; ?>
			<option value="<?php wpl_esc::attr($listing['id']); ?>" <?php if ($listing['id'] == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($listing['name']); ?></option>
		<?php endforeach; ?>
	</select>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'neighborhood' and !$done_this) {
	$checked = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? 'checked="checked"' : '';
	$style = (isset($values[$field->table_column]) and $values[$field->table_column] == '1') ? '' : 'display:none;';
	?>
	<div class="checkbox-wp">
		<input type="checkbox" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			   name="<?php wpl_esc::attr($field->table_column); ?>" <?php wpl_esc::attr($checked); ?>
			   onchange="wpl_neighborhood_select('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');wpl_field_specific_changed('<?php wpl_esc::attr($field->id); ?>');"
			   data-specific="<?php wpl_esc::attr($specified_children); ?>"/>
		<label class="checkbox-label"
			   for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="required-star">*</span><?php endif; ?></label>
		<div class="distance-wp distance_items_box" id="wpl_span_dis_<?php wpl_esc::attr($field->id); ?>"
			 style="<?php wpl_esc::attr($style); ?>">
			<div class="distance-item distance-value">
				<input type="text" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance"
					   name="<?php wpl_esc::attr($field->table_column); ?>_distance" class="wpl_distance_text"
					   value="<?php wpl_esc::attr($values[$field->table_column . '_distance']); ?>" size='3' maxlength="4"
					   onBlur="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column . '_distance'); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>', '#n_<?php wpl_esc::attr($field->id); ?>_distance');"/>
			</div>
			<div class="distance-item minute-by">
				<?php wpl_esc::html_t('Minutes'); ?><?php wpl_esc::html_t('By'); ?>
			</div>
			<div class="distance-item with-walk">
				<div class="radio-wp">
					<input type="radio" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance0"
						   name="n_<?php wpl_esc::attr($field->id); ?>_distance_by" <?php if ($values[$field->table_column . "_distance_by"] == '1') wpl_esc::e('checked="checked"'); ?>
						   value='1'
						   onchange="wpl_neighborhood_distance_type_select('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column . '_distance_by'); ?>', 1, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>', 'wpl_c_<?php wpl_esc::attr($field->id); ?>_distance0')"/>
					<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance0"><?php wpl_esc::html_t('Walk'); ?></label>
				</div>
			</div>
			<div class="distance-item with-car">
				<div class="radio-wp">
					<input type="radio" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance1"
						   name="n_<?php wpl_esc::attr($field->id); ?>_distance_by" <?php if ($values[$field->table_column . "_distance_by"] == '2') wpl_esc::e('checked="checked"'); ?>
						   value='2'
						   onchange="wpl_neighborhood_distance_type_select('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column . '_distance_by'); ?>', 2, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>', 'wpl_c_<?php wpl_esc::attr($field->id); ?>_distance1')"/>
					<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance1"><?php wpl_esc::html_t('Car'); ?></label>
				</div>
			</div>
			<div class="distance-item with-train">
				<div class="radio-wp">
					<input type="radio" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance2"
						   name="n_<?php wpl_esc::attr($field->id); ?>_distance_by" <?php if ($values[$field->table_column . "_distance_by"] == '3') wpl_esc::e('checked="checked"'); ?>
						   value='3'
						   onchange="wpl_neighborhood_distance_type_select('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column . '_distance_by'); ?>', 3, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>', 'wpl_c_<?php wpl_esc::attr($field->id); ?>_distance2')"/>
					<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_distance2"><?php wpl_esc::html_t('Train'); ?></label>
				</div>
			</div>
		</div>
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="ajax-inline-save"></span>
	</div>
	<?php
	$done_this = true;
} elseif ($type == 'number' and !$done_this) {
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<input type="number" step="any" lang="en" class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>"
		   id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>"
		   value="<?php wpl_esc::attr($value); ?>"
		   onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'mmnumber' and !$done_this) {
	$value = empty($value) ? 0 : $value;
	$value_max = $values[$field->table_column . '_max'] ?? 0;
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<input type="number" step="any" lang="en"
		   class="wpl_minmax_textbox wpl_c_<?php wpl_esc::attr($field->table_column); ?>"
		   id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>"
		   value="<?php wpl_esc::attr($value); ?>"
		   onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
	- <input type="number" step="any" lang="en"
			 class="wpl_minmax_textbox wpl_c_<?php wpl_esc::attr($field->table_column); ?>_max"
			 id="wpl_c_<?php wpl_esc::attr($field->id); ?>_max" name="<?php wpl_esc::attr($field->table_column); ?>_max"
			 value="<?php wpl_esc::attr($value_max); ?>"
			 onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>_max', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'property_types' and !$done_this) {
	$property_types = wpl_global::get_property_types();
	$current_user = wpl_users::get_wpl_user();

	$ptrestrict = $current_user->maccess_ptrestrict ?? NULL;
	$rproperty_types = explode(',', $current_user->maccess_property_types ?? '');

	$current_category = wpl_property_types::get_parent($value);
	$categories = wpl_property_types::get_property_type_categories();
	$listing_types_categories = wpl_listing_types::get_listing_type_categories();

	$category_field = wpl_flex::get_fields('', '', '', '', '', wpl_db::prepare("AND `enabled` >= 1 AND `kind` = %d AND `type` = 'ptcategory'", $kind));
	$category_field = reset($category_field);

	if ($category_field and property_exists($category_field, 'accesses') and wpl_global::check_addon('membership')) {
		$accesses = explode(',', trim($category_field->accesses ?? '', ', '));
		$cur_membership_id = wpl_users::get_user_membership();

		if (trim($category_field->accesses ?? '') and !in_array($cur_membership_id, $accesses) and trim($category_field->accesses_message ?? '') == '') {
			// Show nothing
		} elseif (trim($category_field->accesses ?? '') and !in_array($cur_membership_id, $accesses) and trim($category_field->accesses_message ?? '') != '') {
			wpl_esc::e('<div class="prow wpl_listing_field_container prow-' . $type . '" id="wpl_listing_field_container' . $category_field->id . '" style="' . $display . '">');
			wpl_esc::e('<label for="wpl_c_' . $category_field->id . '">' . wpl_esc::return_html_t($label) . '</label>');
			wpl_esc::e('<span class="wpl-access-blocked-message">' . wpl_esc::return_html_t($category_field->accesses_message) . '</span>');
			wpl_esc::e('</div>');
		} else {
			?>
			<div>
				<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_ptcategory"><?php wpl_esc::html_t('Category'); ?></label>
				<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>_ptcategory"
						id="wpl_c_<?php wpl_esc::attr($field->id); ?>_ptcategory">
					<option value="-1"><?php wpl_esc::html_t('Select'); ?></option>
					<?php foreach ($categories as $category): ?>
						<option value="<?php wpl_esc::attr($category['id']); ?>" <?php if ($category['id'] == $current_category) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($category['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php
		}
	}
	?>
	<div>
		<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="required-star">*</span><?php endif; ?></label>
		<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>"
				id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>"
				onchange="wpl_property_type_changed(this.value); ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');">
			<option value="-1"><?php wpl_esc::html_t('Select'); ?></option>
			<?php foreach ($property_types as $property_type): if ($ptrestrict and !in_array($property_type['id'], $rproperty_types)) continue; ?>
				<option class="wpl-ptcategory-option wpl-ptcategory-<?php wpl_esc::attr($property_type['parent']); ?>"
						value="<?php wpl_esc::attr($property_type['id']); ?>" <?php if ($property_type['id'] == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($property_type['name']); ?></option>
			<?php endforeach; ?>
		</select>
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			wplj('#wpl_c_<?php wpl_esc::attr($field->id); ?>_ptcategory').on('change', function (e, auto) {
				var cat = wplj('#wpl_c_<?php wpl_esc::attr($field->id); ?>_ptcategory').val();

				// If category changed by user, remove the previous selected property type
				if (typeof auto == 'object') wplj('#wpl_c_<?php wpl_esc::attr($field->id); ?>').find('option:selected').removeAttr('selected');

				wplj('.wpl-ptcategory-option').hide();

				if (cat != '' && cat != '-1') wplj('.wpl-ptcategory-' + cat).show();
				else wplj('.wpl-ptcategory-option').show();

				wplj('#wpl_c_<?php wpl_esc::attr($field->id); ?>').trigger('chosen:updated');
			});

			wplj('#wpl_c_<?php wpl_esc::attr($field->id); ?>_ptcategory').trigger('change', true);
		});
	</script>
	<?php
	$done_this = true;
} elseif ($type == 'select' and !$done_this) {
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			name="<?php wpl_esc::attr($field->table_column); ?>"
			onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');">
		<option value="-1"><?php wpl_esc::html_t('Select'); ?></option>

		<?php if (isset($options['params']) and is_array($options['params'])): ?>
			<?php foreach ($options['params'] as $key => $select): if (!$select['enabled']) continue; ?>
				<option value="<?php wpl_esc::attr($select['key']); ?>" <?php if ($select['key'] == $value) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($select['value'] ?? 'Error'); ?></option>
			<?php endforeach; ?>
		<?php endif; ?>

	</select>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'separator' and !$done_this) {
	?>
	<div class="seperator-wp" id="wpl_listing_separator<?php wpl_esc::attr($field->id); ?>">
		<?php wpl_esc::e((isset($options['show_label']) and $options['show_label'] == "1") ? wpl_esc::return_html_t($label) : ''); ?>
	</div>
	<?php
	$done_this = true;
} elseif (in_array($type, array('price', 'volume', 'area', 'length')) and !$done_this) {
	$current_unit = $values[$field->table_column . '_unit'];

	if ($type == 'price') $units = wpl_units::get_units(4, 1, wpl_db::prepare(" AND `type`='4' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	if ($type == 'volume') $units = wpl_units::get_units(3, 1, wpl_db::prepare(" AND `type`='3' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	if ($type == 'area') $units = wpl_units::get_units(2, 1, wpl_db::prepare(" AND `type`='2' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	if ($type == 'length') $units = wpl_units::get_units(1, 1, wpl_db::prepare(" AND `type`='1' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<input onkeyup="wpl_thousand_sep('wpl_c_<?php wpl_esc::attr($field->id); ?>')" type="text"
		   id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>"
		   value="<?php wpl_esc::attr(number_format($value, 2)); ?>"
		   onblur="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', wpl_de_thousand_sep(this.value), '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
	<?php
	if (count($units) <= 1) wpl_esc::html($units[0]['name']);
	else {
		wpl_esc::e('<select onchange="ajax_save(\'' . $field->table_name . '\', \'' . $field->table_column . '_unit\', this.value, \'' . $item_id . '\', \'' . $field->id . '\');">');
		foreach ($units as $unit) wpl_esc::e('<option value="' . $unit['id'] . '" ' . ($current_unit == $unit['id'] ? 'selected="selected"' : '') . '>' . $unit['name'] . '</option>');
		wpl_esc::e('</select>');
	}
	?>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif (in_array($type, array('mmprice', 'mmvolume', 'mmarea', 'mmlength')) and !$done_this) {
	$current_unit = @$values[$field->table_column . '_unit'];

	if ($type == 'mmprice') $units = wpl_units::get_units(4, 1, wpl_db::prepare(" AND `type`='4' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	if ($type == 'mmvolume') $units = wpl_units::get_units(3, 1, wpl_db::prepare(" AND `type`='3' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	if ($type == 'mmarea') $units = wpl_units::get_units(2, 1, wpl_db::prepare(" AND `type`='2' AND (`enabled`>='1' OR `id` = %d)", $current_unit));
	if ($type == 'mmlength') $units = wpl_units::get_units(1, 1, wpl_db::prepare(" AND `type`='1' AND (`enabled`>='1' OR `id` = %d)", $current_unit));

	$value_max = $values[$field->table_column . '_max'] ?? 0;
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<input onkeyup="wpl_thousand_sep('wpl_c_<?php wpl_esc::attr($field->id); ?>')" type="text"
		   id="wpl_c_<?php wpl_esc::attr($field->id); ?>" name="<?php wpl_esc::attr($field->table_column); ?>"
		   value="<?php wpl_esc::attr(number_format($value, 2)); ?>"
		   onblur="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', wpl_de_thousand_sep(this.value), '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
	<input onkeyup="wpl_thousand_sep('wpl_c_<?php wpl_esc::attr($field->id); ?>_max')" type="text"
		   id="wpl_c_<?php wpl_esc::attr($field->id); ?>_max" name="<?php wpl_esc::attr($field->table_column); ?>_max"
		   value="<?php wpl_esc::attr(number_format($value_max, 2)); ?>"
		   onblur="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>_max', wpl_de_thousand_sep(this.value), '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly']) == 1 ? 'disabled="disabled"' : ''); ?> />
	<?php
	if (count($units) <= 1) wpl_esc::html($units[0]['name']);
	else {
		wpl_esc::e('<select onchange="ajax_save(\'' . $field->table_name . '\', \'' . $field->table_column . '_unit\', this.value, \'' . $item_id . '\', \'' . $field->id . '\');">');
		foreach ($units as $unit) wpl_esc::e('<option value="' . $unit['id'] . '" ' . ($current_unit == $unit['id'] ? 'selected="selected"' : '') . '>' . $unit['name'] . '</option>');
		wpl_esc::e('</select>');
	}
	?>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'url' and !$done_this) {
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<input type="text" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
		   name="<?php wpl_esc::attr($field->table_column); ?>" value="<?php wpl_esc::attr($value); ?>"
		   onchange="ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', this.value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');" <?php wpl_esc::e((isset($options['readonly']) and $options['readonly'] == 1) ? 'disabled="disabled"' : ''); ?> />
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<?php
	$done_this = true;
} elseif ($type == 'meta_key' and !$done_this) {
	$current_language = wpl_global::get_current_language();
	if (isset($field->multilingual) and $field->multilingual == 1 and wpl_global::check_multilingual_status()): wp_enqueue_script('jquery-effects-clip', false, array('jquery-effects-core'));
		?>
		<label class="wpl-multiling-label wpl-multiling-text">
			<?php wpl_esc::html_t($label); ?>
			<?php if (in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?>
		</label>
		<div class="wpl-multiling-field wpl-multiling-text">

			<div class="wpl-multiling-flags-wp">
				<div class="wpl-multiling-flag-cnt">
					<?php foreach ($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
						<div data-wpl-field="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
							 data-wpl-title="<?php wpl_esc::attr($wpllang); ?>"
							 class="wpl-multiling-flag wpl-multiling-flag-<?php wpl_esc::attr(strtolower(substr($wpllang, -2)));
							 wpl_esc::attr(empty($values[$lang_column]) ? ' wpl-multiling-empty' : ''); ?>"></div>
					<?php endforeach; ?>
				</div>
				<div class="wpl-multiling-edit-btn"></div>
				<div class="wpl-multilang-field-cnt">
					<?php foreach ($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
						<div class="wpl-lang-cnt"
							 id="wpl_langs_cnt_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>">
							<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"><?php wpl_esc::html($wpllang); ?></label>
							<textarea
									class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
									id="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
									name="<?php wpl_esc::attr($field->table_column); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
									rows="<?php wpl_esc::attr($options['rows']); ?>" cols="<?php wpl_esc::attr($options['cols']); ?>"
									onblur="ajax_multilingual_save('<?php wpl_esc::attr($field->id); ?>', '<?php wpl_esc::attr(strtolower($wpllang)); ?>', this.value, '<?php wpl_esc::js($item_id); ?>');"><?php wpl_esc::html($values[$lang_column] ?? ''); ?></textarea>
							<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
								  class="wpl_listing_saved_span"></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php else: ?>
		<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="wpl_red_star">*</span><?php endif; ?></label>
		<div id="wpl_c_<?php wpl_esc::attr($field->id); ?>_container" class="wpl-meta-wp">
			<div class="wpl-top-row-wp">
				<input type="checkbox" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_manual" name="meta_keywords_manual"
					   onchange="meta_key_manual();" <?php if (isset($values['meta_keywords_manual']) and $values['meta_keywords_manual']) wpl_esc::e('checked="checked"'); ?> />
				<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_manual"><?php wpl_esc::html_t('Manually insert meta keywords'); ?></label>
			</div>
			<textarea id="wpl_c_<?php wpl_esc::attr($field->id); ?>" rows="<?php wpl_esc::attr($options['rows']); ?>"
					  cols="<?php wpl_esc::attr($options['cols']); ?>"
					  onchange="metatag_key_creator(true);" <?php wpl_esc::e(($options['readonly'] == 1 and (!isset($values['meta_keywords_manual']) or (isset($values['meta_keywords_manual']) and !$values['meta_keywords_manual']))) ? 'disabled="disabled"' : ''); ?>><?php wpl_esc::html($value); ?></textarea>
		</div>
		<span id="wpl_c_<?php wpl_esc::attr($field->id); ?>_message"></span>
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
		<script type="text/javascript">
			function metatag_key_creator(force) {
				if (!force) force = 0;

				var meta = '';

				/** Don't regenerate meta keywords if user want to manually insert it **/
				if (wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>_manual").is(':checked')) {
					if (force) {
						meta = wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").val();
						ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', meta, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
					}

					return true;
				}
			}

			var meta_key_pro_addon = <?php wpl_esc::numeric(wpl_global::check_addon('pro') ? '1' : '0'); ?>;

			function meta_key_manual() {
				if (!wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>_manual").is(':checked')) {
					wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").attr('disabled', 'disabled');

					if (meta_key_pro_addon) {
						ajax_save('<?php wpl_esc::js($field->table_name); ?>', 'meta_keywords_manual', '0', '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
						metatag_key_creator();
					}

					return false;
				}

				if (!meta_key_pro_addon) {
					wpl_show_messages("<?php wpl_esc::attr_t('Pro addon must be installed for this!'); ?>", '#wpl_c_<?php wpl_esc::attr($field->id); ?>_message', 'wpl_red_msg');
					setTimeout(function () {
						wpl_remove_message('#wpl_c_<?php wpl_esc::attr($field->id); ?>_message');
					}, 3000);

					wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>_manual").removeAttr('checked');
					return false;
				}

				wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").removeAttr('disabled');
				ajax_save('<?php wpl_esc::js($field->table_name); ?>', 'meta_keywords_manual', '1', '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
			}
		</script>
	<?php endif; ?>
	<?php
	$done_this = true;
} elseif ($type == 'meta_desc' and !$done_this) {
	$current_language = wpl_global::get_current_language();
	if (isset($field->multilingual) and $field->multilingual == 1 and wpl_global::check_multilingual_status()): wp_enqueue_script('jquery-effects-clip', false, array('jquery-effects-core'));
		?>
		<label class="wpl-multiling-label wpl-multiling-text">
			<?php wpl_esc::html_t($label); ?>
			<?php if (in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?>
		</label>
		<div class="wpl-multiling-field wpl-multiling-text">

			<div class="wpl-multiling-flags-wp">
				<div class="wpl-multiling-flag-cnt">
					<?php foreach ($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
						<div data-wpl-field="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
							 data-wpl-title="<?php wpl_esc::attr($wpllang); ?>"
							 class="wpl-multiling-flag wpl-multiling-flag-<?php wpl_esc::attr(strtolower(substr($wpllang, -2)));
							 wpl_esc::attr(empty($values[$lang_column]) ? ' wpl-multiling-empty' : ''); ?>"></div>
					<?php endforeach; ?>
				</div>
				<div class="wpl-multiling-edit-btn"></div>
				<div class="wpl-multilang-field-cnt">
					<?php foreach ($wpllangs as $wpllang): $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $wpllang, false); ?>
						<div class="wpl-lang-cnt"
							 id="wpl_langs_cnt_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>">
							<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"><?php wpl_esc::html($wpllang); ?></label>
							<textarea
									class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
									id="wpl_c_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
									name="<?php wpl_esc::attr($field->table_column); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
									rows="<?php wpl_esc::attr($options['rows']); ?>" cols="<?php wpl_esc::attr($options['cols']); ?>"
									onblur="ajax_multilingual_save('<?php wpl_esc::attr($field->id); ?>', '<?php wpl_esc::attr(strtolower($wpllang)); ?>', this.value, '<?php wpl_esc::js($item_id); ?>');"><?php wpl_esc::html($values[$lang_column] ?? ''); ?></textarea>
							<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::attr(strtolower($wpllang)); ?>"
								  class="wpl_listing_saved_span"></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php else: ?>
		<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
				<span class="wpl_red_star">*</span><?php endif; ?></label>
		<div id="wpl_c_<?php wpl_esc::attr($field->id); ?>_container" class="wpl-meta-wp">
			<div class="wpl-top-row-wp">
				<input type="checkbox" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_manual"
					   name="meta_description_manual"
					   onchange="meta_desc_manual();" <?php if (isset($values['meta_description_manual']) and $values['meta_description_manual']) wpl_esc::e('checked="checked"'); ?> />
				<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>_manual"><?php wpl_esc::html_t('Manually insert meta descriptions'); ?></label>
			</div>
			<textarea id="wpl_c_<?php wpl_esc::attr($field->id); ?>" rows="<?php wpl_esc::attr($options['rows']); ?>"
					  cols="<?php wpl_esc::attr($options['cols']); ?>"
					  onchange="metatag_desc_creator(true);" <?php wpl_esc::e(($options['readonly'] == 1 and (!isset($values['meta_description_manual']) or (isset($values['meta_description_manual']) and !$values['meta_description_manual']))) ? 'disabled="disabled"' : ''); ?>><?php wpl_esc::html($value); ?></textarea>
		</div>
		<span id="wpl_c_<?php wpl_esc::attr($field->id); ?>_message"></span>
		<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
		<script type="text/javascript">
			function metatag_desc_creator(force) {
				if (!force) force = 0;

				var meta = '';

				/** Don't regenerate meta keywords if user want to manually insert it **/
				if (wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>_manual").is(':checked')) {
					if (force) {
						meta = wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").val();
						ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', meta, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
					}

					return true;
				}
			}

			var meta_desc_pro_addon = <?php wpl_esc::e(wpl_global::check_addon('pro') ? '1' : '0'); ?>;

			function meta_desc_manual() {
				if (!wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>_manual").is(':checked')) {
					wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").attr('disabled', 'disabled');

					if (meta_desc_pro_addon) {
						ajax_save('<?php wpl_esc::js($field->table_name); ?>', 'meta_description_manual', '0', '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
						metatag_desc_creator();
					}

					return false;
				}

				if (!meta_desc_pro_addon) {
					wpl_show_messages("<?php wpl_esc::attr_t('Pro addon must be installed for this!'); ?>", '#wpl_c_<?php wpl_esc::attr($field->id); ?>_message', 'wpl_red_msg');
					setTimeout(function () {
						wpl_remove_message('#wpl_c_<?php wpl_esc::attr($field->id); ?>_message');
					}, 3000);

					wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>_manual").removeAttr('checked');
					return false;
				}

				wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").removeAttr('disabled');
				ajax_save('<?php wpl_esc::js($field->table_name); ?>', 'meta_description_manual', '1', '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
			}
		</script>
	<?php endif; ?>
	<?php
	$done_this = true;
} elseif ($type == 'multiselect' and !$done_this) {
	$multiselect_values = explode(',', $value);
	if (trim($multiselect_values[0] ?? '') == '') $multiselect_values = array();
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			name="<?php wpl_esc::attr($field->table_column); ?>" multiple="multiple">
		<?php foreach ($options['params'] as $key => $select): if (!$select['enabled']) continue; ?>
			<option value="<?php wpl_esc::attr($select['key']); ?>" <?php if (in_array($select['key'], $multiselect_values)) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($select['value'] ?? 'Error'); ?></option>
		<?php endforeach; ?>
	</select>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").change(function (e) {
				ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', wplj(this).val() || [].join(), '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
			});
		});
	</script>
	<?php
	$done_this = true;
} elseif ($type == 'multiselect_agent' and !$done_this) {
	// Get data from database
	$result = wpl_db::select("SELECT * FROM `#__wpl_users` WHERE `id` > 0 AND `membership_type`='1'", 'loadObjectList');
	// Create option fields
	foreach ($result as $resultkey => $resultvalue) {
		// isset($resultvalue->first_name)
		$options['params'][$resultkey]['value'] = $resultvalue->first_name . ' ' . $resultvalue->last_name;
		$options['params'][$resultkey]['key'] = $resultvalue->id;
		$options['params'][$resultkey]['enabled'] = 1;
	}

	$multiselect_values = explode(',', $value);
	if (trim($multiselect_values[0] ?? '') == '') $multiselect_values = array();
	?>
	<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if (in_array($mandatory, array(1, 2))): ?>
			<span class="required-star">*</span><?php endif; ?></label>
	<select class="wpl_c_<?php wpl_esc::attr($field->table_column); ?>" id="wpl_c_<?php wpl_esc::attr($field->id); ?>"
			name="<?php wpl_esc::attr($field->table_column); ?>" multiple="multiple">
		<?php foreach ($options['params'] as $key => $select): if (!$select['enabled']) continue; ?>
			<option value="<?php wpl_esc::attr($select['key']); ?>" <?php if (in_array($select['key'], $multiselect_values)) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($select['value'] ?? 'Error'); ?></option>
		<?php endforeach; ?>
	</select>
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>
	<script type="text/javascript">
		jQuery(document).ready(function () {
			wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").change(function (e) {
				ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::attr($field->table_column); ?>', wplj(this).val() || [].join(), '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
			});
		});
	</script>
	<?php
	$done_this = true;
}