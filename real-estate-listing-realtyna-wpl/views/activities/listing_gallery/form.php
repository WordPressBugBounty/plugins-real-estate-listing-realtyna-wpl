<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

$general_watermark_status = wpl_settings::get('watermark_status');
$categories = wpl_items::get_item_categories('gallery');
?>
<div class="fanc-row">
	<label for="wpl_o_image_width"><?php wpl_esc::html_t('Image width'); ?></label>
	<input class="text_box" name="option[image_width]" type="text" id="wpl_o_image_width"
		   value="<?php wpl_esc::attr($this->options->image_width ?? '285'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_image_height"><?php wpl_esc::html_t('Image height'); ?></label>
	<input class="text_box" name="option[image_height]" type="text" id="wpl_o_image_height"
		   value="<?php wpl_esc::attr($this->options->image_height ?? '140'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_image_class"><?php wpl_esc::html_t('Image class'); ?></label>
	<input class="text_box" name="option[image_class]" type="text" id="wpl_o_image_class"
		   value="<?php wpl_esc::attr($this->options->image_class ?? ''); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_category"><?php wpl_esc::html_t('Category'); ?></label>
	<select class="text_box" name="option[category]" type="text" id="wpl_o_category">
		<option value="" <?php if (isset($this->options->category) and $this->options->category == '') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('All'); ?></option>
		<?php foreach ($categories as $category): ?>
			<option value="<?php wpl_esc::attr($category->category_name); ?>" <?php if (isset($this->options->category) and $this->options->category == $category->category_name) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t($category->category_name); ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>
<div class="fanc-row">
	<label for="wpl_o_autoplay"><?php wpl_esc::html_t('Autoplay'); ?></label>
	<select class="text_box" name="option[autoplay]" type="text" id="wpl_o_autoplay">
		<option value="1" <?php if (isset($this->options->autoplay) and $this->options->autoplay == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
		<option value="0" <?php if (isset($this->options->autoplay) and $this->options->autoplay == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
	</select>
</div>

<div class="fanc-row">
	<label for="wpl_o_lazyload"><?php wpl_esc::html_t('Lazyload'); ?></label>
	<select class="text_box" name="option[lazyload]" type="text" id="wpl_o_lazyload">
		<option value="0" <?php if (isset($this->options->lazyload) and $this->options->lazyload == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
		<option value="1" <?php if (isset($this->options->lazyload) and $this->options->lazyload == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
	</select>
</div>

<div class="fanc-row">
	<label for="wpl_o_resize"><?php wpl_esc::html_t('Resize'); ?></label>
	<select class="text_box" name="option[resize]" type="text" id="wpl_o_resize">
		<option value="1" <?php if (isset($this->options->resize) and $this->options->resize == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
		<option value="0" <?php if (isset($this->options->resize) and $this->options->resize == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
	</select>
</div>
<div class="fanc-row">
	<label for="wpl_o_rewrite"><?php wpl_esc::html_t('Rewrite'); ?></label>
	<select class="text_box" name="option[rewrite]" type="text" id="wpl_o_rewrite">
		<option value="0" <?php if (isset($this->options->rewrite) and $this->options->rewrite == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
		<option value="1" <?php if (isset($this->options->rewrite) and $this->options->rewrite == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
	</select>
</div>
<div class="fanc-row">
	<label for="wpl_o_rewrite"><?php wpl_esc::html_t('Show Image Description'); ?></label>
	<select class="text_box" name="option[imgdesc]" type="text" id="wpl_o_imgdesc">
		<option value="0" <?php if (isset($this->options->imgdesc) and $this->options->imgdesc == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
		<option value="1" <?php if (isset($this->options->imgdesc) and $this->options->imgdesc == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
	</select>
</div>
<div class="fanc-row">
	<label for="wpl_o_watermark"><?php wpl_esc::html_t('Watermark'); ?></label>
	<select class="text_box" name="option[watermark]" type="text"
			id="wpl_o_watermark" <?php wpl_esc::attr( !$general_watermark_status ? 'disabled' : ''); ?>>
		<option value="0" <?php if (isset($this->options->watermark) and $this->options->watermark == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
		<option value="1" <?php if (isset($this->options->watermark) and $this->options->watermark == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
	</select>
	<?php if (!$general_watermark_status): ?>
		<div class="gray_tip">
			<?php
			$setting_link = '<a href="' . wpl_global::get_wpl_admin_menu('wpl_admin_settings') . '#Gallery" target="_blank">' . wpl_esc::return_html_t('general watermarking') . '</a>';
			wpl_esc::e(sprintf(wpl_esc::return_html_t('To enable this option, you should enable %s first.'), $setting_link));
			?>
		</div>
	<?php endif; ?>
</div>
<div class="fanc-row">
	<label for="wpl_o_thumbnail"><?php wpl_esc::html_t('Thumbnail'); ?></label>
	<select class="text_box" name="option[thumbnail]" type="text" id="wpl_o_thumbnail">
		<option value="0" <?php if (isset($this->options->thumbnail) and $this->options->thumbnail == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
		<option value="1" <?php if (isset($this->options->thumbnail) and $this->options->thumbnail == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
	</select>
</div>
<div class="fanc-row">
	<label for="wpl_o_thumbnail_width"><?php wpl_esc::html_t('Thumbnail width'); ?></label>
	<input class="text_box" name="option[thumbnail_width]" type="text" id="wpl_o_thumbnail_width"
		   value="<?php wpl_esc::attr($this->options->thumbnail_width ?? '100'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_thumbnail_height"><?php wpl_esc::html_t('Thumbnail height'); ?></label>
	<input class="text_box" name="option[thumbnail_height]" type="text" id="wpl_o_thumbnail_height"
		   value="<?php wpl_esc::attr($this->options->thumbnail_height ?? '80'); ?>"/>
</div>
<div class="fanc-row">
	<label for="wpl_o_thumbnail_numbers"><?php wpl_esc::html_t('Thumbnail numbers'); ?></label>
	<input class="text_box" name="option[thumbnail_numbers]" type="text" id="wpl_o_thumbnail_numbers"
		   value="<?php wpl_esc::attr($this->options->thumbnail_numbers ?? '20'); ?>"/>
</div>
<div class="fanc-row">
	<label><?php wpl_esc::html_t('Show Tags'); ?></label>
	<select class="text_box" name="option[show_tags]" type="text" id="wpl_o_show_tags">
		<option value="0" <?php if (isset($this->options->show_tags) and $this->options->show_tags == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
		<option value="1" <?php if (isset($this->options->show_tags) and $this->options->show_tags == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
	</select>
</div>