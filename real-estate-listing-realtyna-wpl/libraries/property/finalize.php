<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Class wpl_property_finalize
 * @author Howard R <howard@realtyna.com>
 * @since WPL4.4.1
 * @package WPL
 * @date 02/01/2019
 */
class wpl_property_finalize
{
    /**
     * @var integer
     */
    private $property_id;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var integer
     */
    private $user_id;

    /**
     * @var integer
     */
    private $confirm = 0;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $now;

    /**
     * @var wpl_db
     */
    private $db;

    /**
     * @var array
     */
    private $q;

    /**
     * @var array
     */
    private $q2;

    /**
     * @var integer
     */
    private $kind = 0;

    /**
     * @var boolean
     */
    private $multilingual = false;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $columns2;

    /**
     * wpl_property_finalize constructor.
     * @param integer $property_id
     * @param string $mode
     * @param integer $user_id
     */
	public function __construct($property_id, $mode = 'edit', $user_id = NULL)
	{
	    $this->property_id = $property_id;
	    $this->mode = $mode;
	    $this->user_id = $user_id;

        $this->q = array();
        $this->q2 = array();

	    // Settings
        $this->settings = wpl_global::get_settings();

        // Confirm Access
        $this->confirm = wpl_global::check_access('confirm', $this->user_id);

	    // Current Timestamp
	    $this->now = date('Y-m-d H:i:s');

	    // Multilingual Status
        $this->multilingual = wpl_global::check_multilingual_status();

	    // DB
        $this->db = new wpl_db();

        // Property Raw Data
        $this->data = $this->raw();

        // Property Kind
        $this->kind = $this->data['kind'] ?? 0;

        // Properties Columns
        $this->columns = wpl_db::columns('wpl_properties');

        // Properties2 Columns
        $this->columns2 = wpl_db::columns('wpl_properties2');
	}

    /**
     * @return bool
     */
    public function start()
    {
        // Units Query
        $this->q_units();

        // Essentials Query
        $this->q_essentials();

        // Clear Cache Query
        $this->q_cache();

        // Media Query
        $this->q_media();

        // Multilingual
        if($this->multilingual)
        {
            $languages = wpl_addon_pro::get_wpl_languages();
            $current_language = wpl_global::get_current_language();

            $alias_multilingual = wpl_addon_pro::get_multiligual_status_by_column('alias', $this->kind);
            $page_title_multilingual = wpl_addon_pro::get_multiligual_status_by_column('field_312', $this->kind);
            $title_multilingual = wpl_addon_pro::get_multiligual_status_by_column('field_313', $this->kind);

            if(!$alias_multilingual) $this->q_alias();
            if(!$page_title_multilingual) $this->q_page_title();
            if(!$title_multilingual) $this->q_title();

            if($languages)
            {
                foreach($languages as $language)
                {
                    if(wpl_global::switch_language($language))
                    {
                        // Location Text
                        $this->q_location_text();

                        // Text Search Query
                        $this->q_textsearch();

                        if($alias_multilingual) $this->q_alias();
                        if($page_title_multilingual) $this->q_page_title();
                        if($title_multilingual) $this->q_title();
                    }
                }
            }

            // Switch to current language again
            wpl_global::switch_language($current_language);
        }
        else
        {
            // Location Text
            $this->q_location_text();

            // Text Search Query
            $this->q_textsearch();

            $this->q_alias();
            $this->q_page_title();
            $this->q_title();
        }

        $u = '';
        foreach($this->q as $column=>$value)
        {
            if(in_array($column, array('mls_id_num', 'geopoints'))) $u .= wpl_db::prepare('%i = %2$s,', $column, $value);
            else $u .= wpl_db::prepare('%i = %s,', $column, $value);
        }

        // Run Update Query
        $query = "UPDATE `#__wpl_properties` SET ".trim($u ?? '', ', ')." WHERE `id`='".$this->property_id."'";
        $this->db->q($query, 'update');

        // Properties2 Table
        if($this->db->select(wpl_db::prepare('SELECT `id` FROM `#__wpl_properties2` WHERE `id` = %d', $this->property_id), 'loadResult'))
        {
            // Update

            $u2 = '';
            foreach($this->q2 as $column=>$value) $u2 .= wpl_db::prepare('%i = %s,', $column, $value);

            $query = "UPDATE `#__wpl_properties2` SET ".trim($u2 ?? '', ', ')." WHERE `id`='".$this->property_id."'";
            $this->db->q($query, 'update');
        }
        else
        {
            // Insert

            $u2_columns = '`id`,';
            $u2_values = "'".$this->property_id."',";
            foreach($this->q2 as $column=>$value)
            {
                $u2_columns .= wpl_db::prepare('%i,', $column);
                $u2_values .= wpl_db::prepare('%s,', $value);
            }

            $query = "INSERT INTO `#__wpl_properties2` (".trim($u2_columns, ', ').") VALUES (".trim($u2_values, ', ').")";
            $this->db->q($query, 'insert');
        }

        // Clear Flag
        $clear = true;
        if(wpl_global::check_addon('mls') and isset($this->settings['clear_thumbnails_after_update']) and !$this->settings['clear_thumbnails_after_update']) $clear = false;

        // Clear Thumbnails
        if($clear) $this->clear_thumbnails();

        // Throwing Events
        if($this->mode == 'add') wpl_events::trigger('add_property', $this->property_id);
        elseif($this->mode == 'edit') wpl_events::trigger('edit_property', $this->property_id);

        // Finalize Event
        wpl_events::trigger('property_finalized', $this->property_id);

        // Confirm Event
        if($this->confirm) wpl_events::trigger('property_confirm', $this->property_id);

        // Response
        return true;
    }

