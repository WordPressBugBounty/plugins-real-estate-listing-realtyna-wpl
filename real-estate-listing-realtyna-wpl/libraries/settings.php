<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Settings Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/01/2013
 * @package WPL
 */
class wpl_settings
{
    /**
     * Used for caching in get_settings function
     * @static
     * @var array
     */
    public static $wpl_settings = array();

    /**
     * Get settings
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $category
     * @param int $showable
     * @param boolean $return_records
     * @return array
     */
    public static function get_settings($category = '', $showable = 0, $return_records = false)
    {
        // Return from cache if exists
        $cache_key  = trim($category ?? '') != '' ? $category : 'all';
        $cache_key .= '_'.wpl_global::get_current_blog_id();

        if(isset(self::$wpl_settings[$cache_key]) and !$return_records) return self::$wpl_settings[$cache_key];

        $condition = '';
        if(trim($category ?? '') != '')
        {
            if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
            $condition .= wpl_db::prepare(" AND `category` = %s", $category);
        }

        $condition .= wpl_db::prepare(" AND `showable` >= %d", $showable);

        $records = wpl_db::select("SELECT * FROM `#__wpl_settings` WHERE 1 ".$condition." ORDER BY `index` ASC");

        if($return_records)
        {
            return $records;
        }

        $settings = array();
        foreach($records as $record)
        {
            $settings[$record->setting_name] = $record->setting_value;
        }

        /** add to cache **/
        self::$wpl_settings[$cache_key] = $settings;
        return $settings;
    }

    /**
     * This function takes care for modifying existing setting or inserting new record
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $category
     * @param string $condition
     * @return boolean
     */
    public static function save_setting($name, $value = '', $category = '', $condition = '')
    {
        /** first validation **/
        if(trim($name ?? '') == '') return false;

        $exists = wpl_settings::is_setting_exists($name, $category);
		$old_value = null;
        if($exists) {
			$old_value = wpl_settings::get($name);
			$result = wpl_settings::update_setting($name, $value, $category, $condition);
		}
        else {
			$result = wpl_settings::insert_setting($name, $value, $category);
		}
		if($result) {
			do_action('wpl_settings/save_setting', $name, $value, $old_value);
		}

        return $result;
    }

    /**
     * Updates settings
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $category
     * @param string $condition
     * @return boolean
     */
    public static function update_setting($name, $value = '', $category = '', $condition = '')
    {
        /** first validation **/
        if(trim($name ?? '') == '') return false;

        if(trim($condition ?? '') == '' and trim($category ?? '') != '')
        {
            if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
            $condition .= wpl_db::prepare("AND `category` = %d", $category);
        }

        $result = wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_settings` SET `setting_value` = %s WHERE `setting_name` = %s ".$condition, $value, $name), 'update');

        /** trigger event **/
        wpl_global::event_handler('settings_updated', array('setting_value'=>$value, 'setting_name'=>$name));

        return $result;
    }
    /**
     * Delete settings
     * @author Hollis <hollis.a@realtyna.net>
     * @static
     * @param string $name
     * @param string $name
     * @param mixed $value
     * @return boolean
     * @since 4.13.0
     */
    public static function delete_setting($name, $value = '', $category = '', $condition = '')
    {
        /** first validation **/
        if(trim($name ?? '') == '') return false;

        $result = wpl_db::q(wpl_db::prepare("DELETE FROM `#__wpl_settings` WHERE `setting_name` = %s", $name), 'delete');

        /** trigger event **/
        wpl_global::event_handler('settings_updated', array('setting_value'=>$value, 'setting_name'=>$name));

        return $result;
    }

    /**
     * Inserts a new setting
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $category
     * @return boolean
     */
    public static function insert_setting($name, $value = '', $category = '')
    {
        /** first validation **/
        if(trim($name ?? '') == '') return false;

        $category_id = 1;
        if(trim($category ?? '') != '')
        {
            if(!is_numeric($category)) $category_id = wpl_settings::get_category_id($category);
            else $category_id = $category;
        }

        $id = wpl_db::insert('wpl_settings', ['setting_name' => $name, 'setting_value' => $value, 'category' => $category_id]);

        /** trigger event **/
        wpl_global::event_handler('settings_added', array('id'=>$id));

        return $id;
    }

    /**
     * Get one setting
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $setting_name
     * @param string $category
     * @return mixed
     */
    public static function get($setting_name, $category = '', $use_cache = true)
    {
        // Return from cache if exists
        $cache_key  = trim($category ?? '') != '' ? $category : 'all';
        $cache_key .= '_'.wpl_global::get_current_blog_id();

        if(isset(self::$wpl_settings[$cache_key][$setting_name])) return self::$wpl_settings[$cache_key][$setting_name];

        $condition = "`setting_name`='$setting_name' ";
        if(trim($category ?? '') != '')
        {
            if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
            $condition .= wpl_db::prepare(" AND `category` = %d ", $category);
        }

        // Get Setting
        return wpl_db::get('setting_value', 'wpl_settings', '', '', true, $condition, $use_cache);
    }

