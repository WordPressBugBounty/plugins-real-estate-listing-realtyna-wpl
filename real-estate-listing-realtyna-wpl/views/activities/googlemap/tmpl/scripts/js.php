<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

// Load Google Maps API
wpl_global::include_google_maps();

// APS Marker Clustering
if($this->clustering and wpl_global::check_addon('aps'))
{
	if(empty($this->clustering_method) or $this->clustering_method == 'default' ) {
		wpl_extensions::import_javascript((object) ['param1'=>'google-maps-wpl-clustering', 'param2'=>'packages/markerclusterer/js/markerclusterer.min.js']);
	} else {
		wpl_extensions::import_javascript((object) ['param1'=>'google-maps-wpl-clustering', 'param2'=>'js/supercluster.min.js']);
	}
}

$wpl_map_current_limit = wpl_request::getVar('limit', $this->settings['default_page_size']);
$wpl_map_current_limit = intval($wpl_map_current_limit);
$wpl_map_current_page = wpl_request::getVar('wplpage', 1, '', true);
$wpl_map_current_page = intval($wpl_map_current_page);
$wpl_map_search_limit = 0;
if(!empty($this->map_search_limit) and wpl_global::check_addon('aps'))
{
    $wpl_map_search_limit = intval($this->map_search_limit);
    if($wpl_map_search_limit < $wpl_map_current_limit) $wpl_map_search_limit = $wpl_map_current_limit;
}

$map_activities = wpl_activity::get_activities('plisting_position1', 1);
?>
<script type="text/javascript">
var wpl_map<?php wpl_esc::js($this->activity_id); ?>;
var markers_array<?php wpl_esc::js($this->activity_id); ?> = [];
var loaded_markers<?php wpl_esc::js($this->activity_id); ?> = [];
var markers<?php wpl_esc::js($this->activity_id); ?>;
var bounds<?php wpl_esc::js($this->activity_id); ?>;
var infowindow<?php wpl_esc::js($this->activity_id); ?>;
var wpl_map_bounds_extend<?php wpl_esc::js($this->activity_id); ?> = true;
var wpl_map_search_limit<?php wpl_esc::js($this->activity_id); ?> = <?php wpl_esc::js($wpl_map_search_limit) ?>;
var wpl_found_properties<?php wpl_esc::js($this->activity_id); ?> = <?php wpl_esc::js(intval($this->found_properties_count ?? 0)); ?>;
var wpl_map_set_default_geo_point<?php wpl_esc::js($this->activity_id); ?> = true;
var wpl_marker_cluster<?php wpl_esc::js($this->activity_id); ?>;

var wpl_enable_cluster<?php echo $this->activity_id; ?> = <?php echo ($this->clustering and wpl_global::check_addon('aps')) ? 'true' : 'false' ?>;
var wpl_enable_cluster_method<?php echo $this->activity_id; ?> = '<?php echo ($this->clustering and wpl_global::check_addon('aps')) ? $this->clustering_method : '' ?>';
if(typeof google_place_radius == 'undefined') var google_place_radius = 1100;

