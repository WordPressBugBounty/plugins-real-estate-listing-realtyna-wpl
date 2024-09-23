<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl_links_widget_backend_form wpl-widget-form-wp" id="<?php wpl_esc::attr($this->get_field_id('wpl_links_widget_container')); ?>">
    
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('title')); ?>"><?php wpl_esc::html_t('Title'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('title')); ?>" name="<?php wpl_esc::attr($this->get_field_name('title')); ?>" value="<?php wpl_esc::attr($instance['title']); ?>" />
    </div>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('layout')); ?>"><?php wpl_esc::html_t('Layout'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('layout')); ?>" name="<?php wpl_esc::attr($this->get_field_name('layout')); ?>" class="widefat">
            <?php wpl_esc::e($this->generate_layouts_selectbox('links', $instance)); ?>
        </select>
    </div>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>"><?php wpl_esc::html_t('CSS Class'); ?></label>
        <input type="text" id="<?php wpl_esc::attr($this->get_field_id('data_css_class')); ?>" name="<?php wpl_esc::attr($this->get_field_name('data')); ?>[css_class]" value="<?php wpl_esc::attr($instance['data']['css_class'] ?? ''); ?>" />
    </div>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('register_link')); ?>"><?php wpl_esc::html_t('Register Link'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('register_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('register_link')); ?>">
            <option value="1" <?php if(isset($instance['register_link']) and $instance['register_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Show'); ?>
			</option>
            <option value="0" <?php if(isset($instance['register_link']) and $instance['register_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Hide'); ?>
			</option>
        </select>
    </div>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('login_link')); ?>"><?php wpl_esc::html_t('Login Link'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('login_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('login_link')); ?>">
            <option value="1" <?php if(isset($instance['login_link']) and $instance['login_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Show'); ?>
			</option>
            <option value="0" <?php if(isset($instance['login_link']) and $instance['login_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Hide'); ?>
			</option>
        </select>
    </div>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('forget_password_link')); ?>"><?php wpl_esc::html_t('Forget Password Link'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('forget_password_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('forget_password_link')); ?>">
            <option value="1" <?php if(isset($instance['forget_password_link']) and $instance['forget_password_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Show'); ?>
			</option>
            <option value="0" <?php if(isset($instance['forget_password_link']) and $instance['forget_password_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Hide'); ?>
			</option>
        </select>
    </div>

    <?php if(wpl_global::check_addon('membership')): ?>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('dashboard_link')); ?>"><?php wpl_esc::html_t('Dashboard Link'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('dashboard_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('dashboard_link')); ?>">
            <option value="1" <?php if(isset($instance['dashboard_link']) and $instance['dashboard_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Show'); ?>
			</option>
            <option value="0" <?php if(isset($instance['dashboard_link']) and $instance['dashboard_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Hide'); ?>
			</option>
        </select>
    </div>
    <?php endif; ?>

    <?php if(wpl_global::check_addon('pro')): ?>
    <div class="wpl-widget-row">
        <label for="<?php wpl_esc::attr($this->get_field_id('compare_link')); ?>"><?php wpl_esc::html_t('Property Compare Link'); ?></label>
        <select id="<?php wpl_esc::attr($this->get_field_id('compare_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('compare_link')); ?>">
            <option value="1" <?php if(isset($instance['compare_link']) and $instance['compare_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Show'); ?>
			</option>
            <option value="0" <?php if(isset($instance['compare_link']) and $instance['compare_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
				<?php wpl_esc::html_t('Hide'); ?>
			</option>
        </select>
    </div>
    <?php endif; ?>

    <?php if(wpl_global::check_addon('pro') and wpl_global::check_addon('membership')): ?>
        <div class="wpl-widget-row">
            <label for="<?php wpl_esc::attr($this->get_field_id('favorite_link')); ?>"><?php wpl_esc::html_t('Favorite Link'); ?></label>
            <select id="<?php wpl_esc::attr($this->get_field_id('favorite_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('favorite_link')); ?>">
                <option value="1" <?php if(isset($instance['favorite_link']) and $instance['favorite_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Show'); ?>
				</option>
                <option value="0" <?php if(isset($instance['favorite_link']) and $instance['favorite_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Hide'); ?>
				</option>
            </select>
        </div>
    <?php endif; ?>

    <?php if(wpl_global::check_addon('save_searches') and wpl_global::check_addon('membership')): ?>
        <div class="wpl-widget-row">
            <label for="<?php wpl_esc::attr($this->get_field_id('save_search_link')); ?>"><?php wpl_esc::html_t('Save Search Link'); ?></label>
            <select id="<?php wpl_esc::attr($this->get_field_id('save_search_link')); ?>" name="<?php wpl_esc::attr($this->get_field_name('save_search_link')); ?>">
                <option value="1" <?php if(isset($instance['save_search_link']) and $instance['save_search_link'] == 1) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Show'); ?>
				</option>
                <option value="0" <?php if(isset($instance['save_search_link']) and $instance['save_search_link'] == 0) wpl_esc::e('selected="selected"'); ?>>
					<?php wpl_esc::html_t('Hide'); ?>
				</option>
            </select>
        </div>
    <?php endif; ?>

</div>