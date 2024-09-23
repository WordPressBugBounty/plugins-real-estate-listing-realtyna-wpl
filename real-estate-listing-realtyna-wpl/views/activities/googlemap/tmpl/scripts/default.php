<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var markers<?php wpl_esc::attr($this->activity_id); ?> = <?php wpl_esc::e(json_encode($this->markers ?? '')); ?>;

/** default values in case of no marker to showing **/
var default_lt<?php wpl_esc::attr($this->activity_id); ?> = '<?php wpl_esc::attr($this->default_lt); ?>';
var default_ln<?php wpl_esc::attr($this->activity_id); ?> = '<?php wpl_esc::attr($this->default_ln); ?>';
var default_zoom<?php wpl_esc::attr($this->activity_id); ?> = <?php wpl_esc::attr(intval($this->default_zoom)); ?>;

jQuery(document).ready(function()
{
    wpl_add_googlemaps_callbacks(function()
    {
        wpl_initialize<?php wpl_esc::attr($this->activity_id); ?>();
    
        if(markers<?php wpl_esc::attr($this->activity_id); ?>.length === 1)
        {
            /** restore the zoom level after the map is done scaling **/
            var listener = wpl_map<?php wpl_esc::attr($this->activity_id); ?>.addEventListener('idle', function(event)
            {
                wpl_map<?php wpl_esc::attr($this->activity_id); ?>.setZoom(default_zoom<?php wpl_esc::attr($this->activity_id); ?>);
                google.maps.event.removeListener(listener);
            });
        }
    });
});
</script>