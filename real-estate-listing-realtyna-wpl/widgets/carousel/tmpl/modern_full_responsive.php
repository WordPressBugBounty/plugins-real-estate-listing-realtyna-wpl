<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$image_width = $this->instance['data']['image_width'] ?? 1920;
$image_height = $this->instance['data']['image_height'] ?? 558;
$tablet_image_height = $this->instance['data']['tablet_image_height'] ?? 400;
$phone_image_height = $this->instance['data']['phone_image_height'] ?? 310;
$thumbnail_width = $this->instance['data']['thumbnail_width'] ?? 150;
$thumbnail_height = $this->instance['data']['thumbnail_height'] ?? 60;
$this->auto_play = $this->instance['data']['auto_play'] ?? false;
$smart_resize = $this->instance['data']['smart_resize'] ?? false;
$this->slide_interval = intval($this->instance['data']['slide_interval'] ?? 3000);
$show_nav = $this->instance['data']['show_nav'] ?? false;
$hide_pagination = $this->instance['data']['hide_pagination'] ?? false;
$hide_caption = $this->instance['data']['hide_caption'] ?? false;
$this->lazy_load = $this->instance['data']['lazy_load'] ?? false;
$show_tags = $this->instance['data']['show_tags'] ?? false;

/** add Layout js **/
$js[] = (object)array('param1' => 'responsive-slider.js', 'param2' => 'packages/responsive_slider/js/responsive-slider.min.js');
foreach ($js as $javascript) wpl_extensions::import_javascript($javascript);

$css[] = (object)array('param1' => 'responsive-slider.css', 'param2' => 'packages/responsive_slider/css/responsive-slider.css');
foreach ($css as $style) wpl_extensions::import_style($style);

$large_images = $thumbnail = NULL;
$tags = wpl_flex::get_tag_fields(($this->instance['data']['kind'] ?? 0));
$pager = 0;
$this->pager_numbers = count($wpl_properties);
$pager_width = (100 / $this->pager_numbers) . '%';
?>
	<div class="wpl_carousel_container <?php wpl_esc::attr($this->css_class); ?>">
		<div id="wpl-modern-<?php wpl_esc::attr($this->widget_id); ?>"
			 class="responsive-slider <?php wpl_esc::attr($this->lazy_load ? 'loading' : ''); ?>"
			 style="height: <?php wpl_esc::e($image_height . 'px'); ?>">
			<div class="slides" data-group="slides" style="display:none">
				<ul>
					<?php
					$pagination_list = [];
					foreach ($wpl_properties as $key => $gallery) {
						if (!isset($gallery['items']['gallery'][0])) continue;

						$params = array();
						$params['image_name'] = $gallery["items"]["gallery"][0]->item_name;
						$params['image_parentid'] = $gallery["items"]["gallery"][0]->parent_id;
						$params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
						$params['image_source'] = wpl_global::get_upload_base_path(wpl_property::get_blog_id($params['image_parentid'])) . $params['image_parentid'] . DS . $params['image_name'];
						$pager = $pager + 1;
						$pagination_list[] = $pager;

						$image_title = wpl_property::update_property_title($gallery['raw']);

						if (trim($gallery['items']['gallery'][0]->item_extra2 ?? '') != '') $image_alt = $gallery['items']['gallery'][0]->item_extra2;
						elseif (trim($gallery['raw']['meta_keywords'] ?? '')) $image_alt = $gallery['raw']['meta_keywords'];
						else $image_alt = $image_title;

						if ($gallery["items"]["gallery"][0]->item_cat != 'external') {
							$image_url = wpl_images::create_gallery_image($image_width, $image_height, $params, 1);
							$thumbnail_url = wpl_images::create_gallery_image($thumbnail_width, $thumbnail_height, $params, 1);
						} else {
							$image_url = $gallery["items"]["gallery"][0]->item_extra3;
							$thumbnail_url = $gallery["items"]["gallery"][0]->item_extra3;
						}
						// Location visibility
						$location_visibility = wpl_property::location_visibility($gallery['items']['gallery'][0]->parent_id, $gallery['items']['gallery'][0]->parent_kind, wpl_users::get_user_membership());
						?>
						<li <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence'); ?>>
							<div class="slide-body" data-group="slide">
								<img <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
										src="<?php wpl_esc::url($image_url) ?>"
										alt="<?php wpl_esc::attr($image_alt) ?>"
										alt="<?php wpl_esc::attr($image_title) ?>"
										style="width: <?php wpl_esc::e($image_width) ?>px; height: <?php wpl_esc::e($image_height) ?>px"
								/>
								<?php if ($show_tags): ?>
									<div class="wpl-listing-tags-wp">
										<div class="wpl-listing-tags-cnt">
											<?php wpl_esc::e($this->tags($tags, $gallery['raw'])); ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if (!$hide_caption): ?>
									<div class="caption header" data-animate="slideAppearRightToLeft" data-delay="500"
										 data-length="300">
										<h2 <?php wpl_esc::item_prop($this->microdata, 'name'); ?>><?php wpl_esc::attr($image_title) ?></h2>
										<h3 <?php wpl_esc::item_prop($this->microdata, 'address'); ?>
												class="caption sub sub1" data-animate="slideAppearLeftToRight"
												data-delay="800" data-length="500">
											<?php wpl_esc::html(trim($gallery['materials']['living_area']['value'] ?? '') != '' ? $gallery['materials']['living_area']['value'] . ' - ' : ''); ?>
											<?php wpl_esc::html($location_visibility === true ? $gallery['location_text'] : $location_visibility); ?>
										</h3>
										<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?>
												href="<?php wpl_esc::url($gallery["property_link"]); ?>"
												class="btn-primary caption sub sub2 more_info"
												data-animate="slideAppearRightToLeft" data-delay="1000"
												data-length="500">
											<?php wpl_esc::html_t('More info'); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</li>
					<?php } ?>
				</ul>

				<?php if ($show_nav): ?>
					<a class="slider-control left" href="#" data-jump="prev"></a>
					<a class="slider-control right" href="#" data-jump="next"></a>
				<?php endif; ?>

				<?php if (!$hide_pagination): ?>
					<div class="pages-cnt">
						<div class="pages">
							<?php foreach ($pagination_list as $pagination): ?>
								<a class="page" data-jump-to="<?php wpl_esc::e($pagination) ?>"
								   style="width:<?php wpl_esc::e($pager_width); ?>"><?php wpl_esc::e($pagination) ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php $this->_wpl_import('widgets.carousel.scripts.js_responsive', true, true); ?>