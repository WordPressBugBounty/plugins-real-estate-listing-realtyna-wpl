<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Logs Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 04/18/2013
 * @package WPL
 */
class wpl_logs
{
    /**
     * For inserting logs in the logs table
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $log_text
     * @param string $section
     * @param int $status
     * @param int $user_id
     * @param int $addon_id
     * @param int $priority
     * @param array $params
     * @return int
     */
	public static function add($log_text, $section = '', $status = 1, $user_id = NULL, $addon_id = NULL, $priority = 3, $params = array())
	{
		if(trim($log_text ?? '') == '') return 0;
		
		/** set parameters **/
		$section = trim($section ?? '') != '' ? $section : 'no section';
		$status = trim($status ?? '') != '' ? $status : 1;
		$user_id = trim($user_id ?? '') != '' ? $user_id : wpl_users::get_cur_user_id();
		$addon_id = trim($addon_id ?? '') != '' ? $addon_id : 0;
		$log_date = current_time("Y-m-d H:i:s");
		$ip = wpl_users::get_current_ip();
		$params = json_encode($params ?? '');
		
		return wpl_db::insert('wpl_logs', [
			'user_id' => $user_id,
			'addon_id' => $addon_id,
			'section' => $section,
			'status' => $status,
			'log_text' => $log_text,
			'log_date' => $log_date,
			'ip' => $ip,
			'priority' => $priority,
			'params' => $params,
		]);
	}
    
    /**
     * For deleting logs by id or prior date or addon_id
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $log_id
     * @param string $prior_date
     * @param int $addon_id
     * @return boolean
     */
	public static function delete($log_id = NULL, $prior_date = '', $addon_id = NULL)
	{
		if(trim($log_id ?? '' ) == '' and trim($prior_date ?? '') == '' and trim($addon_id ?? '') == '') return false;
		
		$where = '';
		if(trim($log_id ?? '') != '') $where .= wpl_db::prepare(" AND `id` = %d", $log_id);
		if(trim($prior_date ?? '') != '') $where .= wpl_db::prepare(" AND `log_date` < %s", $prior_date);
		if(trim($addon_id ?? '') != '') $where .= wpl_db::prepare(" AND `addon_id` = %d", $addon_id);
		
		if(trim($where ?? '') == '') return false;
		
		return wpl_db::q("DELETE FROM `#__wpl_logs` WHERE 1 " . $where, 'delete');
	}
	
    /**
     * Get one log data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $log_id
     * @return object|boolean
     */
	public static function get($log_id)
	{
		if(trim($log_id ?? '') == '') return false;
		return wpl_db::get('*', 'wpl_logs', 'id', $log_id);
	}
	
    /**
     * Get logs data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $condition
     * @return array of objects
     */
	public static function get_logs($condition = '')
	{
		return wpl_db::select("SELECT * FROM `#__wpl_logs` WHERE 1 ".$condition, 'loadObjectList');
	}
    
    /**
     * For inserting logs using WPL events API
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $params
     * @return int
     */
	public static function autolog($params = array())
	{
        $log = wpl_global::get_setting('log', 1);
        
        if(!$log) return false;
        
        $dynamic_params = $params[0];
        $static_params = $params[1];
        
        $section = $static_params['section'] ?? 'no-section';
        $addon_id = $static_params['addon_id'] ?? 0;
        $user_id = $static_params['user_id'] ?? null;
        $status = $static_params['status'] ?? 1;
        $priority = $static_params['priority'] ?? 3;
        $log_text = '';
        
        $patterns = array('[',']');
        if($static_params['type'] == 1)
        {
            preg_match_all('#\[+[\w|\d]+\]?#', $static_params['message'], $pattern_match);
            $message_pattern = $pattern_match[0];
            
            if(is_array($dynamic_params))
            {
                if(count($dynamic_params) > 1)
                {
                    /* Modify params that took of preg_match */
                    $new_params = str_replace($patterns, '', $message_pattern);
                    $new_array_params = array();
                    
                    foreach($new_params as $value_array) $new_array_params[] = $dynamic_params[$value_array];
                    $log_text = str_replace($message_pattern, $new_array_params, $static_params['message']);
                }
                elseif(count($dynamic_params) == 1)
                {
                    $array_key = array_keys($dynamic_params);
                    $log_text = str_replace($message_pattern[0], $dynamic_params[$array_key[0]], $static_params['message']);
                }
            }
            else $log_text = str_replace($message_pattern[0], $dynamic_params, $static_params['message']);
        }
        elseif($static_params['type'] == 2)
        {
            preg_match_all('#\[+[\w|\d]+\]?#', $static_params['pattern'], $pattern_match);
            $value_pattern = $pattern_match[0];
            
            if(is_array($dynamic_params))
            {
                if(count($dynamic_params) > 1)
                {
                    /* Modify params that took of preg_match */
                    $new_params = str_replace($patterns, '', $value_pattern);
                    $new_array_params = array();
                    
                    foreach($new_params as $value_array) $new_array_params[] = $dynamic_params[$value_array];
                    $query = str_replace($value_pattern, $new_array_params, $static_params['pattern']);
                }
                elseif(count($dynamic_params) == 1)
                {
                    $array_values = array_values($dynamic_params);
                    $query = str_replace($value_pattern[0], $array_values[0], $static_params['pattern']);
                }
                else $query = str_replace($value_pattern[0], $dynamic_params, $static_params['pattern']);
            }
            else $query = str_replace($value_pattern[0], $dynamic_params, $static_params['pattern']);

            $contents = wpl_db::select($query, 'loadAssoc');
            
            $log_text = $static_params['message'];
            if(!empty($contents)) foreach($contents as $key=>$value) $log_text = str_replace('['.$key.']', $value, $log_text);
        }
        
        if($log_text == '') $log_text =  wpl_esc::return_t('Empty');
        return self::add($log_text, $section, $status, $user_id, $addon_id, $priority);
	}

    /**
     * For deleting all logs
     * @author Matthew N. <matthew@realtyna.com>
     * @return boolean
     */
    public static function delete_all_logs()
    {
        return wpl_db::q("DELETE FROM `#__wpl_logs`", 'delete');
    }

	/**
	 * Provide auto-purge capability for logs - Used by cron
	 *
	 * Indeed, this method delete all logs that are out of the period
	 * that specifies with a title to live setting
	 *
	 * @author Noah <noah.s@realtyna.com>
	 * @static
	 * @since WPL 4.13
	 * @date 04/01/2023
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public static function auto_purge()
	{
		$log_auto_purge_status = wpl_global::get_setting('log_auto_purge_status');
		if ($log_auto_purge_status !== 'enable') return false;

		$log_auto_purge_ttl = (int) wpl_global::get_setting('log_auto_purge_ttl');
		if (!$log_auto_purge_ttl) return false;

		wpl_db::q(wpl_db::prepare("DELETE FROM `#__wpl_logs` WHERE section in ('Backend Import', 'MLS Import Images', 'Property', 'MLS Purge', 'WebAPI Import Images', 'IDX Server') and log_date < DATE_SUB(NOW(), INTERVAL %d DAY)", $log_auto_purge_ttl), 'delete');
		wpl_db::q("DELETE FROM `#__wpl_items` WHERE parent_kind = '-1' and item_type = 'security' and creation_date < DATE_SUB(NOW(), INTERVAL 2 DAY)", 'delete');
	}
}