<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if((isset($values->specificable) and $values->specificable) or !$dbst_id)
{
?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>specificable"><?php wpl_esc::html_t('Specificable'); ?></label>
    <select id="<?php wpl_esc::attr($__prefix); ?>specificable" name="<?php wpl_esc::attr($__prefix); ?>specificable" onchange="wpl_flex_change_specificable(this.value, '<?php wpl_esc::attr($__prefix); ?>');">
        <option value="0"><?php wpl_esc::html_t('No'); ?></option>
        <option value="1" <?php if( trim($values->listing_specific ?? '') != '') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Listing specific'); ?></option>
        <option value="2" <?php if( trim($values->property_type_specific ?? '' ) != '') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Property type specific'); ?></option>
        <option value="4" <?php if( trim($values->field_specific ?? '') != '') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Field specific'); ?></option>
    </select>
    <div class="wpl_flex_specificable_cnt" id="<?php wpl_esc::attr($__prefix); ?>specificable1" style="<?php if(!isset($values->listing_specific) or ( trim($values->listing_specific ?? '') == '')) wpl_esc::e('display: none;'); ?>">
        <?php if(!$dbst_id or (isset($values->specificable) and ($values->specificable == 1 or $values->specificable == 2))): ?>
        <ul id="<?php wpl_esc::attr($__prefix); ?>_listing_specific" class="wpl_listing_specific_ul">
            <li><input id="wpl_flex_listing_checkbox_all" type="checkbox" onclick="wpl_listing_specific_all(this.checked)" <?php if(!isset($values->listing_specific) or ( trim($values->listing_specific ?? '') == '')) wpl_esc::e('checked="checked"'); ?> /><label class="wpl_specific_label" for="wpl_flex_listing_checkbox_all">&nbsp;<?php wpl_esc::html_t('All'); ?></label></li>
            <?php
            $listing_specific = isset($values->listing_specific) ? explode(',', $values->listing_specific) : array();
            foreach($listings as $listing)
            {
                ?>
                <li><input id="wpl_flex_listing_checkbox<?php wpl_esc::attr($listing['id']); ?>" type="checkbox" value="<?php wpl_esc::attr($listing['id']); ?>" <?php if(!isset($values->listing_specific) or ( trim($values->listing_specific ?? '') == '') or in_array($listing['id'], $listing_specific)) wpl_esc::e('checked="checked"'); if(!isset($values->listing_specific) or ( trim($values->listing_specific ?? '') == '')) wpl_esc::e('disabled="disabled"'); ?> /><label class="wpl_specific_label" for="wpl_flex_listing_checkbox<?php wpl_esc::attr($listing['id']); ?>">&nbsp;<?php wpl_esc::html_t($listing['name']); ?></label></li>
                <?php
            }
            ?>
        </ul>
        <?php endif; ?>
    </div>
    <div class="wpl_flex_specificable_cnt" id="<?php wpl_esc::attr($__prefix); ?>specificable2" style="<?php if(!isset($values->property_type_specific) or ( trim($values->property_type_specific ?? '') == '')) wpl_esc::e('display: none;'); ?>">
        <?php if(!$dbst_id or (isset($values->specificable) and ($values->specificable == 1 or $values->specificable == 3))): ?>
        <ul id="<?php wpl_esc::attr($__prefix); ?>_property_type_specific" class="wpl_property_type_specific_ul">
            <li><input id="wpl_flex_property_type_checkbox_all" type="checkbox" onclick="wpl_property_type_specific_all(this.checked)" <?php if(!isset($values->property_type_specific) or ( trim($values->property_type_specific ?? '') == '')) wpl_esc::e('checked="checked"'); ?> /><label class="wpl_specific_label" for="wpl_flex_property_type_checkbox_all">&nbsp;<?php wpl_esc::html_t('All'); ?></label></li>
            <?php
            $property_type_specific = isset($values->property_type_specific) ? explode(',', $values->property_type_specific) : array();
            foreach($property_types as $property_type)
            {
                ?>
                <li><input id="wpl_flex_property_type_checkbox<?php wpl_esc::attr($property_type['id']); ?>" type="checkbox" value="<?php wpl_esc::attr($property_type['id']); ?>" <?php if(!isset($values->property_type_specific) or ( trim($values->property_type_specific ?? '') == '') or in_array($property_type['id'], $property_type_specific)) wpl_esc::e('checked="checked"'); if(!isset($values->property_type_specific) or ( trim($values->property_type_specific ?? '') == '')) wpl_esc::e('disabled="disabled"'); ?> /><label class="wpl_specific_label" for="wpl_flex_property_type_checkbox<?php wpl_esc::attr($property_type['id']); ?>">&nbsp;<?php wpl_esc::html_t($property_type['name']); ?></label></li>
                <?php
            }
            ?>
        </ul>
        <?php endif; ?>
    </div>

    <div class="wpl_flex_specificable_cnt" id="<?php wpl_esc::attr($__prefix); ?>specificable4" style="<?php if(!isset($values->field_specific) or (isset($values->field_specific) and trim($values->field_specific) == '')) wpl_esc::e('display: none;'); ?>">
        <?php if(!$dbst_id or (isset($values->specificable) and ($values->specificable == 1 or $values->specificable == 4))): ?>
        
        <?php 
        $field_name = '';
        $field_value = '';
        $fields = wpl_flex::get_fields('', 0, 0, '', '', wpl_db::prepare("AND `type` IN ('feature','neighborhood','boolean','checkbox','') AND `kind` = %d AND `enabled` > 0", $kind));
        
        if(isset($values->field_specific) and trim($values->field_specific ?? '') != '')
        {
            $value = explode(':', $values->field_specific);
            $field_name = $value[0];
            $field_value = $value[1];
        }
        ?>

        <label for="<?php wpl_esc::attr($__prefix); ?>field_specific_name"><?php wpl_esc::html_t('Field'); ?></label>
        <select id="<?php wpl_esc::attr($__prefix); ?>field_specific_name" name="<?php wpl_esc::attr($__prefix); ?>field_specific_name" onchange="wpl_flex_change_field_specific_fields(this.value, '<?php wpl_esc::attr($__prefix); ?>','#<?php wpl_esc::attr($__prefix); ?>field_specific_value');">
            <?php foreach ($fields as $field): ?>
                <option value="<?php wpl_esc::attr($field->id); ?>" <?php if($field_name == $field->id) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($field->name); ?></option>
            <?php endforeach; ?>            
        </select>

        <label for="<?php wpl_esc::attr($__prefix); ?>field_specific_value"><?php wpl_esc::html_t('Value'); ?></label>
        <select id="<?php wpl_esc::attr($__prefix); ?>field_specific_value" name="<?php wpl_esc::attr($__prefix); ?>field_specific_value">
            <?php
                if (!empty($fields)) {
                    $field_name = !empty($field_name) ? $field_name : reset($fields)->id;
                    $field = (array) wpl_flex::get_field($field_name);
                    $field_values = json_decode($field['options']);

                    if (in_array($field['type'], array('neighborhood', 'boolean', 'checkbox'))) {
						wpl_esc::e('<option value="0"' . ($field_value == 0 ? ' selected="selected"' : '') . '>' . wpl_esc::return_html_t('No') . '</option>');
						wpl_esc::e('<option value="1"' . ($field_value == 1 ? ' selected="selected"' : '') . '>' . wpl_esc::return_html_t('Yes') . '</option>');
                    } elseif ($field_values->type == "none" || empty($field_values)) {
						wpl_esc::e('<option>' . wpl_esc::return_html_t('No option') . '</option>');
                    } else {
                        foreach ($field_values->values as $value) {
							wpl_esc::e('<option value="' . $value->key . '"' . ($field_value == $value->key ? ' selected="selected"' : '') . '>' . wpl_esc::return_html($value->value) . '</option>');
                        }
                    }
                }
            ?> 
        </select>

        <?php endif; ?>
    </div>
</div>
<?php
}
?>