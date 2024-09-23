<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_unit_manager_js');
$this->_wpl_import($this->tpl_path . '.scripts.internal_unit_manager_css');
?>
<div class="unit-manager-wp">
    <div class="panel-wp">
        <div class="panel-body">
            <span><?php wpl_esc::html_t('Unit type'); ?> : </span>
            <select class="selectbox" onchange="load_new_unit_category(this.value);">
                <?php foreach ($unit_types as $id => $wp_unite_type): ?>
                <option value="<?php wpl_esc::attr($wp_unite_type['id']); ?>" <?php if ($wp_unite_type['id'] == 4) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($wp_unite_type['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <span id="wpl_ajax_loader_span"></span>
        </div>
    </div>
    <!-- end of filtering panel -->
    <div id="unit_manager_content">
    	<?php $this->generate_currency_page(); ?>
    </div>
</div>
