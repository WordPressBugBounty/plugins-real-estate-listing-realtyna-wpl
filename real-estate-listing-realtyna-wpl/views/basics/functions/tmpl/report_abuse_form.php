<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-links-report-wp" id="wpl_form_report_abuse_container">
    <form class="wpl-gen-form-wp" id="wpl_report_abuse_form" onsubmit="wpl_report_abuse_submit(); return false;" novalidate="novalidate">
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-name"><?php wpl_esc::html_t('Name'); ?></label>
            <input type="text" name="wplfdata[name]" id="wpl-links-report-name" placeholder="<?php wpl_esc::attr_t('Name'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-email"><?php wpl_esc::html_t('Email'); ?></label>
            <input type="email" name="wplfdata[email]" placeholder="<?php wpl_esc::attr_t('Email'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-tel"><?php wpl_esc::html_t('Phone'); ?></label>
            <input type="tel" name="wplfdata[tel]" placeholder="<?php wpl_esc::attr_t('Phone'); ?>" />
        </div>
        <div class="wpl-gen-form-row">
            <label for="wpl-links-report-message"><?php wpl_esc::html_t('Message'); ?></label>
            <textarea name="wplfdata[message]" placeholder="<?php wpl_esc::attr_t('Message'); ?>"></textarea>
        </div>
        <div class="wpl-gen-form-row wpl-recaptcha">
            <label for="wpl-links-report-message"></label>
            <?php wpl_esc::e(wpl_global::include_google_recaptcha('gre_report_listing', $this->property_id)); ?>
            <?php wpl_security::nonce_field('wpl_report_abuse_form'); ?>
        </div>
        
        <div class="wpl-gen-form-row wpl-util-right">
            <input class="wpl-gen-btn-1" type="submit" value="<?php wpl_esc::attr_t('Send'); ?>" />
        </div>
        <div class="wpl_show_message"></div>

        <input type="hidden" name="wplfdata[property_id]" value="<?php wpl_esc::numeric($this->property_id); ?>" />
    </form>
</div>