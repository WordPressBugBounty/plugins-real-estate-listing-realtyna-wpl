<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="wpl_o_html"><?php wpl_esc::html_t('HTML'); ?></label>
    <textarea class="long" name="option[html]" id="wpl_o_html"><?php wpl_esc::e(isset($this->options->html) ? stripslashes($this->options->html) : ''); ?></textarea>
</div>