function wpl_initialize<?php wpl_esc::js($this->activity_id); ?>()
{
	/** create empty LatLngBounds object **/
	bounds<?php wpl_esc::js($this->activity_id); ?> = new google.maps.LatLngBounds();
	var mapOptions = {
		scrollwheel: <?php wpl_esc::js($this->scroll_wheel ?? 'false'); ?>,
		mapTypeId: google.maps.MapTypeId.<?php wpl_esc::js($this->googlemap_view ?? 'ROADMAP'); ?>,
		clickableIcons: false,
		mapTypeControl: true,
		mapTypeControlOptions: {
              mapTypeIds: ['roadmap', 'satellite'],
              style: google.maps.MapTypeControlStyle.DEFAULT,
              position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
		fullscreenControl: false,
		streetViewControl: false,
		zoomControl: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM
		},
		rotateControl: true,
		rotateControlOptions: {
			position: google.maps.ControlPosition.LEFT_CENTER
		}
	};

	/** init map **/
	wpl_map<?php wpl_esc::js($this->activity_id); ?> = new google.maps.Map(document.getElementById('wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>'), mapOptions);
	infowindow<?php wpl_esc::js($this->activity_id); ?> = new google.maps.InfoWindow();

	var load_markers_on_init = false;
	if(wpl_map_search_limit<?php wpl_esc::js($this->activity_id); ?> > 0 && (<?php wpl_esc::numeric($wpl_map_current_page) ?> > 1 || !markers<?php wpl_esc::js($this->activity_id); ?> || (wpl_found_properties<?php wpl_esc::js($this->activity_id); ?> >= <?php wpl_esc::numeric($wpl_map_current_limit); ?>))) load_markers_on_init = true;

	/** load markers **/
    if(load_markers_on_init)
    {
        // just for loading map
        wpl_load_markers<?php wpl_esc::js($this->activity_id); ?>([]);
    }
    else wpl_load_markers<?php wpl_esc::js($this->activity_id); ?>(markers<?php wpl_esc::js($this->activity_id); ?>);

	<?php if(wpl_global::check_addon('spatial') and $this->spatial and wpl_settings::get('spatial_api')): ?>
    (function(map)
    {
        var s = document.createElement('script'),a='<?php wpl_esc::attr( wpl_settings::get('spatial_api')); ?>';
        s.onload=function(){loadSpatialFromKey(a,map)};
        s.charset='UTF-8';s.src='https://cdn.spatial.ai/spatial-1.2.1.min.js';document.head.appendChild(s);
    })(wpl_map<?php wpl_esc::js($this->activity_id); ?>);
  	<?php endif; ?>

    <?php if(isset($this->googlemap_view) and $this->googlemap_view == 'WPL'): ?>
    var styles = [{"featureType": "water", "stylers": [{"color": "#46bcec"},{"visibility": "on"}]},{"featureType": "landscape","stylers": [{"color": "#f2f2f2"}]},{"featureType": "road","stylers": [{"saturation": -100},{"lightness": 45}]},{"featureType": "road.highway","stylers": [{"visibility": "simplified"}]},{"featureType": "administrative","elementType": "labels.text.fill","stylers": [{"color": "#444444"}]},{"featureType": "poi","stylers": [{"visibility": "off"}]}];
    var styledMap = new google.maps.StyledMapType(styles, {name: "WPL Map"});

    wpl_map<?php wpl_esc::js($this->activity_id); ?>.mapTypes.set('map_style', styledMap);
    wpl_map<?php wpl_esc::js($this->activity_id); ?>.setMapTypeId('map_style');
    <?php endif; ?>

    /* Check Google Places */
	if((typeof google_place != 'undefined') && (google_place == 1) && typeof marker != 'undefined')
	{
        var request = {
            location: marker.position,
            radius: google_place_radius
        };

		var service = new google.maps.places.PlacesService(wpl_map<?php wpl_esc::js($this->activity_id); ?>);
		service.search(request, wpl_gplace_callback<?php wpl_esc::js($this->activity_id); ?>);
	}

    if(typeof wpl_dmgfc_init != 'undefined')
    {
        var wpl_dmgfc_init_listener = google.maps.event.addListener(wpl_map<?php wpl_esc::js($this->activity_id); ?>, 'idle', function()
        {
            wpl_dmgfc_init();
            jQuery('.wpl_map_canvas').append('<div class="wpl_dmgfc_container"></div>');

            /** Remove listener **/
            google.maps.event.removeListener(wpl_dmgfc_init_listener);
        });
    }

    // Show get direction form
    wplj('.wpl-map-get-direction').removeClass('wpl-util-hidden');

    <?php if($this->map_search_status): ?>
    // Search on map
	wplj('#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>').append('<div class="wpl_search_on_map"><input class="wpl_map_search_input" id="wpl_map_search_input<?php wpl_esc::js($this->activity_id); ?>" type="search" /></div>');

    var input = document.getElementById('wpl_map_search_input<?php wpl_esc::js($this->activity_id); ?>');
	var searchBox = new google.maps.places.SearchBox(input);
	wpl_map<?php wpl_esc::js($this->activity_id); ?>.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	wplj('.wpl_search_on_map').click(function()
    {
		wplj('#wpl_map_search_input<?php wpl_esc::js($this->activity_id); ?>').fadeToggle();
	});

    wplj('#wpl_map_search_input<?php wpl_esc::js($this->activity_id); ?>').on('change', function()
    {
        if(wplj("#wpl_aps_map_search_toggle_checkbox<?php wpl_esc::js($this->activity_id); ?>").length && !wplj("#wpl_aps_map_search_toggle_checkbox<?php wpl_esc::js($this->activity_id); ?>").is(":checked"))
        {
            wplj("#wpl_aps_map_search_toggle_checkbox<?php wpl_esc::js($this->activity_id); ?>").prop('checked', true);
        }

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': this.value }, function(results, status)
        {
            if(status == google.maps.GeocoderStatus.OK)
            {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();

                // Change the Map Center to searched location
                if(latitude && longitude && results[0].geometry.bounds)
                {
                    bounds<?php wpl_esc::js($this->activity_id); ?> = results[0].geometry.bounds;
                    wpl_map<?php wpl_esc::js($this->activity_id); ?>.fitBounds(bounds<?php wpl_esc::js($this->activity_id); ?>);
                }
            }
        });
    });
    <?php endif; ?>

	// Resize button
	wplj('#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>').append('<div class="wpl_map_size"></div>');
	wplj('.wpl_map_size').on('click', function()
    {
		if(wplj(this).hasClass('active') == true)
        {
			wplj(this).removeClass('active');
			wplj('.wpl_map_canvas').removeClass('wpl_mapfull');
			wplj('.wpl-map-add-ons').removeClass('wpl_fixed');
			wplj('header').show();
		}
        else
        {
			wplj(this).addClass('active');
			wplj('.wpl_map_canvas').addClass('wpl_mapfull');
			wplj('.wpl-map-add-ons').addClass('wpl_fixed');
			wplj('header').hide();
		}

		google.maps.event.trigger(wpl_map<?php wpl_esc::js($this->activity_id); ?>, 'resize');
	});

    if(load_markers_on_init)
    {
        setTimeout(function()
        {
            var request_string = '<?php wpl_esc::attr( http_build_query($_GET)); ?>';
            wpl_load_map_markers(request_string, false);
        }, 200)
    }
}

