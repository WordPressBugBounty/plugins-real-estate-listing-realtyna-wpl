<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$sort_options = wpl_sort_options::get_sort_options(2, 1);
?>
<div class="wpl_sort_options_container">
    <?php if(count($sort_options)): ?>
    <div class="wpl_sort_options_container_title"><?php wpl_esc::html('Sort Option'); ?></div>
    <span class="wpl-sort-options-list <?php if ($this->wpl_listing_sort_type == 'list') wpl_esc::e('active'); ?> <?php if ($this->wpl_listing_sort_type == 'dropdown') wpl_esc::e('wpl-util-hidden'); ?>"><?php wpl_esc::e($this->model->generate_sorts(array('type'=>1, 'kind'=>$this->kind, 'sort_options'=>$sort_options))); ?></span>
    <span class="wpl-sort-options-selectbox <?php if ($this->wpl_listing_sort_type == 'dropdown') wpl_esc::e('active'); ?> <?php if ($this->wpl_listing_sort_type == 'list') wpl_esc::e('wpl-util-hidden'); ?>"><?php wpl_esc::e($this->model->generate_sorts(array('type'=>0, 'kind'=>$this->kind, 'sort_options'=>$sort_options))); ?></span>
    <?php endif; ?>
    
    <?php if($this->property_css_class_switcher): ?>
    <div class="wpl_list_grid_switcher <?php if($this->switcher_type == "icon+text") wpl_esc::e('wpl-list-grid-switcher-icon-text'); ?>">
        <div id="grid_view" class="<?php wpl_esc::e(($this->switcher_type == "icon") ? 'wpl-tooltip-top ' : ''); ?>grid_view <?php if($this->property_css_class == 'grid_box' || $this->property_css_class == 'map_box') wpl_esc::e('active'); ?>">
            <?php if($this->switcher_type == "icon+text"): ?>
				<span><?php wpl_esc::html_t('Grid') ?></span>
			<?php endif; ?>
        </div>
        <?php if ($this->switcher_type == "icon"): ?>
            <div class="wpl-util-hidden"><?php wpl_esc::html_t('Grid') ?></div>
        <?php endif; ?>
        <div id="list_view" class="<?php wpl_esc::e(($this->switcher_type == "icon") ? 'wpl-tooltip-top ' : ''); ?>list_view <?php if($this->property_css_class == 'row_box') wpl_esc::e('active'); ?>">
            <?php if($this->switcher_type == "icon+text"): ?>
				<span><?php wpl_esc::html_t('List') ?></span>
			<?php endif; ?>
        </div>
        <?php if ($this->switcher_type == "icon"): ?>
            <div class="wpl-util-hidden"><?php wpl_esc::html_t('List') ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<div class="wpl-row wpl-expanded <?php if($this->property_css_class == "grid_box" || $this->property_css_class == 'map_box') wpl_esc::attr("wpl-small-up-1 wpl-medium-up-2 wpl-large-up-".$this->profile_columns); ?>  wpl_profile_listing_profiles_container clearfix">
  <?php wpl_esc::e($this->profiles_str); ?>
</div>

<?php if($this->wplpagination != 'scroll'): ?>
<div class="wpl_pagination_container">
    <?php wpl_esc::e($this->pagination->show()); ?>
</div>
<?php endif;