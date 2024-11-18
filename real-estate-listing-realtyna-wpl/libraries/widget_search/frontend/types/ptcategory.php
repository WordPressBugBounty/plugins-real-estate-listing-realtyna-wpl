<?php
defined('_WPLEXEC') or die('Restricted access');

function widget_search_frontend_general_ptcategory(
    $html,
    $type,
    $options,
    $field,
    $field_data,
    $widget_id,
    $specified_children,
    $ajax
) {
    if ($type == 'ptcategory') {
        switch ($field['type']) {
            case 'select':
                $show = 'select';
                $any = true;
                $label = true;
                break;
        }

        /** current value **/
        $current_value = stripslashes(wpl_request::getVar('sf_ptcategory', -1));
        $categories = wpl_property_types::get_property_type_categories();

        if ($label) {
            $html .= '<label>' . wpl_esc::return_html_t($field['name']) . '</label>';
        }

        if ($show == 'select') {
            $html .= '<select data-placeholder="' . wpl_esc::return_attr_t(
                    $field['name']
                ) . '" name="sf' . $widget_id . '_ptcategory" class="wpl_search_widget_field_' . $field['id'] . '" id="sf' . $widget_id . '__ptcategory">';
            if ($any) {
                $html .= '<option value="-1">' . wpl_esc::return_html_t($field['name']) . '</option>';
            }

            foreach ($categories as $category) {
                $html .= '<option data-id="' . $category['id'] . '" value="' . $category['name'] . '" ' . (strtolower(
                        $current_value
                    ) == strtolower($category['name']) ? 'selected="selected"' : '') . '>' . wpl_esc::return_html_t(
                        $category['name']
                    ) . '</option>';
            }

            $html .= '</select>';

            wpl_html::set_footer(
                '<script type="text/javascript">
        (function($){$(function()
        {
			var select_options = wplj("#wpl_search_form_' . $widget_id . ' .wpl_search_widget_field_property_type").html(); //Saving options in select

			wplj("#sf' . $widget_id . '__ptcategory").on("change", function()
            {
				var category_id = wplj("#sf' . $widget_id . '__ptcategory option:selected").data("id");

				wplj("#wpl_search_form_' . $widget_id . ' .wpl_search_widget_field_property_type option").detach(); //Reset Select
				wplj("#wpl_search_form_' . $widget_id . ' .wpl_search_widget_field_property_type").append(select_options); //Reset Select

                if(category_id)
                {
				    wplj("#wpl_search_form_' . $widget_id . ' .wpl_search_widget_field_property_type").children("option").each(function(){
						if(!wplj(this).hasClass("wpl_pt_parent"+category_id))
						{
							if(wplj(this).attr("value") != "-1")
							{
								wplj(this).detach(); // Removing unwanted options
							}
						}
					});
                }

                wplj("#wpl_search_form_' . $widget_id . ' .wpl_search_widget_field_property_type").trigger("chosen:updated");
            });
            
            wplj("#sf' . $widget_id . '__ptcategory").trigger("change");
        });})(jQuery);
        </script>'
            );
        }
    }
    return $html;
}

add_filter('widget_search/frontend/general/ptcategory', 'widget_search_frontend_general_ptcategory', 10, 8);
