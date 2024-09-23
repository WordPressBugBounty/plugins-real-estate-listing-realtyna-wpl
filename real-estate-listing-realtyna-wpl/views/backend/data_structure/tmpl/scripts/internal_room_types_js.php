 <?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	wplj( ".sortable_room_types").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move" ,
        update : function(e, ui)
        {
            var stringDiv = "";
            wplj(this).children("tr").each(function(i)
            {
                var tr = wplj(this);
                var tr_id = tr.attr("id").split("_");
                if(i != 0) stringDiv += ",";
                stringDiv += tr_id[3];
            });

            request_str = 'wpl_format=b:data_structure:ajax_room_types&wpl_function=sort_rooms&sort_ids='+stringDiv+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

            wplj.ajax(
            {
                type: "POST",
                url: '<?php wpl_esc::current_url(); ?>',
                data: request_str,
                success: function(data)
                {
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');

                }
            });
        }
    });
});

// change enabled state enabled/disabled
function wpl_room_types_enabled_change(id)
{	
	if(!id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid field'); ?>", '.wpl_show_message');
		return false;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_rooms_'+id;
	ajax_flag = '#wpl_ajax_flag_rooms_'+id;
	
	// get status for whene repate the state
	var enabled_status=null;
	if(wplj(ajax_flag).hasClass('icon-enabled'))
	{
		enabled_status = 0;
	}
	else if(wplj(ajax_flag).hasClass('icon-disabled'))
	{
		enabled_status = 1;
	}
	
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	request_str = 'wpl_format=b:data_structure:ajax_room_types&wpl_function=room_types_enabled_state_change&id='+id+'&enabled_status='+enabled_status+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				if(enabled_status == 1)
				{
					wplj(ajax_flag).removeClass('icon-disabled').addClass('icon-enabled');
				}
				else
				{
					wplj(ajax_flag).removeClass('icon-enabled').addClass('icon-disabled');
				}

				wplj(ajax_loader_element).html('');
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		}
	});
}

function wpl_remove_room_type(room_type_id, confirmed)
{
	if(!room_type_id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid room type!'); ?>", '.wpl_data_structure_list .wpl_show_message');
		return false;
	}
	
	if(!confirmed)
	{
		message = "<?php wpl_esc::js_t('Are you sure you want to remove this item?'); ?>&nbsp;(<?php wpl_esc::js_t('ID'); ?>:"+room_type_id+")&nbsp;";
		message += '<span class="wpl_actions" onclick="wpl_remove_room_type(\''+room_type_id+'\', 1);"><?php wpl_esc::js_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php wpl_esc::js_t('No'); ?></span>';
		
		wpl_show_messages(message, '.wpl_data_structure_list .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message();
	
	ajax_loader_element = '#rooms_items_row_'+room_type_id;
	request_str = 'wpl_format=b:data_structure:ajax_room_types&wpl_function=remove_room_type&room_type_id='+room_type_id+'&wpl_confirmed='+confirmed+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).slideUp(500);
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_generate_new_room_type()
{
	wpl_remove_message('.wpl_show_message');
	request_str = 'wpl_format=b:data_structure:ajax_room_types&wpl_function=generate_new_room_type&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_new_room_type").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_change_room_type_name(id, name)
{
	wpl_remove_message('.wpl_show_message');
	ajax_loader_element = '#wpl_ajax_loader_room_name_'+id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	request_str = 'wpl_format=b:data_structure:ajax_room_types&wpl_function=change_room_type_name&id='+id+'&name='+name+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wplj(ajax_loader_element).html('');
			}
			else if(data.success != 1)
			{
				wplj(ajax_loader_element).html('');
			}
		}
	});
}

function wpl_ajax_save_room_type()
{	
	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	
	if(!wplj("#name").val())
	{
		wpl_alert("<?php wpl_esc::js_t('Invalid Room type name'); ?>");
		return false;
	}
	
	var name = wplj('#name').val();
	request_str = 'wpl_format=b:data_structure:ajax_room_types&wpl_function=save_room_type&name='+name+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Room type added.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_green_msg');
			wplj._realtyna.lightbox.close();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}
</script>