<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
	<label for="wpl_o_video_width"><?php wpl_esc::html_t('Video width'); ?></label>
	<input class="text_box" name="option[video_width]" type="text" id="wpl_o_video_width"
		   value="<?php wpl_esc::attr($this->options->video_width ?? '640'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_video_height"><?php wpl_esc::html_t('Video height'); ?></label>
	<input class="text_box" name="option[video_height]" type="text" id="wpl_o_video_height"
		   value="<?php wpl_esc::attr($this->options->video_height ?? '270'); ?>"/>
</div>