<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_save_activity()
{
    ajax_loader_element = "#wpl_activity_modify_ajax_loader";
    wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
    wplj("#wpl_activity_submit_button").prop("disabled", "disabled");

    var param_str = '';
    wplj("#wpl_activity_modify_container input:checkbox").each(function(ind, elm)
	{
		param_str += "&"+elm.name+"=";
		if(elm.checked) param_str += '1'; else param_str += '0';
	});
	
	wplj("#wpl_activity_modify_container input:text, #wpl_activity_modify_container input[type='hidden'], #wpl_activity_modify_container select, #wpl_activity_modify_container textarea").each(function(ind, elm)
	{
		param_str += "&"+elm.name+"=";
		param_str += wplj(elm).val();
	});
    
    request_str = 'wpl_format=b:activity_manager:ajax&wpl_function=save_activity&'+param_str+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'HTML',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj(ajax_loader_element).html('');
			wplj("#wpl_activity_submit_button").removeAttr("disabled");

			wplj._realtyna.lightbox.close();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_page_association_selected(activity_id)
{
    var association = wplj("#wpl_page_association"+activity_id).val();
    if(association == '1' || association == '0') wplj(".wpl_activity_pages_container").hide();
    else wplj(".wpl_activity_pages_container").show();
}

function wpl_activity_change_accesses(value, activity_id)
{
    if(value == '1') wplj("#accesses_cnt"+activity_id).slideDown();
    else wplj("#accesses_cnt"+activity_id).slideUp();
}
</script>