<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** importing js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, $footer_js);

?>
<div class="file-upload-wp">
    <div class="wpl-button button-1 button-upload">
        <span><?php wpl_esc::html_t('Select Your File'); ?></span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="<?php wpl_esc::attr($html_element_id); ?>" name="<?php wpl_esc::attr($html_element_id); ?>" onchange="return <?php wpl_esc::js($js_function); ?>();" class="<?php wpl_esc::attr($element_class); ?>" type="file" autocomplete="off" />
    </div>
</div>