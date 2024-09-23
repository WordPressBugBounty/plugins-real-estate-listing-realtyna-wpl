<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$value = $value ?? '';

if($format == 'select' and !$done_this)
{
	if($value != '-1' and trim($value ?? '') != '')
    {
        $wplview = wpl_request::getVar('wplview', NULL);

        // If Multi Agent addon is instaled then filter additional agents column as well
        if($table_column == 'user_id' and wpl_global::check_addon('multi_agents') and !in_array($wplview, array('addon_crm')))
        {
            $query .= wpl_db::prepare(" AND (%i = %s OR `additional_agents` LIKE %s)",[$table_column, $value, wpl_db::esc_like(",$value,")]);
        }
        else $query .= wpl_db::prepare(" AND %i = %s", $table_column, $value);
    }
    
	$done_this = true;
}
elseif($format == 'multiselect' and !$done_this)
{
	if($value != '-1' and trim($value ?? '') != '')
    {
        $query .= wpl_db::prepare(" AND (%i = %s OR %i LIKE %s OR %i LIKE %s OR %i LIKE %s)", $table_column, $value, $table_column, wpl_db::esc_like("$value,",'right'), $table_column, wpl_db::esc_like(",$value",'left'), $table_column, wpl_db::esc_like(",$value,"));
    }
    
	$done_this = true;
}
elseif($format == 'tmin' and !$done_this)
{
	if($value != '-1' and trim($value) != '') $query .= wpl_db::prepare(" AND %i >= %s", $table_column, $value);
	$done_this = true;
}
elseif($format == 'tmax' and !$done_this)
{
	if($value != '-1' and trim($value) != '') $query .= wpl_db::prepare(" AND %i <= %s", $table_column, $value);
	$done_this = true;
}
elseif($format == 'multiple' and !$done_this)
{
	if(!($value == '' or $value == '-1' or $value == ','))
	{
		$value = rtrim($value, ',');
		if($value != '')
        {
            $values_ex = explode(',', $value);
            $value_str = [];
            foreach($values_ex as $value_ex) {
				$value_str[] = wpl_db::prepare('%s', trim($value_ex ?? ''));
			}

			$query .= wpl_db::prepare(" AND %i IN (" . implode(',', $value_str) . ")", $table_column);
        }
	}
	
	$done_this = true;
}
elseif($format == 'notmultiple' and !$done_this)
{
    if(!($value == '' or $value == ','))
    {
        $value = rtrim($value, ',');
        if($value != '')
        {
            $values_ex = explode(',', $value);
            $value_str = [];
            foreach($values_ex as $value_ex) {
				$value_str[] = wpl_db::prepare('%s', trim($value_ex ?? ''));
			}

            $query .= wpl_db::prepare(" AND %i NOT IN (" . implode(',', $value_str) . ")", $table_column);
        }
    }

    $done_this = true;
}
elseif($format == 'multiselectmultiple' and !$done_this)
{
	if(!($value == '' or $value == '-1' or $value == ','))
	{
		$value = rtrim($value, ',');
		if($value != '')
        {
            $values_ex = explode(',', $value);
            $sub_query = [];

            foreach($values_ex as $value_ex)
            {
            	$value_ex = trim($value_ex);
				$sub_query[] = wpl_db::prepare('(%i = %s OR %i LIKE %s OR %i LIKE %s OR %i LIKE %s)', $table_column, $value_ex, $table_column, wpl_db::esc_like("$value_ex,", 'right'), $table_column, wpl_db::esc_like(",$value_ex", 'left'), $table_column, wpl_db::esc_like(",$value_ex,"));
            }
            $query .= " AND (" . implode(' OR ', $sub_query) . ")";
        }
	}
	
	$done_this = true;
}
elseif($format == 'notmultiselectmultiple' and !$done_this)
{
	if(!($value == '' or $value == '-1' or $value == ','))
	{
		$value = rtrim($value, ',');
		if($value != '')
        {
            $values_ex = explode(',', $value);
			$table_column_option = "{$table_column}_options";
            $sub_query = [];

            foreach($values_ex as $value_ex)
            {
            	$value_ex = trim($value_ex);
				$q = [
					wpl_db::prepare('%i NOT LIKE %s', $table_column_option, $value_ex),
					wpl_db::prepare('%i NOT LIKE %s', $table_column_option, wpl_db::esc_like(",$value_ex,")),
					wpl_db::prepare('%i NOT LIKE %s', $table_column_option, wpl_db::esc_like("$value_ex,", 'right')),
					wpl_db::prepare('%i NOT LIKE %s', $table_column_option, wpl_db::esc_like(",$value_ex", 'left')),
					wpl_db::prepare('%i NOT LIKE %s', $table_column_option, ",$value_ex,"),
				];
				$sub_query[] = '(' . implode(' AND ', $q) . ')';
            }
			
            $q = implode(' AND ', $sub_query);
            $query .= wpl_db::prepare(" AND (($q) OR %i IS NULL)", $table_column_option);
        }
	}
	
	$done_this = true;
}
elseif($format == 'text' and !$done_this)
{
	if(trim($value) != '') $query .= wpl_db::prepare(" AND %i LIKE %s", $table_column, wpl_db::esc_like($value));
	$done_this = true;
}
elseif($format == 'between' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
    {
        $ex = explode(':', $value);
        $min = isset($ex[0])? $ex[0] : 0;
        $max = isset($ex[1])? $ex[1] : NULL;
        
        $query .= wpl_db::prepare(" AND %i >= %f", $table_column, $min);
        if(!is_null($max)) $query .= wpl_db::prepare(" AND %i <= %f", $table_column, $max);
    }
    
	$done_this = true;
}
elseif($format == 'betweenunit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$unit_id = isset($vars['sf_unit_'.$table_column]) ? $vars['sf_unit_'.$table_column] : 0;
        $unit_data = wpl_units::get_unit($unit_id);
		
        $ex = explode(':', $value);
        $min = isset($ex[0])? $ex[0] : 0;
        $max = isset($ex[1])? $ex[1] : 0;
		
		$si_value_min = $unit_data['tosi'] * $min;
		$si_value_max = $unit_data['tosi'] * $max;
		
        if($si_value_min != 0) $query .= wpl_db::prepare(" AND %i >= %f", "{$table_column}_si", $si_value_min);
		if($si_value_max != 0) $query .= wpl_db::prepare(" AND %i <= %f", "{$table_column}_si", $si_value_max);
	}
	
	$done_this = true;
}
elseif($format == 'betweenmmunit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$unit_id = isset($vars['sf_mmunit_'.$table_column]) ? $vars['sf_mmunit_'.$table_column] : 0;
        $unit_data = wpl_units::get_unit($unit_id);
		
        $ex = explode(':', $value);
        $min = isset($ex[0])? $ex[0] : 0;
        $max = isset($ex[1])? $ex[1] : 0;
		
		$si_value_min = $unit_data['tosi'] * $min;
		$si_value_max = $unit_data['tosi'] * $max;
		
        if($si_value_min != 0) $query .= wpl_db::prepare(" AND %i >= %f", "{$table_column}_si", $si_value_min);
		if($si_value_max != 0) $query .= wpl_db::prepare(" AND %i <= %f", "{$table_column}_max_si", $si_value_max);
	}
	
	$done_this = true;
}
elseif($format == 'feature' and !$done_this)
{
	if(!($value == '' or $value == '-1' or $value == ','))
	{
        $value = trim($value, ',');
        
		if($value != '')
        {
            $values_ex = explode(',', $value);
            
            $sub_query = [];
            foreach($values_ex as $value_ex) {
				$sub_query[] = wpl_db::prepare("%i LIKE %s", "{$table_column}_options", wpl_db::esc_like(",$value_ex,"));
			}
            $q = implode(' OR ', $sub_query);
            
            $query .= wpl_db::prepare(" AND %i='1' AND (".$q.")", $table_column);
        }
	}
	
	$done_this = true;
}
elseif($format == 'ptcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select(wpl_db::prepare('SELECT `id` FROM `#__wpl_property_types` WHERE name = %s AND `parent` = 0', $value), 'loadResult');
        $property_types = wpl_db::select(wpl_db::prepare('SELECT `id` FROM `#__wpl_property_types` WHERE `parent` = %d', $category_id), 'loadAssocList');
		
        $property_types_str = '';
        if(count($property_types) and $category_id)
        {
            foreach($property_types as $property_type) $property_types_str .= $property_type['id'].',';
            $property_types_str = trim($property_types_str, ', ');
        }
        
        if(trim($property_types_str)) $query .= " AND `property_type` IN ($property_types_str)";
	}
	
	$done_this = true;
}
elseif($format == 'ltcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select(wpl_db::prepare('SELECT `id` FROM `#__wpl_listing_types` WHERE name = %s AND `parent` = 0', $value), 'loadResult');
        $listing_types = wpl_db::select(wpl_db::prepare('SELECT `id` FROM `#__wpl_listing_types` WHERE `parent` = %d', $category_id), 'loadAssocList');
		
        $listing_types_str = '';
        if(count($listing_types) and $category_id)
        {
            foreach($listing_types as $listing_type) $listing_types_str .= $listing_type['id'].',';
            $listing_types_str = trim($listing_types_str, ', ');
        }
        
        if(trim($listing_types_str)) $query .= " AND `listing` IN ($listing_types_str)";
	}
	
	$done_this = true;
}
elseif($format == 'datemin' and !$done_this)
{
	if(trim($value) != '')
	{
		$value = wpl_render::derender_date($value);
		$query .= wpl_db::prepare(' AND DATE(%i) >= %s', $table_column, $value);
	}
    
	$done_this = true;
}
elseif($format == 'datemax' and !$done_this)
{
	if(trim($value) != '')
	{
		$value = wpl_render::derender_date($value);
		$query .= wpl_db::prepare(" AND DATE(%i) <= %s", $table_column, $value);
	}
    
	$done_this = true;
}
elseif($format == 'rawdatemin' and !$done_this)
{
	if(trim($value) != '') $query .= wpl_db::prepare(" AND DATE(%i) >= %s", $table_column, $value);
	$done_this = true;
}
elseif($format == 'rawdatemax' and !$done_this)
{
	if(trim($value) != '') $query .= wpl_db::prepare(" AND DATE(%i) <= %s", $table_column, $value);
	$done_this = true;
}
elseif($format == 'gallery' and !$done_this)
{
	if($value != '-1' and trim($value) != '') $query .= " AND (`pic_numb`>0)";
	$done_this = true;
}
elseif($format == 'notselect' and !$done_this)
{
	if($value != '-1' and trim($value) != '') $query .= wpl_db::prepare(" AND %i != %s", $table_column, $value);
	$done_this = true;
}
elseif($format == 'parent' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        /** converts listing id to property id **/
        if($value) $value = wpl_property::pid($value);
        
		$query .= wpl_db::prepare(" AND `parent` = %d", $value);
	}
	
	$done_this = true;
}
elseif($format == 'textsearch' and !$done_this)
{
	if(trim($value) != '')
	{
        /** If the field is multilingual, or it is textsearch field **/
        if(wpl_global::check_multilingual_status() and (wpl_addon_pro::get_multiligual_status_by_column($table_column, wpl_request::getVar('kind', 0)) or $table_column == 'textsearch')) $table_column = wpl_addon_pro::get_column_lang_name($table_column, wpl_global::get_current_language(), false);
        
        $values_ex = explode(',', $value);
        $qq = array();
        
        foreach($values_ex as $value_ex)
        {
            if(trim($value_ex ?? '') == '') continue;
            $qq[] = wpl_db::prepare("%i LIKE %s", $table_column, wpl_db::esc_like(trim($value_ex ?? '', ', ')));
        }
        
        $query .= " AND (".implode(' OR ', $qq).")";
	}
	
	$done_this = true;
}
elseif($format == 'unit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$unit_data = wpl_units::get_unit($value);
		
        $min = (isset($vars['sf_min_'.$table_column]) and $vars['sf_min_'.$table_column] != '-1') ? (float) str_replace(',', '', $vars['sf_min_'.$table_column]) : 0;
		$max = (isset($vars['sf_max_'.$table_column]) and $vars['sf_max_'.$table_column] != '-1') ? (float) str_replace(',', '', $vars['sf_max_'.$table_column]) : 0;
        
		$si_value_min = $unit_data['tosi'] * $min;
		$si_value_max = $unit_data['tosi'] * $max;
		
		if($si_value_max != 0) $query .= wpl_db::prepare(" AND %i <= %f", "{$table_column}_si", $si_value_max);
		if($si_value_min != 0) $query .= wpl_db::prepare(" AND %i >= %f", "{$table_column}_si", $si_value_min);
	}
	
	$done_this = true;
}
elseif($format == 'mmunit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$unit_data = wpl_units::get_unit($value);
		
        $min = (isset($vars['sf_min_'.$table_column]) and $vars['sf_min_'.$table_column] != '-1') ? (float) str_replace(',', '', $vars['sf_min_'.$table_column]) : 0;
		$max = (isset($vars['sf_max_'.$table_column.'_max']) and $vars['sf_max_'.$table_column.'_max'] != '-1') ? (float) str_replace(',', '', $vars['sf_max_'.$table_column.'_max']) : 0;
        
		$si_value_min = $unit_data['tosi'] * $min;
		$si_value_max = $unit_data['tosi'] * $max;
		$table_column_max_si = "{$table_column}_max_si";
		$table_column_si = "{$table_column}_si";
		if($si_value_max)
		{
			// min [property] max
			$q = wpl_db::prepare("(%i >= %f AND %i <= %f) OR ", $table_column_si, $si_value_min, $table_column_max_si, $si_value_max);

			// [min property max]
			$q .= wpl_db::prepare("(%i <= %f AND %i >= %f) OR ", $table_column_si, $si_value_min, $table_column_max_si, $si_value_max);

			// min [property max]
			$q .= wpl_db::prepare('(%i >= %f AND %i <= %f AND %i >= %f) OR ', $table_column_si, $si_value_min, $table_column_si, $si_value_max, $table_column_max_si, $si_value_max);

			// [min property] max
			$q .= wpl_db::prepare('(%i <= %f AND %i >= %f AND %i <= %f)', $table_column_si, $si_value_min, $table_column_max_si, $si_value_min, $table_column_max_si, $si_value_max);

			$query .= " AND (".$q.")";
		}
		else
		{
			if($si_value_max != 0) $query .= wpl_db::prepare(" AND %i <= %f", $table_column_max_si, $si_value_max);
			if($si_value_min != 0) $query .= wpl_db::prepare(" AND %i >= %f", $table_column_si, $si_value_min);
		}
	}
	
	$done_this = true;
}
elseif($format == 'textyesno' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
    {
        if($value==1) $query .= wpl_db::prepare(" AND (%i IS NOT NULL AND %i !='')", $table_column, $table_column);
	}

	$done_this = true;
}
elseif($format == 'groupor' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$query_or_status = true;
		if(!$query_or_values[$table_column]) $query_or_values[$table_column] = $value;
	}

	$done_this = true;
}
elseif($format == 'restrict' and !$done_this)
{
	if(trim($value) != '')
	{
		$value = rtrim($value, ',');
		if($value != '')
        {
            $values_ex = explode(',', $value);
            $value_str = [];
            foreach($values_ex as $value_ex) {
				$value_str[] = wpl_db::prepare('%s', trim($value_ex ?? ''));
			}
            
            $query .= wpl_db::prepare(" AND %i IN (".implode(',', $value_str).")", $table_column);
        }
	}

	$done_this = true;
}
elseif($format == 'mmnumber' and !$done_this)
{
	if($value != '-1' and trim($value) != '') {
		$query .= wpl_db::prepare(' AND %i <= %f AND %i >= %f', $table_column, $value, "{$table_column}_max", $value);
	}
	$done_this = true;
}