<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
function wpl_user_logreg()
{
    var request = wplj('#wpl_user_login_register_form').serialize();
    var message_path = '#wpl_user_login_register_form_show_messages';
    var wplmethod = wplj("#wpl_user_logreg_guest_method").val();
    
    /** Make button disabled **/
    wplj("#wpl_user_login_register_"+wplmethod+"_submit").attr('disabled', 'disabled');
    
    wplj.ajax(
    {
        url: '<?php wpl_esc::e($this->wplraw ? wpl_global::get_wp_url() : wpl_global::get_full_url()); ?>',
        data: 'wpl_format=f:profile_show:ajax&'+request,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(response)
        {
            /** Make button enabled **/
            wplj("#wpl_user_login_register_"+wplmethod+"_submit").removeAttr('disabled');
            
            if(response.success)
            {
                if(wplmethod == 'login')
                {
                    wplj("#wpl_user_login_register_form").hide();
                    wplj("#wpl_user_login_register_toggle").hide();

                    setTimeout(function()
                    {
                        wplj._realtyna.lightbox.close();
                    }, 2000);
                }
                else
                {
                    wplj("#wpl_lr_username").val(wplj("#wpl_lr_email").val());
                    wplj("#wpl_lr_password").val('');
                    wpl_user_logreg_toggle('login');
                }
                
                wpl_show_messages(response.message, message_path, 'wpl_green_msg');
                if(response.data.token) wplj("#wpl_user_login_register_token").val(response.data.token);
            }
            else
            {
                wpl_show_messages(response.message, message_path, 'wpl_red_msg');
                if(response.data.token) wplj("#wpl_user_login_register_token").val(response.data.token);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            wpl_show_messages("<?php wpl_esc::js_t('Error Occurred!'); ?>", message_path, 'wpl_red_msg');
            
            /** Make button enabled **/
            wplj("#wpl_user_login_register_"+wplmethod+"_submit").removeAttr('disabled');
        }
    });
}

function wpl_user_logreg_toggle(type)
{
    if(typeof type === undefined) type = 'register';
    
    if(type === 'login')
    {
        wplj("#wpl_user_login_register_toggle_register").hide();
        wplj("#wpl_user_login_register_toggle_login").show();
        
        wplj("#wpl_user_login_register_form_register").hide();
        wplj("#wpl_user_login_register_form_login").show();
        
        wplj("#wpl_user_login_register_register_submit").hide();
        wplj("#wpl_user_login_register_login_submit").show();
    }
    else
    {
        wplj("#wpl_user_login_register_toggle_register").show();
        wplj("#wpl_user_login_register_toggle_login").hide();
        
        wplj("#wpl_user_login_register_form_register").show();
        wplj("#wpl_user_login_register_form_login").hide();
        
        wplj("#wpl_user_login_register_register_submit").show();
        wplj("#wpl_user_login_register_login_submit").hide();
    }
    
    /** Set type to form values **/
    wplj("#wpl_user_logreg_guest_method").val(type);
}

<?php

$membership_addon_is_available = wpl_global::check_addon('membership');

$gmail_login_enabled = wpl_settings::get('gmail_login_api'); 
 
?>
<?php if ( $gmail_login_enabled && $membership_addon_is_available ) : ?>
function gmail_ouath(googleUser) {
    auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut()
    var wplmethod = wplj("#wpl_user_logreg_guest_method").val();

    var profile = googleUser.getBasicProfile();
    var id = profile.getId();
    var name = profile.getGivenName();
    var familyName = profile.getFamilyName();
    var email = profile.getEmail();

    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;
    var token = wplj("#wpl_membership_login_token").val();

    var request = "user_id="+id+"&first_name="+name+"&last_name="+familyName+"&email="+email+"&id_token="+id_token+"&token="+token;
    var message_path = '#wpl_user_login_register_form_show_messages';

    /** Make login button disabled **/
    wplj("#wpl_user_login_register_"+wplmethod+"_submit").removeAttr('disabled');


    wpl_login_ajax = wplj.ajax(
        {
            url: '<?php wpl_esc::current_url(); ?>',
            data: 'wpl_format=f:addon_membership:ajax&wpl_function=login_gmail&'+request,
            type: 'POST',
            dataType: 'json',
            cache: false,
            success:function(response){

                wplj("#wpl_login_submit").removeAttr('disabled');

                if(response.success)
                {
                    wpl_show_messages(response.message, message_path, 'wpl_green_msg');

                    if(wplmethod == 'login')
                    {
                        wplj("#wpl_user_login_register_form").hide();
                        wplj("#wpl_user_login_register_toggle").hide();

                        setTimeout(function()
                        {
                            wplj._realtyna.lightbox.close();
                        }, 2000);
                    }
                    else
                    {
                        wplj("#wpl_lr_username").val(wplj("#wpl_lr_email").val());
                        wplj("#wpl_lr_password").val('');
                        wpl_user_logreg_toggle('login');
                    }
                }
                else
                {
                    wpl_show_messages(response.message, message_path, 'wpl_red_msg');

                    if(response.data.token) wplj("#wpl_membership_login_token").val(response.data.token);
                    if(response.data.redirect_to) window.location = response.data.redirect_to;
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                wpl_show_messages("<?php wpl_esc::js_t('Error Occurred!'); ?>", message_path, 'wpl_red_msg');

                /** Make login button enabled **/
                wplj("#wpl_user_login_register_"+wplmethod+"_submit").removeAttr('disabled');
            }
        });
}
<?php endif; ?>
</script>