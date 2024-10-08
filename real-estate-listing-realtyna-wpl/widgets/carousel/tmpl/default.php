<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.carousel.scripts.js', true, true);

$image_width = $this->instance['data']['image_width'] ?? 310;
$image_height = $this->instance['data']['image_height'] ?? 220;
$slide_interval = $this->instance['data']['slide_interval'] ?? 3000;
$auto_play = $this->instance['data']['auto_play'] ?? false;
$show_nav = $this->instance['data']['show_nav'] ?? false;
$show_tags = $this->instance['data']['show_tags'] ?? false;
$wpl_rtl = is_rtl() ? 'true' : 'false';

$js[] = (object)array('param1' => 'owl.slider', 'param2' => 'packages/owl_slider/owl.carousel.min.js');
foreach ($js as $javascript) wpl_extensions::import_javascript($javascript);

$wpl_properties_count = count($wpl_properties);
$tags = wpl_flex::get_tag_fields(($this->instance['data']['kind'] ?? 0));
?>
	<div id="owl-slider<?php wpl_esc::e($this->widget_id); ?>"
		 class="wpl-plugin-owl owl-carousel wpl-owl-theme-1 wpl-carousel-default <?php if ($wpl_properties_count == 1) wpl_esc::e("wpl-carousel-default-single"); ?> <?php wpl_esc::attr($this->css_class); ?>">
		<?php foreach ($wpl_properties as $key => $gallery): ?>
			<?php
			if (!isset($gallery["items"]["gallery"][0])) continue;

			$params = array();
			$params['image_name'] = $gallery["items"]["gallery"][0]->item_name;
			$params['image_parentid'] = $gallery["items"]["gallery"][0]->parent_id;
			$params['image_parentkind'] = $gallery["items"]["gallery"][0]->parent_kind;
			$params['image_source'] = wpl_global::get_upload_base_path(wpl_property::get_blog_id($params['image_parentid'])) . $params['image_parentid'] . DS . $params['image_name'];

			$image_title = wpl_property::update_property_title($gallery['raw']);
			$image_location = $gallery['location_text'];

			if (isset($gallery['items']['gallery'][0]->item_extra2) and trim($gallery['items']['gallery'][0]->item_extra2 ?? '') != '') $image_title = $gallery['items']['gallery'][0]->item_extra2;

			if ($gallery["items"]["gallery"][0]->item_cat != 'external') $image_url = wpl_images::create_gallery_image($image_width, $image_height, $params, 1);
			else $image_url = $gallery["items"]["gallery"][0]->item_extra3;
			?>
			<div <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence'); ?>>
				<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?>
						href="<?php wpl_esc::url($gallery["property_link"]); ?>">
					<span <?php wpl_esc::item_prop($this->microdata, 'name'); ?> style="display:none"><?php wpl_esc::html($image_title); ?></span>
					<span <?php wpl_esc::item_prop($this->microdata, 'address'); ?> style="display:none"><?php wpl_esc::html($image_location); ?></span>
					<img class="lazyOwl" <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
						 src="<?php wpl_esc::url($image_url); ?>" alt="<?php wpl_esc::attr($image_title); ?>"
						 title="<?php wpl_esc::attr($image_title); ?>" data-src="<?php wpl_esc::url($image_url); ?>"/>
				</a>
				<?php if ($show_tags): ?>
					<div class="wpl-listing-tags-wp">
						<div class="wpl-listing-tags-cnt">
							<?php wpl_esc::e($this->tags($tags, $gallery['raw'])); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php if ($wpl_properties_count > 1): ?>
	<script type="text/javascript">
		wplj(function () {
			wplj("#owl-slider<?php wpl_esc::e($this->widget_id); ?>").owlCarousel(
				{
					items: 1,
					nav: <?php wpl_esc::e($show_nav ? 'true' : 'false'); ?>,
					dots: false,
					navText: false,
					loop: true,
					autoplay: <?php wpl_esc::e($auto_play ? 'true' : 'false'); ?>,
					autoplayTimeout: <?php wpl_esc::e($slide_interval ?: '3000'); ?>,
					responsiveClass: true,
					lazyLoad: true,
					rtl: <?php wpl_esc::e($wpl_rtl); ?>,
					responsive:
						{
							0: {
								items: 1
							},
							480: {
								items: 1
							},
							768: {
								items: 1
							}
						}
				});
		});
	</script>
<?php endif;