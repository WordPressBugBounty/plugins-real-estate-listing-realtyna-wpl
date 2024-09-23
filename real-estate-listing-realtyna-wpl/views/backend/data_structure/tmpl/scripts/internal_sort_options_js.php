 <?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
jQuery(document).ready(function()
{
	wplj(".sortable_sort_options").sortable(
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

            request_str = 'wpl_format=b:data_structure:ajax_sort_options&wpl_function=sort_options&sort_ids='+stringDiv+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';

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

/** change enabled state enabled/disabled **/
function wpl_sort_options_enabled_change(id, key)
{
	if(!id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid sort option!'); ?>", '.wpl_show_message');
		return false;
	}
	
    var ajax_loader_element = Realtyna.ajaxLoader.show('#wpl_ajax_'+key+'_'+id, 'tiny', 'rightIn');
	var ajax_flag = '#wpl_ajax_'+key+'_'+id;
	
	// Get the status
	var enabled_status = null;
    
	if(wplj(ajax_flag).hasClass('icon-enabled')) enabled_status = 0;
	else if(wplj(ajax_flag).hasClass('icon-disabled')) enabled_status = 1;
	
	var request_str = 'wpl_format=b:data_structure:ajax_sort_options&wpl_function=sort_options_enabled_state_change&id='+id+'&key='+key+'&enabled_status='+enabled_status+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
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

				Realtyna.ajaxLoader.hide(ajax_loader_element);
			}
			else if(data.success != 1)
			{
				wpl_show_messages(data.message, '.wpl_flex_list .wpl_show_message', 'wpl_red_msg');
				Realtyna.ajaxLoader.hide(ajax_loader_element);
			}
		}
	});
}

/** change enabled state enabled/disabled **/
function wpl_save_sort_option(id, key, value)
{	
	if(!id)
	{
		wpl_show_messages("<?php wpl_esc::js_t('Invalid sort option!'); ?>", '.wpl_show_message');
		return false;
	}
	
	var ajax_loader_element = Realtyna.ajaxLoader.show('#wpl_sort_option_'+key+id, 'tiny', 'rightOut');
	var request_str = 'wpl_format=b:data_structure:ajax_sort_options&wpl_function=save_sort_option&id='+id+'&key='+key+'&value='+value+'&_wpnonce=<?php wpl_esc::attr($nonce); ?>';
	
	/** run ajax query **/
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::current_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success == 1)
			{
				Realtyna.ajaxLoader.hide(ajax_loader_element);
			}
			else if(data.success != 1)
			{
				Realtyna.ajaxLoader.hide(ajax_loader_element);
			}
		}
	});
}
</script>