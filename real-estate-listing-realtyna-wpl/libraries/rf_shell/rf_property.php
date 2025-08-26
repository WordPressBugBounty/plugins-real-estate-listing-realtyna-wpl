<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_rf_property
{
	public $total = 0;
	public $searched = false;
	public $query = [];
	public $columns = null;
	private static $instance = null;
	private static $cached = [];

	public static function getInstance() {
		if(!static::$instance) {
			static::$instance = new wpl_rf_property();
		}
		return static::$instance;
	}

	public function setMarkerColumns() {
		$this->columns = apply_filters('wpl_rf_property/setMarkerColumns', [
			'ListingId',
			'ListingKey',
			'ListPrice',
			'Latitude',
			'Longitude',
			'Coordinates',
			'PropertyType',
			'StandardStatus',
		]);
	}

	private function map_listing($listing)
	{
		$cacheKey = "map_listing-$listing";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if(!$found) {
			$query = "SELECT `id` FROM `#__wpl_listing_types` WHERE `parent` <> '0' AND ( id = '" . $listing . "' or `name` = '" . $listing . "')";
			$listing_id = wpl_db::select($query, 'loadResult');
		} else {
			$listing_id = $found;
		}

		if ($listing_id) {
			wp_cache_set($cacheKey, $listing_id, 'wpl_rf_property');
			return $listing_id;
		}
		return wpl_listing_types::insert_listing_type(1, $listing);
	}

	private function map_property_type($property_type)
	{
		$property_type = apply_filters('wpl_rf_property/map_property_type', $property_type);
		$cacheKey = "map_property_type-$property_type";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if(!$found) {
			$query = "SELECT `id` FROM `#__wpl_property_types` WHERE `parent` <> '0' AND ( id = '" . $property_type . "' or `name` = '" . $property_type . "')";
			$property_type_id = wpl_db::select($query, 'loadResult');
		} else {
			$property_type_id = $found;
		}

		if ($property_type_id) {
			wp_cache_set($cacheKey, $property_type_id, 'wpl_rf_property');
			return $property_type_id;
		}
		return wpl_property_types::insert_property_type(1, $property_type);
	}
	public function map_select_type($fieldId, $metaValue)
	{
		$cacheKey = "map_select_type-$fieldId";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if(!$found) {
			$options = wpl_flex::get_field_options($fieldId);
		} else {
			$options = $found;
		}

		if(empty($options['params'])) {
			$options['params'] = [
				['key' => 1, 'value' => $metaValue, 'enabled' => 1]
			];
			wpl_flex::update('wpl_dbst', $fieldId, 'options', json_encode($options));
			return 1;
		}
		$maxId = 0;
		foreach($options['params'] as $item)
		{
			if($metaValue == $item['value']) {
				wp_cache_set($cacheKey, $options, 'wpl_rf_property');
				return $item['key'];
			}
			$maxId = max($maxId, $item['key']) + 1;
		}
		$options['params'][] = ['key' => $maxId, 'value' => $metaValue, 'enabled' => 1];
		wpl_flex::update('wpl_dbst', $fieldId, 'options', json_encode($options));
		wp_cache_delete($cacheKey, 'wpl_rf_property');
		return $maxId;
	}
	private function map_feature_type($fieldId, $metaValue)
	{
		if(!is_array($metaValue)) {
			$metaValue = [$metaValue];
		}

		$cacheKey = "map_feature_type-$fieldId";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if(!$found) {
			$options = wpl_flex::get_field_options($fieldId);
		} else {
			$options = $found;
		}
		if(empty($options)) {
			$options = [];
		}
		$storedValues = empty($options['values']) ? [] : $options['values'];
		$keys = [];
		$changed = false;
		foreach ($metaValue as $value) {
		    $value = trim($value ?? '');
			if(empty($value)) {
				continue;
			}
			if(empty($storedValues)) {
				$storedValues = [
					['key' => 1, 'value' => $value, 'enabled' => 1]
				];
				$keys[] = 1;
				$changed = true;
				continue;
			}
			$maxId = 0;
			$found = false;
			foreach($storedValues as $item)
			{
				if($value == $item['value']) {
					$keys[] = $item['key'];
					$found = true;
					break;
				}
				$maxId = max($maxId, $item['key']) + 1;
			}
			if(!$found) {
				$storedValues[] = ['key' => $maxId, 'value' => $value, 'enabled' => 1];
				$keys[] = $maxId;
				$changed = true;
			}
		}
		if($changed) {
			$options['values'] = $storedValues;
			wpl_flex::update('wpl_dbst', $fieldId, 'options', json_encode($options));
			wp_cache_delete($cacheKey, 'wpl_rf_property');
		} else {
			wp_cache_set($cacheKey, $options, 'wpl_rf_property');
		}

		return implode(',', $keys);
	}
	private function get_property_type_name($property_type_id)
	{
		$query = "SELECT `name` FROM `#__wpl_property_types` WHERE id = '" . $property_type_id . "'";
		return wpl_db::select($query, 'loadResult');
	}
	private function get_listing_type_name($listing_type_id)
	{
		$query = "SELECT `name` FROM `#__wpl_listing_types` WHERE id = '" . $listing_type_id . "'";
		return wpl_db::select($query, 'loadResult');
	}
	private static function normalizeLatLong($latLong)
	{
		$factor = pow(10, 6);
		return intval($latLong * $factor) / $factor;
	}

	public static function render_markers($wpl_properties) {
		$rf_markers = [];
		$listings = wpl_global::return_in_id_array(wpl_global::get_listings());
		$geo_points = [];
		$index = 0;
		$multiple_marker_icon = wpl_global::get_setting('multiple_marker_icon');
		if(trim($multiple_marker_icon ?? '') == '') $multiple_marker_icon = 'multiple.png';
		$advanced_markers_json = wpl_global::get_setting('advanced_markers');
		$advanced_markers = json_decode($advanced_markers_json ?? '', true);
		if(!is_array($advanced_markers)) $advanced_markers = array();
		$advanced_markers_status = false;
		if(isset($advanced_markers['status']) and $advanced_markers['status']) $advanced_markers_status = true;

		$icons = wpl_global::get_property_type_icons();

		foreach($wpl_properties as $key => $wpl_property) {
			if($key == 'current' and !count($wpl_property)) continue;
			if(empty($wpl_property['googlemap_lt'])) {
				continue;
			}
			$lat = static::normalizeLatLong($wpl_property['googlemap_lt']);
			$long = static::normalizeLatLong($wpl_property['googlemap_ln']);
			$lat_long =  $lat .','. $long;
			if(isset($geo_points[$lat_long]))
			{
				$j = $geo_points[$lat_long];
				$rf_markers[$j]['pids'] .= ',' . $wpl_property['id'];
				$rf_markers[$j]['gmap_icon'] = $multiple_marker_icon;
				if($advanced_markers_status) $rf_markers[$j]['advanced_marker'] = '<img src="'.wpl_global::get_wpl_asset_url('img/listing_types/gicon/'.$multiple_marker_icon).'">';
				continue;
			}
			$rf_marker = [
				'id' => $wpl_property['id'],
				'googlemap_lt' => $lat,
				'googlemap_ln' => $long,
				'title' => wpl_render::render_price($wpl_property['price'], $wpl_property['price_unit'], '', wpl_global::wpl_minimize_price($wpl_property['price'])),
				'pids' => $wpl_property['id'] . '',
				'gmap_icon' => $listings[$wpl_property['listing']]['gicon'] ?? 'default.png',
			];
			if($advanced_markers_status)
			{
				$color = (isset($advanced_markers['listing_types'][$wpl_property['listing']])) ? $advanced_markers['listing_types'][$wpl_property['listing']] : '#333333';
				$icon = (isset($advanced_markers['property_types'][$wpl_property['property_type']])) ? $advanced_markers['property_types'][$wpl_property['property_type']] : 'residential.svg';
				$icon_url = '';
				foreach ($icons as $icon_item) {
					if($icon_item['icon'] == $icon) {
						$icon_url = $icon_item['url'];
						break;
					}
				}

				$rich_marker = '<div class="wpl-richmarker-wp" style="color: '.$color.';">';
				$rich_marker .= '<div class="wpl-richmarker-icon"><img src="'. $icon_url .'"></div>';
				$rich_marker .= '</div>';

				$rf_marker['advanced_marker'] = $rich_marker;
			}
			$rf_markers[$index] = apply_filters('wpl_property/render_markers', $rf_marker, $wpl_property);
			$geo_points[$lat_long] = $index;
			$index++;
		}
		return apply_filters('wpl_property/render_markers/all', array_values($rf_markers), $wpl_properties);
	}

	public function createQuery($where, $needle_str = 'sf_') {
		$this->query = $this->getQuery($where, $needle_str);
	}
	private function getQuery($where, $needle_str = 'sf_') {
		unset($where['sf_select_confirmed']);
		unset($where['sf_select_finalized']);
		unset($where['sf_select_deleted']);
		unset($where['sf_select_expired']);
		unset($where['sf_select_kind']);

		$path = __DIR__ . DS . 'create_query';
		$files = wpl_folder::files($path, '.php$');

		$find_files = [];
		$query = [];
		if(!empty($where['sf_tmin_googlemap_lt']) and ($where['sf_polygonsearch'] ?? '') != '1') {
			$where['sf_polygonsearch'] = 1;
			$where['sf_polygonsearchpoints'] = '[' . implode(';', [
				$where['sf_tmin_googlemap_lt'] . ',' . $where['sf_tmin_googlemap_ln'],
				$where['sf_tmax_googlemap_lt'] . ',' . $where['sf_tmin_googlemap_ln'],
				$where['sf_tmax_googlemap_lt'] . ',' . $where['sf_tmax_googlemap_ln'],
				$where['sf_tmin_googlemap_lt'] . ',' . $where['sf_tmax_googlemap_ln'],
				$where['sf_tmin_googlemap_lt'] . ',' . $where['sf_tmin_googlemap_ln'],
				]) . ';]';
			unset($where['sf_tmin_googlemap_lt']);
			unset($where['sf_tmax_googlemap_lt']);
			unset($where['sf_tmin_googlemap_ln']);
			unset($where['sf_tmax_googlemap_ln']);
		}
        $where = apply_filters('wpl_rf_property/createQuery/where', $where);
		$vars = $where;
		foreach($where as $key => $value)
		{
			if(strpos($key ?? '', $needle_str) === false) continue;
			$value = trim($value ?? '');
			if(strlen($value) == 0) {
				continue;
			}

			if($key == 'sf_on_the_fly') {
				// to fix escaping quotes in URL
				$value = implode("",explode("\\",$value));
				$value = stripslashes(trim($value));
				$query[] =[
					'key' => 'raw',
					'value' => "($value)",
					'compare' => '=',
				];
				continue;
			}

			$ex = explode('_', $key);

			$format = $ex[1];
			$table_column = str_replace($needle_str.$format.'_', '', $key);
			$field_values = [];

			$field_id = wpl_flex::get_dbst_id($table_column);
			$field_type = wpl_flex::get_dbst_key('type', $field_id);

			if($this->endsWith($key, '_property_type')) {
				if(strpos($value, ',') !== false) {
					$values = explode(',', $value);
					$correct_values = [];
					foreach ($values as $value_item) {
						if(intval($value_item)) {
							$correct_values[] = $this->get_property_type_name($value_item);
						} else {
							$correct_values[] = $value_item;
						}

					}
					$value = implode(',', $correct_values);
				} else {
					if(intval($value)) {
						$value = $this->get_property_type_name($value);
					}
				}
			}
			elseif($this->endsWith($key, '_listing')) {
				if(($value == 9 || $value == 10)) {
					$orQuery = [
						'relation' => $value == 10 ? 'OR' : 'AND',
					];
					$property_types = ['Residential Lease', 'Commercial Lease', 'Rental'];
					foreach ($property_types as $property_type) {
						$orQuery[] = [
							'key' => 'property_type',
							'value' => $property_type,
							'compare' => $value == 10 ? '=' : '!=',
						];
					}

					$query[] = $orQuery;
				}
				continue;
			}
			elseif($format == 'feature') {
				$field_options = wpl_flex::get_field_options($field_id);
				foreach ($field_options['values'] as $field_option) {
					$field_values[$field_option['key']] = $field_option['value'];
				}
			} else {
    			$flex_field_options = wpl_flex::get_field_options($field_id);

    			// feature
    			if(!empty($flex_field_options['values'])) {
					$values = explode(',', $value);
					$correct_values = [];
					foreach ($flex_field_options['values'] as $value_item) {
					    foreach ($values as $value_selected_item) {
					        if($value_item['key'] == $value_selected_item) {
						        $correct_values[] = $value_item['value'];
					        }
					    }
					}
					if(!empty($correct_values)) {
						$value = implode(',', $correct_values);
					}

    			}

    			// select
    			if(!empty($flex_field_options['params'])) {
					$values = explode(',', $value);
					$correct_values = [];
					foreach ($flex_field_options['params'] as $value_item) {
					    foreach ($values as $value_selected_item) {
					        if($value_item['key'] == $value_selected_item) {
						        $correct_values[] = $value_item['value'];
					        }
					    }
					}
					if(!empty($correct_values)) {
						$value = implode(',', $correct_values);
					}
				}
			}


			$done_this = false;
			$created_query = apply_filters("wpl_rf_property/createQuery/$format", [], $table_column, $value, $vars);
			if(!empty($created_query)) {
				$query[] = $created_query;
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

		return apply_filters('wpl_rf_property/createQuery/query', $query, $where);
	}

	static function startsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		return substr( $haystack, 0, $length ) === $needle;
	}
	static function endsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		if( !$length ) {
			return true;
		}
		return substr( $haystack, -$length ) === $needle;
	}

	public function load_meta_mapping($mapping) {
		$fields = [];
		foreach ($mapping as $metaKey => $metaValue) {
			if(static::startsWith($metaKey, 'wpl_') === false) {
				continue;
			}
			$metaKey = substr($metaKey, 4);
			$fields[$metaKey] = $metaValue;
		}

		$flexCacheKey = "load_meta_mapping";
		$found = wp_cache_get($flexCacheKey, 'wpl_rf_property');
		if(!$found) {
			$existColumns = wpl_db::select("SELECT table_column FROM `#__wpl_dbst` WHERE table_column in ('" . implode("','", array_keys($fields)) . "')", 'loadColumn');
			wp_cache_set($flexCacheKey, $existColumns, 'wpl_rf_property');
		} else {
			$existColumns = $found;
		}

		$existColumns = array_merge($existColumns, ['kind', 'pic_numb', 'confirmed', 'finalized', 'deleted', 'user_id', 'expired', 'source']);

		$namedColumns = [
			'field_2111' => 'Office Name',
			'field_2112' => 'Agent Name',
		];

		$cacheKey = "load_meta_mapping-categories";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if(!$found) {
			$db_categories = wpl_flex::get_categories(0);
			wp_cache_set($cacheKey, $db_categories, 'wpl_rf_property');
		} else {
			$db_categories = $found;
		}

		$categories = [];
		foreach ($db_categories as $db_category) {
			$categories[$db_category->name] = $db_category->id;
		}

		foreach ($fields as $field => $fieldOptions) {
			if(in_array($field, $existColumns) || $field == 'zip_id' || static::endsWith($field, '_si') || static::endsWith($field, '_unit')) {
				continue;
			}
			if(static::startsWith($field, 'location') && static::endsWith($field, '_name')) {
				continue;
			}
			$fieldName = ucwords(str_replace('_', ' ', $field));
			if(!empty($namedColumns[$field])) {
				$fieldName = $namedColumns[$field];
			}
			$category = 1;
			if(!empty($fieldOptions['category'])) {
				$categoryName = $fieldOptions['category'];
				if(empty($categories[$categoryName])) {
					$query = "INSERT INTO `#__wpl_dbcat` (`name`,`prefix`,`kind`) VALUES ('{$categoryName}','RF','0')";
					$categories[$categoryName] = wpl_db::q($query, 'insert');
					wp_cache_delete($cacheKey, 'wpl_rf_property');
				}
				$category = $categories[$categoryName];
			}
			// to make sure another process didn't create it
			if(wpl_flex::get_dbst_id($field, 0)) {
				continue;
			}
			$type = 'text';
			if(!empty($fieldOptions['return_type'])) {
				if($fieldOptions['return_type'] == 'array') {
					$type = 'feature';
				} else {
					$type = $fieldOptions['return_type'];
				}
			}

			$data = [
				'id' => wpl_flex::get_new_dbst_id(),
				'category' => $category,
				'type' => $type,
				'kind' => 0,
				'table_name' => 'wpl_properties',
				'table_column' => $field,
				'name' => $fieldName,
				'pshow' => 1,
				'pdf' => 1,
				'is_fly' => 1,
			];
			wpl_db::insert('wpl_dbst', $data);
			wp_cache_delete($flexCacheKey, 'wpl_rf_property');
		}
		return $mapping;
	}

	public function rf_after_mapping($posts)
	{
		$cacheKey = "rf_after_mapping";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if(!$found) {
			$fields = wpl_db::select("SELECT id, table_column, `type` from `#__wpl_dbst` where kind = 0 and `type` in ('select', 'feature', 'listings', 'property_types')", 'loadAssocList');
			wp_cache_set($cacheKey, $fields, 'wpl_rf_property');
		} else {
			$fields = $found;
		}

		$specialFields = [];
		foreach ($fields as $field) {
			$specialFields[$field['table_column']] = $field;
		}
		foreach ($posts as &$post) {
			if(empty($post->meta_data)) {
				continue;
			}
			foreach ($post->meta_data as $metaKey => $metaValue) {
				if(strpos($metaKey, 'wpl_') !== 0) {
					continue;
				}
				$newKey = substr($metaKey, 4); // remove wpl_ prefix
				if(in_array($newKey, array_keys($specialFields))) {

					$field = $specialFields[$newKey];
					if($field['type'] == 'listings') {
						$post->meta_data[$newKey] = $this->map_listing($post->meta_data[$metaKey]);
						continue;
					}
					else if($field['type'] == 'property_types') {
						$post->meta_data[$newKey] = $this->map_property_type($post->meta_data[$metaKey]);
						continue;
					}
					else if($field['type'] == 'select' && strlen($post->meta_data[$metaKey] ?? '') > 0) {
						$post->meta_data[$newKey] = $this->map_select_type($field['id'], $post->meta_data[$metaKey]);
						continue;
					}
					else if($field['type'] == 'feature') {
						if(is_string($post->meta_data[$metaKey])) {
							$post->meta_data[$metaKey] = explode(',', $post->meta_data[$metaKey]);
						}
						$post->meta_data[$newKey] = $this->map_feature_type($field['id'], $post->meta_data[$metaKey]);

						if(strlen($post->meta_data[$newKey] ?? '') > 0) {
							$post->meta_data[$newKey . '_options'] = $post->meta_data[$newKey];
							$post->meta_data[$newKey] = 1;
						}else {
						    $post->meta_data[$newKey] = 0;
						}
						continue;
					}
				}
				if(is_array($post->meta_data[$metaKey])) {
				    $post->meta_data[$newKey] = implode(', ', $post->meta_data[$metaKey]);
				} else {
					$post->meta_data[$newKey] = $post->meta_data[$metaKey];
				}
			}
			$post->meta_data['zip_id'] = $post->meta_data['zip_id'] ?? null;
			$post->meta_data['sp_openhouse'] = $post->meta_data['sp_openhouse'] ?? null;
			$post->meta_data['sp_forclosure'] = $post->meta_data['sp_forclosure'] ?? null;
			$post->meta_data['sp_featured'] = $post->meta_data['sp_featured'] ?? null;
			$post->meta_data['sp_hot'] = $post->meta_data['sp_hot'] ?? null;
			$post->meta_data['meta_keywords'] = $post->meta_data['meta_keywords'] ?? '';
			$post->meta_data['show_internet'] = $post->meta_data['show_internet'] ?? 1;
			$post->meta_data['kind'] = $post->meta_data['kind'] ?? 0;
			$post->meta_data['finalized'] = 1;
			$post->meta_data['confirmed'] = 1;
			$post->meta_data['deleted'] = 0;
			$post->meta_data['expired'] = 0;
			$mls_id = $post->meta_data['mls_id'];
			$ref_id = $post->meta_data['ref_id'];
			$source = $post->meta_data['source'];
			$kind = $post->meta_data['kind'];
			$rfData = (array)$post->meta_data['realty_feed_raw_data'];
			if (empty($kind)) {
				$kind = 0;
				$post->meta_data['kind'] = 0;
			}
			$saved_property = wpl_db::select("SELECT * FROM `#__wpl_properties` WHERE kind = '$kind' and mls_id = '$mls_id' and `source` = '$source'", 'loadAssoc');
			if (empty($saved_property)) {
				$default_user_id = wpl_settings::get('rf_default_user');

				if(empty($default_user_id)) {
					$default_user_id = 0;
				}

				$property_id = wpl_property::create_property_default(1, $kind);

				$property = [
					'kind' => $kind,
					'mls_id' => $mls_id,
					'user_id' => $default_user_id,
					'ref_id' => $ref_id,
					'source' => $source,
					'deleted' => 0,
					'expired' => 0,
					'confirmed' => 1,
					'finalized' => 1,
				];

				$property = apply_filters('wpl_rf_property/rf_after_mapping/property', $property, $rfData, $property_id);

				wpl_db::update('wpl_properties', $property, 'id', $property_id);
				// for multisites
				if(wpl_global::is_multisite()) {
					wpl_db::update('wpl_properties', ['source_blog_id' => wpl_global::get_current_blog_id(),], 'id', $property_id);
				}
			} else {
				$property_id = $saved_property['id'];
				$this->update_existing_property($property_id, $saved_property, $ref_id, $rfData);
			}
			$post->meta_data['_wpl_id'] = intval($property_id);
			$post->meta_data['id'] = intval($property_id);
			$post->meta_data['show_address'] = 1;
		}

		return $posts;
	}

	public function getOpenDates($refId) {
		if(empty($refId)) {
			return [];
		}
		$cacheKey = "wpl-openhouse-$refId";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if($found) {
			return $found;
		}
		try {
			$items = $this->doRfRequest('OpenHouse', [
				['method' => 'where', 'field' => 'ListingKey', 'operator' => 'eq', 'value' => $refId]
			]);

			usort($items, function ($a, $b) {
				$dateA = DateTime::createFromFormat('Y-m-d H:i:s', $a->OpenHouseDate.' '.$a->OpenHouseStartTime);
				$dateB = DateTime::createFromFormat('Y-m-d H:i:s', $b->OpenHouseDate.' '.$b->OpenHouseStartTime);
				return $dateA <=> $dateB;
			});
			$openDates = [];
			foreach ($items as $key => $open_row) {
				$open_row = apply_filters('wpl_rf_property/getOpenDates/openDateRaw', $open_row);
				$openDates[] = (object)[
					'item_extra3' => '',
					'item_name' => $open_row->OpenHouseDate,
					'item_extra2' => 'Start time: ' . $open_row->OpenHouseStartTime . ' - ' . 'End time: ' . $open_row->OpenHouseEndTime,
					'id' => 0 - $key,
					'raw' => $open_row,
				];
			}
			wp_cache_set($cacheKey, $openDates, 'wpl_rf_property');
			return $openDates;
		} catch (Exception $e) {
			return [];
		}

	}

	public function fetch_agent_info($property) {
		if(!empty($property['list_office_mls_id']) && empty($property['field_2111'])) {
			$property['RF_Office'] = $this->getOffice($property['list_office_mls_id']);
			if(!empty($property['RF_Office'])) {
				$property['field_2111'] = $property['RF_Office']['OfficeName'];
			}
		}
		if(!empty($property['list_agent_mls_id']) && empty($property['field_2112'])) {
			$property['RF_Agent'] = $this->getAgent($property['list_agent_mls_id']);
			if(!empty($property['RF_Agent'])) {
				$property['field_2112'] = $property['RF_Agent']['MemberFullName'];
			}

		}

		return $property;
	}

	public function doRfRequest($entity, $where) {
		$rf = \Realtyna\MlsOnTheFly\Boot\App::get(\Realtyna\MlsOnTheFly\Components\CloudPost\SubComponents\RFClient\SDK\RF\RF::class);
		$RFQuery = new \Realtyna\MlsOnTheFly\Components\CloudPost\SubComponents\RFClient\SDK\RF\RFQuery();
		$RFQuery->set_entity($entity);
		$RFQuery->set_select(['ALL']);
		foreach ($where as $whereItem) {
			$RFQuery->add_filter($whereItem['method'], $whereItem['field'], $whereItem['operator'], $whereItem['value']);
		}
		$filterCallback = function ($default) {
			return [];
		};
		add_filter('pre_option_realtyna_rf_shell_global_filters', $filterCallback);
		$RFResponse = $rf->get($RFQuery);
		remove_filter('pre_option_realtyna_rf_shell_global_filters', $filterCallback);
		return $RFResponse->items;
	}

	public function getAgent($agentMlsId) {
		if(empty($agentMlsId)) {
			return [];
		}
		try {
			$items = $this->doRfRequest('Member', [
				['method' => 'where', 'field' => 'MemberMlsId', 'operator' => 'eq', 'value' => $agentMlsId]
			]);
			if(empty($items)) {
				return [];
			}
			return (array) $items[0];
		} catch (Exception $e) {
			return [];
		}
	}

	public function getOffice($officeMlsId) {
		if(empty($officeMlsId)) {
			return [];
		}
		try {
			$items = $this->doRfRequest('Office', [
				['method' => 'where', 'field' => 'OfficeMlsId', 'operator' => 'eq', 'value' => $officeMlsId]
			]);
			if(empty($items)) {
				return [];
			}
			return (array) $items[0];
		} catch (Exception $e) {
			return [];
		}
	}

	public function parse_post_meta($post_id, $return_array = false)
	{
		if(is_object($post_id)) {
			$row = $post_id->meta_data;
			$rawData = $row['realty_feed_raw_data'];
			$row['googlemap_lt'] = $rawData->Latitude;
			$row['googlemap_ln'] = $rawData->Longitude;
			$row['mls_id'] = $rawData->ListingId;
			$row['ref_id'] = $rawData->ListingKey;
			$row['price'] = $rawData->ListPrice;
			$row['price_si'] = $rawData->ListPrice;
			$row['price_unit'] = 260;
			$row['kind'] = 0;
			$saved_property = wpl_db::select("SELECT * FROM `#__wpl_properties` WHERE kind = '0' and mls_id = '{$rawData->ListingId}' and `source` = 'RF'", 'loadAssoc');
			if(!$saved_property) {
				$default_user_id = wpl_settings::get('rf_default_user');

				if(empty($default_user_id)) {
					$default_user_id = 0;
				}

				$property_id = wpl_property::create_property_default(1, 0);

				$property = [
					'kind' => 0,
					'mls_id' => $row['mls_id'],
					'user_id' => $default_user_id,
					'ref_id' => $row['ref_id'],
					'source' => 'RF',
					'deleted' => 0,
					'expired' => 0,
					'confirmed' => 1,
					'finalized' => 1,
				];

				$property = apply_filters('wpl_rf_property/rf_after_mapping/property', $property, (object) $row, $property_id);

				wpl_db::update('wpl_properties', $property, 'id', $property_id);
			} else {
				$property_id = $saved_property['id'];
				$this->update_existing_property($property_id, $saved_property, $row['ref_id'], $row);
			}
			$row['_wpl_id'] = $property_id;
			$row['id'] = $property_id;
			$row = apply_filters('wpl_rf_property/parse_post_meta', (object) $row, [], $this);
			return $return_array ? (array)$row : $row;
		}
		$meta = get_post_meta($post_id);
		if (empty($meta)) {
			return $return_array ? [] : null;
		}
		$row = new stdClass();
		$row->post_id = $post_id;
		foreach ($meta as $meta_key => $meta_value) {
			if(!empty($meta_value)) {
			    if(count($meta_value) == 1) {
					$row->{$meta_key} = $meta_value[0];
			    } else {
			        $row->{$meta_key} = $meta_value;
			    }
			}
		}
		if(empty($row->id)) {
			$row->id = $row->_wpl_id;
		}
		$row = apply_filters('wpl_rf_property/parse_post_meta', $row, $meta, $this);
		return $return_array ? (array)$row : $row;

	}

	private function update_existing_property($property_id, $saved_property, $ref_id, $rfData) {
		if(!empty($ref_id) && (empty($saved_property['ref_id']) || $ref_id != $saved_property['ref_id'])) {
			wpl_db::set('wpl_properties', $property_id, 'ref_id', $ref_id);
		}
		$update = apply_filters('wpl_rf_property/rf_after_mapping/property/update', [], $saved_property, $rfData);

		$default_user_id = wpl_settings::get('rf_default_user');
		if(empty($update['user_id']) and empty($saved_property['user_id']) and !empty($default_user_id)) {
			$update['user_id'] = $default_user_id;
		}
		if(!empty($update)) {
			wpl_db::update('wpl_properties', $update, 'id', $property_id);
		}
		return $update;
	}

	public function setting_changed($name, $value, $old_value) {
		if($name == 'rf_default_user' && !empty($old_value)) {
			wpl_db::q("UPDATE `#__wpl_properties` SET user_id = $value WHERE user_id = $old_value and `source` = 'RF'");
		}
	}

	private function rf_query($args)
	{
		add_filter('realtyna_rf_shell_cloud_posts', [$this, 'rf_after_mapping'], 99);
		if (empty($args['post_type'])) {
			$args['post_type'] = 'wpl_property';
		}
		if(!empty($this->columns)) {
			$max_records = 2000;
			$args['posts_per_page'] = intval(wpl_request::getVar('limit', $max_records));
			if($args['posts_per_page'] < 1 || $args['posts_per_page'] > $max_records) {
				$args['posts_per_page'] = $max_records;
			}
			$args['rf_select_fields'] = $this->columns;
		}
		$args['ignore_sticky_posts'] = true;
		$args = apply_filters( 'wpl_rf_property/rf_query', $args );
		return new WP_Query( $args );
	}

	public function get_cloud_post_id($property_id)
	{
		$cacheKey = "get_cloud_post_id-$property_id";
		$found = wp_cache_get($cacheKey, 'wpl_rf_property');
		if($found) {
			return $found;
		}
		if(wpl_global::is_multisite())
		{
			$fs = wpl_sql_parser::getInstance();
			$fs->disable();
		}
		$ref_id = wpl_db::select("SELECT ref_id from `#__wpl_properties` WHERE id = '$property_id'", 'loadResult');
		if(empty($ref_id)) {
			return null;
		}
		$result = wpl_db::select("SELECT post_id from `#__realtyna_rf_mappings` WHERE listing_key = '$ref_id'", 'loadResult');
		wp_cache_set($cacheKey, $result, 'wpl_rf_property');
		if(wpl_global::is_multisite())
		{
			$fs = wpl_sql_parser::getInstance();
			$fs->enable();
		}
		return $result;
	}

	public function search($orderBy, $order, $start, $limit)
	{
		if(empty($limit)) {
			$limit = 10;
		}

		if($limit > 500) {
			$limit = 500;
		}
		$orderBy = str_replace('`', '', $orderBy);

		$args = array(
			"post_type" => 'wpl_property',
			"paged" => ($start / $limit ),
			"posts_per_page" => $limit,
			'orderby' => str_replace('p.', '', $orderBy),
			'order' => $order,
			'meta_query' => [],
		);
		if(!empty($this->query)) {
			$args['meta_query'] = $this->query;
		}
		$result = $this->rf_query($args);
		$this->total = $result->found_posts;
		$this->searched = true;
		$properties = [];
		foreach ($result->posts as $property) {
			$cloud_property = $this->parse_post_meta(!empty($this->columns) ? $property : $property->ID);
			$correct_property = (object) $this->merge_fields($property->_wpl_id, (array) $cloud_property);
			$correct_property->id = $property->_wpl_id;
			$properties[] = $correct_property;
		}
		return $properties;
	}

	public function get_raw_data($orderBy, $order, $start, $limit) {
		$rf = \Realtyna\MlsOnTheFly\Boot\App::get(\Realtyna\MlsOnTheFly\Components\CloudPost\SubComponents\RFClient\SDK\RF\RF::class);
		$args = array(
			"post_type" => 'wpl_property',
			"paged" => ($start / $limit ),
			"posts_per_page" => $limit,
			'orderby' => str_replace('p.', '', $orderBy),
			'order' => $order,
			'meta_query' => [],
			'rf_params' => [
				'clusters' => 25
			],
			'inject' => false
		);
		if(!empty($this->query)) {
			$args['meta_query'] = $this->query;
		}
		$query = $this->rf_query($args);
		return $rf->get($query);
	}

	private function merge_fields($property_id, $cloud_data) {
		$local = $this->get_local_property($property_id);
		if(!empty($cloud_data['googlemap_lt'])) {
			unset($local['googlemap_lt']);
		}
		if(!empty($cloud_data['googlemap_ln'])) {
			unset($local['googlemap_ln']);
		}
		$return = array_merge($cloud_data, $local);
		return apply_filters('wpl_rf_property/merge_fields', $return, $property_id);
	}

	private function get_local_property($propertyId) {
		$result = wpl_db::select('select id, user_id, googlemap_ln, googlemap_lt from `#__wpl_properties` where id = ' . $propertyId, 'loadAssoc', true);
		if(empty($result)) {
			return [];
		}
		if($result['googlemap_ln'] == 0 || $result['googlemap_lt'] == 0) {
			unset($result['googlemap_lt']);
			unset($result['googlemap_ln']);
		}
		// to remove columns that have no value
		return array_filter($result);
	}

	private function import_missing_listing($property_id) {
		$values = wpl_db::get(['mls_id', 'source', 'ref_id'], 'wpl_properties', 'id', $property_id);
		if(empty($values)) {
			return null;
		}
		$mls_id = $values->mls_id;
		$source = $values->source;
		$ref_id = $values->ref_id;
		if(empty($mls_id) || $source != 'RF' || strpos($ref_id ?? '', '_not_found')) {
			return null;
		}
		$args = array(
			"post_type" => 'wpl_property',
			"paged" => 0,
			"posts_per_page" => 1,
			'orderby' => 'mls_id',
			'order' => 'ASC',
			'meta_query' => $this->getQuery(['sf_select_mls_id' => $mls_id]),
		);
		if(!empty($this->query)) {
			$args['meta_query'] = $this->query;
		}
		$result = $this->rf_query($args);
		if(empty($result->found_posts)) {
			wpl_db::set('wpl_properties', 'id', 'ref_id', $ref_id . '_not_found');
			return null;
		}
		return $this->get_cloud_post_id($property_id);
	}

	public function get_property_raw_data($property_id, $output_type = 'loadAssoc') {
		$cacheKey = "$property_id-$output_type";
		if(!empty(wpl_rf_property::$cached[$cacheKey])) {
			return wpl_rf_property::$cached[$cacheKey];
		}
		$post_id = $this->get_cloud_post_id($property_id);

		if(empty($post_id)) {
			$post_id = $this->import_missing_listing($property_id);
			if(empty($post_id)) {
				return [];
			}
		}
		$result = $this->parse_post_meta($post_id, true);
		if(empty($result)) {
			$ref_id = wpl_db::select("SELECT ref_id from `#__wpl_properties` WHERE id = '$property_id'", 'loadResult');
			$args = array(
				'name' => $ref_id
			);
			$posts = $this->rf_query($args);
			if(!empty($posts->posts[0])) {
				$result = $this->parse_post_meta($posts->posts[0]->ID, true);
			}
		} else {
			$saved_property = wpl_db::select("SELECT * FROM `#__wpl_properties` WHERE id = '{$result['id']}' and `source` = 'RF'", 'loadAssoc');
			$update =  $this->update_existing_property($property_id, $saved_property, $saved_property['ref_id'], $result);
			foreach ($update as $field => $value) {
				$result[$field] = $value;
			}
		}
		if(!empty($result)) {
			$result = $this->merge_fields($property_id, $result);
		}
		wpl_rf_property::$cached[$cacheKey] = $result;
		return $output_type != 'loadAssoc' ? (object) $result : $result;
	}

	public static function get_location_names($level, $conditions = []) {
		$args = array(
			'taxonomy' => 'wpl_property_location' . $level . '_name',
		);
		if(!empty($conditions)) {
			$args['meta_query'] = [];
			foreach ($conditions as $table_column => $column_value) {
				if(!is_array($column_value)) {
					$column_value = trim($column_value);
					if(empty($column_value)) {
						continue;
					}
					$column_value = explode(',', $column_value);
				}
				$orQuery = [
					'relation' => 'OR',
				];
				foreach ($column_value as $column_value_item) {
					$column_value_item = trim($column_value_item);
					if(empty($column_value_item)) {
						continue;
					}
					$orQuery[] = [
						'key' => $table_column,
						'value' => $column_value_item,
						'compare' => '=',
					];
				}
				if(count($orQuery) == 1) {
					continue;
				}
				$args['meta_query'][] = $orQuery;
			}
		}
		$terms = get_terms($args);
		$return = [];
		foreach($terms as $term) {
			$return[] = $term->name;
		}
		sort($return);
		return apply_filters('wpl_rf_property/get_location_names', $return, $level) ;
	}

	public function get_total($conditions = [])
	{
	    
		$args = array(
			"post_type" => 'wpl_property',
			"posts_per_page" => -1,
			'meta_query' => [],
		);
		
		if(!empty($conditions)) {
			$args['meta_query'] = [];
			foreach ($conditions as $table_column => $column_value) {
				if(!is_array($column_value)) {
					$column_value = trim($column_value);
					if(empty($column_value)) {
						continue;
					}
					$column_value = explode(',', $column_value);
				}
				$orQuery = [
					'relation' => 'OR',
				];
				foreach ($column_value as $column_value_item) {
					$column_value_item = trim($column_value_item);
					if(empty($column_value_item)) {
						continue;
					}
					$orQuery[] = [
						'key' => $table_column,
						'value' => $column_value_item,
						'compare' => '=',
					];
				}
				if(count($orQuery) == 1) {
					continue;
				}
				$args['meta_query'][] = $orQuery;
			}
		}
		$result = $this->rf_query($args);
		$this->total = $result->found_posts;
		
		return $this->total;
	}
}

add_filter('rf_shell_meta_mapping_file', [wpl_rf_property::getInstance(), 'load_meta_mapping'], 99, 3);
add_filter('wpl_property_show_controller_abstract/display/property', [wpl_rf_property::getInstance(), 'fetch_agent_info'], 9);
add_action('wpl_settings/save_setting', [wpl_rf_property::getInstance(), 'setting_changed'], 9, 3);
