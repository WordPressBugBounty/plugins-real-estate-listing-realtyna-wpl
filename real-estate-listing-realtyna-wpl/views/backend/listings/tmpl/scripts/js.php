<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
    var trash_class = 'icon-trash';
    var restore_class = 'icon-restore';
    var confirm_class = 'icon-confirm';
    var unconfirm_class = 'icon-unconfirm';
    var ajax_loader_image_class = 'ajax-inline-save';
    var delete_class = 'icon-delete';
    var clone_class = 'icon-clone';

	function wpl_revert_expire( id )
	{
        let message = '<?php wpl_esc::js_t("Are you sure to revert this expired property?"); ?>';
		let confirmation = confirm(message);

        if(!confirmation) return

		let element_id = wplj("#" + id).data('id');
		
        wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=revert_expired_property&pid="+element_id+"&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>",
			success: function (data) {
				if(data.success) {
					wplj("#pmanager_expired_status" + element_id).fadeOut(300, function() { wplj(this).remove(); });
				} else {
					wpl_alert( data.message );
				}
			}
		});
		return false;
	}

    function wpl_search_listings()
    {
        /** Create full url of search **/
        var search_str = '<?php wpl_esc::current_url(); ?>';

        wplj("#wpl_listing_manager_search_form_cnt select").each(function(ind, elm)
        {
            if(wplj(elm).val() != '-1') search_str = wpl_update_qs(elm.id, wplj(elm).val(), search_str);
            else if(wplj(elm).val() == '-1') search_str = wpl_update_qs(elm.id, '', search_str);
        });

        wplj("#wpl_listing_manager_search_form_cnt input:text, #wpl_listing_manager_search_form_cnt input[type='hidden']").each(function(ind, elm)
        {
            search_str = wpl_update_qs(elm.id, wplj(elm).val(), search_str);
        });

        window.location = search_str;
    }

    function wpl_reset_listings()
    {
        wplj("#wpl_listing_manager_search_form_cnt").find(':input').each(function()
        {
            switch(this.type)
            {
                case 'text':

                    wplj(this).val('');
                    break;

                case 'select-multiple':

                    wplj(this).multiselect("uncheckAll");
                    break;

                case 'select-one':

                    wplj(this).val('-1');
                    wplj(this).trigger("chosen:updated");
                    break;

                case 'password':
                case 'textarea':

                    wplj(this).val('');
                    break;

                case 'checkbox':
                case 'radio':

                    this.checked = false;
                    break;
            }
        });

        wpl_search_listings();
    }

    function select_all_checkboxes()
    {
        wplj(".js-pcheckbox").each(function()
        {
            wplj(this).attr("checked", status);
        });
    }

    function deselect_all_checkboxes()
    {
        wplj(".js-pcheckbox").each(function()
        {
            wplj(this).removeAttr('checked');
        });
    }

    function toggle_checkboxes()
    {
        wplj(".js-pcheckbox").each(function()
        {
            if(wplj(this).attr("checked"))
                wplj(this).removeAttr('checked');
            else
                wplj(this).attr("checked", status);
        });
    }

    function mass_delete_completely_properties()
    {
        var message = '<?php wpl_esc::html_t("Are you sure you want to delete these properties?"); ?>';
        var confirmation = confirm(message);

        if(!confirmation) return

        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');
            purge_property(pid, true);
        });
    }

    function mass_trash_properties()
    {
        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');

            if(wplj("#pmanager_trash"+pid).find('i').hasClass(trash_class)) trash_property(pid);
        });
    }

    function mass_restore_properties()
    {
        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');

            if(wplj("#pmanager_trash"+pid).find('i').hasClass(restore_class)) trash_property(pid);
        });
    }

    function mass_confirm_properties()
    {
        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');

            if(wplj("#pmanager_confirm"+pid).find('i').hasClass(unconfirm_class)) confirm_property(pid);
        });
    }

    function mass_unconfirm_properties()
    {
        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');

            if(wplj("#pmanager_confirm"+pid).find('i').hasClass(confirm_class)) confirm_property(pid);
        });
    }

    function mass_change_user(uid)
    {
        if(!uid)
        {
            wpl_show_messages('<?php wpl_esc::html_t('User is not valid!'); ?>', '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
            return false;
        }

        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');
            wplj("#pmanager_change_user_select"+pid).val(uid);

            change_user(pid, uid);
        });
    }

    function mass_additional_agents()
    {
        wplj('.js-pcheckbox:checked').each(function()
        {
            var pid = wplj(this).attr('id');
            var uids = wplj("#pmanager_mass_additional_agents_select").val();
            wplj("#pmanager_additional_agents_select"+pid).val(uids);
            wplj("#pmanager_additional_agents_select"+pid).trigger('chosen:updated');

            additional_agents(pid);
        });
    }
    <?php if(wpl_global::check_addon("facebook") && get_option('wpl_addon_facebook_init_info') !== false && get_option( 'wpl_addon_facebook_catalog_id' ) !== false): ?>
    function mass_facebook_publish_properties()
    {
        var pids = "";
        var new_class = unconfirm_class;
        var prev_class = confirm_class;
        var onclick = wplj(".wpl-mass-publish-facebook").attr("onclick");


        wplj(".wpl-mass-publish-facebook").removeAttr("onclick");

        wplj('.js-pcheckbox:checked').each(function()
        {
            pid = wplj(this).attr('id');

            if(wplj("#pmanager_facebook_publish"+pid).find('i').hasClass(confirm_class))
            {
                if(pids)
                    pids += ','+wplj(this).attr('id');
                else
                    pids = wplj(this).attr('id');
            }
        });

        var pids_array = pids.split(',');

        wplj(".wpl-mass-publish-facebook i").removeClass(prev_class).addClass(ajax_loader_image_class);
        wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:addon_facebook:ajax&wpl_function=set_listings&pids="+pids,
			success: function (data) {
				if(data.status == true)
				{
					for (i = 0; i < pids_array.length; i++)
					{
						wplj("#pmanager_facebook_publish"+pids_array[i]+" i").removeClass(ajax_loader_image_class).addClass(new_class);
						wplj("#pmanager_facebook_publish"+pids_array[i]+" label").text("<?php wpl_esc::html_t('unPublish From FB'); ?>");
					}
				}
				else if(data.status == false)
				{
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}

				wplj(".wpl-mass-publish-facebook").attr("onclick",onclick);
				wplj(".wpl-mass-publish-facebook i").removeClass(ajax_loader_image_class).addClass(prev_class);
			}
		});
    }

    function facebook_publish(pid) {

        var onclick = wplj("#pmanager_facebook_publish"+pid).attr("onclick");
        wplj("#pmanager_facebook_publish"+pid).removeAttr("onclick");

        if(wplj("#pmanager_facebook_publish"+pid+" i").hasClass(confirm_class))
        {
            var new_class = unconfirm_class;
            var prev_class = confirm_class;
            var confirmed = 0;
            var wpl_function = "set_listings";

        }

        if(wplj("#pmanager_facebook_publish"+pid+" i").hasClass(unconfirm_class))
        {
            var new_class = confirm_class;
            var prev_class = unconfirm_class;
            var confirmed = 1;
            var wpl_function = "delete_listings";
        }

        wplj("#pmanager_facebook_publish"+pid+" i").removeClass(prev_class).removeClass(new_class).addClass(ajax_loader_image_class);

        wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:addon_facebook:ajax&wpl_function="+wpl_function+"&pids="+pid,
			success: function (data) {
				if(data.status == true)
				{
					wplj("#pmanager_facebook_publish"+pid+" i").removeClass(ajax_loader_image_class).addClass(new_class);

					if(confirmed) wplj("#pmanager_facebook_publish"+pid+" label").text("<?php wpl_esc::html_t('Publish on FB'); ?>");
					else wplj("#pmanager_facebook_publish"+pid+" label").text("<?php wpl_esc::html_t('unPublish From FB'); ?>");
				}
				else if(data.status == false)
				{
					wplj("#pmanager_facebook_publish"+pid+" i").removeClass(ajax_loader_image_class).addClass(prev_class);
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
				wplj("#pmanager_facebook_publish"+pid).attr("onclick",onclick);
			}
		});
    }
    <?php endif; ?>

    function purge_property(pid, confirmation)
    {
        if(typeof confirmation == 'undefined') confirmation = 0;

        if(!confirmation)
        {
            var message = '<?php wpl_esc::html_t("Are you sure you want to delete this property?"); ?>';
            confirmation = confirm(message);

            if(!confirmation) return;
        }

        wplj("#pmanager_delete"+pid+" i").removeClass(delete_class).addClass(ajax_loader_image_class);

		wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=purge_property&pid="+pid+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>',
			success: function (data) {
				if(data.success == 1)
				{
					wplj("#plist_main_div_"+pid).fadeOut('slow');
				}
				else if(data.success != 1)
				{
					wplj("#pmanager_delete"+pid+" i").removeClass(ajax_loader_image_class).addClass(delete_class);
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
			}
		});
    }

    function confirm_property(pid)
    {
        if(wplj("#pmanager_confirm"+pid+" i").hasClass(confirm_class))
        {
            var new_class = unconfirm_class;
            var prev_class = confirm_class;
            var confirmed = 0;
        }

        if(wplj("#pmanager_confirm"+pid+" i").hasClass(unconfirm_class))
        {
            var new_class = confirm_class;
            var prev_class = unconfirm_class;
            var confirmed = 1;
        }

        wplj("#pmanager_confirm"+pid+" i").removeClass(prev_class).addClass(ajax_loader_image_class);

        wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=update_property&pid="+pid+"&action=confirm&value="+confirmed+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>',
			success: function (data) {
				if(data.success == 1)
				{
					wplj("#pmanager_confirm"+pid+" i").removeClass(ajax_loader_image_class).addClass(new_class);

					if(confirmed) wplj("#pmanager_confirm"+pid+" span").text("<?php wpl_esc::html_t('Confirmed'); ?>");
					else wplj("#pmanager_confirm"+pid+" span").text("<?php wpl_esc::html_t('Unconfirmed'); ?>");
				}
				else if(data.success != 1)
				{
					wplj("#pmanager_confirm"+pid+" i").removeClass(ajax_loader_image_class).addClass(prev_class);
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
			}
		});
    }

    function trash_property(pid)
    {
        if(wplj("#pmanager_trash"+pid+" i").hasClass(trash_class))
        {
            var new_class = restore_class;
            var prev_class = trash_class;
            var deleted = 1;
        }

        if(wplj("#pmanager_trash"+pid+" i").hasClass(restore_class))
        {
            var new_class = trash_class;
            var prev_class = restore_class;
            var deleted = 0;
        }

        wplj("#pmanager_trash"+pid+" i").removeClass(prev_class).addClass(ajax_loader_image_class);

        wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=update_property&pid="+pid+"&action=trash&value="+deleted+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>',
			success: function (data) {
				if(data.success)
				{
					wplj("#pmanager_trash"+pid+" i").removeClass(ajax_loader_image_class).addClass(new_class);

					if(deleted) wplj("#pmanager_trash"+pid+" span").text("<?php wpl_esc::html_t('Restore'); ?>");
					else wplj("#pmanager_trash"+pid+" span").text("<?php wpl_esc::html_t('Trash'); ?>");
				}
				else
				{
					wplj("#pmanager_trash"+pid+" i").removeClass(ajax_loader_image_class).addClass(prev_class);
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
			}
		});
    }

    function clone_property(pid)
    {
        wplj("#pmanager_clone"+pid+" i").removeClass(clone_class).addClass(ajax_loader_image_class);

		wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=clone_property&pid="+pid+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>',
			success: function (data) {
				if(data.success)
				{
					window.location = data.data.edit_link;
				}
				else
				{
					wplj("#pmanager_clone"+pid+" i").removeClass(ajax_loader_image_class).addClass(clone_class);
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
			}
		});
    }

    function change_user(pid, uid)
    {
        wplj("#pmanager_change_user_label"+pid).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

		wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=change_user&pid="+pid+"&uid="+uid+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>',
			success: function (data) {
				if(data.success)
				{
					wplj("#pmanager_change_user_label"+pid).html('<?php wpl_esc::html_t('User'); ?>: ');
				}
				else
				{
					wplj("#pmanager_change_user_label"+pid).html('<?php wpl_esc::html_t('User'); ?>: ');
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
			}
		});
    }

    function additional_agents(pid)
    {
        var uids = wplj("#pmanager_additional_agents_select"+pid).val();

        wplj("#pmanager_additional_agents_label"+pid).html('<img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader3.gif')); ?>" />');

		wplj.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '<?php wpl_esc::current_url(); ?>',
			data: "wpl_format=b:listings:ajax&wpl_function=additional_agents&pid="+pid+"&uids="+uids+'&_wpnonce=<?php wpl_esc::attr($this->nonce); ?>',
			success: function (data) {
				if(data.success === 1)
				{
					wplj("#pmanager_additional_agents_label"+pid).html("<?php wpl_esc::html_t('Additional Agents'); ?>: ");
				}
				else if(data.success !== 1)
				{
					wplj("#pmanager_additional_agents_label"+pid).html("<?php wpl_esc::html_t('Additional Agents'); ?>: ");
					wpl_show_messages(data.message, '.wpl_property_manager_list .wpl_show_message', 'wpl_red_msg');
				}
			}
		});
    }

</script>