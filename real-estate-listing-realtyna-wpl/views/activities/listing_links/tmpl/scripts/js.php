<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var wpl_favorites_force_login<?php wpl_esc::attr($this->activity_id); ?> = <?php wpl_esc::e((!wpl_users::get_cur_user_id() and wpl_global::check_addon('membership') and wpl_global::get_setting('favorite_force_login') == '1') ? 1 : 0); ?>;
function wpl_favorite_control<?php wpl_esc::attr($this->activity_id); ?>(id, mode)
{
    if(wpl_favorites_force_login<?php wpl_esc::attr($this->activity_id); ?> == '1')
    {
        wpl_favorite_login<?php wpl_esc::attr($this->activity_id); ?>();
        return;
    }

	var request_str = 'wpl_format=f:property_listing:ajax_pro&wpl_function=favorites_control&pid='+id+'&mode='+mode;
	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj('#wpl_favorite_remove_<?php wpl_esc::attr($this->activity_id); ?>_'+id).toggle().parent('li').toggleClass('added');
			wplj('#wpl_favorite_add_<?php wpl_esc::attr($this->activity_id); ?>_'+id).toggle();

			if(typeof wpl_load_favorites == 'function')
			{
				wpl_load_favorites(data.pids);
			}

			if(typeof wpl_refresh_searchwidget_counter == 'function')
			{
				wpl_refresh_searchwidget_counter();
			}
		}
	});
	return false;
}

function wpl_favorite_login<?php wpl_esc::attr($this->activity_id); ?>()
{
    var request_str = 'wpl_format=f:profile_show:raw&wplmethod=login';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("#wpl_pshow_lightbox_content_container").html(html);

			/** Open lightbox **/
			wplj._realtyna.lightbox.open("#wpl_favorites_lightbox",
				{
					reloadPage: true,
					cssClasses: {wrap: 'wpl-frontend-lightbox-wp', overlay: 'realtyna-lightbox-overlay realtyna-lightbox-overlay-drp'},
					closeOnOverlay: true,
				});
		}
	});
}

function wpl_report_abuse_get_form(id)
{
	var request_str = 'wpl_format=c:functions:ajax&wpl_function=report_abuse_form&pid='+id+'&form_id=0';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").html(html);
		}
	});
	return false;
}

function wpl_report_abuse_submit()
{
    var message_path = '.wpl_show_message';
	var request_str = 'wpl_format=c:functions:ajax&wpl_function=report_abuse_submit&'+wplj('#wpl_report_abuse_form').serialize();
	wplj(message_path).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />`);
	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success)
			{
				wpl_show_messages(data.message, message_path, 'wpl_green_msg');

				// Trigger the success event
				wplj('#wpl_form_report_abuse_container').trigger('success');

				//Hide Inputs
				wplj('.wpl-gen-form-wp .wpl-gen-form-row').each( function () {
					wplj(this).fadeOut();
				});

				//Hide header
				wplj('.realtyna-lightbox-title').fadeOut();

				// change close button color
				wplj('.realtyna-lightbox-close-btn').css('color','#000');

				// add border to box
				wplj('.realtyna-lightbox-text-wrap').css('box-shadow', 'rgb(160, 220, 30) 0px 0px 0px 2px')
			}
			else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
		}
	});
	
	return false;
}

function wpl_send_to_friend_get_form(id)
{
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=send_to_friend_form&pid='+id+'&form_id=0';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").html(html);
			if(wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").find('.g-recaptcha').length > 0) {
				grecaptcha.render(document.getElementById(wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").find('.g-recaptcha').first().attr('id')));
			}
		}
	});
    return false;
}

function wpl_send_to_friend_submit()
{
    var message_path = '.wpl_show_message';
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=send_to_friend_submit&'+wplj('#wpl_send_to_friend_form').serialize();
	wplj(message_path).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />`);
	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success)
			{
				wpl_show_messages(data.message, message_path, 'wpl_green_msg');

				// Trigger the success event
				wplj('#wpl_form_send_to_friend_container').trigger('success');

				//Hide Inputs
				wplj('.wpl-gen-form-wp .wpl-gen-form-row').each( function () {
					wplj(this).fadeOut();
				});

				//Hide header
				wplj('.realtyna-lightbox-title').fadeOut();

				// change close button color
				wplj('.realtyna-lightbox-close-btn').css('color','#000');

				// add border to box
				wplj('.realtyna-lightbox-text-wrap').css('box-shadow', 'rgb(160, 220, 30) 0px 0px 0px 2px')
			}
			else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
		}
	});
    return false;
}

