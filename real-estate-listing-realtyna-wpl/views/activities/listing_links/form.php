<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

?>
<div class="fanc-row">
    <label for="wpl_o_facebook"><?php wpl_esc::html_t('Facebook'); ?></label>
    <input <?php if(isset($this->options->facebook) and $this->options->facebook == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[facebook]" type="checkbox" id="wpl_o_facebook" value="<?php wpl_esc::attr($this->options->facebook ?? '1'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_twitter"><?php wpl_esc::html_t('Twitter'); ?></label>
    <input <?php if(isset($this->options->twitter) and $this->options->twitter == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[twitter]" type="checkbox" id="wpl_o_twitter" value="<?php wpl_esc::attr($this->options->twitter ?? '1'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_pinterest"><?php wpl_esc::html_t('Pinterest'); ?></label>
    <input <?php if(isset($this->options->pinterest) and $this->options->pinterest == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[pinterest]" type="checkbox" id="wpl_o_pinterest" value="<?php wpl_esc::attr($this->options->pinterest ?? '1'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_linkedin"><?php wpl_esc::html_t('Linkedin'); ?></label>
    <input <?php if(isset($this->options->linkedin) and $this->options->linkedin == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[linkedin]" type="checkbox" id="wpl_o_linkedin" value="<?php wpl_esc::attr($this->options->linkedin ?? '1'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_favorite"><?php wpl_esc::html_t('Favorite'); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_favorite" class="gray_tip"><?php wpl_esc::html_t('Pro addon must be installed for this!'); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->favorite) and $this->options->favorite == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[favorite]" type="checkbox" id="wpl_o_favorite" value="<?php wpl_esc::attr($this->options->favorite ?? '1'); ?>" />
    <?php endif; ?>
    
</div>
<div class="fanc-row">
    <label for="wpl_o_pdf"><?php wpl_esc::html_t('PDF'); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_pdf" class="gray_tip"><?php wpl_esc::html_t('Pro addon must be installed for this!'); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->pdf) and $this->options->pdf == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[pdf]" type="checkbox" id="wpl_o_pdf" value="<?php wpl_esc::attr($this->options->pdf ?? '1'); ?>" />
    <?php endif; ?>
    
</div>
<div class="fanc-row">
    <label for="wpl_o_report_abuse"><?php wpl_esc::html_t('Report Listing'); ?></label>
    
    <?php if(!wpl_global::check_addon('pro')): ?>
	<span id="wpl_o_report_abuse" class="gray_tip"><?php wpl_esc::html_t('Pro addon must be installed for this!'); ?></span>
	<?php else: ?>
    <input <?php if(isset($this->options->report_abuse) and $this->options->report_abuse == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[report_abuse]" type="checkbox" id="wpl_o_report_abuse" value="<?php wpl_esc::attr($this->options->report_abuse ?? '1'); ?>" />
    <?php endif; ?>
    
</div>
<div class="fanc-row">
    <label for="wpl_o_send_to_friend"><?php wpl_esc::html_t('Send to Friend'); ?></label>
    <input <?php if(isset($this->options->send_to_friend) and $this->options->send_to_friend == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[send_to_friend]" type="checkbox" id="wpl_o_send_to_friend" value="<?php wpl_esc::attr($this->options->send_to_friend ?? '1'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_request_a_visit"><?php wpl_esc::html_t('Request a Visit'); ?></label>
    <input <?php if(isset($this->options->request_a_visit) and $this->options->request_a_visit == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[request_a_visit]" type="checkbox" id="wpl_o_request_a_visit" value="<?php wpl_esc::attr($this->options->request_a_visit ?? '1'); ?>" />
</div>
<?php if(wpl_global::check_addon('save_searches')): ?>
    <div class="fanc-row">
        <label for="wpl_o_watch_changes"><?php wpl_esc::html_t('Watch Changes'); ?></label>
        <input <?php if(isset($this->options->watch_changes) and $this->options->watch_changes == '1') wpl_esc::e('checked="checked"'); ?> class="text_box" name="option[watch_changes]" type="checkbox" id="wpl_o_watch_changes" value="<?php wpl_esc::attr($this->options->watch_changes ?? '1'); ?>" />
    </div>
<?php endif; ?>
<div class="fanc-row">
    <label for="wpl_o_crm"><?php wpl_esc::html_t('CRM'); ?></label>
    
    <?php if(!wpl_global::check_addon('crm')): ?>
    <span id="wpl_o_crm" class="gray_tip"><?php wpl_esc::html_t('The CRM Add-on must be installed for this feature!'); ?></span>
    <?php else: ?>
    <input <?php if(isset($this->options->crm) and $this->options->crm == '1') wpl_esc::e('checked="checked'); ?> class="text_box" name="option[crm]" type="checkbox" id="wpl_o_crm" value="<?php wpl_esc::attr($this->options->crm ?? '1'); ?>" />
    <?php endif; ?>
    
</div>

<div class="fanc-row">
    <label for="wpl_o_adding_price_request"><?php wpl_esc::html_t('Price Request'); ?></label>
    <input <?php if(isset($this->options->adding_price_request) and $this->options->adding_price_request == '1') wpl_esc::e('checked="checked'); ?> class="text_box" name="option[adding_price_request]" type="checkbox" id="wpl_o_adding_price_request" value="<?php wpl_esc::attr($this->options->adding_price_request ?? '1'); ?>" />
</div>