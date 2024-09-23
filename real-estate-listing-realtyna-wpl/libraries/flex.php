<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Flex Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 05/01/2013
 * @package WPL
 */
#[AllowDynamicProperties]
class wpl_flex
{
	public static $category_listing_specific_array = array();
	public static $category_property_type_specific_array = array();
	public static $wizard_js_validation = array();
    public static $category_user_specific_array = array();
    
    /**
     * Used for caching in get_field_by_column function
     * @static
     * @var array
     */
    public static $fields_by_column = array();
	
    /**
     * Returns dbst fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $category
     * @param int $enabled
     * @param int $kind
     * @param string $key
     * @param mixed $value
     * @param string $condition
     * @return array of objects
     */
	public static function get_fields($category = NULL, $enabled = 0, $kind = 0, $key = '', $value = '', $condition = '')
	{
		if(!$condition)
		{
			$condition = '';
			
			if(trim($category ?? '') != '') $condition .= wpl_db::prepare(" AND `category` = %s", $category);
			if(trim($enabled ?? '') != '') $condition .= wpl_db::prepare(" AND `enabled` >= %d", $enabled);
			if(trim($kind ?? '') != '') $condition .= wpl_db::prepare(" AND `kind` = %d", $kind);
			
			if(trim($key ?? '') != '') $condition .= wpl_db::prepare(" AND %i >= %s", $key, $value);
		}
		
		return wpl_db::select("SELECT * FROM `#__wpl_dbst` WHERE 1 $condition ORDER BY `index` ASC", 'loadObjectList');
	}
	
    /**
     * Get dbst field data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $field_id
     * @return object
     */
	public static function get_field($field_id)
	{
        return wpl_db::select(wpl_db::prepare("SELECT * FROM `#__wpl_dbst` WHERE `id` = %d", $field_id), 'loadObject');
	}

    /**
     * Get DB structure id based on kind and table column
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $table_column
     * @param int $kind
     * @return int
     */
    public static function get_dbst_id($table_column, $kind = 0)
    {
        $cache_key = 'wpl_dbst_id_'.$table_column.'_'.$kind;

        // Return From Cache
        $cached = wp_cache_get($cache_key);
        if($cached) return $cached;

        $id = wpl_db::select(wpl_db::prepare("SELECT `id` FROM `#__wpl_dbst` WHERE `kind` = %d and `table_column` = %s", $kind, $table_column), 'loadResult');

        // Set to Cache
        wp_cache_set($cache_key, $id, '', 100);

        return $id;
    }

    /**
     * @param string $column
     * @param integer $kind
     * @return object|null
     */
    public static function get_field_by_column($column, $kind = 0)
	{
        if(isset(self::$fields_by_column[$kind]) and isset(self::$fields_by_column[$kind][$column])) return self::$fields_by_column[$kind][$column];

        $result = wpl_db::select(wpl_db::prepare("SELECT * FROM `#__wpl_dbst` WHERE `kind` = %d AND `table_column` = %s LIMIT 1", $kind, $column), 'loadObject');

        if(!isset(self::$fields_by_column[$kind])) self::$fields_by_column[$kind] = array();

        self::$fields_by_column[$kind][$column] = $result;
        return $result;
	}
    
    /**
     * Create default dbst field and returns new dbst id
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $dbst_id
     * @param int $searchmod
     * @return int
     */
	public static function create_default_dbst($dbst_id = 0, $searchmod = 1)
	{
		if(!$dbst_id) $dbst_id = self::get_new_dbst_id();
		
		wpl_db::q(wpl_db::prepare('INSERT INTO `#__wpl_dbst` (`id`, `enabled`, `pshow`, `plisting`, `searchmod`, `pwizard`, `index`) VALUES (%d, 1, 1, 0, %s, 1,%d);', $dbst_id, $searchmod, $dbst_id), 'insert');

        return $dbst_id;
	}
	
    /**
     * Returns dbcats data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $enabled
     * @param int $kind
     * @param string $condition
     * @return array of objects
     */
	public static function get_categories($enabled = 1, $kind = 0, $condition = '')
	{
		if(trim($condition ?? '') == '') $condition = wpl_db::prepare(" AND `enabled` >= %d AND `kind` = %d", $enabled, $kind);
		
		return wpl_db::select("SELECT * FROM `#__wpl_dbcat` WHERE 1 ".$condition." ORDER BY `index` ASC", 'loadObjectList');
	}
	
    /**
     * Returns dbcat data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $category_id
     * @param string $condition
     * @return object
     */
	public static function get_category($category_id, $condition = '')
	{
        if(trim($condition ?? '') == '') $condition = wpl_db::prepare(" AND `id` = %d", $category_id);
        
		return wpl_db::select("SELECT * FROM `#__wpl_dbcat` WHERE 1 ".$condition, 'loadObject');
	}
	
    /**
     * Returns Kind Label
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $kind
     * @return string
     */
	public static function get_kind_label($kind = 0)
	{
		return wpl_db::get('name', 'wpl_kinds', 'id', $kind);
	}
	
