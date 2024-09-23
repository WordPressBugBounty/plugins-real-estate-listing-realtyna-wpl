<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

$this->top_comment = isset($params['top_comment']) ? $params['top_comment'] : '';

$user = wpl_users::get_wpl_user();

include _wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_contact_container wpl-contact-listing-wp" id="wpl_contact_container<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->property_id); ?>">
    <?php if(trim($this->top_comment) != ''): ?>
    <p class="wpl_contact_comment"><?php wpl_esc::kses($this->top_comment); ?></p>
    <?php endif; ?>
	<form method="post" action="#" id="wpl_contact_form<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->property_id); ?>" onsubmit="return wpl_send_contact<?php wpl_esc::attr($this->activity_id); ?>(<?php wpl_esc::attr($this->property_id); ?>);">
        <div class="form-field">
            <input class="text-box" type="text" value="<?php if(wpl_global::check_addon('membership')) wpl_esc::attr($user->first_name); if(!empty($user->last_name)) wpl_esc::attr(' ' . $user->last_name); ?>" id="wpl_contact_fullname<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->property_id); ?>" name="fullname" placeholder="<?php wpl_esc::html_t('Full Name'); ?>" />
        </div>

        <div class="form-field">
            <input class="text-box" type="text" value="<?php if(wpl_global::check_addon('membership')) wpl_esc::attr(!empty($user->mobile) ? $user->mobile : $user->tel); ?>" id="wpl_contact_phone<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->property_id); ?>" name="phone" placeholder="<?php wpl_esc::html_t('Phone'); ?>" />
        </div>

        <div class="form-field">
            <input class="text-box" type="text" value="<?php if(wpl_global::check_addon('membership')) wpl_esc::attr($user->main_email); ?>" id="wpl_contact_email<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->property_id); ?>" name="email" placeholder="<?php wpl_esc::html_t('Email'); ?>" />
        </div>

        <div class="form-field wpl-contact-listing-msg">
            <textarea class="text-box" id="wpl_contact_message<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->property_id); ?>" name="message" placeholder="<?php wpl_esc::html_t('Message'); ?>"></textarea>
        </div>
        
        <div class="form-field">
        <?php
            /**
            * Fires for integrating contact forms with third party plugins such as captcha plugins
            */
            do_action('comment_form_after_fields');
        ?>
        </div>
        <div class="contact-recaptcha">
            <?php wpl_esc::e(wpl_global::include_google_recaptcha('gre_listing_contact_activity', $this->property_id)); ?>
            <?php wpl_security::nonce_field('wpl_listing_contact_form'); ?>
        </div>
        <div class="form-field wpl-contact-listing-btn">
            <input class="btn btn-primary" type="submit" value="<?php wpl_esc::html_t('Send'); ?>" />
        </div>
    </form>
    <div id="wpl_contact_ajax_loader<?php wpl_esc::attr($this->activity_id); ?>_<?php wpl_esc::attr($this->property_id); ?>"></div>
    <div id="wpl_contact_message<?php wpl_esc::attr($this->activity_id); ?>_<?php wpl_esc::attr($this->property_id); ?>"></div>
</div>