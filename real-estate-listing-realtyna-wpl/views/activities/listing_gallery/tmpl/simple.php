<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = $params['wpl_properties'] ?? array();
$this->property_id = $wpl_properties['current']['data']['id'] ?? NULL;
$this->current_property = $wpl_properties['current'];

/** get image params **/
$this->image_width = intval($params['image_width'] ?? 285);
$this->image_height = intval($params['image_height'] ?? 200);
$this->image_class = $params['image_class'] ?? '';
$this->category = ( trim($params['category'] ?? '') != '') ? $params['category'] : '';
$this->resize = ( trim($params['resize'] ?? '') != '') ? $params['resize'] : 1;
$this->rewrite = ( trim($params['rewrite'] ?? '' ) != '') ? $params['rewrite'] : 0;
$this->watermark = ( trim($params['watermark'] ?? '') != '') ? $params['watermark'] : 0;

$this->lazyload = ( trim($params['lazyload'] ?? '') != '') ? $params['lazyload'] : 0;
$lazy_load = $this->lazyload ? 'lazyimg' : '';
$src = $this->lazyload ? 'data-src' : 'src';

/** show tags **/
$show_tags = ( trim($params['show_tags'] ?? '') != '') ? $params['show_tags'] : 1;

/** render gallery **/
$raw_gallery = isset($wpl_properties['current']['items']['gallery']) ? $wpl_properties['current']['items']['gallery'] : array();

// Filter images by category
if(trim($this->category ?? '') != '') $raw_gallery = $this->categorize($raw_gallery, $this->category);

$gallery = wpl_items::render_gallery($raw_gallery, wpl_property::get_blog_id($this->property_id));
?>
<div class="wpl_gallery_container" id="wpl_gallery_container<?php wpl_esc::attr($this->property_id); ?>">
    <?php
    if(!count($gallery))
    {
        wpl_esc::kses('<a class="no_image_box" href="'.wpl_esc::return_url($wpl_properties['current']['property_link']).'"></a>');
    }
    else
    {
        $image_url = $gallery[0]['url'];
        $image_alt = '';
        
        if(isset($gallery[0]['raw']['item_extra2']) && !empty($image['raw']['item_extra2'])) $image_alt = $gallery[0]['raw']['item_extra2'];
        elseif(isset($wpl_properties['current']['raw']['meta_keywords'])) $image_alt = $wpl_properties['current']['raw']['meta_keywords'];

        if($this->resize and $this->image_width and $this->image_height and $gallery[0]['category'] != 'external')
        {
            /** set resize method parameters **/
            $params = array();
            $params['image_name'] = $gallery[0]['raw']['item_name'];
            $params['image_parentid'] = $gallery[0]['raw']['parent_id'];
            $params['image_parentkind'] = $gallery[0]['raw']['parent_kind'];
            $params['image_source'] = $gallery[0]['path'];
            
            /** resize image if does not exist **/
            $image_url = wpl_images::create_gallery_image($this->image_width, $this->image_height, $params, $this->watermark, $this->rewrite);
        }
		$image_url = apply_filters('wpl_activities/listing_gallery/image_thumbnail_url', $image_url, $gallery[0]);
        
        wpl_esc::e('<a class="noHover" href="'.wpl_esc::return_url($wpl_properties['current']['property_link']).'"><img '.$this->itemprop_image.' id="wpl_gallery_image'.$this->property_id .'" '.$src.'="'.wpl_esc::return_url($image_url).'" class="'.wpl_esc::return_attr($lazy_load).' wpl_gallery_image '.wpl_esc::return_attr($this->image_class).'" alt="'.wpl_esc::return_attr($image_alt).'" title="'.wpl_esc::return_attr($image_alt).'" width="'.$this->image_width.'" height="'.$this->image_height.'" style="width: '.$this->image_width.'px; height: '.$this->image_height.'px;" /></a>');
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