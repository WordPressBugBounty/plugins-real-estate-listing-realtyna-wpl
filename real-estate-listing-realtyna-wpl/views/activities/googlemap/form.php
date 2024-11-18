<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-gen-accordion wpl-gen-accordion-active">
    <h4 class="wpl-gen-accordion-title" id="wpl_accordion1"><?php wpl_esc::html_t('Basic Options'); ?></h4>
    <div class="wpl-gen-accordion-cnt" id="wpl_accordion1_cnt">
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_googlemap_type"><?php wpl_esc::html_t('Map Type'); ?></label>
            <select class="text_box" name="option[googlemap_type]" id="wpl_o_googlemap_type">
                <option value="0" <?php if(isset($this->options->googlemap_type) and $this->options->googlemap_type == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Typical'); ?></option>
                <option value="1" <?php if(isset($this->options->googlemap_type) and $this->options->googlemap_type == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Street View'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_googlemap_view"><?php wpl_esc::html_t('Map View'); ?></label>
            <select class="text_box" name="option[googlemap_view]" id="wpl_o_googlemap_view">
                <option value="ROADMAP" <?php if(isset($this->options->googlemap_view) and $this->options->googlemap_view == 'ROADMAP') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Roadmap'); ?></option>
                <option value="SATELLITE" <?php if(isset($this->options->googlemap_view) and $this->options->googlemap_view == 'SATELLITE') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Satellite'); ?></option>
                <option value="HYBRID" <?php if(isset($this->options->googlemap_view) and $this->options->googlemap_view == 'HYBRID') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Hybrid'); ?></option>
                <option value="TERRAIN" <?php if(isset($this->options->googlemap_view) and $this->options->googlemap_view == 'TERRAIN') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Terrain'); ?></option>
                <option value="WPL" <?php if(isset($this->options->googlemap_view) and $this->options->googlemap_view == 'WPL') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('WPL Style'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_default_lt"><?php wpl_esc::html_t('Default latitude'); ?></label>
            <input class="text_box" name="option[default_lt]" type="text" id="wpl_o_default_lt" value="<?php wpl_esc::attr($this->options->default_lt ?? '38.685516'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_default_ln"><?php wpl_esc::html_t('Default longitude'); ?></label>
            <input class="text_box" name="option[default_ln]" type="text" id="wpl_o_default_ln" value="<?php wpl_esc::attr($this->options->default_ln ?? '-101.073324'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_default_zoom"><?php wpl_esc::html_t('Default zoom level'); ?></label>
            <input class="text_box" name="option[default_zoom]" type="text" id="wpl_o_default_zoom" value="<?php wpl_esc::attr($this->options->default_zoom ?? '4'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_scroll_wheel"><?php wpl_esc::html_t('Scroll wheel zoom'); ?></label>
            <select class="text_box" name="option[scroll_wheel]" id="wpl_o_scroll_wheel">
                <option value="false" <?php if(isset($this->options->scroll_wheel) and $this->options->scroll_wheel == 'false') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="true" <?php if(isset($this->options->scroll_wheel) and $this->options->scroll_wheel == 'true') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_map_height"><?php wpl_esc::html_t('Map height'); ?></label>
            <input class="text_box" name="option[map_height]" type="text" id="wpl_o_map_height" value="<?php wpl_esc::attr($this->options->map_height ?? '480'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_infowindow_event"><?php wpl_esc::html_t('Infowindow Event'); ?></label>
            <select class="text_box" name="option[infowindow_event]" id="wpl_o_infowindow_event">
                <option value="click" <?php if(isset($this->options->infowindow_event) and $this->options->infowindow_event == 'click') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Click'); ?></option>
                <option value="mouseover" <?php if(isset($this->options->infowindow_event) and $this->options->infowindow_event == 'mouseover') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Mouse Over'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_googlemap_hits"><?php wpl_esc::html_t('Maximum Daily Hits'); ?></label>
            <input class="text_box" name="option[googlemap_hits]" type="text" id="wpl_o_googlemap_hits" value="<?php wpl_esc::attr($this->options->googlemap_hits ?? '1000000'); ?>" />
        </div>
        <?php if(wpl_global::check_addon('aps')): ?>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_get_direction"><?php wpl_esc::html_t('Get Direction'); ?></label>
            <select class="text_box" name="option[get_direction]" id="wpl_o_get_direction">
                <option value="0" <?php if(isset($this->options->get_direction) and $this->options->get_direction == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->get_direction) and $this->options->get_direction == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
                <option value="2" <?php if(isset($this->options->get_direction) and $this->options->get_direction == 2) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled - Show Direction Text'); ?></option>
            </select>
        </div>
        <?php endif; ?>
        <?php if(wpl_global::check_addon('spatial')): ?>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_spatial"><?php wpl_esc::html_t('Spatial API'); ?></label>
            <select class="text_box" name="option[spatial]" id="wpl_o_spatial">
                <option value="0" <?php if(isset($this->options->spatial) and $this->options->spatial == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->spatial) and $this->options->spatial == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
            </select>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php if(wpl_global::check_addon('aps')): ?>
<div class="wpl-gen-accordion">
    <h4 class="wpl-gen-accordion-title" id="wpl_accordion2"><?php wpl_esc::html_t('Map Search'); ?></h4>
    <div class="wpl-gen-accordion-cnt" id="wpl_accordion2_cnt">
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_map_search"><?php wpl_esc::html_t('Map Search'); ?></label>
            <select class="text_box" name="option[map_search]" id="wpl_o_map_search">
                <option value="0" <?php if(isset($this->options->map_search) and $this->options->map_search == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->map_search) and $this->options->map_search == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled - Checked'); ?></option>
                <option value="2" <?php if(isset($this->options->map_search) and $this->options->map_search == 2) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled - Unchecked'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_map_search_toggle"><?php wpl_esc::html_t('Map Search Toggle'); ?></label>
            <select class="text_box" name="option[map_search_toggle]" id="wpl_o_map_search_toggle">
                <option value="0" <?php if(isset($this->options->map_search_toggle) and $this->options->map_search_toggle == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->map_search_toggle) and $this->options->map_search_toggle == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_map_search_limit"><?php wpl_esc::html_t('Number Of Markers'); ?></label>
            <input class="text_box" name="option[map_search_limit]" type="text" id="wpl_o_map_search_limit" value="<?php wpl_esc::attr($this->options->map_search_limit ?? ''); ?>" />
        </div>
    </div>
</div>
<div class="wpl-gen-accordion">
    <h4 class="wpl-gen-accordion-title" id="wpl_accordion6"><?php wpl_esc::html_t('Marker Clustering'); ?></h4>
    <div class="wpl-gen-accordion-cnt" id="wpl_accordion6_cnt">
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_clustering"><?php wpl_esc::html_t('Marker Clustering'); ?></label>
            <select class="text_box" name="option[clustering]" id="wpl_o_clustering">
                <option value="0" <?php if(isset($this->options->clustering) and $this->options->clustering == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->clustering) and $this->options->clustering == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_clustering_method"><?php wpl_esc::html_t('Method'); ?></label>
            <select class="text_box" name="option[clustering_method]" id="wpl_o_clustering_method">
                <option value="default" <?php if(isset($this->options->clustering_method) and $this->options->clustering_method == 'default') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Default'); ?></option>
                <option value="super_cluster" <?php if(isset($this->options->clustering_method) and $this->options->clustering_method == 'super_cluster') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Super Cluster'); ?></option>
            </select>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="wpl-gen-accordion">
    <h4 class="wpl-gen-accordion-title" id="wpl_accordion3"><?php wpl_esc::html_t('Google Place'); ?></h4>
    <div class="wpl-gen-accordion-cnt" id="wpl_accordion3_cnt">
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_google_place"><?php wpl_esc::html_t('Google place'); ?></label>
            <?php if(!wpl_global::check_addon('pro')): ?>
            <span id="wpl_o_google_place" class="gray_tip"><?php wpl_esc::html_t('Pro addon must be installed for this!'); ?></span>
            <?php else: ?>
            <select class="text_box" name="option[google_place]" id="wpl_o_google_place">
                <option value="0" <?php if(isset($this->options->google_place) and $this->options->google_place == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disable'); ?></option>
                <option value="1" <?php if(isset($this->options->google_place) and $this->options->google_place == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enable'); ?></option>
            </select>
            <?php endif; ?>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_google_place_radius"><?php wpl_esc::html_t('Google place radius'); ?></label>
            <?php if(!wpl_global::check_addon('pro')): ?>
            <span id="wpl_o_google_place_radius" class="gray_tip"><?php wpl_esc::html_t('Pro addon must be installed for this!'); ?></span>
            <?php else: ?>
            <input class="text_box" name="option[google_place_radius]" type="text" id="wpl_o_google_place_radius" value="<?php wpl_esc::attr($this->options->google_place_radius ?? '1000'); ?>" />
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if(wpl_global::check_addon('demographic')): ?>
<div class="wpl-gen-accordion">
    <h4 class="wpl-gen-accordion-title" id="wpl_accordion4"><?php wpl_esc::html_t('Demographic'); ?></h4>
    <div class="wpl-gen-accordion-cnt" id="wpl_accordion4_cnt">
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic"><?php wpl_esc::html_t('Demographic'); ?></label>
            <select class="text_box" name="option[demographic]" id="wpl_o_demographic">
                <option value="0" <?php if(isset($this->options->demographic) and $this->options->demographic == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->demographic) and $this->options->demographic == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
            </select>
        </div>
        <?php
            /** Demographic Object **/
            _wpl_import('libraries.addon_demographic');
            $this->demographic = new wpl_addon_demographic();
            $this->categories = $this->demographic->get_categries();
        ?>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_category"><?php wpl_esc::html_t('Category'); ?></label>
            <select class="text_box" name="option[demographic_category]" id="wpl_o_demographic_category">
                <?php foreach($this->categories as $category): ?>
                <option value="<?php wpl_esc::attr($category); ?>" <?php if(isset($this->options->demographic_category) and $this->options->demographic_category == $category) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t(wpl_global::human_readable($category)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_color"><?php wpl_esc::html_t('Color'); ?></label>
            <input class="text_box" type="text" name="option[demographic_color]" id="wpl_o_demographic_color" value="<?php wpl_esc::attr($this->options->demographic_color ?? '88c1e1'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_bcolor"><?php wpl_esc::html_t('Border Color'); ?></label>
            <input class="text_box" type="text" name="option[demographic_bcolor]" id="wpl_o_demographic_bcolor" value="<?php wpl_esc::attr($this->options->demographic_bcolor ?? '549cf2'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_hcolor"><?php wpl_esc::html_t('Hover Color'); ?></label>
            <input class="text_box" type="text" name="option[demographic_hcolor]" id="wpl_o_demographic_hcolor" value="<?php wpl_esc::attr($this->options->demographic_hcolor ?? 'fefefe'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_fill_opacity"><?php wpl_esc::html_t('Fill Opacity'); ?></label>
            <input class="text_box" type="text" name="option[demographic_fill_opacity]" id="wpl_o_demographic_fill_opacity" value="<?php wpl_esc::attr($this->options->demographic_fill_opacity ?? '0.25'); ?>" />
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_auto_color"><?php wpl_esc::html_t('Auto Color'); ?></label>
            <select class="text_box" id="wpl_o_demographic_auto_color" name="option[demographic_auto_color]">
                <option value="0"><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="average_home_value" <?php if(isset($this->options->demographic_auto_color) and $this->options->demographic_auto_color == 'average_home_value') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Average Home Value'); ?></option>
                <option value="median_income" <?php if(isset($this->options->demographic_auto_color) and $this->options->demographic_auto_color == 'median_income') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Median Income'); ?></option>
                <option value="population" <?php if(isset($this->options->demographic_auto_color) and $this->options->demographic_auto_color == 'population') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Population'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_show_map_guide"><?php wpl_esc::html_t('Show Map Guide'); ?></label>
            <select class="text_box" id="wpl_o_demographic_show_map_guide" name="option[demographic_show_map_guide]">
                <option value="0" <?php if(isset($this->options->demographic_show_map_guide) and $this->options->demographic_show_map_guide == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
                <option value="1" <?php if(isset($this->options->demographic_show_map_guide) and $this->options->demographic_show_map_guide == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_layer_toggle"><?php wpl_esc::html_t('Layer Toggle'); ?></label>
            <select class="text_box" id="wpl_o_demographic_layer_toggle" name="option[demographic_layer_toggle]">
                <option value="0" <?php if(isset($this->options->demographic_layer_toggle) and $this->options->demographic_layer_toggle == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
                <option value="1" <?php if(isset($this->options->demographic_layer_toggle) and $this->options->demographic_layer_toggle == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes - Checked'); ?></option>
                <option value="2" <?php if(isset($this->options->demographic_layer_toggle) and $this->options->demographic_layer_toggle == 2) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes - Unchecked'); ?></option>
            </select>
        </div>
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_o_demographic_show_categories"><?php wpl_esc::html_t('Show Categories'); ?></label>
            <select class="text_box" id="wpl_o_demographic_show_categories" name="option[demographic_show_categories]">
                <option value="0" <?php if(isset($this->options->demographic_show_categories) and $this->options->demographic_show_categories == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
                <option value="1" <?php if(isset($this->options->demographic_show_categories) and $this->options->demographic_show_categories == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
            </select>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(wpl_global::check_addon('aps')): ?>
<div class="wpl-gen-accordion">
    <h4 class="wpl-gen-accordion-title" id="wpl_accordion5"><?php wpl_esc::html_t('Property Preview'); ?></h4>
    <div class="wpl-gen-accordion-cnt" id="wpl_accordion5_cnt">
        <div class="wpl-gen-accordion-row fanc-row">
            <label for="wpl_map_property_preview"><?php wpl_esc::html_t('Property Preview'); ?></label>
            <select class="text_box" name="option[map_property_preview]" id="wpl_map_property_preview">
                <option value="0" <?php if(isset($this->options->map_property_preview) and $this->options->map_property_preview == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Disabled'); ?></option>
                <option value="1" <?php if(isset($this->options->map_property_preview) and $this->options->map_property_preview == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Enabled'); ?></option>
            </select>
            <p><i><?php wpl_esc::html_t('Note: To use this feature, the Map Search must be enabled!') ?></i></p>
        </div>
    </div>
</div>
<?php endif;