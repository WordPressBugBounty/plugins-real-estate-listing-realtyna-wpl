<?php

defined('_WPLEXEC') or die('Restricted access');
$imports = [
	'addon_calendar',
	'area',
	'checkbox',
	'date',
	'feature',
    'gallery',
    'listings',
    'neighborhood',
    'number',
	'price',
	'property_types',
	'ptcategory',
	'select',
	'separator',
	'text',
	'textarea',
	'textsearch',
    'user_type',
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