function wpl_marker<?php wpl_esc::js($this->activity_id); ?>(dataMarker)
{
	if(wplj.inArray(dataMarker.id, loaded_markers<?php wpl_esc::js($this->activity_id); ?>) != '-1') return true;

	<?php if($this->map_property_preview): ?>
	wpl_preview_property_add_events<?php wpl_esc::js($this->activity_id); ?>(dataMarker);
	<?php else: ?>

    var marker_content = `<img src="<?php wpl_esc::wpl_url(); ?>assets/img/listing_types/gicon/${dataMarker.gmap_icon}" alt="map marker" title="map marker">`;
    if(typeof dataMarker.advanced_marker != 'undefined' && dataMarker.advanced_marker != '') marker_content = dataMarker.advanced_marker;

	if(dataMarker.count) {
		let marker_width = 70;
		if(dataMarker.count < 20000) {
			marker_width = 65;
		}
		if(dataMarker.count < 10000) {
			marker_width = 60;
		}
		if(dataMarker.count < 5000) {
			marker_width = 55;
		}
		if(dataMarker.count < 1000) {
			marker_width = 50;
		}
		if(dataMarker.count < 500) {
			marker_width = 45;
		}
		marker_content = '<div style="height: ' + marker_width + 'px;line-height: 45px;width: ' + marker_width + 'px;text-align: center;cursor: pointer;color: white;font-size: 10px;background-color: #1f59b7;border: 2px solid #1448ad;border-radius: 100%;display: flex;justify-content: center;align-items: center;" >' + dataMarker.count + '</div>';
		wpl_enable_cluster<?php echo $this->activity_id; ?> = false;
	} else {
		wpl_enable_cluster<?php echo $this->activity_id; ?> = <?php echo ($this->clustering and wpl_global::check_addon('aps')) ? 'true' : 'false' ?>;
	}
  	marker = new RichMarker({
		position: new google.maps.LatLng(dataMarker.googlemap_lt, dataMarker.googlemap_ln),
		map: <?php wpl_esc::attr($this->show_marker ? 'wpl_map'.$this->activity_id : 'null'); ?>,
		property_ids: dataMarker.pids,
        flat: true,
		content: marker_content,
		title: dataMarker.title
	});

	// zIndex
	marker.setZIndex(1000);

	/** extend the bounds to include each marker's position **/
  	if(wpl_map_bounds_extend<?php wpl_esc::js($this->activity_id); ?>) bounds<?php wpl_esc::js($this->activity_id); ?>.extend(marker.position);

	loaded_markers<?php wpl_esc::js($this->activity_id); ?>.push(dataMarker.id);
  	markers_array<?php wpl_esc::js($this->activity_id); ?>.push(marker);
	if(dataMarker.count) {
		google.maps.event.addListener(marker, "<?php echo $this->infowindow_event; ?>", function (event) {
			wpl_map<?php echo $this->activity_id; ?>.panTo(this.getPosition());
			if(wpl_map<?php echo $this->activity_id; ?>.zoom < 20) {
				wpl_map<?php echo $this->activity_id; ?>.setZoom(wpl_map<?php echo $this->activity_id; ?>.zoom + 2);
			}
		});
	} else {
		google.maps.event.addListener(marker, "<?php wpl_esc::attr($this->infowindow_event); ?>", function(event) {
			/** Don't run APS AJAX search because of boundary change due to opening infowindow **/
			if(typeof wpl_aps_freeze != 'undefined') wpl_aps_freeze = true;

			if(this.html)
			{
				infowindow<?php wpl_esc::js($this->activity_id); ?>.close();
				infowindow<?php wpl_esc::js($this->activity_id); ?>.setContent(this.html);
				infowindow<?php wpl_esc::js($this->activity_id); ?>.open(wpl_map<?php wpl_esc::js($this->activity_id); ?>, this);
			}
			else
			{
				const loading_info_window = `<div><img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader1.gif')); ?>" /></div>`;
				/** AJAX loader **/
				wplj("#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>").append(`<div class="map_search_ajax_loader"><img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader4.gif')); ?>" /></div>`);

				infowindow<?php wpl_esc::js($this->activity_id); ?>.close();
				infowindow<?php wpl_esc::js($this->activity_id); ?>.open(wpl_map<?php wpl_esc::js($this->activity_id); ?>, this);
				this.html = loading_info_window;
				infowindow<?php wpl_esc::js($this->activity_id); ?>.setContent(loading_info_window);
				get_infowindow_html<?php wpl_esc::js($this->activity_id); ?>(this.property_ids, (infowindow_html) => {
					this.html = infowindow_html;
					infowindow<?php wpl_esc::js($this->activity_id); ?>.setContent(infowindow_html);
					wplj(".map_search_ajax_loader").remove();
				});

			}
		});
	}
	<?php endif; ?>
}

