<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_textarea(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'textarea') {
        /** current value **/
        $current_value = stripslashes(wpl_request::getVar('sf_text_' . $field_data['table_column'], ''));

        $html .= '<label for="sf' . $widget_id . '_text_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                $field['name']
            ) . '</label>
				<textarea name="sf' . $widget_id . '_text_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_text_' . $field_data['table_column'] . '">' . esc_textarea(
                $current_value
            ) . '</textarea>';
    }
    return $html;
}

add_filter('widget_search/frontend/general/textarea', 'widget_search_frontend_general_textarea', 10, 8);
