<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$sort_options = wpl_sort_options::get_sort_options($this->kind, 1);
$listings_page = wpl_property::get_property_listing_link();
?>
<?php if(wpl_global::check_addon('aps')): ?>
<i id="map_view_handler" class="map_view_handler cl" style="display: none;" onclick="map_view_toggle_listing()">&nbsp;</i>
<?php endif; ?>

<div class="wpl_sort_options_container">

    <?php if(count($sort_options)): ?>
        <div class="wpl_sort_options_container_title"><?php wpl_esc::html_t('Sort Option'); ?></div>
        <div class="wpl-sort-options-list <?php wpl_esc::attr($this->wpl_listing_sort_type == 'list' ? 'active' : ''); ?> <?php wpl_esc::attr($this->wpl_listing_sort_type == 'dropdown' ? 'wpl-util-hidden' : ''); ?>">
			<?php wpl_esc::e($this->model->generate_sorts(array('type'=>1, 'kind'=>$this->kind, 'sort_options'=>$sort_options))); ?>
		</div>
        <span class="wpl-sort-options-selectbox <?php wpl_esc::attr($this->wpl_listing_sort_type == 'dropdown' ? 'active' : ''); ?> <?php wpl_esc::attr($this->wpl_listing_sort_type == 'list' ? 'wpl-util-hidden' : ''); ?>">
			<?php wpl_esc::e($this->model->generate_sorts(array('type'=>0, 'kind'=>$this->kind, 'sort_options'=>$sort_options))); ?>
		</span>
    <?php endif; ?>
    
    <?php if($this->property_css_class_switcher): ?>
    <div class="wpl_list_grid_switcher <?php wpl_esc::attr($this->switcher_type == "icon+text" ? 'wpl-list-grid-switcher-icon-text' : ''); ?>">
        <span id="grid_view" class="<?php wpl_esc::attr($this->switcher_type == "icon" ? 'wpl-tooltip-top ' : ''); ?>grid_view <?php wpl_esc::attr($this->property_css_class == 'grid_box' ? 'active' : ''); ?>">
			<?php if ($this->switcher_type == "icon+text"): ?>
				<span><?php wpl_esc::html_t('Grid') ?></span>
			<?php endif; ?>
        </div>
        <?php if ($this->switcher_type == "icon"): ?>
            <div class="wpl-util-hidden"><?php wpl_esc::html_t('Grid') ?></div>
        <?php endif; ?>

        <div id="list_view" class="<?php wpl_esc::attr($this->switcher_type == "icon" ? 'wpl-tooltip-top ' : '') ?>list_view <?php wpl_esc::attr($this->property_css_class == 'row_box' ? 'active' : ''); ?>">
			<?php if ($this->switcher_type == "icon+text"): ?>
				<span><?php wpl_esc::html_t('List') ?></span>
			<?php endif; ?>
        </div>
        <?php if ($this->switcher_type == "icon"): ?>
            <div class="wpl-util-hidden"><?php wpl_esc::html_t('List') ?></div>
        <?php endif; ?>

        <?php if(wpl_global::check_addon('aps') and $this->map_activity and (!isset($this->settings['googlemap_display_status']) or (isset($this->settings['googlemap_display_status']) and $this->settings['googlemap_display_status']) or (isset($this->settings['googlemap_display_status']) and !$this->settings['googlemap_display_status'] and wpl_request::getVar('wplfmap', 0)))): ?>
            <div id="map_view" class="<?php wpl_esc::attr($this->switcher_type == "icon" ? 'wpl-tooltip-top ' : '') ?>map_view <?php wpl_esc::attr($this->property_css_class == 'map_box' ? 'active' : ''); ?>">
				<?php if ($this->switcher_type == "icon+text"): ?>
					<span><?php wpl_esc::html_t('Map') ?></span>
				<?php endif; ?>
            </div>
            <?php if ($this->switcher_type == "icon"): ?>
                <div class="wpl-util-hidden"><?php wpl_esc::html_t('Map') ?></div>
            <?php endif; ?>

        <?php elseif(wpl_global::check_addon('aps') and $this->map_activity): ?>
			<a class="<?php wpl_esc::attr($this->switcher_type == "icon" ? 'wpl-tooltip-top ' : ''); ?>map_view" href="<?php wpl_esc::url(wpl_global::add_qs_var('wplpcc', 'map_box', wpl_global::add_qs_var('wplfmap', 1, wpl_global::get_full_url()))); ?>">
				<?php if ($this->switcher_type == "icon+text"): ?>
					<span><?php wpl_esc::html_t('Map') ?></span>
				<?php endif; ?>
			</a>
            <?php if ($this->switcher_type == "icon"): ?>
                <div class="wpl-util-hidden"><?php wpl_esc::html_t('Map') ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if(wpl_global::check_addon('pro') and $this->listings_rss_enabled): ?>
    <div class="wpl-rss-wp">
        <a class="wpl-rss-link" href="#" onclick="wpl_generate_rss();"><span><?php wpl_esc::html_t('RSS'); ?></span></a>
    </div>
    <?php endif; ?>
    
    <?php if(wpl_global::check_addon('pro') and $this->print_results_page): ?>
    <div class="wpl-print-rp-wp">
        <a class="wpl-print-rp-link" href="#" onclick="wpl_generate_print_rp();"><span><i class="fa fa-print"></i></span></a>
    </div>
    <?php endif; ?>
    
    <?php if(wpl_global::check_addon('save_searches') and $this->save_search_button): ?>
    <div class="wpl-save-search-wp wpl-plisting-link-btn">
        <a id="wpl_save_search_link_lightbox" class="wpl-save-search-link" data-realtyna-href="#wpl_plisting_lightbox_content_container" onclick="return wpl_generate_save_search();" data-realtyna-lightbox-opts="title:<?php wpl_esc::attr_t('Save this Search'); ?>">
			<span><?php wpl_esc::html_t('Save Search'); ?></span>
		</a>
    </div>
    <?php endif; ?>
    
    <?php if(wpl_global::check_addon('aps') and wpl_global::get_setting('aps_landing_page_generator') and wpl_users::check_access('landing_page')): ?>
    <div class="wpl-landing-page-generator-wp wpl-plisting-link-btn">
        <a id="wpl_landing_page_generator_link_lightbox" class="wpl-landing-page-generator-link" data-realtyna-href="#wpl_plisting_lightbox_content_container" onclick="return wpl_generate_landing_page_generator();" data-realtyna-lightbox-opts="title:<?php wpl_esc::attr_t('Landing Page Generator'); ?>">
			<span><?php wpl_esc::html_t('Create Landing Page'); ?></span>
		</a>
    </div>
    <?php endif; ?>
</div>

<div class="wpl-row wpl-expanded <?php wpl_esc::attr($this->property_css_class == "grid_box" ? "wpl-small-up-1 wpl-medium-up-2 wpl-large-up-".$this->listing_columns : ''); ?>  wpl_property_listing_listings_container clearfix">

    <?php if(wpl_request::getVar('sf_geolocationstatus', 0) and trim($this->properties_str) == ''): ?>
    <p><?php wpl_esc::html_t('There are no listings around you matching criteria!'); ?></p>
    <?php endif; ?>

    <?php wpl_esc::e($this->properties_str); ?>

</div>

<?php if($this->wplpagination != 'scroll'): ?>
<div class="wpl_pagination_container">
    <?php wpl_esc::e($this->pagination->show()); ?>
</div>
<?php endif;