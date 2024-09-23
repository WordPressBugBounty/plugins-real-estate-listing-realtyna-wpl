<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_top_comment"><?php wpl_esc::html_t('Comment'); ?></label>
    <input class="text_box" name="option[top_comment]" type="text" id="wpl_o_top_comment" value="<?php wpl_esc::attr($this->options->top_comment ?? ''); ?>" />
</div>