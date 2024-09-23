<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1" id="wpl_delete_listing_property_type_cnt">
    <h2><?php wpl_esc::html_t('Removing listing type') ?> <?php wpl_esc::html_t($listing_type_data->name); ?></h2>

    <div class="fanc-body wpl-del-options" id="lt-del-options">
        <div class="wpl_show_message<?php wpl_esc::attr($listing_type_id); ?>" style="margin: 0 10px;"></div>

        <div onclick="purge_properties_listing_type(<?php wpl_esc::attr($listing_type_id); ?>)" id="purge_properties" class="button button-large wpl-purge">
            <?php wpl_esc::html_t('Purge related properties'); ?>
        </div>

        <div onclick="show_opt_2_listing_type()" id="option_2" class="button button-primary button-large wpl-assign">
            <?php wpl_esc::html_t('Assign to another listing type'); ?>
        </div>
    </div>

    <div class="fanc-body hidden" id="lt-del-plist">
        <div class="fanc-row fanc-button-row-2">
            <input type="button" class="wpl-button button-1" value="<?php wpl_esc::html_t('Assign'); ?>" onclick="assign_properties_listing_type(<?php wpl_esc::attr($listing_type_id); ?>);" />
        </div>
        <div class="fanc-row">
            <label for="listing_type_select"><?php wpl_esc::html_t('Listing Type'); ?></label>
            <select id="listing_type_select">
                <option value="-1">-----</option>
                <?php
                foreach($listing_types as $listing_type)
                {
                    if($listing_type['id'] == $listing_type_id) continue;
                ?>
				<option value="<?php wpl_esc::attr($listing_type['id']); ?>"><?php wpl_esc::html_t($listing_type['name']); ?></option>
				<?php
				}
                ?>
            </select>
        </div>
    </div>
</div>