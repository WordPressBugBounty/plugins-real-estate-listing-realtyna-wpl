<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_chart_background"><?php wpl_esc::html_t('Chart Background'); ?></label>
    <input class="text_box" name="option[chart_background]" type="text" id="wpl_o_chart_background" value="<?php wpl_esc::attr($this->options->chart_background ?? '#ffffff'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_chart_title"><?php wpl_esc::html_t('Chart Title'); ?></label>
    <input class="text_box" name="option[chart_title]" type="text" id="wpl_o_chart_title" value="<?php wpl_esc::attr($this->options->chart_title ?? 'Chart'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_chart_width"><?php wpl_esc::html_t('Chart Width'); ?></label>
    <input class="text_box" name="option[chart_width]" type="text" id="wpl_o_chart_width" value="<?php wpl_esc::attr($this->options->chart_width ?? '100%'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_chart_height"><?php wpl_esc::html_t('Chart Height'); ?></label>
    <input class="text_box" name="option[chart_height]" type="text" id="wpl_o_chart_height" value="<?php wpl_esc::attr($this->options->chart_height ?? '400px'); ?>" />
</div>
<div class="fanc-row">
    <label for="wpl_o_data"><?php wpl_esc::html_t('Chart Data'); ?></label>
    <input class="text_box" name="option[data]" type="text" id="wpl_o_data" value="<?php wpl_esc::attr(isset($this->options->data) ? stripslashes($this->options->data ?? '') : "[['Sony',7], ['Samsumg',13.3], ['LG',14.7], ['Vizio',5.2], ['Insignia', 1.2]]"); ?>" title="<?php wpl_esc::attr_t('Insert rendered string (For developers)'); ?>" />
</div>