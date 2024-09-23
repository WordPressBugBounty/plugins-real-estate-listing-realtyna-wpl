<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.carousel.scripts.js', true, true);

/** add Layout js **/
$js[] = (object)array('param1' => 'owl.slider', 'param2' => 'packages/owl_slider/owl.carousel.min.js');
foreach ($js as $javascript) wpl_extensions::import_javascript($javascript);

$description_column = 'field_308';
if (wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) {
	$description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);
}

$image_height = $this->instance['data']['image_height'] ?? 700;
$images_per_page = $this->instance['data']['images_per_page'] ?? 1;
$image_width = $this->instance['data']['image_width'] ?? 1200;
$auto_play = $this->instance['data']['auto_play'] ?? false;
$slide_interval = $this->instance['data']['slide_interval'] ?? 3000;
$show_tags = $this->instance['data']['show_tags'] ?? false;
$wpl_rtl = is_rtl() ? 'true' : 'false';

$wpl_properties_count = count($wpl_properties);
$tags = wpl_flex::get_tag_fields(($this->instance['data']['kind'] ?? 0));
?>
	<div id="wpl-details_view-<?php wpl_esc::e($this->widget_id); ?>"
		 class="wpl_carousel_container <?php wpl_esc::attr($this->css_class); ?>">
		<ul class="details_view wpl-plugin-owl <?php if ($wpl_properties_count == 1) wpl_esc::e("wpl-carousel-details-single"); ?>">
			<?php foreach ($wpl_properties as $key => $gallery): ?>
				<?php
				if (!isset($gallery['items']['gallery'][0])) continue;

				$params = array();
				$params['image_name'] = $gallery['items']['gallery'][0]->item_name;
				$params['image_parentid'] = $gallery['items']['gallery'][0]->parent_id;
				$params['image_parentkind'] = $gallery['items']['gallery'][0]->parent_kind;
				$params['image_source'] = wpl_global::get_upload_base_path(wpl_property::get_blog_id($params['image_parentid'])) . $params['image_parentid'] . DS . $params['image_name'];

				$image_title = wpl_property::update_property_title($gallery['raw']);

				$description = stripslashes(strip_tags($gallery['raw'][$description_column] ?? ''));
				$cut_position = (trim($description) ? strrpos(substr($description, 0, 130), '.', -1) : 0);
				if (!$cut_position) $cut_position = 129;

				$image_description = $gallery["items"]["gallery"][0]->item_extra2;

				if ($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params);
				else $image_url = $gallery["items"]["gallery"][0]->item_extra3;

				// Location visibility
				$location_visibility = wpl_property::location_visibility($gallery['items']['gallery'][0]->parent_id, $gallery['items']['gallery'][0]->parent_kind, wpl_users::get_user_membership());
				?>
				<li <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence'); ?>
						style="background-image:url('<?php wpl_esc::url($image_url); ?>')">
					<div class="owl-item-prp_container">
						<div class="owl-item-prp_top">
							<div class="left_section">
								<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?>
										href="<?php wpl_esc::url($gallery["property_link"]); ?>">
									<span style="background:url('<?php wpl_esc::url($image_url); ?>') no-repeat center/cover"></span>
								</a>
							</div>
							<div class="right_section">
								<div class="title" <?php wpl_esc::item_prop($this->microdata, 'name'); ?>>
									<a class="more_info_title" href="<?php wpl_esc::url($gallery["property_link"]); ?>">
										<?php wpl_esc::html($image_title); ?>
									</a>
								</div>
								<div class="location" <?php wpl_esc::item_prop($this->microdata, 'address'); ?>>
									<?php wpl_esc::html($location_visibility === true ? $gallery["location_text"] : $location_visibility); ?>
								</div>
								<div class="description" <?php wpl_esc::item_prop($this->microdata, 'description'); ?>>
									<?php wpl_esc::html(substr($description, 0, $cut_position + 1)); ?>
								</div>
								<?php if (isset($gallery['materials']['price'])): ?>
									<div class="price"
										 content="<?php wpl_esc::attr($gallery['materials']['price']['value']); ?>"><?php wpl_esc::html($gallery['materials']['price']['value']); ?></div>
								<?php endif; ?>
							</div>
						</div>
						<div class="owl-item-prp_bottom">
							<div class="wpl_icon_box_wrap">
								<div class="wpl_icon_box">
									<?php if (isset($gallery['materials']['bedrooms'])): ?>
										<div class="bedrooms"
											 content="<?php wpl_esc::attr($gallery['materials']['bedrooms']['value']); ?>"><?php wpl_esc::html($gallery['materials']['bedrooms']['value']); ?></div>
									<?php endif; ?>
									<?php if (isset($gallery['materials']['bathrooms'])): ?>
										<div class="bathrooms"
											 content="<?php wpl_esc::attr($gallery['materials']['bathrooms']['value']); ?>"><?php wpl_esc::html($gallery['materials']['bathrooms']['value']); ?></div>
									<?php endif; ?>
									<?php if (isset($gallery['materials']['living_area'])): ?>
										<div class="living_area"
											 content="<?php wpl_esc::attr($gallery['materials']['living_area']['value']); ?>"><?php wpl_esc::html($gallery['materials']['living_area']['value']); ?></div>
									<?php endif; ?>
								</div>
							</div>
							<a class="more_info"
							   href="<?php wpl_esc::url($gallery["property_link"]); ?>"><?php wpl_esc::html_t('Details'); ?></a>
						</div>
						<?php if ($show_tags): ?>
							<div class="wpl-listing-tags-wp">
								<div class="wpl-listing-tags-cnt">
									<?php wpl_esc::e($this->tags($tags, $gallery['raw'])) ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

<?php if ($wpl_properties_count > 1): ?>
	<script type="text/javascript">
		wplj(function () {
			wplj("#wpl-details_view-<?php wpl_esc::e($this->widget_id); ?> .details_view").owlCarousel({
				items: 1,
				loop: true,
				nav: true,
				autoplay: <?php wpl_esc::e($auto_play ? 'true' : 'false'); ?>,
				autoplayTimeout: <?php wpl_esc::e(intval($slide_interval ?: '3000')); ?>,
				autoplayHoverPause: true,
				navText: false,
				dots: false,
				animateOut: 'fadeOut',
				animateIn: 'fadeIn',
				smartSpeed: 450,
				rtl: <?php wpl_esc::e($wpl_rtl); ?>,
				responsiveClass: true,
				responsive: {
					0: {
						items: 1,
						nav: false,
						dots: true
					},
					768: {
						items: 1
					}
				}
			});
		});
	</script>
<?php endif; ?>