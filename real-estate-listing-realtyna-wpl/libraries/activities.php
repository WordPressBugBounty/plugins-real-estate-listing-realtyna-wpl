<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('view_renderer');

/**
 * WPL Activity library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/10/2013
 * @package WPL
 */
#[AllowDynamicProperties]
class wpl_activity
{
    use ViewRenderer;
    /**
     * Frontend Key
     */
    const ACTIVITY_FRONTEND = 0;
    
    /**
     * Backend Key
     */
    const ACTIVITY_BACKEND = 1;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity_layout;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity_file;
    
    /**
     *
     * @var string
     */
    public static $_wpl_activity_name;
    
    /**
     * Used for caching in get_activities function
     * @static
     * @var array
     */
    public static $activities = array();

    /**
     * @var integer
     */
    public $activity_id;

    /**
     * @var array
     */
    public $data;

    /**
     * Renders an activity and returns its output
     * @author Howard <howard@realtyna.com>
     * @static
     * @param object $activity
     * @param array $params
     * @return string
     */
    public static function render_activity($activity, $params = array())
    {
        $activity_params = array();
        if(trim($activity->params ?? '') != '') $activity_params = json_decode($activity->params ?? '', true);

        $params = array_merge($activity_params, $params);
        
        $wpl_activity = new wpl_activity();
        
        ob_start();
        $wpl_activity->import($activity->activity, $activity->id, $params);
        return ob_get_clean();
    }
    
    /**
     * Loads a specific position
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $position
     * @param array $params
     */
    public static function load_position($position, $params = array())
    {
        $wpl_activity = new wpl_activity();
        $activities = $wpl_activity->get_activities($position, 1);
        
        foreach($activities as $activity)
        {
            $activity_params = array();
            if(trim($activity->params ?? '') != '') $activity_params = json_decode($activity->params ?? '', true);

            $_params = array_merge($activity_params, $params);
            $wpl_activity->import($activity->activity, $activity->id, $_params);
        }
    }
    
    /**
     * Imports an activity
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $activity
     * @param int $activity_id
     * @param array|boolean $params
     * @return mixed
     */
    public static function import($activity, $activity_id = 0, $params = false)
    {
        self::$_wpl_activity = $activity;
        $ex = explode(':', self::$_wpl_activity);

        self::$_wpl_activity_name = $ex[0];
        self::$_wpl_activity_layout = ( trim($ex[1] ?? '') != '') ? $ex[1] : 'default';
        self::$_wpl_activity_file = ( trim($ex[2] ?? '') != '') ? $ex[2] : 'main';
        $_wpl_activity_client = self::get_activity_client();

        $wpl_activity_path = 'views.' . $_wpl_activity_client . '.' . self::$_wpl_activity_name;
        $path = _wpl_import($wpl_activity_path . '.' . self::$_wpl_activity_file, true, true);

        /** check an activity if exists * */
        if(!wpl_file::exists($path))
        {
			wpl_esc::e('<div>' . wpl_esc::return_html_t("Activity not found!") . '</div>');
            return false;
        }

        /** set activity params * */
        $layout = $wpl_activity_path . '.tmpl.' . self::$_wpl_activity_layout;
        $params = self::get_params($activity_id, $params);
        $activity_class_name = 'wpl_activity_' . self::$_wpl_activity_file . '_' . self::$_wpl_activity_name;

        /** include the activity class if not exists * */
        if(!class_exists($activity_class_name)) include $path;

        $activity_object = new $activity_class_name();
        $activity_object->activity_id = $activity_id;
        $activity_object->data = $activity_object->data($activity_id);
        $activity_object->start($layout, $params);

        return true;
    }

    /**
     * Returns an activity data
     * @author Howard <howard@realtyna.com>
     * @param int $activity_id
     * @return mixed
     */
    public static function data($activity_id)
    {
        return wpl_db::select(wpl_db::prepare('SELECT * FROM `#__wpl_activities` WHERE `id` = %d', $activity_id), 'loadObject');
    }
    
    /**
     * Returns params of activity
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $activity_id
     * @param array|boolean $params
     * @return array
     */
    public static function get_params($activity_id, $params = false)
    {
        if(!$params)
        {
            $result = wpl_db::select(wpl_db::prepare('SELECT * FROM `#__wpl_activities` WHERE `id` = %d', $activity_id), 'loadAssoc');
            $params = json_decode($result['params'] ?? '', true);
        }

        if(!$params) {
			return array();
		}
        return $params;
    }
    