function wpl_load_markers<?php wpl_esc::js($this->activity_id); ?>(markers, delete_markers, total = null)
{
	if(delete_markers)
	{
		delete_markers<?php wpl_esc::js($this->activity_id); ?>();
		bounds<?php wpl_esc::js($this->activity_id); ?> = new google.maps.LatLngBounds();
	}
	let zoom = wpl_map<?php wpl_esc::js($this->activity_id); ?>.zoom || 2;
	if(wpl_enable_cluster_method<?php echo $this->activity_id; ?> === 'super_cluster' && zoom <= 16 && total > 0 && markers.length < total) {
		if(markers.length === 0) {
			return;
		}
		const index = new Supercluster({log:true, radius: 40, maxZoom: 16});
		index.load(markers.map(marker => ({geometry: { coordinates: [marker.googlemap_ln, marker.googlemap_lt]}})));
		<?php if(!empty($_GET['sf_tmin_googlemap_lt'])): ?>
		if(wpl_map_bounds_extend<?php wpl_esc::js($this->activity_id); ?>) {
			let WORLD_DIM = {height: 256, width: 256};
			const $mapDiv = wplj('#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>');
			let mapDim = { height: $mapDiv.height(), width: $mapDiv.width() };

			function latRad(lat) {
				let sin = Math.sin(lat * Math.PI / 180);
				let radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
				return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
			}

			function getZoom(mapPx, worldPx, fraction) {
				return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
			}

			const sw = new google.maps.LatLng(<?php wpl_esc::numeric($_GET['sf_tmin_googlemap_lt']); ?>, <?php wpl_esc::numeric($_GET['sf_tmin_googlemap_ln']); ?>);
			const ne = new google.maps.LatLng(<?php wpl_esc::numeric($_GET['sf_tmax_googlemap_lt']); ?>, <?php wpl_esc::numeric($_GET['sf_tmax_googlemap_ln']); ?>);

			let latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;

			let lngDiff = ne.lng() - sw.lng();
			let lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;

			let latZoom = getZoom(mapDim.height, WORLD_DIM.height, latFraction);
			let lngZoom = getZoom(mapDim.width, WORLD_DIM.width, lngFraction);

			zoom = Math.max(latZoom, lngZoom);
		}
		<?php endif; ?>

		const clusters = index.getClusters([-180, -85, 180, 85], zoom);
		bounds<?php wpl_esc::js($this->activity_id); ?> = new google.maps.LatLngBounds();
		let counter = 0;
		for(const cluster of clusters) {
			counter++;
			let marker_count = 0;
			let marker_title = '';
			let pids = '';
			let gmap_icon = markers[0].gmap_icon;
			if(cluster.properties) {
				marker_count = total ? Math.ceil(cluster.properties.point_count * total / wpl_map_search_limit<?php wpl_esc::js($this->activity_id); ?>) : cluster.properties.point_count;
				marker_title = cluster.properties.point_count_abbreviated;
			} else {
				const marker = markers.filter(m => m.googlemap_lt === cluster.geometry.coordinates[1] && m.googlemap_ln === cluster.geometry.coordinates[0]).pop();
				if(marker) {
					pids = marker.pids;
					gmap_icon = marker.gmap_icon;
				}
			}
			wpl_marker<?php wpl_esc::js($this->activity_id); ?>({
				id: cluster.id || counter,
				count: marker_count,
				googlemap_lt: cluster.geometry.coordinates[1],
				googlemap_ln: cluster.geometry.coordinates[0],
				pids: pids,
				gmap_icon: gmap_icon,
				title: marker_title,
			});
		}
		if(wpl_map_bounds_extend<?php wpl_esc::js($this->activity_id); ?>) {
			wpl_map<?php wpl_esc::js($this->activity_id); ?>.fitBounds(bounds<?php wpl_esc::js($this->activity_id); ?>);
			wpl_map<?php wpl_esc::js($this->activity_id); ?>.setZoom(wpl_map<?php wpl_esc::js($this->activity_id); ?>.zoom + 1);
		}
		return;
	}

	for(var i = 0; i < markers.length; i++)
	{
		wpl_marker<?php wpl_esc::js($this->activity_id); ?>(markers[i]);
	}

	if(!markers.length && wpl_map_set_default_geo_point<?php wpl_esc::js($this->activity_id); ?>)
	{
		wpl_map<?php wpl_esc::js($this->activity_id); ?>.setCenter(new google.maps.LatLng(default_lt<?php wpl_esc::js($this->activity_id); ?>, default_ln<?php wpl_esc::js($this->activity_id); ?>));
		wpl_map<?php wpl_esc::js($this->activity_id); ?>.setZoom(parseInt(default_zoom<?php wpl_esc::js($this->activity_id); ?>));
	}
	else
	{
		/** now fit the map to the newly inclusive bounds **/
		if(wpl_map_bounds_extend<?php wpl_esc::js($this->activity_id); ?> && markers.length) wpl_map<?php wpl_esc::js($this->activity_id); ?>.fitBounds(bounds<?php wpl_esc::js($this->activity_id); ?>);

        <?php if($this->clustering and wpl_global::check_addon('aps') && $this->clustering_method != 'super_cluster'): ?>
        if(typeof wpl_marker_cluster<?php wpl_esc::js($this->activity_id); ?> == 'undefined')
        {
            // Add a marker clusterer to manage the markers.
            wpl_marker_cluster<?php wpl_esc::js($this->activity_id); ?> = new MarkerClusterer
            (
                wpl_map<?php wpl_esc::js($this->activity_id); ?>,
                markers_array<?php wpl_esc::js($this->activity_id); ?>,
				{styles:[{
					url: '<?php wpl_esc::wpl_url(); ?>assets/packages/markerclusterer/img/<?php wpl_esc::js($this->clusterer_iconset); ?>1.png',
					width: 53,
					height: 52,
					textSize:15,
					textColor:"white"
				},
				{
					url: '<?php wpl_esc::wpl_url(); ?>assets/packages/markerclusterer/img/<?php wpl_esc::js($this->clusterer_iconset); ?>2.png',
					width: 56,
					height:55,
					textSize:15,
					textColor:"white"
				},
				{
					url: '<?php wpl_esc::wpl_url(); ?>assets/packages/markerclusterer/img/<?php wpl_esc::js($this->clusterer_iconset); ?>3.png',
					width: 66,
					height:65,
					textSize:15,
					textColor:"white"
				},
				{
					url: '<?php wpl_esc::wpl_url(); ?>assets/packages/markerclusterer/img/<?php wpl_esc::js($this->clusterer_iconset); ?>4.png',
					width: 78,
					height: 77,
					textSize:15,
					textColor:"white"
				},
				{
					url: '<?php wpl_esc::wpl_url(); ?>assets/packages/markerclusterer/img/<?php wpl_esc::js($this->clusterer_iconset); ?>5.png',
					width: 90,
					height:89,
					textSize:15,
					textColor:"white"
				}
				]}
            );
        }
        else
        {
            if(delete_markers) wpl_marker_cluster<?php wpl_esc::js($this->activity_id); ?>.clearMarkers();
            wpl_marker_cluster<?php wpl_esc::js($this->activity_id); ?>.addMarkers(markers_array<?php wpl_esc::js($this->activity_id); ?>, false);
            wpl_marker_cluster<?php wpl_esc::js($this->activity_id); ?>.redraw();
        }
        <?php endif; ?>
	}
}

