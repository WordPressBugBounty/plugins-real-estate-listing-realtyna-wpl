<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'locationtextsearch' and !$done_this)
{
    $kind = wpl_request::getVar('kind', 0);
	$profile = isset($vars['sf_select_finalized']) ? false : true;
	
    $value = stripslashes($value ?? '');
    $values_raw = array_reverse(explode(',', $value));
    
	$values = array();
    
    $l = 0;
	foreach($values_raw as $value_raw)
	{
        $l++;
		if(trim($value_raw ?? '') == '') continue;
        
        $value_raw = trim($value_raw);
        if($l <= 2 and (strlen($value_raw) == 2 or strlen($value_raw) == 3)) $value_raw = wpl_locations::get_location_name_by_abbr($value_raw, $l);
        
        $ex_space = explode(' ', $value_raw);
        foreach($ex_space as $value_raw) array_push($values, stripslashes($value_raw ?? ''));
	}
	
	if(count($values))
	{
		$qqq = array();
        $qq = array();
        
        $column = 'textsearch';
        
        /** Multilingual location text search **/
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

        foreach($values as $val)
        {
            if(strlen($val) < 2) continue;

            $qq[] = wpl_db::prepare('+%s', "LOC-$val");
        } 

        $match_against = implode(' ', $qq);
        
        /** It might be searched by Listing ID **/
        $mls_id_q = '';
		$listing_id =wpl_esc::return_t('Listing ID');
        if(($kind == 0 or $kind == 1) and count($values) == 1 and !$profile) $mls_id_q = wpl_db::prepare('"%1$s %2$s"', $listing_id, $values[0]);
        
        $qqq[] = wpl_db::prepare(" MATCH(%i) AGAINST ( %s IN BOOLEAN MODE ) ", $column, "($match_against) $mls_id_q");
        
        $query .= " AND (".implode(' OR ', $qqq).")";
        if($kind == 0 or $kind == 1) $query .= " AND `show_address`='1'";
	}
	
	$done_this = true;
}
elseif($format == 'multiplelocationtextsearch' and !$done_this)
{
    $kind = wpl_request::getVar('kind', 0);
	$profile = isset($vars['sf_select_finalized']) ? false : true;
	
    $values_raw = explode(':', $value);
	$multiple_values = array();
	
	foreach($values_raw as $value_raw)
	{
		if(trim($value_raw ?? '') != '') array_push($multiple_values, trim($value_raw));
	}
	
	$multiple_values = array_reverse($multiple_values);
    
    if(count($multiple_values))
	{
        $qqqq = array();
        
        foreach($multiple_values as $value)
        {
            $values_raw = array_reverse(explode(',', $value));
    
            $values = array();

            $l = 0;
            foreach($values_raw as $value_raw)
            {
                $l++;
                if(trim($value_raw ?? '') == '') continue;

                $value_raw = trim($value_raw);
                if($l <= 2 and (strlen($value_raw) == 2 or strlen($value_raw) == 3)) $value_raw = wpl_locations::get_location_name_by_abbr($value_raw, $l);
                
                $ex_space = explode(' ', $value_raw);
                foreach($ex_space as $value_raw) array_push($values, stripslashes($value_raw ?? ''));
            }

            if(count($values))
            {
                $qqq = array();
                $qq = array();
                
                $column = 'textsearch';

                /** Multilingual location text search **/
                if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

                foreach($values as $val)
                {
                    if(strlen($val) < 2) continue;
					$qq[] = wpl_db::prepare('+%s', "LOC-$val");
                } 

                $match_against = implode(' ', $qq);

				/** It might be searched by Listing ID **/
				$mls_id_q = '';
				$listing_id =wpl_esc::return_t('Listing ID');
				if(($kind == 0 or $kind == 1) and count($values) == 1 and !$profile) $mls_id_q = wpl_db::prepare('"%1$s %2$s"', $listing_id, $values[0]);

				$qqq[] = wpl_db::prepare(" MATCH(%i) AGAINST ( %s IN BOOLEAN MODE ) ", $column, "($match_against) $mls_id_q");
				$qqqq[] = '('.implode(' OR ', $qqq).')';
            }
        }
        
        $query .= " AND (".implode(' OR ', $qqqq).")";
        if($kind == 0 or $kind == 1) $query .= " AND `show_address`='1'";
	}
    
    $done_this = true;
}
elseif($format == 'advancedlocationtextsearch' and !$done_this)
{
    $kind = wpl_request::getVar('kind', 0);
    $column = wpl_request::getVar('sf_advancedlocationcolumn', '');
    
    if(trim($value ?? '') != '')
    {
        $value = stripslashes($value ?? '');
        
        if($column) // Search based on a specific column
        {
            $query .= wpl_db::prepare(" AND %i = %s AND `show_address` = '1'", $column, $value);
        }
        else // Search based on keyword
        {
			$columns = array_keys(wpl_property::get_suggestion_fields($kind, '') ?? []);
			$columns = array_merge($columns, ['field_308', 'meta_keywords', 'meta_description']);
            $query .= " AND `show_address` = '1' AND (";
            foreach ($columns as $column)
            {
                $query .= wpl_db::prepare("%i LIKE %s OR ", $column, wpl_db::esc_like($value));
            }
            
            $query = rtrim($query, 'OR ').')';
        }
    }
    
    $done_this = true;
}
// new advance location Search structure (search in textsearch field when no columns assigned)
elseif($format == 'advancedlocationtextsearch_v2' and !$done_this)
{
	$kind = wpl_request::getVar('kind', 0);
	$column = wpl_request::getVar('sf_advancedlocationcolumn', '');

	if(trim($value ?? '') != '')
	{
		$value = stripslashes($value ?? '');

		if($column) // Search based on a specific column
		{
			$query .= wpl_db::prepare(" AND %i = %s AND `show_address` = '1'", $column, $value);
		}
		else {
			$normalizedText = wpl_locations::createNormalizedSearchText($value);
			$query .= wpl_db::prepare(" AND `textsearch` LIKE %s ", wpl_db::esc_like($normalizedText));
		}
	}

	$done_this = true;
}
elseif($format == 'mullocationkeys' and !$done_this)
{
    $kind = wpl_request::getVar('kind', 0);

    $value = stripslashes($value ?? '');
    $values_raw = array_reverse(explode(',', $value));

    $values = array();
    foreach($values_raw as $value_raw)
    {
        if(trim($value_raw ?? '') == '') continue;

        $value_raw = trim($value_raw);
        if(strlen($value_raw) == 2 or strlen($value_raw) == 3)
        {
            $value_raw = wpl_locations::get_location_name_by_abbr($value_raw, 2);
            if(!trim($value_raw)) $value_raw = wpl_locations::get_location_name_by_abbr($value_raw, 1);
        }

        $ex_space = explode(' ', $value_raw);
        foreach($ex_space as $value_raw) array_push($values, stripslashes($value_raw ?? ''));
    }

    if(count($values))
    {
        $qq = array();
        $column = 'textsearch';

        /** Multilingual location text search **/
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

        foreach($values as $val)
        {
            if(strlen($val) < 2) continue;

            $qq[] = wpl_db::prepare('%i LIKE %s', $column, wpl_db::esc_like("LOC-$val"));
        }

        $query .= ' AND (' .implode(' OR ', $qq). ')';
        if($kind == 0 or $kind == 1) $query .= ' AND `show_address` = 1 ';
    }

    $done_this = true;
}