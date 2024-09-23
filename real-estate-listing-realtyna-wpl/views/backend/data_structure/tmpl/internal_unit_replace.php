<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');
$this->_wpl_import($this->tpl_path . '.scripts.internal_unit_replace_js');
?>
<div class="fanc-content size-width-2">
	<h2><?php wpl_esc::html_t('Replace Unit'); ?></h2>
	<div class="fanc-body">
		<div class="fanc-row fanc-button-row-2">
			<span class="ajax-inline-save" id="wpl_replaced_unit_ajax_loader"></span>
			<input class="wpl-button button-1" type="button" value="Save" id="wpl_replaced_unit_submit_button"
				   onclick="wpl_save_replaced_unit();">
		</div>
		<div class="col-wp">
			<div class="col-fanc-bottom wpl-fanc-full-row">
				<div class="fanc-row fanc-inline-title">
					<?php wpl_esc::html_t('Replace current unit with another active unit in listings'); ?>
				</div>
				<div class="fanc-row">
					<label for="wpl_replaced_unit"><?php wpl_esc::html_t('Set new unit type'); ?>:</label>
					<select data-old-unit="<?php wpl_esc::attr($unit_id); ?>"
							data-type="<?php wpl_esc::attr($unit_type); ?>" class="wpl_unit_selectbox"
							id="wpl_replaced_unit">
						<?php foreach ($units as $unit): if ($unit_id == $unit['id']) continue; ?>
							<option value="<?php wpl_esc::attr($unit['id']); ?>"><?php wpl_esc::html($unit['name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>