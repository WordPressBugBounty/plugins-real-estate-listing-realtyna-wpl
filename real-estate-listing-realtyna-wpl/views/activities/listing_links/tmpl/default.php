<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = $params['wpl_properties'] ?? array();
$property_id = $wpl_properties['current']['data']['id'] ?? NULL;
$property_link = urlencode($wpl_properties['current']['property_link']);

$show_facebook = (isset($params['facebook']) and $params['facebook']) ? 1 : 0;
$show_twitter = (isset($params['twitter']) and $params['twitter']) ? 1 : 0;
$show_pinterest = (isset($params['pinterest']) and $params['pinterest']) ? 1 : 0;
$show_linkedin = (isset($params['linkedin']) and $params['linkedin']) ? 1 : 0;
$show_favorite = (isset($params['favorite']) and $params['favorite']) ? 1 : 0;
$show_pdf = (isset($params['pdf']) and $params['pdf']) ? 1 : 0;
$show_abuse = (isset($params['report_abuse']) and $params['report_abuse']) ? 1 : 0;
$show_crm = (isset($params['crm']) and $params['crm']) ? 1 : 0;
$show_request_a_visit = (isset($params['request_a_visit']) and $params['request_a_visit']) ? 1 : 0;
$show_send_to_friend = (isset($params['send_to_friend']) and $params['send_to_friend']) ? 1 : 0;
$watch_changes = (isset($params['watch_changes']) and $params['watch_changes']) ? 1 : 0;

