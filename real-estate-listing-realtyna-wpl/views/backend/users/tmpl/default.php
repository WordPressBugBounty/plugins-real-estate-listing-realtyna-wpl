<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

// Get Franchise addon assets
$current_blog_id = wpl_global::get_current_blog_id();
$super_admin = wpl_users::is_super_admin();

// If the WPL users is shared on network then don't let normal admins to manage them
$fs_can_manage_users = true;
if (wpl_global::check_addon('franchise') and !$super_admin) {
	$fs = new wpl_addon_franchise();
	if ($fs->is_network_shared('wpl_users')) $fs_can_manage_users = false;
}

$brokers = array();
if (wpl_global::check_addon('brokerage')) {
	_wpl_import('libraries.addon_brokerage');
	$brokers = method_exists('wpl_addon_brokerage', 'brokers') ? wpl_addon_brokerage::brokers() : [];
}

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');

// Include RETS addon JS File
if (wpl_global::check_addon('rets')) $this->_wpl_import($this->tpl_path . '.scripts.addon_rets');
?>
<div class="wrap wpl-wp user-wp">
	<header>
		<div id="icon-user" class="icon48"></div>
		<h2><?php wpl_esc::html_t('User Manager'); ?></h2>
		<a href="<?php wpl_esc::url(wpl_global::add_qs_var('kind', wpl_flex::get_kind_id('user'), wpl_global::get_wpl_admin_menu('wpl_admin_flex'))); ?>"
		   class="setting-toolbar-btn button"
		   title="<?php wpl_esc::attr_t('Manage User Data Structure'); ?>"><?php wpl_esc::html_t('Manage User Data Structure'); ?></a>
	</header>

	<?php if (wpl_global::is_multisite()): $fs = new wpl_addon_franchise();
		if ($fs->is_agents_limit_reached()): ?>
			<div class="wpl_gold_msg"><?php wpl_esc::html_t("Your website limit for adding new agents reached so you cannot add new agents to your website!"); ?></div>
		<?php endif; endif; ?>
	<div class="wpl_user_list">
		<div class="wpl_show_message"></div>
	</div>

	<div class="wpl-users-search-form">
		<form method="GET" id="wpl_users_search_form">
			<input type="hidden" name="page" value="wpl_admin_user_manager"/>
			<label for="sf_filter"><?php wpl_esc::html_t('Filter'); ?>: </label>
			<input type="text" id="sf_filter" name="filter" value="<?php wpl_esc::attr($this->filter); ?>"
				   placeholder="<?php wpl_esc::attr_t('Name, Email'); ?>"
				   class="long"/>
			<select name="show_all" id="show_all" data-has-chosen=""
					title="<?php wpl_esc::attr_t('WPL / WordPress Users'); ?>">
				<option value="0" <?php if ($this->show_all == 0) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('Only WPL users'); ?></option>
				<option value="1" <?php if ($this->show_all == 1) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t('All WordPress users'); ?></option>
			</select>
			<?php if (wpl_global::check_addon('membership')): ?>
				<select name="membership_id" id="membership_id" data-has-chosen=""
						title="<?php wpl_esc::attr_t('Membership'); ?>">
					<option value=""><?php wpl_esc::html_t('Membership'); ?></option>
					<?php foreach ($this->memberships as $membership): ?>
						<option value="<?php wpl_esc::attr($membership->id); ?>" <?php if (isset($this->membership_id) and $membership->id == $this->membership_id) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($membership->membership_name); ?></option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
			<?php if (wpl_global::check_addon('brokerage')): ?>
				<select name="parent" id="parent" data-has-chosen=""
						title="<?php wpl_esc::attr_t('Brokerage'); ?>">
					<option value=""><?php wpl_esc::html_t('Brokerage'); ?></option>
					<?php foreach ($brokers as $broker): ?>
						<option value="<?php wpl_esc::attr($broker->id); ?>" <?php if ($broker->id == $this->parent) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html( trim($broker->first_name . ' ' . $broker->last_name) . ' (' . $broker->user_email . ')'); ?></option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
			<button class="wpl-button button-1"><?php wpl_esc::html_t('Search'); ?></button>
			<button type="reset" class="button button-1"
					onclick="wpl_reset_users_form();"><?php wpl_esc::html_t('Reset'); ?></button>
		</form>
	</div>
	<div class="sidebar-wp">
		<?php if (isset($this->pagination->max_page) and $this->pagination->max_page > 1): ?>
			<div class="pagination-wp">
				<?php wpl_esc::e($this->pagination->show()); ?>
			</div>
		<?php endif; ?>
		<table class="widefat page">
			<thead>
			<tr>
				<th scope="col"
					class="manage-column"><?php wpl_global::order_table(wpl_esc::return_html_t('ID'), 'u.id'); ?></th>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Username'); ?></th>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Name'); ?></th>
				<?php if (wpl_global::check_addon('membership')): ?>
					<th scope="col"
						class="manage-column"><?php wpl_esc::html_t('Membership'); ?></th><?php endif; ?>
				<?php if (wpl_global::check_addon('brokerage')): ?>
					<th scope="col"
						class="manage-column"><?php wpl_esc::html_t('Brokerage'); ?></th><?php endif; ?>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Email'); ?></th>
				<th scope="col"
					class="manage-column"><?php wpl_global::order_table(wpl_esc::return_html_t('Registered'), 'u.user_registered'); ?></th>
				<?php if (wpl_global::check_addon('membership')): ?>
					<th scope="col"
						class="manage-column"><?php wpl_global::order_table(wpl_esc::return_html_t('Expiry Date'), 'wpl.expiry_date'); ?></th><?php endif; ?>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Actions'); ?></th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th scope="col"
					class="manage-column"><?php wpl_global::order_table(wpl_esc::return_html_t('ID'), 'u.id'); ?></th>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Username'); ?></th>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Name'); ?></th>
				<?php if (wpl_global::check_addon('membership')): ?>
					<th scope="col"
						class="manage-column"><?php wpl_esc::html_t('Membership'); ?></th><?php endif; ?>
				<?php if (wpl_global::check_addon('brokerage')): ?>
					<th scope="col"
						class="manage-column"><?php wpl_esc::html_t('Brokerage'); ?></th><?php endif; ?>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Email'); ?></th>
				<th scope="col"
					class="manage-column"><?php wpl_global::order_table(wpl_esc::return_html_t('Registered'), 'u.user_registered'); ?></th>
				<?php if (wpl_global::check_addon('membership')): ?>
					<th scope="col"
						class="manage-column"><?php wpl_global::order_table(wpl_esc::return_html_t('Expiry Date'), 'wpl.expiry_date'); ?></th><?php endif; ?>
				<th scope="col"
					class="manage-column"><?php wpl_esc::html_t('Actions'); ?></th>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->wp_users as $wp_user): $wpl_data = wpl_users::get_wpl_data($wp_user->ID); ?>
				<tr id="item_row<?php wpl_esc::attr($wp_user->ID); ?>">
					<td class="size-1"><?php wpl_esc::attr($wp_user->ID); ?></td>
					<td>
						<?php if ($wp_user->id): ?>
							<div class="wpl-username"><a
										href="<?php wpl_esc::url(wpl_global::add_qs_var('id', $wp_user->ID, wpl_global::get_wpl_admin_menu('wpl_admin_profile'))); ?>"><?php wpl_esc::html($wp_user->user_login); ?></a>
							</div>
							<div class="wpl-edit-profile"><a
										href="<?php wpl_esc::url(wpl_global::add_qs_var('id', $wp_user->ID, wpl_global::get_wpl_admin_menu('wpl_admin_profile'))); ?>"><?php wpl_esc::html_t('Edit Profile'); ?></a>
							</div>
						<?php else: ?>
							<?php wpl_esc::html($wp_user->user_login); ?>
						<?php endif; ?>
					</td>
					<td><?php wpl_esc::html(is_object($wpl_data) ? $wpl_data->first_name . ' ' . $wpl_data->last_name : ''); ?></td>
					<?php if (wpl_global::check_addon('membership')): ?>
						<td>
							<?php if (!$fs_can_manage_users): ?>
								<span><?php wpl_esc::html_t('No Permission!'); ?></span>
							<?php elseif ($wp_user->id): ?>
								<select data-without-chosen name="membership_id_<?php wpl_esc::attr($wp_user->ID); ?>"
										id="membership_id_<?php wpl_esc::attr($wp_user->ID); ?>"
										onChange="wpl_change_membership(<?php wpl_esc::attr($wp_user->ID); ?>);"
										autocomplete="off"
										title="<?php wpl_esc::attr_t('Membership') ?>">
									<option value=""><?php wpl_esc::html_t('None'); ?></option>
									<?php foreach ($this->memberships as $membership): ?>
										<option value="<?php wpl_esc::attr($membership->id); ?>" <?php if (is_object($wpl_data) and $membership->id == $wpl_data->membership_id) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($membership->membership_name); ?></option>
									<?php endforeach; ?>
								</select>
								<span id="wpl_ajax_loader_membership_<?php wpl_esc::attr($wp_user->ID); ?>"></span>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<?php if (wpl_global::check_addon('brokerage')): ?>
						<td>
							<?php if (!$fs_can_manage_users): ?>
								<span><?php wpl_esc::html_t('No Permission!'); ?></span>
							<?php elseif ($wp_user->id and !wpl_users::is_broker($wp_user->id)): ?>
								<select data-without-chosen name="parent_<?php wpl_esc::attr($wp_user->ID); ?>"
										id="parent_<?php wpl_esc::attr($wp_user->ID); ?>"
										onChange="wpl_change_brokerage(<?php wpl_esc::attr($wp_user->ID); ?>);" autocomplete="off"
										title="<?php wpl_esc::attr_t('Brokerage') ?>">
									<option value=""><?php wpl_esc::html_t('None'); ?></option>
									<?php foreach ($brokers as $broker): ?>
										<option value="<?php wpl_esc::attr($broker->id); ?>" <?php if (is_object($wpl_data) and $broker->id == $wpl_data->parent) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html($broker->user_login); ?></option>
									<?php endforeach; ?>
								</select>
								<span id="wpl_ajax_loader_parent_<?php wpl_esc::attr($wp_user->ID); ?>"></span>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<td><?php wpl_esc::html($wp_user->user_email); ?></td>
					<td><?php wpl_esc::html(date('Y-m-d', strtotime($wp_user->user_registered))); ?></td>
					<?php if (wpl_global::check_addon('membership')): ?>
						<td>
                        <span id="wpl_user_expiry_date<?php wpl_esc::attr($wp_user->ID); ?>">
                        <?php
						if (!trim($wp_user->expiry_date ?? '') or $wp_user->expiry_date == '-1' or $wp_user->expiry_date == '0000-00-00 00:00:00'): wpl_esc::html_t('Unlimited') . '</span>';
						else:
						wpl_esc::html(date('Y-m-d', strtotime($wp_user->expiry_date)));
						?>
                        </span>
							<span id="wpl_user_renew<?php wpl_esc::attr($wp_user->ID); ?>" class="action-btn wpl-gen-icon-refresh"
								  onclick="wpl_renew_user(<?php wpl_esc::attr($wp_user->ID); ?>);"
								  title="<?php wpl_esc::attr_t('Renew'); ?>"></span>
							<?php endif; ?>
							<?php if (!$wp_user->expired): ?>
								<span id="wpl_user_expire<?php wpl_esc::attr($wp_user->ID); ?>" class="action-btn icon-disabled"
									  onclick="wpl_expire_user(<?php wpl_esc::attr($wp_user->ID); ?>);"
									  title="<?php wpl_esc::attr_t('Expire User Now'); ?>"></span>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<td class="wpl_manager_td">
						<?php if (!$fs_can_manage_users): ?>
							<span><?php wpl_esc::html_t('No Permission!'); ?></span>
						<?php else: ?>
							<span data-realtyna-lightbox data-realtyna-href="#wpl_user_edit_div"
								  id="wpl_edit_btn_<?php wpl_esc::attr($wp_user->ID); ?>"
								  class="action-btn icon-edit wpl_show wpl_user_edit_div"
								  onclick="wpl_edit_user(<?php wpl_esc::attr($wp_user->ID); ?>);"
								  style="<?php if (!$wp_user->id) wpl_esc::e('display: none;'); ?>"></span>
							<span class="<?php if ($wp_user->id) wpl_esc::e('wpl_hidden_element'); ?>"
								  id="no_added_to_wpl<?php wpl_esc::attr($wp_user->ID); ?>">
                            <span class="action-btn icon-plus" onclick="add_to_wpl(<?php wpl_esc::attr($wp_user->ID); ?>);"
								  title="<?php wpl_esc::attr_t('Add user to WPL'); ?>"></span>
                        </span>
							<span class="<?php if (!$wp_user->id) wpl_esc::e('wpl_hidden_element'); ?>  wpl_actions_icon_disable"
								  id="added_to_wpl<?php wpl_esc::attr($wp_user->ID); ?>">                	
                            <span class="action-btn icon-recycle"
								  onclick="wpl_remove_user(<?php wpl_esc::attr($wp_user->ID); ?>, 0);"
								  title="<?php wpl_esc::attr_t('Remove user from WPL'); ?>"></span>
                        </span>
							<span class="ajax-inline-save" id="wpl_ajax_loader_<?php wpl_esc::attr($wp_user->ID); ?>"></span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<div id="wpl_user_edit_div" class="wpl_hidden_element"></div>
	</div>
	<footer>
		<div class="logo"></div>
	</footer>
</div>