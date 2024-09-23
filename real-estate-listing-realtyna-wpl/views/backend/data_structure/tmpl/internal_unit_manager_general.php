<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_unit_manager_js');
?>
<div id="wpl_data_structure_units" class="wpl_hidden_element"></div>
<table class="widefat page" cellspacing="0" width="100%">
	<thead>
	<tr>
		<th scope="col" class="manage-column" width="30"><?php wpl_esc::html_t('ID'); ?></th>
		<th scope="col" class="manage-column" width="50"><?php wpl_esc::html_t('Enabled'); ?></th>
		<th scope="col" class="manage-column" width="50"><?php wpl_esc::html_t('Name'); ?></th>
		<th scope="col" class="manage-column"><?php wpl_esc::html_t('Conv .Factor'); ?></th>
		<th scope="col" class="manage-column"><?php wpl_esc::html_t('Move'); ?></th>
	</tr>
	</thead>
	<tbody class="sortable_unit">
	<?php foreach ($units as $id => $unit): ?>
		<tr id="item_row_<?php wpl_esc::attr($unit['id']); ?>">
			<td>
				<?php wpl_esc::html($unit['id']); ?>
			</td>
			<td>
				<span class="action-btn enabled_check <?php wpl_esc::attr($unit['enabled'] == 1 ? "icon-enabled" : "icon-disabled"); ?> "
					  onclick="wpl_unit_enabled_change(<?php wpl_esc::attr($unit['id'] . ', ' . $unit['type']); ?>)"
					  id="wpl_ajax_flag_<?php wpl_esc::attr($unit['id'] . '_' . $unit['type']); ?>"></span>

				<span id="wpl_property_unit_<?php wpl_esc::attr($unit['id'] . '_' . $unit['type']); ?>"
					  data-realtyna-lightbox class="wpl_hidden_element" data-realtyna-href="#wpl_data_structure_units"
					  onclick="unit_enabled_state_replace_form(<?php wpl_esc::attr($unit['type'] . ', ' . $unit['id']); ?>);"></span>

				<span class="wpl_ajax_loader"
					  id="wpl_ajax_loader_<?php wpl_esc::attr($unit['id'] . '_' . $unit['type']); ?>"></span>
			</td>
			<td width="100">
				<input type="text" value="<?php wpl_esc::attr_t($unit['name']); ?>"
					   data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>" onchange="wpl_change_unit_name(this);"/>
				<span class="wpl-loader"></span>
			</td>
			<td width="100">
				<input type="text" value="<?php wpl_esc::attr_t($unit['tosi']); ?>"
					   data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>" onchange="wpl_change_unit_tosi(this);"/>
				<span class="wpl-loader"></span>
			</td>
			<td class="wpl_manager_td">
				<span class="action-btn icon-move" id="extension_move_1"></span>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>	
