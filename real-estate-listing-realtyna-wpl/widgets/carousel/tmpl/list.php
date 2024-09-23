<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.carousel.scripts.js', true, true);

$image_width = $this->instance['data']['image_width'] ?? 90;
$image_height = $this->instance['data']['image_height'] ?? 82;
$show_tags = $this->instance['data']['show_tags'] ?? false;
?>
<div class="wpl_carousel_container <?php wpl_esc::attr($this->css_class); ?>">
	<ul class="simple_list">
		<?php
		$tags = wpl_flex::get_tag_fields((isset($this->instance['data']['kind']) ? $this->instance['data']['kind'] : 0));

		foreach ($wpl_properties as $key => $gallery) {
			if (!isset($gallery['items']['gallery'][0])) continue;

			$params = array();
			$params['image_name'] = $gallery['items']['gallery'][0]->item_name;
			$params['image_parentid'] = $gallery['items']['gallery'][0]->parent_id;
			$params['image_parentkind'] = $gallery['items']['gallery'][0]->parent_kind;
			$params['image_source'] = wpl_global::get_upload_base_path(wpl_property::get_blog_id($params['image_parentid'])) . $params['image_parentid'] . DS . $params['image_name'];

			$image_title = wpl_property::update_property_title($gallery['raw']);

			if (trim($gallery['items']['gallery'][0]->item_extra2 ?? '') != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
			elseif (trim($gallery['raw']['meta_keywords'] ?? '')) $image_alt = $gallery['raw']['meta_keywords'];
			else $image_alt = $image_title;

			$image_description = $gallery["items"]["gallery"][0]->item_extra2;

			if ($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
			else $image_url = $gallery["items"]["gallery"][0]->item_extra3;

			// Location visibility
			$location_visibility = wpl_property::location_visibility($gallery['items']['gallery'][0]->parent_id, $gallery['items']['gallery'][0]->parent_kind, wpl_users::get_user_membership());
			?>
			<li <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence'); ?>>
				<div class="left_section">
					<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?>
							href="<?php wpl_esc::url($gallery["property_link"]); ?>">
                        <span style="width:<?php wpl_esc::e($image_width) ?>px;height:<?php wpl_esc::e($image_height) ?>px;">
                            <img <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
								 src="<?php wpl_esc::url($image_url) ?>"
								 title="<?php wpl_esc::attr($image_title) ?>"
								 alt="<?php wpl_esc::attr($image_alt) ?>"
								 width="<?php wpl_esc::e($image_width) ?>"
								 height="<?php wpl_esc::e($image_height) ?>"
								 style="width: <?php wpl_esc::e($image_width) ?>px; height: <?php wpl_esc::e($image_height) ?>px;"
							/>
                        </span>
					</a>
				</div>
				<div class="right_section">
					<div class="title" <?php wpl_esc::item_prop($this->microdata, 'name'); ?>>
						<a class="more_info_title"
						   href="<?php wpl_esc::url($gallery["property_link"]); ?>"><?php wpl_esc::html($image_title) ?></a>
					</div>
					<div class="location" <?php wpl_esc::item_prop($this->microdata, 'address'); ?>>
						<?php wpl_esc::html($location_visibility === true ? $gallery["location_text"] : $location_visibility); ?>
					</div>
					<?php if (isset($gallery['materials']['price'])): ?>
						<div class="price"
							 content="<?php wpl_esc::attr($gallery['materials']['price']['value']); ?>"><?php wpl_esc::html($gallery['materials']['price']['value']); ?></div>
					<?php endif; ?>
				</div>
				<a class="more_info"
				   href="<?php wpl_esc::url($gallery["property_link"]) ?>"><?php wpl_esc::html_t('More Info') ?></a>
				<?php if (!empty($show_tags)): ?>
					<div class="wpl-listing-tags-wp">
						<div class="wpl-listing-tags-cnt">
							<?php wpl_esc::e($this->tags($tags, $gallery['raw'])); ?>
						</div>
					</div>
				<?php endif; ?>
			</li>
			<?php
		}
		?>
	</ul>
</div>