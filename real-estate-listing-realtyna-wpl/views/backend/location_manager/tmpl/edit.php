<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-1">
    <h2><?php wpl_esc::html_t($this->location_id ? 'Edit location' : 'Add location'); ?></h2>
    <div class="fanc-body">
        <div class="fanc-row">
            <label for="wpl_location_name"><?php wpl_esc::html_t('Name'); ?></label>
            <input class="text_box" type="text" id="wpl_location_name" value="<?php wpl_esc::attr(wpl_global::isset_object('name', $this->location_data)); ?>" autocomplete="off" />
        </div>
        <?php if($this->level != 'zips'): ?>
        <div class="fanc-row">
            <label for="wpl_location_abbr"><?php wpl_esc::html_t('Abbreviation'); ?></label>
            <input class="text_box" type="text" id="wpl_location_abbr" value="<?php wpl_esc::attr(wpl_global::isset_object('abbr', $this->location_data)); ?>" autocomplete="off" />
        </div>
        <?php endif; ?>
        <div class="fanc-row fanc-button-row">
            <?php if ($this->location_id): ?>
                <input type="hidden" id="wpl_location_id" value="<?php wpl_esc::attr($this->location_id); ?>" />
                <input class="wpl-button button-1" type="submit" id="wpl_submit" value="<?php wpl_esc::attr_t('Save'); ?>" onclick="wpl_ajax_modify_location('<?php wpl_esc::attr($this->level); ?>', '', '<?php wpl_esc::attr($this->location_id); ?>');" />
            <?php else: ?>
                <input class="wpl-button button-1" type="submit" id="wpl_submit" value="<?php wpl_esc::attr_t('Save'); ?>" onclick="wpl_ajax_modify_location('<?php wpl_esc::attr($this->level); ?>', '<?php wpl_esc::attr($this->parent); ?>');" />
            <?php endif; ?>
            <span class="ajax-inline-save" id="wpl_ajax_loader"></span>
        </div>
        <div class="wpl_show_message_location"></div>
    </div>
</div>