<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_property_types_js');
wp_enqueue_style('wp-color-picker');
wp_enqueue_script('wp-color-picker');
?>
<table class="widefat page">
    <thead>
        <tr>
        	<th scope="col" class="size-1 manage-column" colspan="2"><?php wpl_esc::html_t('Categories'); ?></th>
            <th colspan="5">
                <div class="actions-wp">
                    <a data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" class="action-btn icon-plus" href="#wpl_data_structure_edit_div" onclick="wpl_generate_new_page_ptcategory();"></a>
                </div>
            </th>
        </tr>
    </thead>
    <tbody class="sortable_ptcategory">
        <?php foreach($categories as $category): ?>
        <tr id="item_row_<?php wpl_esc::attr($category['id']); ?>">
            <td class="size-1"><?php wpl_esc::attr($category['id']); ?></td>
            <td><?php wpl_esc::html_t($category['name']); ?></td>
            <td class="manager-wp">
                <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php wpl_esc::attr($category['id']); ?>"></span>
            </td>
            <td class="manager-wp">
                <?php if(($category['editable'] == 1) || ($category['editable'] == 2)): ?>
                <a data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" href="#wpl_data_structure_edit_div" class="action-btn icon-edit" onclick="wpl_generate_edit_page_ptcategory(<?php wpl_esc::attr($category['id']); ?>);"></a>
                <?php endif; ?>
            </td>
            <td class="manager-wp">
                <?php if($category['editable'] == 2): ?>
                <span id="wpl_ptcategory_remove<?php wpl_esc::attr($category['id']); ?>" class="action-btn icon-recycle" onclick="wpl_remove_ptcategory(<?php wpl_esc::attr($category['id']); ?>, 0);"></span>
                <?php endif; ?>
            </td>
            <td class="manager-wp">
                <span class="action-btn icon-move" id="extension_move_2"></span>
            </td>
        </tr>
		<?php endforeach; ?>
    </tbody>
</table>
<br />
<table class="widefat page">
    <thead>
        <tr>
        	<th scope="col" class="size-1 manage-column" colspan="2"><?php wpl_esc::html_t('Property Types'); ?></th>
            <th colspan="5">
                <div class="actions-wp">
                    <a data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" class="action-btn icon-plus" href="#wpl_data_structure_edit_div" onclick="wpl_generate_new_page_property_type();"></a>
                </div>
            </th>
        </tr>
    </thead>
    <tbody class="sortable_property_type">
        <?php foreach ($property_types as $id => $wp_property_type): ?>
        <tr id="item_row_<?php wpl_esc::attr($wp_property_type['id']); ?>">
            <td class="size-1"><?php wpl_esc::attr($wp_property_type['id']); ?></td>
            <td><?php wpl_esc::html_t($wp_property_type['name']); ?></td>
            <td class="manager-wp">
                <span class="wpl_ajax_loader" id="wpl_ajax_loader_<?php wpl_esc::attr($wp_property_type['id']); ?>"></span>
            </td>
            <td class="manager-wp">
                <?php if (($wp_property_type['editable'] == 1) || ($wp_property_type['editable'] == 2)): ?>
                <a data-realtyna-lightbox data-realtyna-lightbox-opts="reloadPage:true" href="#wpl_data_structure_edit_div" class="action-btn icon-edit" onclick="wpl_generate_edit_page_property_type(<?php wpl_esc::attr($wp_property_type['id']); ?>);"></a>
                <?php endif; ?>
            </td>
            <td class="manager-wp">
                <?php if ($wp_property_type['editable'] == 2): ?>
                <span id="wpl_property_type_remove<?php wpl_esc::attr($wp_property_type['id']); ?>" data-realtyna-href="#wpl_data_structure_edit_div" class="action-btn icon-recycle" onclick="wpl_remove_property_type(<?php wpl_esc::attr($wp_property_type['id']); ?>, 0);"></span>
                <?php endif; ?>
            </td>
            <td class="manager-wp">
                <?php
                if($wp_property_type['enabled'] == 1)
                {
                    $property_type_enable_class = "wpl_show";
                    $property_type_disable_class = "wpl_hidden";
                }
                else
                {
                    $property_type_enable_class = "wpl_hidden";
                    $property_type_disable_class = "wpl_show";
                }
                ?>
                <span class="action-btn icon-disabled <?php wpl_esc::attr($property_type_disable_class); ?>" id="property_types_disable_<?php wpl_esc::attr($wp_property_type['id']); ?>" onclick="wpl_set_enabled_property_type(<?php wpl_esc::attr($wp_property_type['id']); ?>, 1);"></span>
                <span class="action-btn icon-enabled <?php wpl_esc::attr($property_type_enable_class); ?>" id="property_types_enable_<?php wpl_esc::attr($wp_property_type['id']); ?>" onclick="wpl_set_enabled_property_type(<?php wpl_esc::attr($wp_property_type['id']); ?>, 0);"></span>
            </td>
            <td class="manager-wp">
                <span class="action-btn icon-move" id="extension_move_1"></span>
            </td>
        </tr>
		<?php endforeach; ?>
    </tbody>
</table>