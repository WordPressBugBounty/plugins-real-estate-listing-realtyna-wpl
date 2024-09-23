<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Property types Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 04/9/2013
 * @package WPL
 */
class wpl_property_types
{
    /**
     *
     * @var array
     */
	public $property_types;
	
    /**
     * Removes property type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_type_id
     * @return boolean
     */
	public static function remove_property_type($property_type_id)
	{
        /** trigger event **/
		wpl_global::event_handler('property_type_removed', array('id'=>$property_type_id));
		return wpl_db::delete('wpl_property_types', $property_type_id);
	}
	
    /**
     * Deprecated -> Use wpl_global::get_property_types instead.
     * @author Howard R <howard@realtyna.com>
     * @static
     * @deprecated
     * @param int $property_type_id
     * @return array
     */
	public static function get_property_type($property_type_id)
	{
		return wpl_global::get_property_types($property_type_id);
	}
	
    /**
     * For getting parent id of a property type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_type_id
     * @return int
     */
    public static function get_parent($property_type_id)
    {
        return wpl_db::get('parent', 'wpl_property_types', 'id', $property_type_id);
    }
    
    /**
     * Add a new property type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $parent
     * @param string $name
     * @return int
     */
	public static function insert_property_type($parent, $name)
	{
		$id = wpl_db::insert('wpl_property_types', [
			'parent' => $parent,
			'enabled' => 1,
			'editable' => 2,
			'index' => 0,
			'listing' => 0,
			'name' => $name,
		]);
        
		wpl_db::set('wpl_property_types', $id, 'index', "$id.00");
        
		return $id;
	}
	
    /**
     * Sorts property types
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     * @return int
     */
	public static function sort_property_types($sort_ids)
	{
		$sort_ids = wpl_db::prepare_id_list($sort_ids);
		$property_types = wpl_db::select("SELECT `id`, `index` FROM `#__wpl_property_types` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC", 'loadAssocList');
		
		$counter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		foreach($ex_sort_ids as $ex_sort_id)
		{
            $index = (float) $property_types[$counter]['index'] ? $property_types[$counter]['index'] : $counter;
            
			self::update($ex_sort_id, 'index', $index);
			$counter++;
		}
		
		return $counter;
	}
	
    /**
     * Updates a property type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $id
     * @param string $key
     * @param string $value
     * @return boolean
     */
	public static function update($id, $key, $value = '')
	{
		/** first validation **/
		if(trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set('wpl_property_types', $id, $key, $value);
	}
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_property_types()
	{
		return wpl_global::get_property_types('', 0);
	}
	
    /**
     * Gets property types category
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_property_type_categories()
	{
		return wpl_db::select("SELECT * FROM `#__wpl_property_types` WHERE `parent` = '0' ORDER BY `index` ASC", 'loadAssocList');
	}
    
    /**
     * Checks if a property type has properties or not
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_type_id
     * @return int
     */
	public static function have_properties($property_type_id)
	{
		$res = wpl_db::select(wpl_db::prepare("SELECT count(`id`) as 'id' FROM `#__wpl_properties` WHERE `property_type` = %s", $property_type_id), 'loadAssoc');
        
		return $res['id'];
	}
    
    /**
     * Get Category Data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $category_id
     * @return array
     */
	public static function get_category($category_id)
	{
		return wpl_global::get_property_types($category_id);
	}
    
    /**
     * Add a new category
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $name
     * @return int
     */
	public static function insert_category($name)
	{
		$id = wpl_db::insert('wpl_property_types', [
			'parent' => 0,
			'enabled' => 1,
			'editable' => 2,
			'index' => 0,
			'listing' => 0,
			'name' => $name,
		]);

		wpl_db::set('wpl_property_types', $id, 'index', "$id.00");
        
		return $id;
	}
}