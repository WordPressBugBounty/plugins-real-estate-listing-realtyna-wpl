<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.images');

/** activity class **/
class wpl_activity_main_listing_gallery extends wpl_activity
{
    public $tpl_path = 'views.activities.listing_gallery.tmpl';
	
	public function start($layout, $params)
	{
        /** Settings **/
        $this->settings = wpl_settings::get_settings();

        /** Microdata **/
        $this->microdata = $this->settings['microdata'] ?? 0;
        $this->itemprop_image = ($this->microdata) ? 'itemprop="image"' : '';

        /** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
    
    public function tags()
	{
        $kind = $this->current_property['data']['kind'];
        $tags_str = '';
        
        static $loaded_tags = array();
        
        // Load the Tags only once
        if(isset($loaded_tags[$kind]) and is_array($loaded_tags[$kind])) $tags = $loaded_tags[$kind];
        else
		{
			$tags = wpl_flex::get_tag_fields($kind);
			$loaded_tags[$kind] = $tags;
		}

		$cur_membership_id = wpl_users::get_user_membership();
        foreach($tags as $tag)
        {
            if(empty($this->current_property['raw'][$tag->table_column])) continue;

			if(trim($tag->accesses ?? "") != '')
			{
				$accesses = explode(',', trim($tag->accesses, ', '));
				if(!in_array($cur_membership_id, $accesses)) {
					continue;
				}
			}
            
            $options = json_decode($tag->options ?? '', true);
            if(!$options['ribbon']) continue;

			$tags_str_html = '<div class="wpl-listing-tag '.$tag->table_column.'">'.wpl_esc::return_html_t($tag->name).'</div>';
            $tags_str .= apply_filters('wpl_activity_main_listing_gallery/tags/loop/str_html', $tags_str_html, $tag, $this->current_property['data'], $this);
        }

		$tags_str = apply_filters('wpl_activity_main_listing_gallery/tags/after', $tags_str, $this->current_property['data']);
        $this->tags_styles($tags);
        
        return $tags_str;
	}
    
    public function tags_styles($tags = NULL)
    {
        static $loaded = array();
        
        if(isset($loaded[$this->activity_id])) return;
        if(!isset($loaded[$this->activity_id])) $loaded[$this->activity_id] = true;
        
        if(is_null($tags))
        {
            $kind = $this->current_property['data']['kind'];
            $tags = wpl_flex::get_tag_fields($kind);
        }
        
        /** Initialize WPL color library **/
        $color = new wpl_color();
        
        $styles_str = '';
        foreach($tags as $tag)
        {
            $options = json_decode($tag->options ?? '', true);
            if(!$options['ribbon']) continue;
            
            $darken = $color->convert(trim($options['color'] ?? '', '# '), 130, true);
            $styles_str .= '.wpl-listing-tag.'.$tag->table_column.'{background-color: #'.trim($options['color'] ?? '', '# ').'; color: #'.trim($options['text_color'] ?? '', '# ').'} .wpl-listing-tag.'.$tag->table_column.'::after{border-color: #'.$darken.' transparent transparent #'.$darken.';}';
        }
        
        $wplhtml = wpl_html::getInstance();
        $wplhtml->set_footer('<style type="text/css">'.$styles_str.'</style>');
    }
    
    public function categorize($images, $category)
    {
        // First validation
        if(trim($category ?? '') == '') return $images;
        
        $categorized = array();
        foreach($images as $image)
        {
            /** force to array **/
			$image = (array) $image;
            
            if($image['item_cat'] == $category) $categorized[] = $image;
        }
        
        return $categorized;
    }
}