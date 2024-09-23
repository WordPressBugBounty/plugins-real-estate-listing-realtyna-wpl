<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-links-req-visit-wp" id="wpl_form_request_a_visit_container">
    <form class="wpl-gen-form-wp" id="wpl_request_a_visit_form" onsubmit="wpl_request_a_visit_submit(); return false;" novalidate="novalidate">
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-name"><?php wpl_esc::html_t('Name'); ?></label>
            <input type="text" name="wplfdata[name]" id="wpl-links-req-visit-name" placeholder="<?php wpl_esc::attr_t('Name'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-email"><?php wpl_esc::html_t('Email'); ?></label>
            <input type="email" name="wplfdata[email]" id="wpl-links-req-visit-email" placeholder="<?php wpl_esc::attr_t('Email'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-tel"><?php wpl_esc::html_t('Phone'); ?></label>
            <input type="tel" name="wplfdata[tel]" id="wpl-links-req-visit-tel" placeholder="<?php wpl_esc::attr_t('Phone'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-req-visit-message"><?php wpl_esc::html_t('Message'); ?></label>
            <textarea name="wplfdata[message]" id="wpl-links-req-visit-message" placeholder="<?php wpl_esc::attr_t('Message'); ?>"></textarea>
        </div>
        <div class="wpl-gen-form-row wpl-recaptcha">
            <label for="wpl-links-report-message"></label>
            <?php wpl_esc::e(wpl_global::include_google_recaptcha('gre_request_visit', $this->property_id)); ?>
            <?php wpl_security::nonce_field('wpl_request_a_visit_form'); ?>
        </div>
        
        <div class="wpl-gen-form-row wpl-util-right">
            <input class="wpl-gen-btn-1" type="submit" value="<?php wpl_esc::attr_t('Send'); ?>" />
        </div>
        <div class="wpl_show_message"></div>

        <input type="hidden" name="wplfdata[property_id]" value="<?php wpl_esc::numeric($this->property_id); ?>" />
    </form>
</div>