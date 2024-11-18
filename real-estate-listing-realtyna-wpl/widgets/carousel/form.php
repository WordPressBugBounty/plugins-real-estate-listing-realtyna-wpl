<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.carousel.scripts.css_backend', true, true);
include _wpl_import('widgets.carousel.scripts.js_backend', true, true);

$location_settings = wpl_settings::get_settings(3);
?>
<script type="text/javascript">
	function wpl_carousel_toggle<?php wpl_esc::attr($this->widget_id); ?>(element_id) {
		wplj('#' + element_id).toggle();
	}
</script>
<div class="wpl_carousel_widget_backend_form wpl-carousel-widget-<?php wpl_esc::attr($this->widget_id); ?>"
	 id="<?php wpl_esc::attr($this->get_field_id('wpl_carousel_widget_container')); ?>">

	<h4><?php wpl_esc::html_t('Widget Configurations'); ?></h4>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('title')); ?>"><?php wpl_esc::html_t('Title'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('title')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('title')); ?>"
			   placeholder="<?php wpl_esc::html_t('Main Carousel'); ?>"
			   value="<?php wpl_esc::attr($instance['title'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('wpltarget')); ?>"><?php wpl_esc::html_t('Target page'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('wpltarget')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('wpltarget')); ?>">
			<option value="">-----</option>
			<?php wpl_esc::e($this->generate_pages_selectbox($instance)); ?>
		</select>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"><?php wpl_esc::html_t('Layout'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('layout')); ?>" name="<?php wpl_esc::attr($this->get_field_name('layout')); ?>"
				class="wpl-carousel-widget-layout" data-wpl-carousel-id="<?php wpl_esc::attr($this->widget_id); ?>">
			<?php wpl_esc::e($this->generate_layouts_selectbox('carousel', $instance)); ?>
		</select>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>"><?php wpl_esc::html_t('CSS Class'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[css_class]"
			   value="<?php wpl_esc::attr($instance['data']['css_class'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="general"
		 data-wpl-pl-init="default:310|details:1920|multi_images:310|modern:1920|list:90|modern_full_responsive:1920">
		<label for="<?php wpl_esc::attr($this->get_field_id('image_width')); ?>"><?php wpl_esc::html_t('Image Width'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('image_width')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[image_width]" placeholder="310"
			   value="<?php wpl_esc::attr($instance['data']['image_width'] ?? '1920'); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="general"
		 data-wpl-pl-init="default:220|modern:558|list:82|modern_full_responsive:750">
		<label for="<?php wpl_esc::attr($this->get_field_id('image_height')); ?>"><?php wpl_esc::html_t('Image Height'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('image_height')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[image_height]" placeholder="220"
			   value="<?php wpl_esc::attr($instance['data']['image_height'] ?? '558'); ?>"/>
	</div>
	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="modern">
		<label for="<?php wpl_esc::attr($this->get_field_id('tablet_image_height')); ?>"><?php wpl_esc::html_t('Tablet Image Height'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('tablet_image_height')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[tablet_image_height]" placeholder="400"
			   value="<?php wpl_esc::attr($instance['data']['tablet_image_height'] ?? '400'); ?>"/>
	</div>
	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="modern">
		<label for="<?php wpl_esc::attr($this->get_field_id('phone_image_height')); ?>"><?php wpl_esc::html_t('Phone Image Height'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('phone_image_height')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[phone_image_height]" placeholder="310"
			   value="<?php wpl_esc::attr($instance['data']['phone_image_height'] ?? '310'); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="modern" data-wpl-pl-init="modern:150">
		<label for="<?php wpl_esc::attr($this->get_field_id('thumbnail_width')); ?>"><?php wpl_esc::html_t('Thumbnail Width'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('thumbnail_width')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[thumbnail_width]"
			   value="<?php wpl_esc::attr($instance['data']['thumbnail_width'] ?? '150'); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="">
		<label for="<?php wpl_esc::attr($this->get_field_id('thumbnail_height')); ?>"><?php wpl_esc::html_t('Thumbnail Height'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('thumbnail_height')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[thumbnail_height]"
			   value="<?php wpl_esc::attr($instance['data']['thumbnail_height'] ?? '60'); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt"
		 data-wpl-carousel-type="default details multi_images modern modern_full_responsive">
		<label for="<?php wpl_esc::attr($this->get_field_id('slide_interval')); ?>"><?php wpl_esc::html_t('Slide Interval (ms)'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('slide_interval')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[slide_interval]" placeholder="3000"
			   value="<?php wpl_esc::attr($instance['data']['slide_interval'] ?? '3000'); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="default modern_full_responsive">
		<input <?php if (isset($instance['data']['show_nav']) and $instance['data']['show_nav']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_show_nav')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[show_nav]"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('show_nav')); ?>"><?php wpl_esc::html_t('Show Navigation'); ?></label>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="modern_full_responsive">
		<input <?php if (isset($instance['data']['hide_pagination']) and $instance['data']['hide_pagination']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_hide_pagination')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[hide_pagination]"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('hide_pagination')); ?>"><?php wpl_esc::html_t('Hide Pagination'); ?></label>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="modern_full_responsive">
		<input <?php if (isset($instance['data']['hide_caption']) and $instance['data']['hide_caption']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_hide_caption')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[hide_caption]"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('hide_caption')); ?>"><?php wpl_esc::html_t('Hide Caption'); ?></label>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt"
		 data-wpl-carousel-type="default details multi_images modern modern_full_responsive">
		<input <?php if (isset($instance['data']['auto_play']) and $instance['data']['auto_play']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_auto_play')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[auto_play]"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('auto_play')); ?>"><?php wpl_esc::html_t('Auto Play'); ?></label>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="modern">
		<input <?php if (isset($instance['data']['smart_resize']) and $instance['data']['smart_resize']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_smart_resize')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[smart_resize]"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('smart_resize')); ?>"><?php wpl_esc::html_t('Smart Resize'); ?></label>
	</div>

	<div class="wpl-widget-row">
		<input <?php if (isset($instance['data']['show_tags']) and $instance['data']['show_tags']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_show_tags')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[show_tags]"/>
		<label><?php wpl_esc::html_t('Show Tags'); ?></label>
	</div>
	<div class="wpl-widget-row">
		<input <?php if (isset($instance['data']['lazy_load']) and $instance['data']['lazy_load']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_lazy_load')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[lazy_load]"/>
		<label><?php wpl_esc::html_t('lazy Load'); ?></label>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt" data-wpl-carousel-type="multi_images">
		<label for="<?php wpl_esc::attr($this->get_field_id('images_per_page')); ?>"><?php wpl_esc::html_t('Images per Page'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('images_per_page')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[images_per_page]" placeholder="3"
			   value="<?php wpl_esc::attr($instance['data']['images_per_page'] ?? '3'); ?>"/>
	</div>

	<div class="wpl-widget-row wpl-carousel-opt">
		<label for="<?php wpl_esc::attr($this->get_field_id('slide_fillmode')); ?>"><?php wpl_esc::html_t('Fill Mode'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('slide_fillmode')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[slide_fillmode]">
			<option value="0" <?php wpl_esc::e((isset($instance['data']['slide_fillmode']) and $instance['data']['slide_fillmode'] == 0) ? 'selected="selected"' : ''); ?>>
				<?php wpl_esc::html_t('Stretch'); ?>
			</option>
			<option value="1" <?php wpl_esc::e((isset($instance['data']['slide_fillmode']) and $instance['data']['slide_fillmode'] == 1) ? 'selected="selected"' : ''); ?>>
				<?php wpl_esc::html_t('Contain'); ?>
			</option>
			<option value="2" <?php wpl_esc::e((isset($instance['data']['slide_fillmode']) and $instance['data']['slide_fillmode'] == 2) ? 'selected="selected"' : ''); ?>>
				<?php wpl_esc::html_t('Cover'); ?>
			</option>
			<option value="4" <?php wpl_esc::e((isset($instance['data']['slide_fillmode']) and $instance['data']['slide_fillmode'] == 4) ? 'selected="selected"' : ''); ?>>
				<?php wpl_esc::html_t('Actual Size'); ?>
			</option>
			<option value="5" <?php wpl_esc::e((isset($instance['data']['slide_fillmode']) and $instance['data']['slide_fillmode'] == 5) ? 'selected="selected"' : ''); ?>>
				<?php wpl_esc::html_t('Contain/Actual'); ?>
			</option>
		</select>
	</div>

	<h4><?php wpl_esc::html_t('Filter Properties'); ?></h4>
	<?php if (wpl_settings::is_mls_on_the_fly()): ?>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('on_the_fly')); ?>"><?php wpl_esc::html_t('Fly Query'); ?></label>
			<input type="text" id="<?php wpl_esc::attr($this->get_field_id('on_the_fly')); ?>"
				   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[on_the_fly]"
				   value="<?php wpl_esc::attr($instance['data']['on_the_fly'] ?? ''); ?>"/>
		</div>
	<?php endif; ?>
	<div class="wpl-widget-row">
		<?php $kinds = wpl_flex::get_kinds('wpl_properties'); ?>
		<label for="<?php wpl_esc::attr($this->get_field_id('data_kind')); ?>"><?php wpl_esc::html_t('Kind'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('data_kind')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[kind]">
			<?php foreach ($kinds as $kind): ?>
				<option <?php if (isset($instance['data']['kind']) and $instance['data']['kind'] == $kind['id']) wpl_esc::e('selected="selected"'); ?>
						value="<?php wpl_esc::attr($kind['id']); ?>"><?php wpl_esc::html_t($kind['name']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<?php $listings = wpl_global::get_listings(); ?>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_listing')); ?>"><?php wpl_esc::html_t('Listing'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('data_listing')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[listing]">
			<option value="-1"><?php wpl_esc::html_t('All'); ?></option>
			<?php foreach ($listings as $listing): ?>
				<option <?php if (isset($instance['data']['listing']) and $instance['data']['listing'] == $listing['id']) wpl_esc::e('selected="selected"'); ?>
						value="<?php wpl_esc::attr($listing['id']); ?>"><?php wpl_esc::html_t($listing['name']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<?php $property_types = wpl_global::get_property_types(); ?>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_property_type')); ?>"><?php wpl_esc::html_t('Property type'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('data_property_type')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[property_type]">
			<option value="-1"><?php wpl_esc::html_t('All'); ?></option>
			<?php foreach ($property_types as $property_type): ?>
				<option <?php if (isset($instance['data']['property_type']) and $instance['data']['property_type'] == $property_type['id']) wpl_esc::e('selected="selected"'); ?>
						value="<?php wpl_esc::attr($property_type['id']); ?>"><?php wpl_esc::html_t($property_type['name']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<?php for ($i = 2; $i < 8; $i++): if (!trim($location_settings['location' . $i . '_keyword'] ?? '')) continue; ?>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_location') . $i . '_name'); ?>">
				<?php wpl_esc::html_t($location_settings['location' . $i . '_keyword']); ?>
			</label>
			<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_location') . $i . '_name'); ?>"
				   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[location<?php wpl_esc::attr($i); ?>_name]"
				   value="<?php wpl_esc::attr($instance['data']['location' . $i . '_name'] ?? ''); ?>"/>
		</div>
	<?php endfor; ?>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_zip_name')); ?>"><?php wpl_esc::html_t('Zip-Code'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_zip_name')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[zip_name]"
			   value="<?php wpl_esc::attr($instance['data']['zip_name'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_build_year')); ?>"><?php wpl_esc::html_t('Year Built Range'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_build_year')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[build_year]"
			   value="<?php wpl_esc::attr($instance['data']['build_year'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_living_area')); ?>"><?php wpl_esc::html_t('SqFt Range'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_living_area')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[living_area]"
			   value="<?php wpl_esc::attr($instance['data']['living_area'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_price')); ?>"><?php wpl_esc::html_t('Price Range'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_price')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[price]"
			   value="<?php wpl_esc::attr($instance['data']['price'] ?? ''); ?>"/>
	</div>

	<?php if (wpl_global::check_addon('complex')): ?>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_parent')); ?>"><?php wpl_esc::html_t('Parent'); ?></label>
			<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_parent')); ?>"
				   title="<?php wpl_esc::html_t('Listing ID of parent property'); ?>"
				   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[parent]"
				   value="<?php wpl_esc::attr($instance['data']['parent'] ?? ''); ?>"/>
		</div>
		<div class="wpl-widget-row">
			<input <?php if (isset($instance['data']['auto_parent']) and $instance['data']['auto_parent']) wpl_esc::e('checked="checked"'); ?>
					value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_auto_parent')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[auto_parent]"/>
			<label for="<?php wpl_esc::attr($this->get_field_id('data_auto_parent')); ?>"
				   title="<?php wpl_esc::html_t('For single property pages.'); ?>"><?php wpl_esc::html_t('Detect parent automatically.'); ?></label>
		</div>
	<?php endif; ?>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_listing_ids')); ?>"><?php wpl_esc::html_t('Listing IDs (Comma Separated)'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_listing_ids')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[listing_ids]"
			   value="<?php wpl_esc::attr($instance['data']['listing_ids'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_agent_ids')); ?>"><?php wpl_esc::html_t('Agent IDs (Comma Separated)'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_agent_ids')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[agent_ids]"
			   value="<?php wpl_esc::attr($instance['data']['agent_ids'] ?? ''); ?>"/>
	</div>

	<div class="wpl-widget-row">
		<input <?php if (isset($instance['data']['random']) and $instance['data']['random']) wpl_esc::e('checked="checked"'); ?>
				value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_random')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[random]"
				onclick="random_clicked<?php wpl_esc::attr($this->widget_id); ?>();"/>
		<label for="<?php wpl_esc::attr($this->get_field_id('data_random')); ?>"><?php wpl_esc::html_t('Random'); ?></label>
	</div>

	<?php
	$tags = wpl_flex::get_fields(NULL, NULL, NULL, NULL, NULL, "AND `type`='tag' AND `enabled`>='1' GROUP BY `table_column`");
	foreach ($tags as $tag) {
		$tagkey = 'only_' . ltrim($tag->table_column ?? '', 'sp_');
		?>
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_') . $tagkey); ?>"
				   class="<?php wpl_esc::attr($this->get_field_id('data_tags_label')); ?>">
				<?php wpl_esc::html_t('Only ' . $tag->name); ?>
			</label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_') . $tagkey); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[<?php wpl_esc::attr($tagkey); ?>]">
				<option value="0" <?php if (isset($instance['data'][$tagkey]) and $instance['data'][$tagkey] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
				<option value="1" <?php if (isset($instance['data'][$tagkey]) and $instance['data'][$tagkey] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
			</select>
		</div>
	<?php } ?>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('tag_group_join_type_or')); ?>">
			<input <?php if (isset($instance['data']['tag_group_join_type']) and
				$instance['data']['tag_group_join_type'] == 'or') wpl_esc::e('checked="checked"'); ?>
					value="or" type="radio"
									id="<?php wpl_esc::attr($this->get_field_id('tag_group_join_type_or')); ?>"
									name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[tag_group_join_type]"
									onclick=""/>
			<?php wpl_esc::html_t('Or'); ?>
		</label>

		<label for="<?php wpl_esc::attr($this->get_field_id('tag_group_join_type_and')); ?>">
			<input <?php if (!isset($instance['data']['tag_group_join_type']) or $instance['data']['tag_group_join_type'] == 'and') wpl_esc::e('checked="checked"'); ?>
					value="and" type="radio"
									id="<?php wpl_esc::attr($this->get_field_id('tag_group_join_type_and')); ?>"
									name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[tag_group_join_type]"
									onclick=""/>
			<?php wpl_esc::html_t('And'); ?>
		</label>
	</div>
	<?php do_action('wpl_view/widgets/carousel/form/filter', $instance, $this); ?>

	<h4><?php wpl_esc::html_t('Similar Properties'); ?></h4>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_only_similars')); ?>"><?php wpl_esc::html_t('Only Similars'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_only_similars')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_only_similars]"
				onchange="wpl_carousel_toggle<?php wpl_esc::attr($this->widget_id); ?>('<?php wpl_esc::attr($this->get_field_id('data_sml_fields_container')); ?>');">
			<option value="0" <?php if (isset($instance['data']['sml_only_similars']) and $instance['data']['sml_only_similars'] == 0) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('No'); ?>
			</option>
			<option value="1" <?php if (isset($instance['data']['sml_only_similars']) and $instance['data']['sml_only_similars'] == 1) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Yes'); ?>
			</option>
		</select>
	</div>
	<div id="<?php wpl_esc::attr($this->get_field_id('data_sml_fields_container')); ?>"
		 style="<?php wpl_esc::e((!isset($instance['data']['sml_only_similars']) or !$instance['data']['sml_only_similars']) ? 'display: none;' : ''); ?>">
		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_listing')); ?>"><?php wpl_esc::html_t('Include Listings'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_listing')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_inc_listing]">
				<option value="1" <?php if (isset($instance['data']['sml_inc_listing']) and $instance['data']['sml_inc_listing'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
				<option value="0" <?php if (isset($instance['data']['sml_inc_listing']) and $instance['data']['sml_inc_listing'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
			</select>
		</div>

		<div class="wpl-widget-row">
			<input onchange="wpl_carousel_toggle<?php wpl_esc::attr($this->widget_id); ?>('<?php wpl_esc::attr($this->get_field_id('data_sml_override_listing_container')); ?>')" <?php if (isset($instance['data']['sml_override_listing']) and $instance['data']['sml_override_listing']) wpl_esc::e('checked="checked"'); ?>
				   value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('sml_override_listing')); ?>"
				   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_override_listing]"/>
			<label for="<?php wpl_esc::attr($this->get_field_id('sml_override_listing')); ?>"><?php wpl_esc::html_t('Override Listing Type'); ?></label>
		</div>
		<div id="<?php wpl_esc::attr($this->get_field_id('data_sml_override_listing_container')); ?>"
			 style="<?php wpl_esc::attr((!isset($instance['data']['sml_override_listing']) or !$instance['data']['sml_override_listing']) ? 'display: none;' : ''); ?>">
			<div class="wpl-widget-row">
				<label for="<?php wpl_esc::attr($this->get_field_id('sml_override_listing_new')); ?>"><?php wpl_esc::html_t('Show'); ?></label>
				<select id="<?php wpl_esc::attr($this->get_field_id('sml_override_listing_new')); ?>"
						name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_override_listing_new]">
					<?php foreach ($listings as $listing): ?>
						<option <?php if (isset($instance['data']['sml_override_listing_new']) and $instance['data']['sml_override_listing_new'] == $listing['id']) wpl_esc::e('selected="selected"'); ?>
								value="<?php wpl_esc::attr($listing['id']); ?>"><?php wpl_esc::html_t($listing['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="wpl-widget-row">
				<label for="<?php wpl_esc::attr($this->get_field_id('sml_override_listing_old')); ?>"><?php wpl_esc::html_t('when current listing type is'); ?></label>
				<select id="<?php wpl_esc::attr($this->get_field_id('sml_override_listing_old')); ?>"
						name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_override_listing_old]">
					<?php foreach ($listings as $listing): ?>
						<option <?php if (isset($instance['data']['sml_override_listing_old']) and $instance['data']['sml_override_listing_old'] == $listing['id']) wpl_esc::e('selected="selected"'); ?>
								value="<?php wpl_esc::attr($listing['id']); ?>"><?php wpl_esc::html_t($listing['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_property_type')); ?>"><?php wpl_esc::html_t('Include Property Type'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_property_type')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_inc_property_type]">
				<option value="1" <?php if (isset($instance['data']['sml_inc_property_type']) and $instance['data']['sml_inc_property_type'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?></option>
				<option value="0" <?php if (isset($instance['data']['sml_inc_property_type']) and $instance['data']['sml_inc_property_type'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?></option>
			</select>
		</div>

		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_agent')); ?>"><?php wpl_esc::html_t('Include Agent'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_agent')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_inc_agent]">
				<option value="0" <?php if (isset($instance['data']['sml_inc_agent']) and $instance['data']['sml_inc_agent'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
				<option value="1" <?php if (isset($instance['data']['sml_inc_agent']) and $instance['data']['sml_inc_agent'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
			</select>
		</div>

		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_price')); ?>"><?php wpl_esc::html_t('Include Price'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_price')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_inc_price]"
					onchange="wpl_carousel_toggle<?php wpl_esc::attr($this->widget_id); ?>('<?php wpl_esc::attr($this->get_field_id('data_sml_price_container')); ?>');">
				<option value="1" <?php if (isset($instance['data']['sml_inc_price']) and $instance['data']['sml_inc_price'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
				<option value="0" <?php if (isset($instance['data']['sml_inc_price']) and $instance['data']['sml_inc_price'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
			</select>
		</div>

		<div id="<?php wpl_esc::attr($this->get_field_id('data_sml_price_container')); ?>"
			 style="<?php wpl_esc::attr((!isset($instance['data']['sml_inc_price']) or !$instance['data']['sml_inc_price']) ? 'display: none;' : ''); ?>">
			<div class="wpl-widget-row">
				<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_price_down_rate')); ?>"><?php wpl_esc::html_t('Price Down Rate'); ?></label>
				<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_sml_price_down_rate')); ?>"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_price_down_rate]"
					   value="<?php wpl_esc::attr($instance['data']['sml_price_down_rate'] ?? '0.8'); ?>"/>
			</div>

			<div class="wpl-widget-row">
				<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_price_up_rate')); ?>"><?php wpl_esc::html_t('Price Up Rate'); ?></label>
				<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_sml_price_up_rate')); ?>"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_price_up_rate]"
					   value="<?php wpl_esc::attr($instance['data']['sml_price_up_rate'] ?? '1.2'); ?>"/>
			</div>
		</div>

		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_radius')); ?>"><?php wpl_esc::html_t('Include Radius'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_inc_radius')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_inc_radius]"
					onchange="wpl_carousel_toggle<?php wpl_esc::attr($this->widget_id); ?>('<?php wpl_esc::attr($this->get_field_id('data_sml_radius_container')); ?>');">
				<option value="0" <?php if (isset($instance['data']['sml_inc_radius']) and $instance['data']['sml_inc_radius'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
				<option value="1" <?php if (isset($instance['data']['sml_inc_radius']) and $instance['data']['sml_inc_radius'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
			</select>
		</div>

		<div id="<?php wpl_esc::attr($this->get_field_id('data_sml_radius_container')); ?>"
			 style="<?php wpl_esc::e((!isset($instance['data']['sml_inc_radius']) or !$instance['data']['sml_inc_radius']) ? 'display: none;' : ''); ?>">
			<div class="wpl-widget-row">
				<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_radius')); ?>"><?php wpl_esc::html_t('Radius'); ?></label>
				<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_sml_radius')); ?>"
					   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_radius]"
					   value="<?php wpl_esc::attr($instance['data']['sml_radius'] ?? '2000'); ?>"/>
			</div>

			<?php $units = wpl_units::get_units(1); ?>
			<div class="wpl-widget-row">
				<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_radius_unit')); ?>"><?php wpl_esc::html_t('Radius Unit'); ?></label>
				<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_radius_unit')); ?>"
						name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[sml_radius_unit]">
					<?php foreach ($units as $unit): ?>
						<option value="<?php wpl_esc::attr($unit['id']); ?>" <?php if (isset($instance['data']['sml_radius_unit']) and $instance['data']['sml_radius_unit'] == $unit['id']) wpl_esc::e('selected="selected"'); ?>>
							<?php wpl_esc::html_t($unit['name']); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="wpl-widget-row">
			<label for="<?php wpl_esc::attr($this->get_field_id('data_sml_zip_code')); ?>"><?php wpl_esc::html_t('Include Zip-Code'); ?></label>
			<select id="<?php wpl_esc::attr($this->get_field_id('data_sml_zip_code')); ?>"
					name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[data_sml_zip_code]">
				<option value="0" <?php if (isset($instance['data']['data_sml_zip_code']) and $instance['data']['data_sml_zip_code'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('No'); ?>
				</option>
				<option value="1" <?php if (isset($instance['data']['data_sml_zip_code']) and $instance['data']['data_sml_zip_code'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Yes'); ?>
				</option>
			</select>
		</div>
		<?php do_action('wpl_view/widgets/carousel/form/similar', $instance, $this); ?>
	</div>

	<h4><?php wpl_esc::html_t('Sort and Limit'); ?></h4>
	<?php $sort_options = wpl_sort_options::render(wpl_sort_options::get_sort_options(0)); ?>
	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_orderby')); ?>"><?php wpl_esc::html_t('Order by'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('data_orderby')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[orderby]">
			<?php foreach ($sort_options as $sort_option): ?>
				<option <?php if (isset($instance['data']['orderby']) and urlencode($sort_option['field_name']) == $instance['data']['orderby']) wpl_esc::e('selected="selected"'); ?>
						value="<?php wpl_esc::attr(urlencode($sort_option['field_name'])); ?>"><?php wpl_esc::html($sort_option['name']); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_order')); ?>"><?php wpl_esc::html_t('Order'); ?></label>
		<select id="<?php wpl_esc::attr($this->get_field_id('data_order')); ?>"
				name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[order]">
			<option <?php if (isset($instance['data']['order']) and $instance['data']['order'] == 'ASC') wpl_esc::e('selected="selected"'); ?>
					value="ASC"><?php wpl_esc::html_t('ASC'); ?></option>
			<option <?php if (isset($instance['data']['order']) and $instance['data']['order'] == 'DESC') wpl_esc::e('selected="selected"'); ?>
					value="DESC"><?php wpl_esc::html_t('DESC'); ?></option>
		</select>
	</div>

	<div class="wpl-widget-row">
		<label for="<?php wpl_esc::attr($this->get_field_id('data_limit')); ?>"><?php wpl_esc::html_t('Number of properties'); ?></label>
		<input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_limit')); ?>"
			   name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[limit]"
			   value="<?php wpl_esc::attr($instance['data']['limit'] ?? ''); ?>"/>
	</div>
	<?php do_action('wpl_view/widgets/carousel/form', $instance, $this); ?>
	<!-- Create a button to show Short-code of this widget -->
	<?php if (wpl_global::check_addon('pro') and !wpl_global::is_page_builder()): ?>
		<button id="<?php wpl_esc::attr($this->get_field_id('btn-shortcode')); ?>"
				data-item-id="<?php wpl_esc::attr($this->number); ?>"
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
</div>