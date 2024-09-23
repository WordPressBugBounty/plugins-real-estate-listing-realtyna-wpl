<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_send_contact<?php wpl_esc::attr($this->activity_id); ?>(property_id)
{
    var ajax_loader_element = '#wpl_contact_ajax_loader<?php wpl_esc::attr($this->activity_id); ?>_'+property_id;
	wplj(ajax_loader_element).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />`);
    wpl_remove_message('#wpl_contact_message<?php wpl_esc::attr($this->activity_id); ?>_'+property_id);
	
	var request_str = 'wpl_format=f:property_listing:ajax&wpl_function=contact_listing_user&'+wplj('#wpl_contact_form<?php wpl_esc::attr($this->activity_id); ?>'+property_id).serialize()+'&pid='+property_id;
	wplj.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_site_url(); ?>',
		data: request_str,
		success: function(data)
		{
			if(data.success === 1)
			{
				wpl_show_messages(data.message, '#wpl_contact_message<?php wpl_esc::attr($this->activity_id); ?>_'+property_id, 'wpl_green_msg');
				wplj('#wpl_contact_form<?php wpl_esc::attr($this->activity_id); ?>'+property_id).hide();
				// listhub metrics
				<?php if(wpl_global::check_addon('listhub') and $this->settings['listhub_tracking_status'] == '1'): ?>
				lh('submit', 'AGENT_EMAIL_SENT', {lkey:'<?php wpl_esc::js($wpl_properties['current']['raw']['listing_key']); ?>'});
				<?php endif; ?>
			}
			else if(data.success === 0)
			{
				wpl_show_messages(data.message, '#wpl_contact_message<?php wpl_esc::attr($this->activity_id); ?>_'+property_id, 'wpl_red_msg');
			}

			wplj(ajax_loader_element).html('');
		},
		error: function (jqXHR, textStatus, errorThrown) {
			if (ajax_loader_element)
				wplj(ajax_loader_element).html('');
			wpl_show_messages("<?php wpl_esc::js_t('Error Occurred!'); ?>", '#wpl_contact_message<?php wpl_esc::attr($this->activity_id); ?>_'+property_id, 'wpl_red_msg');
		}
	});
	
	return false;
}
</script>