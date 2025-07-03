<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.locations');

class wpl_property_listing_controller extends wpl_controller
{
    public function display()
    {
        $function = wpl_request::getVar('wpl_function');

        if($function == 'get_locations')
        {
            $location_level = wpl_request::getVar('location_level');
            $parent = wpl_request::getVar('parent');
            $current_location_id = wpl_request::getVar('current_location_id');
            $widget_id = wpl_request::getVar('widget_id');

            $this->get_locations($location_level, $parent, $current_location_id, $widget_id);
        }
        elseif($function == 'locationtextsearch_autocomplete')
        {
            $term = wpl_request::getVar('term');
			$kind = wpl_request::getVar('kind', 0);
            $this->locationtextsearch_autocomplete($term, $kind);
        }
        elseif($function == 'advanced_locationtextsearch_autocomplete')
        {
            $term = wpl_request::getVar('term');
            $kind = wpl_request::getVar('kind', 0);
            $this->advanced_locationtextsearch_autocomplete($term, $kind);
        }
        elseif($function == 'contact_listing_user' or $function == 'contact_agent')
        {
            $this->contact_listing_user();
        }
        elseif($function == 'set_pcc')
        {
            $this->set_pcc();
        }
        elseif($function == 'refresh_searchwidget_counter')
        {
            $this->refresh_searchwidget_counter();
        }
        elseif($function == 'get_total_results')
        {
            $this->get_total_results();
        }
        elseif($function == 'get_property_ids')
        {
            $this->get_property_ids();
        }
    }

