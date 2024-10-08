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
$this->resize = ( trim($params['resize'] ?? '') != '') ? $params['resize'] : 1;
$this->rewrite = ( trim($params['rewrite'] ?? '') != '') ? $params['rewrite'] : 0;
$this->watermark = ( trim($params['watermark'] ?? '') != '') ? $params['watermark'] : 1;
$this->lazyload = ( trim($params['lazyload'] ?? '') != '') ? $params['lazyload'] : 0;

/** show tags **/
$show_tags = ( trim($params['show_tags'] ?? '') != '') ? $params['show_tags'] : 0;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();

// Filter images by category
if(trim($this->category ?? '') != '') $raw_gallery = $this->categorize($raw_gallery, $this->category);

$this->gallery = wpl_items::render_gallery($raw_gallery, wpl_property::get_blog_id($this->property_id));

$js[] = (object) array('param1'=>'jquery.bxslider', 'param2'=> 'js/libraries/wpl.jquery.bxslider.min.js');
foreach($js as $javascript) wpl_extensions::import_javascript($javascript, true);

/** import js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.pshow', true, true);
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?>" style="max-height: <?php wpl_esc::attr($this->image_height); ?>px;width: <?php wpl_esc::attr($this->image_width); ?>px">
    <?php
    if(!count($this->gallery))
    {
		wpl_esc::e('<div class="gallery_no_image"></div>');
    }
    else
    {
		wpl_esc::e('<ul class="bxslider" id="bxslider_'.$this->property_id.'_'.$this->activity_id.'">');
        $i = 0;
        $pager_box = '';

        foreach($this->gallery as $image)
        {
            $image_url = $image['url'];
            $image_thumbnail_url = $image['url'];
            
            if(isset($image['raw']['item_extra2']) && !empty($image['raw']['item_extra2'])) $image_alt = $image['raw']['item_extra2'];
            else $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

            if($this->resize and $this->image_width and $this->image_height and $image['category'] != 'external')
            {
                /** set resize method parameters **/
                $params                     = array();
                $params['image_name']       = $image['raw']['item_name'];
                $params['image_parentid']   = $image['raw']['parent_id'];
                $params['image_parentkind'] = $image['raw']['parent_kind'];
                $params['image_source']     = $image['path'];
                
                /** resize image if does not exist and add watermark **/
                $image_url           = wpl_images::create_gallery_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
                $image_thumbnail_url = wpl_images::create_gallery_image(100, 80, $params, 0, $this->rewrite);
            }
			$image_url = apply_filters('wpl_activities/listing_gallery/image_url', $image_url, $image);
			$image_thumbnail_url = apply_filters('wpl_activities/listing_gallery/image_thumbnail_url', $image_thumbnail_url, $image);

            /** start loading images **/
            if($this->lazyload == 1)
            {
				wpl_esc::e('<li><img src="" data-src="' . wpl_esc::return_url($image_url) . '" ' . $this->itemprop_image . ' class="lazyimg wpl_gallery_image ' . wpl_esc::return_attr($this->image_class) . '" id="wpl_gallery_image' . $image['raw']['id'] . '" width="' . $this->image_width . '" height="' . $this->image_height . '" alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" style="max-height:' . $this->image_height . 'px" /></li>');
                $pager_box .= '<a data-slide-index="' . $i . '" href=""><img class="lazyimg" data-src="' . wpl_esc::return_url($image_thumbnail_url) . '" width="100" height="80" style="width: 100px; height: 80px;" itemprop="image" alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" /></a>';
                $i++;
            }
            else
            {
				wpl_esc::e('<li><img src="' . wpl_esc::return_url($image_url) . '" ' . $this->itemprop_image . ' class="wpl_gallery_image ' . wpl_esc::return_attr($this->image_class) . '" id="wpl_gallery_image' . $image['raw']['id'] . '" width="' . $this->image_width . '" height="' . $this->image_height . '" alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" style="max-height:' . $this->image_height . 'px" /></li>');
                $pager_box .= '<a data-slide-index="' . $i . '" href=""><img src="' . wpl_esc::return_url($image_thumbnail_url) . '" width="100" height="80" style="width: 100px; height: 80px;" itemprop="image" alt="' . wpl_esc::return_attr($image_alt) . '" title="' . wpl_esc::return_attr($image_alt) . '" /></a>';
                $i++;
            }
        }

		wpl_esc::e('</ul>');
    ?>
    <div class="wpl-slider-bx-pager-wp" id="bx-pager-<?php wpl_esc::attr($this->activity_id); ?>">
        <?php wpl_esc::e('<div class="wpl-slider-bx-img-count" id="img-count-'.$this->activity_id.'">'.count($this->gallery).'</div>'.$pager_box); ?>
    </div>
    <?php 
	}
	
	if($show_tags):
	?>
	
    <div class="wpl-listing-tags-wp">
        <div class="wpl-listing-tags-cnt">
            <?php wpl_esc::e($this->tags()); ?>
        </div>
    </div>
	<?php endif; ?>
    
</div>