function wpl_request_a_visit_get_form(id)
{
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=request_a_visit_form&pid='+id+'&form_id=0';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").html(html);
		}
	});
    return false;
}

function wpl_request_a_visit_submit()
{
    var message_path = '.wpl_show_message';
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=request_a_visit_submit&'+wplj('#wpl_request_a_visit_form').serialize();
	wplj(message_path).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />`);
	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success)
			{
				wpl_show_messages(data.message, message_path, 'wpl_green_msg');

				// Trigger the success event
				wplj('#wpl_form_request_a_visit_container').trigger('success');

				//Hide Inputs
				wplj('.wpl-gen-form-wp .wpl-gen-form-row').each( function () {
					wplj(this).fadeOut();
				});

				//Hide header
				wplj('.realtyna-lightbox-title').fadeOut();

				// change close button color
				wplj('.realtyna-lightbox-close-btn').css('color','#000');

				// add border to box
				wplj('.realtyna-lightbox-text-wrap').css('box-shadow', 'rgb(160, 220, 30) 0px 0px 0px 2px')
			}
			else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
		}
	});
    return false;
}

function wpl_adding_price_request(id)
{
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=adding_price_request&pid='+id+'&form_id=0';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").html(html);
		}
	});
    return false;
}

function adding_price_request_submit()
{
    var message_path = '.wpl_show_message';
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=adding_price_request_submit&'+wplj('#adding_price_request_form').serialize();
	wplj(message_path).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />`);
	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success)
			{
				wpl_show_messages(data.message, message_path, 'wpl_green_msg');

				// Trigger the success event
				wplj('#wpl_form_adding_price_request_container').trigger('success');

				//Hide Inputs
				wplj('.wpl-gen-form-wp .wpl-gen-form-row').each( function () {
					wplj(this).fadeOut();
				});

				//Hide header
				wplj('.realtyna-lightbox-title').fadeOut();

				// change close button color
				wplj('.realtyna-lightbox-close-btn').css('color','#000');

				// add border to box
				wplj('.realtyna-lightbox-text-wrap').css('box-shadow', 'rgb(160, 220, 30) 0px 0px 0px 2px')
			}
			else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
		}
	});
    return false;
}

function wpl_watch_changes_get_form(id)
{
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=watch_changes_form&pid='+id+'&form_id=0';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("<?php wpl_esc::attr($this->lightbox_container); ?>").html(html);
		}
	});
    return false;
}

function wpl_watch_changes_submit()
{
    var message_path = '.wpl_show_message';
    var request_str = 'wpl_format=c:functions:ajax&wpl_function=watch_changes_submit&'+wplj('#wpl_watch_changes_form').serialize();
	wplj(message_path).html(`<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />`);
	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			if(data.success)
			{
				wpl_show_messages(data.message, message_path, 'wpl_green_msg');

				wplj('#wpl_watch_changes_toggle').hide();
				wplj('#wpl_watch_changes_form_register').hide();
				wplj('#wpl_watch_changes_form_login').hide();

				// Trigger the success event
				wplj('#wpl_form_watch_changes_container').trigger('success');

				//Hide Inputs
				wplj('.wpl-gen-form-wp .wpl-gen-form-row').each( function () {
					wplj(this).fadeOut();
				});

				//Hide header
				wplj('.realtyna-lightbox-title').fadeOut();

				// change close button color
				wplj('.realtyna-lightbox-close-btn').css('color','#000');

				// add border to box
				wplj('.realtyna-lightbox-text-wrap').css('box-shadow', 'rgb(160, 220, 30) 0px 0px 0px 2px')

			}
			else wpl_show_messages(data.message, message_path, 'wpl_red_msg');
		}
	});
    return false;
}
</script>