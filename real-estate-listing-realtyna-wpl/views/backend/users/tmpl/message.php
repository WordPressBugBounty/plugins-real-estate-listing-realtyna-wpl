<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap">
    <div class="wpl_message_container" id="wpl_message_container">
        <?php wpl_esc::html($this->message); ?>
	</div>
</div>