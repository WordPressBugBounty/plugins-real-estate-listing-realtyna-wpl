<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if($type == 'locations' and !$done_this)
{
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
    wplj(".wpl-flex-locations-sortable").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move" ,
        update : function(e, ui)
        {
        }
    });
});

function wpl_flex_disable_location(param_id)
{
    if(wplj("#wpl_flex_change_param_status" + param_id).hasClass("wpl_actions_icon_enable"))
    {
        wplj("#wpl_flex_change_param_status" + param_id).removeClass("wpl_actions_icon_enable").removeClass("icon-enabled");
        wplj("#wpl_flex_change_param_status" + param_id).addClass("wpl_actions_icon_disable").addClass("icon-disabled");
        wplj("#wpl_flex_locations_params_row" + param_id + " input[name='<?php wpl_esc::attr($__prefix); ?>opt_params[" + param_id + "][enabled]']").val(0);
    }
    else
    {
        wplj("#wpl_flex_change_param_status" + param_id).removeClass("wpl_actions_icon_disable").removeClass("icon-disabled");
        wplj("#wpl_flex_change_param_status" + param_id).addClass("icon-enabled").addClass("wpl_actions_icon_enable");
        wplj("#wpl_flex_locations_params_row" + param_id + " input[name='<?php wpl_esc::attr($__prefix); ?>opt_params[" + param_id + "][enabled]']").val(1);
    }
}
</script>
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
                <p><?php wpl_esc::html_t('You can disable / enable locations in listing details page.'); ?></p>
                <div class="wpl-flex-locations-sortable">
                    <?php
                    $location_settings = wpl_global::get_settings('3'); # location settings
                    $option_params = (isset($options['params']) and is_array($options['params'])) ? $options['params'] : array();

                    foreach($option_params as $k => $v)
                    {
                        $keyword = $location_settings['location' . $k . '_keyword'] ?? '';
                        if(trim($keyword) == '') continue;
                        ?>
                        <div class="fanc-row" id="wpl_flex_locations_params_row<?php wpl_esc::attr($k); ?>">
                            <span style="width: 200px; display: inline-block;"><?php wpl_esc::html_t($keyword); ?></span>
                            <span class="margin-left-1p action-btn icon-<?php wpl_esc::attr((isset($v['enabled']) and $v['enabled']) ? 'enabled wpl_actions_icon_enable' : 'disabled'); ?>"
                                  id="wpl_flex_change_param_status<?php wpl_esc::attr($k); ?>"
                                  onclick="wpl_flex_disable_location('<?php wpl_esc::attr($k); ?>');"></span>
                            <span class="action-btn icon-move ui-sortable-handle"></span>
                            <input type="hidden" id="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::attr($k); ?>][enabled]"
                                   name="<?php wpl_esc::attr($__prefix); ?>opt_params[<?php wpl_esc::attr($k); ?>][enabled]"
                                   value="<?php wpl_esc::attr(((isset($v['enabled']) and $v['enabled'])) ? $v['enabled'] : 0); ?>"/>
                        </div>
                        <?php
                    }
                    ?>
                </div>
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