<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_user_type(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if (in_array($type, array('user_type', 'user_membership'))) {
        switch ($field['type']) {
            case 'select':
                $show = 'select';
                $any = true;
                $label = true;
                break;

            case 'multiple':
                $show = 'multiple';
                $any = false;
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
        }

        /** current value **/
        $raw_options = $type == 'user_type' ? wpl_users::get_user_types(1) : wpl_users::get_wpl_memberships();

        $options = array();
        foreach ($raw_options as $raw_option) {
            $options[$raw_option->id] = array(
                'key' => $raw_option->id,
                'value' => (isset($raw_option->membership_name) ? $raw_option->membership_name : $raw_option->name)
            );
        }

        $current_value = stripslashes(wpl_request::getVar('sf_select_' . $field_data['table_column'], ''));

        if ($label) {
            $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';
        }

        if ($show == 'select') {
            $html .= '<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '">';
            if ($any) {
                $html .= '<option value="">' . wpl_esc::return_html_t($field['name']) . '</option>';
            }

            foreach ($options as $option) {
                $html .= '<option value="' . $option['key'] . '" ' . ($current_value == $option['key'] ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $option['value']
                    ) . '</option>';
            }

            $html .= '</select>';
        } elseif ($show == 'multiple') {
            /** current value **/
            $current_values = explode(
                ',',
                stripslashes(wpl_request::getVar('sf_multiple_' . $field_data['table_column'], ''))
            );

            $html .= '<div class="wpl_searchwid_' . $field_data['table_column'] . '_multiselect_container">
		<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" class="wpl_searchmod_' . $field_data['table_column'] . '_multiselect" id="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" multiple="multiple">';

            foreach ($options as $option) {
                $html .= '<option value="' . $option['key'] . '" ' . (in_array(
                        $option['key'],
                        $current_values
                    ) ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t($option['value']) . '</option>';
            }

            $html .= '</select></div>';
        } elseif ($show == 'checkboxes') {
            /** current value **/
            $current_values = explode(
                ',',
                stripslashes(wpl_request::getVar('sf_multiple_' . $field_data['table_column'], ''))
            );

            $i = 0;
            foreach ($options as $option) {
                $i++;
                $html .= '<input ' . (in_array(
                        $option['key'],
                        $current_values
                    ) ? 'checked="checked"' : '') . ' name="chk' . $widget_id . '_select_' . $field_data['table_column'] . '" type="checkbox" value="' . $option['key'] . '" id="chk' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_add_to_multiple' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="chk' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $option['value']
                    ) . '</label>';
            }

            $render_current_value = implode(',', $current_values);
            if (!empty($render_current_value) and !stristr($render_current_value, ',')) {
                $render_current_value = $render_current_value . ',';
            }

            $html .= '<input value="' . $render_current_value . '" type="hidden" id="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" />';
        } elseif ($show == 'radios') {
            $i = 0;
            if ($any) {
                $html .= '<input ' . ($current_value == -1 ? 'checked="checked"' : '') . ' name="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '" type="radio" value="-1" id="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_select_radio' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $field['name']
                    ) . '</label>';
            }

            foreach ($options as $option) {
                $i++;
                $html .= '<input ' . ($current_value == $option['key'] ? 'checked="checked"' : '') . ' name="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '" type="radio" value="' . $option['key'] . '" id="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_select_radio' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $option['value']
                    ) . '</label>';
            }

            $html .= '<input value="' . $current_value . '" type="hidden" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" />';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/user_type', 'widget_search_frontend_general_user_type', 10, 8);
add_filter('widget_search/frontend/general/user_membership', 'widget_search_frontend_general_user_type', 10, 8);
