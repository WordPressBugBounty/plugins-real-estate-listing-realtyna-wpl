<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_area(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'area' or $type == 'mmarea' or $type == 'volume' or $type == 'mmvolume' or $type == 'length' or $type == 'mmlength') {
        $default_min_value = 0;

        if ($type == 'volume' or $type == 'mmvolume') {
            $unit_type = 3;
            $default_max_value = 1000;
            $default_division_value = 50;
        } elseif ($type == 'area' or $type == 'mmarea') {
            $unit_type = 2;
            $default_max_value = 10000;
            $default_division_value = 100;
        } elseif ($type == 'length' or $type == 'mmlength') {
            $unit_type = 1;
            $default_max_value = 100;
            $default_division_value = 10;
        }

        /** MIN/MAX extoptions **/
        $extoptions = explode(',', $field['extoption']);

        $min_value = (isset($extoptions[0]) and trim($extoptions[0] ?? '') != '') ? $extoptions[0] : 0;
        $max_value = isset($extoptions[1]) ? $extoptions[1] : $default_max_value;
        $division = isset($extoptions[2]) ? $extoptions[2] : $default_division_value;
        $default_unit_id = isset($extoptions[3]) ? $extoptions[3] : null;
        $separator = isset($extoptions[4]) ? $extoptions[4] : ',';

        // Get Units
        $condition = '';
        if ($default_unit_id) {
            $condition .= wpl_db::prepare(" AND `id` = %d", $default_unit_id);
        }

        $units = wpl_units::get_units($unit_type, 1, $condition);
        $unit_name = count($units) == 1 ? $units[0]['name'] : '';

        switch ($field['type']) {
            case 'minmax':
                $show = 'minmax';
                $input_type = 'number';
                break;

            case 'minmax_slider':
                $show = 'minmax_slider';
                $input_type = 'hidden';
                break;

            case 'minmax_selectbox':
                $show = 'minmax_selectbox';
                $any = true;
                break;

            case 'minmax_selectbox_plus':
                $show = 'minmax_selectbox_plus';
                break;

            case 'minmax_selectbox_minus':
                $show = 'minmax_selectbox_minus';
                break;

            case 'minmax_selectbox_range':
                $show = 'minmax_selectbox_range';
                break;
        }

        $min_column = $field_data['table_column'];
        $max_column = $field_data['table_column'];
        $unit_query = 'unit';

        if ($type == 'mmarea') {
            $unit_query = 'mmunit';
            $max_column .= '_max';
        }

        $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';

        /** current values **/
        $current_unit = stripslashes(
            wpl_request::getVar('sf_' . $unit_query . '_' . $field_data['table_column'], $units[0]['id'])
        );
        $current_min_value = max(stripslashes(wpl_request::getVar('sf_min_' . $min_column, $min_value)), $min_value);
        $current_max_value = min(stripslashes(wpl_request::getVar('sf_max_' . $max_column, $max_value)), $max_value);

        if (count($units) > 1) {
            $html .= '<select class="wpl_search_widget_field_unit" name="sf' . $widget_id . '_' . $unit_query . '_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_' . $unit_query . '_' . $field_data['table_column'] . '">';
            foreach ($units as $unit) {
                $html .= '<option value="' . $unit['id'] . '" ' . ($current_unit == $unit['id'] ? 'selected="selected"' : '') . '>' . $unit['name'] . '</option>';
            }
            $html .= '</select>';
        } elseif (count($units) == 1) {
            $html .= '<input type="hidden" class="wpl_search_widget_field_unit" name="sf' . $widget_id . '_' . $unit_query . '_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_' . $unit_query . '_' . $field_data['table_column'] . '" value="' . $units[0]['id'] . '" />';
        }

        if ($show == 'minmax') {
            if ($input_type == 'number') {
                $html .= '<label id="wpl_search_widget_from_label' . $widget_id . '" class="wpl_search_widget_from_label" for="sf' . $widget_id . '_min_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                        'Min'
                    ) . '</label>';
            }
            $html .= '<input name="sf' . $widget_id . '_min_' . $min_column . '" type="' . $input_type . '" id="sf' . $widget_id . '_min_' . $min_column . '" step="' . $division . '" min="' . $min_value . '" value="' . (trim(
                    wpl_request::getVar('sf_min_' . $min_column, '')
                ) != '' ? $current_min_value : (isset($extoptions[0]) ? $extoptions[0] : '')) . '" placeholder="' . wpl_esc::return_attr_t(
                    'Min'
                ) . '" />';

            if ($input_type == 'number') {
                $html .= '<label id="wpl_search_widget_to_label' . $widget_id . '" class="wpl_search_widget_to_label" for="sf' . $widget_id . '_max_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                        'Max'
                    ) . '</label>';
            }
            $html .= '<input name="sf' . $widget_id . '_max_' . $max_column . '" type="' . $input_type . '" id="sf' . $widget_id . '_max_' . $max_column . '" step="' . $division . '" min="' . $min_value . '" value="' . (trim(
                    wpl_request::getVar('sf_max_' . $max_column, '')
                ) != '' ? $current_max_value : (isset($extoptions[1]) ? $extoptions[1] : '')) . '" placeholder="' . wpl_esc::return_attr_t(
                    'Max'
                ) . '" />';
        } elseif ($show == 'minmax_slider') {
            wpl_html::set_footer(
                '<script type="text/javascript">
        function wpl_slider_area_range' . $widget_id . '()
        {
            wplj("#slider' . $widget_id . '_range_' . $field_data['table_column'] . '").slider(
            {
                step: ' . $division . ',
                range: true,
                min: ' . (is_numeric($min_value) ? $min_value : 0) . ',
                max: ' . (is_numeric($max_value) ? $max_value : 0) . ',                
                field_id: ' . $field['id'] . ',
                values: [' . $current_min_value . ', ' . $current_max_value . '],
                slide: function(event, ui)
                {
                    v1 = wpl_th_sep' . $widget_id . '(ui.values[0]);
                    v2 = wpl_th_sep' . $widget_id . '(ui.values[1]);
                    wplj("#slider' . $widget_id . '_showvalue_' . $field_data['table_column'] . '").html(v1+" - "+ v2);
                },
                stop: function(event, ui)
                {
                    wplj("#sf' . $widget_id . '_min_' . $min_column . '").val(ui.values[0]);
                    wplj("#sf' . $widget_id . '_max_' . $max_column . '").val(ui.values[1]);
                    ' . ((isset($ajax) and $ajax == 2) ? 'wpl_do_search_' . $widget_id . '();' : '') . '
                }
            });
        };
        (function($){
        	wpl_slider_area_range' . $widget_id . '();
        })(jQuery);
        </script>'
            );

            $html .= '<span class="wpl_search_slider_container">
            <input type="hidden" value="' . $current_min_value . '" name="sf' . $widget_id . '_min_' . $min_column . '" id="sf' . $widget_id . '_min_' . $min_column . '" />
            <input type="hidden" value="' . $current_max_value . '" name="sf' . $widget_id . '_max_' . $max_column . '" id="sf' . $widget_id . '_max_' . $max_column . '" />
            <span class="wpl_slider_show_value" id="slider' . $widget_id . '_showvalue_' . $field_data['table_column'] . '">' . number_format(
                    (double)$current_min_value,
                    0,
                    '',
                    $separator
                ) . ' - ' . number_format((double)$current_max_value, 0, '', $separator) . '</span>
            <span class="wpl_span_block" style="width: 92%; height: 20px;"><span class="wpl_span_block" id="slider' . $widget_id . '_range_' . $field_data['table_column'] . '"></span></span>
        </span>';
        } elseif ($show == 'minmax_selectbox') {
            wpl_html::set_footer(
                '<script type="text/javascript">
        (function($){$(function()
        {
            wplj("#sf' . $widget_id . '_min_' . $min_column . '" ).change(function()
            {
                var min_value = wplj("#sf' . $widget_id . '_min_' . $min_column . '" ).val();
                wplj("#sf' . $widget_id . '_max_' . $max_column . ' option").filter(function()
                {
                    if(parseInt(this.value) < parseInt(min_value)) wplj(this).hide();
                });
                
                try {wplj("#sf' . $widget_id . '_max_' . $max_column . '").trigger("chosen:updated");} catch(err) {}
            });
        });})(jQuery);
        </script>'
            );

            $i = $min_value;
            $html .= '<select name="sf' . $widget_id . '_min_' . $min_column . '" id="sf' . $widget_id . '_min_' . $min_column . '">';
            if ($any) {
                $html .= '<option value="0" ' . ($current_min_value == $i ? 'selected="selected"' : '') . '>' . sprintf(
                        wpl_esc::return_html_t('Min %s'),
                        wpl_esc::return_html_t($field_data['name'])
                    ) . '</option>';
            }

            while ($i < $max_value) {
                if ($i == '0' and $any) {
                    $i += $division;
                    continue;
                }

                $decimal = 0;
                if (is_float($i)) {
                    $decimal = 1;
                }

                $html .= '<option value="' . $i . '" ' . (($current_min_value == $i and $i != $default_min_value) ? 'selected="selected"' : '') . '>' . number_format(
                        $i,
                        $decimal,
                        '.',
                        $separator
                    ) . ' ' . $unit_name . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '">' . number_format(
                    $max_value,
                    0,
                    '.',
                    $separator
                ) . ' ' . $unit_name . '</option>';
            $html .= '</select>';

            $html .= '<select name="sf' . $widget_id . '_max_' . $max_column . '" id="sf' . $widget_id . '_max_' . $max_column . '">';
            if ($any) {
                $html .= '<option value="999999999999" ' . ($current_max_value == $i ? 'selected="selected"' : '') . '>' . sprintf(
                        wpl_esc::return_html_t('Max %s'),
                        wpl_esc::return_html_t($field_data['name'])
                    ) . '</option>';
            }

            $i = $min_value;

            while ($i < $max_value) {
                if ($i == '0' and $any) {
                    $i += $division;
                    continue;
                }

                $decimal = 0;
                if (is_float($i)) {
                    $decimal = 1;
                }

                $html .= '<option value="' . $i . '" ' . (($current_max_value == $i and $i != $default_min_value) ? 'selected="selected"' : '') . '>' . number_format(
                        $i,
                        $decimal,
                        '.',
                        $separator
                    ) . ' ' . $unit_name . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '">' . number_format(
                    $max_value,
                    0,
                    '.',
                    $separator
                ) . ' ' . $unit_name . '</option>';
            $html .= '</select>';
        } elseif ($show == 'minmax_selectbox_plus') {
            $i = $min_value;

            $html .= '<select name="sf' . $widget_id . '_min_' . $min_column . '" id="sf' . $widget_id . '_min_' . $min_column . '">';
            $html .= '<option value="-1" ' . ($current_min_value == $i ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</option>';

            $selected_printed = false;
            if ($current_min_value == $i) {
                $selected_printed = true;
            }

            while ($i < $max_value) {
                if ($i == '0') {
                    $i += $division;
                    continue;
                }

                $decimal = 0;
                if (is_float($i)) {
                    $decimal = 1;
                }

                $html .= '<option value="' . $i . '" ' . (($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>' . number_format(
                        $i,
                        $decimal,
                        '.',
                        $separator
                    ) . '+ ' . $unit_name . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" ' . ($current_min_value == $i ? 'selected="selected"' : '') . '>' . number_format(
                    $max_value,
                    0,
                    '.',
                    ','
                ) . '+ ' . $unit_name . '</option>';
            $html .= '</select>';
        } elseif ($show == 'minmax_selectbox_minus') {
            $i = $min_value;
            if (wpl_request::getVar('sf_max_' . $max_column, '-1') == '-1') {
                $current_max_value = '-1';
            }

            $html .= '<select name="sf' . $widget_id . '_max_' . $max_column . '" id="sf' . $widget_id . '_max_' . $max_column . '">';
            $html .= '<option value="-1" ' . ($current_max_value == $i ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                    $field['name']
                ) . '</option>';

            $selected_printed = false;
            if ($current_max_value == $i) {
                $selected_printed = true;
            }

            while ($i < $max_value) {
                if ($i == '0') {
                    $i += $division;
                    continue;
                }

                $decimal = 0;
                if (is_float($i)) {
                    $decimal = 1;
                }

                $html .= '<option value="' . $i . '" ' . (($current_max_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>-' . number_format(
                        $i,
                        $decimal,
                        '.',
                        $separator
                    ) . ' ' . $unit_name . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" ' . ($current_max_value == $i ? 'selected="selected"' : '') . '>-' . number_format(
                    $max_value,
                    0,
                    '.',
                    $separator
                ) . ' ' . $unit_name . '</option>';
            $html .= '</select>';
        } elseif ($show == 'minmax_selectbox_range') {
            $i = $min_value;

            $current_between_value = stripslashes(
                wpl_request::getVar('sf_betweenunit_' . $field_data['table_column'], '')
            );

            $html .= '<select name="sf' . $widget_id . '_betweenunit_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_betweenunit_' . $field_data['table_column'] . '">';
            $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';

            while ($i < $max_value) {
                $decimal = 0;
                if (is_float($i)) {
                    $decimal = 1;
                }

                $range_value = $i . ':' . ($i + $division);
                $html .= '<option value="' . $range_value . '" ' . ($current_between_value == $range_value ? 'selected="selected"' : '') . '>' . number_format(
                        $i,
                        $decimal,
                        '.',
                        $separator
                    ) . ' - ' . number_format(
                        ($i + $division),
                        $decimal,
                        '.',
                        $separator
                    ) . ' ' . $unit_name . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" ' . ($current_between_value == $max_value ? 'selected="selected"' : '') . '>' . number_format(
                    $max_value,
                    0,
                    '.',
                    $separator
                ) . '+ ' . $unit_name . '</option>';
            $html .= '</select>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/area', 'widget_search_frontend_general_area', 10, 8);
add_filter('widget_search/frontend/general/mmarea', 'widget_search_frontend_general_area', 10, 8);
add_filter('widget_search/frontend/general/volume', 'widget_search_frontend_general_area', 10, 8);
add_filter('widget_search/frontend/general/mmvolume', 'widget_search_frontend_general_area', 10, 8);
add_filter('widget_search/frontend/general/length', 'widget_search_frontend_general_area', 10, 8);
add_filter('widget_search/frontend/general/mmlength', 'widget_search_frontend_general_area', 10, 8);
