<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1" id="wpl_delete_listing_property_type_cnt">
    <h2><?php wpl_esc::html_t('Removing property type'); ?> <?php wpl_esc::html_t($property_type_data->name); ?></h2>

    <div class="fanc-body wpl-del-options" id="pt-del-options">
        <div class="wpl_show_message<?php wpl_esc::attr($property_type_id); ?>" style="margin: 0 10px;"></div>

        <div onclick="purge_properties_property_type(<?php wpl_esc::attr($property_type_id); ?>)" id="purge_properties" class="button button-large wpl-purge">
            <?php wpl_esc::html_t('Purge related properties'); ?>
        </div>

        <div onclick="show_opt_2_property_type()" id="option_2" class="button button-primary button-large wpl-assign">
            <?php wpl_esc::html_t('Assign to another property type'); ?>
        </div>
    </div>

    <div class="fanc-body hidden" id="pt-del-plist">
        <div class="fanc-row fanc-button-row-2">
            <input class="wpl-button button-1" type="button" value="<?php wpl_esc::html_t('Assign'); ?>" onclick="assign_properties_property_type(<?php wpl_esc::attr($property_type_id); ?>);" />
        </div>
        <div class="fanc-row">
            <label for="property_type_select"><?php wpl_esc::html_t('Property Type'); ?></label>
            <select id="property_type_select">
                <option value="-1">-----</option>
                <?php
                foreach($property_types as $property_type)
                {
                    if($property_type['id'] == $property_type_id) continue;
				?>
					<option value="<?php wpl_esc::attr($property_type['id']); ?>"><?php wpl_esc::html_t($property_type['name']); ?></option>
					<?php
				}
				?>
            </select>
        </div>
    </div>
</div>