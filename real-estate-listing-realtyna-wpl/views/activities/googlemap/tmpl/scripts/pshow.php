<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var markers<?php wpl_esc::js($this->activity_id); ?> = <?php wpl_esc::e(json_encode($this->markers ?? '')); ?>;
var google_place = <?php wpl_esc::numeric($this->google_place); ?>;
var google_place_radius = <?php wpl_esc::numeric($this->google_place_radius); ?>

/** default values in case of no marker to showing **/
var default_lt<?php wpl_esc::js($this->activity_id); ?> = '<?php wpl_esc::numeric($this->default_lt); ?>';
var default_ln<?php wpl_esc::js($this->activity_id); ?> = '<?php wpl_esc::numeric($this->default_ln); ?>';
var default_zoom<?php wpl_esc::js($this->activity_id); ?> = <?php wpl_esc::numeric($this->default_zoom); ?>;
var wpl_map_initialized<?php wpl_esc::js($this->activity_id); ?> = false;
var wpl_pshow_bounds_extended = false;

jQuery(document).ready(function()
{
    if(wplj('#wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>').is(':visible'))
    {
        wpl_add_googlemaps_callbacks(wpl_pshow_map_init<?php wpl_esc::js($this->activity_id); ?>);
    }
});

function wpl_pshow_map_init<?php wpl_esc::js($this->activity_id); ?>()
{
	if(wpl_map_initialized<?php wpl_esc::js($this->activity_id); ?>) return;
	wpl_initialize<?php wpl_esc::js($this->activity_id); ?>();
	
	/** restore the zoom level after the map is done scaling **/
	var listener = google.maps.event.addListener(wpl_map<?php wpl_esc::js($this->activity_id); ?>, 'idle', function(event)
	{
		wpl_map<?php wpl_esc::js($this->activity_id); ?>.setZoom(default_zoom<?php wpl_esc::js($this->activity_id); ?>);
        if(wpl_pshow_bounds_extended) setTimeout(function(){wpl_map<?php wpl_esc::js($this->activity_id); ?>.fitBounds(bounds<?php wpl_esc::js($this->activity_id); ?>)}, 2000);
        
		google.maps.event.removeListener(listener);
	});
	
    <?php if($this->googlemap_type == '1'): ?>
  	var panoramaOptions = 
    {
		position: marker.position,
        scrollwheel: <?php wpl_esc::e($this->scroll_wheel == 'true' ? 'true' : 'false'); ?>,
		pov: 
		{
            heading: 34,
            pitch: 10,
            zoom: 1
		}
	};
    
	var panorama = new google.maps.StreetViewPanorama(document.getElementById('wpl_map_canvas<?php wpl_esc::js($this->activity_id); ?>'), panoramaOptions);
	wpl_map<?php wpl_esc::js($this->activity_id); ?>.setStreetView(panorama);
 	<?php endif; ?>
    
    <?php
    foreach($this->demographic_objects as $demographic_object)
    {
        $boundaries = $this->demographic->toBoundaries($demographic_object->item_extra1);
        ?>
            var demographicCoords = [];
            <?php foreach($boundaries as $boundary): ?>
            var position = new google.maps.LatLng(<?php wpl_esc::numeric($boundary['lat']); ?>, <?php wpl_esc::numeric($boundary['lng']); ?>);
            demographicCoords.push(position);
            bounds<?php wpl_esc::js($this->activity_id); ?>.extend(position);
            wpl_pshow_bounds_extended = true;
            <?php endforeach; ?>
        <?php
        if(strtolower($demographic_object->item_cat) == 'polygon')
        {
        ?>
            var polygon = new google.maps.Polygon(
            {
                paths: demographicCoords,
                strokeColor: '#1e74c7',
                strokeOpacity: 0.6,
                strokeWeight: 1,
                fillColor: '#1e90ff',
                fillOpacity: 0.3
            });
    
            polygon.setMap(wpl_map<?php wpl_esc::js($this->activity_id); ?>);
        <?php
        }
        elseif(strtolower($demographic_object->item_cat) == 'polyline')
        {
        ?>
            var polyline = new google.maps.Polyline(
            {
                path: demographicCoords,
                strokeColor: '#1e74c7',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
            
            polyline.setMap(wpl_map<?php wpl_esc::js($this->activity_id); ?>);
        <?php
        }
    }
    ?>
    
	/** set true **/
	wpl_map_initialized<?php wpl_esc::js($this->activity_id); ?> = true;
}
</script>