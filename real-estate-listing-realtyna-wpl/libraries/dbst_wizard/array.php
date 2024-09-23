<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'array' and !$done_this)
{
	$explode_array_value = stristr($value, '|') ? explode('|', $value) : array($value);
	?>
		<label for="wpl_c_<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($label); ?><?php if(in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
		<input type="hidden" id="wpl_c_<?php wpl_esc::attr($field->id); ?>">

	<div id="wpl_array_field_value_box<?php wpl_esc::attr($field->id); ?>" class="wpl-array-field-values">
        <div class="fanc-row">
            <input class="wpl-button button-1" type="button" onclick="wpl_array_add_value_<?php wpl_esc::attr($field->id); ?>()" value="<?php wpl_esc::attr_t('Add new') ?>">
        </div>
	<?php
	$i = 0;

	foreach ($explode_array_value as $value): $i++?>
		<div class="fanc-row" id="wpl_array_value_row_<?php wpl_esc::attr($field->id); ?>_<?php wpl_esc::e($i); ?>">
		    <input type="text" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_index<?php wpl_esc::e($i); ?>" name="wpl_c_<?php wpl_esc::attr($field->id); ?>_index<?php wpl_esc::e($i); ?>" value="<?php wpl_esc::attr($value); ?>">
            <input class="wpl-button button-1" type="button" onclick="wpl_array_remove_value_<?php wpl_esc::attr($field->id); ?>(<?php wpl_esc::e($i); ?>);" value="<?php wpl_esc::attr_t('Remove') ?>">
        </div>
	<?php endforeach; ?>

	</div>

    <input class="wpl-button button-1 wpl-save-btn" type="button" onclick="wpl_array_save_values_<?php wpl_esc::attr($field->id); ?>()" value="<?php wpl_esc::attr_t('Save') ?>">
	<span id="wpl_listing_saved_span_<?php wpl_esc::attr($field->id); ?>" class="wpl_listing_saved_span"></span>

	<script type="text/javascript">
	var wpl_array_param_counter_<?php wpl_esc::attr($field->id); ?> = <?php wpl_esc::e($i); ?>;

	function wpl_array_add_value_<?php wpl_esc::attr($field->id); ?>()
	{
		wpl_array_param_counter_<?php wpl_esc::attr($field->id); ?>++;

		html = '<div class="fanc-row" id="wpl_array_value_row_<?php wpl_esc::attr($field->id); ?>_'+wpl_array_param_counter_<?php wpl_esc::attr($field->id); ?>+'">'+
				'<input type="text" id="wpl_c_<?php wpl_esc::attr($field->id); ?>_index'+wpl_array_param_counter_<?php wpl_esc::attr($field->id); ?>+'" name="wpl_c_<?php wpl_esc::attr($field->id); ?>_index'+wpl_array_param_counter_<?php wpl_esc::attr($field->id); ?>+'" />'+
                '<input class="wpl-button button-1" type="button" onclick="wpl_array_remove_value_<?php wpl_esc::attr($field->id); ?>('+wpl_array_param_counter_<?php wpl_esc::attr($field->id); ?>+');" value="<?php wpl_esc::attr_t('Remove') ?>"></div>';

		wplj('#wpl_array_field_value_box<?php wpl_esc::attr($field->id); ?>').append(html);
	}

	function wpl_array_remove_value_<?php wpl_esc::attr($field->id); ?>(index)
	{
		wplj('#wpl_array_value_row_<?php wpl_esc::attr($field->id); ?>_'+index).remove();
	}

	function wpl_array_save_values_<?php wpl_esc::attr($field->id); ?>()
	{
		value = '';

		wplj('#wpl_array_field_value_box<?php wpl_esc::attr($field->id); ?> input[type="text"]').each(function(ind,element)
		{
			element_value = wplj(this).val().replaceAll('|',' ');
			value += element_value+'|';
		});

		render_value = value.substring(0, value.length - 1);

		wplj("#wpl_c_<?php wpl_esc::attr($field->id); ?>").val(render_value);

		ajax_save('<?php wpl_esc::js($field->table_name); ?>', '<?php wpl_esc::js($field->table_column); ?>', render_value, '<?php wpl_esc::js($item_id); ?>', '<?php wpl_esc::attr($field->id); ?>');
	}

	</script>

	<style>
		.wpl-array-field-values {
			display: inline-block;
		}
		.wpl-array-field-values-save {
			margin: 20px;
		}
	</style>
	<?php
	$done_this = true;
}