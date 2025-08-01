<script type="text/javascript">
var _wplSearchCallbacks<?php wpl_esc::numeric($this->widget_id); ?> = [];

jQuery(document).ready(function()
{
    <?php if($this->more_options_type): ?>
    wplj(document).on('click', '.wpl_search_from_box #more_search_option<?php wpl_esc::numeric($this->widget_id); ?>', function()
    {
        var widget_id = <?php wpl_esc::numeric($this->widget_id); ?>;

        wplj._realtyna.lightbox.open({'href': '#wpl_advanced_search<?php wpl_esc::numeric($this->widget_id); ?>'},
        {
            clearContent: false,
            title: "<?php wpl_esc::attr_t('Advanced Search'); ?>",
            cssClasses: {wrap : 'wpl-frontend-lightbox-wp'},
            callbacks:
            {
                beforeOpen: function()
                {
					var html_simple = wplj('#wpl_default_search_<?php wpl_esc::numeric($this->widget_id); ?>').children().detach();
					wplj('#wpl_form_override_search<?php wpl_esc::numeric($this->widget_id); ?>').append(html_simple);

                },
                afterOpen: function()
                {
                    wplj("#realtyna-js-lightbox-content .hasDatepicker").removeClass('hasDatepicker').datepicker();
                    wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id).show();

					wpl_render_checkbox_select_<?php wpl_esc::numeric($this->widget_id); ?>();

					wplj('#wpl_form_override_search<?php wpl_esc::numeric($this->widget_id); ?> select').chosen('destroy');
					wplj('#wpl_form_override_search<?php wpl_esc::numeric($this->widget_id); ?> select').chosen();

					wplj('#realtyna-js-lightbox-wrapper').css('height', 'auto');
                },
                afterClose: function()
                {
					var html_adv = wplj('#wpl_form_override_search<?php wpl_esc::numeric($this->widget_id); ?>').children().detach();
					wplj('#wpl_default_search_<?php wpl_esc::numeric($this->widget_id); ?>').append(html_adv);

                    wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id).hide();

                    wpl_render_checkbox_select_<?php wpl_esc::numeric($this->widget_id); ?>();
                }
            }
        });
    });
    <?php else: ?>
        wplj('.wpl_search_from_box #more_search_option<?php wpl_esc::numeric($this->widget_id); ?>').on('click', function () {
            var widget_id = wplj(this).attr('data-widget-id');

            if (wplj(this).hasClass('active')) {
                wplj(this).removeClass('active');
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id).slideUp("fast");
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id + ' .wpl_search_field_container').animate({
                    marginLeft: 100 + 'px',
                    opacity: 1
                });
                wplj(this).text("<?php wpl_esc::attr_t('More options'); ?>");
            }
            else {
                wplj(this).addClass('active');
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id).fadeIn();
                wplj('.wpl_search_from_box #wpl_search_from_box_bot' + widget_id + ' .wpl_search_field_container').animate({
                    marginLeft: 0 + 'px',
                    opacity: 1
                });
                wplj(this).text("<?php wpl_esc::attr_t('Fewer options'); ?>");
            }
        })

        wplj(".MD_SEP > .wpl_search_field_container:first-child").on('click', function () {
            wplj(this).siblings(".wpl_search_field_container").slideToggle(400);
        })

        <?php if(isset($bott_div_open) and $bott_div_open): ?>
        wplj(".wpl_search_from_box #more_search_option<?php wpl_esc::numeric($this->widget_id); ?>").trigger('click');
        <?php endif; ?>
    <?php endif; ?>

    <?php if($this->ajax == 2): ?>
    wplj("#wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> input, #wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> select, #wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> textarea").on('change', function () {
        setTimeout("wpl_do_search_<?php wpl_esc::numeric($this->widget_id); ?>()", 300);
    });
    <?php endif; ?>

    <?php if($this->show_total_results): ?>
    wpl_set_total_results_handler_<?php wpl_esc::numeric($this->widget_id); ?>();
    
    // First Run
    wpl_show_total_results_<?php wpl_esc::numeric($this->widget_id); ?>();
    <?php endif; ?>
});

