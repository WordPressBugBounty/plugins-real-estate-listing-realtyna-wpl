<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$kind_table = wpl_flex::get_kind_table($kind, $cat_id);
?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>category"><?php wpl_esc::html_t('Data category'); ?></label>
    <select id="<?php wpl_esc::attr($__prefix); ?>category" name="<?php wpl_esc::attr($__prefix); ?>data_category">
        <?php foreach ($dbcats as $dbcat): ?>
        <option value="<?php wpl_esc::attr($dbcat->id); ?>" <?php if (isset($values->category) and $dbcat->id == $values->category) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::attr($dbcat->name); ?></option>
        <?php endforeach; ?>
    </select>
    <!-- hidden fields -->
    <input type="hidden" name="<?php wpl_esc::attr($__prefix); ?>type" id="<?php wpl_esc::attr($__prefix); ?>type" value="<?php wpl_esc::attr($type); ?>" />
    <input type="hidden" name="<?php wpl_esc::attr($__prefix); ?>kind" id="<?php wpl_esc::attr($__prefix); ?>kind" value="<?php wpl_esc::attr($kind); ?>" />
    <input type="hidden" name="<?php wpl_esc::attr($__prefix); ?>table_name" id="<?php wpl_esc::attr($__prefix); ?>table_name" value="<?php wpl_esc::attr($kind_table); ?>" />
</div>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>name"><?php wpl_esc::html_t('Name'); ?></label>
    <input type="text" name="<?php wpl_esc::attr($__prefix); ?>name" id="<?php wpl_esc::attr($__prefix); ?>name" value="<?php wpl_esc::attr($values->name ?? ''); ?>" />
</div>

<?php if($kind_table == 'wpl_properties' and (!isset($values->id) or (isset($values->id) and trim($values->table_column ?? ''))) and (!isset($values->deletable) or (isset($values->deletable) and $values->deletable))): ?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>storage"><?php wpl_esc::html_t('Storage'); ?></label>
    <select class="wpl-storage-method" name="<?php wpl_esc::attr($__prefix); ?>storage" id="<?php wpl_esc::attr($__prefix); ?>storage">
        <option value="wpl_properties2" <?php if(isset($values->table_name) and $values->table_name == 'wpl_properties2') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Normal Storage'); ?></option>
        <option value="wpl_properties" <?php if(isset($values->table_name) and $values->table_name == 'wpl_properties') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Search-able Storage'); ?></option>
    </select>
</div>
<?php endif; ?>

