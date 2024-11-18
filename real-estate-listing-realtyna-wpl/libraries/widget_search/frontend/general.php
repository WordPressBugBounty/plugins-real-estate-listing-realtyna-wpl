<?php

defined('_WPLEXEC') or die('Restricted access');

$imports = [
    'gallery',
    'date',
    'feature',
    'checkbox',
    'listings',
    'neighborhood',
    'number',
    'user_type',
    'textarea',
    'price',
    'area',
    'text',
    'textsearch',
    'separator',
];

foreach ($imports as $import) {
    _wpl_import("libraries.widget_search.frontend.types.$import");
}

$options = apply_filters('widget_search/frontend/general/options', $options, $field_data);

$current = '';

$html .= apply_filters(
    'widget_search/frontend/general/' . $type,
    $current,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $this->ajax
);