jQuery(document).ajaxComplete(function()
{
	<?php if($this->show_total_results): ?>
    wpl_set_total_results_handler_<?php wpl_esc::numeric($this->widget_id); ?>();
	<?php endif; ?>
});

function wpl_get_request_str_<?php wpl_esc::numeric($this->widget_id); ?>(force)
{
    if(typeof force == 'undefined') force = false;
    
    var ajax = force ? force : <?php wpl_esc::numeric(trim($this->ajax) ? '1' : '0'); ?>;
    request_str = '';
    
    wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input:checkbox").each(function (index, element) {
        id = element.id;
        name = element.name;
        if (name.substring(0, 2) == 'sf') {
            if (wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> #" + id).closest('li').css('display') != 'none') {
                if (element.checked) value = element.value; else value = "-1";
                if (ajax || (!ajax && value != '' && value != '-1')) request_str += "&" + element.name.replace('sf<?php wpl_esc::numeric($this->widget_id); ?>_', 'sf_') + "=" + value;
            }
        }
    });

    wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input:text").each(function (index, element) {
        id = element.id;
        name = element.name;
        if (name.substring(0, 2) == 'sf') {
            if (wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> #" + id).closest('li').css('display') != 'none') {
                value = element.value.trim();
                if (ajax || (!ajax && value != '' && value != '-1')) request_str += "&" + element.name.replace('sf<?php wpl_esc::numeric($this->widget_id); ?>_', 'sf_') + "=" + encodeURIComponent(value);
            }
        }
    });

    wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type='number']").each(function (index, element) {
        id = element.id;
        name = element.name;
        if (name.substring(0, 2) == 'sf') {
            if (wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> #" + id).closest('li').css('display') != 'none') {
                value = element.value.trim();
                if (ajax || (!ajax && value != '' && value != '-1')) request_str += "&" + element.name.replace('sf<?php wpl_esc::numeric($this->widget_id); ?>_', 'sf_') + "=" + encodeURIComponent(value);
            }
        }
    });

    wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type=hidden]").each(function (index, element) {

        // field excluded
        if(wplj(element).hasClass('wpl-exclude-search-widget')) return;

        id = element.id;
        name = element.name;
        if (name.substring(0, 2) == 'sf') {
            if (wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> #" + id).closest('li').css('display') != 'none') {
                value = element.value;
                if (ajax || (!ajax && value != '' && value != '-1')) request_str += "&" + element.name.replace('sf<?php wpl_esc::numeric($this->widget_id); ?>_', 'sf_') + "=" + encodeURIComponent(value);
            }
        }
    });

    wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> select, #wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> textarea").each(function (index, element) {

        // field excluded
        if(wplj(element).hasClass('wpl-exclude-search-widget')) return;

        id = element.id;
        name = element.name;
        if (name.substring(0, 2) == 'sf') {
            if (wplj(element).closest('li').css('display') != 'none') {
                value = wplj(element).val();
                if(value == null) value = '';

                if (ajax || (!ajax && value != '' && value != '-1')) request_str += "&" + element.name.replace('sf<?php wpl_esc::numeric($this->widget_id); ?>_', 'sf_') + "=" + encodeURIComponent(value);
            }
        }
    });

    /** Adding widget id and kind **/
    return 'widget_id=<?php wpl_esc::numeric($this->widget_id); ?>&kind=<?php wpl_esc::numeric($this->kind); ?>' + request_str;
}