    /**
     * Returns category id by category name
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $category
     * @return int
     */
    public static function get_category_id($category)
    {
        return wpl_db::select(wpl_db::prepare("SELECT `id` FROM `#__wpl_setting_categories` WHERE name = %s", $category), 'loadResult');
    }

    /**
     * Get setting categories
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $showable
     * @return object
     */
    public static function get_categories($showable = 1)
    {
        return wpl_db::select(wpl_db::prepare("SELECT * FROM `#__wpl_setting_categories` WHERE `showable` >= %d ORDER BY `index` ASC", $showable), 'loadObjectList');
    }

    /**
     * Check if setting exists or not
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param string $category
     * @return boolean
     */
    public static function is_setting_exists($name, $category = '')
    {
        $condition = '';

        if(trim($category ?? '') != '')
        {
            if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
            $condition .= wpl_db::prepare("AND `category` = %d", $category);
        }

        $query = "SELECT COUNT(`id`) FROM `#__wpl_settings` WHERE `setting_name`='$name' ".$condition;
        $num = wpl_db::num($query);

        return ($num ? true : false);
    }

    /**
     * Generate setting field
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $setting_record
     * @param string $nonce
     * @return void
     */
    public static function generate_setting_form($setting_record, $nonce)
    {
        /** first validation **/
        if(!$setting_record) return;

        $done_this = false;
        $type = $setting_record->type;
        $value = $setting_record->setting_value;
        $params = json_decode($setting_record->params ?? "", true);
        $options = json_decode($setting_record->options ?? "", true);

        $setting_title = trim($setting_record->title ?? '') != '' ?wpl_esc::return_t($setting_record->title) :wpl_esc::return_t(str_replace('_', ' ', $setting_record->setting_name));

        /** get files **/
        $path = WPL_ABSPATH .DS. 'libraries' .DS. 'settings_form';
        if(wpl_folder::exists($path))
        {
            $files = wpl_folder::files($path, '.php$');

            foreach($files as $file)
            {
                include($path .DS. $file);
            }
        }
    }

    /**
     * Generates settings form (All the fields)
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $setting_records
     * @return void
     */
    public static function generate_setting_forms($setting_records)
    {
        /** first validation **/
        if(!$setting_records) return;

        // Create Nonce
        $nonce = wpl_security::create_nonce('wpl_settings');

        foreach($setting_records as $key=>$setting_record)
        {
            self::generate_setting_form($setting_record, $nonce);
        }
    }

    /**
     * Removes WPL cached data
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $cache_type
     * @return boolean
     */
    public static function clear_cache($cache_type = 'all')
    {
        /** first validation **/
        $cache_type = strtolower($cache_type);
        if(trim($cache_type ?? '') == '') return false;

        if($cache_type == 'wpl_cache_directory' or $cache_type == 'all')
        {
            $cache = wpl_global::get_wpl_cache();
            $path = $cache->get_path();

            $directories = wpl_folder::folders($path);
            foreach($directories as $directory) wpl_folder::delete($path.DS.$directory.DS);

            $files = wpl_folder::files($path);
            foreach($files as $file) wpl_file::delete($path.DS.$file);
        }

        if($cache_type == 'unfinalized_properties' or $cache_type == 'all')
        {
            $properties = wpl_db::select("SELECT `id` FROM `#__wpl_properties` WHERE `finalized`='0'", 'loadAssocList');
            foreach($properties as $property) wpl_property::purge($property['id']);
        }

        if($cache_type == 'properties_cached_data' or $cache_type == 'all')
        {
            $q = " `location_text`='', `rendered`='', `alias`=''";
            if(wpl_global::check_multilingual_status()) $q = self::get_multilingual_query(array('alias', 'location_text', 'rendered'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q);
            wpl_locations::purge_cached_locations(true);
        }

        if($cache_type == 'location_cached_data' or $cache_type == 'all')
        {
            wpl_locations::purge_cached_locations(true);
        }

        if($cache_type == 'properties_title' or $cache_type == 'all')
        {
            $q = " `field_313`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('field_313'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `kind`='0'", 'UPDATE');
        }

        if($cache_type == 'properties_page_title' or $cache_type == 'all')
        {
            $q = " `field_312`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('field_312'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `kind`='0'", 'UPDATE');
        }

