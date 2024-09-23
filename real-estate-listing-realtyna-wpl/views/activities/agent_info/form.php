<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_picture_width"><?php wpl_esc::html_t('Picture width'); ?></label>
    <input class="text_box" name="option[picture_width]" type="text" id="wpl_o_picture_width" value="<?php wpl_esc::attr($this->options->picture_width ?? '90'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_picture_height"><?php wpl_esc::html_t('Picture height'); ?></label>
    <input class="text_box" name="option[picture_height]" type="text" id="wpl_o_picture_height" value="<?php wpl_esc::attr($this->options->picture_height ?? '100'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_mailto"><?php wpl_esc::html_t('Mailto:'); ?></label>
    <input class="text_box" name="option[mailto]" type="checkbox" id="wpl_o_mailto" <?php wpl_esc::attr((isset($this->options->mailto) and $this->options->mailto) ? 'checked="checked"' : ''); ?> />
    <span class="gray_tip"><?php wpl_esc::html_t('Sending emails directly'); ?></span>
</div>