<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

//Fix default charset query wp in WPL
add_filter('pre_get_table_charset', function($charset,$table){
	if(strpos($table, wpl_db::get_DBO()->prefix . 'wpl_' ) === 0){
		$charset = 'utf8mb4';
	}
	return $charset;
},10,2);

/**
 * WPL DB library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 02/10/2013
 * @package WPL
 */
class wpl_db
{
	public static $cachedNullable = [];
	public static $cachedQueries = [];
    public static function q_raw($query, $type = '')
    {
        static::disable_parser();
        $result = wpl_db::q($query, $type);
        static::enable_parser();
        return $result;
    }
    /**
     * Use this function for runnig INSERT, UPDATE and DELETE queries, also set type if you need any result.
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @param string $type
     * @return mixed based on $type parameter
     */
	public static function q($query, $type = '', $use_cache = false)
	{
		/** convert type to lowercase **/
		$type = strtolower($type);
		
		/** call select function if query type is select **/
		if($type == 'select') return wpl_db::select($query, 'loadObjectList', $use_cache);
		
        /** db prefix **/
		$query = self::_prefix($query);
        
		/** db object **/
		$database = self::get_DBO();
		
		if($type == 'insert')
		{
			$result = $database->query($query);
			if($result === false) {
				return false;
			}
			return $database->insert_id;
		}

		return $database->query($query);
	}
	public static function runQuery($query, $type = '')
	{
		$errorMessage = '';
		$result = null;
		try {
			$result = wpl_db::q($query, $type);
			if($result === false) {
				$errorMessage = static::get_DBO()->last_error;
			}
		}catch (Exception $e) {
			$errorMessage = $e->getMessage();
		}

		if(!empty($errorMessage)) {
			wpl_logs::add($errorMessage, 'Query Error', 1, null, null, 1, [substr(static::get_DBO()->last_query, 0, 50)]);
			throw new Exception($errorMessage);
		}

		return $result;
	}

	/**
	 * Using this method you can send an array to insert data into different tables
	 * @author Alfred <alfred@realtyna.com>
	 * @param null $table
	 * @param array $data
	 * @param bool $chack_data
	 * @param string $operator
	 * @return mixed|null
     */
	public static function insert($table = null, $data = array(), $chack_data = false, $operator = 'AND')
	{
		$condition = [];
		$fields = [];
		$values = [];
        
		/** create table fields with value to insert data into table **/
		foreach($data as $field=>$value)
		{
			$fields[] = wpl_db::prepare('%i', $field);
			$values[] = $value === null ? 'null' : wpl_db::prepare('%s', $value);
			$sql_value_condition = $value === null ? ' is null' : wpl_db::prepare(' = %s', $value);
			$condition[] = wpl_db::prepare("%i.%i $sql_value_condition", "#__$table", $field);
		}

		/** if there is a record with these fields and values in the table the record will not be inserted **/
		if($chack_data && !empty($condition) and in_array(strtolower($operator ?? ''), ['and', 'or']))
		{
			$result = wpl_db::select(wpl_db::prepare("SELECT * FROM %i WHERE " . implode(" $operator ", $condition), "#__$table"), 'loadAssoc');
			if(count($result) > 0)
			{
				return $result;
			}
		}

		$fields = implode(',', $fields);
		$values = implode(',', $values);

		return wpl_db::q(wpl_db::prepare("INSERT INTO %i ($fields) VALUES ($values)", "#__$table"), "INSERT");
	}
    
    /**
     * Use this function getting num of result
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @param string $table
     * @return int
     */
	public static function num($query, $table = '')
	{
		if(trim($table ?? '') != '')
		{
			$query = wpl_db::prepare("SELECT COUNT(*) FROM %i", "#__$table");
		}
		
		/** db prefix **/
		$query = self::_prefix($query);
		
		/** db object **/
		$database = self::get_DBO();
		return $database->get_var($query);
	}
    
