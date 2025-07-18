<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * SEF Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 08/14/2013
 * @package WPL
 */
class wpl_sef
{
    /**
     * This is a system function for processing SEF
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $instance
     * @return mixed
     */
	public static function process($instance)
	{
		/** get global settings **/
		$settings = wpl_global::get_settings();
		$wpl_qs = wpl_global::get_wp_qvar('wpl_qs');
		
		/** get view **/
		$view = self::get_view($wpl_qs, $settings['sef_main_separator']) ?? '';
		if(!trim($view)) $view = 'property_listing';
        
		/** load view **/
		return wpl_global::load($view, '', $instance);
	}
    
    /**
     * Detects WPL view from URL (Query String)
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $query_string
     * @param string $separator
     * @return string
     */
	public static function get_view($query_string = '', $separator = '')
	{
		$view = apply_filters('wpl_sef/get_view', null, $query_string, $separator);
		if(!empty($view)) {
			wpl_request::setVar('wplview', $view);
			return $view;
		}
		/** first validations **/
		if(trim($query_string ?? '') == '') $query_string = wpl_global::get_wp_qvar('wpl_qs');
		if(trim($separator ?? '') == '') $separator = wpl_global::get_setting('sef_main_separator');
        
		if(trim($query_string ?? '') != '')
        {
            $ex = explode($separator, $query_string);
            
            if(trim($ex[0] ?? '') == '') $view = 'property_listing';
            else
            {
                $exp = explode('-', $ex[0]);

                if(is_numeric($exp[0])) $view = 'property_show';
                elseif($ex[0] == 'features') $view = 'features';
                elseif($ex[0] == 'v')
                {
                    if($ex[1] == 'members') $view = 'addon_membership';
                    elseif($ex[1] == 'manager') $view = 'property_manager';
                    elseif($ex[1] == 'booking') $view = 'addon_booking';
                    elseif($ex[1] == 'save-search') $view = 'addon_save_searches';
                    else $view = $ex[1];
                }
                elseif($ex[0] == 'search' and wpl_global::check_addon('save_searches'))
                {
                    /** Import Library **/
                    _wpl_import('libraries.addon_save_searches');
                    $save_searches = new wpl_addon_save_searches();
                    
                    $exp = explode('-', $ex[1]);
                    $search_id = $exp[0];
                    
                    $save_search = $save_searches->get($search_id);
                    $criteria = json_decode($save_search['criteria'] ?? '', true);
                    
                    $view = 'property_listing';
                    foreach($criteria as $key=>$value) wpl_request::setVar($key, $value);
                }
                elseif($ex[0] == 'crm') $view = 'addon_crm';
                elseif(strpos($ex[0], ':') === false) $view = 'profile_show';
                else $view = 'property_listing';
            }
        }
        else
        {
            $view = wpl_request::getVar('wplview', '');
            if(trim($view ?? '') == '') self::set_view();
            
            $view = wpl_request::getVar('wplview', '');
        }
        
        /** Set View **/
        wpl_request::setVar('wplview', $view);
		
		return $view;
	}
	
