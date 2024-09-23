<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-links-send-to-friend-wp" id="wpl_form_send_to_friend_container">
    <form class="wpl-gen-form-wp" id="wpl_send_to_friend_form" onsubmit="wpl_send_to_friend_submit(); return false;" novalidate="novalidate">
        <div class="wpl-gen-form-row">
            <label for="wpl-links-send-to-friend-your_name"><?php wpl_esc::html_t('Your Name'); ?></label>
            <input type="text" name="wplfdata[your_name]" id="wpl-links-send-to-friend-your_name" placeholder="<?php wpl_esc::attr_t('Your Name'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-send-to-friend-your_email"><?php wpl_esc::html_t('Your Email'); ?></label>
            <input type="email" name="wplfdata[your_email]" id="wpl-links-send-to-friend-your_email" placeholder="<?php wpl_esc::attr_t('Your Email'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-send-to-friend-friends_email"><?php wpl_esc::html_t('Friend Email'); ?></label>
            <input type="email" name="wplfdata[friends_email]" id="wpl-links-send-to-friend-friends_email" placeholder="<?php wpl_esc::attr_t('Friend Email'); ?>" />
        </div>
        <div class="wpl-gen-form-row wpl-recaptcha">
            <label for="wpl-links-send-to-friend-email_subject"><?php wpl_esc::html_t('Email Subject'); ?></label>
            <input type="text" name="wplfdata[email_subject]" id="wpl-links-send-to-friend-email_subject" placeholder="<?php wpl_esc::attr_t('Email Subject'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-send-to-friend-message"><?php wpl_esc::html_t('Message'); ?></label>
            <textarea name="wplfdata[message]" id="wpl-links-send-to-friend-message" placeholder="<?php wpl_esc::attr_t('Message'); ?>"></textarea>
        </div>
        <div class="wpl-gen-form-row wpl-recaptcha">
            <label for="wpl-links-report-message"></label>
            <?php wpl_esc::e(wpl_global::include_google_recaptcha('gre_send_to_friend', $this->property_id)); ?>
            <?php wpl_security::nonce_field('wpl_send_to_friend_form'); ?>
        </div>
        
        <div class="wpl-gen-form-row wpl-util-right">
            <input class="wpl-gen-btn-1" type="submit" value="<?php wpl_esc::attr_t('Send'); ?>" />
        </div>
        <div class="wpl_show_message"></div>
        <input type="hidden" name="wplfdata[property_id]" value="<?php wpl_esc::numeric($this->property_id); ?>" />
    </form>
</div>