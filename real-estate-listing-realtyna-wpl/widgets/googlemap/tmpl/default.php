<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

// WPL Main listing page
$listings_page = wpl_property::get_property_listing_link();
?>
<div id="wpl_googlemap_widget_cnt<?php wpl_esc::numeric($this->widget_id); ?>"
	 class="wpl-googlemap-widget <?php wpl_esc::attr($this->css_class); ?>">
	<?php wpl_esc::html($args['before_title']); ?>
	<?php wpl_esc::html_t($this->title); ?>
	<?php wpl_esc::html($args['after_title']); ?>

	<div class="wpl-googlemap-widget-link">
		<?php if (wpl_global::check_addon('aps')): ?>
			<a href="<?php wpl_esc::url(wpl_global::add_qs_var('wplpcc', 'map_box', wpl_global::add_qs_var('wplfmap', '1', $listings_page))); ?>">
				<?php wpl_esc::html_t('Map View'); ?>
			</a>
		<?php else: ?>
			<a href="<?php wpl_esc::url(wpl_global::add_qs_var('wplfmap', '1', $listings_page)); ?>">
				<?php wpl_esc::html_t('Map View'); ?>
			</a>
		<?php endif; ?>
	</div>
</div>