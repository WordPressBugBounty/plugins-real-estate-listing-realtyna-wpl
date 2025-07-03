<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
    wplj(".sortable").sortable(
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
                stringDiv += tr_id[2];
            });

            var request_str = 'wpl_format=b:flex:ajax&wpl_function=sort_flex&sort_ids='+stringDiv+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';

            wplj.ajax(
            {
                type: "POST",
                url: '<?php wpl_esc::current_url(); ?>',
                data: request_str,
                success: function(data)
                {},
                error: function(jqXHR, textStatus, errorThrown)
                {
                    wpl_show_messages('<?php wpl_esc::html_t('Error Occured.'); ?>', '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
                }
            })
        }
    });

    wplj(".categories_sortable").sortable(
    {
        handle: 'span.icon-move',
        cursor: "move" ,
        update : function(e, ui)
        {
            var stringDiv = "";
            wplj(this).children("tr").each(function(i)
            {
                var tr = wplj(this);
                var tr_id = tr.attr("id").split("-");
                if(i != 0) stringDiv += ",";
                stringDiv += tr_id[2];
            });

            var request_str = 'wpl_format=b:flex:ajax&wpl_function=sort_categories&sort_ids='+stringDiv+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';

            wplj.ajax(
            {
                type: "POST",
                url: '<?php wpl_esc::current_url(); ?>',
                data: request_str,
                success: function(data)
                {window.location='<?php wpl_esc::current_url(); ?>'},
                error: function(jqXHR, textStatus, errorThrown)
                {
                    wpl_show_messages('<?php wpl_esc::html_t('Error Occured.'); ?>', '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
                }
            })
        }
    });
});