function get_infowindow_html<?php wpl_esc::js($this->activity_id); ?>(property_ids, callback)
{
	<?php if($this->map_property_preview): ?>
	ajax_layout = '&tpl=infowindow_preview';
	<?php else: ?>
	ajax_layout = '&tpl=infowindow';
	<?php endif; ?>

	wplj.ajax(
	{
		url: '<?php wpl_esc::current_url(); ?>',
		data: 'wpl_format=c:functions:ajax&wpl_function=infowindow&property_ids='+property_ids+'&wpltarget=<?php wpl_esc::numeric(wpl_request::getVar('wpltarget', 0)); ?>'+ajax_layout,
		type: 'GET',
		cache: false,
		timeout: 30000,
		success: function(data)
		{
			if(callback) {
				callback(data);
			}
		}
	});
}

function delete_markers<?php wpl_esc::js($this->activity_id); ?>()
{
	if(markers_array<?php wpl_esc::js($this->activity_id); ?>)
	{
		for(i=0; i < markers_array<?php wpl_esc::js($this->activity_id); ?>.length; i++) markers_array<?php wpl_esc::js($this->activity_id); ?>[i].setMap(null);
		markers_array<?php wpl_esc::js($this->activity_id); ?>.length = 0;
	}

	if(loaded_markers<?php wpl_esc::js($this->activity_id); ?>) loaded_markers<?php wpl_esc::js($this->activity_id); ?>.length = 0;
}

