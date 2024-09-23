<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$this->property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$this->current_property = $wpl_properties['current'];

/** get image params **/
$this->image_width = isset($params['image_width']) ? $params['image_width'] : 360;
$this->image_height = isset($params['image_height']) ? $params['image_height'] : 285;
$this->image_class = isset($params['image_class']) ? $params['image_class'] : '';
$this->category = ( trim($params['category'] ?? '') != '') ? $params['category'] : '';
$this->autoplay = ( trim($params['autoplay'] ?? '') != '') ? $params['autoplay'] : 1;
$this->lazyload = ( trim($params['lazyload'] ?? '') != '') ? $params['lazyload'] : 0;
$this->resize = ( trim($params['resize'] ?? '') != '') ? $params['resize'] : 1;
$this->rewrite = ( trim($params['rewrite'] ?? '') != '') ? $params['rewrite'] : 0;
$this->imgdesc = ( trim($params['imgdesc'] ?? '') != '') ? $params['imgdesc'] : 0;
$this->watermark = ( trim($params['watermark'] ?? '') != '') ? $params['watermark'] : 1;
$this->thumbnail = ( trim($params['thumbnail'] ?? '') != '') ? $params['thumbnail'] : 1;
$this->thumbnail_width = isset($params['thumbnail_width']) ? $params['thumbnail_width'] : 100;
$this->thumbnail_height = isset($params['thumbnail_height']) ? $params['thumbnail_height'] : 80;
$this->thumbnail_numbers = isset($params['thumbnail_numbers']) ? $params['thumbnail_numbers'] : 20;

/** show tags **/
$show_tags = ( trim($params['show_tags'] ?? '') != '') ? $params['show_tags'] : 0;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();

// Filter images by category
if(trim($this->category ?? '') != '') $raw_gallery = $this->categorize($raw_gallery, $this->category);


$this->gallery = wpl_items::render_gallery($raw_gallery, wpl_property::get_blog_id($this->property_id));

$js[] = (object) array('param1'=>'lightslider.js', 'param2'=>'packages/light_slider/js/lightslider.min.js','param4' => '1');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

$css[] = (object) array('param1'=>'lightslider.css', 'param2'=>'packages/light_slider/css/lightslider.min.css');
foreach($css as $style) wpl_extensions::import_style($style);

/** import js/css codes **/
$this->_wpl_import($this->tpl_path.'.scripts.pshow_modern', true, false);
?>
<div class="wpl-gallery-pshow-wp" id="wpl_gallery_wrapper-<?php wpl_esc::attr($this->activity_id); ?>">

    <ul class="wpl-gallery-pshow" id="wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?>">

        <?php
        if(!count($this->gallery))
        {
			wpl_esc::e('<li class="gallery_no_image"></li>');
        }
        else
        {
            foreach($this->gallery as $image)
            {
                $image_url = $image['url'];
                $image_thumbnail_url = $image['url'];
                $original_image_url = $image['url'];
                $image_title = $image['title'];
                $image_description = $image['description'];

                if(isset($image['raw']['item_extra2']) && !empty($image['raw']['item_extra2'])) $image_alt = $image['raw']['item_extra2'];
                else $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

                if($this->resize and ($this->image_width || $this->image_height) and $image['category'] != 'external')
                {
                    /** set resize method parameters **/
                    $params = array();
                    $params['image_name'] = $image['raw']['item_name'];
                    $params['image_parentid'] = $image['raw']['parent_id'];
                    $params['image_parentkind'] = $image['raw']['parent_kind'];
                    $params['image_source'] = $image['path'];
                    
                    /** resize image if does not exist and add watermark **/
                    if(!$this->image_width) $this->image_width = "auto";
                    if(!$this->image_height) $this->image_height = "auto";
                    
                    $image_url = wpl_images::create_gallery_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
                    $image_thumbnail_url = wpl_images::create_gallery_image($this->thumbnail_width, $this->thumbnail_height, $params, 0, $this->rewrite);
                    
                    /** Watermark original image **/
                    if($this->watermark) $original_image_url = wpl_images::watermark_original_image($params);
                }
				$image_url = apply_filters('wpl_activities/listing_gallery/image_url', $image_url, $image);
				$image_thumbnail_url = apply_filters('wpl_activities/listing_gallery/image_thumbnail_url', $image_thumbnail_url, $image);
                ?>
                <li <?php if($this->imgdesc): ?> data-sub-html="<div><h3><?php wpl_esc::html_t($image_title); ?></h3><p><?php wpl_esc::kses($image_description); ?></p></div>" <?php endif; ?> id="wpl-gallery-img-<?php wpl_esc::attr($image['raw']['id']); ?>" data-thumb="<?php wpl_esc::url($image_thumbnail_url); ?>" data-src="<?php wpl_esc::url($original_image_url); ?>" data-hover-title="<?php wpl_esc::html_t('Click to see gallery'); ?>" style="opacity: 0;position: relative">
                    <span>
                        <?php if($this->resize){
                            if($this->lazyload == 1) {
								wpl_esc::e( '<img data-src="' . wpl_esc::return_url($image_url) . '" ' . $this->itemprop_image . ' width="' . $this->image_width . '" height="' . $this->image_height . '" alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" style="max-height:' . $this->image_height . 'px;opacity:0" />');
                            } else {
								wpl_esc::e( '<img src="' . wpl_esc::return_url($image_url) . '" ' . $this->itemprop_image . ' width="' . $this->image_width . '" height="' . $this->image_height . '" alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" style="max-height:' . $this->image_height . 'px;opacity:0" />');
                            }
                        }else{
                            if($this->lazyload == 1) {
								wpl_esc::e( '<img data-src="' . wpl_esc::return_url($image_url) . '" ' . $this->itemprop_image . ' alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" style="opacity:0" />');
                            } else {
								wpl_esc::e( '<img src="' . wpl_esc::return_url($image_url) . '" ' . $this->itemprop_image . ' alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" style="opacity:0" />');
                            }
                        }
                        ?>
                    </span>
                    <?php if($this->imgdesc): ?>
                    <div class="wpl-gallery-pshow-img-desc">
                        <h3><?php wpl_esc::html($image_title); ?></h3>
                        <p><?php wpl_esc::kses($image_description); ?></p>
                    </div>
                    <?php endif; ?>
                </li>
                <?php
            }
        }
        ?>

    </ul>
	
	<?php if($show_tags): ?>
    <div class="wpl-listing-tags-wp">
        <div class="wpl-listing-tags-cnt">
             <?php wpl_esc::kses($this->tags()); ?>
        </div>
    </div>
	<?php endif; ?>
</div>