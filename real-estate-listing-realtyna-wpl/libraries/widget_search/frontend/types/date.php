<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_date(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if (in_array($type, array('date', 'datetime'))) {
        $date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
        $jqdate_format = $date_format_arr[1];

        /** MIN/MAX extoptions **/
        $extoptions = explode(',', $field['extoption']);

        if (isset($extoptions[0]) and trim(
                $extoptions[0] ?? ''
            ) != '' and ($extoptions[0] == 'now' or $extoptions[0] == 'minimum_date')) {
            $min_value = date("Y-m-d");
        } else {
            $min_value = (isset($extoptions[0]) and trim($extoptions[0] ?? '') != '') ? $extoptions[0] : '1990-01-01';
        }

        if (isset($extoptions[1]) and trim($extoptions[1] ?? '') != '' and $extoptions[1] == 'now') {
            $max_value = date("Y-m-d");
        } else {
            $max_value = (isset($extoptions[1]) and trim($extoptions[1] ?? '') != '') ? $extoptions[1] : '2030-01-01';
        }

        $show_icon = (isset($extoptions[2]) and trim($extoptions[2] ?? '') != '') ? $extoptions[2] : 0;

        $mindate = explode('-', $min_value);
        $maxdate = explode('-', $max_value);

        switch ($field['type']) {
            case 'datepicker':
                $show = 'datepicker';
                break;
        }

        $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';

        if ($show == 'datepicker') {
            /** current value **/
            $current_min_value = wpl_request::getVar('sf_datemin_' . $field_data['table_column'], '');
            $current_max_value = wpl_request::getVar('sf_datemax_' . $field_data['table_column'], '');

            $html .= '<div class="wpl_search_widget_from_container"><label class="wpl_search_widget_from_label" for="sf' . $widget_id . '_datemin_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    'Min'
                ) . '</label><input type="text" placeholder="' . sprintf(
                    wpl_esc::return_attr_t('Min %s'),
                    wpl_esc::return_attr_t($field['name'])
                ) . '" name="sf' . $widget_id . '_datemin_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_datemin_' . $field_data['table_column'] . '" value="' . ($current_min_value != '' ? esc_attr(
                    $current_min_value
                ) : '') . '" /></div>';
            $html .= '<div class="wpl_search_widget_to_container"><label class="wpl_search_widget_to_label" for="sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '">' . wpl_esc::return_html_t(
                    'Max'
                ) . '</label><input type="text" placeholder="' . sprintf(
                    wpl_esc::return_attr_t('Max %s'),
                    wpl_esc::return_attr_t($field['name'])
                ) . '" name="sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '" id="sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '" value="' . ($current_max_value != '' ? esc_attr(
                    $current_max_value
                ) : '') . '" /></div>';

            wpl_html::set_footer(
                '<script type="text/javascript">
		jQuery(document).ready(function()
		{
			var change_date_max_value' . $widget_id . ' = function()
			{
				var date_start_value = wplj("#sf' . $widget_id . '_datemin_' . $field_data['table_column'] . '").val();
                var date_end_value = wplj("#sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '").val();

                var d_start = new Date(wpl_date_convert(date_start_value, "' . addslashes($jqdate_format) . '"));
                var d_end = new Date(wpl_date_convert(date_end_value, "' . addslashes($jqdate_format) . '"));

                if(date_end_value == "") wplj("#sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '").val(date_start_value);
                if(d_start > d_end) wplj("#sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '").val(date_start_value);
			};

			wplj("#sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '").datepicker(
			{
				dayNamesMin: ["' . wpl_esc::return_attr_t('SU') . '", "' . wpl_esc::return_attr_t(
                    'MO'
                ) . '", "' . wpl_esc::return_attr_t('TU') . '", "' . wpl_esc::return_attr_t(
                    'WE'
                ) . '", "' . wpl_esc::return_attr_t('TH') . '", "' . wpl_esc::return_attr_t(
                    'FR'
                ) . '", "' . wpl_esc::return_attr_t('SA') . '"],
				dayNames: 	 ["' . wpl_esc::return_attr_t('Sunday') . '", "' . wpl_esc::return_attr_t(
                    'Monday'
                ) . '", "' . wpl_esc::return_attr_t('Tuesday') . '", "' . wpl_esc::return_attr_t(
                    'Wednesday'
                ) . '", "' . wpl_esc::return_attr_t('Thursday') . '", "' . wpl_esc::return_attr_t(
                    'Friday'
                ) . '", "' . wpl_esc::return_attr_t('Saturday') . '"],
				monthNames:  ["' . wpl_esc::return_attr_t('January') . '", "' . wpl_esc::return_attr_t(
                    'February'
                ) . '", "' . wpl_esc::return_attr_t('March') . '", "' . wpl_esc::return_attr_t(
                    'April'
                ) . '", "' . wpl_esc::return_attr_t('May') . '", "' . wpl_esc::return_attr_t(
                    'June'
                ) . '", "' . wpl_esc::return_attr_t('July') . '", "' . wpl_esc::return_attr_t(
                    'August'
                ) . '", "' . wpl_esc::return_attr_t('September') . '", "' . wpl_esc::return_attr_t(
                    'October'
                ) . '", "' . wpl_esc::return_attr_t('November') . '", "' . wpl_esc::return_attr_t('December') . '"],
				dateFormat: "' . addslashes($jqdate_format) . '",
				gotoCurrent: true,
				minDate: new Date(' . $mindate[0] . ', ' . intval($mindate[1]) . '-1, ' . $mindate[2] . '),
				maxDate: new Date(' . $maxdate[0] . ', ' . intval($maxdate[1]) . '-1, ' . $maxdate[2] . '),
				changeYear: true,
				yearRange: "' . $mindate[0] . ':' . $maxdate[0] . '",
				' . ($show_icon == '1' ? 'showOn: "both", buttonImage: "' . wpl_global::get_wpl_asset_url(
                        'img/system/calendar2.png'
                    ) . '",' : '') . '
				buttonImageOnly: true,
				onSelect:function() { change_date_max_value' . $widget_id . '(); }
			});

			// Onchange the datemax field when a user change it manually.
			wplj("#sf' . $widget_id . '_datemax_' . $field_data['table_column'] . '").on("change", function() { change_date_max_value' . $widget_id . '(); });

			wplj("#sf' . $widget_id . '_datemin_' . $field_data['table_column'] . '").datepicker(
			{
				dayNamesMin: ["' . wpl_esc::return_attr_t('SU') . '", "' . wpl_esc::return_attr_t(
                    'MO'
                ) . '", "' . wpl_esc::return_attr_t('TU') . '", "' . wpl_esc::return_attr_t(
                    'WE'
                ) . '", "' . wpl_esc::return_attr_t('TH') . '", "' . wpl_esc::return_attr_t(
                    'FR'
                ) . '", "' . wpl_esc::return_attr_t('SA') . '"],
				dayNames: 	 ["' . wpl_esc::return_attr_t('Sunday') . '", "' . wpl_esc::return_attr_t(
                    'Monday'
                ) . '", "' . wpl_esc::return_attr_t('Tuesday') . '", "' . wpl_esc::return_attr_t(
                    'Wednesday'
                ) . '", "' . wpl_esc::return_attr_t('Thursday') . '", "' . wpl_esc::return_attr_t(
                    'Friday'
                ) . '", "' . wpl_esc::return_attr_t('Saturday') . '"],
				monthNames:  ["' . wpl_esc::return_attr_t('January') . '", "' . wpl_esc::return_attr_t(
                    'February'
                ) . '", "' . wpl_esc::return_attr_t('March') . '", "' . wpl_esc::return_attr_t(
                    'April'
                ) . '", "' . wpl_esc::return_attr_t('May') . '", "' . wpl_esc::return_attr_t(
                    'June'
                ) . '", "' . wpl_esc::return_attr_t('July') . '", "' . wpl_esc::return_attr_t(
                    'August'
                ) . '", "' . wpl_esc::return_attr_t('September') . '", "' . wpl_esc::return_attr_t(
                    'October'
                ) . '", "' . wpl_esc::return_attr_t('November') . '", "' . wpl_esc::return_attr_t('December') . '"],
				dateFormat: "' . addslashes($jqdate_format) . '",
				gotoCurrent: true,
				minDate: new Date(' . $mindate[0] . ', ' . intval($mindate[1]) . '-1, ' . $mindate[2] . '),
				maxDate: new Date(' . $maxdate[0] . ', ' . intval($maxdate[1]) . '-1, ' . $maxdate[2] . '),
				changeYear: true,
				yearRange: "' . $mindate[0] . ':' . $maxdate[0] . '",
				' . ($show_icon == '1' ? 'showOn: "both", buttonImage: "' . wpl_global::get_wpl_asset_url(
                        'img/system/calendar2.png'
                    ) . '",' : '') . '
				buttonImageOnly: true,
				onSelect:function() { change_date_max_value' . $widget_id . '(); }
			});
		});
		</script>'
            );
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/date', 'widget_search_frontend_general_date', 10, 8);
add_filter('widget_search/frontend/general/datetime', 'widget_search_frontend_general_date', 10, 8);
