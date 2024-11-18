<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_gallery(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if (in_array($type, array('select', 'multiselect'))) {
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

            case 'predefined':
                $show = 'predefined';
                $any = false;
                $label = false;

            case 'select-predefined':
                $show = 'select-predefined';
                $any = true;
                $label = true;
                break;
        }

        /** current value **/
        $current_value = stripslashes(wpl_request::getVar('sf_' . $type . '_' . $field_data['table_column'], -1));

        if ($label) {
            $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';
        }

        if ($show == 'select') {
            $html .= '<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_' . $type . '_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_' . $type . '_' . $field_data['table_column'] . '">';
            if ($any) {
                $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';
            }

            foreach ($options['params'] as $option) {
                if (!$option['enabled']) {
                    continue;
                }

                $html .= '<option value="' . $option['key'] . '" ' . ($current_value == $option['key'] ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $option['value'] ?? 'Error'
                    ) . '</option>';
            }

            $html .= '</select>';
        } elseif ($show == 'multiple') {
            $query_type = ($type == 'multiselect') ? 'multiselectmultiple' : 'multiple';

            /** current value **/
            $current_values = explode(
                ',',
                stripslashes(wpl_request::getVar('sf_' . $query_type . '_' . $field_data['table_column'], ''))
            );
            if (trim($current_values[0] ?? '') == '') {
                $current_values = array();
            }

            $html .= '<div class="wpl_searchwid_' . $field_data['table_column'] . '_multiselect_container">
		<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" class="wpl_searchmod_' . $field_data['table_column'] . '_multiselect" id="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" multiple="multiple">';

            foreach ($options['params'] as $option) {
                if (!$option['enabled']) {
                    continue;
                }

                $html .= '<option value="' . $option['key'] . '" ' . (in_array(
                        $option['key'],
                        $current_values
                    ) ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $option['value'] ?? 'Error'
                    ) . '</option>';
            }

            $html .= '</select></div>';
        } elseif ($show == 'checkboxes') {
            $query_type = ($type == 'multiselect') ? 'multiselectmultiple' : 'multiple';

            /** current value **/
            $current_values = explode(
                ',',
                stripslashes(wpl_request::getVar('sf_' . $query_type . '_' . $field_data['table_column'], ''))
            );

            $i = 0;
            foreach ($options['params'] as $option) {
                if (!$option['enabled']) {
                    continue;
                }

                $i++;
                $html .= '<input ' . (in_array(
                        $option['key'],
                        $current_values
                    ) ? 'checked="checked"' : '') . ' name="chk' . $widget_id . '_select_' . $field_data['table_column'] . '" type="checkbox" value="' . $option['key'] . '" id="chk' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_add_to_multiple' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="chk' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $option['value'] ?? 'Error'
                    ) . '</label>';
            }

            $render_current_value = implode(',', $current_values);
            if (!empty($render_current_value) and !stristr($render_current_value, ',')) {
                $render_current_value = $render_current_value . ',';
            }

            $html .= '<input value="' . $render_current_value . '" type="hidden" id="sf' . $widget_id . '_multiple_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" />';
        } elseif ($show == 'radios') {
            $i = 0;
            if ($any) {
                $html .= '<input ' . ($current_value == -1 ? 'checked="checked"' : '') . ' name="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '" type="radio" value="-1" id="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_select_radio' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $field['name']
                    ) . '</label>';
            }

            foreach ($options['params'] as $option) {
                if (!$option['enabled']) {
                    continue;
                }

                $i++;
                $html .= '<input ' . ($current_value == $option['key'] ? 'checked="checked"' : '') . ' name="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '" type="radio" value="' . $option['key'] . '" id="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '" onclick="wpl_select_radio' . $widget_id . '(this.value, this.checked, \'' . $field_data['table_column'] . '\');"><label for="rdo' . $widget_id . '_select_' . $field_data['table_column'] . '_' . $i . '">' . wpl_esc::return_html_t(
                        $option['value'] ?? 'Error'
                    ) . '</label>';
            }

            $html .= '<input value="' . $current_value . '" type="hidden" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" name="sf' . $widget_id . '_' . $type . '_' . $field_data['table_column'] . '" />';
        } elseif ($show == 'predefined') {
            $predefined_types = is_array($field['extoption']) ? implode(',', $field['extoption']) : null;

            if (is_array($field['extoption'])) {
                $type = 'multiple';
            }

            $html .= '<input name="sf' . $widget_id . '_' . $type . '_' . $field_data['table_column'] . '" type="hidden" value="' . $predefined_types . '" id="sf' . $widget_id . '_' . $type . '_' . $field_data['table_column'] . '" />';
        } elseif ($show == 'select-predefined') {
            $current_values = explode(
                ',',
                stripslashes(wpl_request::getVar('sf_' . $query_type . '_' . $field_data['table_column'], ''))
            );
            if (trim($current_values[0] ?? '') == '') {
                $current_values = array();
            }

            $html .= '<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field_data['table_column'] . ' wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" onchange="wpl_property_type_changed' . $widget_id . '(this.value);">';
            if ($any) {
                $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';
            }

            foreach ($options['params'] as $option) {
                if (!$option['enabled']) {
                    continue;
                }

                if (is_array($field['extoption']) and in_array($option['key'], $field['extoption'])) {
                    $html .= '<option value="' . $option['key'] . '" ' . (in_array(
                            $option['key'],
                            $current_values
                        ) ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                            $option['value'] ?? 'Error'
                        ) . '</option>';
                }
            }

            $html .= '</select>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/gallery', 'widget_search_frontend_general_gallery', 10, 8);
