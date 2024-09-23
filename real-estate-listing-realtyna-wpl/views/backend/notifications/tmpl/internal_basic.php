<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$subject_column = 'subject';
if(wpl_global::check_multilingual_status()) $subject_column = wpl_addon_pro::get_column_lang_name($subject_column, wpl_global::get_admin_language(), false);
?>
<div class="pwizard-panel">
    <div class="pwizard-section noti-wp">
        <div class="prow">
            <label for="wpl_subject"><?php wpl_esc::html_t('Subject'); ?>:</label>
            <input type="text" name="info[subject]" id="wpl_subject" value="<?php wpl_esc::attr($this->notification->{$subject_column}); ?>" />
            <input type="hidden" name="info[template_path]" value="<?php wpl_esc::attr($this->notification->template);?>" />
        </div>
        <div class="prow">
            <label for="wpl_template"><?php wpl_esc::html_t('Email Template'); ?>:</label>
            <?php wp_editor($this->template, 'wpl_template', array('textarea_name'=>'info[template]', 'teeny'=>true)); ?>
        </div>

        <!-- Check SMS add-on -->
        <?php if(wpl_global::check_addon('sms')): ?>
        <div class="prow">
            <label for="wpl_sms_template"><?php wpl_esc::html_t('SMS Template'); ?>:</label>
            <textarea name="info[wpl_sms_template]" id="wpl_sms_template" cols="30" rows="10"><?php wpl_esc::html($this->sms_template); ?></textarea>
        </div>
        <?php endif; ?>
    </div>
</div>