    /**
     * Get Property Raw Data
     * @return array
     */
    private function raw()
    {
        return wpl_property::get_property_raw_data($this->property_id);
    }

    private function q_units()
    {
        $units = wpl_global::return_in_id_array(wpl_units::get_units('', 1));

        foreach($this->data as $field=>$value)
        {
            if(strpos($field ?? '', '_unit') === false) continue;
            if(!isset($units[$value])) continue;

            $core_field = str_replace('_unit', '', $field);
            if(!isset($this->data[$core_field])) continue;


            // to avoid the string * string issue
            if(empty($this->data[$core_field])) {
                $this->data[$core_field] = 0;
            }
            if(empty($this->data[$core_field.'_max'])) {
                $this->data[$core_field.'_max'] = 0;
            }

            // Add SI value to the Query
            if(in_array($core_field.'_si', $this->columns)) $this->q[$core_field.'_si'] = ($units[$value]['tosi'] * $this->data[$core_field]);
            else $this->q2[$core_field.'_si'] = ($units[$value]['tosi'] * $this->data[$core_field]);

            // Add Max SI value to the Query
            if(isset($this->data[$core_field.'_max']) and in_array($core_field.'_max_si', $this->columns)) $this->q[$core_field.'_max_si'] = ($units[$value]['tosi'] * $this->data[$core_field.'_max']);
            elseif(isset($this->data[$core_field.'_max']) and in_array($core_field.'_max_si', $this->columns2)) $this->q2[$core_field.'_max_si'] = ($units[$value]['tosi'] * $this->data[$core_field.'_max']);
        }
    }

    private function q_essentials()
    {
        // Finalize Flag
        $this->q['finalized'] = '1';

        // Numeric MLS ID
        $this->q['mls_id_num'] = floatval($this->data['mls_id']);

        // Finalize Timestamp
        $this->q['last_finalize_date'] = $this->now;

        // Confirm Flag
        if($this->confirm) $this->q['confirmed'] = '1';

        // Listing Expiration
        $listing_expiration_status = $this->settings['lisexpr_status'] ?? 0;

        if($listing_expiration_status and !wpl_global::check_addon('membership')) $this->q['expired'] = '0';
        elseif($listing_expiration_status)
        {
            $membership = new wpl_addon_membership();
            if(!$membership->is_expired($this->user_id)) $this->q['expired'] = '0';
        }

        // APS Point
        if(wpl_global::check_addon('APS')) $this->q['geopoints'] = 'Point(`googlemap_ln`,`googlemap_lt`)';
    }