/** main search function **/
function wpl_do_search_<?php wpl_esc::numeric($this->widget_id); ?>(remove_lat_long = false)
{
    request_str = wpl_get_request_str_<?php wpl_esc::numeric($this->widget_id); ?>();

    /** Create full url of search **/
    <?php
        $search_page = $this->get_target_page(($this->target_id ?? NULL));

        // Apply Filters
        @extract(wpl_filters::apply('search_widget_search_page', array('search_page'=>$search_page, 'target_id'=>$this->target_id)));
    ?>
    search_page = '<?php wpl_esc::js($search_page); ?>';

    if (search_page.indexOf('?') >= 0) search_str = search_page + '&' + request_str;
    else search_str = search_page + '?' + request_str;

	if(remove_lat_long) {
		request_str = wpl_update_qs('sf_tmin_googlemap_lt', '', request_str);
		request_str = wpl_update_qs('sf_tmax_googlemap_lt', '', request_str);
		request_str = wpl_update_qs('sf_tmin_googlemap_ln', '', request_str);
		request_str = wpl_update_qs('sf_tmax_googlemap_ln', '', request_str);
	}
    <?php if(!$this->ajax): ?>
    wpl_do_search_no_ajax<?php wpl_esc::numeric($this->widget_id); ?>(search_str);
    <?php elseif($this->ajax): ?>
    if (!wplj('#wpl_property_listing_container').length) wpl_do_search_no_ajax<?php wpl_esc::numeric($this->widget_id); ?>(search_str);
    else wpl_do_search_ajax<?php wpl_esc::numeric($this->widget_id); ?>(request_str, search_str);
    <?php endif; ?>

    return false;
}

function wpl_set_total_results_handler_<?php wpl_esc::numeric($this->widget_id); ?>()
{
	wplj("#wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> input, #wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> select, #wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> textarea").off('change.wpl_total_results').on('change.wpl_total_results', function()
	{
		setTimeout("wpl_show_total_results_<?php wpl_esc::numeric($this->widget_id); ?>()", 300);
	});
	
	wplj('.wpl_property_listing_list_view_container').off('DOMSubtreeModified.wpl_total_results').on('DOMSubtreeModified.wpl_total_results', function()
	{
		setTimeout("wpl_show_total_results_<?php wpl_esc::numeric($this->widget_id); ?>()", 300);
	});
}

var wpl_total_results_ongoing<?php wpl_esc::numeric($this->widget_id); ?> = false;
function wpl_show_total_results_<?php wpl_esc::numeric($this->widget_id); ?>()
{
    request_str = wpl_get_request_str_<?php wpl_esc::numeric($this->widget_id); ?>(true);

    <?php if($this->ajax): ?>
	if(typeof wpl_listing_request_str != 'undefined')
	{
		wpl_listing_request_str = wpl_qs_apply(wpl_listing_request_str, request_str);
		request_str = wpl_qs_apply(request_str, wpl_listing_request_str);
	}
	<?php endif; ?>

	if(wpl_total_results_ongoing<?php wpl_esc::numeric($this->widget_id); ?>) return false;
	wpl_total_results_ongoing<?php wpl_esc::numeric($this->widget_id); ?> = true;
    
    wplj.ajax(
    {
        url: '<?php wpl_esc::current_url(); ?>',
        data: 'wpl_format=f:property_listing:ajax&wpl_function=get_total_results&' + request_str,
        dataType: 'json',
        type: 'GET',
        async: true,
        cache: false,
        timeout: 30000,
        success: function (data)
        {
            wplj("#wpl_total_results<?php wpl_esc::numeric($this->widget_id); ?> span").text(data.total);
            wpl_total_results_ongoing<?php wpl_esc::numeric($this->widget_id); ?> = false;
        }
    });
}

function wpl_do_search_no_ajax<?php wpl_esc::numeric($this->widget_id); ?>(search_str) {

    window.location = search_str;
}

function wpl_add_callback_search<?php wpl_esc::numeric($this->widget_id); ?>(func){

    if(typeof func != 'undefined'){

        if(wplj.isFunction(func)){
            _wplSearchCallbacks<?php wpl_esc::numeric($this->widget_id); ?>.push(func);
            return true;
        }

    }

    return false;
}

function wpl_get_callback_search<?php wpl_esc::numeric($this->widget_id); ?>(){
    return _wplSearchCallbacks<?php wpl_esc::numeric($this->widget_id); ?>;
}

