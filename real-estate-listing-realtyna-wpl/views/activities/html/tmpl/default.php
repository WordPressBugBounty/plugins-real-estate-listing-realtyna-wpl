<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$HTML = isset($params['html']) ? wpl_esc::return_t($params['html']) : '';

/** return **/
if(!trim($HTML)) return NULL;
?>
<div class="wpl_html_activity" id="wpl_html_activity<?php wpl_esc::attr($this->activity_id); ?>">
    <?php wpl_esc::e(do_shortcode(stripslashes($HTML))); ?>
</div>