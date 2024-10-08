<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="panel-wp">
    <h3><?php wpl_esc::html_t('Add new field'); ?></h3>
    <div class="panel-body">
        <select id="wpl_dbst_types_select">
            <?php foreach ($this->dbst_types as $dbst_type): ?>
                <option value="<?php wpl_esc::attr($dbst_type->type); ?>"><?php wpl_esc::attr($dbst_type->type); ?></option>
            <?php endforeach; ?>
        </select>
        <input data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_flex_edit_div" type="button" class="wpl-button button-1" onclick="generate_modify_page(0);" value="<?php wpl_esc::attr_t('Add'); ?>" />
    </div>
</div>
<?php if(in_array($this->kind, $this->dbcat_manager_kinds)): ?>
    <div class="panel-wp">
        <h3><?php wpl_esc::html_t('Add new Category'); ?></h3>
        <div class="panel-body">
            <input data-realtyna-lightbox="" data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_flex_category" class="wpl-button button-1" onclick="wpl_category_form(0);" value="<?php wpl_esc::attr_t('Create a new category')?>" type="button">
        </div>
    </div>
<?php endif; ?>