    private function q_cache()
    {
        // @todo
        $columns = array('rendered', 'location_text', 'textsearch', 'alias');

        // Remove Automatic Meta Keywords
        if(isset($this->data['meta_keywords_manual']) and !$this->data['meta_keywords_manual']) $columns[] = 'meta_keywords';

        // Remove Automatic Meta Description
        if(isset($this->data['meta_description_manual']) and !$this->data['meta_description_manual']) $columns[] = 'meta_description';

        // Add Multilingual Columns
        if($this->multilingual) $columns = $this->get_multilingual_columns($columns);

        // Add to Query
        foreach($columns as $column) $this->q[$column] = '';
    }

    private function q_media()
    {
        $items = wpl_items::get_items($this->property_id, '', $this->kind, '', 1);

        $this->q['pic_numb'] = (isset($items['gallery']) ? count($items['gallery']) : 0);
        $this->q2['att_numb'] = (isset($items['attachment']) ? count($items['attachment']) : 0);
    }

    private function q_textsearch()
    {
        // Get Textsearch Fields
        $fields = wpl_flex::get_fields('', 1, $this->kind, 'text_search', '1');
        $rendered = wpl_property::render_property($this->data, $fields);

        $text_search_data = array();
        foreach($rendered as $data)
        {
            
            if( !trim($data['type'] ?? '' ) or !trim( $data['value'] ?? '' )  ) continue;

            // Default Value
            $value = isset($data['value']) ? $data['value'] : '';
            $value2 = '';
            $type = $data['type'];

            if($type == 'text' or $type == 'textarea')
            {
                $value = $data['name'] .' '. $data['value'];
            }
            elseif($type == 'neighborhood')
            {
                $value = $data['name'] .(isset($data['distance']) ? ' ('. $data['distance'] .' '.wpl_esc::return_t('MINUTES') .' '.wpl_esc::return_t('BY') .' '. $data['by'] .')' : '');
            }
            elseif($type == 'feature')
            {
                $feature_value = $data['name'] ?? '';

                if(isset($data['values'][0]))
                {
                    $feature_value .= ' ';

                    foreach($data['values'] as $val) $feature_value .= $val .', ';
                    $feature_value = rtrim($feature_value, ', ');
                }

                $value = $feature_value;
            }
            elseif($type == 'locations' and isset($data['locations']) and is_array($data['locations']))
            {
                $location_values = array();
                foreach($data['locations'] as $location_level=>$value)
                {
                    array_push($location_values, $data['keywords'][$location_level]);

                    $location_name = stripslashes_deep($data['raw'][$location_level] ?? '');
                    $abbr = wpl_locations::get_location_abbr_by_name($location_name, $location_level) ?? '';
                    $name = wpl_locations::get_location_name_by_abbr($abbr, $location_level) ?? '';

                    $ex_space = explode(' ', stripslashes_deep($name));
                    foreach($ex_space as $value_raw) array_push($location_values, stripslashes_deep($value_raw));

                    if($name !== $abbr)
                    {
                        array_push($location_values, stripslashes_deep($abbr));

                        if($abbr == 'US') array_push($location_values, 'USA');
                        elseif($abbr == 'USA') array_push($location_values, 'US');
                    }
                }

                // Add all location fields to the location text search
                $location_category = wpl_flex::get_category(NULL, wpl_db::prepare(" AND `kind` = %d AND `prefix` = 'ad'", $this->kind));
                $location_fields = wpl_flex::get_fields($location_category->id, 1, $this->kind);

                foreach($location_fields as $location_field)
                {
                    if(!isset($rendered[$location_field->id])) continue;
                    if(!trim($location_field->table_column ?? '')) continue;
                    if( !trim($rendered[$location_field->id]['value'] ?? '') ) continue;

                    $ex_space = explode(' ', strip_tags($rendered[$location_field->id]['value'] ?? ''));
                    foreach($ex_space as $value_raw) array_push($location_values, stripslashes_deep($value_raw ?? ''));
                }

                $location_suffix_prefix = wpl_locations::get_location_suffix_prefix();
                foreach($location_suffix_prefix as $suffix_prefix) array_push($location_values, $suffix_prefix);

                $location_string = '';
                $location_values = array_unique($location_values);
                foreach($location_values as $location_value) $location_string .= 'LOC-'.wpl_esc::return_html_t($location_value).' ';

                $value = trim($location_string);
            }
            elseif(isset($data['value']))
            {
                $value = $data['name'] .' '. $data['value'];
                if(is_numeric($data['value']))
                {
                    $value2 = $data['name'] .' '. wpl_global::number_to_word($data['value']);
                }
            }

            // Set value in text search data
            if(trim($value ?? '') != '') $text_search_data[] = strip_tags($value ?? '');
            if(trim($value2 ?? '') != '') $text_search_data[] = strip_tags($value2 ?? '');
        }

        $column = 'textsearch';
        if($this->multilingual) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

	    //Create normalized search text
	    $normalizedText = wpl_locations::createNormalizedSearchText($this->q['location_text']);
	    if ($normalizedText) {
		    $text_search_data[] = $normalizedText;
	    }

        $this->q[$column] = wpl_global::remove_emoji(implode(' ', $text_search_data));
    }