function wpl_clear_callback_search<?php wpl_esc::numeric($this->widget_id); ?>(){
    _wplSearchCallbacks<?php wpl_esc::numeric($this->widget_id); ?> = [];
    return true;
}

function wpl_do_search_ajax<?php wpl_esc::numeric($this->widget_id); ?>(request_str, search_str) {
    /** Move to First Page **/
    request_str = wpl_update_qs('wplpage', '1', request_str);

    if (typeof wpl_listing_request_str != 'undefined') {
        wpl_listing_request_str = wpl_qs_apply(wpl_listing_request_str, request_str);
        request_str = wpl_qs_apply(request_str, wpl_listing_request_str);

        search_str = wpl_qs_apply(search_str, request_str);
    }

    /** Load Markers **/
    if (typeof wpl_load_map_markers == 'function') wpl_load_map_markers(request_str, true, true);

    /** Load Multiple Circles **/
    <?php if(wpl_global::check_addon('aps')): ?>
    if (typeof wpl_create_multiple_circles == 'function') wpl_create_multiple_circles(request_str);
    <?php endif; ?>

    wplj(".wpl_property_listing_list_view_container").fadeTo(300, 0.5);

    try {
        history.pushState({search: 'WPL'}, "<?php wpl_esc::attr_t('Search Results'); ?>", search_str);
    }
    catch (err) {
    }

    wplj.ajax(
    {
        url: '<?php wpl_esc::current_url(); ?>',
        data: 'wpl_format=f:property_listing:list&' + request_str,
        dataType: 'json',
        type: 'GET',
        async: true,
        cache: false,
        timeout: 30000,
        success: function (data) {
            wpl_listing_total_pages = data.total_pages;
            wpl_listing_current_page = data.current_page;

            wplj.when( wplj(".wpl_property_listing_list_view_container").html(data.html) ).then(function() {
                wplj(".wpl-sort-options-selectbox .wpl_plist_sort").chosen({ width: 'initial' });
            });
            wplj(".wpl_property_listing_list_view_container").fadeTo(300, 1);

            if(typeof wpl_fix_no_image_size == 'function') setTimeout(function(){wpl_fix_no_image_size();}, 50);
            wpl_listing_enable_view(wpl_current_property_css_class);
            if(typeof wpl_scroll_pagination == 'function' && wpl_current_property_css_class == 'map_box')
            {
                setTimeout(function()
                {
                    /** Remove previous scroll listener **/
                    wplj(wpl_sp_selector_div).off('scroll', wpl_scroll_pagination_listener);

                    wpl_sp_selector_div = '.wpl_property_listing_listings_container';
                    wpl_sp_append_div = '.wpl_property_listing_listings_container';

                    /** Add new scroll listener **/
                    var wpl_scroll_pagination_listener = wplj(wpl_sp_selector_div).on('scroll', function()
                    {
                        wpl_scroll_pagination();
                    });
                }, 50);
            }

            wpl_listing_last_search_time = new Date().getTime();

            var callbacks = wpl_get_callback_search<?php wpl_esc::numeric($this->widget_id); ?>();
            for (var func in callbacks) {
                if (wplj.isFunction(callbacks[func])) {
                    callbacks[func].call();
                }
            }

            /*Image lazy loading*/
            wplj(".lazyimg").Lazy();
        }
    });
}

