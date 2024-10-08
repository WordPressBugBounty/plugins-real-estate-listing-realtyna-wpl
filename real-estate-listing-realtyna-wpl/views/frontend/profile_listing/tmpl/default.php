<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->profiles_str = $this->_wpl_render($this->tpl_path.'.assets.default_profiles', true);

if($this->wplraw == 1)
{
    wpl_esc::e($this->profiles_str);
    exit;
}

$this->listview_str = $this->_wpl_render($this->tpl_path.'.assets.default_profiles_listview', true);
if($this->wplraw == 2)
{
    $this->response(array('total_pages'=>$this->total_pages, 'current_page'=>$this->page_number, 'html'=>$this->listview_str));
}

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
if($this->wplpagination == 'scroll' and $this->property_listview and wpl_global::check_addon('pro')) $this->_wpl_import($this->tpl_path.'.scripts.js_scroll', true, true);
?>
<div class="wpl-profile-listing-wp <?php if(!$this->listing_picture_mouseover) wpl_esc::e("wpl-prp-disable-image-hover"); ?>" id="wpl_profile_listing_main_container">
    
    <?php if(is_active_sidebar('wpl-profile-listing-top')): ?>
    <div class="wpl_plisting_top_sidebar_container">
        <?php dynamic_sidebar('wpl-profile-listing-top'); ?>
    </div>
    <?php endif; ?>
    
    <?php if($this->property_listview): ?>
    <div class="wpl_profile_listing_container wpl_profile_listing_list_view_container" id="wpl_profile_listing_container">
        <?php wpl_esc::e($this->listview_str); ?>
	</div>
    <?php endif; ?>
</div>