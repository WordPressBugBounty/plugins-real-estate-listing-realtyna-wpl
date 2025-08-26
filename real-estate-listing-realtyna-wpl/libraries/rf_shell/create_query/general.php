<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$value = $value ?? '';
$MAX_VALUE = 999999999999;

// We don't support this at all
if($table_column == 'user_id') {
	return;
}
if($format == 'select' and !$done_this)
{
	if($value != '-1' and trim($value ?? '') != '')
    {
        $wplview = wpl_request::getVar('wplview', NULL);

		if($table_column == 'id') {
			$table_column = 'ref_id';
			$value = wpl_db::get('ref_id', 'wpl_properties', 'id', $value);
		}
		$query[] = [
			'key' => $table_column,
			'value' => $value,
			'compare' => '=',
		];
    }
    
	$done_this = true;
}
elseif($format == 'multiselect' and !$done_this)
{
	if($value != '-1' and trim($value ?? '') != '')
    {
		$query[] = [
			'relation' => 'OR',
			[
				'key' => $table_column,
				'value' => $value,
				'compare' => '=',
			],
			[
				'key' => $table_column,
				'value' => $value,
				'compare' => 'LIKE',
			],
		];
    }
    
	$done_this = true;
}
elseif($format == 'tmin' and !$done_this)
{
	if($value != '-1' and trim($value) != '' and intval($value) != 0) {
		$query[] = [
			'key' => $table_column,
			'value' => $value,
			'compare' => '>=',
		];
	}
	$done_this = true;
}
elseif($format == 'tmax' and !$done_this)
{
	if($value != '-1' and trim($value) != '' and intval($value) != $MAX_VALUE) {
		$query[] = [
			'key' => $table_column,
			'value' => $value,
			'compare' => '<=',
		];
	}
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
			$orQuery = [
				'relation' => 'OR',
			];

			$origin_table_column = $table_column;
			foreach ($values_ex as $val) {
				if($origin_table_column == 'id') {
					$table_column = 'ref_id';
					$val = wpl_db::get('ref_id', 'wpl_properties', 'id', $val);
				}
				$orQuery[] = [
					'key' => $table_column,
					'value' => $val,
					'compare' => '=',
				];
			}
			$query[] = $orQuery;
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
			$andQuery = [
				'relation' => 'AND',
			];
			foreach ($values_ex as $val) {
				$andQuery[] = [
					'key' => $table_column,
					'value' => $val,
					'compare' => '<>',
				];
			}
			$query[] = $andQuery;
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
			$orQuery[] = [
				'relation' => 'OR',
			];

            foreach($values_ex as $value_ex)
            {
				$orQuery[] = [
					'relation' => 'OR',
					[
						'key' => $table_column,
						'value' => $value_ex,
						'compare' => '=',
					],
					[
						'key' => $table_column,
						'value' => $value_ex,
						'compare' => 'LIKE',
					],
				];
            }

			$query[] = $orQuery;
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
			$orQuery = [
				'relation' => 'OR',
				[
					'key' => $table_column,
					'value' => null,
					'compare' => '=',
				],
			];
			$andQuery[] = [
				'relation' => 'AND',
			];

            foreach($values_ex as $value_ex)
            {
				$value_ex = trim($value_ex);
				$andQuery[] = [
					[
						'key' => $table_column,
						'value' => $value_ex,
						'compare' => 'NOT LIKE',
					],
					[
						'key' => $table_column,
						'value' => ',' . $value_ex . ',',
						'compare' => 'NOT LIKE',
					],
					[
						'key' => $table_column,
						'value' => $value_ex . ',',
						'compare' => 'NOT LIKE',
					],
					[
						'key' => $table_column,
						'value' => ',' . $value_ex,
						'compare' => 'NOT LIKE',
					],
					[
						'key' => $table_column,
						'value' => ',' . $value_ex . ',',
						'compare' => '<>',
					],

				];
            }
			$orQuery[] = $andQuery;
			$query[] = $orQuery;
        }
	}
	
	$done_this = true;
}
elseif($format == 'text' and !$done_this)
{
	if(trim($value) != '') {
		if($table_column == 'location_text') {
			$value = str_replace('#', '', $value);
			$valueArray = wpl_locations::getAbbrAndFull($value);
			$orQuery = [
				'relation' => 'OR',
			];
			foreach($valueArray as $valueItem) {
				$orQuery[] = [
					'key' => $table_column,
					'value' => urlencode($valueItem),
					'compare' => '=',
				];
			}
			$query[] = $orQuery;
		} else {
			$query[] = [
				'key' => $table_column,
				'value' => $value,
				'compare' => '=',
			];
		}
	}
	$done_this = true;
}
elseif($format == 'between' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
    {
        $ex = explode(':', $value);
        $min = $ex[0] ?? 0;
        $max = $ex[1] ?? null;

		$query[] = [
			'key' => $table_column,
			'value' => $min,
			'compare' => '>=',
		];
        if(!is_null($max)) {
			$query[] = [
				'key' => $table_column,
				'value' => $max,
				'compare' => '<=',
			];
		};
    }
    
	$done_this = true;
}
elseif($format == 'betweenunit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
//		$unit_id = $vars['sf_unit_' . $table_column] ?? 0;
//        $unit_data = wpl_units::get_unit($unit_id);
		
        $ex = explode(':', $value);
        $min = $ex[0] ?? 0;
        $max = $ex[1] ?? 0;
		
//		$si_value_min = $unit_data['tosi'] * $min;
//		$si_value_max = $unit_data['tosi'] * $max;
		
        if($min != 0) {
			$query[] = [
				'key' => $table_column . '_si',
				'value' => $min,
				'compare' => '>=',
			];
		};
		if($max != 0) {
			$query[] = [
				'key' => $table_column . '_si',
				'value' => $max,
				'compare' => '<=',
			];
		}
	}
	
	$done_this = true;
}
elseif($format == 'betweenmmunit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
//		$unit_id = $vars['sf_mmunit_' . $table_column] ?? 0;
//        $unit_data = wpl_units::get_unit($unit_id);
		
        $ex = explode(':', $value);
        $min = $ex[0] ?? 0;
        $max = $ex[1] ?? 0;
		
//		$si_value_min = $unit_data['tosi'] * $min;
//		$si_value_max = $unit_data['tosi'] * $max;

		if($min != 0) {
			$query[] = [
				'key' => $table_column . '_si',
				'value' => $min,
				'compare' => '>=',
			];
		};
		if($max != 0) {
			$query[] = [
				'key' => $table_column . '_max_si',
				'value' => $max,
				'compare' => '<=',
			];
		}
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

			$orQuery = [
				'relation' => 'OR',
			];

            foreach($values_ex as $value_ex) {
				$orQuery[] = [
					'key' => $table_column,
					'value' => $field_values[$value_ex],
					'compare' => '=',
				];
			}
			$query[] = $orQuery;
        }
	}
	
	$done_this = true;
}
elseif($format == 'ptcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select("SELECT `id` FROM `#__wpl_property_types` WHERE LOWER(name)='".strtolower($value)."' AND `parent`='0'", 'loadResult');
        $property_types = wpl_db::select("SELECT `name` FROM `#__wpl_property_types` WHERE `parent`='$category_id'", 'loadColumn');
		if(!empty($property_types)) {
			$orQuery = [
				'relation' => 'OR',
			];
			foreach ($property_types as $property_type) {
				$orQuery[] = [
					'key' => 'property_type',
					'value' => $property_type,
					'compare' => '=',
				];
			}
			$query[] = $orQuery;
		}
	}
	
	$done_this = true;
}
elseif($format == 'ltcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select("SELECT `id` FROM `#__wpl_listing_types` WHERE LOWER(name)='".strtolower($value)."' AND `parent`='0'", 'loadResult');
        $listing_types = wpl_db::select("SELECT `name` FROM `#__wpl_listing_types` WHERE `parent`='$category_id'", 'loadAssocList');
		if(!empty($listing_types)) {
			$orQuery = [
				'relation' => 'OR',
			];
			foreach ($listing_types as $listing_type) {
				$orQuery[] = [
					'key' => 'listing',
					'value' => $listing_type,
					'compare' => '=',
				];
			}
			$query[] = $orQuery;
		}
	}
	
	$done_this = true;
}
elseif($format == 'datemin' and !$done_this)
{
	if(trim($value) != '')
	{
		$value = wpl_render::derender_date($value);
		$query[] = [
			'key' => $table_column,
			'value' => $field_type == 'date' ? $value : $value . 'T00:00:00Z',
			'compare' => '>=',
		];
	}
    
	$done_this = true;
}
elseif($format == 'datemax' and !$done_this)
{
	if(trim($value) != '')
	{
		$value = wpl_render::derender_date($value);
		$query[] = [
			'key' => $table_column,
			'value' => $field_type == 'date' ? $value : $value . 'T00:00:00Z',
			'compare' => '<=',
		];
	}
    
	$done_this = true;
}
elseif($format == 'rawdatemin' and !$done_this)
{
	if(trim($value) != '') {
		$query[] = [
			'key' => $table_column,
			'value' => $field_type == 'date' ? $value : $value . 'T00:00:00Z',
			'compare' => '>=',
		];
	}
	$done_this = true;
}
elseif($format == 'rawdatemax' and !$done_this)
{
	if(trim($value) != '') {
		$query[] = [
			'key' => $table_column,
			'value' => $field_type == 'date' ? $value : $value . 'T00:00:00Z',
			'compare' => '<=',
		];
	}
	$done_this = true;
}
elseif($format == 'gallery' and !$done_this)
{
	if($value != '-1' and trim($value) != '') {
		$query[] = [
			'key' => 'pic_numb',
			'value' => 0,
			'compare' => '>',
		];
	}
	$done_this = true;
}
elseif($format == 'notselect' and !$done_this)
{
	if($value != '-1' and trim($value) != '') {
		if($table_column == 'id') {
			$table_column = 'ref_id';
			$value = wpl_db::get('ref_id', 'wpl_properties', 'id', $value);
		}
		$query[] = [
			'key' => $table_column,
			'value' => $value,
			'compare' => '<>',
		];
	}
	$done_this = true;
}
elseif($format == 'parent' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        /** converts listing id to property id **/
        if($value) $value = wpl_property::pid($value);
		$query[] = [
			'key' => 'parent',
			'value' => $value,
			'compare' => '=',
		];
	}
	
	$done_this = true;
}
elseif($format == 'textsearch' and !$done_this)
{
	if(trim($value) != '')
	{
        /** If the field is multilingual or it is textsearch field **/
        if(wpl_global::check_multilingual_status() and (wpl_addon_pro::get_multiligual_status_by_column($table_column, wpl_request::getVar('kind', 0)) or $table_column == 'textsearch')) $table_column = wpl_addon_pro::get_column_lang_name($table_column, wpl_global::get_current_language(), false);
        
        $values_ex = explode(',', $value);
		$orQuery = [
			'relation' => 'OR',
		];
        
        foreach($values_ex as $value_ex)
        {
            if(trim($value_ex ?? '') == '') continue;
			$orQuery[] = [
				'key' => $table_column,
				'value' => trim($value_ex ?? '', ', '),
				'compare' => '=',
			];
        }
		$query[] = $orQuery;
	}
	
	$done_this = true;
}
elseif($format == 'unit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
//		$unit_data = wpl_units::get_unit($value);
		
        $min = (isset($vars['sf_min_'.$table_column]) and $vars['sf_min_'.$table_column] != '-1') ? (float) str_replace(',', '', $vars['sf_min_'.$table_column]) : 0;
		$max = (isset($vars['sf_max_'.$table_column]) and $vars['sf_max_'.$table_column] != '-1') ? (float) str_replace(',', '', $vars['sf_max_'.$table_column]) : 0;
        
//		$si_value_min = $unit_data['tosi'] * $min;
//		$si_value_max = $unit_data['tosi'] * $max;
		
		if($table_column == 'price') {
			$min = intval($min);
			$max = intval($max);
		}
		if($min > $MAX_VALUE) {
			$min = $MAX_VALUE;
		}
		if($max > $MAX_VALUE) {
			$max = $MAX_VALUE;
		}

		if($max != 0 and $max != $MAX_VALUE) {
			$query[] = [
				'key' => $table_column . '_si',
				'value' => $max,
				'compare' => '<=',
			];
		}
		if($min != 0 and  $min != $MAX_VALUE) {
			$query[] = [
				'key' => $table_column . '_si',
				'value' => $min,
				'compare' => '>=',
			];
		}
	}
	
	$done_this = true;
}
elseif($format == 'mmunit' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
//		$unit_data = wpl_units::get_unit($value);
		
        $min = (isset($vars['sf_min_'.$table_column]) and $vars['sf_min_'.$table_column] != '-1') ? (float) str_replace(',', '', $vars['sf_min_'.$table_column]) : 0;
		$max = (isset($vars['sf_max_'.$table_column.'_max']) and $vars['sf_max_'.$table_column.'_max'] != '-1') ? (float) str_replace(',', '', $vars['sf_max_'.$table_column.'_max']) : 0;
        
//		$si_value_min = $unit_data['tosi'] * $min;
//		$si_value_max = $unit_data['tosi'] * $max;

		if($table_column == 'price') {
			$min = intval($min);
			$max = intval($max);
		}
		if($min > $MAX_VALUE) {
			$min = $MAX_VALUE;
		}
		if($max > $MAX_VALUE) {
			$max = $MAX_VALUE;
		}

		if($max and $max != $MAX_VALUE)
		{
			$query[] = [
				'relation' => 'AND',
				[
					'relation' => 'OR',
					[
						'relation' => 'AND',
						[
							'key' => $table_column . '_si',
							'value' => $min,
							'compare' => '>=',
						],
						[
							'key' => $table_column . '_max_si',
							'value' => $max,
							'compare' => '<=',
						],
					],
					[
						'relation' => 'AND',
						[
							'key' => $table_column . '_si',
							'value' => $min,
							'compare' => '<=',
						],
						[
							'key' => $table_column . '_max_si',
							'value' => $max,
							'compare' => '>=',
						],
					],
					[
						'relation' => 'AND',
						[
							'key' => $table_column . '_si',
							'value' => $min,
							'compare' => '>=',
						],
						[
							'key' => $table_column . '_si',
							'value' => $max,
							'compare' => '<=',
						],
						[
							'key' => $table_column . '_max_si',
							'value' => $max,
							'compare' => '>=',
						],
					],
					[
						'relation' => 'AND',
						[
							'key' => $table_column . '_si',
							'value' => $min,
							'compare' => '<=',
						],
						[
							'key' => $table_column . '_max_si',
							'value' => $min,
							'compare' => '>=',
						],
						[
							'key' => $table_column . '_max_si',
							'value' => $max,
							'compare' => '<=',
						],
					],
				],
			];
		}
		else
		{
			if($max != 0 and $max != $MAX_VALUE) {
				$query[] = [
					'key' => $table_column . '_max_si',
					'value' => $max,
					'compare' => '<=',
				];
			}

			if($min != 0) {
				$query[] = [
					'key' => $table_column . '_si',
					'value' => $min,
					'compare' => '>=',
				];
			}
		}
	}
	
	$done_this = true;
}
elseif($format == 'textyesno' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
    {
        if($value==1) {
			$query[] = [
				'key' => $table_column,
				'value' => $value,
				'compare' => '=',
			];
		}
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
			$orQuery = [
				'relation' => 'OR',
			];
			foreach ($values_ex as $val) {
				$orQuery[] = [
					'key' => $table_column,
					'value' => $val,
					'compare' => '=',
				];
			}
			$query[] = $orQuery;
        }
	}

	$done_this = true;
}
elseif($format == 'mmnumber' and !$done_this)
{
	if($value != '-1' and trim($value) != '') {
		$query[] = [
			'key' => $table_column,
			'value' => $value,
			'compare' => '<=',
		];
		$query[] = [
			'key' => $table_column . '_max',
			'value' => $value,
			'compare' => '>=',
		];
	}
	$done_this = true;
}