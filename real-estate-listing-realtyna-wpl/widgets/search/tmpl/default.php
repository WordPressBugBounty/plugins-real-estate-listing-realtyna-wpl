<?php
defined('_WPLEXEC') or die('Restricted access');

if (wpl_global::check_addon('membership')) $this->membership = new wpl_addon_membership();
?>
	<div id="wpl_default_search_<?php wpl_esc::e($widget_id); ?>">
		<form action="<?php wpl_esc::url(wpl_property::get_property_listing_link()); ?>"
			  id="wpl_search_form_<?php wpl_esc::e($widget_id); ?>" method="GET"
			  onsubmit="return wpl_do_search_<?php wpl_esc::e($widget_id); ?>('wpl_searchwidget_<?php wpl_esc::e($widget_id); ?>');"
			  class="wpl_search_from_box clearfix wpl_search_kind<?php wpl_esc::attr($this->kind); ?> <?php wpl_esc::attr($this->style); ?> <?php wpl_esc::attr($this->css_class); ?>">

			<!-- Do not change the ID -->
			<div id="wpl_searchwidget_<?php wpl_esc::e($widget_id); ?>" class="clearfix">
				<?php
				$top_div = '';
				$bott_div = '';
				$bott_div_open = false;

				$is_separator = false;
				$top_array = array();

				$counter = 1;
				foreach ($this->rendered as $data) {
					if (($data['field_data']['type'] == 'separator') && $counter > 1) {
						$is_separator = true;
						break;
					}

					$counter++;
				}

				if (!$is_separator) $top_array = array(41, 3, 6, 8, 9, 2);

				$counter = 1;
				foreach ($this->rendered as $data) {
					if ($is_separator or (!$is_separator and in_array($data['id'], $top_array))) $top_div .= $data['html'];
					else {
						if (is_string($data['current_value']) and trim($data['current_value']) and $data['current_value'] != '-1') $bott_div_open = true;
						$bott_div .= $data['html'];
					}

					if ($data['field_data']['type'] == 'separator' and $counter > 1) $is_separator = false;
					$counter++;
				}
				?>
				<div class="wpl_search_from_box_top">
					<?php wpl_esc::e($top_div); ?>
					<?php if ($this->show_reset_button): ?>
						<div class="wpl_search_reset"
							 onclick="wpl_do_reset<?php wpl_esc::e($widget_id); ?>([], <?php wpl_esc::e($this->ajax == 2 ? 'true' : 'false'); ?>);"
							 id="wpl_search_reset<?php wpl_esc::e($widget_id); ?>">
							<?php wpl_esc::html_t('Reset'); ?>
						</div>
					<?php endif; ?>
					<div class="search_submit_box">
						<input id="wpl_search_widget_submit<?php wpl_esc::e($widget_id); ?>"
							   class="wpl_search_widget_submit"
							   data-widget-id="<?php wpl_esc::e($widget_id); ?>"
							   data-ajax="<?php wpl_esc::attr($this->ajax); ?>"
							   data-search-page="<?php wpl_esc::attr($this->get_target_page(($this->target_id ?? NULL))); ?>"
							   data-kind="<?php wpl_esc::attr($this->kind); ?>"
							   data-nounce="<?php wpl_esc::attr(wp_create_nonce('realtynaElAjaxCheckHeaderSearch')); ?>"
							   type="submit"
							   value="<?php wpl_esc::attr_t('Search'); ?>"
						/>
						<?php if ($this->show_total_results == 1): ?><span
							id="wpl_total_results<?php wpl_esc::e($widget_id); ?>" class="wpl-total-results">
								(<span></span>)</span><?php endif; ?>
					</div>
					<?php if ($this->show_total_results == 2): ?>
						<span id="wpl_total_results<?php wpl_esc::e($widget_id); ?>" class="wpl-total-results-after">
						<span></span> <?php wpl_esc::html_t('listings'); ?>
					</span>
					<?php endif; ?>
					<?php if (wpl_global::check_addon('membership') and ($this->kind == 0 or $this->kind == 1)): ?>
						<div class="wpl_dashboard_links_container">
							<?php if (wpl_global::check_addon('save_searches') and ($this->show_saved_searches)) : ?>
								<a class="wpl-addon-save-searches-link"
								   href="<?php wpl_esc::url($this->membership->URL('searches')); ?>">
									<?php wpl_esc::html_t('Saved Searches'); ?>
									<span id="wpl-addon-save-searches-count<?php wpl_esc::e($widget_id); ?>">
									<?php wpl_esc::html($this->saved_searches_count); ?>
								</span>
								</a>
							<?php endif; ?>
							<?php if ($this->show_favorites): ?>
								<a class="wpl-widget-favorites-link"
								   href="<?php wpl_esc::url($this->membership->URL('favorites')); ?>">
									<?php wpl_esc::html_t('Favorites'); ?>
									<span id="wpl-widget-favorites-count<?php wpl_esc::e($widget_id); ?>">
									<?php wpl_esc::html($this->favorites_count); ?>
								</span>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="wpl_search_from_box_bot" id="wpl_search_from_box_bot<?php wpl_esc::e($widget_id); ?>">
					<?php wpl_esc::e($bott_div); ?>
				</div>
			</div>
			<?php if ($bott_div): ?>
				<div class="more_search_option" data-widget-id="<?php wpl_esc::e($widget_id); ?>"
					 id="more_search_option<?php wpl_esc::e($widget_id); ?>">
					<?php wpl_esc::html_t('More options'); ?>
				</div>
			<?php endif; ?>
		</form>
	</div>

<?php if ($this->more_options_type): ?>
	<!-- Advanced Search -->
	<div id="wpl_advanced_search<?php wpl_esc::e($widget_id); ?>" class="wpl-advanced-search-wp wpl-util-hidden">
		<div class="container">
			<div id="wpl_form_override_search<?php wpl_esc::e($widget_id); ?>" class="wpl-advanced-search-popup"></div>
		</div>
	</div>
<?php endif;
// Import JS Codes
$this->_wpl_import('widgets.search.scripts.js', true, true);