<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
    wplj(".sortable_ltcategory").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move",
        update: function(e, ui)
        {
            var stringDiv = "";
            wplj(this).children("tr").each(function(i)
            {
                var tr = wplj(this);
                var tr_id = tr.attr("id").split("_");
                if (i != 0) stringDiv += ",";
                stringDiv += tr_id[2];
            });

            request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=sort_listing_types&sort_ids=' + stringDiv + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

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
    
    wplj(".sortable_list_type").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move",
        update: function(e, ui)
        {
            var stringDiv = "";
            wplj(this).children("tr").each(function(i)
            {
                var tr = wplj(this);
                var tr_id = tr.attr("id").split("_");
                if(i != 0) stringDiv += ",";
                stringDiv += tr_id[2];
            });

            request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=sort_listing_types&sort_ids=' + stringDiv + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

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

function wpl_remove_listing_type(listing_type_id, confirmed)
{
	if (!listing_type_id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid Listing Types'); ?>", '.wpl_data_structure_list .wpl_show_message');
		return false;
	}

	/** load delete light box **/
    wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
    request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=generate_delete_page&listing_type_id='+listing_type_id+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

    /** run ajax query **/
    wplj.ajax(
    {
        type: "POST",
        url: '<?php wpl_esc::current_url(); ?>',
        data: request_str,
        success: function(data)
        {
            wplj("#wpl_data_structure_edit_div").html(data);
            wplj._realtyna.lightbox.open("#wpl_listing_type_remove"+listing_type_id, {reloadPage: true});
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
            wplj._realtyna.lightbox.close();
        }
    });
}

function wpl_set_enabled_listing_type(listing_type_id, enabled_status)
{
	if (!listing_type_id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid listing Type'); ?>", '.wpl_data_structure_list .wpl_show_message');
		return false;
	}

	ajax_loader_element = '#wpl_ajax_loader_' + listing_type_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=set_enabled_listing_type&listing_type_id=' + listing_type_id + '&enabled_status=' + enabled_status + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				if (enabled_status == 0)
				{
					wplj('#listing_types_enable_' + listing_type_id).removeClass("wpl_show").addClass("wpl_hidden");
					wplj('#listing_types_disable_' + listing_type_id).removeClass("wpl_hidden").addClass("wpl_show");
				}
				else
				{
					wplj('#listing_types_enable_' + listing_type_id).removeClass("wpl_hidden").addClass("wpl_show");
					wplj('#listing_types_disable_' + listing_type_id).removeClass("wpl_show").addClass("wpl_hidden");
				}
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_generate_new_page_listing_type()
{
	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=generate_new_page&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
			
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_generate_edit_page_listing_type(listing_type_id)
{
	if (!listing_type_id) return false;

	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=generate_edit_page&listing_type_id=' + listing_type_id + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_ajax_save_listing_type(key, element, id)
{
	if(id == '10000') return;
	table = 'wpl_listing_types';

	ajax_loader_element = '#' + element.id + '_ajax_loader';
	url = '<?php wpl_esc::current_url(); ?>';

	wpl_remove_message('.wpl_show_message' + id);
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

    /** run ajax query **/
    request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=save_listing_type&listing_type_id=' + id + '&key=' + key + '&value=' + element.value + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_green_msg');
				wplj(ajax_loader_element).html('');
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_gicon_delete(icon, confirmed, index)
{
	if (!icon)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid Icon'); ?>", '.wpl_data_structure_list_gicon .wpl_show_message');
		return false;
	}

	if (!confirmed)
	{
		message = "<?php wpl_esc::js_t('Are you sure you want to remove this item?'); ?>&nbsp;(" + icon + ")&nbsp;<?php wpl_esc::js_t('All related items will be removed.'); ?>";
		message += '<span class="wpl_actions" onclick="wpl_gicon_delete(\'' + icon + '\', 1, ' + index + ');"><?php wpl_esc::js_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php wpl_esc::js_t('No'); ?></span>';

		wpl_show_messages(message, '.wpl_data_structure_list_gicon .wpl_show_message');
		return false;
	}
	else
	{
		wpl_remove_message();
	}

	ajax_loader_element = '#wpl_gicon_ajax_loader_' + index;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=gicon_delete&icon=' + encodeURIComponent(icon) + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#gicon" + index).remove();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list_gicon .wpl_show_message', 'wpl_red_msg');
		}
	});
}