function wpl_sef_request<?php wpl_esc::numeric($this->widget_id); ?>(request_str) {
    request_str = request_str.slice(1);
    splited = request_str.split("&");
    sef_str = '';
    unsef_str = '';
    var first_param = true;

    for (var i = 0; i < splited.length; i++) {
        splited2 = splited[i].split("=");
        key = splited2[0];
        value = splited2[1];

        if (key.substring(0, 9) == 'sf_select') {
            table_field = splited2[0].replace('sf_select_', '');
            key = wpl_ucfirst(table_field.replace('_', ' '));
            value = splited2[1];

            /** for setting text instead of value **/
            if (value != -1 && value != '' && (table_field == 'listing' || table_field == 'property_type')) {
                field_type = wplj("#sf<?php wpl_esc::numeric($this->widget_id); ?>_select_" + table_field).prop('tagName');
                if (field_type.toLowerCase() == 'select') value = wplj("#sf<?php wpl_esc::numeric($this->widget_id); ?>_select_" + table_field + " option:selected").text();
            }

            /** set to the SEF url **/
            if (value != -1 && value != '') sef_str += '/' + key + ':' + value;
        }
        else {
            if (first_param && value != -1 && value != '') {
                unsef_str += '?' + key + '=' + value;
                first_param = false;
            }
            else if (value != -1 && value != '') {
                unsef_str += '&' + key + '=' + value;
            }
        }
    }

    final_str = sef_str + "/" + unsef_str;
    return final_str.slice(1);
}

function wpl_add_to_multiple<?php wpl_esc::numeric($this->widget_id); ?>(value, checked, table_column) {
    setTimeout("wpl_add_to_multiple<?php wpl_esc::numeric($this->widget_id); ?>_do('" + value + "', " + checked + ", '" + table_column + "');", 30);
}

function wpl_add_to_multiple<?php wpl_esc::numeric($this->widget_id); ?>_do(value, checked, table_column) {
    var values = wplj('#sf<?php wpl_esc::numeric($this->widget_id); ?>_multiple_' + table_column).val();
    values = values.replace(value + ',', '');

    if (checked) values += value + ',';
    wplj('#sf<?php wpl_esc::numeric($this->widget_id); ?>_multiple_' + table_column).val(values);
}

function wpl_select_radio<?php wpl_esc::numeric($this->widget_id); ?>(value, checked, table_column) {
    if (checked) wplj('#sf<?php wpl_esc::numeric($this->widget_id);?>_select_' + table_column).val(value);
}

function wpl_do_reset<?php wpl_esc::numeric($this->widget_id); ?>(exclude, do_search) {
    if (!exclude) exclude = new Array();
    if (!do_search) do_search = false;

    wplj("#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?>").find(':input').each(function () {
        if (exclude.indexOf(this.id) != -1) return;
        switch (this.type) {
            case 'text':
            case 'number':

                if(!wplj(this).parents().hasClass('chosen-choices'))
                {
                    wplj(this).val(null);
                }
                break;
                
            case 'select-multiple':

                wplj(this).find('option:selected').removeAttr('selected');
                wplj(this).trigger('chosen:updated');
                break;

            case 'select-one':

                wplj(this).val(wplj(this).find('option:first').val());
                wplj(this).trigger("chosen:updated");
                break;

            case 'password':
            case 'textarea':

                wplj(this).val('');
                break;

            case 'checkbox':
            case 'radio':

                wplj(this).removeAttr('checked');

                if(wplj(this).parent().find('input').val() == -1) wplj(this).nextAll('input[type="hidden"]').attr('value', '-1');
                else wplj(this).nextAll('input[type="hidden"]').attr('value', '-1');

                break;

            case 'hidden':

                elmid = this.id;
                idmin = elmid.indexOf("min");
                idmax = elmid.indexOf("max");
                idtmin = elmid.indexOf("tmin");
                idtmax = elmid.indexOf("tmax");

                if (idtmin != '-1') {
                    var table_column = elmid.split("_tmin_");
                    table_column = table_column[1];
                    var widget_id = elmid.split("_");
                    widget_id = parseInt(widget_id[0].replace("sf", ""));
                }
                else if (idtmax != '-1') {
                    var table_column = elmid.split("_tmax_");
                    table_column = table_column[1];
                    var widget_id = elmid.split("_");
                    widget_id = parseInt(widget_id[0].replace("sf", ""));
                }
                else if (idmin != '-1') {
                    var table_column = elmid.split("_min_");
                    table_column = table_column[1];
                    var widget_id = elmid.split("_");
                    widget_id = parseInt(widget_id[0].replace("sf", ""));
                }
                else if (idmax != '-1') {
                    var table_column = elmid.split("_max_");
                    table_column = table_column[1];
                    var widget_id = elmid.split("_");
                    widget_id = parseInt(widget_id[0].replace("sf", ""));
                }

                try {
                    if(wplj("#slider" + widget_id + "_range_" + table_column).length > 0){
                        var min_slider_value = wplj("#slider" + widget_id + "_range_" + table_column).slider("option", "min");
                        var max_slider_value = wplj("#slider" + widget_id + "_range_" + table_column).slider("option", "max");

                        wplj("#sf" + widget_id + "_tmin_" + table_column).val(min_slider_value);
                        wplj("#sf" + widget_id + "_tmax_" + table_column).val(max_slider_value);
                        wplj("#sf" + widget_id + "_min_" + table_column).val(min_slider_value);
                        wplj("#sf" + widget_id + "_max_" + table_column).val(max_slider_value);

                        wplj("#slider" + widget_id + "_range_" + table_column).slider("values", 0, min_slider_value);
                        wplj("#slider" + widget_id + "_range_" + table_column).slider("values", 1, max_slider_value);

                        wplj("#slider" + widget_id + "_showvalue_" + table_column).html(wpl_th_sep<?php wpl_esc::numeric($this->widget_id); ?>(min_slider_value) + " - " + wpl_th_sep<?php wpl_esc::numeric($this->widget_id); ?>(max_slider_value));
                    }else{
                        wplj(this).val(null);
                    }
                }
                catch (err) {
                }
        }
    });

    if (do_search) wpl_do_search_<?php wpl_esc::numeric($this->widget_id); ?>();
	
	<?php if($this->show_total_results): ?>
	wpl_show_total_results_<?php wpl_esc::numeric($this->widget_id); ?>();
	<?php endif; ?>
}