    /**
     * Use this function for creating query
     * @author Howard <howard@realtyna.com>
     * @param array $vars
     * @param string $needle_str
     * @return string $query
     */
	public static function create_query($vars = NULL, $needle_str = 'sf_')
	{
		if(!$vars)
		{
			$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		}
		
		/** Clean and Escape vars **/
		/** David.M - we use prepare, no need to use this */
		// $vars = wpl_global::clean($vars);
		
		$query = '';
		
		/** this is to include any customized and special form fields conditions **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'create_query';
        $files = wpl_folder::files($path, '.php$');

		$find_files = array();

		/** fields to generate the OR query, of the 'groupor' type exists. */
		$query_or_status = false;
		$query_or_values = array();

		foreach($vars as $key=>$value)
		{
			if(strpos($key ?? '', $needle_str) === false) continue;
			$ex = explode('_', $key);
			
			$format = $ex[1];
			$table_column = str_replace($needle_str.$format.'_', '', $key);
			
			$done_this = false;
			$created_query = apply_filters("wpl_db/create_query/$format", '', $table_column, $value);
			if(!empty($created_query)) {
				$query .= $created_query;
				continue;
			}
			
			/** using detected files **/
			if(isset($find_files[$format]))
			{
				include($path .DS. $find_files[$format]);
				continue;
			}
			
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				if($done_this)
				{
					/** add to detected files **/
					$find_files[$format] = $file;
					break;
				}
			}
		}

		if($query_or_status and !empty($query_or_values))
		{
			$generate_or_query = [];
			foreach($query_or_values as $table_name=>$value) $generate_or_query[] = wpl_db::prepare('%i = %s', $table_name, $value);

			if(!empty($generate_or_query)) {
				$query .= ' AND (' . implode(' OR ', $generate_or_query) . ')';
			}
		}

