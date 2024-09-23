<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$kind = trim(wpl_request::getVar('kind') ?? "") != '' ? wpl_request::getVar('kind') : 0;
?>
<div class="fanc-content size-width-1">
    <h2><?php wpl_esc::html_t(isset($this->category) ? 'Edit Category'  : 'Add Category'); ?></h2>
    <div class="wpl_show_message" id="wpl_category_form_message"></div>
    <div class="fanc-body">
        <div class="fanc-row">
            <label for="category_name"><?php wpl_esc::html_t('Name'); ?></label>
            <input class="text_box" type="text" id="category_name" value="<?php wpl_esc::attr(isset($this->category) ? $this->category->name : ''); ?>" />
            <input type="hidden" id="category_kind" value="<?php wpl_esc::attr($kind); ?>" />
        </div>
        <div class="fanc-row fanc-button-row">
            <div id="wpl_category_form_loader"></div>
            <input class="wpl-button button-1" onclick="wpl_save_category('<?php wpl_esc::attr(isset($this->category) ? $this->category->id : ''); ?>')" value="<?php wpl_esc::attr_t('Save'); ?>" type="button">
        </div>
    </div>
</div>