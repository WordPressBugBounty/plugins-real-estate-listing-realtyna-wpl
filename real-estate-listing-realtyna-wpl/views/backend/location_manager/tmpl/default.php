<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp location-wp">
    <header>
        <div id="icon-location" class="icon48"></div>
        <h2><?php wpl_esc::html_t('Locations'); ?></h2>
        <a data-realtyna-lightbox data-realtyna-lightbox-opts="clearContent:false" href="#wpl_location_settings_lightbox" class="action-btn icon-gear"><?php wpl_esc::html_t('Settings'); ?></a>
    </header>
    <div class="sidebar-wp">
        <div class="side-15">
            <div class="panel-wp">
                <h3>
                    <?php wpl_esc::html_t('Location Tools'); ?>
                </h3>
                <div class="panel-body">
                    <?php
                    $params = array('element_class' => 'location_breadcrumb', 'root_url' => $this->admin_url . 'admin.php?page=wpl_admin_locations', 'location_level' => $this->level, 'location_id' => $this->parent, 'load_zipcodes' => $this->load_zipcodes);
                    wpl_global::import_activity('location_breadcrumb:location_manager', '', $params);
                    ?>
                    <div class="location_tools">
                        <?php if ($this->level == 1 and $this->enabled == 1): ?>
                        <input class="button" type="submit" value="<?php wpl_esc::attr_t('Show all countries'); ?>" onclick="wpl_show_countries(-1);" />
                        <?php elseif ($this->level == 1): ?>
                        <input class="button" type="submit" value="<?php wpl_esc::attr_t('Show enabled countries'); ?>" onclick="wpl_show_countries(1);" />
                        <?php endif; ?>
                        <input class="text_box" type="text" id="wpl_search_location" value="<?php wpl_esc::attr($this->text_search); ?>" autocomplete="on" />
                        <input class="button" type="submit" value="<?php wpl_esc::attr_t('Search'); ?>" onclick="wpl_search_location('wpl_search_location', '<?php wpl_esc::attr($this->level); ?>');" />
                        <input class="button" type="submit" value="<?php wpl_esc::attr_t('Reset'); ?>" onclick="wpl_reset_search_location();" />
                    </div> 
                </div>
            </div>
            <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
            <div class="pagination-wp">
                <?php wpl_esc::e($this->pagination->show()); ?>
            </div>
            <?php endif; ?>
			<div class="wpl_location_list"><div class="wpl_show_message"></div></div>
            <table class="widefat page fixed">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column size-1"><?php wpl_global::order_table(wpl_esc::return_html_t('ID'), 'id'); ?></th>
                        <th scope="col" class="manage-column size-4"><?php wpl_global::order_table(wpl_esc::return_html_t('Name'), 'name'); ?></th>
                        <th scope="col" class="manage-column size-3">&nbsp;</th>
                        <th scope="col" class="manage-column size-2">&nbsp;</th>
                        <th scope="col" class="manage-column"><?php wpl_esc::html_t('Actions'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th scope="col" class="manage-column size-1"><?php wpl_global::order_table(wpl_esc::return_html_t('ID'), 'id'); ?></th>
                        <th scope="col" class="manage-column size-4"><?php wpl_global::order_table(wpl_esc::return_html_t('Name'), 'name'); ?></th>
                        <th scope="col" class="manage-column size-3">&nbsp;</th>
                        <th scope="col" class="manage-column size-2">&nbsp;</th>
                        <th scope="col" class="manage-column"><?php wpl_esc::html_t('Actions'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                	<?php if(!count($this->wp_locations)): ?>
                    <tr>
                    	<td onclick="wplj('#wpl_add_location_item').trigger('click');" class="wpl_no_item" colspan="5"><?php wpl_esc::html_t('No location! Add a new location'); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php
                    foreach ($this->wp_locations as $wp_location)
					{
                        $link = $this->admin_url . "admin.php?page=wpl_admin_locations&level=" . ($this->level + 1) . "&sf_select_parent=" . $wp_location->id;
                    ?>
                    <tr id="item_row<?php wpl_esc::attr($wp_location->id); ?>">
                        <td><?php wpl_esc::html($wp_location->id); ?></td>
                        <td><?php wpl_esc::html($wp_location->name); ?></td>
                        <td>
                            <?php
                            if ($this->level < 7 and !$this->load_zipcodes): ?>
                                <a href="<?php wpl_esc::url($link); ?>"><?php wpl_esc::html_t('Load Next Level'); ?></a>
							<?php endif; ?>
							<?php if ($this->level == $this->zipcode_parent_level): ?>
								<br /><a href="<?php wpl_esc::url($link . '&load_zipcodes=1'); ?>"><?php wpl_esc::html_t('Load Zipcodes'); ?></a>
                            <?php endif; ?>
                        </td>
                        <td class="wpl_manager_td">
                            <span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($wp_location->id); ?>"></span>
                        </td>
                        <td class="wpl_actions_td">
                            <?php
                            if ($this->level == 1) {
                                if ($wp_location->enabled == 1) {
                                    $location_enable_class = "wpl_show";
                                    $location_disable_class = "wpl_hidden";
                                } else {
                                    $location_enable_class = "wpl_hidden";
                                    $location_disable_class = "wpl_show";
                                }
                                ?>
                                <span class="action-btn icon-disabled <?php wpl_esc::attr($location_disable_class); ?>" id="location_disable_<?php wpl_esc::attr($wp_location->id); ?>" onclick="wpl_set_enabled_location(<?php wpl_esc::attr($wp_location->id); ?>, 1);"></span>
                                <span class="action-btn icon-enabled <?php wpl_esc::attr($location_enable_class); ?>" id="location_enable_<?php wpl_esc::attr($wp_location->id); ?>" onclick="wpl_set_enabled_location(<?php wpl_esc::attr($wp_location->id); ?>, 0);"></span>
                            <?php } ?>
                            <a data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" href="#wpl_location_fancybox_cnt" class="action-btn icon-edit" onclick="wpl_generate_modify_page('<?php wpl_esc::attr(!$this->load_zipcodes ? $this->level : 'zips'); ?>', '', '<?php wpl_esc::attr($wp_location->id); ?>');"></a>
                            <span class="action-btn icon-recycle" onclick="wpl_remove_location('<?php wpl_esc::attr(!$this->load_zipcodes ? $this->level : 'zips'); ?>', '<?php wpl_esc::attr($wp_location->id); ?>', 0);"></span>
                            <a data-realtyna-lightbox href="#wpl_location_fancybox_cnt" class="action-btn icon-gear" onclick="wpl_generate_params_page('<?php wpl_esc::attr(!$this->load_zipcodes ? $this->level : 'zips'); ?>', '<?php wpl_esc::attr($wp_location->id); ?>');"></a>
                            <?php /** including a custom file **/ $this->_wpl_import($this->tpl_path . '.custom.action_bar'); ?>
                        </td>
                    </tr>
	                <?php } ?>
                </tbody>
            </table>
            <?php if(isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
            <div class="pagination-wp">
                <?php wpl_esc::e($this->pagination->show()); ?>
            </div>
            <?php endif; ?>
            <div id="wpl_location_fancybox_cnt" class="wpl_hidden_element"></div>
            <div id="wpl_location_settings_lightbox" class="wpl_hidden_element">
                <div class="fanc-content size-width-1 fanc-settings">
                    <h2><?php wpl_esc::html_t('Location Settings'); ?></h2>
                    <div class="fanc-body label-x2">
					<?php
						$setting_records = wpl_settings::get_settings(3, 1, true);
						wpl_settings::generate_setting_forms($setting_records);
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>