    /**
     * Get directory of activities
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
    private static function get_activity_client()
    {
        return 'activities';
    }
    
    /**
     * Returns some activity with specified criteria
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $position
     * @param int $enabled
     * @param string $condition
     * @param string $result
     * @param string $activity
     * @return mixed
     */
    public static function get_activities($position, $enabled = 1, $condition = '', $result = 'loadObjectList', $activity = '')
    {
        // Generate the Cache Key
        $cache_key = $position.'_'.$enabled.'_'.$condition.'_'.$result.'_'.$activity;
        
        // Return from cache if exists
        if(isset(self::$activities[$cache_key])) return self::$activities[$cache_key];
        
        if(trim($condition ?? '') == '')
        {
            $client = wpl_global::get_client();
            $condition = wpl_db::prepare(" AND `client` IN (%d, 2)", $client);
            
            if(trim($position ?? '') != '') $condition .= wpl_db::prepare(' AND `position` = %s', $position);
            if(trim($enabled ?? '') != '') $condition .= wpl_db::prepare(' AND `enabled` >= %d', $enabled);
            if(trim($activity ?? '') != '') $condition .= wpl_db::prepare(' AND (`activity` = %s OR `activity` = %s)', $activity, "$activity:default");
            
            // page associations
            if(is_page())
            {
                $post_id = wpl_global::get_the_ID();
                if($post_id) $condition .= wpl_db::prepare(" AND (`association_type`='1' OR (`association_type`='2' AND `associations` LIKE %s) OR (`association_type`='3' AND `associations` NOT LIKE %s))", wpl_db::esc_like("[$post_id]"), wpl_db::esc_like("[$post_id]"));
            }
            
            // Accesses
            if(wpl_global::check_addon('membership'))
            {
                $cur_membership = wpl_users::get_user_membership();
                if($cur_membership) $condition .= wpl_db::prepare(" AND (`access_type`='2' OR (`access_type`='1' AND `accesses` LIKE %s))", wpl_db::esc_like(",$cur_membership,"));
            }
            
            $condition .= " ORDER BY `index` ASC, `ID` DESC";
        }

        $results = wpl_db::select("SELECT * FROM `#__wpl_activities` WHERE 1 " . $condition, $result);
        
        /** add to cache **/
		self::$activities[$cache_key] = $results;
        
        return $results;
    }

    /**
     * check Activity is Frontend or Backend
     * @author Kevin J <kevin@realtyna.com>
     * @param string $activity_name 
     * @param integer $mode Activity mode to check
     * @return boolean
     */
    public static function check_activity($activity_name, $mode = self::ACTIVITY_FRONTEND)
    {
        $xml = self::get_system_params($activity_name);
        if($xml) return ($mode == $xml->backend);
        return false;
    }

    /**
     * sort Activity item by given Activities ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $sort_ids id of item to sort by string seperate by coma(,)
     * @return int count of updated items
     */
    public static function sort($sort_ids)
    {
        $ex_sort_ids = explode(',', $sort_ids);
        $data = self::_get_data_for_sort($ex_sort_ids);
        $count = 0;
		
        foreach($ex_sort_ids as $ex_sort_id)
        {
            $newItem = explode(':', $ex_sort_id);
            $currentRank = $data[$newItem[0]];
            $newItem[1] = $newItem[1] / 100;
            if($currentRank != $newItem[1]) // Check Index of Activity is Changed or Not
            {
                self::update_one($newItem[0], 'index', $newItem[1]);
                $count++;
            }
        }
		
        return $count;
    }

    /**
     * get Activities by given Array of ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $ex_sort_ids
     * @return array
     */
    private static function _get_data_for_sort($ex_sort_ids)
    {
        $id = array();
        $data = array();
		
        foreach($ex_sort_ids as $ex_sort_id)
        {
            $id_section = explode(':', $ex_sort_id);
            $id[] = $id_section[0];
        }
		
        $activities = wpl_db::select("SELECT `id`, `index` FROM `#__wpl_activities` WHERE `id` IN (".implode(",", $id).") ORDER BY `index` ASC, `id` DESC", 'loadAssocList');
		
        foreach($activities as $activity)
        {
            $data[$activity['id']] = $activity['index'];
        }
		
        return $data;
    }

    /**
     * Remove Activity by Given ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param integer $activity_id Activity ID to remove
     * @return boolean
     */
    public static function remove_activity($activity_id)
    {
        /** trigger event **/
		wpl_global::event_handler('activity_removed', array('id'=>$activity_id));

        return wpl_db::q(wpl_db::prepare("DELETE FROM `#__wpl_activities` WHERE `id` = %d", $activity_id));
    }

    /**
     * update Activity
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param integer $id ID of Activity to Update
     * @param string $key field Key must to change
     * @param string $value new Value to set this
     * @return boolean
     */
    public static function update_one($id, $key, $value = '')
    {
        /** first validation **/
        if(trim($id ?? '') == '' or trim($key ?? '') == '') return false;
		
        return wpl_db::set('wpl_activities', $id, $key, $value);
    }

    /**
     * get Activity Folder Path
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @return string
     */
    public static function get_activity_folder()
    {
        return WPL_ABSPATH . 'views' . DS . 'activities' . DS;
    }

    /**
     * get Activity System Parameter
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param String $activity_name
     * @return SimpleXMLElement|boolean
     */
    public static function get_system_params($activity_name)
    {
        $path = self::get_activity_folder() . $activity_name . DS . 'system.xml';
        if(file_exists($path)) return simplexml_load_file($path);
		
        return false;
    }

    /**
     * Search Activity and return just one result
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $conditions
     * @return Object
     */
    public static function get_activity($conditions)
    {
        return self::get_activities('', '', $conditions, 'loadObject');
    }