function wpl_set_multiple_icon(icon, index)
{
	var request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=set_multiple_icon&icon=' + encodeURIComponent(icon) + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_multiple_gicon_listing .gmarker-icon").removeClass('selected');
			wplj("#multiple_gicon" + index).addClass('selected');
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list_gicon .wpl_show_message', 'wpl_red_msg');
		}
	});
}

function wpl_ajax_insert_listing_type(id)
{
	if(id != '10000') return;
	table = 'wpl_listing_types';

	url = '<?php wpl_esc::current_url(); ?>';

	wpl_remove_message('.wpl_show_message' + id);
	parent = wplj('#wpl_parent10000').val();
    name = wplj('#wpl_name10000').val();
	gicon = wplj('#wpl_gicon10000').val();
	
    /** validation for parent **/
    if(parent == '')
    {
        wpl_show_messages('<?php wpl_esc::js_t('Select category!'); ?>', '.wpl_show_message' + id, 'wpl_red_msg');
        return;
    }
    
    /** run ajax query **/
    request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=insert_listing_type&parent=' + parent + '&name=' + name + '&gicon=' + gicon + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_green_msg');
				setTimeout(function()
				{
					wplj._realtyna.lightbox.close();
				}, 1000);
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_red_msg');
			}
		}
	});
}

function purge_properties_listing_type(listing_type_id)
{
	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=purge_related_property&listing_type_id=' + listing_type_id + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + listing_type_id, 'wpl_green_msg');
				setTimeout(function()
				{
					wplj._realtyna.lightbox.close();
				}, 1000);
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + listing_type_id, 'wpl_red_msg');
			}
		}
	});
}

function assign_properties_listing_type(listing_type_id)
{
	var select_id = wplj('#listing_type_select').val();
    if(select_id == -1) return;
	
	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=assign_related_properties&listing_type_id=' + listing_type_id+ '&select_id=' + select_id + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if (data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + listing_type_id, 'wpl_green_msg');
				setTimeout(function()
				{
					wplj._realtyna.lightbox.close();
				}, 1000);

			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + listing_type_id, 'wpl_red_msg');
			}
		}
	});
}

function show_opt_2_listing_type()
{
	wplj('#lt-del-options').fadeOut(200,function(){
        wplj('#lt-del-plist').fadeIn();
    });
}
function wpl_generate_new_page_ltcategory()
{
	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=generate_new_page_ltcategory&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}
function wpl_generate_edit_page_ltcategory(id)
{
	if (!id) return false;

	wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
	var request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=generate_edit_page_ltcategory&ltcategory_id=' + id + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
    
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_data_structure_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wpl_show_messages('<?php wpl_esc::js_t('Error Occured.'); ?>', '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_ajax_insert_ltcategory(id)
{
    if(id != '10000') return;

	wpl_remove_message('.wpl_show_message' + id);
    var name = wplj('#wpl_name10000').val();
    
    /** run ajax query **/
    var request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=insert_ltcategory&name=' + name + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_green_msg');
				setTimeout(function()
				{
					wplj._realtyna.lightbox.close();
					location.reload();
				}, 1000);
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_show_message' + id, 'wpl_red_msg');
			}
		}
	});
}

function wpl_remove_ltcategory(id, confirmed)
{
    if(!id) return false;
    
    if(!confirmed)
	{
		var message = "<?php wpl_esc::js_t('Are you sure you want to remove this item?'); ?>&nbsp;(<?php wpl_esc::js_t('ID'); ?>:"+id+")&nbsp;";
		message += '<span class="wpl_actions" onclick="wpl_remove_ltcategory(\''+id+'\', 1);"><?php wpl_esc::js_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message();"><?php wpl_esc::js_t('No'); ?></span>';
		
		wpl_show_messages(message, '.wpl_data_structure_list .wpl_show_message');
		return false;
	}
	else if(confirmed) wpl_remove_message('.wpl_data_structure_list .wpl_show_message');
    
    /** Show AJAX loader **/
    var ajax_loader_element = '#wpl_ajax_loader_'+id;
    wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
        
    /** run ajax query **/
    var request_str = 'wpl_format=b:data_structure:ajax_listing_types&wpl_function=remove_ltcategory&id=' + id + '&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj(ajax_loader_element).html('');

			if(data.success == 1)
			{
				wplj('#item_row_'+id).remove();
			}
			else if (data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_data_structure_list .wpl_show_message', 'wpl_red_msg');
			}
		}
	});
}
</script>