function wpl_th_sep<?php wpl_esc::numeric($this->widget_id); ?>(num) {
    let sep = ",";
    let x = num.toString();
    let z = "";

    for (let i = x.length - 1; i >= 0; i--)
        z += x.charAt(i);

    // add separators but undo the trailing one if there
    z = z.replace(/(\d{3})/g, "$1" + sep);

    if (z.slice(-sep.length) == sep)
        z = z.slice(0, -sep.length);

    x = "";
    // reverse again to get back the number
    for (let i = z.length - 1; i >= 0; i--)
        x += z.charAt(i);

    return x;
}

<?php
    $this->create_listing_specific_js();
    $this->create_property_type_specific_js();
    $this->create_field_specific_js();
?>

(function($){

    $(function(){

        try{

            if(typeof $.fn.chosen != 'undefined') $("#wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> select").chosen({ width: 'initial', search_contains:true, enable_split_word_search:false });
            else
                throw 'WPL::Dependency Missing->Chosen library is not available.';

            $('#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input.yesno[type="checkbox"]').checkbox({
                cls: 'jquery-safari-checkbox',
                empty: '<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/empty.png')); ?>'
            });

            $('#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type="checkbox"]:not(.yesno)').checkbox({empty: '<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/empty.png')); ?>'});

            /** make the form empty if searched by listing id **/
            $("#sf<?php wpl_esc::numeric($this->widget_id); ?>_select_mls_id").on("change", function () {
                wpl_do_reset<?php wpl_esc::numeric($this->widget_id); ?>(new Array("sf<?php wpl_esc::numeric($this->widget_id); ?>_select_mls_id"), false);
            });

        }catch(e){
            console.log(e);
        }

        <?php if(wpl_global::check_addon('aps')): ?>
        $(document).on('submit','#wpl_form_override_search<?php wpl_esc::numeric($this->widget_id); ?> form', function()
        {
            wplj._realtyna.lightbox.close();
        });

        // set the selected value in respective tags
        $(document).on('blur','#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type="text"]', function(event)
        {
            element_id = event.target.id;
            value = $('#'+element_id).val();
            $('#'+element_id).attr('value', value);
        });

        $(document).on('blur','#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type="number"]', function(event)
        {
            element_id = event.target.id;
            value = $('#'+element_id).val();
            $('#'+element_id).attr('value', value);
        });

        $(document).on('change','#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> select:not([multiple])', function(event)
        {
            element_id = event.target.id;
            select_box_id = $('#'+element_id).val();
            $('#'+element_id+' option').removeAttr('selected');
            $('#'+element_id+' option[value="'+select_box_id+'"]').prop('selected', true);
        });

        $(document).on('change','#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type="checkbox"]', function(event)
        {
            element_id = event.target.id;
            if($('#'+element_id).is(':checked')) $('#'+element_id).prop('checked', true);
            else $('#'+element_id).removeAttr('checked');
        });

        $(document).on('change','#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type="radio"]', function(event)
        {
            element_id = event.target.id;
            if($('#'+element_id).is(':checked')) $('#'+element_id).prop('checked', true);
            else $('#'+element_id).removeAttr('checked');

        });
        <?php endif; ?>
    });

})(jQuery);