    /**
     * Returns Kind Data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $kind
     * @return array
     */
    public static function get_kind($kind = 0)
	{
        return wpl_db::select(wpl_db::prepare("SELECT * FROM `#__wpl_kinds` WHERE `id` = %d", $kind), 'loadAssoc');
	}
    
    /**
     * Returns Kind ID
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $kind_name
     * @return int
     */
	public static function get_kind_id($kind_name = 'property')
	{
        return wpl_db::select(wpl_db::prepare("SELECT `id` FROM `#__wpl_kinds` WHERE name = %s", $kind_name), 'loadResult');
	}
    
    /**
     * Returns Kind Table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $dbcat
     * @param int $kind
     * @return string
     */
	public static function get_kind_table($kind = 0, $dbcat = 0)
	{
        $result = wpl_db::select(wpl_db::prepare("SELECT `table`, `params` FROM `#__wpl_kinds` WHERE `id` = %d", $kind), 'loadObject');
        
        if($dbcat and $result->params)
        {
            $params = json_decode($result->params ?? '', true);
            if(isset($params['dbcat_tables']) and isset($params['dbcat_tables'][$dbcat]))
            {
                return $params['dbcat_tables'][$dbcat];
            }
        } 

        return $result->table;
	}
	
    /**
     * Returns Valid Kinds
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_valid_kinds()
	{
        return wpl_db::select("SELECT `id` FROM `#__wpl_kinds` ORDER BY `index` ASC", 'loadColumn');
	}
    
    /**
     * Returns Kinds
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param String $table
     * @return array
     */
	public static function get_kinds($table = 'wpl_properties')
	{
		$condition = '';
		if(trim($table ?? "")) {
			$condition = wpl_db::prepare(' AND `table` = %s', $table);
		}
        return wpl_db::select("SELECT * FROM `#__wpl_kinds` WHERE `enabled`>='1' $condition ORDER BY `index` ASC", 'loadAssocList');
	}
    
    /**
     * Returns Kind Template
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $wplpath
     * @param string $tpl
     * @param int $kind
     * @return string
     */
    public static function get_kind_tpl($wplpath, $tpl = NULL, $kind = 0)
	{
        if(!trim($tpl ?? '')) $tpl = 'default';
        
        /** Create Kind tpl such as default1.php etc. **/
        $kind_tpl = $tpl.'_k'.$kind;
        
        $wplpath = rtrim($wplpath, '.').'.'.$kind_tpl;
        $path = _wpl_import($wplpath, true, true);
        
        if(wpl_file::exists($path)) return $kind_tpl;
        else return $tpl;
	}
    
    /**
     * Returns dbst types
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $enabled
     * @param int $kind
     * @param string $condition
     * @return array of objects
     */
	public static function get_dbst_types($enabled = 1, $kind = 0, $condition = '')
	{
		if(trim($condition ?? '') == '')
		{
			$condition = wpl_db::prepare(" AND `enabled` >= %d AND `kind` LIKE %s", $enabled, wpl_db::esc_like("[$kind]"));
		}
		return wpl_db::select("SELECT * FROM `#__wpl_dbst_types` WHERE 1 ".$condition." ORDER BY `id` ASC", 'loadObjectList');
	}
    
    /**
     * Returns dbst type data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $enabled
     * @param int $kind
     * @param string $type
     * @return object
     */
	public static function get_dbst_type($enabled = 1, $kind = 0, $type = '')
	{
		return wpl_db::select(wpl_db::prepare("SELECT * FROM `#__wpl_dbst_types` WHERE `enabled` >= %d AND `kind` LIKE %s AND `type` = %s", $enabled, wpl_db::esc_like("[$kind]"), $type), 'loadObject');
	}
	
    /**
     * Returns value of one specific column of dbst record on dbst table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $key
     * @param int $dbst_id
     * @param int $kind
     * @return boolean
     */
	public static function get_dbst_key($key, $dbst_id, $kind = 0)
	{
		/** first validation **/
		if(trim($key ?? '') == '' or trim($dbst_id ?? '') == '') return false;
		
		$dbst_data = self::get_field($dbst_id);
		return (isset($dbst_data->$key) ? $dbst_data->$key : NULL);
	}
	
    /**
     * Returns new dbst id
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return int
     */
	public static function get_new_dbst_id()
	{
		$max_dbst_id = wpl_db::get("MAX(`id`)", "wpl_dbst", '', '', '', "`id`<'10000'");
		return max(($max_dbst_id+1), 3000);
	}
	
