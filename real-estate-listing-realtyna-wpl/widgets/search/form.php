<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.search.scripts.css_backend', true, true);
include _wpl_import('widgets.search.scripts.js_backend', true, true);

wpl_extensions::import_javascript((object)array('param1' => 'wpl-sly-scrollbar', 'param2' => 'js/libraries/wpl.slyscrollbar.min.js'));
?>
<div class="wpl-widget-search-wp wpl-widget-form-wp"
	 id="<?php wpl_esc::attr($this->get_field_id('wpl_search_widget_container')); ?>">

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('title')); ?>"><?php wpl_esc::html_t('Title'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('title')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('title')); ?>"
			   value="<?php wpl_esc::attr($instance['title']); ?>"
			   onblur="wplSearchWidget<?php wpl_esc::attr($this->number) ?>.saveChange(this);"/>
	</div>

	<div class="wpl-widget-row">
		<?php $kinds = wpl_flex::get_kinds(''); ?>
		<label for="<?php wpl_esc::attr($this->get_field_id('kind')); ?>"><?php wpl_esc::html_t('Kind'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('kind')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('kind')); ?>"
				onchange="wplSearchWidget<?php wpl_esc::attr($this->number) ?>.saveAndReload(this);">
			<?php foreach ($kinds as $kind): if (trim($kind['addon_name']) and !wpl_global::check_addon($kind['addon_name'])) continue; ?>
				<option <?php if (isset($instance['kind']) and $instance['kind'] == $kind['id']) wpl_esc::e('selected="selected"'); ?>
						value="<?php wpl_esc::attr($kind['id']); ?>"><?php wpl_esc::html_t($kind['name']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"><?php wpl_esc::html_t('Layout'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('layout')); ?>"
				onchange="wplSearchWidget<?php wpl_esc::attr($this->number) ?>.saveChange(this);">
			<?php wpl_esc::e($this->generate_layouts_selectbox('search', $instance)); ?>
		</select>
	</div>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('style')); ?>"><?php wpl_esc::html_t('Style'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('style')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('style')); ?>">
			<option value="wpl-search-default" <?php if (isset($instance['style']) and $instance['style'] == "wpl-search-default") wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Default'); ?>
			</option>
			<option value="wpl-search-sidebar" <?php if (isset($instance['style']) and $instance['style'] == "wpl-search-sidebar") wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Sidebar'); ?>
			</option>
			<option value="wpl-search-float" <?php if (isset($instance['style']) and $instance['style'] == "wpl-search-float") wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Float'); ?>
			</option>
		</select>
	</div>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('wpltarget')); ?>"><?php wpl_esc::html_t('Target page'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('wpltarget')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('wpltarget')); ?>">
			<option value="">-----</option>
			<?php wpl_esc::e($this->generate_pages_selectbox($instance)); ?>
			<option value="-1" <?php wpl_esc::e((isset($instance['wpltarget']) and $instance['wpltarget'] == '-1') ? 'selected="selected"' : ''); ?>>
				<?php wpl_esc::html_t('Self Page'); ?>
			</option>
		</select>
	</div>

	<?php if (wpl_global::check_addon('aps')): ?>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('ajax')); ?>"><?php wpl_esc::html_t('AJAX Search'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('ajax')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('ajax')); ?>">
				<option value="0" <?php if (isset($instance['ajax']) and $instance['ajax'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
				<option value="1" <?php if (isset($instance['ajax']) and $instance['ajax'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
				<option value="2" <?php if (isset($instance['ajax']) and $instance['ajax'] == 2) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes (On the fly)'); ?>
				</option>
			</select>
		</div>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('total_results')); ?>"><?php wpl_esc::html_t('Show Total Results'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('total_results')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('total_results')); ?>">
				<option value="0" <?php if (isset($instance['total_results']) and $instance['total_results'] == "0") wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Hide'); ?>
				</option>
				<option value="1" <?php if (isset($instance['total_results']) and $instance['total_results'] == "1") wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Show (Inside of Search Button)'); ?>
				</option>
				<option value="2" <?php if (isset($instance['total_results']) and $instance['total_results'] == "2") wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Show (Next to Search Button)'); ?>
				</option>
			</select>
		</div>
	<?php endif; ?>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('css_class')); ?>"><?php wpl_esc::html_t('CSS Class'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('css_class')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('css_class')); ?>"
			   value="<?php wpl_esc::attr($instance['css_class'] ?? ''); ?>"/>
	</div>

	<?php if (wpl_global::check_addon('aps')): ?>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('more_options_type')); ?>"><?php wpl_esc::html_t('More Options Type'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('more_options_type')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('more_options_type')); ?>">
				<option value="0" <?php if (isset($instance['more_options_type']) and $instance['more_options_type'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Slide'); ?>
				</option>
				<option value="1" <?php if (isset($instance['more_options_type']) and $instance['more_options_type'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Pop-up'); ?>
				</option>
			</select>
		</div>
	<?php endif; ?>

	<div class="wpl-widget-row">
		<input <?php if (isset($instance['show_reset_button']) and $instance['show_reset_button']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('show_reset_button')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('show_reset_button')); ?>"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('show_reset_button')); ?>"><?php wpl_esc::html_t('Show Reset Button'); ?></label>
	</div>

	<?php if (wpl_global::check_addon('membership') and ($this->kind == 0 or $this->kind == 1)): ?>
		<?php if (wpl_global::check_addon('save_searches')): ?>
			<div class="wpl-widget-row">
				<input <?php if (isset($instance['show_saved_searches']) and $instance['show_saved_searches']) wpl_esc::e('checked="checked"'); ?>
						value="1" type="checkbox"
						id="<?php wpl_esc::attr($this->get_field_id('show_saved_searches')); ?>"
						name="<?php wpl_esc::attr($this->get_field_name('show_saved_searches')); ?>"/>
				<label for="<?php wpl_esc::attr($this->get_field_id('show_saved_searches')); ?>"><?php wpl_esc::html_t('Show Saved Searches'); ?></label>
			</div>
		<?php endif; ?>
		<div class="wpl-widget-row">
			<input <?php if (isset($instance['show_favorites']) and $instance['show_favorites']) wpl_esc::e('checked="checked"'); ?>
					value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('show_favorites')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('show_favorites')); ?>"/>
			<label for="<?php wpl_esc::attr($this->get_field_id('show_favorites')); ?>"><?php wpl_esc::html_t('Show Favorites'); ?></label>
		</div>
	<?php endif; ?>

	<button id="btn-search-<?php wpl_esc::attr($this->number) ?>"
			data-is-init="false"
			data-item-id="<?php wpl_esc::attr($this->number) ?>"
			data-fancy-id="wpl_view_fields_<?php wpl_esc::attr($this->number) ?>"
			class="wpl-btn-search-view-fields wpl-button button-1"
			href="#wpl_view_fields_<?php wpl_esc::attr($this->number) ?>"
			type="button"><?php wpl_esc::html_t('View Fields'); ?></button>

	<?php if (wpl_global::check_addon('pro') and !wpl_global::is_page_builder()): ?>
		<button id="<?php wpl_esc::attr($this->get_field_id('btn-shortcode')); ?>"
				data-item-id="<?php wpl_esc::attr($this->number) ?>"
				data-fancy-id="<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>"
				class="wpl-open-lightbox-btn wpl-button button-1"
				href="#<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>"
				type="button"><?php wpl_esc::html_t('View Shortcode'); ?></button>

		<div id="<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>" class="hidden">
			<div class="fanc-content size-width-1">
				<h2><?php wpl_esc::html_t('View Shortcode'); ?></h2>
				<div class="fanc-body fancy-search-body">
					<p class="wpl_widget_shortcode_preview">[wpl_widget_instance id="<?php wpl_esc::attr($this->id); ?>"]</p>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<span id="wpl-js-page-must-reload-<?php wpl_esc::attr($this->number) ?>"
		  class="wpl-widget-search-must-reload"><?php wpl_esc::html_t('The page needs to be reloaded before opening the field editor.'); ?></span>
</div>

<div id="wpl_view_fields_<?php wpl_esc::attr($this->number) ?>" class="hidden">
	<div class="fanc-content" id="wpl_flex_modify_container_<?php wpl_esc::attr($this->number) ?>">
		<h2><?php wpl_esc::html_t('Search Fields Configurations'); ?></h2>
		<div class="fanc-body fancy-search-body wpl-widget-search-fields-wp">
			<div class="search-fields-wp">
				<div class="search-tabs-wp">
					<?php $this->generate_backend_categories_tabs($instance['data']); ?>
				</div>
				<div class="search-tab-content">
					<?php $this->generate_backend_categories($instance['data']); ?>
				</div>
			</div>
			<div id="fields-order" class="order-list-wp">
				<h4>
                    <span>
                        <?php wpl_esc::html_t('Fields Order'); ?>
                    </span>
				</h4>

				<div class="order-list-body">
					<ul>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>