<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'gallery' and !$done_this)
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
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_ext_file"><?php wpl_esc::html_t('Valid file type'); ?></label>
				<input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_ext_file" id="<?php wpl_esc::attr($__prefix); ?>opt_ext_file" value="<?php wpl_esc::attr(isset($options['ext_file']) ? $options['ext_file'] : ''); ?>" />
			</div>
			<div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_file_size"><?php wpl_esc::html_t('Max file size (KB)'); ?></label>
				<input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_file_size" id="<?php wpl_esc::attr($__prefix); ?>opt_file_size" value="<?php wpl_esc::attr(isset($options['file_size']) ? $options['file_size'] : ''); ?>" />
			</div>
		</div>
	</div>
</div>
<?php
    $done_this = true;
}