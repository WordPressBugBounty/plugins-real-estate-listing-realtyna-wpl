<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'tag' and !$done_this)
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
				/** include specific file **/
				include _wpl_import('libraries.dbst_modify.main.'.($kind == 2 ? 'user' : '').'specific', true, true);
			?>
            <div class="fanc-row fanc-inline-title">
				<span>
					<?php wpl_esc::html_t('Params'); ?>
				</span>
			</div>
            <?php if(wpl_global::check_addon('tags')): $tag_categories = json_decode(wpl_global::get_setting('tags_categories') ?? '', true); ?>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_category"><?php wpl_esc::html_t('Category'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_category" id="<?php wpl_esc::attr($__prefix); ?>opt_category">
                    <option value="">----</option>
                    <?php foreach($tag_categories as $tag_category): ?>
                    <option value="<?php wpl_esc::attr($tag_category); ?>" <?php wpl_esc::e((isset($options['category']) and $options['category'] == $tag_category) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t($tag_category); ?></option>
                    <?php endforeach; ?>
                </select>
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_ribbon"><?php wpl_esc::html_t('Ribbon'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_ribbon" id="<?php wpl_esc::attr($__prefix); ?>opt_ribbon">
                    <option value="1" <?php wpl_esc::e((isset($options['ribbon']) and $options['ribbon'] == 1) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Show'); ?></option>
                    <option value="0" <?php wpl_esc::e((isset($options['ribbon']) and $options['ribbon'] == 0) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('hide'); ?></option>
                </select>
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_widget"><?php wpl_esc::html_t('Tags Widget'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_widget" id="<?php wpl_esc::attr($__prefix); ?>opt_widget">
                    <option value="1" <?php wpl_esc::e((isset($options['widget']) and $options['widget'] == 1) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Show'); ?></option>
                    <option value="0" <?php wpl_esc::e((isset($options['widget']) and $options['widget'] == 0) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('hide'); ?></option>
                </select>
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_color"><?php wpl_esc::html_t('Background Color'); ?></label>
                <input class="wpl-flex-tag-field-color" type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_color" id="<?php wpl_esc::attr($__prefix); ?>opt_color" value="<?php wpl_esc::attr(isset($options['color']) ? $options['color'] : '29a9df'); ?>" />
			</div>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_text_color"><?php wpl_esc::html_t('Text Color'); ?></label>
                <input class="wpl-flex-tag-field-color" type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_text_color" id="<?php wpl_esc::attr($__prefix); ?>opt_text_color" value="<?php wpl_esc::attr(isset($options['text_color']) ? $options['text_color'] : 'ffffff'); ?>" />
			</div>
            <?php if(!$dbst_id): ?>
            <div class="fanc-row">
				<label for="<?php wpl_esc::attr($__prefix); ?>opt_default_value"><?php wpl_esc::html_t('Labeled by default'); ?></label>
                <select name="<?php wpl_esc::attr($__prefix); ?>opt_default_value" id="<?php wpl_esc::attr($__prefix); ?>opt_default_value">
                    <option value="0" <?php wpl_esc::e((isset($options['default_value']) and $options['default_value'] == 0) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('No'); ?></option>
                    <option value="1" <?php wpl_esc::e((isset($options['default_value']) and $options['default_value'] == 1) ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Yes'); ?></option>
                </select>
			</div>
            <?php endif; ?>
            <?php else: ?>
            <div class="fanc-row">
                <span><?php wpl_esc::html_t('The Tags Add-on must be installed for this feature!'); ?></span>
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