        if($cache_type == 'complexes_title' or $cache_type == 'all')
        {
            $q = " `field_313`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('field_313'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `kind`='1'", 'UPDATE');
        }

        if($cache_type == 'complexes_page_title' or $cache_type == 'all')
        {
            $q = " `field_312`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('field_312'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `kind`='1'", 'UPDATE');
        }

        if($cache_type == 'neighborhoods_title' or $cache_type == 'all')
        {
            $q = " `field_313`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('field_313'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `kind`='4'", 'UPDATE');
        }

        if($cache_type == 'neighborhoods_page_title' or $cache_type == 'all')
        {
            $q = " `field_312`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('field_312'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `kind`='4'", 'UPDATE');
        }

        if($cache_type == 'listings_meta_keywords' or $cache_type == 'all')
        {
            $q = " `meta_keywords`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('meta_keywords'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `meta_keywords_manual`='0'", 'UPDATE');
        }

        if($cache_type == 'listings_meta_description' or $cache_type == 'all')
        {
            $q = " `meta_description`=''";
            if(wpl_global::check_multilingual_status()) $q = wpl_settings::get_multilingual_query(array('meta_description'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q." WHERE `meta_description_manual`='0'", 'UPDATE');
        }

        if($cache_type == 'location_texts' or $cache_type == 'all')
        {
            $q = " `location_text`=''";
            if(wpl_global::check_multilingual_status()) $q = self::get_multilingual_query(array('location_text'));

            wpl_db::q("UPDATE `#__wpl_properties` SET ".$q);
        }

        if($cache_type == 'listings_thumbnails' or $cache_type == 'all')
        {
            $properties = wpl_db::select("SELECT `id`, `kind` FROM `#__wpl_properties` WHERE `id`>0", 'loadAssocList');
            foreach($properties as $property)
            {
                wpl_property::remove_thumbnails($property['id'], $property['kind']);
            }
        }

        if($cache_type == 'users_cached_data' or $cache_type == 'all')
        {
            $q = " `location_text`='', `rendered`=''";
            if(wpl_global::check_multilingual_status()) $q = self::get_multilingual_query(array('location_text', 'rendered'), 'wpl_users');

            wpl_db::q("UPDATE `#__wpl_users` SET ".$q);
        }

        if($cache_type == 'users_thumbnails' or $cache_type == 'all')
        {
            $users = wpl_db::select("SELECT `id` FROM `#__wpl_users` WHERE `id`>0", 'loadAssocList');
            $ext_array = array('[j|J][p|P][g|G]', '[j|J][p|P][e|E][g|G]', '[g|G][i|I][f|F]', '[p|P][n|N][g|G]');

            foreach($users as $user)
            {
                $path = wpl_items::get_path($user['id'], 2, null, false);
                $thumbnails = wpl_folder::files($path, '^(th|wm).*\.('.implode('|', $ext_array).')$', 3, true);

                foreach($thumbnails as $thumbnail) wpl_file::delete($thumbnail);

                /** delete email images **/
                if(wpl_file::exists($path.'main_email.png')) wpl_file::delete($path.'main_email.png');
                if(wpl_file::exists($path.'second_email.png')) wpl_file::delete($path.'second_email.png');
            }
        }

        if($cache_type == 'map_snapshots' or $cache_type == 'all')
        {
            $cache = wpl_global::get_wpl_cache();

            $directory = $cache->get_path().DS.'activities'.DS.'googlemap'.DS;
            wpl_folder::delete($directory);
        }

        /** trigger event **/
        wpl_global::event_handler('cache_cleared', array('cache_type'=>$cache_type));
        return true;
    }

    /**
     * Get Multilingual columns for clear cache queries
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $columns
     * @param string $table
     * @return string
     */
    public static function get_multilingual_query($columns = array(), $table = 'wpl_properties')
    {
        $q = "";

        $columns = wpl_global::get_multilingual_columns($columns, true, $table);
        foreach($columns as $column) $q .= "`$column`='', ";

        return trim($q ?? '', ', ');
    }

    /**
     * Import Settings from a file
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string  $file Settings File
     * @return boolean		 Result
     */
    public static function import_settings($file)
    {
        $content = wpl_file::read($file) ?? '';
        $ext = wpl_file::getExt($file);

        if($ext == 'json')
        {
            $settings = json_decode($content);
            if(!$settings) return false;
        }
        elseif($ext == 'xml')
        {
            $settings = simplexml_load_string($content);
            if(!$settings) return false;

            $settings = (array) $settings;
        }
        else return false;

        foreach($settings as $name=>$value) self::update_setting($name, $value);
        return true;
    }

    /**
     * Export Settings to a file
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string $format File Format
     * @return object 		  Settings File
     */
    public static function export_settings($format = 'json')
    {
        $settings = self::get_settings() ?? array();

        if($format == 'json') return json_encode($settings);
        elseif($format == 'xml')
        {
            $xml = new SimpleXMLElement('<wplsettings/>');
            foreach($settings as $k=>$v) $xml->addChild($k, htmlspecialchars($v));

            return $xml->asXML();
        }
        else return NULL;
    }

	public static function is_mls_on_the_fly() {
		return (defined('REALTYNA_RF_SHELL_BASE_PATH') || defined('REALTYNA_MLS_ON_THE_FLY_VERSION'))
			&& static::get('property_source') == 'rf'
			&& file_exists(_wpl_import('libraries.rf_shell.rf_property', true, true));
	}
}