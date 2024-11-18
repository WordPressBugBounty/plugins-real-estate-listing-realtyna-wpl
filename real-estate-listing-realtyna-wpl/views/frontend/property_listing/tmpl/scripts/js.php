<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$geolocation_session = wpl_session::get('geolocation');
?>
<script type="text/javascript">
var wpl_ajax = <?php wpl_esc::numeric(wpl_global::check_addon('aps') ? (empty(wpl_global::get_setting('aps_ajax_listing')) ? 0 : wpl_global::get_setting('aps_ajax_listing')) : 0); ?>;
var wpl_listing_request_str = '<?php wpl_esc::js(wpl_global::generate_request_str()); ?>';
var wpl_listing_limit = <?php wpl_esc::numeric(isset($this->model) ? $this->model->limit : wpl_settings::get('default_page_size')); ?>;
var wpl_listing_total_pages = <?php wpl_esc::numeric($this->total_pages); ?>;
var wpl_listing_current_page = <?php wpl_esc::numeric($this->page_number); ?>;
var wpl_listing_last_search_time = 0;

/** CSS Class **/
var wpl_current_property_css_class = '<?php wpl_esc::js($this->property_css_class); ?>';

jQuery(document).ready(function()
{
	var main_win_size = wplj(window).width();
	if((main_win_size <= 480))
	{
		wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').click(function()
		{
			wplj(this).next('ul').stop().slideToggle();
		});
	}

    /** jQuery Triggers **/
    wpl_listing_set_js_triggers();
    wpl_idx_check_existence();

    <?php if(wpl_global::get_setting('geolocation_listings') and !$geolocation_session): ?>
    if(navigator.geolocation)
    {
		const lastRedirect = localStorage.getItem('wpl_last_geolocation');
		if(new Date().getTime() - 86400 > +lastRedirect) {
			navigator.geolocation.getCurrentPosition(wpl_set_geolocation);
		}
    }

    function wpl_set_geolocation(position)
    {
        wpl_listing_request_str = wpl_update_qs('sf_radiussearch_lat', position.coords.latitude, wpl_listing_request_str);
        wpl_listing_request_str = wpl_update_qs('sf_radiussearch_lng', position.coords.longitude, wpl_listing_request_str);
        wpl_listing_request_str = wpl_update_qs('sf_radiussearchradius', '10', wpl_listing_request_str);
        wpl_listing_request_str = wpl_update_qs('sf_radiussearchunit', '13', wpl_listing_request_str);
        wpl_listing_request_str = wpl_update_qs('sf_geolocationstatus', '1', wpl_listing_request_str);
		localStorage.setItem('wpl_last_geolocation', new Date().getTime() + '');
        window.location.href = wpl_qs_apply(window.location.href, wpl_listing_request_str);
    }
    <?php endif; ?>

    wplj(window).resize(function()
    {
        var win_size = wplj(window).width();
        if((win_size <= 480))
        {
            wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').unbind('click').click(function()
            {
                wplj(this).next('ul').slideToggle();
            });
        }
        else if(win_size > 480)
        {
            wplj('#wpl_property_listing_container .wpl_sort_options_container .wpl_sort_options_container_title').unbind('click');
            wplj('#wpl_property_listing_container .wpl_sort_options_container ul').show();
        }
    });
    if(typeof wpl_scroll_pagination == 'function') wpl_scroll_pagination();
});

