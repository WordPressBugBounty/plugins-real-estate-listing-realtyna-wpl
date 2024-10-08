<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
});

function wpl_set_enabled_location(location_id, enabeled_status)
{
	if(!location_id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid Location'); ?>", '.wpl_location_list .wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_'+location_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	request_str = 'wpl_format=b:location_manager:ajax&wpl_function=set_enabled_location&location_id='+location_id+'&enabeled_status='+enabeled_status+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_location_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				if(enabeled_status==0)
				{
					wplj('#location_enable_'+location_id).removeClass("wpl_show").addClass("wpl_hidden");
					wplj('#location_disable_'+location_id).removeClass("wpl_hidden").addClass("wpl_show");
				}
				else
				{
					wplj('#location_enable_'+location_id).removeClass("wpl_hidden").addClass("wpl_show");
					wplj('#location_disable_'+location_id).removeClass("wpl_show").addClass("wpl_hidden");
				}

				$tr_current=wplj(ajax_loader_element).parent().parent();
				$tr_move=wplj(ajax_loader_element).parent().parent().prev();
				$tr_current.after($tr_move);

			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_location_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_generate_modify_page(level, parent, location_id)
{
	if(!level) return false;
	if(!parent) parent = 0;
	if(!location_id) location_id = '';
	
	wpl_remove_message('.wpl_location_list .wpl_show_message');
	request_str = 'wpl_format=b:location_manager:ajax&wpl_function=generate_modify_page&level='+level+'&parent='+parent+'&location_id='+location_id+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_location_fancybox_cnt").html(data);
			wplj("#wpl_location_name").focus();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_location_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_generate_params_page(level, location_id)
{
	if(!level) return false;
	if(!location_id) location_id = '';
	
	request_str = 'wpl_format=b:location_manager:ajax&wpl_function=generate_params_page&level='+level+'&location_id='+location_id+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_location_fancybox_cnt").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_location_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_ajax_modify_location(level, parent, location_id)
{
	if(!parent) parent = 0;
	if(!location_id) location_id = '';
	
	var name = wplj("#wpl_location_name").val();
    var abbr = wplj("#wpl_location_abbr").val();
	
	ajax_loader_element = 'wpl_ajax_loader';
	url = '<?php wpl_esc::current_url(); ?>';
	
	wpl_remove_message('.wpl_show_message_location');
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	request_str = 'wpl_format=b:location_manager:ajax&wpl_function=save_location&name='+name+'&abbr='+abbr+'&level='+level+'&parent='+parent+'&location_id='+location_id+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: url,
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message_location', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				wplj._realtyna.lightbox.close();
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message_location', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_search_location(name, level)
{
	name = wplj("#wpl_search_location").val();
	url = '<?php wpl_esc::current_url(); ?>'+'&sf_text_name='+name;
	window.location = url;
}

function wpl_reset_search_location()
{
	url = '<?php wpl_esc::url(wpl_global::remove_qs_var('sf_text_name')); ?>';
	window.location = url;
}

function wpl_show_countries(enabled_status)
{
	url = wpl_update_qs('sf_select_enabled', enabled_status);
	window.location = url;
}

function wpl_remove_location(level, location_id, confirmed)
{
	if(!location_id || !level)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid Location or level'); ?>", '.wpl_location_list .wpl_show_message');
		return false;
	}
	
	if(!confirmed)
	{
		message = "<?php wpl_esc::js_t('Are you sure you want to remove this item?'); ?>&nbsp;(<?php wpl_esc::js_t('Level'); ?>:"+level+", <?php wpl_esc::js_t('ID'); ?>:"+location_id+")&nbsp;<?php wpl_esc::js_t('All related items will be removed.'); ?>";
		message += '<span class="wpl_actions" onclick="wpl_remove_location(\''+level+'\',\''+location_id+'\', 1);"><?php wpl_esc::js_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php wpl_esc::js_t('No'); ?></span>';
		
		wpl_show_messages(message, '.wpl_location_list .wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_'+location_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	request_str = 'wpl_format=b:location_manager:ajax&wpl_function=delete_location&location_id='+location_id+'&level='+level+'&wpl_confirmed='+confirmed+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_location_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');
				wplj("#item_row"+location_id).slideUp(1000);
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_location_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_setting_save(setting_id, setting_name, setting_value, setting_category)
{
	wplj("#wpl_st_form_element"+setting_id).attr("disabled", "disabled");
	
	var element_type = wplj("#wpl_st_form_element"+setting_id).attr('type');
    var tag_name = wplj("#wpl_st_form_element"+setting_id).prop('tagName').toLowerCase();
    
	if(element_type == 'checkbox')
	{
		if(wplj("#wpl_st_form_element"+setting_id).is(':checked')) setting_value = 1;
		else setting_value = 0;
	}
    
    var ajax_loader_element = '#wpl_st_form_element'+setting_id;
    if(tag_name == 'select')
    {
        ajax_loader_element = '#wpl_st_form_element'+setting_id+'_chosen';
    }
	
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show(ajax_loader_element, 'tiny', 'rightOut');
	
	var request_str = 'wpl_format=b:settings:ajax&wpl_function=save&setting_name='+setting_name+'&setting_value='+setting_value+'&setting_category='+setting_category+'&_wpnonce=<?php wpl_esc::attr(wpl_security::create_nonce('wpl_settings')); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj("#wpl_st_form_element"+setting_id).removeAttr("disabled");

			/** Remove AJAX loader **/
			Realtyna.ajaxLoader.hide(wpl_ajax_loader);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}
</script>