<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.agents.scripts.css_backend', true, true);
include _wpl_import('widgets.agents.scripts.js_backend', true, true);
?>
<div class="wpl_agents_widget_backend_form wpl-widget-form-wp" id="<?php wpl_esc::attr($this->get_field_id('wpl_agents_widget_container')); ?>">
    
    <h4><?php wpl_esc::html_t('Widget Configurations'); ?></h4>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('title')); ?>"><?php wpl_esc::html_t('Title'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('title')); ?>" name="<?php wpl_esc::attr($this->get_field_name('title')); ?>" value="<?php wpl_esc::attr(isset($instance['title']) ? $instance['title'] : ''); ?>" />
    </div>
    
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"><?php wpl_esc::html_t('Layout'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('layout')); ?>" name="<?php wpl_esc::attr($this->get_field_name('layout')); ?>">
	        <?php wpl_esc::e($this->generate_layouts_selectbox('agents', $instance)); ?>
        </select>
    </div>

    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('style')); ?>"><?php wpl_esc::html_t('Style'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('style')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[style]">
            <option <?php if(isset($instance['data']['style']) and  $instance['data']['style']== '1') wpl_esc::e('selected="selected"'); ?> value="1"><?php wpl_esc::html_t('Horizontal'); ?></option>
            <option <?php if(isset($instance['data']['style']) and  $instance['data']['style']== '2') wpl_esc::e('selected="selected"'); ?> value="2"><?php wpl_esc::html_t('Vertical'); ?></option>
        </select>
    </div>
    
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('wpltarget')); ?>"><?php wpl_esc::html_t('Target page'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('wpltarget')); ?>" name="<?php wpl_esc::attr($this->get_field_name('wpltarget')); ?>">
            <option value="">-----</option>
	        <?php wpl_esc::e($this->generate_pages_selectbox($instance)); ?>
        </select>
    </div>
    
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>"><?php wpl_esc::html_t('CSS Class'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[css_class]" value="<?php wpl_esc::attr(isset($instance['data']['css_class']) ? $instance['data']['css_class'] : ''); ?>" />
    </div>
    
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('data_image_width')); ?>"><?php wpl_esc::html_t('Image Width'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_image_width')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[image_width]" value="<?php wpl_esc::attr(isset($instance['data']['image_width']) ? $instance['data']['image_width'] : '230'); ?>" />
    </div>
    
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('data_image_height')); ?>"><?php wpl_esc::html_t('Image Height'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_image_height')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[image_height]" value="<?php wpl_esc::attr(isset($instance['data']['image_height']) ? $instance['data']['image_height'] : '230'); ?>" />
    </div>

    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('data_lazyload')); ?>"><?php wpl_esc::html_t('Lazyload'); ?></label>
        <select class="text_box" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[lazyload]" id="<?php wpl_esc::attr($this->get_field_id('data_lazyload')); ?>">
            <option value="0" <?php if(isset($instance['data']['lazyload']) and $instance['data']['lazyload'] == '0') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('No'); ?></option>
            <option value="1" <?php if(isset($instance['data']['lazyload']) and $instance['data']['lazyload'] == '1') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Yes'); ?></option>
        </select>
    </div>

    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('data_mailto_status')); ?>"><?php wpl_esc::html_t('Mailto Status'); ?></label>
        <input type="checkbox" <?php if(isset($instance['data']['mailto_status']) and $instance['data']['mailto_status']) wpl_esc::e('checked="checked"'); ?> value="1" id="<?php wpl_esc::attr($this->get_field_id('data_mailto_status')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[mailto_status]" />
    </div>

    <h4><?php wpl_esc::html_t('Filter Profiles'); ?></h4>
    <?php if(wpl_global::check_addon('pro')): ?>
    <?php $membership_types = wpl_users::get_membership_types(); ?>
    <div class="wpl-widget-row">
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_user_type')); ?>"><?php wpl_esc::html_t('User Type'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('data_user_type')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[user_type]">
        	<option value="-1"><?php wpl_esc::html_t('All'); ?></option>
            <?php foreach($membership_types as $membership_type): ?>
            <option <?php if(isset($instance['data']['user_type']) and $instance['data']['user_type'] == $membership_type->id) wpl_esc::e('selected="selected"'); ?> value="<?php wpl_esc::attr($membership_type->id); ?>"><?php wpl_esc::html_t($membership_type->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php $memberships = wpl_users::get_wpl_memberships(); ?>
    <div class="wpl-widget-row">
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_membership')); ?>"><?php wpl_esc::html_t('Membership'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('data_membership')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[membership]">
        	<option value=""><?php wpl_esc::html_t('All'); ?></option>
            <?php foreach($memberships as $membership): ?>
            <option <?php if(isset($instance['data']['membership']) and $instance['data']['membership'] == $membership->id) wpl_esc::e('selected="selected"'); ?> value="<?php wpl_esc::attr($membership->id); ?>"><?php wpl_esc::html_t($membership->membership_name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    
    <div class="wpl-widget-row">
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_user_ids')); ?>"><?php wpl_esc::html_t('User IDs'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_user_ids')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[user_ids]" value="<?php wpl_esc::attr(isset($instance['data']['user_ids']) ? $instance['data']['user_ids'] : ''); ?>" />
    </div>
    
    <div class="wpl-widget-row">
    	<input <?php if(isset($instance['data']['random']) and $instance['data']['random']) wpl_esc::e('checked="checked"'); ?> value="1" type="checkbox" id="<?php wpl_esc::attr($this->get_field_id('data_random')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[random]" onclick="random_clicked_agents<?php wpl_esc::e($this->widget_id); ?>();" />
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_random')); ?>"><?php wpl_esc::html_t('Random'); ?></label>
    </div>
    
    <h4><?php wpl_esc::html_t('Sort and Limit'); ?></h4>
    <?php $sort_options = wpl_sort_options::render(wpl_sort_options::get_sort_options(2)); ?>
    <div class="wpl-widget-row">
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_orderby')); ?>"><?php wpl_esc::html_t('Order by'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('data_orderby')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[orderby]">
        	<?php foreach($sort_options as $sort_option): ?>
            <option <?php if(isset($instance['data']['orderby']) and urlencode($sort_option['field_name']) == $instance['data']['orderby']) wpl_esc::e('selected="selected"'); ?> value="<?php wpl_esc::attr(urlencode($sort_option['field_name'])); ?>"><?php wpl_esc::html($sort_option['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="wpl-widget-row">
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_order')); ?>"><?php wpl_esc::html_t('Order'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('data_order')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[order]">
            <option <?php if(isset($instance['data']['order']) and $instance['data']['order'] == 'ASC') wpl_esc::e('selected="selected"'); ?> value="ASC"><?php wpl_esc::html_t('ASC'); ?></option>
            <option <?php if(isset($instance['data']['order']) and $instance['data']['order'] == 'DESC') wpl_esc::e('selected="selected"'); ?> value="DESC"><?php wpl_esc::html_t('DESC'); ?></option>
        </select>
    </div>
    
    <div class="wpl-widget-row">
    	<label for="<?php wpl_esc::attr($this->get_field_id('data_limit')); ?>"><?php wpl_esc::html_t('Number of users'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_limit')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[limit]" value="<?php wpl_esc::attr( isset($instance['data']['limit']) ? $instance['data']['limit'] : ''); ?>" />
    </div>
    
    <!-- Create a button to show Short-code of this widget -->
    <?php if(wpl_global::check_addon('pro') and !wpl_global::is_page_builder()): ?>
        <button id="<?php wpl_esc::attr($this->get_field_id('btn-shortcode')); ?>"
                data-item-id="<?php wpl_esc::attr($this->number); ?>"
                data-fancy-id="<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>" class="wpl-open-lightbox-btn wpl-button button-1"
                href="#<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>" type="button"><?php wpl_esc::html_t('View Shortcode'); ?></button>
    
        <div id="<?php wpl_esc::attr($this->get_field_id('wpl_view_shortcode')); ?>" class="hidden">
            <div class="fanc-content size-width-1">
                <h2><?php wpl_esc::html_t('View Shortcode'); ?></h2>
                <div class="fanc-body fancy-search-body">
                    <p class="wpl_widget_shortcode_preview">[wpl_widget_instance id="<?php wpl_esc::e($this->id); ?>"]</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>