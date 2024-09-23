<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_googlemap_widget_backend_form wpl-widget-form-wp"
	 id="<?php wpl_esc::attr($this->get_field_id('wpl_google_map_widget_container')); ?>">

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('title')); ?>"><?php wpl_esc::html_t('Title'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('title')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('title')); ?>"
			   value="<?php wpl_esc::attr($instance['title']); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"><?php wpl_esc::html_t('Layout'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('layout')); ?>" class="widefat">
			<?php wpl_esc::e($this->generate_layouts_selectbox('googlemap', $instance)); ?>
		</select>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>"><?php wpl_esc::html_t('CSS Class'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[css_class]"
			   value="<?php wpl_esc::attr($instance['data']['css_class'] ?? ''); ?>"/>
	</div>

	<!-- Create a button to show Short-code of this widget -->
	<?php if (wpl_global::check_addon('pro') and !wpl_global::is_page_builder()): ?>
		<button id="<?php wpl_esc::attr($this->get_field_id('btn-shortcode')); ?>"
				data-item-id="<?php wpl_esc::attr($this->number); ?>"
				data-realtyna-lightbox-opts="clearContent:false"
				data-fancy-id="<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>"
				class="wpl-open-lightbox-btn wpl-button button-1"
				href="#<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>" data-realtyna-lightbox
				type="button"><?php wpl_esc::html_t('View Shortcode'); ?></button>

		<div id="<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>" class="hidden">
			<div class="fanc-content size-width-1">
				<h2><?php wpl_esc::html_t('View Shortcode'); ?></h2>
				<div class="fanc-body fancy-search-body">
					<p class="wpl_widget_shortcode_preview">[wpl_widget_instance id="<?php wpl_esc::attr($this->id); ?>
						"]</p>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>