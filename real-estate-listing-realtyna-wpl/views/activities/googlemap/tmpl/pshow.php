<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = $params['wpl_properties'] ?? array();
$property_id = $wpl_properties['current']['data']['id'] ?? NULL;

/** Kind **/
$this->kind = $wpl_properties['current']['data']['kind'] ?? 0;
$kind_data = wpl_flex::get_kind($this->kind);

/** Property Type **/
$ptype_data = array();
if(isset($wpl_properties['current']['data']['property_type'])) $ptype_data = wpl_global::get_property_types($wpl_properties['current']['data']['property_type']);

/** Parameters **/
$this->params = $params;

/** get params **/
$this->googlemap_type = $params['googlemap_type'] ?? 0;
$this->googlemap_view = $params['googlemap_view'] ?? 'ROADMAP';
$this->map_width = $params['map_width'] ?? 360;
$this->map_height = $params['map_height'] ?? 385;
$this->default_lt = $params['default_lt'] ?? '38.685516';
$this->default_ln = $params['default_ln'] ?? '-101.073324';
$this->default_zoom = $params['default_zoom'] ?? '14';
$this->infowindow_event = $params['infowindow_event'] ?? 'click';
$this->get_direction = $params['get_direction'] ?? 0;
$this->scroll_wheel = $params['scroll_wheel'] ?? 'false';
$this->spatial = 0;

// Clustering
$this->clustering = 0;
$this->clusterer_iconset = $this->settings['aps_cluster_iconset'] ?? 'c';

// Show the marker or not
$this->show_marker = (isset($kind_data['map']) and $kind_data['map'] != 'marker') ? 0 : ((isset($ptype_data->show_marker) and !$ptype_data->show_marker) ? 0 : 1);
if(isset($wpl_properties['current']['data']['show_marker']) and $wpl_properties['current']['data']['show_marker'] != 2) $this->show_marker = ($wpl_properties['current']['data']['show_marker'] ? $wpl_properties['current']['data']['show_marker'] : 0);

/** Preview Property feature **/
$this->map_property_preview = 0;
$this->map_property_preview_show_marker_icon = 'price';

/* Get Google Place Option */
$this->google_place = $params['google_place'] ?? 0;
$this->google_place_radius = $params['google_place_radius'] ?? 1000;

$this->markers = wpl_property::render_markers($wpl_properties);

/** Map Search **/
$this->map_search_status = 0;

/** WPL Demographic addon **/
$this->demographic_objects = array();
if(wpl_global::check_addon('demographic'))
{
    _wpl_import('libraries.addon_demographic');
    $this->demographic = new wpl_addon_demographic();

    $this->demographic_status = $params['demographic'] ?? 0;
    if($this->demographic_status) $this->_wpl_import($this->tpl_path.'.scripts.addon_demographic', true, true);

    $this->demographic_objects = $wpl_properties['current']['items']['demographic'] ?? array();
}

// Include JavaScript files in footer or not
$wplformat = wpl_request::getVar('wpl_format', NULL);
$inclusion = !(strpos($wplformat ?? "", ':raw') !== false);

/** load js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.js', true, $inclusion);
$this->_wpl_import($this->tpl_path.'.scripts.pshow', true, $inclusion);
?>
<div class="wpl_googlemap_container wpl_googlemap_pshow" id="wpl_googlemap_container<?php wpl_esc::attr($this->activity_id); ?>">
    <div class="wpl-map-add-ons"></div>
    <div class="wpl_map_canvas" id="wpl_map_canvas<?php wpl_esc::attr($this->activity_id); ?>" style="height: <?php wpl_esc::attr($this->map_height) ?>px;"></div>
    <?php if($this->get_direction): ?>
    <div class="wpl-map-get-direction wpl-util-hidden">
        <form method="post" action="#" id="wpl_get_direction_form<?php wpl_esc::attr($this->activity_id); ?>" onsubmit="return wpl_get_direction<?php wpl_esc::attr($this->activity_id); ?>(<?php wpl_esc::attr($this->markers[0]['googlemap_lt']); ?>, <?php wpl_esc::attr($this->markers[0]['googlemap_ln']); ?>);" class="clearfix">
            <div class="wpl-map-get-direction-address-cnt">
                <input class="wpl-map-get-direction-address" type="text" placeholder="<?php wpl_esc::html_t('From Address').' ...'; ?>" id="wpl_get_direction_addr<?php wpl_esc::attr($this->activity_id); ?>" />
                <span class="wpl-map-get-direction-reset wpl-util-hidden" onclick="wpl_remove_direction<?php wpl_esc::attr($this->activity_id); ?>();"></span>
            </div>
            <div class="wpl-map-get-direction-btn-cnt btn btn-primary">
                <input type="submit" value="" />
                <span><?php wpl_esc::html_t('Get Direction'); ?></span>
            </div>
        </form>
        <?php if($this->get_direction == 2): ?>
        <div class="wpl_map_direction_text" id="wpl_map_direction_text<?php wpl_esc::attr($this->activity_id); ?>"></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>