		return trim($query ?? '', ' ,');
	}

	private static function disable_parser() {
        if(class_exists('wpl_sql_parser')) {
            $sqlParser = wpl_sql_parser::getInstance();
            $sqlParser->disable();
        }
    }

	private static function enable_parser() {
        if(class_exists('wpl_sql_parser')) {
            $sqlParser = wpl_sql_parser::getInstance();
            $sqlParser->enable();
        }
    }

    /**
     * Same with select except it disable sql_parser
     * @param $query
     * @param string $result
     * @return mixed
     */
    public static function select_raw($query, $result = 'loadObjectList')
    {
	    static::disable_parser();
	    $result = wpl_db::select($query, $result);
        static::enable_parser();
        return $result;
    }
    
    /**
     * Use this function for runnig SELECT queries, also you can change type of result if need.
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @param string $result
     * @return mixed
     */
	public static function select($query, $result = 'loadObjectList', $use_cache = false)
	{
		/** db prefix **/
		$query = self::_prefix($query);

		$cacheKey = md5($query . $result);
		if($use_cache and array_key_exists($cacheKey, static::$cachedQueries)) {
			return static::$cachedQueries[$cacheKey];
		}
		
		/** db object **/
		$database = self::get_DBO();

		$start = round(microtime(true) * 1000);
		switch ($result) {
			case 'loadObjectList':
				$return = $database->get_results($query, OBJECT_K);
				break;
			case 'loadObject':
				$return = $database->get_row($query, OBJECT);
				break;
			case 'loadAssocList':
				$return = $database->get_results($query, ARRAY_A);
				break;
			case 'loadAssoc':
				$return = $database->get_row($query, ARRAY_A);
				break;
			case 'loadResult':
				$return = $database->get_var($query);
				break;
			case 'loadColumn':
				$return = $database->get_col($query);
				break;
			default:
				$return = $database->get_results($query, OBJECT_K);
		}
		$end = round(microtime(true) * 1000);
		do_action('wpl_db/select', $query, $start, $end, $result);
		static::$cachedQueries[$cacheKey] = $return;
		return $return;
	}
	
    /**
     * Use this function for running SELECT queries just for 1 record. it creates query automatically.
     * @author Howard <howard@realtyna.com>
     * @param string|string[] $selects
     * @param string $table
     * @param string $field
     * @param string $value
     * @param boolean $return_object
     * @param string $condition
     * @return mixed
     */
	public static function get($selects, $table, $field, $value, $return_object = true, $condition = '', $use_cache = false)
	{
		$fields = '';
		
		if(is_array($selects))
		{
			foreach($selects as $select) $fields .= wpl_db::prepare('%i,', $select);
			$fields = trim($fields ?? '', ' ,');
		}
		else
		{
			$fields = $selects;
		}
		
		if(trim($condition ?? '') == '') $condition = wpl_db::prepare('%i = %s', $field, $value);
		$query = "SELECT $fields FROM `#__$table` WHERE $condition";
		
		/** db prefix **/
		$query = self::_prefix($query);

		$cacheKey = md5('get_' . $query . ($return_object ? '1' : '0'));
		if($use_cache and array_key_exists($cacheKey, static::$cachedQueries)) {
			return static::$cachedQueries[$cacheKey];
		}
		
		/** db object **/
		$database = self::get_DBO();
		
		if($selects != '*' and !is_array($selects)) {
			$return = $database->get_var($query);
		} elseif($return_object) {
			$return = $database->get_row($query);
		} else {
			$return = $database->get_row($query, ARRAY_A);
		}
		static::$cachedQueries[$cacheKey] = $return;
		return $return;
	}
    
    /**
     * Use this function for running DELETE commands
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param int $id
     * @param string $condition
     * @return mixed
     */
	public static function delete($table, $id, $condition = '')
	{
		/** first validation **/
		if( trim($table ?? '') == '' or ( trim($id ?? '') == ''  and trim($condition ?? '') == '' )) return false;

		if(trim($condition ?? '') == '') $condition = wpl_db::prepare(' AND id = %d', $id);
		if(trim($condition) == '') return false;
		
		return wpl_db::q(wpl_db::prepare("DELETE FROM %i WHERE 1 $condition", "#__$table"), 'delete');
	}

	/**
	 * To check if a column is nullable
	 * @return bool|null
	 */
	public static function isNullable(string $table, string $table_column)
	{
		$cacheKey = $table . '_' . $table_column;
		if(!array_key_exists($cacheKey, static::$cachedNullable)) {
			$table_name = wpl_db::_prefix('#__' . $table);
			$isNullable = wpl_db::select(wpl_db::prepare("select is_nullable from information_schema.columns where table_schema = %s and table_name = %s and column_name = %s", DB_NAME, $table_name, $table_column), 'loadResult');
			static::$cachedNullable[$cacheKey] = $isNullable;
		} else {
			$isNullable = static::$cachedNullable[$cacheKey];
		}

		if (empty($isNullable)) {
			return null;
		}
		return strtoupper($isNullable) == 'YES';
	}
    
    /**
     * Using this function you can update one column from some records in a certain table
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param string $where_value
     * @param string $key
     * @param string $value
     * @param string $where_key
     * @return mixed
     */
	public static function set($table, $where_value, $key, $value = '', $where_key = 'id')
	{
		/** first validation **/
		if(trim($table ?? '') == '' or trim($where_value ?? '') == '' or trim($key ?? '') == '' or trim($where_key ?? '') == '') return false;

		return wpl_db::q(wpl_db::prepare('UPDATE %i SET %i = %s WHERE %i = %s', "#__$table", $key, $value, $where_key, $where_value), 'update');
	}
	
    /**
     * For updating some columns from some records in a certain table you can use this function
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param array $params
     * @param string $where_key
     * @param string $where_value
     * @return mixed
     */
	public static function update($table, $params = array(), $where_key = 'id', $where_value = '')
	{
		/** first validation **/
		if(trim($table ?? '') == '' or trim($where_value ?? '') == '' or trim($where_key ?? '') == '' or !is_array($params)) return false;
		if(count($params) == 0) return false;
		
		$update_str = '';
		foreach($params as $field => $value)
		{
			$update_str .= $value === null ? wpl_db::prepare('%i = null, ', $field) : wpl_db::prepare('%i = %s, ', $field, $value);
		}
		
		$update_str = trim($update_str ?? '', ', ');

		return wpl_db::q(wpl_db::prepare("UPDATE %i SET ".$update_str." WHERE %i = %s", "#__$table", $where_key, $where_value ), 'update');
	}
    
    /**
     * Fetch list of table columns or check existence of a column in a table
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param string $column
     * @return mixed
     */
	public static function columns($table = 'wpl_properties', $column = NULL)
	{
		if(trim($table ?? '') == '') return false;

		$results = wpl_db::q(wpl_db::prepare('SHOW COLUMNS FROM %i', "#__$table"), "select", true);
		
		$columns = array();
		foreach($results as $key=>$result) $columns[] = $result->Field;
		
        if(trim($column ?? "") and in_array($column, $columns)) return true;
        elseif(trim($column ?? "")) return false;
        
		return $columns;
	}
	
    /**
     * Use this function for checking existence of a record on a table
     * @author Howard <howard@realtyna.com>
     * @since 1.9.0
     * @param mixed $value
     * @param string $table
     * @param string $column
     * @return int
     */
	public static function exists($value, $table, $column = 'id')
	{
		return self::num(wpl_db::prepare("SELECT COUNT(*) FROM %i WHERE %i = %s", "#__$table", $column, $value));
	}
    
    /**
     * Use this function for escaping any variable
     * @author Howard <howard@realtyna.com>
     * @param mixed $parameter
     * @return mixed
     */
    public static function escape($parameter)
    {
        if (is_null($parameter)) return NULL;
        /** db object **/
        $database = self::get_DBO();
		$wp_version = wpl_global::wp_version();
		
        if(is_array($parameter)) // Added by Kevin for Escape Array Items
        {
			$return_data = array();
			
            foreach($parameter as $key=>$value)
            {
                $return_data[$key] = self::escape($value);
            }
        }
        else
		{
            if(version_compare($wp_version, '3.6', '<')) $return_data = $database->escape($parameter);
			else $return_data = esc_sql($parameter);
		}
        
        return $return_data;
    }
    
    /**
     * Checks for invalid UTF-8, Convert single < characters to entity, strip all tags, remove line breaks, tabs and extra white space, strip octets. 
     * @author Chris <chris@realtyna.com>
     * @param mixed $input
     * @return mixed
     */
	public static function sanitize($input)
	{
		return sanitize_text_field($input);
	}
    
    public static function index_add($column, $table = 'wpl_properties')
    {
        // Get Indexes
        $indexes = wpl_db::indexes($table);
        
        // The index is already available
        if(in_array($column, $indexes)) return true;
        
        // Column is not exists
		if(wpl_db::columns($table, $column)) {
			
			if ( defined( 'REALTYNA_INDEX_MANAGER' ) && REALTYNA_INDEX_MANAGER ){
				
				$tableDetails = wpl_db::select( "SHOW TABLE STATUS WHERE Name = '#__wpl_properties'" , 'loadAssoc' );
				$tableEngine = !empty( $tableDetails['Engine'] ) ? strtolower( $tableDetails['Engine'] ) : 'unknow';
				
				if ( $tableEngine == 'innodb') {
					wpl_db::q(wpl_db::prepare('ALTER TABLE %i ADD INDEX (%i)', "#__$table", $column));
					if ( defined( 'WP_DEBUG' ) &&  WP_DEBUG ) {
						error_log( "WPL : $table.$column Indexed." );
					}
					return true;
				}
				if ( defined( 'WP_DEBUG' ) &&  WP_DEBUG ){
					error_log( "WPL : Adding Index to $table.$column prevented. Please change Table Engine to InnoDB" );
				}
			}else{
				if ( defined( 'WP_DEBUG' ) &&  WP_DEBUG ){
					error_log( "WPL : Adding Index to $table.$column prevented. Please define REALTYNA_INDEX_MANAGER constant in wp-config.php" );
				}
			}
        }
        return false;
	}
    
    public static function index_remove($column, $table = 'wpl_properties')
    {
        $rows = wpl_db::select(wpl_db::prepare('SHOW INDEX FROM %i', "#__$table"), 'loadAssocList');
        
        foreach($rows as $row)
        {
            // Index exists
            if($row['Column_name'] == $column)
            {
                // Drop the index
                wpl_db::q(wpl_db::prepare('ALTER TABLE %i DROP INDEX %i', "#__$table", $row['Key_name']));
                return true;
            }
        }
        
        // Index is not exists
        return false;
    }

    /**
     * Returns indexes of a table
     * @param string $table
     * @param string $key
     * @return array
     */
    public static function indexes($table, $key = 'Column_name')
    {
        $rows = wpl_db::select(wpl_db::prepare('SHOW INDEX FROM %i', "#__$table"), 'loadAssocList');

        $indexes = array();
        foreach($rows as $row) $indexes[] = $row[$key];

        return $indexes;
    }
    
    /**
     * Returns MySQL Version
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
    public static function version()
	{
		return wpl_db::select("SELECT VERSION()", 'loadResult');
	}
    
    /**
     * Use this function for replacing fake prefix with real one
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @return string
     */
	public static function _prefix($query)
	{
		if(empty($query)) {
			return $query;
		}
		$database = self::get_DBO();
		
        if(class_exists('wpl_sql_parser'))
        {
            $sqlParser = wpl_sql_parser::getInstance();
            if($sqlParser->enabled) $query = $sqlParser->parse($query);
            
            $query = str_replace('#__usermeta', $database->base_prefix.'usermeta', $query);
            $query = str_replace('#__users', $database->base_prefix.'users', $query);
            $query = str_replace('#__realtyna_rf_mappings', $database->get_blog_prefix().'realtyna_rf_mappings', $query);
            $query = str_replace('#__blogs', $database->base_prefix.'blogs', $query);
            $query = str_replace('#__wpl', $database->base_prefix.'wpl', $query);
            $query = str_replace('#__', $database->prefix, $query);
        }
        else
        {
            $query = str_replace('#__usermeta', $database->base_prefix.'usermeta', $query);
            $query = str_replace('#__users', $database->base_prefix.'users', $query);
			$query = str_replace('#__realtyna_rf_mappings', $database->get_blog_prefix().'realtyna_rf_mappings', $query);
            $query = str_replace('#__', $database->prefix, $query);
        }
        
		return $query;
	}
	
    /**
     * Use this function for getting database object
     * @author Howard <howard@realtyna.com>
     * @global wpdb $wpdb
     * @return wpdb
     */
	public static function get_DBO()
	{
		global $wpdb;
		return $wpdb;
	}

    public static function prepare(string $query, ...$args)
    {
		return self::get_DBO()->prepare($query, $args);
    }

    public static function esc_like(string $text,string $percent = 'both')
    {
        $text = self::get_DBO()->esc_like($text);

        if ($percent == 'both'){
            return "%$text%";
        }

        if ($percent == 'left'){
            return "%$text";
        }

        if ($percent == 'right'){
            return "$text%";
        }

        return $text;
    }
	public static function prepare_id_list($id_list_with_comma)
	{
		$array = explode(',', $id_list_with_comma ?? '');
		$return = [];
		foreach ($array as $item) {
			$item = trim($item);
			if(!is_numeric($item)) {
				continue;
			}
			$return[] = wpl_db::prepare('%d', $item);
		}
		return implode(',', $return);
	}
}