    /**
     * Removes a dbst field from dbst table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $dbst_id
     * @return boolean
     */
	public static function remove_dbst($dbst_id)
	{
		/** first validation **/
		if(!$dbst_id) return false;
		
        /** Multilingual **/
        if(wpl_global::check_addon('pro')) wpl_addon_pro::remove_multilingual($dbst_id);

        /** trigger event **/
		wpl_global::event_handler('dbst_removed', $dbst_id);
        
        $table_column = wpl_flex::get_dbst_key('table_column', $dbst_id);
        
		wpl_db::delete("wpl_dbst", $dbst_id);
        
        // Remove field from all blogs
        if(wpl_global::is_multisite() and trim($table_column ?? '') != '')
        {
            $current_blog_id = wpl_global::get_current_blog_id();
            
            $blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
            foreach($blogs as $blog_id)
            {
                if($blog_id == $current_blog_id) continue;
                switch_to_blog($blog_id);
                
                wpl_db::q(wpl_db::prepare("DELETE FROM `#__wpl_dbst` WHERE `table_column` = %s", $table_column), "UPDATE");
            }

            switch_to_blog($current_blog_id);
        }
        
        return true;
	}
	
    /**
     * Generates modify form of a dbst field
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $dbst_type
     * @param int $dbst_id
     * @param int $kind
     * @param int $cat_id
     * @return void
     */
	public static function generate_modify_form($dbst_type = 'text', $dbst_id = 0, $kind = 0, $cat_id = 0)
	{
		/** first validation **/
		if(!$dbst_type) return;
		
        if($dbst_id != 0)
        {
            $dbst_data = self::get_field($dbst_id);
        }
        else
        {
            $dbst_data = new stdClass();
            $dbst_data->category = $cat_id;
        }

        if(wpl_global::check_addon('pro'))
        {
            $dbst_data = self::render_comparable($dbst_data);
        }
		
		$done_this = false;
		$type = $dbst_type;
		$values = $dbst_data;
		$options = isset($values->options) ? json_decode($values->options ?? '', true) : array();
		
		$__prefix = 'wpl_flex_modify';
		
		$dbcats = self::get_categories(0, $kind);
		$listings = wpl_listing_types::get_listing_types();
		$property_types = wpl_property_types::get_property_types();
        $user_types = wpl_users::get_user_types(1, 'loadAssocList');
        $memberships = wpl_users::get_wpl_memberships();
		
		/** get files **/
		$dbst_modifypath = WPL_ABSPATH . DS . 'libraries' . DS . 'dbst_modify';
		$files = array();
		
		if(wpl_folder::exists($dbst_modifypath))
		{
			$files = wpl_folder::files($dbst_modifypath, '.php$');
			
			foreach($files as $file)
			{
				include($dbst_modifypath .DS. $file);
			}
			
			if(!$done_this)
			{
				/** include default file **/
				include _wpl_import('libraries.dbst_modify.main.default', true, true);
			}
		}
	}
	
