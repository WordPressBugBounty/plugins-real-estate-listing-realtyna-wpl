<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp">
    <header>
        <div id="icon-data-structure" class="icon48"></div>
        <h2><?php wpl_esc::html_t('Activity Manager'); ?></h2>
    </header>
    <div class="wpl_activity_manager_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="activity_manager_top_bar">
        	<div class="wpl_left_section">
            	<input type="text" name="activity_manager_filter" id="activity_manager_filter" placeholder="<?php wpl_esc::attr_t('Filter'); ?>" autocomplete="off" />
            </div>
            <div class="wpl_right_section">
                <select name="wpl_activity_add" id="wpl_activity_add" data-has-chosen="1">
                    <option value="">-----</option>
                    <?php foreach($this->available_activities as $available_activity): ?>
                    <option value="<?php wpl_esc::attr($available_activity); ?>"><?php wpl_esc::html($available_activity); ?></option>
                    <?php endforeach; ?>
                </select>&nbsp;
                <span class="wpl_create_new action-btn icon-plus" title="<?php wpl_esc::attr_t('Add Activity'); ?>" onclick="wpl_generate_modify_activity_page(0);"></span>
                <span id="wpl_lightbox_handler" class="wpl_hidden_element" data-realtyna-href="#wpl_activity_manager_edit_div"></span>
            </div>
            <div class="clearfix"></div>
        </div>
        <table class="widefat page" id="wpl_activity_manager_table">
            <thead>
                <tr>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('ID'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Title'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Activity'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Layout'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Position'); ?></th>
                    <th></th>
                    <th id="wpl_actions_td_thead" scope="col" class="manage-column wpl_actions_td"><?php wpl_esc::html_t('Actions'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('ID'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Title'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Activity'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Layout'); ?></th>
                    <th scope="col" class="manage-column"><?php wpl_esc::html_t('Position'); ?></th>
                    <th></th>
                    <th scope="col" class="manage-column wpl_actions_td"><?php wpl_esc::html_t('Actions'); ?></th>
                </tr>
            </tfoot>
            <tbody class="sortable_activity">
                <?php
                foreach($this->activities as $activity)
				{
                    $activity_field_name = wpl_activity::get_activity_name_layout($activity->activity);
					
                    /** Skip Backend Activity **/
                    if(wpl_activity::check_activity($activity_field_name[0], wpl_activity::ACTIVITY_BACKEND)) continue;
                    ?>
                    <tr id="<?php wpl_esc::attr($activity->id); ?>">
                        <td class="size-1"><?php wpl_esc::html($activity->id); ?></td>
                        <td class="wpl_activity_title"><?php wpl_esc::html($activity->title); ?></td>
                        <td class="wpl_activity_activity"><?php wpl_esc::html($activity_field_name[0]); ?></td>
                        <td class="wpl_activity_layout"><?php wpl_esc::html($activity_field_name[1] ?? ''); ?></td>
                        <td class="wpl_activity_position"><?php wpl_esc::html($activity->position); ?></td>
                        <td class="manager-wp">
                            <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php wpl_esc::html($activity->id) ?>"></span>
                        </td>
                        <td class="wpl_actions_td">
                            <?php
                                if($activity->enabled == 1)
                                {
                                    $activity_enable_class = "wpl_show";
                                    $activity_disable_class = "wpl_hidden";
                                }
                                else
                                {
                                    $activity_enable_class = "wpl_hidden";
                                    $activity_disable_class = "wpl_show";
                                }
                            ?>
                            <span class="action-btn icon-disabled <?php wpl_esc::attr($activity_disable_class); ?>" id="activity_disable_<?php wpl_esc::attr($activity->id); ?>" onclick="wpl_set_enabled_activity(<?php wpl_esc::attr($activity->id); ?>, 1);"></span>
                            <span class="action-btn icon-enabled <?php wpl_esc::attr($activity_enable_class); ?>" id="activity_enable_<?php wpl_esc::attr($activity->id); ?>" onclick="wpl_set_enabled_activity(<?php wpl_esc::attr($activity->id); ?>, 0);"></span>
                            <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" data-realtyna-href="#wpl_activity_manager_edit_div" class="action-btn icon-edit" onclick="wpl_generate_modify_activity_page(<?php wpl_esc::attr($activity->id); ?>)"></span>
                            <span class="action-btn icon-recycle wpl_show" onclick="wpl_remove_activity(<?php wpl_esc::attr($activity->id); ?>);"></span>
                            <span class="action-btn icon-move" id="extension_move_1"></span>
                        </td>
                    </tr>
				<?php
				}
                ?>
            </tbody>
        </table>
    </div>
    <div id="wpl_activity_manager_edit_div" class="fanc-box-wp wpl_lightbox wpl_hidden_element"></div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>