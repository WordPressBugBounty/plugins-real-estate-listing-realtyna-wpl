<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>

<table class="widefat page" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th style="width: 30px;">
                <?php wpl_esc::html_t('ID'); ?>
            </th>
            <th>
                <?php wpl_esc::html_t('Name'); ?>
            </th>
            <th>
                <?php wpl_esc::html_t('Type'); ?>
            </th>
            <th></th>
            <th></th>
            <th class="wpl-mandatory-fields"></th>
            <th class="wpl-edit-fields"></th>
            <th class="wpl-remove-fields"></th>
            <th class="wpl-disable-enable-fields"></th>
            <th class="wpl-sort-options-fields"></th>
            <th class="wpl-sort-fields"></th>
        </tr>
    </thead>
    <tbody class="sortable">
        <?php foreach ($this->fields as $field): ?>

            <tr id="item_row_<?php wpl_esc::attr($field->id); ?>">
                <?php
                //enable fields
                $wpl_field_enable_class = $field->enabled == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_disable_class = $field->enabled == 0 ? "wpl_show" : "wpl_hidden";
                $wpl_field_always_enable_class = $field->enabled == 2 ? "wpl_show" : "wpl_hidden";

                //mandatori fields
                $wpl_field_mandatory_class = $field->mandatory == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_mandatory_disable_class = $field->mandatory == 0 ? "wpl_show" : "wpl_hidden";
                $wpl_field_mandatory_always_class = $field->mandatory == 2 ? "wpl_show cursor-none" : "wpl_hidden";
                $wpl_field_mandatory_never_class = $field->mandatory == 3 ? "wpl_show cursor-none" : "wpl_hidden";

                //sort option fields
                $wpl_field_sort_option_class = $field->sortable == 1 && in_array($field->table_column, $this->sort_options) ? "wpl_show" : "wpl_hidden";
                $wpl_field_sort_option_disable_class = $field->sortable == 1 && !in_array($field->table_column, $this->sort_options) ? "wpl_show" : "wpl_hidden";
                $wpl_field_sort_option_always_class = $field->sortable == 2 ? "wpl_show cursor-none" : "wpl_hidden";
                $wpl_field_sort_option_never_class = $field->sortable == 0 ? "wpl_show cursor-none" : "wpl_hidden";

                //editable fields
                $wpl_field_editable_class = $field->editable == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_editable_dis_class = $field->editable == 0 ? "wpl_show" : "wpl_hidden";

                //deletable fields
                $wpl_field_deletable_class = $field->deletable == 1 ? "wpl_show" : "wpl_hidden";
                $wpl_field_deletable_dis_class = $field->deletable == 0 ? "wpl_show" : "wpl_hidden";
                ?>

                <td title="<?php wpl_esc::attr($field->table_name . ', ' . $field->table_column); ?>"><?php wpl_esc::html_t($field->id); ?></td>
                <td><?php wpl_esc::html_t($field->name); ?></td>
                <td><?php wpl_esc::html($field->type); ?></td>

                <td class="wpl_manager_td">
                    <span id="wpl_flex_remove_ajax_loader<?php wpl_esc::attr($field->id); ?>"></span>
                    <span class="wpl_ajax_loader" id="wpl_flex_ajax_loader_<?php wpl_esc::attr($field->id); ?>"></span>
                </td>
                <td class="wpl_manager_td">
                    <span data-realtyna-lightbox data-realtyna-href="#wpl_flex_edit_div" class="action-btn icon-gear" onclick="wpl_generate_params_page('<?php wpl_esc::attr($field->id); ?>');"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-star disable <?php wpl_esc::attr($wpl_field_mandatory_disable_class); ?>" id="wpl_flex_field_mandatory_dis_span<?php wpl_esc::attr($field->id); ?>" onclick="wpl_dbst_mandatory(<?php wpl_esc::attr($field->id); ?>, 1);"></span>
                    <span class="action-btn icon-star <?php wpl_esc::attr($wpl_field_mandatory_class); ?>" id="wpl_flex_field_mandatory_span<?php wpl_esc::attr($field->id); ?>" onclick="wpl_dbst_mandatory(<?php wpl_esc::attr($field->id); ?>, 0);"></span>

                    <span class="action-btn icon-star <?php wpl_esc::attr($wpl_field_mandatory_always_class); ?>" id="wpl_flex_field_mandatory_always_span<?php wpl_esc::attr($field->id); ?>" title="<?php wpl_esc::attr_t('This field is always mandatory.'); ?>"></span>
                    <span class="action-btn icon-star disable <?php wpl_esc::attr($wpl_field_mandatory_never_class); ?>" id="wpl_flex_field_mandatory_never_span<?php wpl_esc::attr($field->id); ?>" title="<?php wpl_esc::attr_t('This field is never mandatory.'); ?>" ></span>
                </td>

                <td class="wpl_manager_td">
                    <span data-realtyna-lightbox data-realtyna-href="#wpl_flex_edit_div" class="action-btn icon-edit <?php wpl_esc::attr($wpl_field_editable_class); ?>" onclick="generate_modify_page(<?php wpl_esc::attr($field->id); ?>, '<?php wpl_esc::attr($field->type); ?>');"></span>
                    <span class="action-btn icon-edit disable <?php wpl_esc::attr($wpl_field_editable_dis_class); ?> cursor-none"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-recycle <?php wpl_esc::attr($wpl_field_deletable_class); ?>" onclick="wpl_remove_dbst(<?php wpl_esc::attr($field->id); ?>, 0);"></span>
                    <span class="action-btn icon-recycle disable <?php wpl_esc::attr($wpl_field_deletable_dis_class); ?> cursor-none"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-disabled <?php wpl_esc::attr($wpl_field_disable_class); ?>" id="wpl_flex_field_disable_span<?php wpl_esc::attr($field->id); ?>" onclick="wpl_dbst_enabled(<?php wpl_esc::attr($field->id); ?>, 1);"></span>
                    <span class="action-btn icon-enabled <?php wpl_esc::attr($wpl_field_enable_class); ?>" id="wpl_flex_field_enable_span<?php wpl_esc::attr($field->id); ?>" onclick="wpl_dbst_enabled(<?php wpl_esc::attr($field->id); ?>, 0);"></span>
                    <span class="action-btn icon-enabled disable <?php wpl_esc::attr($wpl_field_always_enable_class); ?> cursor-none" id="wpl_flex_field_enable_dis_span<?php wpl_esc::attr($field->id); ?>"></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-add-to-sort-options <?php wpl_esc::attr($wpl_field_sort_option_disable_class); ?>" id="wpl_flex_field_sort_option_dis_span<?php wpl_esc::attr($field->id); ?>" onclick="wpl_sort_option(<?php wpl_esc::attr($field->id); ?>, <?php wpl_esc::attr($this->kind); ?>, 1);" title="<?php wpl_esc::attr_t('Add to available sort options.'); ?>"></span>
                    <span class="action-btn icon-remove-from-sort-options <?php wpl_esc::attr($wpl_field_sort_option_class); ?>" id="wpl_flex_field_sort_option_span<?php wpl_esc::attr($field->id); ?>" onclick="wpl_sort_option(<?php wpl_esc::attr($field->id); ?>, <?php wpl_esc::attr($this->kind); ?>, 0);" title="<?php wpl_esc::attr_t('Remove from sort options.'); ?>"></span>

                    <span class="action-btn icon-remove-from-sort-options disable <?php wpl_esc::attr($wpl_field_sort_option_always_class); ?>" id="wpl_flex_field_sort_option_always_span<?php wpl_esc::attr($field->id); ?>" title="<?php wpl_esc::attr_t('This field is always sortable.'); ?>"></span>
                    <span class="action-btn icon-add-to-sort-options disable <?php wpl_esc::attr($wpl_field_sort_option_never_class); ?>" id="wpl_flex_field_sort_option_never_span<?php wpl_esc::attr($field->id); ?>" title="<?php wpl_esc::attr_t('This field is not sortable.'); ?>" ></span>
                </td>
                <td class="wpl_manager_td">
                    <span class="action-btn icon-move" id="extension_move_<?php wpl_esc::attr($field->id); ?>"></span>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>