function wpl_page_sortchange(order_string)
{
    var order_obj = order_string.split('&');

    var order_v1 = order_obj[0].split('=');
    var order_v2 = order_obj[1].split('=');

    // AJAX
    if(wpl_ajax == '1')
    {
        //alert(wpl_current_property_css_class);
        wpl_listing_request_str = wpl_update_qs(order_v1[0], order_v1[1], wpl_listing_request_str);
        wpl_listing_request_str = wpl_update_qs(order_v2[0], order_v2[1], wpl_listing_request_str);

        wplj(".wpl_property_listing_list_view_container").fadeTo(300, 0.5);

		var request_str = 'wpl_format=f:property_listing:list&'+wpl_listing_request_str;
        var full_url = window.location.href;

        try {
            full_url = wpl_qs_apply(full_url, wpl_listing_request_str);
            history.pushState({search: 'WPL'}, "<?php wpl_esc::attr('Search Results'); ?>", full_url);
        }
        catch (err) {
        }

        /** Load Markers **/
        if(typeof wpl_load_map_markers == 'function') wpl_load_map_markers(wpl_listing_request_str, true);

		wplj.ajax(
		{
			url: '<?php wpl_esc::current_url(); ?>',
			data: request_str,
            dataType: 'json',
			type: 'GET',
			async: true,
			cache: false,
			timeout: 30000,
			success: function(data)
			{
                wplj.when( wplj(".wpl_property_listing_list_view_container").html(data.html) ).then(function() {
					wplj(".wpl-sort-options-selectbox .wpl_plist_sort").chosen({ width: 'initial' });

				});

                wplj(".wpl_property_listing_list_view_container").fadeTo(300, 1);
                if(typeof wpl_fix_no_image_size == 'function') setTimeout(function(){wpl_fix_no_image_size();}, 50);
                wpl_listing_enable_view(wpl_current_property_css_class);

                /*Image lazy loading*/
                if(wplj('.wpl_property_listing_container').hasClass('wpl-property-listing-mapview')){
                    wplj(".wpl-property-listing-mapview .wpl_property_listing_listings_container .lazyimg").Lazy({
                        appendScroll: wplj('.wpl-property-listing-mapview .wpl_property_listing_listings_container')
                    });
                } else {
                    wplj(".lazyimg").Lazy();
                }
			}
		});
    }
    // No AJAX
    else
    {
        var url = window.location.href;

        url = wpl_update_qs(order_v1[0], order_v1[1], url);
        url = wpl_update_qs(order_v2[0], order_v2[1], url);

        /** Move to First Page **/
        url = wpl_update_qs('wplpage', '1', url);

        window.location = url;
    }
}

function wpl_pagesize_changed(page_size)
{
    // AJAX
    if(wpl_ajax == '1')
    {
        wpl_listing_request_str = wpl_update_qs('limit', page_size, wpl_listing_request_str);

        /** Move to First Page **/
        wpl_listing_request_str = wpl_update_qs('wplpage', '1', wpl_listing_request_str);

        wplj(".wpl_property_listing_list_view_container").fadeTo(300, 0.5);

		var request_str = 'wpl_format=f:property_listing:list&'+wpl_listing_request_str;
        var full_url = window.location.href;

        try {
            full_url = wpl_qs_apply(full_url, wpl_listing_request_str);
            history.pushState({search: 'WPL'}, "<?php wpl_esc::attr('Search Results'); ?>", full_url);
        }
        catch (err) {
        }

        /** Load Markers **/
        if(typeof wpl_load_map_markers == 'function') wpl_load_map_markers(wpl_listing_request_str, true);

		wplj.ajax(
		{
			url: '<?php wpl_esc::current_url(); ?>',
			data: request_str,
            dataType: 'json',
			type: 'GET',
			async: true,
			cache: false,
			timeout: 30000,
			success: function(data)
			{
                wplj('html, body').animate({ scrollTop: 0 })

                wplj.when( wplj(".wpl_property_listing_list_view_container").html(data.html) ).then(function() {
					wplj(".wpl-sort-options-selectbox .wpl_plist_sort").chosen({ width: 'initial' });
				});
                wplj(".wpl_property_listing_list_view_container").fadeTo(300, 1);

                if(typeof wpl_fix_no_image_size == 'function') setTimeout(function(){wpl_fix_no_image_size();}, 50);
                wpl_listing_enable_view(wpl_current_property_css_class);

                /*Image lazy loading*/
                wplj(".lazyimg").Lazy();
			}
		});
    }
    // No AJAX
    else
    {
        var url = window.location.href;
        url = wpl_update_qs('limit', page_size, url);

        /** Move to First Page **/
        url = wpl_update_qs('wplpage', '1', url);

        window.location = url;
    }
}

