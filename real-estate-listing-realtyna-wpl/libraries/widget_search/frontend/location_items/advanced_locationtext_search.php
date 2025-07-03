<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($show == 'advanced_locationtextsearch' and !$done_this)
{
	/** add scripts and style sheet **/
	wp_enqueue_script('jquery-ui-autocomplete');
		
	/** current value **/
	$current_value = stripslashes(wpl_request::getVar('sf_advancedlocationtextsearch', ''));
	$current_column_value = stripslashes(wpl_request::getVar('sf_advancedlocationcolumn', ''));

	/** element id **/
	$element_id = 'sf'.$widget_id.'_advancedlocationtextsearch';
	$element_column_id = 'sf'.$widget_id.'_advancedlocationcolumn';
	
	$html .= '<div style="position: relative" class="wpl_search_widget_location_level_container" id="wpl'.$widget_id.'_search_widget_location_level_container_advanced_location_text">';
	$html .= '<input class="wpl_search_widget_location_textsearch" value="'.esc_attr($current_value).'" name="'.$element_id.'" id="'.$element_id.'" placeholder="'.wpl_esc::return_attr_t($placeholder).'" />';
	$html .= '<input type="hidden" value="'.esc_attr($current_column_value).'" name="'.$element_column_id.'" id="'.$element_column_id.'" />';

	$suggest_fields = wpl_property::get_suggestion_fields($this->kind);
	if(empty($suggest_fields)) {
		$suggest_fields = [];
	} else {
		$first_fields = [];
		$second_fields = [];
		foreach ($suggest_fields as $field_name => $field_title) {
			if(in_array($field_name, ['location_text', 'mls_id']) and !wpl_global::zap_search_enabled()) {
				$second_fields[] = $field_name;
			} else {
				$first_fields[] = $field_name;
			}
		}
		$suggest_fields = array_filter([implode(',', $first_fields), implode(',', $second_fields), ['keyword']]);
	}
	$suggest_fields = json_encode($suggest_fields);
	wpl_html::set_footer('<script type="text/javascript">
    var ajaxRequest'.$widget_id.' = null;
	var autocomplete_cache'.$widget_id.' = {};
	(function($){$(function()
    {    
		$.widget( "custom.wpl_catcomplete", $.ui.autocomplete,
		{
			create: function()
			{
				this._super();
				this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
			},
			_renderMenu: function(ul, items)
			{
				var that = this, currentCategory = "";
				$.each(items, function(index, item)
				{
				    var li;
				    if(item.title !== currentCategory)
					{
						ul.append( "<li class=\'ui-autocomplete-category\' aria-label=\'" + item.title + "\'>" + item.title + "</li>" );
						currentCategory = item.title;
                    }
                    
                    li = that._renderItemData(ul, item);
                    if(item.title)
                    {
                       li.attr( "aria-label", item.title + " : " + item.value );
                    }
				});
			 }
		});
		
		$("#'.$element_id.'").wpl_catcomplete(
		{
			search: function(){},
			open: function(){$(this).removeClass("ui-corner-all").addClass("ui-corner-top");},
			close: function(){$(this).removeClass("ui-corner-top").addClass("ui-corner-all");},
			select: function(event, ui) 
			{
				wplj("#'.$element_id.'").val(ui.item.value);
				wplj("#'.$element_column_id.'").val(ui.item.column);
				wpl_do_search_'.$widget_id.'();
			},
			source: function (request, response) {
				var term = request.term.toUpperCase(), items = [];
				for (var key in autocomplete_cache'.$widget_id.') {
					if (key === term) {
						response(autocomplete_cache'.$widget_id.'[key]);
						return;
					}
				}

				if (ajaxRequest'.$widget_id.') {
					for (const ajaxRequestItem of ajaxRequest'.$widget_id.') {
						ajaxRequestItem.abort();
					}
					ajaxRequest'.$widget_id.' = [];
				} else {
					ajaxRequest'.$widget_id.' = [];
				}
				$(".advanced_suggestion_loading").show();
				const fields = JSON.parse("' . addslashes($suggest_fields) . '");
				const totalItems = {};
				if (fields) {
					for (const fieldIndex in fields) {
						const field = fields[fieldIndex];
						ajaxRequest'.$widget_id.'.push($.ajax({
							type: "GET",
							url: "' . wpl_global::get_wp_site_url() . '?wpl_format=f:property_listing:ajax&wpl_function=advanced_locationtextsearch_autocomplete&field=" + field + "&kind=' . $this->kind . '&term=" + request.term,
							contentType: "application/json; charset=utf-8",
							success: function (msg) {
								let wplContinue = false;
								for (const ajaxRequestItem of ajaxRequest'.$widget_id.') {
									if(ajaxRequestItem.status === undefined) {
										wplContinue = true;
										break;
									}
								}
								totalItems[fieldIndex] = $.parseJSON(msg); 
								const mergedItems = [...(totalItems[0] ?? []), ...(totalItems[1] ?? []), ...(totalItems[2] ?? [])];
								if (!wplContinue) {
									ajaxRequest'.$widget_id.' = [];
									$(".advanced_suggestion_loading").hide();
									autocomplete_cache'.$widget_id.'[request.term.toUpperCase()] = mergedItems;
								}
								response(mergedItems);
							}
						}));
						for (const ajaxRequestItem of ajaxRequest'.$widget_id.') {
							console.log("ajaxRequestItem", ajaxRequestItem.status);
						}
					}
				}
			},
			width: 260,
			matchContains: true,
			minLength: 1,
			delay: 100
			});
		});
	})(jQuery);
	</script>');

	$html .= '<span style="display: none; position: absolute; right: 5px;" class="advanced_suggestion_loading"><img src="' . wpl_global::get_wpl_asset_url('img/ajax-loader3.gif') . '" /></span>';
	$html .= '</div>';
	$done_this = true;
}