function wpl_render_checkbox_select_<?php wpl_esc::numeric($this->widget_id); ?>()
{
    wplj('#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> .jquery-checkbox, #wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> .jquery-safari-checkbox, #wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> .chosen-container').each(function(ind, element)
    {
        wplj(this).remove();
    });

    wplj('#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input.yesno[type="checkbox"]').checkbox(
    {
        cls: 'jquery-safari-checkbox',
        empty: '<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/empty.png')); ?>'
    });

    wplj('#wpl_searchwidget_<?php wpl_esc::numeric($this->widget_id); ?> input[type="checkbox"]:not(.yesno)').checkbox({empty: '<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/empty.png')); ?>'});

    if(typeof wplj.fn.chosen !== 'undefined')
    {
        wplj("#wpl_search_form_<?php wpl_esc::numeric($this->widget_id); ?> select").each(function()
        {
            wplj(this).show(function()
            {
				wplj(this).chosen('destroy');
                wplj(this).chosen();
            })
        });
    }

    wplj('[id*="slider<?php wpl_esc::numeric($this->widget_id); ?>_range"]').each(function () {
        wplj(this).empty();
    });

    if(typeof wpl_slider_price_range<?php wpl_esc::numeric($this->widget_id); ?>_sale == 'function') {
        wpl_slider_price_range<?php wpl_esc::numeric($this->widget_id); ?>_sale();
    }
    if(typeof wpl_slider_price_range<?php wpl_esc::numeric($this->widget_id); ?>_rental == 'function') {
        wpl_slider_price_range<?php wpl_esc::numeric($this->widget_id); ?>_rental();
    }
    if(typeof wpl_slider_number_range<?php wpl_esc::numeric($this->widget_id); ?> == 'function') {
        wpl_slider_number_range<?php wpl_esc::numeric($this->widget_id); ?>();
    }
    if(typeof wpl_slider_area_range<?php wpl_esc::numeric($this->widget_id); ?> == 'function') {
        wpl_slider_area_range<?php wpl_esc::numeric($this->widget_id); ?>();
    }
}

function wpl_refresh_searchwidget_counter()
{
    wplj.ajax(
    {
        url: '<?php wpl_esc::current_url(); ?>',
        data: 'wpl_format=f:property_listing:ajax&wpl_function=refresh_searchwidget_counter',
        dataType: 'json',
        type: 'GET',
        async: true,
        cache: false,
        timeout: 30000,
        success: function(data)
        {
            wplj('#wpl-addon-save-searches-count<?php wpl_esc::numeric($this->widget_id); ?>').html(data.saved_searches);
            wplj('#wpl-widget-favorites-count<?php wpl_esc::numeric($this->widget_id); ?>').html(data.favorites);
        }
    });  
}
</script>