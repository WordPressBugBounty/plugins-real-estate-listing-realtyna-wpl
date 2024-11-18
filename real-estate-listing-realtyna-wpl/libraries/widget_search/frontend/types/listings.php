<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_listings(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    $listings = wpl_global::get_listings();
    if ($type == 'listings') {
        switch ($field['type']) {
            case 'select':
                $show = 'select';
                $any = true;
                $multiple = false;
                $label = true;
                break;

            case 'multiple':
                $show = 'multiple';
                $any = false;
                $multiple = true;
                $label = true;
                break;

            case 'checkboxes':
                $show = 'checkboxes';
                $any = false;
                $label = true;
                break;

            case 'radios':
                $show = 'radios';
                $any = false;
                $label = true;
                break;

            case 'radios_any':
                $show = 'radios';
                $any = true;
                $label = true;
                break;

            case 'predefined':
                $show = 'predefined';
                $any = false;
                $label = false;
                break;

            case 'select-predefined':
                $show = 'select-predefined';
                $any = true;
                $label = true;
                break;
        }

        // Restricted Listing Types
        $current_user = wpl_users::get_wpl_user();
        $availables = (isset($current_user->maccess_lrestrict_plisting) and $current_user->maccess_lrestrict_plisting and isset($current_user->maccess_listings_plisting) and trim(
                $current_user->maccess_listings_plisting ?? '',
                ', '
            ) != '') ? explode(',', trim($current_user->maccess_listings_plisting ?? '', ', ')) : array();

        // Current Value
        $current_value = wpl_request::getVar('sf_select_' . $field_data['table_column'], -1);

        if ($label) {
            $html .= '<label for="sf' . $widget_id . '_select_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>';
        }

        if ($show == 'select') {
            $html .= '<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" onchange="wpl_listing_changed' . $widget_id . '(this.value);">';
            if ($any) {
                $html .= '<option value="-1" selected="selected">' . wpl_esc::return_html_t(
                        $field['name']
                    ) . '</option>';
            }

            foreach ($listings as $listing) {
                // Skip if listing type is not allowed for this user
                if (is_array($availables) and count($availables) and !in_array($listing['id'], $availables)) {
                    continue;
                }

                $html .= '<option value="' . esc_attr(
                        $listing['id']
                    ) . '" ' . ($current_value == $listing['id'] ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $listing['name']
                    ) . '</option>';
            }

            $html .= '</select>';
        } elseif ($show == 'multiple') {
            // Current Value
            $current_values = explode(',', wpl_request::getVar('sf_multiple_' . $field_data['table_column']));

            $html .= '<div class="wpl_searchwid_' . $field_data['table_column'] . '_multiselect_container">
		<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '"  data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" class="wpl_searchmod_' . $field_data['table_column'] . '_multiselect" id="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" multiple="multiple">';

            foreach ($listings as $listing) {
                // Skip if listing type is not allowed for this user
                if (is_array($availables) and count($availables) and !in_array($listing['id'], $availables)) {
                    continue;
                }

                $html .= '<option value="' . esc_attr($listing['id']) . '" ' . (in_array(
                        $listing['id'],
                        $current_values
                    ) ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t($listing['name']) . '</option>';
            }

            $html .= '</select></div>';
        } elseif ($show == 'checkboxes') {
            // Current Value
            $current_values = explode(',', wpl_request::getVar('sf_multiple_' . $field_data['table_column']));

            $i = 0;
            foreach ($listings as $listing) {
                // Skip if listing type is not allowed for this user
                if (is_array($availables) and count($availables) and !in_array($listing['id'], $availables)) {
                    continue;
                }

                $i++;
                $html .= '<input ' . (in_array(
                        $listing['id'],
                        $current_values
                    ) ? 'checked="checked"' : '') . ' name="chk' . $widget_id . '_multiple_' . $field_data['table_column'] . '" type="checkbox" value="' . $listing['id'] . '" id="chk' . $widget_id . '_multiple_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_add_to_multiple' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="chk' . $widget_id . '_multiple_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $listing['name']
                    ) . '</label>';
            }

            $render_current_value = implode(',', $current_values);
            if (!empty($render_current_value) and !stristr($render_current_value, ',')) {
                $render_current_value = $render_current_value . ',';
            }

            $html .= '<input value="' . esc_attr(
                    $render_current_value
                ) . '" type="hidden" id="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" />';
        } elseif ($show == 'radios') {
            $i = 0;
            if ($any) {
                $html .= '<input ' . ($current_value == -1 ? 'checked="checked"' : '') . ' name="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '" type="radio" value="-1" id="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_select_radio' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');" onchange="wpl_listing_changed' . $widget_id . '(this.value);"><label for="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $field['name']
                    ) . '</label>';
            } elseif ($current_value == -1) {
                $current_value = reset($listings)['id'];
            }

            foreach ($listings as $listing) {
                // Skip if listing type is not allowed for this user
                if (is_array($availables) and count($availables) and !in_array($listing['id'], $availables)) {
                    continue;
                }

                $i++;
                $html .= '<input ' . ($current_value == $listing['id'] ? 'checked="checked"' : '') . ' name="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '" type="radio" value="' . $listing['id'] . '" id="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_select_radio' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');" onchange="wpl_listing_changed' . $widget_id . '(this.value);"><label for="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $listing['name']
                    ) . '</label>';
            }

            $html .= '<input value="' . esc_attr(
                    $current_value
                ) . '" type="hidden" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" onchange="wpl_listing_changed' . $widget_id . '(this.value);" />';
        } elseif ($show == 'predefined') {
            $predefined_types = is_array($field['extoption']) ? implode(',', $field['extoption']) : null;

            $html .= '<input name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" type="hidden" value="' . $predefined_types . '" onchange="wpl_listing_changed' . $widget_id . '(this.value);" />';
        } elseif ($show == 'select-predefined') {
            $html .= '<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" onchange="wpl_listing_changed' . $widget_id . '(this.value);">';
            if ($any) {
                $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';
            }

            foreach ($listings as $listing) {
                // Skip if listing type is not allowed for this user
                if (is_array($availables) and count($availables) and !in_array($listing['id'], $availables)) {
                    continue;
                }

                if (is_array($field['extoption']) and in_array($listing['id'], $field['extoption'])) {
                    $html .= '<option value="' . esc_attr(
                            $listing['id']
                        ) . '" ' . ($current_value == $listing['id'] ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                            $listing['name']
                        ) . '</option>';
                }
            }

            $html .= '</select>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/listings', 'widget_search_frontend_general_listings', 10, 8);
