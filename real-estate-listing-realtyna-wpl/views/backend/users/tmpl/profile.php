<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');

$my_profile_top_activities = count(wpl_activity::get_activities('my_profile_top', 1));
$my_profile_bottom_activities = count(wpl_activity::get_activities('my_profile_bottom', 1));
$this->finds = array();
?>
<div class="wrap wpl-wp profile-wp <?php wpl_esc::attr(wpl_request::getVar('wpl_dashboard', 0) ? '' : 'wpl_view_container'); ?>">
    <header>
        <div id="icon-profile" class="icon48"></div>
        <h2><?php wpl_esc::html_t('Profile'); ?></h2>
    </header>
    <div class="wpl_user_profile"><div class="wpl_show_message"></div></div>
    
    <?php if($my_profile_top_activities): ?>
    <div id="my_profile_top_container">
        <?php
            $activities = wpl_activity::get_activities('my_profile_top', 1);
            foreach($activities as $activity)
            {
                $content = wpl_activity::render_activity($activity, array('user_data'=>$this->user_data));
                if(trim($content ?? '') == '') continue;
                ?>
                <div class="panel-wp margin-top-1p">
                    <?php if($activity->show_title and trim($activity->title) != ''): ?>
                    <h3><?php wpl_esc::html_t($activity->title); ?></h3>
                    <?php endif; ?>
                    <div class="panel-body"><?php wpl_esc::e($content); ?></div>
                </div>
                <?php
            }
        ?>
    </div>
    <?php endif; ?>
    
    <div class="panel-wp margin-top-1p">
        <h3><?php wpl_esc::html_t('Profile'); ?></h3>
        <div class="panel-body">
            <div class="pwizard-panel">
                <div class="pwizard-section">
                    <?php
                        $wpl_flex = new wpl_flex();
                        $wpl_flex->kind = $this->kind;
                        $wpl_flex->generate_wizard_form($this->user_fields, $this->user_data, $this->user_data['id'], $this->finds, $this->nonce);
                    ?>
                </div>
                <div class="text-left finilize-btn">
                    <button class="wpl-button button-1" onclick="wpl_profile_finalize(<?php wpl_esc::attr($this->user_data['id']); ?>);" id="wpl_profile_finalize_button" type="button" class="button button-primary"><?php wpl_esc::html_t('Finalize'); ?></button>
                    <span id="wpl_profile_wizard_ajax_loader"></span>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($my_profile_bottom_activities): ?>
    <div id="my_profile_bottom_container">
        <?php
            $activities = wpl_activity::get_activities('my_profile_bottom', 1);
            foreach($activities as $activity)
            {
                $content = wpl_activity::render_activity($activity, array('user_data'=>$this->user_data));
                if(trim($content) == '') continue;
                ?>
                <div class="panel-wp margin-top-1p">
                    <?php if($activity->show_title and trim($activity->title) != ''): ?>
                    <h3><?php wpl_esc::html_t($activity->title); ?></h3>
                    <?php endif; ?>
                    <div class="panel-body"><?php wpl_esc::e($content); ?></div>
                </div>
                <?php
            }
        ?>
    </div>
    <?php endif; ?>
    
    <footer>
        <div class="logo"></div>
    </footer>
</div>

<script type="text/javascript">
function wpl_profile_finalize(item_id)
{
	/** validate form **/
	if (!wpl_validation_check()) return;
	
	var ajax_loader_element = '#wpl_profile_wizard_ajax_loader';
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	wplj("#wpl_profile_finalize_button").attr("disabled", "disabled");
	
	var request_str = 'wpl_format=b:users:ajax&wpl_function=finalize&item_id=' + item_id + '&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';

	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj("#wpl_profile_finalize_button").removeAttr("disabled");
			wplj(ajax_loader_element).html('');

			if(data.success === 1)
			{
				<?php /* Force Profile Completion */ if(isset($this->user_data['maccess_fpc']) and $this->user_data['maccess_fpc']): ?>
				window.location.replace("<?php wpl_esc::url(wpl_addon_membership::URL('dashboard')); ?>");
				<?php endif; ?>
			}
		}
	});
}

function wpl_validation_check()
{
    var go_to_error = false;

	<?php
	foreach (wpl_flex::$wizard_js_validation as $js_validation) {
		if (trim($js_validation) == '')
			continue;

		wpl_esc::e($js_validation);
	}
	?>
	
	return true;
}
</script>