<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_profiles = $params['wpl_profiles'] ?? array();

$this->user_id = $params['user_id'] ?? NULL;
$this->user_id = $wpl_profiles['current']['data']['id'] ?? $this->user_id;

$this->top_comment = $params['top_comment'] ?? '';

include _wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div class="wpl_contact_container wpl_user_contact_container" id="wpl_user_contact_container<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->user_id); ?>">
    <?php if(trim($this->top_comment ?? '') != ''): ?>
    <p class="wpl_contact_comment"><?php wpl_esc::kses($this->top_comment); ?></p>
    <?php endif; ?>
	<form method="post" action="#" id="wpl_user_contact_form<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->user_id); ?>" onsubmit="return wpl_send_user_contact<?php wpl_esc::attr($this->activity_id); ?>('<?php wpl_esc::attr($this->user_id); ?>');">
        <div class="form-field text-field">
            <input class="text-box" type="text" id="wpl_user_contact_fullname<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->user_id); ?>" name="fullname" placeholder="<?php wpl_esc::attr_t('Full Name'); ?>" />
        </div>

        <div class="form-field text-field">
            <input class="text-box" type="text" id="wpl_user_contact_phone<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->user_id); ?>" name="phone" placeholder="<?php wpl_esc::attr_t('Phone'); ?>" />
        </div>

        <div class="form-field text-field">
            <input class="text-box" type="text" id="wpl_user_contact_email<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->user_id); ?>" name="email" placeholder="<?php wpl_esc::attr_t('Email'); ?>" />
        </div>

        <div class="form-field text-area">
            <textarea class="text-box" id="wpl_user_contact_message<?php wpl_esc::attr($this->activity_id); ?><?php wpl_esc::attr($this->user_id); ?>" name="message" placeholder="<?php wpl_esc::attr_t('Message'); ?>"></textarea>
        </div>
        
        <div class="form-field">
        <?php
            /**
            * Fires for integrating contact forms with third party plugins such as captcha plugins
            */
            do_action('comment_form_after_fields');
        ?>
        </div>

        <?php wpl_esc::e(wpl_global::include_google_recaptcha('gre_user_contact_activity')); ?>
        <?php wpl_security::nonce_field('wpl_user_contact_form'); ?>
        
        <div class="form-field button">
            <input class="btn btn-primary" type="submit" value="<?php wpl_esc::attr_t('Send'); ?>" />
        </div>
    </form>
    <div id="wpl_user_contact_ajax_loader<?php wpl_esc::attr($this->activity_id); ?>_<?php wpl_esc::attr($this->user_id); ?>"></div>
    <div id="wpl_user_contact_message<?php wpl_esc::attr($this->activity_id); ?>_<?php wpl_esc::attr($this->user_id); ?>"></div>
</div>