function wpl_category_form(cat_id)
{
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=category_form&_wpnonce=<?php wpl_esc::js($this->nonce); ?>&cat_id='+cat_id;

	wplj.ajax(
    {
        type: "POST",
        url: '<?php wpl_esc::current_url(); ?>',
        data: request_str,
        success: function(data)
        {
            wplj("#wpl_flex_category").append(data);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages('<?php wpl_esc::html_t('Error Occured.'); ?>', '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
        }
    });
}

function wpl_save_category(cat_id)
{
	var name = wplj('#category_name').val();
	var kind = wplj('#category_kind').val();
	var message_path = '#wpl_category_form_message';

	var request_str = 'wpl_format=b:flex:ajax&wpl_function=update_category&_wpnonce=<?php wpl_esc::js($this->nonce); ?>&cat_id='+cat_id+'&category_name='+name+'&category_kind='+kind;

	var ajax_loader_element = "#wpl_category_form_loader";
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wplj._realtyna.lightbox.close();
			}
			else if(data.success == 0)
			{
				wplj(ajax_loader_element).html('');
				wpl_show_messages(data.message, message_path, 'wpl_red_msg');
				wplj(message_path).delay(3000).fadeOut(200);
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_remove_category(cat_id,confirmed)
{
	var message_path = '.wpl_flex_list .wpl_show_message';

	if(!cat_id)
	{
		alert('cat');
		wpl_show_messages("<?php wpl_esc::html_t('Invalid field'); ?>", message_path);
		return false;
	}

	if(!confirmed)
	{
		var message = "<?php wpl_esc::html_t('Are you sure you want to remove this item?'); ?>&nbsp;(<?php wpl_esc::html_t('ID'); ?>:"+cat_id+")&nbsp;<?php wpl_esc::html_t('All related items will be removed.'); ?>";
		message += '<span class="wpl_actions" onclick="wpl_remove_category(\''+cat_id+'\', 1);"><?php wpl_esc::html_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message(\''+message_path+'\');"><?php wpl_esc::html_t('No'); ?></span>';

		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);

	var ajax_loader_element = "#category_remove_loader"+cat_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

	var request_str = 'wpl_format=b:flex:ajax&wpl_function=remove_category&cat_id='+cat_id+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';

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
				wpl_show_messages(data.message, message_path, 'wpl_green_msg');
				wplj(message_path).delay(3000).fadeOut(200);
				wplj(".cat_"+cat_id).hide(200);
				wplj("#wpl_slide_label_id"+cat_id).hide(200);
			}
			else if(data.success == 0)
			{
				wpl_show_messages(data.message, message_path, 'wpl_red_msg');
				wplj(message_path).delay(3000).fadeOut(200);
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_toggle_category_status(cat_id)
{
    var request_str = 'wpl_format=b:flex:ajax&wpl_function=toggle_category_status&cat_id='+cat_id+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
    wplj.ajax(
    {
        type: "POST",
        url: '<?php wpl_esc::current_url(); ?>',
        data: request_str,
        success: function(data)
        {
            window.location = '<?php wpl_esc::current_url(); ?>';
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages('<?php wpl_esc::html_t('Error Occured.'); ?>', '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
        }
    });
}

function wpl_dbst_mandatory(dbst_id, mandatory_status)
{
	if(!dbst_id)
	{
		wpl_show_messages("<?php wpl_esc::html_t('Invalid field'); ?>", '.wpl_flex_list .wpl_show_message');
		return false;
	}
	
	var ajax_loader_element = '#wpl_flex_ajax_loader_'+dbst_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=mandatory&dbst_id='+dbst_id+'&mandatory_status='+mandatory_status+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				if(mandatory_status == 0)
				{
					wplj('#wpl_flex_field_mandatory_span'+dbst_id).removeClass("wpl_show").addClass("wpl_hidden");
					wplj('#wpl_flex_field_mandatory_dis_span'+dbst_id).removeClass("wpl_hidden").addClass("wpl_show");
				}
				else
				{
					wplj('#wpl_flex_field_mandatory_span'+dbst_id).removeClass("wpl_hidden").addClass("wpl_show");
					wplj('#wpl_flex_field_mandatory_dis_span'+dbst_id).removeClass("wpl_show").addClass("wpl_hidden");
				}
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_dbst_enabled(dbst_id, enabled_status)
{
	if(!dbst_id)
	{
		wpl_show_messages("<?php wpl_esc::html_t('Invalid field'); ?>", '.wpl_flex_list .wpl_show_message');
		return false;
	}
	
	var ajax_loader_element = '#wpl_flex_ajax_loader_'+dbst_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=enabled&dbst_id='+dbst_id+'&enabled_status='+enabled_status+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				if(enabled_status == 0)
				{
					wplj('#wpl_flex_field_enable_span'+dbst_id).removeClass("wpl_show").addClass("wpl_hidden");
					wplj('#wpl_flex_field_disable_span'+dbst_id).removeClass("wpl_hidden").addClass("wpl_show");
				}
				else
				{
					wplj('#wpl_flex_field_enable_span'+dbst_id).removeClass("wpl_hidden").addClass("wpl_show");
					wplj('#wpl_flex_field_disable_span'+dbst_id).removeClass("wpl_show").addClass("wpl_hidden");
				}
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function generate_modify_page(field_id, field_type)
{
	if(!field_id) field_id = 0;
	if(field_id == 0) field_type = wplj("#wpl_dbst_types_select").val();
	var cat_id = wplj(".wpl-side-2 .active").data("id");

	var ajax_loader_element = '';
	var request_str = 'wpl_format=b:flex:modify&wpl_function=generate_modify_page&field_type='+field_type+'&field_id='+field_id+'&cat_id='+cat_id+'&kind=<?php wpl_esc::js($this->kind); ?>&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'HTML',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj("#wpl_flex_edit_div").html(data);

			/** for fixing horizontal scroll **/
			wplj("#wpl_flex_edit_div").width("auto");

			// Storage Trigger
			wpl_storage_trigger();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function get_specific_options_string(prefix)
{
	var specific_str = '';
	
	/** specific options **/
    wplj("#wpl_flex_specific_options input:text, #wpl_flex_specific_options input[type='hidden'], #wpl_flex_specific_options select, #wpl_flex_specific_options textarea, #wpl_flex_specific_options input:radio").each(function (index, element)
    {
        if (wplj(this).attr('type') == 'radio') {
            specific_str += "&"+element.id.replace(prefix, "")+"="+encodeURIComponent(wplj('#'+element.id+':checked').val());
        }else{
            specific_str += "&"+element.id.replace(prefix, "")+"="+encodeURIComponent(wplj(element).val());
        }
    });
	
	return specific_str;
}

function makeNullable(flexId) {
	const request_str = 'wpl_format=b:flex:ajax&wpl_function=makeNullable&flexId=' + flexId + '&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	const ajax_loader_element = '#wpl_dbst_make_nullable_ajax_loader';
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			console.log('data', data);
			wplj(ajax_loader_element).html('Done, Reload the form');
		},
		error: function (jqXHR, textStatus, errorThrown) {
			wplj(ajax_loader_element).html('Error');
		}
	});
}

function save_dbst(prefix, dbst_id)
{
	if(!dbst_id) dbst_id = 0;
	
	var request_str = "";
	
	var ajax_loader_element = "#wpl_dbst_modify_ajax_loader";
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
    
	/** general options **/
	wplj("#wpl_flex_general_options input:text, #wpl_flex_general_options input[type='hidden'], #wpl_flex_general_options select, #wpl_flex_general_options textarea").each(function (index, element)
	{
		request_str += "&fld_"+element.id.replace(prefix,"")+"="+wplj(element).val();
	});
	
	/** Data Specific **/
	var specificable = wplj("#"+prefix+"specificable").val();
	if(specificable == 1) /** listing specific **/
	{
		var listing_specific = '';
		
		if(!wplj("#wpl_flex_listing_checkbox_all").is(':checked'))
		{
			wplj(".wpl_listing_specific_ul input[type='checkbox']").each(function(index, element)
			{
				if(element.id != "wpl_flex_listing_checkbox_all" && element.checked) { listing_specific += element.value +','; }
			});
		}
		
		request_str += "&fld_listing_specific="+listing_specific+"&fld_property_type_specific=&fld_user_specific=&fld_field_specific=";
	}
	else if(specificable == 2) /** property type specific **/
	{
		var property_type_specific = '';
		
		if(!wplj("#wpl_flex_property_type_checkbox_all").is(':checked'))
		{
			wplj(".wpl_property_type_specific_ul input[type='checkbox']").each(function(index, element)
			{
				if(element.id != "wpl_flex_property_type_checkbox_all" && element.checked) { property_type_specific += element.value +','; }
			});
		}
		
		request_str += "&fld_property_type_specific="+property_type_specific+"&fld_listing_specific=&fld_user_specific=&fld_field_specific=";
	}
    else if(specificable == 3) /** user type specific **/
	{
		var user_specific = '';
		
		if(!wplj("#wpl_flex_user_checkbox_all").is(':checked'))
		{
			wplj(".wpl_user_specific_ul input[type='checkbox']").each(function(index, element)
			{
				if(element.id != "wpl_flex_v_checkbox_all" && element.checked) { user_specific += element.value +','; }
			});
		}
		
		request_str += "&fld_user_specific="+user_specific+"&fld_listing_specific=&fld_property_type_specific=&fld_field_specific=";
	}
	else if(specificable == 4) /** field specific **/
	{
		var field_specific = wplj("#"+prefix+"field_specific_name").val() + ':' + wplj("#"+prefix+"field_specific_value").val();

		request_str += "&fld_field_specific="+field_specific+"&fld_listing_specific=&fld_property_type_specific=&fld_user_specific=";
	}
	else if(specificable == 0) /** No specific **/
	{
		request_str += "&fld_property_type_specific=&fld_listing_specific=&fld_user_specific=&fld_field_specific=";
	}
    
    /** Data Accesses **/
	var viewable = wplj("#"+prefix+"accesses").val();
	if(viewable == 1) /** Selected Users **/
	{
		var accesses_str = '';
		
		wplj(".wpl_accesses_ul input[type='checkbox']").each(function(index, element)
        {
            if(element.checked) accesses_str += element.value+',';
        });
		
        var accesses_message = wplj("#"+prefix+"accesses_message").val();
		request_str += "&fld_accesses="+accesses_str+"&fld_accesses_message="+accesses_message;
	}
	else if(viewable == 2) /** All Users **/
	{
		request_str += "&fld_accesses=&fld_accesses_message=";
	}
	
	/** specific options **/
	if(get_specific_options_string(prefix)) request_str += get_specific_options_string(prefix);
	
	request_str = 'wpl_format=b:flex:ajax&wpl_function=save_dbst&dbst_id='+dbst_id+request_str+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj(ajax_loader_element).html('');
			wplj("#wpl_dbst_submit_button").removeAttr("disabled");
			wplj._realtyna.lightbox.close();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_generate_params_page(dbst_id)
{
	if(!dbst_id) dbst_id = '';
	
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=generate_params_page&dbst_id='+dbst_id+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function(data)
		{
			wplj("#wpl_flex_edit_div").html(data);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj._realtyna.lightbox.close();
		}
	});
}

function wpl_remove_dbst(dbst_id, confirmed)
{
    var message_path = '.wpl_flex_list .wpl_show_message';
    
	if(!dbst_id)
	{
		wpl_show_messages("<?php wpl_esc::html_t('Invalid field'); ?>", message_path);
		return false;
	}
	
	if(!confirmed)
	{
		message = "<?php wpl_esc::html_t('Are you sure you want to remove this item?'); ?>&nbsp;(<?php wpl_esc::html_t('ID'); ?>:"+dbst_id+")&nbsp;<?php wpl_esc::html_t('All related items will be removed.'); ?>";
		message += '<span class="wpl_actions" onclick="wpl_remove_dbst(\''+dbst_id+'\', 1);"><?php wpl_esc::html_t('Yes'); ?></span>&nbsp;<span class="wpl_actions" onclick="wpl_remove_message(\''+message_path+'\');"><?php wpl_esc::html_t('No'); ?></span>';
		
		wpl_show_messages(message, message_path);
		return false;
	}
	else if(confirmed) wpl_remove_message(message_path);
	
	var ajax_loader_element = "#wpl_flex_remove_ajax_loader"+dbst_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=remove_dbst&dbst_id='+dbst_id+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	
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
				wplj("#item_row_"+dbst_id).slideUp(200);
			}
			else if(data.success == 0)
			{
				wpl_show_messages(data.message, message_path, 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_flex_change_specificable(specificable_value, prefix)
{
	wplj(".wpl_flex_specificable_cnt").slideUp();
    wplj("#"+prefix+"specificable"+specificable_value).slideDown();
}

function wpl_listing_specific_all(checked)
{
	if(checked)
	{
		wplj(".wpl_listing_specific_ul input[type='checkbox']").each(function(index, element)
		{
			if(element.id != "wpl_flex_listing_checkbox_all") { element.checked = true; element.disabled = true; }
		});
	}
	else
	{
		wplj(".wpl_listing_specific_ul input[type='checkbox']").each(function(index, element)
		{
			if(element.id != "") { element.disabled = false;  element.checked = false;}
		});
	}
}

function wpl_property_type_specific_all(checked)
{
	if(checked)
	{
		wplj(".wpl_property_type_specific_ul input[type='checkbox']").each(function(index, element)
		{
			if(element.id != "wpl_flex_property_type_checkbox_all") { element.checked = true; element.disabled = true; }
		});
	}
	else
	{
		wplj(".wpl_property_type_specific_ul input[type='checkbox']").each(function(index, element)
		{
			if(element.id != "") { element.disabled = false;  element.checked = false;}
		});
	}
}

function wpl_user_specific_all(checked)
{
	if(checked)
	{
		wplj(".wpl_user_specific_ul input[type='checkbox']").each(function(index, element)
		{
			if(element.id != "wpl_flex_user_checkbox_all") { element.checked = true; element.disabled = true; }
		});
	}
	else
	{
		wplj(".wpl_user_specific_ul input[type='checkbox']").each(function(index, element)
		{
			if(element.id != "") { element.disabled = false;  element.checked = false;}
		});
	}
}

function wpl_flex_change_accesses(value, prefix)
{
    if(value == '1') wplj("#"+prefix+"accesses_cnt").slideDown();
    else wplj("#"+prefix+"accesses_cnt").slideUp();
}

function convert_dbst(prefix, dbst_id, new_type)
{
	if(!dbst_id) dbst_id = 0;
	
	var ajax_loader_element = "#wpl_dbst_modify_ajax_loader";
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=convert_dbst&dbst_id='+dbst_id+'&type='+new_type+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj(ajax_loader_element).html('');
			wplj("#wpl_dbst_submit_button").removeAttr("disabled");
			wplj._realtyna.lightbox.close();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_sort_option(dbst_id, kind, status)
{
	if(!dbst_id)
	{
		wpl_show_messages("<?php wpl_esc::html_t('Invalid field'); ?>", '.wpl_flex_list .wpl_show_message');
		return false;
	}
	
	var ajax_loader_element = '#wpl_flex_ajax_loader_'+dbst_id;
	wplj(ajax_loader_element).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');
	
	var request_str = 'wpl_format=b:flex:ajax&wpl_function=sort_option&dbst_id='+dbst_id+'&kind='+kind+'&status='+status+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';

	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_green_msg');
				wplj(ajax_loader_element).html('');

				if(status == 0)
				{
					wplj('#wpl_flex_field_sort_option_span'+dbst_id).removeClass("wpl_show").addClass("wpl_hidden");
					wplj('#wpl_flex_field_sort_option_dis_span'+dbst_id).removeClass("wpl_hidden").addClass("wpl_show");
				}
				else
				{
					wplj('#wpl_flex_field_sort_option_span'+dbst_id).removeClass("wpl_hidden").addClass("wpl_show");
					wplj('#wpl_flex_field_sort_option_dis_span'+dbst_id).removeClass("wpl_show").addClass("wpl_hidden");
				}
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
				wplj(ajax_loader_element).html('');
			}
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_flex_compare_change(element)
{
	if(typeof(element) == 'undefined' || !wplj(element).length) return;
	if(!wplj('.wpl-compare-row-container').length) return;

	if(parseInt(wplj(element).val()))
	{
		wplj('.wpl-compare-row-container').show();
	}
	else
	{
		wplj('.wpl-compare-row-container').hide();
	}
}

function wpl_storage_trigger()
{
    wplj('.wpl-storage-method').off('change').on('change', function()
    {
        var table = wplj(this).val();

        // Hide Elements
        wplj('.wpl-storage-field').hide();
        wplj('.wpl-storage-field.wpl-storage-'+table).show();
    }).trigger('change');
}

function wpl_flex_change_field_specific_fields(dbst_id, prefix, value_element){

	wplj(value_element).addClass('disabled');

	var request_str = 'wpl_format=b:flex:ajax&wpl_function=get_field_values&dbst_id='+dbst_id+'&_wpnonce=<?php wpl_esc::js($this->nonce); ?>';

	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (response) {
			var valuesField = wplj(value_element);

			valuesField.empty().removeClass('disabled');

			if (response.data.values) {
				const values = Array.isArray(response.data.values) ? response.data.values : Object.values(response.data.values)
				valuesField.append(values.map(function(item) {
					return wplj('<option>', { value: item.key, text: item.value });
				}));
			} else if (response.data.params) {
				const values = Array.isArray(response.data.params) ? response.data.params : Object.values(response.data.params)
				valuesField.append(values.map(function(item) {
					return wplj('<option>', { value: item.key, text: item.value });
				}));
			} else {
				valuesField.append(wplj('<option>', { text: "<?php wpl_esc::js_t('No option'); ?>" }));
			}
		},
	});
}
</script>