    /**
     * Sets parameters of a view
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $view
     * @param string $query_string
     */
	public static function setVars($view = 'property_listing', $query_string = '')
	{
		/** first validations **/
		if(trim($query_string ?? '') == '') $query_string = wpl_global::get_wp_qvar('wpl_qs');
		
		$separator = wpl_global::get_setting('sef_main_separator'); /** default value is "/" character **/
		if(trim($separator ?? '') == '') $separator = '/';
		
		$ex = explode($separator, $query_string);
        
		/** set view **/
		wpl_request::setVar('wplview', $view, 'method', false);
		
		if($view == 'property_show')
		{
			$exp = explode('-', $ex[0]);
			$property_id = apply_filters('wpl_service_sef/run/property_show/detect_property', $exp[0], $query_string);
			wpl_request::setVar('pid', $property_id, 'method', false);
			$source = wpl_property::get_property_field('source', $property_id);
			// if it's not realty feed property
			if($source != 'RF') {
				add_filter( 'admin_bar_menu', function() use ($property_id) {
					global $wp_admin_bar;
					$wp_admin_bar->add_menu(array(
						'id' => 'edit-wpl-listing',
						'title' =>wpl_esc::return_t('Edit Listing'),
						'href' => admin_url('admin.php?page=wpl_admin_add_listing&pid=' . $property_id),
					));
				}, 80, 2 );
			}
		}
		elseif($view == 'profile_show')
		{
			$uid = apply_filters('wpl_sef/setVars/profile_show/uid', 0, $ex);
			if(empty($uid)) {
				$uid = wpl_db::select(wpl_db::prepare("SELECT `ID` FROM `#__users` WHERE `user_login` = %s ORDER BY ID LIMIT 1", $ex[0]), 'loadResult');
			}

			
            // Set the User ID
            if($uid)
            {
                wpl_request::setVar('uid', $uid, 'method', false);
                wpl_request::setVar('sf_select_user_id', $uid, 'method', false);
            }
		}
		else
		{
			/** specific fields like country, state, city and ... **/
			$specific_fields = array();
			
			/** set location vars **/
			self::set_location_vars($ex);
			
			foreach($ex as $parameter)
			{
				$types = array();
                $fields = array();
                $values = array();
				$detected = explode(':', $parameter);
				
				if(count($detected) == 1) continue;
				elseif(count($detected) == 2)
				{
					$types[0]  = 'select';
					$fields[0] = $detected[0];
					$values[0] = $detected[1];
					
					$parsed_value = explode('-', $detected[1]);
					
					if(count($parsed_value) == 2)
					{
						$types[0]  = 'tmin';
						$fields[0] = $detected[0];
						$values[0] = $parsed_value[0];
						
						$types[1]  = 'tmax';
						$fields[1] = $detected[0];
						$values[1] = $parsed_value[1];
					}
					elseif(count($parsed_value) == 3)
					{
						$types[0]  = 'min';
						$fields[0] = $detected[0];
						$values[0] = $parsed_value[0];
						
						$types[1]  = 'max';
						$fields[1] = $detected[0];
						$values[1] = $parsed_value[1];
						
						$types[2]  = 'unit';
						$fields[2] = $detected[0];
						$values[2] = $parsed_value[2];
					}
				}
				elseif(count($detected) == 3)
				{
                    $types[0]  = strtolower($detected[0]);
					$fields[0] = $detected[1];
					$values[0] = $detected[2];
				}
				
				$i = 0;
				foreach($types as $type)
				{
					$field = self::parse_field(urldecode($fields[$i]), $specific_fields);
					$value = self::get_id_by_name($field, urldecode($values[$i]));
					
					if(trim($value ?? '') != '') wpl_request::setVar('sf_'.$type.'_'.$field, $value, 'method', false);
					$i++;
				}
			}
		}
	}
	
    /**
     * Returns id of property type or listing type etc
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $field
     * @param string $value
     * @return string
     */
	public static function get_id_by_name($field = '', $value = '')
	{
		/** return if value is numeric for some special fields **/
		if(is_numeric($value)) return $value;
		
		if($field == 'listing')
		{
			$result = wpl_db::select(wpl_db::prepare("SELECT `id` FROM `#__wpl_listing_types` WHERE name = %s", $value), 'loadResult');
			
			return $result ?: '';
		}
		elseif($field == 'property_type')
		{
			$result = wpl_db::select(wpl_db::prepare("SELECT `id` FROM `#__wpl_property_types` WHERE name = %s", $value), 'loadResult');
			
			return $result ?: '';
		}
		else
		{
			return $value;
		}
	}
	
    /**
     * It changes dummy fields to WPL fields for example "Property Type" to "property_type". Also it takes care of specific fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $field
     * @param array $specific_fields
     * @return string
     */
	public static function parse_field($field, $specific_fields = array())
	{
		if(trim($field ?? '') == '') return '';
		
		if(in_array($field, $specific_fields)) return $specific_fields[$field];
		else return strtolower(str_replace(' ', '_', $field));
	}
    