    private function get_locations($location_level = '', $parent = '', $current_location_id = '', $widget_id)
    {
        $location_settings = wpl_global::get_settings('3'); # location settings

        if($location_settings['zipcode_parent_level'] == $location_level - 1)
        {
            $location_level = 'zips';
        }

        $location_data = wpl_locations::get_locations($location_level, $parent, ($location_level == '1' ? 1 : ''), '', '`name` ASC', '');

        $res = count($location_data) ? 1 : 0;
        $message = $res ? wpl_esc::return_html_t('Fetched.') : wpl_esc::return_html_t('Error Occured.');
        $name_id = $location_level != 'zips' ? 'sf' . $widget_id . '_select_location' . $location_level . '_id' : 'sf' . $widget_id . '_select_zip_id';

        $html = '<select name="' . $name_id . '" id="' . $name_id . '"';

        if($location_level != 'zips')
            $html .='onchange="wpl' . $widget_id . '_search_widget_load_location(\'' . $location_level . '\', this.value, \'' . $current_location_id . '\');"';

        $html .= '>';
        $html .= '<option value="-1">' . wpl_esc::return_html_t((trim($location_settings['location'.$location_level.'_keyword'] ?? '') != '' ? $location_settings['location'.$location_level.'_keyword'] : 'Select')) . '</option>';

        foreach($location_data as $location)
        {
            $html .= '<option value="' . $location->id . '" ' . ($current_location_id == $location->id ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t($location->name) . '</option>';
        }

        $html .= '</select>';

        $response = array('success' => $res, 'message' => $message, 'data' => $location_data, 'html' => $html, 'keyword' => wpl_esc::return_html_t($location_settings['location' . $location_level . '_keyword']));
        $this->response($response);
    }

    private function locationtextsearch_autocomplete($term, $kind = 0)
    {
        $limit = 10;
		if(wpl_settings::is_mls_on_the_fly() && $kind == 0) {
			$property_object = new wpl_property();
			$property_object->start(0, $limit, '', '', ["sf_text_location_text" => $term]);
			$property_object->query();
			$found = $property_object->search();
			$counter = 0;
			$output = [];
			foreach ($found as $found_item) {
				$counter++;
				if($counter > $limit) {
					break;
				}
				$output[] = ['title' => $found_item->location_text, 'label' => $found_item->location_text];
			}
			$this->response($output);
		}

        if(wpl_global::check_multilingual_status())
        {
            $location_text = wpl_addon_pro::get_column_lang_name('location_text', wpl_global::get_current_language(), false);
            $results = wpl_db::select(wpl_db::prepare('SELECT %i AS name FROM `#__wpl_properties` WHERE %i LIKE %s LIMIT %d', $location_text, $location_text, wpl_db::esc_like($term, 'right'), $limit), 'loadAssocList');
        }
        else
        {
			$location_text = 'location_text';
			$results = wpl_db::select(wpl_db::prepare('SELECT DISTINCT %i AS name FROM `#__wpl_properties` WHERE %i LIKE %s LIMIT %d', $location_text, $location_text, wpl_db::esc_like($term, 'right'), $limit), 'loadAssocList');
        }

        $output = array();
        foreach($results as $result)
        {
            $name = preg_replace("/\s,/", '', $result['name']);
            $output[] = array('label' => $name, 'value' => $name);
        }

        $this->response($output);
    }

    private function advanced_locationtextsearch_autocomplete($term, $kind = 0)
    {
		$settings = wpl_settings::get_settings(3);
		$queries = wpl_property::get_suggestion_fields($kind, $term);
        $limit = 5;
        $output = array();
        $condition = wpl_db::prepare("`finalized` = 1 AND `confirmed` = 1 AND `deleted` = 0 AND `expired` = 0 and kind = %d", $kind);
		$condition = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/condition', $condition, $kind);

		$selected_field = wpl_request::getVar('field');

		if(!empty($selected_field) and $selected_field === 'keyword') {
			$output[] = array('label' => $term, 'title' => wpl_esc::return_html_t('Keyword'), 'column' => '', 'value' => $term);
			$output = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/output', $output, $term, $kind, $limit);
			$this->response($output);
		}
		if(!empty($selected_field)) {
			$selected_field_array = explode(',', $selected_field);
			$new_queries = [];
			foreach ($selected_field_array as $selected_field_item) {
				if(array_key_exists($selected_field_item, $queries)) {
					$new_queries[$selected_field_item] = $queries[$selected_field_item];
				}
			}
			$queries = $new_queries;
		}
		if(wpl_settings::is_mls_on_the_fly() && $kind == 0) {
			$output = [];
			foreach($queries as $column => $title)
			{
				if(in_array($column, ['mls_id', 'location_text'])) {
					$property_object = new wpl_property();
					$property_object->start(0, $limit, '', '', ["sf_text_$column" => $term]);
					$property_object->query();
					$found = $property_object->search();
					$counter = 0;
					foreach ($found as $found_item) {
						$counter++;
						if($counter > $limit) {
							break;
						}
						$value = $found_item->{$column};
						if($column == 'location_text') {
							$value = wpl_property::generate_location_text((array) $found_item);
						}
						$output[] = array('title' => $title, 'label' => $value, 'column' => $column, 'value' => $value);
					}
					continue;
				}
				$found_terms = get_terms('wpl_property_' . $column);
				$counter = 0;
				foreach($found_terms as $found_term) {
					if(strpos(strtolower($found_term->name), strtolower($term)) !== false) {
						$counter++;
						if($counter > $limit) {
							break;
						}
						$output_row = array('title' => $title, 'label' => $found_term->name, 'column' => $column, 'value' => $found_term->name);
	                    $output[] = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/rf/output_row', $output_row, $found_term);
					}
				}
			}
			$output = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/output', $output, $term, $kind, $limit);
			$this->response($output);
		}

	    if(wpl_global::zap_search_enabled())
	    {
		    $suggestion = new Flare\Rush\Suggestion;
		    $matches = $suggestion->get($term, $limit);
		    $labels = $suggestion->types_labels($settings);
		    foreach ($matches as $match)
		    {
			    $output_row = array('label' => $match['phrase'], 'title' => $labels[$match['type']], 'column' => $match['type'], 'value' => $match['phrase']);
	            $output[] = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/zap/output_row', $output_row, $match);
		    }
	    }
	    else
	    {
	        foreach($queries as $column => $title)
	        {
				if($column == 'mls_id' || $column == 'zip_name') {
					$query = wpl_db::prepare("SELECT DISTINCT %i AS `name` FROM `#__wpl_properties` WHERE $condition AND %i LIKE %s ORDER BY %i LIMIT %d", $column, $column, wpl_db::esc_like($term, 'right'), $column, $limit);
				} else {
					$query = wpl_db::prepare("SELECT DISTINCT %i AS `name` FROM `#__wpl_properties` WHERE $condition AND (%i LIKE %s OR %i LIKE %s) ORDER BY %i LIMIT %d", $column, $column, wpl_db::esc_like($term, 'right'), $column, wpl_db::esc_like($term), $column, $limit);
				}

				$query = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/sql_query', $query, $column, $term, $condition, $limit);
	            $results = wpl_db::select($query, 'loadAssocList');

	            foreach($results as $result)
	            {
	                $output_row = array('label' => $result['name'], 'title' => $title, 'column' => $column, 'value' => $result['name']);
	                $output[] = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/db/output_row', $output_row, $result);
	            }
	        }
        }
		$output = apply_filters('wpl_property_listing_controller/advanced_locationtextsearch_autocomplete/output', $output, $term, $kind, $limit);
        $this->response($output);
    }

    private function contact_listing_user()
    {
        $fullname = wpl_request::getVar('fullname', '');
        $phone = wpl_request::getVar('phone', '');
        $email = wpl_request::getVar('email', '');
        $message = wpl_request::getVar('message', '');
        $property_id = wpl_request::getVar('pid', '');
        $gre = wpl_request::getVar('g-recaptcha-response', '');

		do_action('wpl_property_listing_controller/contact_listing_user/pre', $this);

        // check recaptcha 
        $gre_response = wpl_global::verify_google_recaptcha($gre, 'gre_listing_contact_activity');

		$check_security = apply_filters('wpl_property_listing_controller/contact_listing_user/check_security', true);

        $returnData = array();
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = wpl_esc::return_html_t('Your email is not a valid email!');
        }
        elseif($check_security and !wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_listing_contact_form'))
        {
            $returnData['success'] = 0;
            $returnData['message'] = wpl_esc::return_html_t('The security nonce is not valid!');
        }
        elseif($gre_response['success'] === 0)
        {
            $returnData['success'] = 0;
            $returnData['message'] = $gre_response['message'];
        }
        else
        {
            $parameters = array(
                'fullname' => $fullname,
                'phone' => $phone,
                'email' => $email,
                'message' => $message,
                'property_id' => $property_id,
                'user_id' => wpl_property::get_property_user($property_id)
            );

			$parameters = apply_filters('wpl_property_listing_controller/contact_listing_user/parameters', $parameters);

            wpl_events::trigger('contact_agent', $parameters);

            $returnData['success'] = 1;
            $returnData['message'] = wpl_esc::return_html_t('Information sent to agent.');

            // Adding in items with type contact stat
            wpl_property::add_property_stats_item($property_id, 'contact_numb');
        }

        $this->response($returnData);
    }

    private function set_pcc()
    {
        $pcc = wpl_request::getVar('pcc', '');

        setcookie('wplpcc', $pcc, time()+(86400*30), '/');
        wpl_request::setVar('wplpcc', $pcc, 'COOKIE');

        $this->response(array('success'=>1));
    }

    private function refresh_searchwidget_counter()
    {
        $current_user_id = wpl_users::get_cur_user_id();
        $saved_searches_count = 0;
        $favorites_count = 0;
        
        if(wpl_global::check_addon('pro'))
        {
            _wpl_import('libraries.addon_pro');

            if($current_user_id)
                $favorites = wpl_addon_pro::favorite_get_pids(false, $current_user_id);
            else
                $favorites = wpl_addon_pro::favorite_get_pids(true);

            $favorites_count = count($favorites);
        }

        if(wpl_global::check_addon('save_searches') and $current_user_id)
        {
            _wpl_import('libraries.addon_save_searches');

            $save_searches = new wpl_addon_save_searches();
            $save_searches = $save_searches->get('', $current_user_id);
            $saved_searches_count = count($save_searches);
        }

        $this->response(array('saved_searches' => $saved_searches_count, 'favorites' => $favorites_count));
    }
    
    private function get_total_results()
    {
        // Kind
		$kind = wpl_request::getVar('kind', 0);
        $table = ($kind == 2) ? '#__wpl_users' : '#__wpl_properties';
        $default = ($kind == 2) ? array('sf_tmin_id'=>1, 'sf_select_access_public_profile'=>1, 'sf_select_expired'=>0) : array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0, 'sf_select_kind'=>$kind);
        
        // WHERE statement
        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$where = array_merge($vars, $default);
		if(wpl_settings::is_mls_on_the_fly() && $kind == 0) {
			$model = new wpl_property();
			$model->start(1, 1, 'id', 'ASC', $where, $kind);
			$model->query();
			$model->search();
			$this->response(array('success'=>1, 'total'=>$model->get_properties_count()));
		}
        $where = wpl_db::create_query($where);
       
        $total = wpl_db::select("SELECT COUNT(`id`) FROM `{$table}` WHERE 1 ".$where, 'loadResult');

        $this->response(array('success'=>1, 'total'=>$total));
    }

    private function get_property_ids(){

        // Kind
        $kind = wpl_request::getVar('kind', 0);
        $default = ($kind == 2) ? array('sf_tmin_id'=>1, 'sf_select_access_public_profile'=>1, 'sf_select_expired'=>0) : array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0, 'sf_select_kind'=>$kind);

        // WHERE statement
        $searchurl = wpl_request::getVar('searchurl');
        $searchurl = !empty($searchurl) ? parse_url($searchurl) : '';
        parse_str($searchurl['query'], $params);

        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'), $params);
        $where = wpl_db::create_query(array_merge($vars, $default));

		$result_save_searches = wpl_db::select("SELECT id FROM `#__wpl_properties` WHERE 1 $where LIMIT 100", 'loadAssocList');

        foreach($result_save_searches as $result){
            $id = !empty($result['id']) ? $result['id'] : '';
            if(!empty($id)) $property_ids .= $id . ',';
        }

        $property_ids = !empty($property_ids) ? rtrim($property_ids ?? '',",") : '';

        $this->response(array('success' => 1, 'property_ids' => $property_ids));

    }
}