<div class="fanc-row wpl-storage-field wpl-storage-wpl_properties">
    <label for="<?php wpl_esc::attr($__prefix); ?>text_search"><?php wpl_esc::html_t('Text Search'); ?></label>
    <select name="<?php wpl_esc::attr($__prefix); ?>text_search" id="<?php wpl_esc::attr($__prefix); ?>text_search">
        <option value="1" <?php if (isset($values->text_search) and $values->text_search == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
        <option value="0" <?php if (isset($values->text_search) and $values->text_search == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
    </select>
</div>
<?php if(wpl_global::check_multilingual_status() and (in_array($type, array('text', 'textarea', 'meta_key', 'meta_desc')) or (isset($values->type) and in_array($values->type, array('text', 'textarea', 'meta_key', 'meta_desc'))))): ?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>multilingual"><?php wpl_esc::html_t('Multilingual'); ?></label>
    <select name="<?php wpl_esc::attr($__prefix); ?>multilingual" id="<?php wpl_esc::attr($__prefix); ?>multilingual">
        <option value="0" <?php if (isset($values->multilingual) and $values->multilingual == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
        <option value="1" <?php if (isset($values->multilingual) and $values->multilingual == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
    </select>
</div>
<?php endif; ?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>pshow"><?php wpl_esc::html_t('Detail Page'); ?></label>
    <select name="<?php wpl_esc::attr($__prefix); ?>pshow" id="<?php wpl_esc::attr($__prefix); ?>pshow">
        <option value="1" <?php if (isset($values->pshow) and $values->pshow == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Show'); ?></option>
        <option value="0" <?php if (isset($values->pshow) and $values->pshow == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hide'); ?></option>
    </select>
</div>
<div class="fanc-row wpl-storage-field wpl-storage-wpl_properties">
    <label for="<?php wpl_esc::attr($__prefix); ?>searchmod"><?php wpl_esc::html_t('Search Widget'); ?></label>
    <select name="<?php wpl_esc::attr($__prefix); ?>searchmod" id="<?php wpl_esc::attr($__prefix); ?>searchmod">
        <option value="1" <?php if (isset($values->searchmod) and $values->searchmod == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Show'); ?></option>
        <option value="0" <?php if (isset($values->searchmod) and $values->searchmod == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hide'); ?></option>
    </select>
</div>
<?php if(wpl_global::check_addon('pro')): ?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>pdf"><?php wpl_esc::html_t('PDF Flyer'); ?></label>
    <select name="<?php wpl_esc::attr($__prefix); ?>pdf" id="<?php wpl_esc::attr($__prefix); ?>pdf">
        <option value="1" <?php if (isset($values->pdf) and $values->pdf == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Show'); ?></option>
        <option value="0" <?php if (isset($values->pdf) and $values->pdf == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hide'); ?></option>
    </select>
</div>
<?php endif; ?>

<?php if(wpl_global::check_addon('save_searches') and wpl_global::check_addon('crm')): ?>
<div class="fanc-row">
    <label for="<?php wpl_esc::attr($__prefix); ?>savesearch"><?php wpl_esc::html_t('Save Search'); ?></label>
    <select name="<?php wpl_esc::attr($__prefix); ?>savesearch" id="<?php wpl_esc::attr($__prefix); ?>savesearch">
        <option value="1" <?php if (isset($values->savesearch) and $values->savesearch == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Show'); ?></option>
        <option value="0" <?php if (isset($values->savesearch) and $values->savesearch == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hide'); ?></option>
    </select>
</div>
<?php endif; ?>

<?php if(wpl_global::is_multisite() and wpl_users::is_super_admin()): ?>
<div class="fanc-row" id="multisite_modify_status_container">
    <label for="multisite_modify_status"><?php wpl_esc::html_t('Network Apply'); ?></label>
    <select name="multisite_modify_status" id="multisite_modify_status">
        <option value="0" <?php if (isset($values->multisite_modify_status) and $values->multisite_modify_status == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
        <option value="1" <?php if (isset($values->multisite_modify_status) and $values->multisite_modify_status == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
    </select>
</div>
<?php endif; ?>

<?php if(wpl_global::check_addon('pro') and isset($values->comparable) and intval($values->comparable)): ?>
    <div class="fanc-row">
        <label for="<?php wpl_esc::attr($__prefix); ?>compare_visible"><?php wpl_esc::html_t('Compare'); ?></label>
        <select onchange="wpl_flex_compare_change(this)" name="<?php wpl_esc::attr($__prefix); ?>compare_visible" id="<?php wpl_esc::attr($__prefix); ?>compare_visible">
            <option value="1" <?php if(isset($values->comparable) and intval($values->compare_visible)) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Show'); ?></option>
            <option value="0" <?php if(isset($values->comparable) and !intval($values->compare_visible)) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hide'); ?></option>
        </select>
    </div>
    <?php if(isset($values->comparable_row) && intval($values->comparable_row)): ?>
        <div class="fanc-row wpl-compare-row-container <?php if (isset($values->comparable) and !intval($values->compare_visible)) wpl_esc::e('hide'); ?>">
            <label for="<?php wpl_esc::attr($__prefix); ?>compare_row"><?php wpl_esc::html_t('Compare row'); ?></label>
            <select name="<?php wpl_esc::attr($__prefix); ?>compare_row" id="<?php wpl_esc::attr($__prefix); ?>compare_row">
                <option value="0" <?php if (isset($values->compare_row) and intval($values->compare_row) == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if (isset($values->compare_row) and intval($values->compare_row) == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Higher is better'); ?></option>
                <option value="2" <?php if (isset($values->compare_row) and intval($values->compare_row) == 2) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Lower is better'); ?></option>
            </select>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php if(isset($values->id)): ?>
<div class="fanc-row">
    <label><?php wpl_esc::html_t('Field ID'); ?></label>
    <input type="text" disabled="disabled" value="<?php wpl_esc::attr($values->id ?? ''); ?>" placeholder="<?php wpl_esc::attr_t('Field ID'); ?>" />
</div>
<div class="fanc-row">
    <label><?php wpl_esc::html_t('Column Name'); ?></label>
    <input type="text" disabled="disabled" value="<?php wpl_esc::attr($values->table_column ?? ''); ?>" placeholder="<?php wpl_esc::attr_t('Column Name'); ?>" />
</div>
<?php endif; ?>
