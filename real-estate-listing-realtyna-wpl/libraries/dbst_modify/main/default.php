<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$done_this = true;
?>
<div class="fanc-body">
    <div class="fanc-row fanc-button-row-2">
        <span class="ajax-inline-save" id="wpl_dbst_modify_ajax_loader"></span>
        <input class="wpl-button button-1" type="button" onclick="save_dbst('<?php wpl_esc::attr($__prefix); ?>', <?php wpl_esc::attr($dbst_id); ?>);" value="<?php wpl_esc::attr_t('Save'); ?>" id="wpl_dbst_submit_button" />
    </div>
    <div class="col-wp">
        <div class="col-fanc-left" id="wpl_flex_general_options">
        	<div class="fanc-row fanc-inline-title">
                <?php wpl_esc::html_t('General Options'); ?>
            </div>
            <?php
				/** include main file **/
				include _wpl_import('libraries.dbst_modify.main.main', true, true);
            ?>
        </div>
        <div class="col-fanc-right">
        	<div class="fanc-row fanc-inline-title">
                <?php wpl_esc::html_t('Specific Options'); ?>
            </div>
            <?php
				/** include specific file **/
				include _wpl_import('libraries.dbst_modify.main.'.($kind == 2 ? 'user' : '').'specific', true, true);
            ?>
        </div>
    </div>
    <div class="col-wp">
        <div class="col-fanc-left">
        	<div class="fanc-row fanc-inline-title">
                <?php wpl_esc::html_t('Accesses'); ?>
            </div>
            <?php
				/** include accesses file **/
				include _wpl_import('libraries.dbst_modify.main.accesses', true, true);
            ?>
        </div>
    </div>
</div>