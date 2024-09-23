<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'boolean' and !$done_this)
{
	$isNullable = wpl_flex::isNullable($dbst_id);
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
				/** include main file * */
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
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_true_label"><?php wpl_esc::html_t('True Label'); ?></label>
                <input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_true_label" id="<?php wpl_esc::attr($__prefix); ?>opt_true_label" value="<?php wpl_esc::attr(isset($options['true_label']) ? $options['true_label'] : 'Yes'); ?>" />
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_false_label"><?php wpl_esc::html_t('False Label'); ?></label>
                <input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_false_label" id="<?php wpl_esc::attr($__prefix); ?>opt_false_label" value="<?php wpl_esc::attr(isset($options['false_label']) ? $options['false_label'] : 'No'); ?>" />
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_default_value"><?php wpl_esc::html_t('Default Value'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_default_value" id="<?php wpl_esc::attr($__prefix); ?>opt_default_value">
					<?php if($isNullable || $options['default_value'] === ''): ?>
                    <option value="" <?php wpl_esc::e($options['default_value'] === '' ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Null'); ?></option>
					<?php endif; ?>
                    <option value="1" <?php wpl_esc::e((isset($options['default_value']) and $options['default_value'] == 1) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('True'); ?></option>
                    <option value="0" <?php wpl_esc::e($options['default_value'] == '0' ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('False'); ?></option>
                </select>
			</div>
			<div class="fanc-row">
				<?php if($isNullable === false): ?>
					It looks like the column is not nullable, click the button below to make it nullable.
					<input style="margin-top: 10px;" class="wpl-button button-1" type="button" onclick="makeNullable(<?php wpl_esc::attr($dbst_id); ?>);" value="<?php wpl_esc::attr_t('Make Nullable'); ?>" />
					<span id="wpl_dbst_make_nullable_ajax_loader"></span>
				<?php endif; ?>
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