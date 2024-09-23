<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<?php if (!wpl_global::check_addon('membership')): ?>
	<div class="fanc-row">
		<?php wpl_esc::html_t('Membership Add-on must be installed for this!'); ?>
	</div>
<?php else: ?>
	<div class="fanc-row">
		<div class="fanc-row">
			<label for="<?php wpl_esc::attr($__prefix); ?>accesses"><?php wpl_esc::html_t('Viewable by'); ?></label>
			<select id="<?php wpl_esc::attr($__prefix); ?>accesses" name="<?php wpl_esc::attr($__prefix); ?>accesses"
					onchange="wpl_flex_change_accesses(this.value, '<?php wpl_esc::attr($__prefix); ?>');">
				<option value="2"><?php wpl_esc::html_t('All Users'); ?></option>
				<option value="1" <?php if (trim($values->accesses ?? '') != '') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Selected Users'); ?></option>
			</select>
		</div>
		<div class="wpl_flex_accesses_cnt" id="<?php wpl_esc::attr($__prefix); ?>accesses_cnt"
			 style="<?php if (empty($values->accesses)) wpl_esc::e('display: none;'); ?>">
			<div class="fanc-row" id="<?php wpl_esc::attr($__prefix); ?>accesses_message_row">
				<label for="<?php wpl_esc::attr($__prefix); ?>accesses_message"><?php wpl_esc::html_t('Message'); ?></label>
				<input type="text" value="<?php wpl_esc::attr($values->accesses_message ?? ''); ?>"
					   id="<?php wpl_esc::attr($__prefix); ?>accesses_message"
					   name="<?php wpl_esc::attr($__prefix); ?>accesses_message"
					   placeholder="<?php wpl_esc::attr_t('Leave empty to hide!'); ?>"/>
			</div>
			<ul id="<?php wpl_esc::attr($__prefix); ?>_accesses_ul" class="wpl_accesses_ul">
				<?php
				$accesses = isset($values->accesses) ? explode(',', $values->accesses) : array();
				foreach ($memberships as $membership) {
					?>
					<li><input id="wpl_flex_membership_checkbox<?php wpl_esc::attr($membership->id); ?>" type="checkbox"
							   value="<?php wpl_esc::attr($membership->id); ?>" <?php if (empty($values->accesses) or in_array($membership->id, $accesses)) wpl_esc::e('checked="checked"'); ?> /><label
								class="wpl_specific_label"
								for="wpl_flex_membership_checkbox<?php wpl_esc::attr($membership->id); ?>">&nbsp;<?php wpl_esc::html_t($membership->membership_name); ?></label>
					</li>
					<?php
				}
				?>
			</ul>

		</div>
	</div>
<?php endif; ?>