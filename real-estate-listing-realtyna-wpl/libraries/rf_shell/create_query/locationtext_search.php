<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'locationtextsearch' and !$done_this)
{
	$value = str_replace('#', '', $value);
	$orQuery = [
		'relation' => 'OR',
	];
	$value = str_replace('#', '', $value);
	$valueArray = wpl_locations::getAbbrAndFull($value);
	foreach($valueArray as $valueItem) {
		$orQuery[] = [
			'key' => 'location_text',
			'value' => urlencode($valueItem),
			'compare' => '=',
		];
	}
	$query[] = $orQuery;
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
		$orQuery = [
			'relation' => 'OR',
		];
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
                if($l <= 2 and (strlen($value_raw) == 2 or strlen($value_raw) == 3)) $value_raw = wpl_locations::get_location_name_by_abbr(wpl_db::escape($value_raw), $l);

                $ex_space = explode(' ', $value_raw);
                foreach($ex_space as $value_raw) array_push($values, stripslashes($value_raw ?? ''));
            }

            if(count($values))
            {
                $column = 'textsearch';

                /** Multilingual location text search **/
                if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

				$orInnerQuery = [
					'relation' => 'OR',
				];
				foreach($values as $val)
				{
					$orInnerQuery[] = [
						'key' => $column,
						'value' => $val,
						'compare' => 'contains',
					];
				}
				$orQuery[] = $orInnerQuery;
            }
        }

        $query[] = $orQuery;
	}
    $done_this = true;
}
elseif($format == 'advancedlocationtextsearch' and !$done_this)
{
    $kind = wpl_request::getVar('kind', 0);
    $column = wpl_request::getVar('sf_advancedlocationcolumn', '');
    
    if(($kind == 0 or $kind == 1) and trim($value ?? '') != '')
    {
        $value = wpl_db::escape(stripslashes($value ?? ''));
		$columns = [];
        if($column) // Search based on a specific column
        {
			$columns[] = $column;
        }
        else // Search based on keyword
        {
            $street = 'field_42';
            $location2 = 'location2_name';
            $location3 = 'location3_name';
            $location4 = 'location4_name';
            $location5 = 'location5_name';
            $location6 = 'location6_name';
            if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($street, $kind)) $street = wpl_addon_pro::get_column_lang_name($street, wpl_global::get_current_language(), false);
            $columns = [$street, $location2, $location3, $location4, $location5, $location6, 'location_text', 'zip_name', 'mls_id'];
			$columns = apply_filters('wpl_rf_property/createQuery/advanced_location_columns', $columns, $value);
        }

		$orQuery = [
			'relation' => 'OR',
		];
		$value = str_replace('#', '', $value);
		foreach($columns as $column)
		{
			$valueArray = wpl_locations::getAbbrAndFull($value);
			foreach($valueArray as $valueItem) {
				$orQuery[] = [
					'key' => $column,
					'value' => urlencode($valueItem),
					'compare' => '=',
				];
			}
		}
		$query[] = $orQuery;
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
            $value_raw = wpl_locations::get_location_name_by_abbr(wpl_db::escape($value_raw), 2);
            if(!trim($value_raw)) $value_raw = wpl_locations::get_location_name_by_abbr(wpl_db::escape($value_raw), 1);
        }

        $ex_space = explode(' ', $value_raw);
        foreach($ex_space as $value_raw) array_push($values, stripslashes($value_raw ?? ''));
    }

    if(count($values))
    {
        $column = 'textsearch';

        /** Multilingual location text search **/
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

		$orQuery = [
			'relation' => 'OR',
		];
		foreach($values as $val)
		{
			$orQuery[] = [
				'key' => $column,
				'value' => $val,
				'compare' => 'contains',
			];
		}
		$query[] = $orQuery;
    }

    $done_this = true;
}