<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'parent' and !$done_this)
{
    $kinds = wpl_flex::get_kinds('wpl_properties');
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
		<div class="col-fanc-right" id="wpl_flex_specific_options">
			<div class="fanc-row fanc-inline-title">
				<?php wpl_esc::html_t('Specific Options'); ?>
			</div>
			<?php
				/** include specific file **/
				include _wpl_import('libraries.dbst_modify.main.'.($kind == 2 ? 'user' : '').'specific', true, true);
			?>
            <div class="fanc-row fanc-inline-title">
				<span>
					<?php wpl_esc::html_t('Params'); ?>
				</span>
			</div>
			<div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_parent_kind"><?php wpl_esc::html_t('Parent Kind'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_parent_kind" id="<?php wpl_esc::attr($__prefix); ?>opt_parent_kind">
                    <?php foreach($kinds as $kind): ?>
                    <option <?php wpl_esc::e((isset($options['parent_kind']) and $options['parent_kind'] == $kind['id']) ? 'selected="selected"' : ''); ?> value="<?php wpl_esc::attr($kind['id']); ?>"><?php wpl_esc::html_t($kind['name']); ?></option>
                    <?php endforeach; ?>
                </select>
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_replace"><?php wpl_esc::html_t('Replace Parent Data'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_replace" id="<?php wpl_esc::attr($__prefix); ?>opt_replace">
                    <option <?php wpl_esc::e((isset($options['replace']) and $options['replace'] == 1) ? 'selected="selected"' : ''); ?> value="1"><?php wpl_esc::html_t('Yes'); ?></option>
                    <option <?php wpl_esc::e((isset($options['replace']) and $options['replace'] == 0) ? 'selected="selected"' : ''); ?> value="0"><?php wpl_esc::html_t('No'); ?></option>
                </select>
			</div>
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
<?php
    $done_this = true;
}