    /**
     * It changes dummy fields to WPL fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $parameters
     */
	public static function set_location_vars($parameters)
	{
		/** specific fields like country, state, city and ... **/
		$location_fields = array();
		$rendered_parameters = array();
		
		/** add location fields to specific fields **/
		$location_settings = wpl_global::get_settings('3'); # location settings
		foreach($location_settings as $location_key=>$location_value)
		{
			if(!strpos($location_key, '_keyword') or trim($location_value ?? '') == '') continue;
			
			$location_id = str_replace('location', '', str_replace('_keyword', '', $location_key));
			
			if($location_id != 'zips') $location_fields['location'.$location_id.'_id'] = self::parse_field($location_value);
			else $location_fields['zip_id'] = self::parse_field($location_value);
		}
        
		foreach($parameters as $parameter)
		{
			$ex = explode(':', $parameter);
			
			if(count($ex) == 2) $rendered_parameters[self::parse_field(urldecode($ex[0]))] = $ex[1];
			elseif(count($ex) == 3) $rendered_parameters[self::parse_field(urldecode($ex[1]))] = $ex[2];
		}
        
		foreach($location_fields as $column=>$location_field)
		{
			if(!isset($rendered_parameters[$location_field])) continue;
			
			$location_id = str_replace('location', '', str_replace('_id', '', $column));
			if($location_id == 'zip') $location_id = 'zips';
			
			$parent = wpl_db::select(wpl_db::prepare("SELECT `id` FROM %i WHERE `name` = %s", "#__wpl_location{$location_id}", urldecode($rendered_parameters[$location_field])), 'loadResult');
			
			wpl_request::setVar('sf_select_'.$column, $parent);
		}
	}
	
    /**
     * Sets WPL view to wplview variable. This function sets other parameters as well in $_REQUEST
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return void
     */
	public static function set_view()
	{
        /** set view **/
        $wplview = wpl_request::getVar('wplview', '');
        if(trim($wplview ?? '') != '') return;
        
		/** checking wordpress post type (post, page, any kind of posts and ...) **/
		if(!is_page() and !is_single()) return;
        
        /** getting the post id and post content **/
        $post_id = wpl_global::get_the_ID();
		$post_content = wpl_db::get('post_content', 'posts', 'id', $post_id) ?? '';
        
        $wplview = '';

        if(strpos($post_content, '[wpl_property_listings') !== false or strpos($post_content, '[WPL') !== false or strpos($post_content, '[et_pb_wpl_property_listing') !== false) $wplview = 'property_listing';
        elseif(strpos($post_content, '[wpl_property_show') !== false or strpos($post_content, '[et_pb_wpl_property_show') !== false) $wplview = 'property_show';
        elseif(strpos($post_content, '[wpl_profile_listing') !== false or strpos($post_content, '[et_pb_wpl_profile_listing') !== false) $wplview = 'profile_listing';
        elseif(strpos($post_content, '[wpl_profile_show') !== false or strpos($post_content, '[et_pb_wpl_profile_show') !== false) $wplview = 'profile_show';
        elseif(strpos($post_content, '[wpl_my_profile') !== false or strpos($post_content, '[et_pb_wpl_profile_wizard') !== false) $wplview = 'profile_wizard';
        elseif(strpos($post_content, '[wpl_add_edit_listing') !== false) $wplview = 'property_wizard';
        elseif(strpos($post_content, '[wpl_listing_manager') !== false) $wplview = 'property_manager';
        elseif(strpos($post_content, '[wpl_payments') !== false) $wplview = 'payments';
        elseif(strpos($post_content, '[wpl_addon_') !== false)
        {
            $pos1 = strpos($post_content, '[wpl_addon_');
            $pos_space = strpos($post_content, ' ', $pos1);
            $pos_close = strpos($post_content, ']', $pos1);

            $pos2 = ($pos_space !== false and ($pos_close and $pos_space < $pos_close)) ? $pos_space : $pos_close;

            $shortcode = trim(substr($post_content, $pos1, ($pos2-$pos1)) ?? '', '[_ ]');
            $shortcode = str_replace('wpl_', '', $shortcode);

            $wplview = $shortcode;
        }
        elseif(strpos($post_content, '[wpl_custom_') !== false) $wplview = 'wpl_custom_view';

        /** set view **/
        if(trim($wplview ?? '') != '') wpl_request::setVar('wplview', $wplview);
        
        $pattern = get_shortcode_regex();
        preg_match('/'.$pattern.'/s', $post_content, $matches);
        
        $wpl_shortcodes = array('WPL', 'wpl_property_listings', 'wpl_property_show', 'wpl_profile_listing', 'wpl_profile_show', 'wpl_my_profile', 'wpl_add_edit_listing', 'wpl_listing_manager');
        if(is_array($matches) and isset($matches[2]) and in_array($matches[2], $wpl_shortcodes))
        {
            $params_str = trim($matches[3] ?? '', ', ');
            
            if(trim($params_str ?? '') != '')
            {
                $attributes = shortcode_parse_atts($params_str);
                foreach($attributes as $key=>$value)
                {
                    if(trim($key ?? '') == '') continue;
                    wpl_request::setVar($key, $value, 'method', false);
                }
            }
        }
	}
    