function wpl_paginate(page)
{
    // AJAX
    if(wpl_ajax == '1')
    {
        wpl_listing_request_str = wpl_update_qs('wplpage', page, wpl_listing_request_str);

        wplj(".wpl_property_listing_list_view_container").fadeTo(300, 0.5);

		var request_str = 'wpl_format=f:property_listing:list&'+wpl_listing_request_str;
        var full_url = window.location.href;

        try {
            full_url = wpl_qs_apply(full_url, wpl_listing_request_str);
            history.pushState({search: 'WPL'}, "<?php wpl_esc::attr('Search Results'); ?>", full_url);
        }
        catch (err) {
        }

        /** Load Markers **/
        if(typeof wpl_load_map_markers == 'function') wpl_load_map_markers(wpl_listing_request_str, true);

		wplj.ajax(
		{
			url: '<?php wpl_esc::current_url(); ?>',
			data: request_str,
            dataType: 'json',
			type: 'GET',
			async: true,
			cache: false,
			timeout: 30000,
			success: function(data)
			{
                wplj('html, body').animate({ scrollTop: 0 })

                wplj.when( wplj(".wpl_property_listing_list_view_container").html(data.html) ).then(function() {
					wplj(".wpl-sort-options-selectbox .wpl_plist_sort").chosen({ width: 'initial' });
				});
                wplj(".wpl_property_listing_list_view_container").fadeTo(300, 1);

                if(typeof wpl_fix_no_image_size == 'function') setTimeout(function(){wpl_fix_no_image_size();}, 50);
                wpl_listing_enable_view(wpl_current_property_css_class);

                /*Image lazy loading*/
                wplj(".lazyimg").Lazy();
			}
		});
    }
    else
    {
        var url = window.location.href;
        url = wpl_update_qs('wplpage', page, url);

        window.location = url;
    }
}

var wpl_set_property_css_class_once = false;
function wpl_set_property_css_class(pcc)
{
    /** Run this function only once **/
    if(wpl_set_property_css_class_once) return;
    else wpl_set_property_css_class_once = true;

    if((pcc == 'row_box' || pcc == 'grid_box') && typeof wpl_sp_selector_div != 'undefined')
    {
        /** Remove previous scroll listener **/
        wplj(wpl_sp_selector_div).off('scroll', wpl_scroll_pagination_listener);

        wpl_sp_selector_div = window;
        wpl_sp_append_div = '#wpl_property_listing_container';

        /** Add new scroll listener **/
        var wpl_scroll_pagination_listener = wplj(wpl_sp_selector_div).on('scroll', function()
        {
            wpl_scroll_pagination();
        });
    }
    else if(pcc == 'map_box' && typeof wpl_sp_selector_div != 'undefined')
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
    }

    <?php if(isset($this->plisting_googlemap_activity_id) and $this->plisting_googlemap_activity_id): ?>
    // Resize the map
    if(typeof wpl_map<?php wpl_esc::js($this->plisting_googlemap_activity_id); ?> !== 'undefined')
    {
        setTimeout(function()
        {
            google.maps.event.trigger(wpl_map<?php wpl_esc::js($this->plisting_googlemap_activity_id); ?>, 'resize');
        }, 500);
    }
    <?php endif; ?>

    wpl_current_property_css_class = pcc;

    wplj.ajax(
    {
        url: '<?php wpl_esc::current_url(); ?>',
        data: 'wpl_format=f:property_listing:ajax&wpl_function=set_pcc&pcc='+pcc,
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function(data)
        {
            wpl_set_property_css_class_once = false;
        }
    });
}


