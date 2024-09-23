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
		<th scope="col" class="manage-column"><?php wpl_esc::html_t('3digit sep'); ?></th>
		<th scope="col" class="manage-column"><?php wpl_esc::html_t('Decimal sep'); ?></th>
		<th scope="col" class="manage-column"><?php wpl_esc::html_t('Cur. after/before'); ?></th>
		<th scope="col" class="manage-column">
			<?php wpl_esc::html_t('Exchange rate'); ?>
			<button type="button" class="wpl-btn-overlay" onclick="wpl_update_exchange_rates(this);">
				<span class="action-btn icon-recycle-2"></span>
			</button>
			<span class="wpl-loader"></span>
		</th>
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
				<span class="action-btn enabled_check <?php wpl_esc::attr($unit['enabled'] == 1 ? "icon-enabled" : "icon-disabled"); ?>"
					  onclick="wpl_unit_enabled_change(<?php wpl_esc::attr($unit['id'] . ', ' . $unit['type']); ?>);"
					  id="wpl_ajax_flag_<?php wpl_esc::attr($unit['id'] . '_' . $unit['type']); ?>"></span>

				<span id="wpl_property_unit_<?php wpl_esc::attr($unit['id'] . '_' . $unit['type']); ?>"
					  data-realtyna-lightbox class="wpl_hidden_element" data-realtyna-href="#wpl_data_structure_units"
					  onclick="unit_enabled_state_replace_form(<?php wpl_esc::attr($unit['type'] . ', ' . $unit['id']); ?>);"></span>

				<span class="wpl_ajax_loader"
					  id="wpl_ajax_loader_<?php wpl_esc::attr($unit['id'] . '_' . $unit['type']); ?>"></span>
			</td>
			<td>
				<input type="text" value="<?php wpl_esc::attr_t($unit['name']); ?>"
					   data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>" onchange="wpl_change_unit_name(this);"/>
				<span class="wpl-loader"></span>
				<span><?php wpl_esc::html_t($unit['extra3']); ?> ( <?php wpl_esc::html_t($unit['extra']); ?> ) </span>
			</td>
			<td>
				<select class="selectbox" data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>" data-wpl-option="seperator"
						onchange="wpl_change_unit_option(this);">
					<option value=""><?php wpl_esc::html_t('No separator'); ?></option>
					<option value="," <?php wpl_esc::attr_str_if($unit['seperator'] == ',', 'selected', 'selected'); ?>>
						, (<?php wpl_esc::html_t('Comma'); ?>)
					</option>
					<option value="." <?php wpl_esc::attr_str_if($unit['seperator'] == '.', 'selected', 'selected'); ?>>
						. (<?php wpl_esc::html_t('Point'); ?>)
					</option>
					<option value="space" <?php wpl_esc::attr_str_if(strlen($unit['seperator'] ?? "") == 2, 'selected', 'selected'); ?>> <?php wpl_esc::html_t('Space'); ?></option>
					<option value="double space" <?php wpl_esc::attr_str_if(strlen($unit['seperator'] ?? "") == 4, 'selected', 'selected'); ?>> <?php wpl_esc::html_t('Double Space'); ?></option>
				</select>
				<span class="wpl-loader"></span>
			</td>
			<td>
				<select class="selectbox" data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>"
						data-wpl-option="d_seperator" onchange="wpl_change_unit_option(this);">
					<option value=""><?php wpl_esc::html_t('No decimal'); ?></option>
					<option value="," <?php wpl_esc::attr_str_if($unit['d_seperator'] == ',', 'selected', 'selected'); ?>>
						, (<?php wpl_esc::html_t('Comma'); ?>)
					</option>
					<option value="." <?php wpl_esc::attr_str_if($unit['d_seperator'] == '.', 'selected', 'selected'); ?>>
						. (<?php wpl_esc::html_t('Point'); ?>)
					</option>
				</select>
				<span class="wpl-loader"></span>
			</td>
			<td>
				<select class="selectboxmini" data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>"
						data-wpl-option="after_before" onchange="wpl_change_unit_option(this);">
					<option value="0"><?php wpl_esc::html_t('Before'); ?></option>
					<option value="1" <?php wpl_esc::attr_str_if($unit['after_before'] == 1, 'selected', 'selected'); ?>><?php wpl_esc::html_t('After'); ?></option>
					<option value="2" <?php wpl_esc::attr_str_if($unit['after_before'] == 2, 'selected', 'selected'); ?>><?php wpl_esc::html_t('Before (with Space)'); ?></option>
					<option value="3" <?php wpl_esc::attr_str_if($unit['after_before'] == 3, 'selected', 'selected'); ?>><?php wpl_esc::html_t('After (with Space)'); ?></option>
				</select>
				<span class="wpl-loader"></span>
			</td>
			<td>
				<input type="text" value="<?php wpl_esc::attr($unit['tosi']); ?>"
					   data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>" data-wpl-role="tosi-input"
					   onchange="wpl_change_unit_tosi(this);"/>
				<button type="button" class="wpl-btn-overlay" data-wpl-id="<?php wpl_esc::attr($unit['id']); ?>"
						data-wpl-currency-code="<?php wpl_esc::attr($unit['extra']) ?>"
						onclick="wpl_update_a_exchange_rate(this);">
					<span class="action-btn icon-recycle-2"></span>
				</button>
				<span class="wpl-loader"></span>
			</td>
			<td class="wpl_manager_td">
				<span class="action-btn icon-move" id="extension_move_1"></span>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
