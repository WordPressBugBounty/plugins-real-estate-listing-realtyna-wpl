<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_number(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'number') {
        switch ($field['type']) {
            case 'text':
                $show = 'text';
                break;

            case 'exacttext':
                $show = 'exacttext';
                break;

            case 'minmax':
                $show = 'minmax';
                break;

            case 'minmax_slider':
                $show = 'minmax_slider';
                break;

            case 'minmax_selectbox':
                $show = 'minmax_selectbox';
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

        /** MIN/MAX extoptions **/
        $extoptions = isset($field['extoption']) ? explode(',', $field['extoption']) : array();

        $min_value = (isset($extoptions[0]) and trim($extoptions[0] ?? '') != '') ? $extoptions[0] : 0;
        $max_value = isset($extoptions[1]) ? $extoptions[1] : 100000;
        $division = isset($extoptions[2]) ? $extoptions[2] : 1000;
        if ($field_data['table_column'] == 'build_year') {
            $separator = '';
        } else {
            $separator = isset($extoptions[3]) ? $extoptions[3] : ',';
        }

        $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';

        /** current values **/
        $current_min_value = max(
            stripslashes(wpl_request::getVar('sf_tmin_' . $field_data['table_column'], $min_value)),
            $min_value
        );
        $current_max_value = min(
            stripslashes(wpl_request::getVar('sf_tmax_' . $field_data['table_column'], $max_value)),
            $max_value
        );

        if ($show == 'text') {
            /** current values **/
            $current_value = stripslashes(wpl_request::getVar('sf_text_' . $field_data['table_column'], ''));

            $html .= '<input name="sf' . $widget_id . '_text_' . $field_data['table_column'] . '" type="text" id="sf' . $widget_id . '_text_' . $field_data['table_column'] . '" value="' . esc_attr(
                    $current_value
                ) . '" placeholder="' . wpl_esc::return_attr_t($field['name']) . '" />';
        } elseif ($show == 'exacttext') {
            /** current values **/
            $current_value = stripslashes(wpl_request::getVar('sf_select_' . $field_data['table_column'], ''));

            $html .= '<input name="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" type="text" id="sf' . $widget_id . '_select_' . $field_data['table_column'] . '" value="' . esc_attr(
                    $current_value
                ) . '"  placeholder="' . wpl_esc::return_attr_t($field['name']) . '"/>';
        } elseif ($show == 'minmax') {
            $html .= '<label class="wpl_search_widget_from_label" for="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    'Min'
                ) . '</label>';
            $html .= '<input name="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '" type="text" id="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '" value="' . (trim(
                    wpl_request::getVar('sf_tmin_' . $field_data['table_column'], '')
                ) != '' ? esc_attr(
                    $current_min_value
                ) : (isset($extoptions[0]) ? $extoptions[0] : '')) . '" placeholder="' . wpl_esc::return_attr_t(
                    'Min'
                ) . '" />';

            $html .= '<label class="wpl_search_widget_to_label" for="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    'Max'
                ) . '</label>';
            $html .= '<input name="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '" type="text" id="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '" value="' . (trim(
                    wpl_request::getVar('sf_tmax_' . $field_data['table_column'], '')
                ) != '' ? esc_attr(
                    $current_max_value
                ) : (isset($extoptions[1]) ? $extoptions[1] : '')) . '" placeholder="' . wpl_esc::return_attr_t(
                    'Max'
                ) . '" />';
        } elseif ($show == 'minmax_slider') {
            wpl_html::set_footer(
                '<script type="text/javascript">
		
		function wpl_slider_number_range' . $widget_id . '()
		{
			wplj("#slider' . $widget_id . '_range_' . $field_data['table_column'] . '" ).slider(
			{
				step: ' . $division . ',
				range: true,
				min: ' . (is_numeric($min_value) ? $min_value : 0) . ',
				max: ' . $max_value . ',
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
					wplj("#sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '").val(ui.values[0]);
					wplj("#sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '").val(ui.values[1]);
					' . ((isset($ajax) and $ajax == 2) ? 'wpl_do_search_' . $widget_id . '();' : '') . '
				}
			});
		};
		(function($){
		  wpl_slider_number_range' . $widget_id . '()
		})(jQuery);
		</script>'
            );

            $html .= '<span class="wpl_search_slider_container">
				<input type="hidden" value="' . esc_attr(
                    $current_min_value
                ) . '" name="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '" /><input type="hidden" value="' . $current_max_value . '" name="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '" />
				<span class="wpl_slider_show_value" id="slider' . $widget_id . '_showvalue_' . $field_data['table_column'] . '">' . number_format(
                    (double)$current_min_value,
                    0,
                    '',
                    $separator
                ) . ' - ' . number_format((double)$current_max_value, 0, '', $separator) . '</span>
				<span class="wpl_span_block" style="width: 92%; height: 20px;"><span class="wpl_span_block" id="slider' . $widget_id . '_range_' . $field_data['table_column'] . '" ></span></span>
				</span>';
        } elseif ($show == 'minmax_selectbox') {
            $html .= '<select name="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '">';

            $i = $min_value;
            $html .= '<option value="-1" ' . ($current_min_value == $i ? 'selected="selected"' : '') . '>' . sprintf(
                    wpl_esc::return_html_t('Min %s'),
                    wpl_esc::return_html_t($field_data['name'])
                ) . '</option>';

            $selected_printed = false;
            if ($current_min_value == $i) {
                $selected_printed = true;
            }

            while ($i < $max_value) {
                $html .= '<option value="' . $i . '" ' . (($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>' . $i . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" ' . (($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>' . $max_value . '</option>';
            $html .= '</select>';

            $html .= '<select name="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '">';

            $i = $min_value;
            $html .= '<option value="-1" ' . ($current_max_value == $i ? 'selected="selected"' : '') . '>' . sprintf(
                    wpl_esc::return_html_t('Max %s'),
                    wpl_esc::return_html_t($field_data['name'])
                ) . '</option>';

            $selected_printed = false;
            if ($current_max_value == $i) {
                $selected_printed = true;
            }

            while ($i < $max_value) {
                $html .= '<option value="' . $i . '" ' . (($current_max_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>' . $i . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" '.(($current_max_value == $max_value) ? 'selected="selected"' : '').'>' . $max_value . '</option>';
            $html .= '</select>';
        } elseif ($show == 'minmax_selectbox_plus') {
            $i = $min_value;

            $html .= '<select name="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_tmin_' . $field_data['table_column'] . '">';
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

                $html .= '<option value="' . $i . '" ' . (($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>' . $i . '+</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" '.(($current_min_value == $max_value) ? 'selected="selected"' : '').'>' . $max_value . '+</option>';
            $html .= '</select>';
        } elseif ($show == 'minmax_selectbox_minus') {
            $i = $min_value;

            $html .= '<select name="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_tmax_' . $field_data['table_column'] . '">';
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

                $html .= '<option value="' . $i . '" ' . (($current_max_value == $i and !$selected_printed) ? 'selected="selected"' : '') . '>-' . $i . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" '.(($current_max_value == $max_value) ? 'selected="selected"' : '').'>-' . $max_value . '</option>';
            $html .= '</select>';
        } elseif ($show == 'minmax_selectbox_range') {
            $i = $min_value;

            $current_between_value = stripslashes(wpl_request::getVar('sf_between_' . $field_data['table_column'], ''));

            $html .= '<select name="sf' . $widget_id . '_between_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_between_' . $field_data['table_column'] . '">';
            $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';

            while ($i < $max_value) {
                $range_value = $i . ':' . ($i + $division);
                $html .= '<option value="' . $range_value . '" ' . ($current_between_value == $range_value ? 'selected="selected"' : '') . '>' . number_format(
                        $i,
                        0,
                        '.',
                        $separator
                    ) . ' - ' . number_format(($i + $division), 0, '.', $separator) . '</option>';
                $i += $division;
            }

            $html .= '<option value="' . $max_value . '" ' . ($current_between_value == $max_value ? 'selected="selected"' : '') . '>' . number_format(
                    $max_value,
                    0,
                    '.',
                    $separator
                ) . '+</option>';
            $html .= '</select>';
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/number', 'widget_search_frontend_general_number', 10, 8);
