<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** import js codes **/
$this->_wpl_import('widgets.carousel.scripts.js', true, true);

$image_width = $this->instance['data']['image_width'] ?? 1920;
$image_height = $this->instance['data']['image_height'] ?? 558;
$tablet_image_height = $this->instance['data']['tablet_image_height'] ?? 400;
$phone_image_height = $this->instance['data']['phone_image_height'] ?? 310;
$thumbnail_width = $this->instance['data']['thumbnail_width'] ?? 150;
$thumbnail_height = $this->instance['data']['thumbnail_height'] ?? 60;
$auto_play = $this->instance['data']['auto_play'] ?? false;
$smart_resize = $this->instance['data']['smart_resize'] ?? false;
$slide_interval = intval($this->instance['data']['slide_interval'] ?? 3000);
$show_nav = $this->instance['data']['show_nav'] ?? false;
$hide_pagination = $this->instance['data']['hide_pagination'] ?? false;
$hide_caption = $this->instance['data']['hide_caption'] ?? false;
$show_tags = $this->instance['data']['show_tags'] ?? false;
$lazy_load = $this->instance['data']['lazy_load'] ?? false;

/** add Layout js **/
$js[] = (object)array('param1' => 'modern.slider', 'param2' => 'js/libraries/wpl.modern.slider.min.js');
foreach ($js as $javascript) wpl_extensions::import_javascript($javascript);

$large_images = $thumbnail = NULL;
$tags = wpl_flex::get_tag_fields(($this->instance['data']['kind'] ?? 0));
?>
<div class="wpl_carousel_container <?php wpl_esc::attr($this->css_class); ?>">
	<div id="wpl-modern-<?php wpl_esc::attr($this->widget_id); ?>" class="ei-slider">
		<ul class="ei-slider-large">
			<?php
			$thumbnails = [];
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

				if ($gallery["items"]["gallery"][0]->item_cat != 'external') {
					$image_url = wpl_images::create_gallery_image($image_width, $image_height, $params, 1);
					$thumbnail_url = wpl_images::create_gallery_image($thumbnail_width, $thumbnail_height, $params);
				} else {
					$image_url = $gallery["items"]["gallery"][0]->item_extra3;
					$thumbnail_url = $gallery["items"]["gallery"][0]->item_extra3;
				}
				$thumbnails[] = ['image_title' => $image_title, 'image_alt' => $image_alt, 'thumbnail_url' => $thumbnail_url];

				// Location visibility
				$location_visibility = wpl_property::location_visibility($gallery['items']['gallery'][0]->parent_id, $gallery['items']['gallery'][0]->parent_kind, wpl_users::get_user_membership());

				?>
				<li <?php wpl_esc::item_type($this->microdata, 'SingleFamilyResidence'); ?>>
					<img <?php wpl_esc::item_prop($this->microdata, 'image'); ?>
							src="<?php wpl_esc::url($image_url) ?>"
							alt="<?php wpl_esc::attr($image_alt) ?>"
							title="<?php wpl_esc::attr($image_title) ?>"
							style="width: <?php wpl_esc::e($image_width) ?>px; height: <?php wpl_esc::e($image_height) ?>px;"
					/>
					<?php if ($show_tags): ?>
						<div class="wpl-listing-tags-wp">
							<div class="wpl-listing-tags-cnt">
								<?php wpl_esc::e($this->tags($tags, $gallery['raw'])); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (!$hide_caption): ?>
						<div class="ei-title">
							<h2 <?php wpl_esc::item_prop($this->microdata, 'name'); ?>><?php wpl_esc::html($image_title); ?></h2>
							<h3 <?php wpl_esc::item_prop($this->microdata, 'address'); ?>>
								<?php wpl_esc::html(trim($gallery['materials']['living_area']['value'] ?? '') != '' ? $gallery['materials']['living_area']['value'] . ' - ' : ''); ?>
								<?php wpl_esc::html($location_visibility === true ? $gallery['location_text'] : $location_visibility); ?>
							</h3>
							<a <?php wpl_esc::item_prop($this->microdata, 'url'); ?> class="more_info"
																					 href="<?php wpl_esc::url($gallery["property_link"]); ?>">
								<?php wpl_esc::html_t('More info'); ?>
							</a>
						</div>
					<?php endif; ?>
				</li>
				<?php
			}
			?>
		</ul>
		<div class="ei-slider-navigation">
			<div class="ei-slider-next"></div>
			<div class="ei-slider-prev"></div>
		</div>
		<ul class="ei-slider-thumbs <?php if ($hide_pagination) wpl_esc::e('wpl-util-hidden'); ?>">
			<li class="ei-slider-element"><?php wpl_esc::html_t('Current'); ?></li>
			<?php foreach ($thumbnails as $thumbnail): ?>
				<li>
					<a href="#"><?php wpl_esc::html($thumbnail['image_title']) ?></a>
					<img
							src="<?php wpl_esc::url($thumbnail['thumbnail_url']) ?>"
							alt="<?php wpl_esc::attr($image_alt) ?>"
							title="<?php wpl_esc::attr($image_title) ?>"
							width="<?php wpl_esc::attr($thumbnail_width) ?>"
							height="<?php wpl_esc::attr($thumbnail_height) ?>"
							style="width: <?php wpl_esc::attr($thumbnail_width) ?>px; height: <?php wpl_esc::attr($thumbnail_height) ?>px;"
					/>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<script type="text/javascript">
	wplj(function () {
		wplj('#wpl-modern-<?php wpl_esc::e($this->widget_id); ?>').modernSlider(
			{
				animation: 'center',
				autoplay: <?php wpl_esc::e($auto_play ? 'true' : 'false'); ?>,
				slideshow_interval: <?php wpl_esc::e($slide_interval); ?>,
				titlesFactor: 0,
				thumbMaxWidth: <?php wpl_esc::e($thumbnail_width); ?>,
				smartResize: <?php wpl_esc::e($smart_resize ? 'true' : 'false'); ?>
			});

	});
</script>
<style>
    #wpl-modern-<?php wpl_esc::e($this->widget_id); ?> {
        height: <?php wpl_esc::e($image_height); ?>px;
    }

    @media (min-width: 481px) and (max-width: 1024px) {
        #wpl-modern-<?php wpl_esc::e($this->widget_id); ?> {
            max-height: <?php wpl_esc::e($tablet_image_height); ?>px;
        }
    }

    @media (max-width: 480px) {
        #wpl-modern-<?php wpl_esc::e($this->widget_id); ?> {
            max-height: <?php wpl_esc::e($phone_image_height); ?>px;
        }
    }

	<?php if(!$lazy_load): ?>
    .ei-slider-loading {
        display: none !important;
    }

	<?php endif; ?>
</style>