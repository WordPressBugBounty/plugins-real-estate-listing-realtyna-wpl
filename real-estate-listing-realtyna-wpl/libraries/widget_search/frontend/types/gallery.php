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
    if ($type == 'gallery') {
        $current_value = wpl_request::getVar('sf_gallery', -1);

        $html .= '<input value="1" ' . ($current_value == 1 ? 'checked="checked"' : '') . ' name="sf' . $widget_id . '_gallery" type="checkbox" id="sf' . $widget_id . '_gallery" />
              <label for="sf' . $widget_id . '_gallery">' . wpl_esc::return_html_t($field['name']) . '</label>';
    }
    return $html;
}

add_filter('widget_search/frontend/general/gallery', 'widget_search_frontend_general_gallery', 10, 8);