    /** Split Activity name and layout name
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $name Activity name
     * @return array [0] is a activity name and [1] if exist is layout name
     */
    public static function get_activity_name_layout($name)
    {
        return explode(':', $name);
    }
    
    /**
     * Returns new activity ID for adding new activities
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return integer
     */
    public static function get_new_activity_id()
    {
        $max_id = wpl_db::get("MAX(`id`)", "wpl_activities", '', '', '', '1');
		return max(($max_id+1), 100);
    }

    /**
     * Add Activity
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $information
     * @return integer inserted Activity ID
     */
    public static function add_activity($information)
    {
        $activity_id = wpl_activity::get_new_activity_id();
        
        $query  = 'INSERT INTO `#__wpl_activities` (`id`,`activity`, `position`, `enabled`, `params`, `show_title`, `title`, `index`, `association_type`, `associations`)';
        $query .= wpl_db::prepare(' VALUES (%d, %s, %s, %s,', $activity_id, $information['activity'], $information['position'], $information['enabled']);
        $query .= wpl_db::prepare("%s, %d, %s, %f, %s, %s)", $information['params'], $information['show_title'], $information['title'], $information['index'], $information['association_type'], $information['associations']);
        
        wpl_db::q($query, 'insert');
        
        if(isset($information['access_type']) and wpl_global::check_addon('membership'))
        {
            wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_activities` SET `access_type` = %s, `accesses` = %s WHERE `id` = %d", $information['access_type'], $information['accesses'], $activity_id), 'UPDATE');
        }
        
        /** trigger event **/
		wpl_global::event_handler('activity_added', array('id'=>$activity_id));
        
        return $activity_id;
    }

    /**
     * Update Activity by Given ID
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $information
     * @return boolean
     */
    public static function update_activity($information)
    {
        $data = [
			'activity' => $information['activity'],
			'position' => $information['position'],
			'enabled' => $information['enabled'],
			'params' => $information['params'],
			'show_title' => $information['show_title'],
			'title' => $information['title'],
			'index' => (float) $information['index'],
			'association_type' => $information['association_type'],
			'associations' => $information['associations'],
			'client' => $information['client'],
		];
        
        if(isset($information['access_type']) and wpl_global::check_addon('membership'))
        {
			$data['access_type'] = $information['access_type'];
			$data['accesses'] = $information['accesses'];
        }
        
        /** trigger event **/
		wpl_global::event_handler('activity_updated', array('id'=>$information['activity_id']));
        
        return wpl_db::update('wpl_activities', $data, 'id', $information['activity_id']);
    }
    
    /**
     * get Activity Options form by Activity Name
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $activity_name
     * @return string|boolean if Activity have Option form return Path or return false if Activity Option not Exist
     */
    public static function get_activity_option_form($activity_name)
    {
        $optionsPath = self::get_activity_folder() . $activity_name . DS . 'form.php';
        if(file_exists($optionsPath))
        {
            return $optionsPath;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * get list of activity layout
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $activityName
     * @return array
     */
    public static function get_activity_layout($activityName)
    {
        $layouts = wpl_folder::files(self::get_activity_folder() . $activityName . DS . 'tmpl');
        $activity_layouts = array();
		
        foreach($layouts as $layout)
        {
            if(strpos($layout ?? '', '.html') !== false or strpos($layout ?? '', 'internal_') !== false) continue;
            
            $file = basename($layout, ".php");
            $activity_layouts[] = $file;
        }
		
        return $activity_layouts;
    }
    
    /**
     * generate activity layout select options
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $activity_name
     * @param string $current_layout
     * @return string select html code
     */
    public static function load_layouts_html($activity_name = '', $current_layout = '')
    {
        if(trim($activity_name ?? '') != '') $activity_layouts = self::get_activity_layout($activity_name);
		
        $html_form = '<select id="wpl_layout" class="text_box" name="info[layout]">';
        $html_form .= '<option value="">-----</option>';
		
        if(!empty($activity_layouts))
        {
            foreach($activity_layouts as $layout)
            {
                $html_form .= '<option';
				
                if(trim($current_layout ?? '') != '' && $layout == $current_layout)
                {
                    $html_form .= ' selected="selected" ';
                }
				
                $html_form .= ' value="'.$layout.'">'.$layout.'</option>';
            }
        }
		
        $html_form .= '</select>';
        return $html_form;
    }
	
	/**
     * get All Activities in Activity folder and remove Backend Activity
     * @author Kevin J <kevin@realtyna.com>
     * @return array list of Frontend activity list
     */
    public static function get_available_activities()
    {
        $activities_folders = wpl_folder::folders(wpl_activity::get_activity_folder());
        $frontend_activity = array();
		
        foreach($activities_folders as $activity)
        {
            if(wpl_activity::check_activity($activity, wpl_activity::ACTIVITY_FRONTEND)) $frontend_activity[] = $activity;
        }
		
        return $frontend_activity;
    }
    
    /**
     * For printing response on the page for AJAX requests
     * @author Howard <howard@realtyna.com>
     * @param array $response
     */
    public function response($response = array())
    {
        wpl_esc::e(json_encode($response ?? ''));
        exit;
    }
}