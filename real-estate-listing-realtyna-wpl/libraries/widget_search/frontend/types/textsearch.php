<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_textsearch(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'textsearch') {
        switch ($field['type']) {
            case 'textarea':
                $show = 'textarea';
                break;

            default:
                $show = 'text';
                break;
        }

        /** current value **/
        $current_value = stripslashes(wpl_request::getVar('sf_textsearch_' . $field_data['table_column'], ''));

        $html .= '<label for="sf' . $widget_id . '_textsearch_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                $field['name']
            ) . '</label>';

        if ($show == 'text') {
            $html .= '<input value="' . esc_attr(
                    $current_value
                ) . '" name="sf' . $widget_id . '_textsearch_' . $field_data['table_column'] . '" type="text" id="sf' . $widget_id . '_textsearch_' . $field_data['table_column'] . '" placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" />';
        } elseif ($show == 'textarea') {
            $html .= '<textarea name="sf' . $widget_id . '_textsearch_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_textsearch_' . $field_data['table_column'] . '">' . esc_textarea(
                    $current_value
                ) . '</textarea>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/textsearch', 'widget_search_frontend_general_textsearch', 10, 8);
