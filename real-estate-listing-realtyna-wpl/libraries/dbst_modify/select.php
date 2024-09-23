<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if(in_array($type, array('select', 'multiselect')) and !$done_this)
{
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
    wpl_flex_select_sortable();
});

function wpl_flex_select_sortable()
{
    wplj(".wpl-flex-select-sortable").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move" ,
        update : function(e, ui)
        {
            var prefix = "<?php wpl_esc::attr($__prefix); ?>";
            var sortedItems = wplj(".wpl-flex-select-sortable").sortable("toArray");
            var newIndex = sortedItems.indexOf(ui.item.attr('id'));
        }
    });
}

function wpl_flex_add_param()
{
    html = '<div class="fanc-row" id="wpl_flex_select_params_row' + wpl_flex_param_counter + '">' +
        '<input type="hidden" id="<?php wpl_esc::attr($__prefix); ?>opt_params[' + wpl_flex_param_counter + '][key]" ' +
        'name="<?php wpl_esc::attr($__prefix); ?>opt_params[' + wpl_flex_param_counter + '][key]" value="' + wpl_flex_param_counter + '" />' +
        '<input type="hidden" id="<?php wpl_esc::attr($__prefix); ?>opt_params[' + wpl_flex_param_counter + '][enabled]" ' +
        'name="<?php wpl_esc::attr($__prefix); ?>opt_params[' + wpl_flex_param_counter + '][enabled]" value="1" />' +
        '<input type="text" id="<?php wpl_esc::attr($__prefix); ?>opt_params[' + wpl_flex_param_counter + '][value]" name="<?php wpl_esc::attr($__prefix); ?>opt_params[' + wpl_flex_param_counter + '][value]" value="" />' +
        '<span class="margin-left-1p action-btn icon-enabled" id="wpl_felx_change_param_status' + wpl_flex_param_counter + '" onclick="wpl_flex_disable_param(' + wpl_flex_param_counter + ');"  title="Disable"></span>' +
        '<span class="action-btn icon-move ui-sortable-handle" title="Move"></span>' +
        '<input type="radio" id="<?php wpl_esc::attr($__prefix); ?>opt_default_value" name="<?php wpl_esc::attr($__prefix); ?>opt_default_value" value="' + wpl_flex_param_counter + '" title="Default"/></div>';
    wplj("#wpl_flex_select_before").before(html);

	wpl_flex_param_counter++;
    wpl_flex_select_sortable();
}

function wpl_flex_disable_param(param_id)
{
	if (wplj("#wpl_felx_change_param_status" + param_id).hasClass("wpl_actions_icon_enable"))
	{
		wplj("#wpl_flex_select_params_row" + param_id + " input[type='text']").attr("disabled", "disabled");
        wplj("#wpl_felx_change_param_status" + param_id).attr("title", "Enable");
		wplj("#wpl_felx_change_param_status" + param_id).removeClass("wpl_actions_icon_enable");
		wplj("#wpl_felx_change_param_status" + param_id).removeClass("icon-enabled");
		wplj("#wpl_felx_change_param_status" + param_id).addClass("wpl_actions_icon_disable");
		wplj("#wpl_felx_change_param_status" + param_id).addClass("icon-disabled");
		wplj("#wpl_flex_select_params_row" + param_id + " input[name='<?php wpl_esc::attr($__prefix); ?>opt_params[" + param_id + "][enabled]']").val(0);
	}
	else
	{
		wplj("#wpl_flex_select_params_row" + param_id + " input[type='text']").removeAttr("disabled");
        wplj("#wpl_felx_change_param_status" + param_id).attr("title", "Disable");
		wplj("#wpl_felx_change_param_status" + param_id).removeClass("wpl_actions_icon_disable");
		wplj("#wpl_felx_change_param_status" + param_id).removeClass("icon-disabled");
		wplj("#wpl_felx_change_param_status" + param_id).addClass("icon-enabled");
		wplj("#wpl_felx_change_param_status" + param_id).addClass("wpl_actions_icon_enable");
		wplj("#wpl_flex_select_params_row" + param_id + " input[name='<?php wpl_esc::attr($__prefix); ?>opt_params[" + param_id + "][enabled]']").val(1);
	}
}
</script>
<div class="fanc-body">
	<div class="fanc-row fanc-button-row-2">
        <span class="ajax-inline-save" id="wpl_dbst_modify_ajax_loader"></span>
		<?php if($dbst_id and $dbst_id >= 3000): ?><input class="wpl-button button-2" type="button" onclick="convert_dbst('<?php wpl_esc::attr($__prefix); ?>', <?php wpl_esc::attr($dbst_id); ?>, 'feature');" value="<?php wpl_esc::attr_t('Convert to Feature'); ?>" id="wpl_dbst_convert_button" /><?php endif; ?>
		<input class="wpl-button button-1" type="button" onclick="save_dbst('<?php wpl_esc::attr($__prefix); ?>', <?php wpl_esc::attr($dbst_id); ?>);" value="<?php wpl_esc::html_t('Save'); ?>" id="wpl_dbst_submit_button" />
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
				<span class="action-btn icon-plus margin-left-1p" onclick="wpl_flex_add_param();" title="Add"></span>
			</div>
            <div class="wpl-flex-select-sortable">
                <?php
                $i = 1;
                $option_params = (isset($options['params']) and is_array($options['params'])) ? $options['params'] : array();
                foreach ($option_params as $k => $v):
                ?>
                <div class="fanc-row" id="wpl_flex_select_params_row<?php wpl_esc::e($i); ?>">
                    <input type="text" id="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::e($i); ?>][value]" name="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::e($i); ?>][value]" value="<?php wpl_esc::attr($v['value'] ?? ''); ?>" <?php wpl_esc::e($v['enabled'] ? '' : 'disabled="disabled"'); ?> />
                    <span class="margin-left-1p action-btn icon-<?php wpl_esc::e($v['enabled'] ? 'enabled wpl_actions_icon_enable' : 'disabled'); ?>" id="wpl_felx_change_param_status<?php wpl_esc::e($i); ?>" onclick="wpl_flex_disable_param(<?php wpl_esc::e($i); ?>);" title="<?php wpl_esc::attr_t($v['enabled'] ? 'Disable' : 'Enable'); ?>"></span>
                    <span class="action-btn icon-move ui-sortable-handle" title="Move"></span>
                    <input type="hidden" id="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::e($i); ?>][key]" name="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::e($i); ?>][key]" value="<?php wpl_esc::attr($v['key']); ?>" />
                    <input type="hidden" id="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::e($i); ?>][enabled]" name="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::e($i); ?>][enabled]" value="<?php wpl_esc::attr($v['enabled']); ?>" />
                    <input type="radio" id="<?php wpl_esc::attr($__prefix); ?>opt_default_value" name="<?php wpl_esc::attr($__prefix); ?>opt_default_value" value="<?php wpl_esc::attr($v['key']); ?>" <?php if (array_key_exists('default_value', $options) && $options['default_value'] == $v['key']) wpl_esc::e('checked'); ?> title="Default">
                </div>
                <?php $i++; endforeach; ?>
                <div id="wpl_flex_select_before"></div>
            </div>
			<script type="text/javascript">
			var wpl_flex_param_counter = <?php wpl_esc::e($i); ?>;
			</script>
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