<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_mmnumber(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'mmnumber') {
        switch ($field['type']) {
            case 'text':
                $show = 'text';
                break;

            case 'selectbox':
                $show = 'selectbox';
                break;

            case 'minmax_selectbox':
                $show = 'minmax_selectbox';
                break;
        }

        /** MIN/MAX extoptions **/
        $extoptions = isset($field['extoption']) ? explode(',', $field['extoption']) : array();

        $min_value = (isset($extoptions[0]) and trim($extoptions[0] ?? '') != '') ? $extoptions[0] : 0;
        $max_value = $extoptions[1] ?? 100000;
        $division = $extoptions[2] ?? 1000;
        if ($field_data['table_column'] == 'build_year') {
            $separator = '';
        } else {
            $separator = isset($extoptions[3]) ? $extoptions[3] : ',';
        }

        $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';

        /** current values **/
        $current_value = stripslashes(wpl_request::getVar('sf_mmnumber_'.$field_data['table_column'], $min_value));

        if ($show == 'text') {
            /** current values **/
            $current_value = stripslashes(wpl_request::getVar('sf_mmnumber_'.$field_data['table_column'], NULL));

            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- field labels are WPL table values, their English strings are in the .pot file
            $placeholder = __($field['name'], 'real-estate-listing-realtyna-wpl');

            $html .= '<input name="sf'.$widget_id.'_mmnumber_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_mmnumber_'.$field_data['table_column'].'" value="'.esc_attr($current_value).'" placeholder="'.esc_attr($placeholder).'" />';
        }
        elseif($show == 'selectbox') {
            $i = $min_value;

            $html .= '<select name="sf'.$widget_id.'_mmnumber_'.$field_data['table_column'].'" id="sf'.$widget_id.'_mmnumber_'.$field_data['table_column'].'">';
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- field labels are WPL table values, their English strings are in the .pot file
            $label = __($field['name'], 'real-estate-listing-realtyna-wpl');
            $html .= '<option value="-1" '.($current_value == $i ? 'selected="selected"' : '').'>'.esc_html($label).'</option>';

            $selected_printed = false;
            if($current_value == $i) $selected_printed = true;

            while($i < $max_value)
            {
                if($i == '0')
                {
                    $i += $division;
                    continue;
                }

                $html .= '<option value="'.$i.'" '.(($current_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>'.$i.'</option>';
                $i += $division;
            }

            $html .= '<option value="'.$max_value.'" '.(($current_value == $max_value and !$selected_printed) ? 'selected="selected"' : '').'>'.$max_value.'</option>';
            $html .= '</select>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/mmnumber', 'widget_search_frontend_general_mmnumber', 10, 8);