function wpl_listing_set_js_triggers()
{
    if(wplj(".wpl_property_listing_container").hasClass("wpl-property-listing-mapview"))
    {
        wplj("#wrapper #main_box").addClass("container_full_width");
    }
    else
    {
        wplj("#wrapper #main_box").removeClass("container_full_width");
    }

    wplj(document).on('click', '#list_view', function()
    {
        wplj("#wrapper #main_box").removeClass("container_full_width");
        wplj('#grid_view, #map_view').removeClass('active');
        wplj('#list_view').addClass('active');

        wpl_set_property_css_class('row_box');

        wpl_fix_no_image_size();

        <?php if(wpl_global::check_addon('aps')): ?>
        wplj('.wpl_property_listing_container').removeClass('wpl-property-listing-mapview');
        <?php endif; ?>

        wplj('.wpl_prp_cont').removeClass('grid_box').removeClass('map_box').addClass('row_box');
        wplj('.wpl_property_listing_listings_container').removeClass('wpl-large-up-<?php wpl_esc::attr($this->listing_columns); ?>').removeClass('wpl-medium-up-2').removeClass('wpl-small-up-1');
    });

    wplj(document).on('click', '#grid_view', function()
    {
        wplj("#wrapper #main_box").removeClass("container_full_width");
        wplj('#list_view, #map_view').removeClass('active');
        wplj('#grid_view').addClass('active');

        wpl_set_property_css_class('grid_box');

        wpl_fix_no_image_size();

        <?php if(wpl_global::check_addon('aps')): ?>
        wplj('.wpl_property_listing_container').removeClass('wpl-property-listing-mapview');
        <?php endif; ?>

        wplj('.wpl_prp_cont').removeClass('row_box').removeClass('map_box').addClass('grid_box');

        wplj('.wpl_property_listing_listings_container').addClass('wpl-large-up-<?php wpl_esc::attr($this->listing_columns); ?>').addClass('wpl-medium-up-2').addClass('wpl-small-up-1');
    });

    <?php if(wpl_global::check_addon('aps')): ?>

    wplj(document).on('click', '#map_view', function()
    {

        wplj("#wrapper #main_box").addClass("container_full_width");
        wplj('#list_view, #grid_view').removeClass('active');
        wplj('#map_view').addClass('active');

        wpl_set_property_css_class('map_box');
        wpl_fix_no_image_size();

        wplj('.wpl_property_listing_container').addClass('wpl-property-listing-mapview');
        wplj('.wpl_prp_cont').removeClass('row_box').removeClass('grid_box').addClass('map_box');

        wplj('.wpl_property_listing_listings_container').removeClass('wpl-large-up-<?php wpl_esc::attr($this->listing_columns); ?>').removeClass('wpl-medium-up-2').removeClass('wpl-small-up-1');
    });
    <?php endif; ?>
}

function wpl_listing_enable_view(pcc)
{
    wpl_listing_set_js_triggers();
    
    if(pcc == 'grid_box')
    {
        wplj('#grid_view').trigger('click');
    }
    else if(pcc == 'row_box')
    {
        wplj('#list_view').trigger('click');
    }
    else if(pcc == 'map_box')
    {
        wplj('#map_view').trigger('click');
    }
}

function wpl_generate_rss()
{
    var url = "<?php wpl_esc::url( wpl_property::get_property_rss_link()); ?>";
    var rss;

    rss = wpl_update_qs('wplpage', '', wpl_listing_request_str);
    rss = wpl_update_qs('wplview', '', rss);
    rss = wpl_update_qs('wplpagination', '', rss);

    if(rss)
    {
        if(url.indexOf("?") !== -1)
            rss = '&'+rss;
        else
            rss = '?'+rss;
    }

    window.open(url+rss);
}

function wpl_generate_print_rp()
{
	<?php if(wpl_global::check_addon('PRO') and wpl_global::get_setting('pdf_results_page_method') == 'print'): ?>
	wplj(".wpl_property_listing_listings_container").print();
	<?php else: ?>
    var prp;

    prp = wpl_update_qs('wplpage', '', wpl_listing_request_str);
    prp = wpl_update_qs('wplview', '', prp);
    prp = wpl_update_qs('wplpagination', '', prp);

    window.open("<?php wpl_esc::url(wpl_property::get_property_print_link()); ?>?"+prp);
	<?php endif; ?>
}

<?php if(wpl_global::check_addon('aps')): ?>
function map_view_toggle_listing()
{
	var proprty_listing_map_view_width = wplj('.wpl_property_listing_list_view_container').outerWidth();

    if(wplj('.map_view_handler').hasClass('op'))
    {
        wplj('.wpl_property_listing_list_view_container').animate({'margin-right': '-' + (proprty_listing_map_view_width)}, function()
        {
            wplj('.map_view_handler').toggleClass('op');
            wplj('.wpl_property_listing_list_view_container').removeClass("open").addClass("close");
        });
    }
    else
    {
        wplj('.wpl_property_listing_list_view_container').animate({'margin-right': '0'}, function()
        {
            wplj('.map_view_handler').toggleClass('op');
            wplj('.wpl_property_listing_list_view_container').removeClass("close").addClass("open");
        });
    }
}

