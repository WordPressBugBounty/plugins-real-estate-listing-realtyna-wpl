<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if ((isset($values->specificable) and $values->specificable) or !$dbst_id) {
	?>
	<div class="fanc-row">
		<label for="<?php wpl_esc::attr($__prefix); ?>specificable"><?php wpl_esc::html_t('Specificable'); ?></label>
		<select id="<?php wpl_esc::attr($__prefix); ?>specificable"
				name="<?php wpl_esc::attr($__prefix); ?>specificable"
				onchange="wpl_flex_change_specificable(this.value, '<?php wpl_esc::attr($__prefix); ?>');">
			<option value="0"><?php wpl_esc::html_t('No'); ?></option>
			<option value="3" <?php if (trim($values->user_specific ?? '') != '') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('User type specific'); ?></option>
		</select>
		<div class="wpl_flex_specificable_cnt" id="<?php wpl_esc::attr($__prefix); ?>specificable3"
			 style="<?php if (!isset($values->user_specific) or (trim($values->user_specific ?? '') == '')) wpl_esc::e('display: none;'); ?>">
			<?php if (!$dbst_id or (isset($values->specificable) and ($values->specificable == 1))): ?>
				<ul id="<?php wpl_esc::attr($__prefix); ?>_user_specific" class="wpl_user_specific_ul">
					<li><input id="wpl_flex_user_checkbox_all" type="checkbox"
							   onclick="wpl_user_specific_all(this.checked);" <?php if (!isset($values->user_specific) or (trim($values->user_specific ?? '') == '')) wpl_esc::e('checked="checked"'); ?> /><label
								class="wpl_specific_label"
								for="wpl_flex_user_checkbox_all">&nbsp;<?php wpl_esc::html_t('All'); ?></label></li>
					<?php
					$user_specific = isset($values->user_specific) ? explode(',', $values->user_specific) : array();
					foreach ($user_types as $user_type) {
						?>
						<li><input id="wpl_flex_user_checkbox<?php wpl_esc::attr($user_type['id']); ?>" type="checkbox"
								   value="<?php wpl_esc::attr($user_type['id']); ?>" <?php if (!isset($values->user_specific) or (trim($values->user_specific ?? '') == '') or in_array($user_type['id'], $user_specific)) wpl_esc::e('checked="checked"');
							if (!isset($values->user_specific) or (trim($values->user_specific ?? '') == '')) wpl_esc::e('disabled="disabled"'); ?> /><label
									class="wpl_specific_label"
									for="wpl_flex_user_checkbox<?php wpl_esc::attr($user_type['id']); ?>">&nbsp;<?php wpl_esc::html_t($user_type['name']); ?></label>
						</li>
						<?php
					}
					?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
?>