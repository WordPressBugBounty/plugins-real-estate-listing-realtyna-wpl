<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_price(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'price') {
        $unit_type = 4;
        $default_min_value = 0;
        $default_max_value = 1000000;
        $default_division_value = 10000;

        $min_column = $field_data['table_column'];
        $max_column = $field_data['table_column'];
        $unit_query = 'unit';

        if ($type == 'mmprice') {
            $unit_query = 'mmunit';
            $max_column .= '_max';
        }

        /** get units **/
        $units = wpl_units::get_units($unit_type);
        $unit_name = count($units) == 1 ? $units[0]['name'] : '';

        $current_listing = wpl_request::getVar('sf_select_listing', 9);
        $current_listing_parent = wpl_listing_types::get_parent($current_listing);

        /** MIN/MAX extoptions **/
        $extoptions = explode(',', $field['extoption']);

        /** MIN/MAX extoptions Rental **/
        $extoptions2 = explode(',', (isset($field['extoption2']) ? $field['extoption2'] : ''));

        $min_value = (isset($extoptions[0]) and trim($extoptions[0] ?? '') != '') ? $extoptions[0] : 0;
        $max_value = isset($extoptions[1]) ? $extoptions[1] : $default_max_value;
        $division = isset($extoptions[2]) ? $extoptions[2] : $default_division_value;
        $separator = isset($extoptions[3]) ? $extoptions[3] : ',';

        if ($division != 1 && $field['type'] <> 'minmax') {
            while (($max_value - $min_value) / $division > 5000) {
                $division *= 10;
            }
        }

        $min_value_rental = (isset($extoptions2[0]) and trim(
                $extoptions2[0] ?? ''
            ) != '') ? $extoptions2[0] : $min_value;
        $max_value_rental = isset($extoptions2[1]) ? $extoptions2[1] : $max_value;
        $division_rental = isset($extoptions2[2]) ? $extoptions2[2] : $division;
        $separator_rental = isset($extoptions2[3]) ? $extoptions2[3] : $separator;

        if ($division_rental != 1) {
            while (($max_value_rental - $min_value_rental) / $division_rental > 5000) {
                $division_rental *= 10;
            }
        }

        // Detect the currency
        $current_unit = stripslashes(wpl_request::getVar('sf_unit_' . $field_data['table_column'], ''));
        if (trim($current_unit ?? '') == '') {
            $current_unit = wpl_request::getVar('wpl_unit' . $unit_type, null, 'COOKIE');
        } // From unit switcher
        if (trim($current_unit ?? '') == '') {
            $current_unit = $units[0]['id'];
        } // Default currency

        // If the currency is set by currency switcher then change the price ranges accordingly
        if (wpl_request::getVar('wpl_unit' . $unit_type, null, 'COOKIE')) {
            $cookie_unit = wpl_request::getVar('wpl_unit' . $unit_type, null, 'COOKIE');
            $rate = round(wpl_units::convert(1, $units[0]['id'], $cookie_unit));

            if (!$rate or (is_numeric($rate) and $rate <= 0)) {
                $rate = 1;
            }

            $min_value = $min_value * $rate;
            $max_value = $max_value * $rate;
            $division = $division * $rate;

            $min_value_rental = $min_value_rental * $rate;
            $max_value_rental = $max_value_rental * $rate;
            $division_rental = $division_rental * $rate;
        }

        /** current values **/
        $current_min_value = max(stripslashes(wpl_request::getVar('sf_min_' . $min_column, $min_value)), $min_value);
        $current_max_value = min(stripslashes(wpl_request::getVar('sf_max_' . $max_column, $max_value)), $max_value);

        $current_min_value_rental = max(
            stripslashes(wpl_request::getVar('sf_min_' . $min_column, $min_value_rental)),
            $min_value_rental
        );
        $current_max_value_rental = min(
            stripslashes(wpl_request::getVar('sf_max_' . $max_column, $max_value_rental)),
            $max_value_rental
        );

        $hide_listing = 'sale';
        if ($current_listing_parent == 1) {
            $current_min_value_rental = $min_value_rental;
            $current_max_value_rental = $max_value_rental;
            $hide_listing = 'rental';
        }

        $listing_fields = array(
            'sale' => array(
                'min' => $min_value,
                'max' => $max_value,
                'division' => $division,
                'separator' => $separator,
                'cur_min' => $current_min_value,
                'cur_max' => $current_max_value
            ),
            'rental' => array(
                'min' => $min_value_rental,
                'max' => $max_value_rental,
                'division' => $division_rental,
                'separator' => $separator_rental,
                'cur_min' => $current_min_value_rental,
                'cur_max' => $current_max_value_rental
            )
        );

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

        $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';

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
            // Show Placeholders when the client didn't search yet
            if (trim(wpl_request::getVar('sf_min_' . $min_column, '')) == '') {
                $current_min_value = (isset($extoptions[0]) ? $extoptions[0] : '');
            }
            if (trim(wpl_request::getVar('sf_max_' . $max_column, '')) == '') {
                $current_max_value = (isset($extoptions[1]) ? $extoptions[1] : '');
            }

            if ($input_type == 'number') {
                $html .= '<label id="wpl_search_widget_from_label' . $widget_id . '" class="wpl_search_widget_from_label" for="sf' . $widget_id . '_min_' . $min_column . '">' . sprintf(
                        wpl_esc::return_html_t('Min %s'),
                        $field_data['name']
                    ) . '</label>';
            }
            $html .= '<input name="sf' . $widget_id . '_min_' . $min_column . '" type="' . $input_type . '" id="sf' . $widget_id . '_min_' . $min_column . '" step="' . $division . '" min="' . $min_value . '" value="' . $current_min_value . '" placeholder="' . sprintf(
                    wpl_esc::return_attr_t('Min %s'),
                    wpl_esc::return_attr_t($field_data['name'])
                ) . '" />';

            if ($input_type == 'number') {
                $html .= '<label id="wpl_search_widget_to_label' . $widget_id . '" class="wpl_search_widget_to_label" for="sf' . $widget_id . '_max_' . $max_column . '">' . sprintf(
                        wpl_esc::return_html_t('Max %s'),
                        $field_data['name']
                    ) . '</label>';
            }
            $html .= '<input name="sf' . $widget_id . '_max_' . $max_column . '" type="' . $input_type . '" id="sf' . $widget_id . '_max_' . $max_column . '" step="' . $division . '" min="' . $min_value . '" value="' . $current_max_value . '" placeholder="' . sprintf(
                    wpl_esc::return_attr_t('Max %s'),
                    wpl_esc::return_attr_t($field_data['name'])
                ) . '" />';
        } elseif ($show == 'minmax_slider') {
            foreach ($listing_fields as $list => $listing_field) {
                wpl_html::set_footer(
                    '<script type="text/javascript">
            function wpl_slider_price_range' . $widget_id . '_' . $list . '()
            {
                wplj("#slider' . $widget_id . '_range_' . $field_data['table_column'] . '_' . $list . '").slider(
                {
                    step: ' . $listing_field['division'] . ',
                    range: true,
                    min: ' . (is_numeric($listing_field['min']) ? $listing_field['min'] : 0) . ',
                    max: ' . $listing_field['max'] . ',
                    field_id: ' . $field['id'] . ',
                    values: [' . $listing_field['cur_min'] . ', ' . $listing_field['cur_max'] . '],
                    slide: function(event, ui)
                    {
                        v1 = wpl_th_sep' . $widget_id . '(ui.values[0]);
                        v2 = wpl_th_sep' . $widget_id . '(ui.values[1]);
                        wplj("#slider' . $widget_id . '_showvalue_' . $field_data['table_column'] . '_' . $list . '").html(v1+" - "+ v2);
                    },
                    stop: function(event, ui)
                    {
                        wplj("#sf' . $widget_id . '_min_' . $min_column . '_' . $list . '").val(ui.values[0]);
                        wplj("#sf' . $widget_id . '_max_' . $max_column . '_' . $list . '").val(ui.values[1]);
                        ' . ((isset($ajax) and $ajax == 2) ? 'wpl_do_search_' . $widget_id . '();' : '') . '
                    }
                });
            }
            (function($){
            	wpl_slider_price_range' . $widget_id . '_' . $list . '();
            })(jQuery);
            </script>'
                );

                $html .= '<span class="wpl_search_slider_container wpl_listing_price_' . $list . ' ' . ($list == $hide_listing ? 'wpl-util-hidden' : '') . '">
                    <input type="hidden" value="' . $listing_field['cur_min'] . '" name="sf' . $widget_id . '_min_' . $min_column . '" id="sf' . $widget_id . '_min_' . $min_column . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" /><input type="hidden" value="' . $listing_field['cur_max'] . '" name="sf' . $widget_id . '_max_' . $max_column . '" id="sf' . $widget_id . '_max_' . $max_column . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" />
                    <span class="wpl_slider_show_value" id="slider' . $widget_id . '_showvalue_' . $field_data['table_column'] . '_' . $list . '">' . number_format(
                        (double)$listing_field['cur_min'],
                        0,
                        '',
                        $listing_field['separator']
                    ) . ' - ' . number_format((double)$listing_field['cur_max'], 0, '', $listing_field['separator']) . '</span>
                    <span class="wpl_span_block" style="width: 92%; height: 20px;"><span class="wpl_span_block" id="slider' . $widget_id . '_range_' . $field_data['table_column'] . '_' . $list . '"></span></span>
                    </span>';
            }
        } elseif ($show == 'minmax_selectbox') {
            foreach ($listing_fields as $list => $listing_field) {
                $html .= '<span class="wpl_search_slider_container wpl_listing_price_' . $list . ' ' . ($list == $hide_listing ? 'wpl-util-hidden' : '') . '">';
                wpl_html::set_footer(
                    '<script type="text/javascript">
            (function($){$(function()
            {
                wplj("#sf' . $widget_id . '_min_' . $min_column . '_' . $list . '").change(function()
                {
                    var min_value = wplj("#sf' . $widget_id . '_min_' . $min_column . '_' . $list . '").val();
                    wplj("#sf' . $widget_id . '_max_' . $max_column . '_' . $list . ' option").filter(
                        function () {
                            if(parseInt(this.value) < parseInt(min_value)) wplj(this).hide();
                        }
                    );
                    try {wplj("#sf' . $widget_id . '_max_' . $max_column . '_' . $list . '").trigger("chosen:updated");} catch(err) {}
                });
            });})(jQuery);
            </script>'
                );

                $i = $listing_field['min'];
                $html .= '<select  name="sf' . $widget_id . '_min_' . $min_column . '" id="sf' . $widget_id . '_min_' . $min_column . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" data-chosen-opt="width:100px">';
                if ($any) {
                    $html .= '<option value="0" ' . ($listing_field['cur_min'] == $i ? 'selected="selected"' : '') . '>' . sprintf(
                            wpl_esc::return_html_t('Min %s'),
                            wpl_esc::return_html_t($field_data['name'])
                        ) . '</option>';
                }

                while ($i < $listing_field['max']) {
                    if ($i == '0' and $any) {
                        $i += $listing_field['division'];
                        continue;
                    }

                    $html .= '<option value="' . $i . '" ' . (($listing_field['cur_min'] == $i and $i != $listing_field['min']) ? 'selected="selected"' : '') . '>' . $unit_name . ' ' . number_format(
                            $i,
                            0,
                            '.',
                            $listing_field['separator']
                        ) . '</option>';
                    $i += $listing_field['division'];
                }

                $html .= '<option value="' . $listing_field['max'] . '">' . $unit_name . ' ' . number_format(
                        $listing_field['max'],
                        0,
                        '.',
                        $listing_field['separator']
                    ) . '</option>';
                $html .= '</select>';

                $html .= '<select name="sf' . $widget_id . '_max_' . $max_column . '" id="sf' . $widget_id . '_max_' . $max_column . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" data-chosen-opt="width:100px">';
                if ($any) {
                    $html .= '<option value="999999999999" ' . ($listing_field['cur_max'] == $i ? 'selected="selected"' : '') . '>' . sprintf(
                            wpl_esc::return_html_t('Max %s'),
                            wpl_esc::return_html_t($field_data['name'])
                        ) . '</option>';
                }

                $i = $listing_field['min'];

                while ($i < $listing_field['max']) {
                    if ($i == '0' and $any) {
                        $i += $listing_field['division'];
                        continue;
                    }

                    $html .= '<option value="' . $i . '" ' . (($listing_field['cur_max'] == $i and $i != $listing_field['min']) ? 'selected="selected"' : '') . '>' . $unit_name . ' ' . number_format(
                            $i,
                            0,
                            '.',
                            $listing_field['separator']
                        ) . '</option>';
                    $i += $listing_field['division'];
                }

                $html .= '<option value="' . $listing_field['max'] . '">' . $unit_name . ' ' . number_format(
                        $listing_field['max'],
                        0,
                        '.',
                        ','
                    ) . '</option>';
                $html .= '</select>';
                $html .= '</span>';
            }
        } elseif ($show == 'minmax_selectbox_plus') {
            foreach ($listing_fields as $list => $listing_field) {
                $html .= '<span class="wpl_search_slider_container wpl_listing_price_' . $list . ' ' . ($list == $hide_listing ? 'wpl-util-hidden' : '') . '">';
                $i = $listing_field['min'];

                $html .= '<select name="sf' . $widget_id . '_min_' . $min_column . '" id="sf' . $widget_id . '_min_' . $min_column . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" data-chosen-opt="width:100px">';
                $html .= '<option value="-1" ' . ($listing_field['cur_min'] == $i ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $field['name']
                    ) . '</option>';

                $selected_printed = false;
                if ($listing_field['cur_min'] == $i) {
                    $selected_printed = true;
                }

                while ($i < $listing_field['max']) {
                    if ($i == '0') {
                        $i += $listing_field['division'];
                        continue;
                    }

                    $html .= '<option value="' . $i . '" ' . (($listing_field['cur_min'] == $i and !$selected_printed) ? 'selected="selected"' : '') . '>' . $unit_name . ' ' . number_format(
                            $i,
                            0,
                            '.',
                            $listing_field['separator']
                        ) . '+</option>';
                    $i += $listing_field['division'];
                }

                $html .= '<option value="' . $listing_field['max'] . '" ' . ($listing_field['cur_min'] == $i ? 'selected="selected"' : '') . '>' . $unit_name . ' ' . number_format(
                        $listing_field['max'],
                        0,
                        '.',
                        $listing_field['separator']
                    ) . '+</option>';
                $html .= '</select>';
                $html .= '</span>';
            }
        } elseif ($show == 'minmax_selectbox_minus') {
            foreach ($listing_fields as $list => $listing_field) {
                $html .= '<span class="wpl_search_slider_container wpl_listing_price_' . $list . ' ' . ($list == $hide_listing ? 'wpl-util-hidden' : '') . '">';

                $i = $listing_field['min'];
                if (wpl_request::getVar('sf_max_' . $max_column, '-1') == '-1') {
                    $listing_field['cur_max'] = '-1';
                }

                $html .= '<select name="sf' . $widget_id . '_max_' . $max_column . '" id="sf' . $widget_id . '_max_' . $max_column . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" data-chosen-opt="width:100px">';
                $html .= '<option value="-1" ' . ($listing_field['cur_max'] == $i ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $field['name']
                    ) . '</option>';

                $selected_printed = false;
                if ($listing_field['cur_max'] == $i) {
                    $selected_printed = true;
                }

                while ($i < $listing_field['max']) {
                    if ($i == '0') {
                        $i += $listing_field['division'];
                        continue;
                    }

                    $html .= '<option value="' . $i . '" ' . (($listing_field['cur_max'] == $i and !$selected_printed) ? 'selected="selected"' : '') . '>-' . $unit_name . ' ' . number_format(
                            $i,
                            0,
                            '.',
                            $listing_field['separator']
                        ) . '</option>';
                    $i += $listing_field['division'];
                }

                $html .= '<option value="' . $listing_field['max'] . '" ' . ($listing_field['cur_max'] == $i ? 'selected="selected"' : '') . '>-' . $unit_name . ' ' . number_format(
                        $listing_field['max'],
                        0,
                        '.',
                        $listing_field['separator']
                    ) . '</option>';
                $html .= '</select>';
                $html .= '</span>';
            }
        } elseif ($show == 'minmax_selectbox_range') {
            foreach ($listing_fields as $list => $listing_field) {
                $html .= '<span class="wpl_search_slider_container wpl_listing_price_' . $list . ' ' . ($list == $hide_listing ? 'wpl-util-hidden' : '') . '">';

                $i = $listing_field['min'];
                $current_between_value = stripslashes(
                    wpl_request::getVar('sf_between' . $unit_query . '_' . $field_data['table_column'], '')
                );

                $html .= '<select name="sf' . $widget_id . '_between' . $unit_query . '_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_between' . $unit_query . '_' . $field_data['table_column'] . '_' . $list . '" class="wpl_search_widget_price_field ' . ($list == $hide_listing ? 'wpl-exclude-search-widget' : '') . '" data-chosen-opt="width:200px">';
                $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';

                while ($i < $listing_field['max']) {
                    $range_value = $i . ':' . ($i + $listing_field['division']);
                    $html .= '<option value="' . $range_value . '" ' . ($current_between_value == $range_value ? 'selected="selected"' : '') . '>' . $unit_name . ' ' . number_format(
                            $i,
                            0,
                            '.',
                            $listing_field['separator']
                        ) . ' - ' . $unit_name . ' ' . number_format(
                            ($i + $listing_field['division']),
                            0,
                            '.',
                            $listing_field['separator']
                        ) . '</option>';
                    $i += $listing_field['division'];
                }

                $html .= '<option value="' . $listing_field['max'] . '" ' . ($current_between_value == $listing_field['max'] ? 'selected="selected"' : '') . '>' . $unit_name . ' ' . number_format(
                        $listing_field['max'],
                        0,
                        '.',
                        $listing_field['separator']
                    ) . '+</option>';
                $html .= '</select>';
                $html .= '</span>';
            }
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/price', 'widget_search_frontend_general_price', 10, 8);
