<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if (!isset($this->options->outer_margin)) $this->options->outer_margin = 2;
if (!isset($this->options->size)) $this->options->size = 4;
?>
<div class="fanc-row">
	<label for="wpl_o_picture_width"><?php wpl_esc::html_t('Picture width'); ?></label>
	<input class="text_box" name="option[picture_width]" type="text" id="wpl_o_picture_width"
		   value="<?php wpl_esc::attr($this->options->picture_width ?? '90'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_picture_height"><?php wpl_esc::html_t('Picture height'); ?></label>
	<input class="text_box" name="option[picture_height]" type="text" id="wpl_o_picture_height"
		   value="<?php wpl_esc::attr($this->options->picture_height ?? '90'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_outer_margin"><?php wpl_esc::html_t('Outer margin'); ?></label>
	<select class="text_box" name="option[outer_margin]" type="text" id="wpl_o_outer_margin">
		<?php for ($i = 1; $i <= 4; $i++): ?>
			<option value="<?php wpl_esc::attr($i); ?>" <?php if (isset($this->options->outer_margin) and $this->options->outer_margin == $i) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::attr($i); ?>
			</option>
		<?php endfor; ?>
	</select>
</div>
<div class="fanc-row">
	<label for="wpl_o_size"><?php wpl_esc::html_t('Size'); ?></label>
	<select class="text_box" name="option[size]" type="text" id="wpl_o_size">
		<?php for ($i = 1; $i <= 10; $i++): ?>
			<option value="<?php wpl_esc::attr($i); ?>" <?php if (isset($this->options->size) and $this->options->size == $i) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::attr($i); ?>
			</option>
		<?php endfor; ?>
	</select>
</div>