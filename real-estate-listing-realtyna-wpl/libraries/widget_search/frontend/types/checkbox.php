<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_checkbox(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if (in_array($type, array('checkbox', 'yesno', 'select', 'tag'))) {
        switch ($field['type']) {
            case 'checkbox':
                $show = 'checkbox';
                break;

            case 'yesno':
                $show = 'yesno';
                break;

            case 'select':
                $show = 'select';
                break;
        }

        /** current value **/
        $current_value = wpl_request::getVar('sf_select_' . $field_data['table_column'], -1);

        if ($show == 'checkbox') {
            $html .= '<input value="1" ' . ($current_value == 1 ? 'checked="checked"' : '') . ' name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" type="checkbox" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '_check" onchange="wpl_field_specific_changed' . $widget_id . '(\'' . $field['id'] . '\')" data-specific="' . $specified_children . '" />
        	<label for="sf' . $widget_id . '_select_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>';
        } elseif ($show == 'yesno') {
            $html .= '<input value="1" ' . ($current_value == 1 ? 'checked="checked"' : '') . ' name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" type="checkbox" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '_check yesno" onchange="wpl_field_specific_changed' . $widget_id . '(\'' . $field['id'] . '\')" data-specific="' . $specified_children . '" />
        	<label for="sf' . $widget_id . '_select_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>';
        } elseif ($show == 'select') {
            $html .= '<label for="sf' . $widget_id . '_select_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>
			<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '_select" onchange="wpl_field_specific_changed' . $widget_id . '(\'' . $field['id'] . '\')" data-specific="' . $specified_children . '">
				<option value="-1" ' . ($current_value == -1 ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</option>
				<option value="1" ' . ($current_value == 1 ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                    'Yes'
                ) . '</option>
				<option value="0" ' . ($current_value == 0 ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                    'No'
                ) . '</option>
			</select>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/checkbox', 'widget_search_frontend_general_checkbox', 10, 8);
add_filter('widget_search/frontend/general/yesno', 'widget_search_frontend_general_checkbox', 10, 8);
add_filter('widget_search/frontend/general/tag', 'widget_search_frontend_general_checkbox', 10, 8);
