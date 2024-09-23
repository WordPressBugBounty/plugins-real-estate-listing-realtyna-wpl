<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'url' and !$done_this)
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
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_link_title"><?php wpl_esc::html_t('Link title'); ?></label>
				<input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_link_title" id="<?php wpl_esc::attr($__prefix); ?>opt_link_title" value="<?php wpl_esc::attr(isset($options['link_title']) ? $options['link_title'] : ''); ?>" />
			</div>
			<div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_link_target"><?php wpl_esc::html_t('Target'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_link_target" id="<?php wpl_esc::attr($__prefix); ?>opt_link_target">
                    <option <?php wpl_esc::e((isset($options['link_target']) and $options['link_target'] == '_blank') ? 'selected="selected"' : ''); ?> value="_blank"><?php wpl_esc::html_t('New Window'); ?></option>
                    <option <?php wpl_esc::e((isset($options['link_target']) and $options['link_target'] == '_self') ? 'selected="selected"' : ''); ?> value="_self"><?php wpl_esc::html_t('Current Window'); ?></option>
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