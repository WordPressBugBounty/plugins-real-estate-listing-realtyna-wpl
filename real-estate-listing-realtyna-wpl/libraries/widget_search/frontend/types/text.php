<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_text(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'text') {
        switch ($field['type']) {
            case 'checkbox':
            case 'yesno':
                $query_type = 'textyesno';
                break;

            case 'text':
                $query_type = 'text';
                break;

            case 'multiple':
                $query_type = 'multiple';
                break;

            case 'exacttext':
                $query_type = 'select';
                break;
        }

        /** current value **/
        $current_value = stripslashes(wpl_request::getVar('sf_' . $query_type . '_' . $field_data['table_column'], ''));

        if ($field['type'] == 'checkbox') {
            $html .= '<input value="1" ' . ($current_value == 1 ? 'checked="checked"' : '') . ' name="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" type="checkbox" id="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '_check" />
        	<label for="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>';
        } elseif ($field['type'] == 'yesno') {
            $html .= '<input value="1" ' . ($current_value == 1 ? 'checked="checked"' : '') . ' name="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" type="checkbox" id="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" class="wpl_search_widget_field_' . $field['id'] . '_check yesno" />
        	<label for="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>';
        } else {
            $html .= '<label for="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</label>
				<input name="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" type="text" id="sf' . $widget_id . '_' . $query_type . '_' . $field_data['table_column'] . '" value="' . esc_attr(
                    $current_value
                ) . '" placeholder="' . wpl_esc::return_attr_t($field['name']) . '" />';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/text', 'widget_search_frontend_general_text', 10, 8);