    /**
     * Returns full link of a wordpress page. It's caring about WordPress permalink structure as well.
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $page_id
     * @return string full link of page
     */
    public static function get_page_link($page_id)
    {
        return get_page_link($page_id);
    }
    
    /**
     * Returns WPL Main rewrite rule
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
    public static function get_main_rewrite_rule()
    {
        $main_permalink = wpl_sef::get_wpl_permalink();
        $neighborhood_main_permalink = wpl_sef::get_neighborhood_wpl_permalink();
        $complex_main_permalink = wpl_sef::get_complex_wpl_permalink();
        $wpl_rules = array();
        
        if(wpl_global::check_multilingual_status())
        {
            $lang_options = wpl_addon_pro::get_wpl_language_options();
            
            $lang_str = '.+';
            foreach($lang_options as $lang_option) $lang_str .= $lang_option['shortcode'].'|';
            $lang_str = trim($lang_str ?? '', '|.+ ');
            
            $wpl_rules[] = array('regex'=>'('.$lang_str.')/('.$main_permalink.')/(.+)$', 'url'=>'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
            $wpl_rules[] = array('regex'=>'language/('.$lang_str.')/('.$main_permalink.')/(.+)$', 'url'=>'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
            if($main_permalink != $neighborhood_main_permalink) {
                $wpl_rules[] = array('regex' => '(' . $lang_str . ')/(' . $neighborhood_main_permalink . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                $wpl_rules[] = array('regex' => 'language/(' . $lang_str . ')/(' . $neighborhood_main_permalink . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
            }

            if($main_permalink != $complex_main_permalink) {
                $wpl_rules[] = array('regex' => '(' . $lang_str . ')/(' . $complex_main_permalink . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                $wpl_rules[] = array('regex' => 'language/(' . $lang_str . ')/(' . $complex_main_permalink . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
            }

            foreach($lang_options as $lang_option)
			{
				$wpl_rules[] = array('regex'=>'('.$lang_option['shortcode'].')/('.wpl_sef::get_post_name((isset($lang_option['main_page']) ? $lang_option['main_page'] : $main_permalink)).')/(.+)$', 'url'=>'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
				$wpl_rules[] = array('regex'=>'language/('.$lang_option['shortcode'].')/('.wpl_sef::get_post_name((isset($lang_option['main_page']) ? $lang_option['main_page'] : $main_permalink)).')/(.+)$', 'url'=>'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                if($main_permalink != $neighborhood_main_permalink) {
                    $wpl_rules[] = array('regex' => '(' . $lang_option['shortcode'] . ')/(' . wpl_sef::get_post_name((isset($lang_option['main_page']) ? $lang_option['main_page'] : $neighborhood_main_permalink)) . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                    $wpl_rules[] = array('regex' => 'language/(' . $lang_option['shortcode'] . ')/(' . wpl_sef::get_post_name((isset($lang_option['main_page']) ? $lang_option['main_page'] : $neighborhood_main_permalink)) . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                }
                if($main_permalink != $complex_main_permalink) {
                    $wpl_rules[] = array('regex' => '(' . $lang_option['shortcode'] . ')/(' . wpl_sef::get_post_name((isset($lang_option['main_page']) ? $lang_option['main_page'] : $complex_main_permalink)) . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                    $wpl_rules[] = array('regex' => 'language/(' . $lang_option['shortcode'] . ')/(' . wpl_sef::get_post_name((isset($lang_option['main_page']) ? $lang_option['main_page'] : $complex_main_permalink)) . ')/(.+)$', 'url' => 'index.php?pagename=$matches[2]&wpl_qs=$matches[3]');
                }
			}
        }
        
        $wpl_rules[] = array('regex'=>'('.$main_permalink.')/(.+)$', 'url'=>'index.php?pagename=$matches[1]&wpl_qs=$matches[2]');
        if($main_permalink != $neighborhood_main_permalink) {
            $wpl_rules[] = array('regex' => '(' . $neighborhood_main_permalink . ')/(.+)$', 'url' => 'index.php?pagename=$matches[1]&wpl_qs=$matches[2]');
        }
        if($main_permalink != $complex_main_permalink) {
            $wpl_rules[] = array('regex' => '(' . $complex_main_permalink . ')/(.+)$', 'url' => 'index.php?pagename=$matches[1]&wpl_qs=$matches[2]');
        }

        // Apply Filters
		@extract(wpl_filters::apply('main_rewrite_rule', array('wpl_rules'=>$wpl_rules)));
        
        return $wpl_rules;
    }
    
    /**
     * Checks WordPress permalink
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return boolean
     */
    public static function is_permalink_default()
    {
        $option = wpl_global::get_wp_option('permalink_structure', NULL);
        if(!trim($option ?? '')) return true;
        else return false;
    }
    
