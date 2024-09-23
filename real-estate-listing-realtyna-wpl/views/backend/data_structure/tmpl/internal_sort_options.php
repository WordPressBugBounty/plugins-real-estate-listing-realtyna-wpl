<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_sort_options_js');
?>
<div>
	<table class="widefat page">
		<thead>
		<tr>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Name'); ?></th>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Ascending Label'); ?></th>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Descending Label'); ?></th>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Default Order'); ?></th>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Kinds'); ?></th>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Enabled'); ?></th>
			<th scope="col" class="manage-column"><?php wpl_esc::html_t('Move'); ?></th>
		</tr>
		</thead>
		<tbody class="sortable_sort_options">
		<?php foreach ($sort_options as $option): ?>
			<tr id="items_row_<?php wpl_esc::attr($option['id']); ?>">
				<td>
					<input type="text" value="<?php wpl_esc::html_t($option['name']); ?>"
						   id="wpl_sort_option_name<?php wpl_esc::attr($option['id']); ?>"
						   onchange="wpl_save_sort_option(<?php wpl_esc::attr($option['id']); ?>, 'name', this.value);"/>
					<span id="wpl_sort_option_ajax_loader<?php wpl_esc::attr($option['id']); ?>"></span>
				</td>
				<td>
					<input <?php if ($option['asc_label'] === '0'): ?>disabled="disabled"<?php endif; ?> type="text"
						   value="<?php $option['asc_label'] !== '0' ? wpl_esc::attr($option['asc_label']) : wpl_esc::attr('Cannot Change!'); ?>"
						   id="wpl_sort_option_asc_label<?php wpl_esc::attr($option['id']); ?>"
						   onchange="wpl_save_sort_option(<?php wpl_esc::attr($option['id']); ?>, 'asc_label', this.value);"/>
					<span class="action-btn <?php wpl_esc::attr($option['asc_enabled'] == 1 ? "icon-enabled" : "icon-disabled"); ?>"
						  onclick="wpl_sort_options_enabled_change(<?php wpl_esc::attr($option['id']); ?>, 'asc_enabled');"
						  id="wpl_ajax_asc_enabled_<?php wpl_esc::attr($option['id']); ?>"></span>
				</td>
				<td>
					<input <?php if ($option['desc_label'] === '0'): ?>disabled="disabled"<?php endif; ?> type="text"
						   value="<?php $option['desc_label'] !== '0' ? wpl_esc::attr($option['desc_label']) : wpl_esc::attr('Cannot Change!'); ?>"
						   id="wpl_sort_option_desc_label<?php wpl_esc::attr($option['id']); ?>"
						   onchange="wpl_save_sort_option(<?php wpl_esc::attr($option['id']); ?>, 'desc_label', this.value);"/>
					<span class="action-btn <?php wpl_esc::attr($option['desc_enabled'] == 1 ? "icon-enabled" : "icon-disabled"); ?>"
						  onclick="wpl_sort_options_enabled_change(<?php wpl_esc::attr($option['id']); ?>, 'desc_enabled');"
						  id="wpl_ajax_desc_enabled_<?php wpl_esc::attr($option['id']); ?>"></span>
				</td>
				<td>
					<select id="wpl_sort_option_default_order<?php wpl_esc::attr($option['id']); ?>"
							onchange="wpl_save_sort_option(<?php wpl_esc::attr($option['id']); ?>, 'default_order', this.value);"
							title="<?php wpl_esc::attr_t('Default Order'); ?>">
						<option value="DESC" <?php wpl_esc::e($option['default_order'] == 'DESC' ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('High to Low'); ?></option>
						<option value="ASC" <?php wpl_esc::e($option['default_order'] == 'ASC' ? 'selected="selected"' : ''); ?>><?php wpl_esc::html_t('Low to High'); ?></option>
					</select>
				</td>
				<td class="manager-wp"><?php wpl_esc::html(implode('/', $option['kind'])); ?></td>
				<td class="manager-wp wpl_sort_options_manager">
					<span class="action-btn <?php wpl_esc::attr($option['enabled'] == 1 ? "icon-enabled" : "icon-disabled"); ?>"
						  onclick="wpl_sort_options_enabled_change(<?php wpl_esc::attr($option['id']); ?>, 'enabled');"
						  id="wpl_ajax_enabled_<?php wpl_esc::attr($option['id']); ?>"></span>
					<span class="wpl_ajax_loader"
						  id="wpl_ajax_loader_options_<?php wpl_esc::attr($option['id']); ?>"></span>
				</td>
				<td class="manager-wp">
					<span class="action-btn icon-move" id="sort_move_<?php wpl_esc::attr($option['id']); ?>"></span>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p><?php wpl_esc::html_t('Disabling/Enabling feature for ascending and descending options is for dropdown sort option only. The dropdown sort normally shows in map view of WPL.'); ?></p>
	<p><?php wpl_esc::html_t('Default order used for normal sort bar that appears in list or grid styles. "Low to High" means ascending order and "High to Low" means descending order.'); ?></p>
</div>