function wpl_generate_landing_page_generator()
{
    /** Open lightbox **/
    wplj._realtyna.lightbox.open("#wpl_landing_page_generator_link_lightbox", {reloadPage: false, cssClasses: {wrap : 'wpl-frontend-lightbox-wp'}});

    var ss;

    ss = wpl_update_qs('wplpage', '', wpl_listing_request_str);
    if(ss !== '') ss = wpl_update_qs('wplview', '', ss);

    var request_str = 'wpl_format=f:addon_aps:raw&wplmethod=landing_page'+(ss !== '' ? '&'+ss : '');
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (html) {
			wplj("#wpl_plisting_lightbox_content_container").html(html);
		}
	});
}
<?php endif; ?>

<?php if(wpl_global::check_addon('pro') and $this->favorite_btn): ?>
var wpl_favorites_force_login = <?php wpl_esc::numeric((!wpl_users::get_cur_user_id() and wpl_global::check_addon('membership') and wpl_global::get_setting('favorite_force_login') == '1') ? 1 : 0); ?>;
function wpl_favorite_control(id, mode)
{
    if(wpl_favorites_force_login == '1')
    {
        wpl_favorite_login();
        return;
    }

    var request_str = 'wpl_format=f:property_listing:ajax_pro&wpl_function=favorites_control&pid='+id+'&mode='+mode;

    var loader = Realtyna.ajaxLoader.show(wplj('#wpl_favorite_add_'+id).parent('li'), 'tiny', 'center', true, '#000', 3, {
        backgroundColor: 'rgba(255,255,255,0)'
    });

	wplj.ajax({
		type: 'GET',
		dataType: 'JSON',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function (data) {
			wplj('#wpl_favorite_remove_'+id).toggle().parent('li').toggleClass('added');
			wplj('#wpl_favorite_add_'+id).toggle();
			Realtyna.ajaxLoader.hide(loader);

			if(typeof wpl_load_favorites == 'function')
			{
				wpl_load_favorites(data.pids);
			}

			if(typeof wpl_refresh_searchwidget_counter == 'function')
			{
				wpl_refresh_searchwidget_counter();
			}
		}
	});

    return false;
}

function wpl_favorite_login()
{
    var request_str = 'wpl_format=f:profile_show:raw&wplmethod=login';
	wplj.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: '<?php wpl_esc::wp_url(); ?>',
		data: request_str,
		success: function(html)
		{
			wplj("#wpl_plisting_lightbox_content_container").html(html);

			/** Open lightbox **/
			wplj._realtyna.lightbox.open("#wpl_favorites_lightbox",
				{
					reloadPage: true,
					cssClasses: {wrap: 'wpl-frontend-lightbox-wp', overlay: 'realtyna-lightbox-overlay realtyna-lightbox-overlay-drp'},
					closeOnOverlay: true,
				});
		}
	});
}
<?php endif; ?>

function wpl_idx_check_existence()
{
    var request_str = 'wpl_format=b:addon_idx:ajax&wpl_function=check_payment';

    wplj.ajax(
    {
        type: "POST",
        url: '<?php wpl_esc::current_url(); ?>',
        data: request_str,
        success: function(data)
        {
            if(data.status == '200' || data.status == '201')
            {
                wplj('.wpl-powered-by-realtyna').show();
            }
        }
    });
}

/** ListHub Compliance */
<?php 
if(wpl_global::check_addon('listhub') and $this->settings['listhub_tracking_status'] == '1')
{
    // Listhub Metrics JS
    _wpl_import('libraries.addon_listhub');
    wpl_esc::e(wpl_addon_listhub::lishub_metrics_js());
    
    $listing_ids = '';
    foreach($this->wpl_properties as $id => $property)
    {
        if($id == 'current') continue;

        $listing_id = $this->wpl_properties['current']['raw']['listing_key'] ?? '';
        $listing_ids .= "{lkey:'{$listing_id}'},";
    }

    $listing_ids = rtrim($listing_ids, ', ');
    if($listing_ids) 
    {
        $mp = $this->settings['listhub_tracking_metrics_id'];
        wpl_esc::e("lh('init', {provider:'$mp', test:false});");
		wpl_esc::e("lh('submit', 'SEARCH_DISPLAY', [{$listing_ids}]);");
    }
}
?>
</script>