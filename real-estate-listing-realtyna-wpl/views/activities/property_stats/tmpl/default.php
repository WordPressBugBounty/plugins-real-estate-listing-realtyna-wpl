<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
if (isset($params['property_data']['data'])) {
	$property_id = $params['property_data']['data']['id'] ?? NULL;
} else {
	$wpl_properties = $params['wpl_properties'] ?? array();
	$property_id = $wpl_properties['current']['data']['id'] ?? NULL;
}

$show_contacts = (isset($params['contacts']) and $params['contacts']) ? 1 : 0;
$show_including_in_listing = (isset($params['including_in_listing']) and $params['including_in_listing']) ? 1 : 0;
$show_view_parent = (isset($params['view_parent']) and $params['view_parent'] and wpl_global::check_addon('complex')) ? 1 : 0;
$show_visit = (isset($params['visit']) and $params['visit']) ? 1 : 0;

$p2 = wpl_property::get_p2($property_id);
?>
<div class="wpl_property_stats_container" id="wpl_property_stats_container<?php wpl_esc::attr($property_id); ?>">

	<div class="wpl_property_stats_title">
		<span><?php wpl_esc::html_t('Property Statistics'); ?></span>
	</div>

	<div class="wpl_property_stats_inner">

		<?php if ($show_contacts): ?>
			<div class="property_stats_contacts">
				<div>
					<span><?php wpl_esc::html_t('Contacts') ?></span>:
					<b><?php wpl_esc::html($p2['contact_numb'] ?? 'N/A'); ?></b>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($show_including_in_listing): ?>
			<div class="property_stats_contacts">
				<div>
					<span><?php wpl_esc::html_t('Including in listings') ?>:</span>
					<b><?php wpl_esc::html($p2['inc_in_listings_numb'] ?? 'N/A'); ?></b>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($show_view_parent): ?>
			<div class="property_stats_contacts">
				<div>
					<span><?php wpl_esc::html_t('Views in Complex Page') ?>:</span>
					<b><?php wpl_esc::html($p2['view_in_parent_numb'] ?? 'N/A'); ?></b>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($show_visit): ?>
			<div class="property_stats_contacts">
				<div>
					<span><?php wpl_esc::html_t('Visits') ?></span>:
					<b><?php wpl_esc::html($p2['visit_time'] ?? 'N/A'); ?></b>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>