$this->lightbox_container = '#wpl_plisting_lightbox_content_container';
$this->_wpl_import($this->tpl_path . '.scripts.js', true, true, true);
?>
<div class="wpl_listing_links_container" id="wpl_listing_links_container<?php wpl_esc::attr($property_id); ?>">
	<ul>
		<?php if ($show_facebook): ?>
			<li class="facebook_link">
				<a class="wpl-tooltip-top"
				   href="https://www.facebook.com/sharer/sharer.php?u=<?php wpl_esc::attr($property_link); ?>"
				   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;"
				   title="<?php wpl_esc::attr_t('Share on Facebook'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Share on Facebook'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Share on Facebook'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_twitter): ?>
			<li class="twitter_link">
				<a class="wpl-tooltip-top" href="https://twitter.com/share?url=<?php wpl_esc::attr($property_link); ?>"
				   target="_blank" title="<?php wpl_esc::attr_t('Tweet'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Tweet'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Share on Twitter'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_pinterest): ?>
			<li class="pinterest_link">
				<a class="wpl-tooltip-top"
				   href="https://pinterest.com/pin/create/link/?url=<?php wpl_esc::attr($property_link); ?>&media=<?php wpl_esc::url(wpl_property::get_property_image($property_id, '300*300')); ?>&description=<?php wpl_esc::attr($wpl_properties['current']['property_title'] ?? ''); ?>"
				   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;"
				   title="<?php wpl_esc::attr_t('Pin it'); ?>" aria-label="<?php wpl_esc::attr_t('Pin it'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Share on Pinterest'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_linkedin): ?>
			<li class="linkedin_link">
				<a class="wpl-tooltip-top"
				   href="https://www.linkedin.com/shareArticle?mini=true&url=<?php wpl_esc::attr($property_link); ?>"
				   onclick="window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=600'); return false;"
				   title="<?php wpl_esc::attr_t('Share on Linkedin'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Share on Linkedin'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Share on Linkedin'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_pdf): ?>
			<li class="pdf_link">
				<a class="wpl-tooltip-top"
				   href="<?php wpl_esc::url(wpl_property::get_property_pdf_link($property_id)); ?>" target="_blank"
				   title="<?php wpl_esc::attr_t('PDF'); ?>" aria-label="<?php wpl_esc::attr_t('PDF'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('PDF'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_favorite): $find_favorite_item = in_array($property_id, wpl_addon_pro::favorite_get_pids()); ?>
			<li class="favorite_link<?php wpl_esc::e($find_favorite_item ? ' added' : '') ?>">
				<a class="wpl-tooltip-top" href="#"
				   style="<?php wpl_esc::e($find_favorite_item ? 'display: none;' : '') ?>"
				   id="wpl_favorite_add_<?php wpl_esc::attr($this->activity_id); ?>_<?php wpl_esc::attr($property_id); ?>"
				   onclick="return wpl_favorite_control<?php wpl_esc::attr($this->activity_id); ?>(<?php wpl_esc::attr($property_id); ?>, 1);"
				   title="<?php wpl_esc::attr_t('Add to favorites'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Add to favorites'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Add to favorites'); ?></div>
				<a class="wpl-tooltip-top" href="#"
				   style="<?php wpl_esc::e(!$find_favorite_item ? 'display: none;' : '') ?>"
				   id="wpl_favorite_remove_<?php wpl_esc::attr($this->activity_id); ?>_<?php wpl_esc::attr($property_id); ?>"
				   onclick="return wpl_favorite_control<?php wpl_esc::attr($this->activity_id); ?>(<?php wpl_esc::attr($property_id); ?>, 0);"
				   title="<?php wpl_esc::attr_t('Remove from favorites'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Remove from favorites'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Remove from favorites'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_abuse): ?>
			<li class="report_abuse_link">
				<a class="wpl-tooltip-top" data-realtyna-lightbox
				   data-realtyna-lightbox-opts="title:<?php wpl_esc::attr_t('Report Listing'); ?>"
				   href="<?php wpl_esc::attr($this->lightbox_container); ?>"
				   onclick="return wpl_report_abuse_get_form(<?php wpl_esc::attr($property_id); ?>);"
				   title="<?php wpl_esc::attr_t('Report Listing'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Report Listing'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Report Listing'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_send_to_friend): ?>
			<li class="send_to_friend_link">
				<a class="wpl-tooltip-top" data-realtyna-lightbox
				   data-realtyna-lightbox-opts="title:<?php wpl_esc::attr_t('Send to Friend'); ?>"
				   href="<?php wpl_esc::attr($this->lightbox_container); ?>"
				   onclick="return wpl_send_to_friend_get_form(<?php wpl_esc::attr($property_id); ?>);"
				   title="<?php wpl_esc::attr_t('Send to Friend'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Send to Friend'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Send to Friend'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_request_a_visit): ?>
			<li class="request_a_visit_link">
				<a class="wpl-tooltip-top" data-realtyna-lightbox
				   data-realtyna-lightbox-opts="title:<?php wpl_esc::attr_t('Request a Visit'); ?>"
				   href="<?php wpl_esc::attr($this->lightbox_container); ?>"
				   onclick="return wpl_request_a_visit_get_form(<?php wpl_esc::attr($property_id); ?>);"
				   title="<?php wpl_esc::attr_t('Request a Visit'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Request a Visit'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Request a Visit'); ?></div>
			</li>
		<?php endif; ?>

		<?php if (wpl_global::check_addon('save_searches') and $watch_changes): ?>
			<li class="watch_changes_link">
				<a class="wpl-tooltip-top" data-realtyna-lightbox
				   data-realtyna-lightbox-opts="title:<?php wpl_esc::attr_t('Watch changes on this property'); ?>"
				   href="<?php wpl_esc::attr($this->lightbox_container); ?>"
				   onclick="return wpl_watch_changes_get_form(<?php wpl_esc::attr($property_id); ?>);"
				   title="<?php wpl_esc::attr_t('Watch changes on this property'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Watch changes on this property'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Watch changes on this property'); ?></div>
			</li>
		<?php endif; ?>

		<?php if ($show_crm): _wpl_import('libraries.addon_crm');
			$crm = new wpl_addon_crm(); ?>
			<li class="crm_link">
				<a class="wpl-tooltip-top"
				   href="<?php wpl_esc::url($crm->URL('form')); ?>&pid=<?php wpl_esc::attr($property_id); ?>" target="_blank"
				   title="<?php wpl_esc::attr_t('Send Request for a new Property'); ?>"
				   aria-label="<?php wpl_esc::attr_t('Send Request for a new Property'); ?>"></a>
				<div class="wpl-util-hidden"><?php wpl_esc::html_t('Send Request for a new Property'); ?></div>
			</li>
		<?php endif; ?>
	</ul>
</div>