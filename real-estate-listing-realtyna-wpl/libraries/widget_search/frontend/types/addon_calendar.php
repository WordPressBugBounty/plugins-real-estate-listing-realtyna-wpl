<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_addon_calendar(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'addon_calendar') {
        /** system date format **/
        $date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
        $jqdate_format = $date_format_arr[1];

        $min_value = date("Y-m-d");
        $mindate = explode('-', $min_value);
        $show_icon = 0;

        /** current value **/
        $current_checkin_value = stripslashes(wpl_request::getVar('sf_calendarcheckin', ''));
        $current_checkout_value = stripslashes(wpl_request::getVar('sf_calendarcheckout', ''));

        /** for opening more details **/
        $current_value = $current_checkin_value;

        $html .= '<div class="wpl_search_widget_calendar_search_container">';
        $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';
        $html .= '<input type="text" name="sf' . $widget_id . '_calendarcheckin" id="sf' . $widget_id . '_calendarcheckin" value="' . ($current_checkin_value != '' ? $current_checkin_value : '') . '" placeholder="' . wpl_esc::return_attr_t(
                'Check In'
            ) . '" />';
        $html .= '<input type="text" name="sf' . $widget_id . '_calendarcheckout" id="sf' . $widget_id . '_calendarcheckout" value="' . ($current_checkout_value != '' ? $current_checkout_value : '') . '" placeholder="' . wpl_esc::return_attr_t(
                'Check Out'
            ) . '" />';
        $html .= '</div>';

        wpl_html::set_footer(
            '<script type="text/javascript">
    jQuery(document).ready(function()
    {
        wplj("#sf' . $widget_id . '_calendarcheckin, #sf' . $widget_id . '_calendarcheckout").datepicker(
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
            changeYear: true,
            ' . ($show_icon == '1' ? 'showOn: "both", buttonImage: "' . wpl_global::get_wpl_asset_url(
                    'img/system/calendar2.png'
                ) . '",' : '') . '
            buttonImageOnly: true,
            onSelect:function()
            {
                var date_start_value = wplj("#sf' . $widget_id . '_calendarcheckin").val();
                var date_end_value = wplj("#sf' . $widget_id . '_calendarcheckout").val();
                
                var d_start = new Date(wpl_date_convert(date_start_value, "' . addslashes($jqdate_format) . '"));
                var d_end = new Date(wpl_date_convert(date_end_value, "' . addslashes($jqdate_format) . '"));
                
                if(date_end_value == "") wplj("#sf' . $widget_id . '_calendarcheckout").val(date_start_value);
                if(d_start > d_end) wplj("#sf' . $widget_id . '_calendarcheckout").val(date_start_value);
            },
        });
    });
    </script>'
        );
    }
    return $html;
}

add_filter('widget_search/frontend/general/addon_calendar', 'widget_search_frontend_general_addon_calendar', 10, 8);
