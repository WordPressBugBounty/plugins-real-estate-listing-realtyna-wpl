<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

?>
<div class="fanc-row">
    <label for="wpl_contacts"><?php wpl_esc::html_t('Contact'); ?></label>
    <input <?php if(isset($this->options->contacts) and $this->options->contacts == '1') wpl_esc::e('checked="checked"'); ?>
			class="text_box"
			name="option[contacts]"
			type="checkbox"
			id="wpl_contacts"
			value="<?php wpl_esc::attr($this->options->contacts ?? '1'); ?>"
	/>
</div>

<div class="fanc-row">
    <label for="wpl_including_in_listing"><?php wpl_esc::html_t('Including in listing page'); ?></label>
    <input <?php if(isset($this->options->including_in_listing) and $this->options->including_in_listing == '1') wpl_esc::e('checked="checked"'); ?>
			class="text_box"
			name="option[including_in_listing]"
			type="checkbox"
			id="wpl_including_in_listing"
			value="<?php wpl_esc::attr($this->options->including_in_listing ?? '1'); ?>"
	/>
</div>

<div class="fanc-row">
    <label for="wpl_view_parent"><?php wpl_esc::html_t('View parent'); ?></label>
    <input <?php if(isset($this->options->view_parent) and $this->options->view_parent == '1') wpl_esc::e('checked="checked"'); ?>
			class="text_box"
			name="option[view_parent]"
			type="checkbox"
			id="wpl_view_parent"
			value="<?php wpl_esc::attr($this->options->view_parent ?? '1'); ?>" />
</div>

<div class="fanc-row">
    <label for="wpl_visit"><?php wpl_esc::html_t('Visit'); ?></label>
    <input <?php if(isset($this->options->visit) and $this->options->visit == '1') wpl_esc::e('checked="checked"'); ?>
			class="text_box"
			name="option[visit]"
			type="checkbox"
			id="wpl_visit"
			value="<?php wpl_esc::attr($this->options->visit ?? '1'); ?>" />
</div>

