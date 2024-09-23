<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if(in_array($type, array('price', 'mmprice', 'area', 'mmarea', 'length', 'mmlength', 'volume')) and !$done_this)
{
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
				/** include specific file * */
				include _wpl_import('libraries.dbst_modify.main.'.($kind == 2 ? 'user' : '').'specific', true, true);
			?>
            <div class="fanc-row fanc-inline-title">
				<span>
					<?php wpl_esc::html_t('Params'); ?>
				</span>
			</div>
			<div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_if_zero"><?php wpl_esc::html_t('If Zero'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_if_zero" id="<?php wpl_esc::attr($__prefix); ?>opt_if_zero">
                    <option <?php wpl_esc::e((isset($options['if_zero']) and $options['if_zero'] == 1) ? 'selected="selected"' : ''); ?> value="1"><?php wpl_esc::html_t('Show'); ?></option>
                    <option <?php wpl_esc::e((isset($options['if_zero']) and $options['if_zero'] == 0) ? 'selected="selected"' : ''); ?> value="0"><?php wpl_esc::html_t('Hide'); ?></option>
                    <option <?php wpl_esc::e((isset($options['if_zero']) and $options['if_zero'] == 2) ? 'selected="selected"' : ''); ?> value="2"><?php wpl_esc::html_t('Show Text'); ?></option>
                </select>
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_call_text"><?php wpl_esc::html_t('Text'); ?></label>
                <input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_call_text" id="<?php wpl_esc::attr($__prefix); ?>opt_call_text" value="<?php wpl_esc::attr(isset($options['call_text']) ? $options['call_text'] : 'Call'); ?>" />
			</div>

            <?php if(wpl_global::check_addon('pro') and in_array($type, array('area', 'mmarea'))): ?>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_conversion"><?php wpl_esc::html_t('Conversion'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_conversion" id="<?php wpl_esc::attr($__prefix); ?>opt_conversion">
                    <option <?php wpl_esc::e((isset($options['conversion']) and $options['conversion'] == 0) ? 'selected="selected"' : ''); ?> value="0"><?php wpl_esc::html_t('Disabled'); ?></option>
                    <option <?php wpl_esc::e((isset($options['conversion']) and $options['conversion'] == 1) ? 'selected="selected"' : ''); ?> value="1"><?php wpl_esc::html_t('Enabled'); ?></option>
                </select>
                <p><?php wpl_esc::html_t('Convert high values to Acre and Hectare'); ?></p>
			</div>
            <?php endif; ?>
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