/** Google places functions **/
function wpl_gplace_callback<?php wpl_esc::js($this->activity_id);?>(results, status)
{
	if(status == google.maps.places.PlacesServiceStatus.OK)
	{
		for(var i=0; i<results.length; i++) wpl_gplace_marker<?php wpl_esc::js($this->activity_id);?>(results[i]);
	}
}

function wpl_gplace_marker<?php wpl_esc::js($this->activity_id);?>(place)
{
	var placeLoc = place.geometry.location;
	var image = new google.maps.MarkerImage
    (
        place.icon,
        new google.maps.Size(51, 51),
        new google.maps.Point(0, 0),
        new google.maps.Point(17, 34),
        new google.maps.Size(25, 25)
    );

	// create place types title
	var title_str = '';

	for(var i=0; i<place.types.length; i++)
	{
		title_str = title_str+place.types[i];
		if((i+1) != place.types.length) title_str = title_str+', ';
	}

	var marker = new google.maps.Marker(
    {
		map: wpl_map<?php wpl_esc::js($this->activity_id); ?>,
		icon: image,
		title: title_str,
		position: place.geometry.location
	});

    /** extend the bounds to include each marker's position **/
  	bounds<?php wpl_esc::js($this->activity_id); ?>.extend(place.geometry.location);

    google.maps.event.addListener(marker, 'click', function()
	{
		infowindow<?php wpl_esc::js($this->activity_id); ?>.setContent('<div class="wpl_gplace_infowindow_container" style="color: #000000;">'+place.name+'</div>');
		infowindow<?php wpl_esc::js($this->activity_id); ?>.open(wpl_map<?php wpl_esc::js($this->activity_id); ?>, this);
	});
}