    /**
     * Returns permalink of a post or page
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param integer $post_id
     * @return string
     */
    public static function get_post_name($post_id)
    {
        if(!trim($post_id ?? '')) return '';
        return wpl_db::get('post_name', 'posts', 'id', $post_id, true, '', true);
    }
    
    /**
     * Returns Post ID of a post or page by its permalink
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $post_name
     * @return int
     */
    public static function get_post_id($post_name)
    {
        if(!trim($post_name ?? "")) return 0;
        return wpl_db::get('ID', 'posts', 'post_name', $post_name, true, '', true);
    }
    
    /**
     * Returns current WordPress Post/Page ID
     * @author Howard R <howard@realtyna.com>
     * @static
     * @global object $post
     * @return int
     */
    public static function get_current_post_id()
    {
        global $post;
        return $post->ID; 
    }
    
    /**
     * Returns WPL permalink
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param boolean $full
     * @param int $kind
     * @return string
     */
    public static function get_wpl_permalink($full = false, $kind = 0)
    {
        $main_permalink_id = '';
        if($kind == 1) {
            $main_permalink_id = wpl_global::get_setting('complex_main_permalink');
        } elseif($kind == 4) {
            $main_permalink_id = wpl_global::get_setting('neighborhood_main_permalink');
        }
        if(empty($main_permalink_id)) {
            $main_permalink_id = wpl_global::get_setting('main_permalink');
        }

        $main_permalink = apply_filters('wpl_change_permalink', $main_permalink_id);
        if(!is_numeric($main_permalink)) $main_permalink = wpl_sef::get_post_id($main_permalink);
        
        /** Multilingual **/
        if(wpl_global::check_multilingual_status())
        {
            _wpl_import('libraries.addon_pro');
            $lang_permalink = wpl_addon_pro::get_lang_main_page($kind);
            if($lang_permalink) $main_permalink = $lang_permalink;
        }
        
        if($full)
        {
            $url = wpl_sef::get_page_link($main_permalink);
            
            /** make sure / character is added to the end of URL in case WordPress SEO permalink is enabled **/
            $nosef = wpl_sef::is_permalink_default();
            if(!$nosef) $url = trim($url ?? '', '/').'/';
            
            return $url;
        }
        
        return wpl_sef::get_post_slug($main_permalink);
    }
    public static function get_neighborhood_wpl_permalink($full = false)
    {
        return  static::get_wpl_permalink($full, 4);
    }

    public static function get_complex_wpl_permalink($full = false)
    {
        return  static::get_wpl_permalink($full, 1);
    }

    /**
     * Returns WPL main page ID
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return int
     */
    public static function get_wpl_main_page_id()
    {
        $main_permalink = wpl_global::get_setting('main_permalink');
        if(!is_numeric($main_permalink)) $main_permalink = wpl_sef::get_post_id($main_permalink);
        
        /** Multilingual **/
        if(wpl_global::check_multilingual_status())
        {
            $lang_permalink = wpl_addon_pro::get_lang_main_page();
            if($lang_permalink) $main_permalink = $lang_permalink;
        }
        
        return $main_permalink;
    }
    
    /**
     * Returns post slug by considering if it has parent or not
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $post_id
     * @return string
     */
    public static function get_post_slug($post_id)
    {
        if(!trim($post_id ?? '')) return '';
        
        $slug = wpl_sef::get_post_name($post_id);
        while($post_id = wp_get_post_parent_id($post_id))
        {
            $slug = wpl_sef::get_post_name($post_id).'/'.$slug;
        }
        
        return $slug;
    }
}