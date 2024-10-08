<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_room_types_js');
$this->_wpl_import($this->tpl_path . '.scripts.internal_room_types_css');
?>
<div>
    <table class="widefat page">
        <thead>
            <tr>
                <th scope="col" class="manage-column">
					<?php wpl_esc::html_t('Name'); ?>
                    <div class="actions-wp">
                        <span data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" class="action-btn icon-plus" data-realtyna-href="#wpl_new_room_type" onclick="wpl_generate_new_room_type()" title="<?php wpl_esc::attr_t('Add new room type'); ?>"></span>
                    </div>
                </th>
                <th scope="col" class="size-1 manage-column"><?php wpl_esc::html_t('Enabled'); ?></th>
                <th scope="col" class="size-1 manage-column"><?php wpl_esc::html_t('Delete'); ?></th>
                <th scope="col" class="size-1 manage-column"><?php wpl_esc::html_t('Move'); ?></th>
            </tr>      
        </thead>
        <tbody class="sortable_room_types">
            <?php foreach ($room_types as $room): ?>
                <tr id="rooms_items_row_<?php wpl_esc::attr($room['id']); ?>">
                    <td class="manager-wp" style="text-align: left;">
                        <input type="text" value="<?php wpl_esc::html_t($room['name']); ?>" onchange="wpl_change_room_type_name(<?php wpl_esc::attr($room['id']); ?>, this.value)"/>
                        <span class="wpl_ajax_loader_room_name" id="wpl_ajax_loader_room_name_<?php wpl_esc::attr($room['id']); ?>"></span>
                    </td>
                    <td class="manager-wp">
                        <span class="action-btn <?php wpl_esc::attr($room['enabled'] == 1 ? "icon-enabled" : "icon-disabled"); ?>"
                              onclick="wpl_room_types_enabled_change(<?php wpl_esc::attr($room['id']); ?>);" id="wpl_ajax_flag_rooms_<?php wpl_esc::attr($room['id']); ?>"></span>
                        <span class="wpl_ajax_loader" id="wpl_ajax_loader_rooms_<?php wpl_esc::attr($room['id']); ?>"></span>							
                    </td>
                    <td class="manager-wp">
                        <span class="action-btn icon-recycle" onclick="wpl_remove_room_type(<?php wpl_esc::attr($room['id']); ?>, 0);"></span>
                    </td>
                    <td class="manager-wp">
                        <span class="action-btn icon-move" id="sort_move_<?php wpl_esc::attr($room['id']); ?>"></span>
                    </td>			
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div id="wpl_new_room_type" class="wpl_hidden_element"></div>