function wpl_load_map_markers(request_str, delete_markers, extend_bound = false)
{
	let url = '<?php wpl_esc::current_url(); ?>';
    if(wpl_map_search_limit<?php wpl_esc::js($this->activity_id); ?> > 0)
    {
        let current_page = <?php wpl_esc::numeric($wpl_map_current_page); ?>;
		let current_limit = <?php wpl_esc::numeric($wpl_map_current_limit); ?>;
        url = '<?php wpl_esc::url(wpl_global::remove_qs_var('wplpage', wpl_global::remove_qs_var('limit', wpl_global::get_full_url()))); ?>';
        request_str = wpl_update_qs('limit', wpl_map_search_limit<?php wpl_esc::js($this->activity_id); ?>, request_str);
		let map_page = Math.ceil((current_page * current_limit ) / wpl_map_search_limit<?php wpl_esc::js($this->activity_id); ?>);
        if(!map_page) map_page = 1;
        request_str = wpl_update_qs('wplpage', map_page, request_str);
        request_str = wpl_update_qs('map_zoom', wpl_map<?php wpl_esc::js($this->activity_id); ?>.zoom, request_str);
    }

    if(typeof delete_markers == 'undefined') delete_markers = false;

    /** AJAX loader **/
    wplj("#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>").append(`<div class="map_search_ajax_loader"><img src="<?php wpl_esc::e(wpl_global::get_wpl_asset_url('img/ajax-loader4.gif')); ?>" /></div>`);

    request_str = 'wpl_format=f:property_listing:raw&wplmethod=get_markers&'+request_str;
	let markers;

    wplj.ajax(
    {
        url: url,
        data: request_str,
        type: 'GET',
        dataType: 'jSON',
        async: true,
        cache: false,
        timeout: 30000,
        success: function(data)
        {
            /** AJAX loader **/
            wplj(".map_search_ajax_loader").remove();

            /** Disable Map search **/
            if(typeof wpl_aps_freeze != 'undefined') wpl_aps_freeze = true;

            markers = data.markers;

            <?php foreach($map_activities as $activity): ?>
			if(extend_bound) {
				wpl_map_bounds_extend<?php wpl_esc::js($this->activity_id); ?> = true;
			}
            wpl_load_markers<?php wpl_esc::js($activity->id); ?>(markers, delete_markers, data.total);
            <?php endforeach; ?>

            /** Enabled Map Search Again **/
            if(typeof wpl_aps_freeze != 'undefined') setTimeout(function(){wpl_aps_freeze = false}, 1000);
        }
    });
}

<?php if($this->get_direction): ?>
function wpl_get_direction<?php wpl_esc::js($this->activity_id); ?>(lat, lng)
{
    var text_direction = <?php wpl_esc::e($this->get_direction == 2 ? 'true' : 'false'); ?>;
    var from = wplj('#wpl_get_direction_addr<?php wpl_esc::js($this->activity_id); ?>').val();

    wpl_draw_direction<?php wpl_esc::js($this->activity_id); ?>(from, lat, lng, text_direction);

    // Show reset button
    wplj('.wpl-map-get-direction-reset').removeClass('wpl-util-hidden');

	return false;
}

var wpl_directionsDisplay;
var wpl_directionsService;

function wpl_draw_direction<?php wpl_esc::js($this->activity_id); ?>(from, lat, lng, text_direction)
{
	wplj("#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>").fadeTo(300, .4);
	var dest = new google.maps.LatLng(lat ,lng);

	if(wpl_directionsDisplay != null)
	{
    	wpl_directionsDisplay.setMap(null);
		wpl_directionsDisplay.setPanel(null);
	}

	wpl_directionsDisplay = new google.maps.DirectionsRenderer();
 	wpl_directionsService = new google.maps.DirectionsService();

 	var request = {
   		origin:  from,
   		destination: dest,
   		travelMode: google.maps.DirectionsTravelMode.DRIVING
 	};

 	wpl_directionsService.route(request, function(result, status)
    {
   		if(status == google.maps.DirectionsStatus.OK)
        {
    		wpl_directionsDisplay.setDirections(result);
     		wpl_directionsDisplay.setMap(wpl_map<?php wpl_esc::js($this->activity_id); ?>);

			if(text_direction) wpl_directionsDisplay.setPanel(document.getElementById("wpl_map_direction_text<?php wpl_esc::js($this->activity_id); ?>"));
   		}

        wplj("#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>").fadeTo(300, 1);
 	});
}

function wpl_remove_direction<?php wpl_esc::js($this->activity_id); ?>()
{
    wplj('#wpl_get_direction_addr<?php wpl_esc::js($this->activity_id); ?>').val('');
    wplj('#wpl_get_direction_form<?php wpl_esc::js($this->activity_id); ?>').submit();

    // Hide reset button
    wplj('.wpl-map-get-direction-reset').addClass('wpl-util-hidden');
}
<?php endif; ?>
</script>