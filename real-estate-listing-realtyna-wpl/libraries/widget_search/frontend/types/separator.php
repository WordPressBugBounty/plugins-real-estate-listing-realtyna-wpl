<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_separator(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'separator') {
        $html .= '<label id="wpl' . $widget_id . '_search_widget_separator_' . $field['id'] . '">' . wpl_esc::return_html_t(
                $field['name']
            ) . '</label>';
    }
    return $html;
}

add_filter('widget_search/frontend/general/separator', 'widget_search_frontend_general_separator', 10, 8);
