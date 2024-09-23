<?php
defined('_WPLEXEC') or die('Restricted access');

if(wpl_global::check_addon('membership')) $this->membership = new wpl_addon_membership();
?>
<form action="<?php wpl_esc::url(wpl_property::get_property_listing_link()); ?>" id="wpl_search_form_<?php wpl_esc::e($widget_id); ?>" method="GET" onsubmit="return wpl_do_search_<?php wpl_esc::e($widget_id); ?>();" class="wpl_search_from_box simple clearfix wpl_search_kind<?php wpl_esc::attr($this->kind); ?> <?php wpl_esc::attr($this->css_class); ?>">
    <!-- Do not change the ID -->
    <div id="wpl_searchwidget_<?php wpl_esc::e($widget_id); ?>" class="clearfix">
	    <div class="wpl_search_from">
	    	<?php foreach($this->rendered as $data): ?>
				<div class="wpl_search_fields <?php wpl_esc::attr($data['field_data']['type']); ?>">
					<?php wpl_esc::e($data['html']); ?>
				</div>
			<?php endforeach; ?>
			<?php if($this->show_reset_button): ?>
				<div class="wpl_search_reset" onclick="wpl_do_reset<?php wpl_esc::e($widget_id); ?>([], <?php wpl_esc::e($this->ajax == 2 ? 'true' : 'false'); ?>);" id="wpl_search_reset<?php wpl_esc::e($widget_id); ?>">
					<?php wpl_esc::html_t('Reset'); ?>
				</div>
	    	<?php endif; ?>
	    	<div class="search_submit_box">
		    	<input id="wpl_search_widget_submit<?php wpl_esc::e($widget_id); ?>"
					   class="wpl_search_widget_submit"
					   data-widget-id="<?php wpl_esc::e($widget_id); ?>"
					   data-ajax="<?php wpl_esc::e($this->ajax); ?>"
					   data-search-page="<?php wpl_esc::e($this->get_target_page((isset($this->target_id) ? $this->target_id : NULL))); ?>"
					   data-kind="<?php wpl_esc::attr($this->kind); ?>"
					   data-nounce="<?php wpl_esc::attr(wp_create_nonce('realtynaElAjaxCheckHeaderSearch')); ?>"
					   type="submit"
					   value="<?php wpl_esc::attr_t('Search'); ?>"
				/>
                <?php if($this->show_total_results == 1): ?>
					<span id="wpl_total_results<?php wpl_esc::e($widget_id); ?>" class="wpl-total-results">
						(<span></span>)
					</span>
				<?php endif; ?>
		    </div>
            <?php if($this->show_total_results == 2): ?>
				<span id="wpl_total_results<?php wpl_esc::e($widget_id); ?>" class="wpl-total-results-after">
					<span></span> <?php wpl_esc::html_t('listings'); ?>
				</span>
			<?php endif; ?>
            <?php if(wpl_global::check_addon('membership') and ($this->kind == 0 or $this->kind == 1)): ?>
				<div class="wpl_dashboard_links_container">
					<?php if(wpl_global::check_addon('save_searches') and ($this->show_saved_searches)): ?>
                    <a class="wpl-addon-save-searches-link" href="<?php wpl_esc::url($this->membership->URL('searches')); ?>">
						<?php wpl_esc::html_t('Saved Searches'); ?>
                        <span id="wpl-addon-save-searches-count<?php wpl_esc::e($widget_id); ?>">
							<?php wpl_esc::html_t($this->saved_searches_count); ?>
						</span>
                    </a>
					<?php endif; ?>
					<?php if($this->show_favorites): ?>
						<a class="wpl-widget-favorites-link" href="<?php wpl_esc::url($this->membership->URL('favorites')); ?>">
							<?php wpl_esc::html_t('Favorites'); ?>
							<span id="wpl-widget-favorites-count<?php wpl_esc::e($widget_id); ?>">
								<?php wpl_esc::html_t($this->favorites_count); ?>
							</span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
	    </div>
	</div>
</form>
<?php
/** import js codes **/
$this->_wpl_import('widgets.search.scripts.js', true, true);