    /**
     * Runs dbst type queries for a dbst field
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $dbst_id
     * @param string $dbst_type
     * @param int $dbst_kind
     * @param string $query_type
     * @param string $table
     * @return boolean
     */
	public static function run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, $query_type = 'add', $table = NULL)
	{
		$dbst_type_data = self::get_dbst_type(0, $dbst_kind, $dbst_type);
        $dbst = self::get_field($dbst_id);

        // Table Name
		if(!trim($table ?? '')) $table = self::get_kind_table($dbst_kind, $dbst->category);

        $options = array();
		if($query_type == 'add') $options = self::get_field_options($dbst_id);
        
        /** Configure dbst columns if add mode **/
        if($query_type == 'add' and $dbst_type_data->options)
        {
            $dbst_type_options = json_decode($dbst_type_data->options ?? '', true);
            $q = '';
            
            foreach($dbst_type_options as $key=>$value) $q .= wpl_db::prepare('%i = %s, ', $key, $value);
            if(trim($q ?? '')) wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_dbst` SET ".trim($q ?? '', ', ')." WHERE `id` = %d", $dbst_id));
        }
        
		/** running all necessary queries **/
		if($query_type == 'add') $queries = $dbst_type_data->queries_add;
		else $queries = $dbst_type_data->queries_delete;
		
		$queries = explode(';', $queries);
		foreach($queries as $query)
		{
			if(trim($query ?? '') == '') continue;

            $wpdb = wpl_db::get_DBO();
            $query = str_replace('[TB_PREFIX]', (class_exists('wpl_sql_parser') ? $wpdb->base_prefix : $wpdb->prefix), $query);

			$query = str_replace('[TABLE_NAME]', $table, $query);
			$query = str_replace('[FIELD_ID]', $dbst_id, $query);

            /** Set default value if exists **/
            $default_value = $options['default_value'] ?? 0;

            if((empty($options) || (array_key_exists('default_value', $options) && $options['default_value'] === '')) && ($dbst_type == 'boolean' || $dbst_type == 'select')) {
                $default_value = 'null';
            }
			$query = str_replace('[DEFAULT_VALUE]', $default_value, $query);
            
			wpl_db::q($query);
		}

		return true;
	}
	
    /**
     * Returns encoded options
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $values
     * @param string $prefix
     * @param array $options
     * @return string
     */
	public static function get_encoded_options($values, $prefix, $options = array())
	{
		$length = strlen($prefix);
		
		foreach($values as $key=>$value)
		{
			if(substr($key, 0, $length) != $prefix) continue;
			
			$field = substr($key, $length);
			$options[$field] = $value;
		}
        
		return json_encode($options ?? '');
	}
	
    /**
     * Updates a table record
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $table
     * @param int $dbst_id
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
	public static function update($table, $dbst_id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table ?? '') == '' or trim($dbst_id ?? '') == '' or trim($key ?? '') == '') return false;
		
		return wpl_db::set($table, $dbst_id, $key, $value);
	}
    
    /**
     * Generates wizard form using dbst fields
     * @author Howard R <howard@realtyna.com>
     * @param array $fields
     * @param array $values
     * @param int $item_id
     * @param array $finds
     * @param string $nonce
     * @return void
     */
	public function generate_wizard_form($fields, $values, $item_id = 0, &$finds = array(), $nonce = NULL)
	{
		/** first validation **/
		if(!$fields) return;
        
        // Create Nonce
        if(is_null($nonce)) $nonce = wpl_security::create_nonce('wpl_listing');
        
		/** get files **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_wizard';
		$files = array();
		
		if(wpl_folder::exists($path)) $files = wpl_folder::files($path, '.php$', false, false);
		
        $wpllangs = wpl_global::check_multilingual_status() ? wpl_addon_pro::get_wpl_languages() : array();
        $has_more_details = false;

        /** store hidden fields used for field-specific */
        $hidden_fields = array();
        
		foreach($fields as $key=>$field)
		{
			if(!$field) return;
			
			$done_this = false;
			$type = $field->type;
            $label = $field->name;
            $mandatory = $field->mandatory;
			$options = json_decode($field->options ?? "", true);
            $value = isset($values[$field->table_column]) ? stripslashes($values[$field->table_column] ?? '') : NULL;
            $kind = isset($values['kind']) ? $values['kind'] : NULL;
            $specified_children = self::get_field_specific_children($field->id);
			$display = '';

            /** Specific **/
			if(trim($field->listing_specific ?? "") != '')
			{
				$specified_listings = explode(',', trim($field->listing_specific, ', '));
				self::$category_listing_specific_array[$field->id] = $specified_listings;
				if(!in_array($values['listing'], $specified_listings))
                {
                    $display = 'display: none;';
                    $hidden_fields[] = $field->id;  
                }
			}
			elseif(trim($field->property_type_specific ?? "") != '')
			{
				$specified_property_types = explode(',', trim($field->property_type_specific, ', '));
				self::$category_property_type_specific_array[$field->id] = $specified_property_types;
				if(!in_array($values['property_type'], $specified_property_types))
                {
                    $display = 'display: none;';
                    $hidden_fields[] = $field->id;  
                }
			}
            elseif(trim($field->user_specific ?? "") != '')
			{
				$specified_user_types = explode(',', trim($field->user_specific, ', '));
				self::$category_user_specific_array[$field->id] = $specified_user_types;
				if(!in_array($values['membership_type'], $specified_user_types))
                {
                    $display = 'display: none;';
                    $hidden_fields[] = $field->id;  
                }
			}
            elseif(trim($field->field_specific ?? "") != '')
            {
                $specified_field = explode(':', trim($field->field_specific));
                $parent_field = self::get_field($specified_field[0]);
                if(isset($parent_field) and (in_array($parent_field->id, $hidden_fields) or $values[$parent_field->table_column] != $specified_field[1]))
                {
                    $display = 'display: none;';
                    $hidden_fields[] = $field->id;
                } 
            }
			elseif(isset($options['access']))
			{
				foreach($options['access'] as $access)
				{
					if(!wpl_global::check_access($access))
					{
						$display = 'display: none;';
                        $hidden_fields[] = $field->id;  
						break;
					}
				}
			}
			
            /** More Details **/
            if($type == 'more_details' and !$has_more_details)
            {
                wpl_esc::e('<div class="wpl_listing_field_container wpl-pwizard-prow-'.$type.'" id="wpl_listing_field_container'.$field->id.'">');
                wpl_esc::e('<label for="wpl_c_'.$field->id.'"><span>'.wpl_esc::return_html_t($label).'</span></label>');
                wpl_esc::e('<div id="wpl_more_details'.$field->id.'" style="display: none;" class="wpl-fields-more-details-block">');
                
                $has_more_details = true;
            }
            elseif($type == 'more_details' and $has_more_details)
            {
                /** Only one details field is acceptable in each category **/
                continue;
            }
            
            /** Accesses **/
			if( trim($field->accesses ?? '') != '' and wpl_global::check_addon('membership'))
			{
				$accesses = explode(',', trim($field->accesses, ', '));
                $cur_membership_id = wpl_users::get_user_membership();
                
				if(!in_array($cur_membership_id, $accesses) and trim($field->accesses_message ?? '') == '') continue;
                elseif(!in_array($cur_membership_id, $accesses) and trim($field->accesses_message ?? '') != '')
                {
                    wpl_esc::e('<div class="prow wpl_listing_field_container prow-'.$type.'" id="wpl_listing_field_container'.$field->id.'" style="'.$display.'">');
                    wpl_esc::e('<label for="wpl_c_'.$field->id.'">'.wpl_esc::return_html_t($label).'</label>');
                    wpl_esc::e('<span class="wpl-access-blocked-message">'.wpl_esc::return_html_t($field->accesses_message).'</span>');
                    wpl_esc::e('</div>');

                    continue;
                }
			}
            
			/** js validation **/
			self::$wizard_js_validation[$field->id] = self::generate_js_validation($field);
            
			if(isset($finds[$type]))
			{
				wpl_esc::e('<div class="prow wpl_listing_field_container prow-'.$type.'" id="wpl_listing_field_container'.$field->id.'" style="'.$display.'">');
				$done_this = apply_filters("wpl_flex/generate_wizard_form/$type", 0, $field, $values, $item_id, $this);
				if(!$done_this) {
					include($path .DS. $finds[$type]);
				}
				wpl_esc::e('</div>');
				
				continue;
			}

			wpl_esc::e('<div class="prow wpl_listing_field_container prow-'.$type.'" id="wpl_listing_field_container'.$field->id.'" style="'.$display.'">');
			$done_this = apply_filters("wpl_flex/generate_wizard_form/$type", 0, $field, $values, $item_id, $this);
			if(!$done_this) {
				foreach($files as $file)
				{
					include($path .DS. $file);

					if($done_this)
					{
						$finds[$type] = $file;
						break;
					}
				}
			}

			wpl_esc::e('</div>');
		}
        
        if($has_more_details)
        {
			wpl_esc::e('</div></div>');
        }
	}
	
    /**
     * Returns js validation code
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param object $field
     * @return string
     */
	public static function generate_js_validation($field)
	{
		$field = (object) $field;
        $label = $field->name;
        $mandatory = $field->mandatory;
        
		$js_string = '';
		
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_wizard' .DS. 'js_validation' .DS. $field->type .'.php';
		$override_path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_wizard' .DS. 'js_validation' .DS. 'overrides' .DS. $field->type .'.php';
		
		if(wpl_file::exists($override_path)) $path = $override_path;
		
		/** include file **/
		if(wpl_file::exists($path)) include $path;
		
		return $js_string;
	}
	
    /**
     * Returns field options
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $field_id
     * @param boolean $return_array
     * @return mixed
     */
	public static function get_field_options($field_id, $return_array = true)
	{
		$field = self::get_field($field_id);
		return ($return_array ? json_decode($field->options ?? '', true) : $field->options);
	}

	/**
	 * Get value of a key in flex option
	 * @param $fieldId
	 * @param $key
	 * @return string|null
	 */
	public static function getOptionValue($fieldId, $key)
	{
		$options = wpl_flex::get_field_options($fieldId);
		$parameters = [];
		if(!empty($options['params']) and is_array($options['params'])) {
			$parameters = $options['params'];
		}elseif(!empty($options['values']) and is_array($options['values'])) {
			$parameters = $options['values'];
		}
		foreach($parameters as $parameter) {
			if($parameter['key'] == $key) {
				return $parameter['value'];
			}
		}
		return null;
	}
    
    /**
     * Sorts flex fields
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     */
	public static function sort_flex($sort_ids)
	{
		$sort_ids = wpl_db::prepare_id_list($sort_ids);
		$flex_category = wpl_db::select("SELECT DISTINCT `category`  FROM `#__wpl_dbst` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC", 'loadAssoc');

		$counter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			if($counter < 10) $index = $flex_category["category"].'.00'.$counter;
            elseif($counter < 100) $index = $flex_category["category"].'.0'.$counter;
			else $index = $flex_category["category"].'.'.$counter;

			self::update('wpl_dbst', $ex_sort_id, 'index', $index);
			$counter++;
		}
	}
	/**
	 * Sorts flex categories
	 * @author Alfred M <Alfred@realtyna.com>
	 * @static
	 * @param string $sort_ids
	 */
	public static function sort_flex_categories($sort_ids=null)
	{
		$sort_ids = wpl_db::prepare_id_list($sort_ids);
		$flex_category = wpl_db::select("SELECT DISTINCT `index` FROM `#__wpl_dbcat` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC", 'loadAssoc');
		$counter = 0;
		$ex_sort_ids = explode(',', $sort_ids);

		foreach($ex_sort_ids as $ex_sort_id)
		{
			if ($counter < 10) $index = $flex_category["index"] . '.00' . $counter;
			elseif ($counter < 100) $index = $flex_category["index"] . '.0' . $counter;
			else $index = $flex_category["index"] . '.' . $counter;

			self::update('wpl_dbcat', $ex_sort_id, 'index', $index);
			$counter++;
		}
	}
	/**
	 * Generate search fields based on DBST fields
	 * @author Steve A. <steve@realtyna.com>
	 * @param  array  $fields
	 * @param  array  $finds
	 * @return array
	 */
	public function generate_search_fields($fields, $finds = array())
	{
		$fields = json_decode(json_encode($fields ?? ''), true);

		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'widget_search' .DS. 'frontend';
		$files = array();
		$widget_id = '';
		
		if(wpl_folder::exists($path)) $files = wpl_folder::files($path, '.php$');
		
		$rendered = array();
		foreach($fields as $key=>$field)
		{
			$type = $field['type'];
			$field_id = $field['id'];
			$field_data = $field;
			$options = json_decode($field['options'] ?? '', true);
			$specified_children = self::get_field_specific_children($field_id);

			$done_this = false;
			$html = '';
			
			if(isset($finds[$type]))
			{
				$html .= '<span class="wpl_search_field_container '.(isset($field['type']) ? $field['type'].'_type' : '').' '.((isset($field['type']) and $field['type'] == 'predefined') ? 'wpl_hidden' : '').'" id="wpl'.$widget_id.'_search_field_container_'.$field['id'].'">';
				include($path .DS. $finds[$type]);
				$html .= '</span> ';
				
				$rendered[$field_id]['id'] = $field_id;
				$rendered[$field_id]['field_options'] = json_decode($field['options'] ?? '', true);
				$rendered[$field_id]['html'] = $html;
                $rendered[$field_id]['current_value'] = isset($current_value) ? $current_value : NULL;
				continue;
			}
			
			$html .= '<span class="wpl_search_field_container '.(isset($field['type']) ? $field['type'].'_type' : '').' '.((isset($field['type']) and $field['type'] == 'predefined') ? 'wpl_hidden' : '').'" id="wpl'.$widget_id.'_search_field_container_'.$field['id'].'">';
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				/** proceed to next field **/
				if($done_this)
				{
					$finds[$type] = $file;
					break;
				}
			}
			$html .= '</span> ';
			
			$rendered[$field_id]['id'] = $field_id;
			$rendered[$field_id]['field_options'] = json_decode($field['options'] ?? '', true);
			$rendered[$field_id]['html'] = $html;
            $rendered[$field_id]['current_value'] = isset($current_value) ? $current_value : NULL;
		}
        
		return $rendered;
	}
    
    /**
     * Returns WPL tag fields
     * @author Howard. <howard@realtyna.com>
     * @static
     * @param int $kind
     * @return array of objects
     */
    public static function get_tag_fields($kind = 0)
    {
		if(is_array($kind)) {
			$kind = implode(',', $kind);
		}
		$kind = wpl_db::prepare_id_list($kind);
		return wpl_flex::get_fields(NULL, NULL, NULL, NULL, NULL, "AND `type`='tag' AND `enabled`>='1' AND `kind` IN ($kind)");
    }

    /**
     * Returns WPL feature fields
     * @author Howard. <howard@realtyna.com>
     * @static
     * @param int $kind
     * @return array of objects
     */
    public static function get_feature_fields($kind = 0)
    {
		if(is_array($kind)) {
			$kind = implode(',', $kind);
		}
		$kind = wpl_db::prepare_id_list($kind);
		return wpl_flex::get_fields(NULL, NULL, NULL, NULL, NULL, "AND `type`='feature' AND `enabled`>='1' AND `kind` IN ($kind)");
    }
    
    /**
     * Returns field, category and kind names of a certain field
     * @author Howard. <howard@realtyna.com>
     * @static
     * @param int $dbst_id
     * @return array
     */
    public static function get_names($dbst_id)
    {
        if(!trim($dbst_id ?? '')) return array();
        
        $dbst = wpl_flex::get_field($dbst_id);
        $dbcat = wpl_flex::get_category($dbst->category);
        $kind = wpl_flex::get_kind($dbst->kind);
        
        return array('dbst_name'=>wpl_esc::return_html_t($dbst->name), 'dbcat_name'=>wpl_esc::return_html_t($dbcat->name), 'kind_name'=>wpl_esc::return_html_t($kind['name']));
    }

    /**
     * Maintain sortable status from a input fields
     * @author Edward <edward@realtyna.com>
     * @static
     * @param array|object $fields
     * @return mixed
     */
    public static function render_sortable($fields)
    {
        if(!is_array($fields) and !is_object($fields)) return false;

        $is_single = is_array($fields) ? false : true;
        if($is_single) $fields = array($fields);

        foreach($fields as $field)
        {
            if(!trim($field->table_name ?? "") or !trim($field->table_column ?? "")) $field->sortable = 0;

            $not_sortables = self::get_not_sortables();
            foreach($not_sortables as $pattern)
            {
                if(preg_match($pattern, $field->type)) $field->sortable = 0;
            }
        }

        return $is_single ? reset($fields) : $fields;
    }

    /**
     * Gets all of not sortables types (type is a field at dbst)
     * @author Edward <edward@realtyna.com>
     * @static
     * @return array
     */
    public static function get_not_sortables()
    {
        /** RegExp **/
        return array('/^addon_*/', '/^sys_*/', '/^fs_*/', '/separator/', '/attachments/', '/gallery/', '/googlemap/', '/locations/', '/neighborhood/', '/parent/', '/rooms/', '/tag/', '/textarea/', '/textsearch/', '/upload/');
    }

    /**
     * Maintain comparable status from a input fields
     * @author Edward <edward@realtyna.com>
     * @static
     * @param array|object $fields
     * @return mixed
     */
    public static function render_comparable($fields)
    {
        if(!is_array($fields) and !is_object($fields)) return false;

        $is_single = is_array($fields) ? false : true;
        if($is_single) $fields = array($fields);

        foreach($fields as $field)
        {
            /** When user inserts a new row **/
            if(!isset($field->table_name) or !isset($field->table_column)) continue;

            if(!trim($field->table_name ?? '') or !trim($field->table_column ?? '')) $field->comparable = 0;
            $field->comparable_row = (intval($field->comparable) and in_array($field->type, self::get_comparable_row_types())) ? 1 : 0;
        }

        return $is_single ? reset($fields) : $fields;
    }

    /**
     * Gets all of comparable_row types (type is a field at dbst)
     * @author Edward <edward@realtyna.com>
     * @static
     * @return array
     */
    public static function get_comparable_row_types()
    {
        return array('area', 'number', 'price', 'rooms', 'text');
    }

    /**
     * Get list of fields that are dependent to selected field
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string $id Field ID
     * @return string     List of fields
     */
    public static function get_field_specific_children($id)
    {
        $result = array();
        $fields = wpl_db::select(wpl_db::prepare("SELECT `id`, `field_specific` FROM `#__wpl_dbst` WHERE `field_specific` LIKE %s", wpl_db::esc_like("$id:", 'right')));

        foreach ($fields as $field) 
        {
            $value = explode(':', $field->field_specific);
            $result[] = $field->id.':'.$value[1];
        }

        return implode(',', $result);
    }
    
    public static function get_field_values($dbst_id)
    {
        $field = wpl_flex::get_field($dbst_id);
        $options = json_decode($field->options ?? '', true);
        
        $values = array();
        if($field->type == 'select')
        {
            $params = isset($options['params']) ? $options['params'] : array();
            foreach($params as $param) $values[$param['key']] = array('value'=>$param['key'], 'label'=>$param['value']);
        }
        elseif($field->type == 'feature')
        {
            $params = isset($options['values']) ? $options['values'] : array();
            foreach($params as $param) $values[$param['key']] = array('value'=>$param['key'], 'label'=>$param['value']);
        }
        elseif($field->type == 'listings')
        {
            $listings = wpl_global::get_listings();
            foreach($listings as $listing) $values[$listing['id']] = array('value'=>$listing['id'], 'label'=>$listing['name']);
        }
        elseif($field->type == 'property_types')
        {
            $property_types = wpl_global::get_property_types();
            foreach($property_types as $property_type) $values[$property_type['id']] = array('value'=>$property_type['id'], 'label'=>$property_type['name']);
        }
        
        return $values;
    }

    public static function change_storage($dbst_id, $new_storage)
    {
        $previous_storage = ($new_storage == 'wpl_properties2' ? 'wpl_properties' : 'wpl_properties2');

        // Field Data
        $dbst = wpl_flex::get_field($dbst_id);

        // Create Fields in New Storage
        wpl_flex::run_dbst_type_queries($dbst_id, $dbst->type, $dbst->kind, 'add', $new_storage);

        // Move Data to New Storage
        $columns = array($dbst->table_column);

        if($dbst->type == 'feature') $columns[] = $dbst->table_column.'_options';
        elseif($dbst->type == 'neighborhood')
        {
            $columns[] = $dbst->table_column.'_distance';
            $columns[] = $dbst->table_column.'_distance_by';
        }
        elseif(in_array($dbst->type, array('area', 'length', 'volume', 'price')))
        {
            $columns[] = $dbst->table_column.'_si';
            $columns[] = $dbst->table_column.'_unit';
        }
        elseif(in_array($dbst->type, array('mmarea', 'mmlength', 'mmvolume', 'mmprice')))
        {
            $columns[] = $dbst->table_column.'_si';
            $columns[] = $dbst->table_column.'_max';
            $columns[] = $dbst->table_column.'_max_si';
            $columns[] = $dbst->table_column.'_unit';
        }
        elseif($dbst->type == 'mmnumber')
        {
            $columns[] = $dbst->table_column.'_max';
        }

        foreach($columns as $column)
        {
            wpl_db::q(wpl_db::prepare('UPDATE %i SET %i = (SELECT %i FROM %i WHERE `id` = %i.`id`)', "#__$new_storage", $column, $column, "#__$previous_storage", "#__$new_storage"));
        }

        // Drop Columns from Previous Storage
        wpl_flex::run_dbst_type_queries($dbst_id, $dbst->type, $dbst->kind, 'delete', $previous_storage);

        // Drop From Sort Options
        if($new_storage == 'wpl_properties2')
        {
            wpl_db::q(wpl_db::prepare('DELETE FROM `#__wpl_sort_options` WHERE `field_name` = %s', "p.$dbst->table_column"));
        }
    }

    public static function save_into_dbst($dbst_id, $post, $multisite_modify_status)
    {
        $mode = 'edit';
        $current_blog_id = wpl_global::get_current_blog_id();

        // Storage
        $storage = $post['fld_storage'];
        if(trim($storage ?? '') and !in_array($storage, array('wpl_properties', 'wpl_properties2'))) $storage = 'wpl_properties';

        // Normal Storage
        if($storage == 'wpl_properties2')
        {
            $post['fld_table_name'] = 'wpl_properties2';
            $post['fld_searchmod'] = 0;
            $post['fld_text_search'] = 0;
            $post['fld_plisting'] = 0;
            $post['fld_sortable'] = 0;
        }
        // Search-able Storage
        else
        {
            $post['fld_sortable'] = 1;
        }

		/** insert new field **/
		if(!$dbst_id)
		{
			$mode = 'add';
			$dbst_id = wpl_flex::create_default_dbst();
		}

		// Previous Storage
        $previous_storage = ($mode == 'add' ? $storage : wpl_flex::get_dbst_key('table_name', $dbst_id));

        $available_columns = wpl_db::columns('wpl_dbst');

        $q = '';
        foreach($post as $field=>$value)
        {
            if(substr($field, 0 ,4) != 'fld_') continue;

            $key = substr($field, 4);
            if(trim($key ?? '') == '') continue;
            if(!in_array($key, $available_columns)) continue;

            $q .= wpl_db::prepare('%i = %s, ', $key, sanitize_text_field($value));
        }

        /** add options to query **/
        $options = wpl_flex::get_encoded_options($post, 'opt_', wpl_flex::get_field_options($dbst_id));
        $q .= wpl_db::prepare('`options` = %s, ', $options);

        if($mode == 'add' and $multisite_modify_status and wpl_global::is_multisite()) $q .= wpl_db::prepare('`source_id` = %s, ', $dbst_id.':'.$current_blog_id);

		$q = trim($q ?? '', ", ");
		wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_dbst` SET ".$q." WHERE `id` = %d", $dbst_id), 'update');

		$dbst_type = $post['fld_type'];
		$dbst_kind = wpl_flex::get_dbst_key('kind', $dbst_id);
		$kind_table = wpl_flex::get_kind_table($dbst_kind);

        $table_name = wpl_flex::get_dbst_key('table_name', $dbst_id);
        $table_column = wpl_flex::get_dbst_key('table_column', $dbst_id);

		/** run queries **/
		if($mode == 'add') wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'add', $table_name);

        /** Multilingual **/
        if(wpl_global::check_addon('pro')) wpl_addon_pro::multilingual($dbst_id);

        // Normal Storage
        if($kind_table == 'wpl_properties' and trim($storage ?? '') and trim($previous_storage ?? '') and $storage != $previous_storage)
        {
            wpl_flex::change_storage($dbst_id, $storage);
        }

		/** trigger event **/
		wpl_global::event_handler('dbst_modified', array('id'=>$dbst_id, 'mode'=>$mode, 'kind'=>$dbst_kind, 'type'=>$dbst_type));

        if($multisite_modify_status and wpl_global::is_multisite())
        {
            $q .= wpl_db::prepare(", `table_column` = %s, ", $table_column);
            $q = trim($q, ', ');

            $blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
            foreach($blogs as $blog_id)
            {
                if($blog_id == $current_blog_id) continue;

                switch_to_blog($blog_id);

                if($mode == 'add')
                {
                    $dbst_id = wpl_flex::create_default_dbst();
                    $where = wpl_db::prepare('`id` = %d', $dbst_id);
                }
                else $where = wpl_db::prepare('`table_column` = %s', $table_column);

                wpl_db::q("UPDATE `#__wpl_dbst` SET " . $q . " WHERE " . $where, 'update');
            }

            switch_to_blog($current_blog_id);
        }
        return $dbst_id;
    }

	public static function isNullable($flexId) {
		$booleanColumn = wpl_db::select(wpl_db::prepare("SELECT table_name, table_column from `#__wpl_dbst` where `type` = 'boolean' and id = %d", $flexId), 'loadAssoc');
		if(empty($booleanColumn)) {
			return true;
		}
		return wpl_db::isNullable($booleanColumn['table_name'], $booleanColumn['table_column']);
	}
	public static function makeNullable($flexId) {
		$booleanColumn = wpl_db::select(wpl_db::prepare("SELECT table_name, table_column, options from `#__wpl_dbst` where `type` = 'boolean' and id = %d", $flexId), 'loadAssoc');
		if(empty($booleanColumn)) {
			return false;
		}
		$table_name = $booleanColumn['table_name'];
		$table_column = $booleanColumn['table_column'];
		$options = json_decode($booleanColumn['options'], true);
		$default = $options['default_value'];
		if($default === '' || empty($options) || array_key_exists('default_value', $options) === false) {
			$default = 'NULL';
		}
		wpl_db::q(wpl_db::prepare("ALTER TABLE %i MODIFY COLUMN %i TINYINT( 4 ) NULL DEFAULT $default", "#__$table_name", $table_column));
		return true;
	}
}