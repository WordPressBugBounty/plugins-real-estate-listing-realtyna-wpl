<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

//$this->_wpl_import($this->tpl_path . '.scripts.css');
$this->_wpl_import($this->tpl_path . '.scripts.js');
?>
<div class="wrap wpl-wp flex-wp<?php wpl_esc::e($this->kind == 2 ? ' user-flex': ''); ?>">
    <header>
        <div id="icon-flex" class="icon48"></div>
        <h2><?php wpl_esc::html_t(ucfirst($this->kind_label) . ' Data Structure'); ?></h2>
    </header>

    <?php $this->include_tabs(); ?>
    <div class="wpl_flex_list">
        <div class="wpl_show_message"></div>
    </div>
    <div class="sidebar-wp">
        <!-- sidebar1 -->
        <div class="wpl-side-2 side-tabs-wp">
            <ul>
                <?php if(in_array($this->kind, $this->dbcat_manager_kinds)): ?>
                    <li>
						<a data-id="0" href="#0" class="wpl_slide_label wpl_slide_label_prefix_m" id="wpl_slide_label_id0" onclick="rta.internal.slides.open('0', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
							<?php wpl_esc::html_t('Category Management'); ?>
						</a>
					</li>
                <?php endif; ?>
                <?php foreach ($this->field_categories as $category): if($category->enabled==1): ?>
				    <li data-id="<?php wpl_esc::attr($category->id); ?>">
						<a href="#<?php wpl_esc::attr($category->id); ?>" class="wpl_slide_label wpl_slide_label_prefix_<?php wpl_esc::attr($category->prefix); ?>" id="wpl_slide_label_id<?php wpl_esc::attr($category->id); ?>" onclick="rta.internal.slides.open('<?php wpl_esc::attr($category->id); ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
							<?php wpl_esc::html_t($category->name); ?>
						</a>
					</li>
                <?php endif; endforeach; ?>
            </ul>
        </div>
        
        <div class="wpl-side-9 side-content-wp flex-content wpl-util-no-padding">
            <!-- sidebar2 -->
            <div class="wpl_slide_container2" >
                <?php if(in_array($this->kind, $this->dbcat_manager_kinds)): ?>
                    <div class="wpl_slide_container" id="wpl_slide_container_id0">
                        <table class="widefat page" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php wpl_esc::html_t('Category Name'); ?></th>
                                    <th colspan="5"></th>
                                </tr>
                            </thead>
                            <tbody class="categories_sortable">
                                <?php foreach ($this->field_categories as $category): ?>
                                    <tr class="cat_<?php wpl_esc::attr($category->id); ?>" id="extension-move-<?php wpl_esc::attr($category->id); ?>-<?php wpl_esc::attr($category->name); ?>">
                                        <td>
                                            <span id="category-<?php wpl_esc::attr($category->id); ?>" ><?php wpl_esc::attr($category->name); ?></span>
                                        </td>
                                        <td>
                                            <div id="category_remove_loader<?php wpl_esc::attr($category->id); ?>"></div>
                                        </td>
                                        <td class="wpl_manager_td">
                                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_flex_category" class="action-btn icon-edit" onclick="wpl_category_form(<?php wpl_esc::attr($category->id); ?>);"></span>
                                        </td>
                                        <td class="wpl_manager_td">
                                            <span class="action-btn icon-recycle wpl_show" onclick="wpl_remove_category('<?php wpl_esc::attr($category->id); ?>', 0);"></span>
                                        </td>

                                        <td class="wpl_manager_td">
                                            <span class="<?php wpl_esc::e($category->enabled == 1 ? 'action-btn icon-enabled' : 'action-btn icon-disabled'); ?> wpl_show" id="wpl_flex_field_enable_span1" onclick="wpl_toggle_category_status('<?php wpl_esc::attr($category->id); ?>');"></span>
                                        </td>
                                        <td class="wpl_manager_td">
                                            <span class="action-btn icon-move" id="extension_move_<?php wpl_esc::attr($category->id); ?>" ></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <?php foreach ($this->field_categories as $category): if($category->enabled==1): ?>
                    <div class="wpl_slide_container" id="wpl_slide_container_id<?php wpl_esc::attr($category->id); ?>">
                        <?php  $this->generate_slide($category); ?>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
        <div class="side-3 flex-right-panel wpl-util-no-padding">
            <?php $this->generate_sidebar(3); ?>
        </div>
    </div>
    <div id="wpl_flex_edit_div" class="wpl_hidden_element"></div>
    <div id="wpl_flex_category" class="wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>