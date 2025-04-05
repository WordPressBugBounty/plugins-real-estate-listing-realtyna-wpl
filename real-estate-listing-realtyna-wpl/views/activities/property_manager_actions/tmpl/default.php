<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import($this->tpl_path . '.scripts.css');

$property_data = $params['property_data']['data'] ?? NULL;
$pid = $property_data['id'] ?? NULL;

$source_blog_id = 1;

if (wpl_global::check_addon("facebook") && get_option('wpl_addon_facebook_init_info') !== false && get_option('wpl_addon_facebook_catalog_id') !== false) {
	$fb_listings = get_option('wpl_facebook_addon_property_list');

	$fb_listings = ($fb_listings === false) ? array() : $fb_listings;


	$is_stored = false;

	foreach ($fb_listings as $fb_data) {
		if ($pid == $fb_data['home_listing_id']) {
			$is_stored = true;
			break;
		}
	}

	$property_data['confirmed_fb'] = ($is_stored === false) ? 1 : 0;

}


if (wpl_global::is_multisite()) $source_blog_id = wpl_property::get_property_field('source_blog_id', $pid);
?>

<div id="pmanager_action_div<?php wpl_esc::attr($pid); ?>" class="p-actions-wp pmanager_actions">
	<?php do_action('wpl_view/activities/property_manager_actions/tmpl/first', $property_data, 'default'); ?>
	<?php if(wpl_users::check_access('change_user') and $source_blog_id == wpl_global::get_current_blog_id()): ?>
		<div id="pmanager_change_user<?php wpl_esc::attr($pid); ?>" class="change-user-cnt-wp">
			<div class="change-user-wp">
				<label id="pmanager_change_user_label<?php wpl_esc::attr($pid); ?>"
					   for="pmanager_change_user_select<?php wpl_esc::attr($pid); ?>">
					<?php wpl_esc::html_t('User'); ?>:
				</label>
				<?php wpl_users::autocomplete($property_data['user_id'] ?? 0, [
					'id' => 'pmanager_change_user_select' . $pid,
					'onchange' => 'change_user(' . $pid . ', this.value);',
				], true); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if(($property_data['source'] !== 'RF') && wpl_users::check_access('confirm', $property_data['user_id'])): ?>
		<div id="pmanager_confirm<?php wpl_esc::attr($pid); ?>" class="p-action-btn"
			 onclick="confirm_property(<?php wpl_esc::attr($pid); ?>);">
			<span><?php wpl_esc::html_t($property_data['confirmed'] == 1 ? 'Publish' : 'Unpublish'); ?></span>
			<i class="<?php wpl_esc::attr($property_data['confirmed'] == 1 ? 'icon-confirm' : 'icon-unconfirm'); ?>"></i>
		</div>
	<?php endif; ?>
	<?php if(($property_data['source'] !== 'RF') && wpl_users::check_access('delete', $property_data['user_id'])): ?>
		<div id="pmanager_trash<?php wpl_esc::attr($pid); ?>" class="p-action-btn"
			 onclick="trash_property(<?php wpl_esc::attr($pid); ?>);">
			<span><?php wpl_esc::html_t($property_data['deleted'] == 1 ? 'Restore' : 'Trash'); ?></span>
			<i class="<?php wpl_esc::attr($property_data['deleted'] == 1 ? 'icon-restore' : 'icon-trash'); ?>"></i>
		</div>
		<div id="pmanager_delete<?php wpl_esc::attr($pid); ?>" class="p-action-btn"
			 onclick="purge_property(<?php wpl_esc::attr($pid); ?>);">
			<span><?php wpl_esc::html_t('Purge'); ?></span>
			<i class="icon-delete"></i>
		</div>
	<?php endif; ?>
	<?php if(($property_data['source'] !== 'RF') && wpl_users::check_access('clone') and wpl_global::check_addon('pro')): ?>
		<div id="pmanager_clone<?php wpl_esc::attr($pid); ?>" class="p-action-btn"
			 onclick="clone_property(<?php wpl_esc::attr($pid); ?>);">
			<span><?php wpl_esc::html_t('Clone'); ?></span>
			<i class="icon-clone"></i>
		</div>
	<?php endif; ?>
	<?php if($property_data['source'] !== 'RF'): ?>
		<a id="pmanager_edit<?php wpl_esc::attr($pid); ?>" class="p-action-btn"
		   href="<?php wpl_esc::url(wpl_property::get_property_edit_link($pid)); ?>">
			<span><?php wpl_esc::html_t('Edit'); ?></span>
			<i class="icon-edit"></i>
		</a>
	<?php endif; ?>
	<?php if (wpl_global::check_addon("facebook") && get_option('wpl_addon_facebook_init_info') !== false && get_option('wpl_addon_facebook_catalog_id') !== false): ?>
		<div id="pmanager_facebook_publish<?php wpl_esc::attr($pid); ?>" class="p-action-btn p-action-facebook-btn"
			 onclick="facebook_publish(<?php wpl_esc::attr($pid); ?>);">
			<label><?php wpl_esc::html_t($property_data['confirmed_fb'] == 1 ? 'Publish on FB' : 'Unpublish From FB'); ?></label>
			<i class="<?php wpl_esc::attr($property_data['confirmed_fb'] == 1 ? 'icon-confirm' : 'icon-unconfirm'); ?>"></i>
		</div>
	<?php endif; ?>
	<?php if (wpl_users::check_access('multi_agents') and wpl_global::check_addon('multi_agents') and $source_blog_id == wpl_global::get_current_blog_id()): ?>
		<?php
		_wpl_import('libraries.addon_multi_agents');

		$multi = new wpl_addon_multi_agents($pid);
		$additional_agents = $multi->get_agents();
		?>
		<div class="pmanager-multi-agent">
			<label id="pmanager_additional_agents_label<?php wpl_esc::attr($pid); ?>"
				   for="pmanager_additional_agents_select<?php wpl_esc::attr($pid); ?>"><?php wpl_esc::html_t('Additional Agents'); ?>
				: </label>
			<?php wpl_users::autocomplete($additional_agents, [
				'id' => 'pmanager_additional_agents_select' . $pid,
				'multiple' => 'multiple',
				'data-chosen-opt' => 'width:100%',
				'onchange' => 'additional_agents(' . $pid . ');',
			], true); ?>
		</div>
	<?php endif; ?>
</div>