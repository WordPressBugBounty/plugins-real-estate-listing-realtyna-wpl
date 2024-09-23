<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$settings = wpl_global::get_settings();
?>
<div class="wpl-cronjobs">

	<div class="wpl_cronjobs_panel">
		<div class="wpl_show_message"></div>
	</div>
	<p><?php wpl_esc::kses(wpl_esc::return_t("WPL should do some jobs regularly in order to keep your website functional. By default, these regular jobs run when a single property details page is viewed by a visitor. However, this method can create some speed issues on your website frontend. We highly recommend that you to setup a cronjob on your website Control Panel to open the following URL <strong>once per 5 minutes</strong>.")); ?></p>
	<div><code><?php wpl_esc::e(wpl_global::get_wp_url('frontend') . '?wpl_do_cronjobs=1'); ?></code>
		<span><?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Latest Run: %s'), ((isset($settings['wpl_last_cpanel_cronjobs']) and trim($settings['wpl_last_cpanel_cronjobs'] ?? '')) ? '<strong>' . $settings['wpl_last_cpanel_cronjobs'] . '</strong>' : '<strong>' . wpl_esc::return_html_t('Never') . '</strong>'))); ?></span>
	</div>
	<br>
	<hr>
	<p><?php wpl_esc::html_t("After setting the cronjob, please disable the default cronjobs using the below form. ATTENTION: Please don't disable it if your cPanel cronjob is not set. If your cPanel cronjob is set correctly, then you will see the latest run time above."); ?></p>
	<form id="wpl_cronjobs_toggle_form">
		<span><?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Current Status: %s'), ((isset($settings['wpl_cronjobs']) and $settings['wpl_cronjobs']) ? '<span id="wpl_cronjobs_label"><strong style="color: red;">' . wpl_esc::return_html_t('Enabled') . '</strong></span>' : '<span id="wpl_cronjobs_label"><strong style="color: green;">' . wpl_esc::return_html_t('Disabled') . '</strong></span>'))); ?></span>
		<input type="hidden" name="status" id="wpl_cronjobs_status"
			   value="<?php wpl_esc::e((isset($settings['wpl_cronjobs']) and $settings['wpl_cronjobs']) ? '1' : '0'); ?>"/>
		<button type="submit" class="wpl-button button-1"
				id="wpl_cronjobs_toggle_submit"><?php wpl_esc::e((isset($settings['wpl_cronjobs']) and $settings['wpl_cronjobs']) ? wpl_esc::return_html_t('Disable it') : wpl_esc::return_html_t('Enable it')); ?></button>
	</form>
</div>