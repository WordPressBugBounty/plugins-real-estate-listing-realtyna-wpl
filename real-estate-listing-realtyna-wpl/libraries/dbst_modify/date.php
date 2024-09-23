<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

if ($type == 'date' and !$done_this) {
	?>
	<div class="fanc-body">
		<div class="fanc-row fanc-button-row-2">
			<span id="wpl_dbst_modify_ajax_loader"></span>
			<input class="wpl-button button-1" type="button"
				   onclick="save_dbst('<?php wpl_esc::attr($__prefix); ?>', <?php wpl_esc::attr($dbst_id); ?>);"
				   value="<?php wpl_esc::attr_t('Save'); ?>" id="wpl_dbst_submit_button"/>
		</div>
		<div class="col-wp">
			<div class="col-fanc-left" id="wpl_flex_general_options">
				<div class="fanc-row fanc-inline-title">
					<?php wpl_esc::html_t('General Options'); ?>
				</div>
				<?php
				/** include main file * */
				include _wpl_import('libraries.dbst_modify.main.main', true, true);
				?>
			</div>
			<div class="col-fanc-right" id="wpl_flex_specific_options">
				<div class="fanc-row fanc-inline-title">
					<?php wpl_esc::html_t('Specific Options'); ?>
				</div>
				<?php
				/** include specific file * */
				include _wpl_import('libraries.dbst_modify.main.' . ($kind == 2 ? 'user' : '') . 'specific', true, true);
				?>
				<div class="fanc-row fanc-inline-title">
					<?php wpl_esc::html_t('Date Range'); ?>
				</div>
				<div class="fanc-row">
					<label for="<?php wpl_esc::attr($__prefix); ?>opt_minimum_date"><?php wpl_esc::html_t('Min date'); ?></label>
					<input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_minimum_date"
						   id="<?php wpl_esc::attr($__prefix); ?>opt_minimum_date"
						   value="<?php wpl_esc::attr(isset($options['minimum_date']) ? $options['minimum_date'] : '1970-01-01'); ?>"
						   placeholder="1970-01-01"/>
				</div>
				<div class="fanc-row">
					<label for="<?php wpl_esc::attr($__prefix); ?>opt_maximum_date"><?php wpl_esc::html_t('Max date'); ?></label>
					<input type="text" name="<?php wpl_esc::attr($__prefix); ?>opt_maximum_date"
						   id="<?php wpl_esc::attr($__prefix); ?>opt_maximum_date"
						   value="<?php wpl_esc::attr(isset($options['maximum_date']) ? $options['maximum_date'] : 'now'); ?>"
						   placeholder="now"" />
				</div>
			</div>
		</div>
		<div class="col-wp">
			<div class="col-fanc-left">
				<div class="fanc-row fanc-inline-title">
					<?php wpl_esc::html_t('Accesses'); ?>
				</div>
				<?php
				/** include accesses file **/
				include _wpl_import('libraries.dbst_modify.main.accesses', true, true);
				?>
			</div>
		</div>
	</div>
	<?php
	$done_this = true;
}