    private function q_alias()
    {
        $column = 'alias';
        $field = wpl_flex::get_field_by_column($column, $this->kind);
        $base_column = NULL;

        if(isset($field->multilingual) and $field->multilingual and $this->multilingual)
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }

		$alias_str = wpl_property::update_alias($this->data, $this->property_id, '-', true);

        $this->q[$column] = $alias_str;
        if($base_column) $this->q[$base_column] = $alias_str;
    }

    private function q_page_title()
    {
        $column = 'field_312';
        $field = wpl_flex::get_field_by_column($column, $this->kind);

        $base_column = NULL;
        if(isset($field->multilingual) and $field->multilingual and $this->multilingual)
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }

        // Page Title Already Added
        if( trim($this->data[$column] ?? '') != '') return stripslashes($this->data[$column] ?? '');

        // Get the pattern
        $default_pattern = '[property_type] [listing][glue] [rooms][glue] [bedrooms][glue] [bathrooms][glue] [price][glue] [mls_id]';
        $page_title_pattern = wpl_global::get_pattern('property_page_title_pattern', $default_pattern, $this->kind, $this->data['property_type']);

        $title_str = wpl_global::render_pattern($page_title_pattern, $this->property_id, $this->data, ' - ');
        $title_str = trim($title_str ?? '', '- ');

        // Apply Filters
        @extract(wpl_filters::apply('generate_property_page_title', array('title_str'=>$title_str, 'patern'=>$page_title_pattern, 'property_data'=>$this->data)));

        $this->q[$column] = $title_str;
        if($base_column) $this->q[$base_column] = $title_str;

        return $title_str;
    }

    private function q_title()
    {
        $column = 'field_313';
        $field = wpl_flex::get_field_by_column($column, $this->kind);

        $base_column = NULL;
        if(isset($field->multilingual) and $field->multilingual and $this->multilingual)
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }

        // Title Already Added
        if( trim($this->data[$column] ?? '') != '') return stripslashes($this->data[$column] ?? '');

        // Get the pattern
        $title_pattern = wpl_global::get_pattern('property_title_pattern', '[property_type] [listing]', $this->kind, $this->data['property_type']);
        $title_str = wpl_global::render_pattern($title_pattern, $this->property_id, $this->data, ' ');

        // Apply Filters
		$title_str = apply_filters('generate_property_title', $title_str, $title_pattern, $this->data);

        $this->q[$column] = $title_str;
        if($base_column) $this->q[$base_column] = $title_str;

        return $title_str;
    }

    private function q_location_text()
    {
        $column = 'location_text';
        $field = wpl_flex::get_field_by_column($column, $this->kind);

        $base_column = NULL;
        if(isset($field->multilingual) and $field->multilingual and $this->multilingual)
        {
            $base_column = wpl_global::get_current_language() == wpl_addon_pro::get_default_language() ? $column : NULL;
            $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        }

        // Return hidex_keyword if address of property is hidden
        if(isset($this->data['show_address']) and !$this->data['show_address'])
        {
            $location_hidden_keyword = isset($this->settings['location_hidden_keyword']) ? $this->settings['location_hidden_keyword'] : '';

            $this->q[$column] = wpl_esc::return_t($location_hidden_keyword);
            if($base_column) $this->q[$base_column] = wpl_esc::return_t($location_hidden_keyword);

            return;
        }

        $locations = array();

        $street_no_column = 'street_no';
        if($this->multilingual and wpl_addon_pro::get_multiligual_status_by_column($street_no_column, $this->kind)) $street_no_column = wpl_addon_pro::get_column_lang_name($street_no_column, wpl_global::get_current_language(), false);
        if( trim($this->data[$street_no_column] ?? '') != '') $locations['street_no'] = wpl_esc::return_html_t($this->data[$street_no_column]);

        $street_column = 'field_42';
        if($this->multilingual and wpl_addon_pro::get_multiligual_status_by_column($street_column, $this->kind)) $street_column = wpl_addon_pro::get_column_lang_name($street_column, wpl_global::get_current_language(), false);
        if( trim($this->data[$street_column] ?? '') != '') $locations['street'] = wpl_esc::return_html_t($this->data[$street_column]);

        $street_suffix_column = 'street_suffix';
        if($this->multilingual and wpl_addon_pro::get_multiligual_status_by_column($street_suffix_column, $this->kind)) $street_suffix_column = wpl_addon_pro::get_column_lang_name($street_suffix_column, wpl_global::get_current_language(), false);
        if( trim($this->data[$street_suffix_column] ?? '') != '') $locations['street_suffix'] = wpl_esc::return_html_t($this->data[$street_suffix_column]);

        if( trim($this->data['location7_name'] ?? '') != '') $locations['location7_name'] = wpl_esc::return_html_t($this->data['location7_name']);
        if( trim($this->data['location6_name'] ?? '') != '') $locations['location6_name'] = wpl_esc::return_html_t($this->data['location6_name']);
        if( trim($this->data['location5_name'] ?? '') != '') $locations['location5_name'] = wpl_esc::return_html_t($this->data['location5_name']);
        if( trim($this->data['location4_name'] ?? '') != '') $locations['location4_name'] = wpl_esc::return_html_t($this->data['location4_name']);
        if( trim($this->data['location3_name'] ?? '') != '') $locations['location3_name'] = wpl_esc::return_html_t($this->data['location3_name']);
        if( trim($this->data['location2_name'] ?? '') != '') $locations['location2_name'] = wpl_esc::return_html_t($this->data['location2_name']);
        if( trim($this->data['zip_name'] ?? '') != '') $locations['zip_name'] = wpl_esc::return_html_t($this->data['zip_name']);
        if( trim($this->data['location1_name'] ?? '') != '') $locations['location1_name'] = wpl_esc::return_html_t($this->data['location1_name']);

        // Location Abbr Names
        if( trim($this->data['location1_name'] ?? '')) $locations['location1_abbr'] = wpl_esc::return_html_t(wpl_locations::get_location_abbr_by_name($this->data['location1_name'], 1));
        if( trim($this->data['location2_name'] ?? '')) $locations['location2_abbr'] = wpl_esc::return_html_t(wpl_locations::get_location_abbr_by_name($this->data['location2_name'], 2));

        // Get the pattern
        $default_pattern = '[street_no] [street] [street_suffix][glue] [location4_name][glue] [location2_abbr] [zip_name]';
        $location_pattern = wpl_global::get_pattern('property_location_pattern', $default_pattern, $this->kind, $this->data['property_type']);

        $glue = ',';
        $location_text = wpl_global::render_pattern($location_pattern, $this->property_id, $this->data, $glue, $locations);

        // Apply Filters
        @extract(wpl_filters::apply('generate_property_location_text', array('location_text'=>$location_text, 'glue'=>$glue, 'property_data'=>$this->data)));

        $final = '';
        $ex = explode($glue, $location_text);

        foreach($ex as $value)
        {
            if(trim($value ?? '') == '') continue;
            $final .= trim($value).$glue.' ';
        }

        $location_text = trim($final, $glue.' ');

        $this->q[$column] = $location_text;
        if($base_column) $this->q[$base_column] = $location_text;
    }

    private function get_multilingual_columns($columns)
    {
        if($languages = wpl_addon_pro::get_wpl_languages())
        {
            foreach($columns as $column)
            {
                foreach($languages as $language)
                {
                    $language_column = wpl_addon_pro::get_column_lang_name($column, $language, false);
                    if(isset($this->data[$language_column])) $columns[] = $language_column;
                }
            }
        }

        return $columns;
    }

    private function clear_thumbnails()
    {
		wpl_property::remove_thumbnails($this->property_id, $this->kind);
    }
}