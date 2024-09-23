<?php
defined('_WPLEXEC') or die('Restricted access');

$registericon = $this->instance['registericon'] ?? 'wpl-font-user';
$loginicon = $this->instance['loginicon'] ?? 'wpl-font-login';
$forgeticon = $this->instance['forgeticon'] ?? 'wpl-font-forget-password';
$dashboardicon = $this->instance['dashboardicon'] ?? 'wpl-font-dashboard';
$logouticon = $this->instance['logouticon'] ?? 'wpl-font-logout';
$compareicon = $this->instance['compareicon'] ?? 'wpl-font-compare2';
$savesearchicon = $this->instance['savesearchicon'] ?? 'wpl-font-save-search';
$favoriteicon = $this->instance['favoriteicon'] ?? 'wpl-font-favorite';
?>
<div class="wpl_links_widget_container <?php wpl_esc::attr($this->css_class); ?>">
	<div class="wpl-login-box">
		<ul>
			<?php if (!is_user_logged_in()): ?>
				<?php if ($this->login_link): ?>
					<li>
						<a href="<?php wpl_esc::url(wp_login_url()); ?>">
							<i class="<?php wpl_esc::attr($loginicon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Login to Account'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if ($this->forget_password_link): ?>
					<li>
						<a href="<?php wpl_esc::url(wp_lostpassword_url()); ?>">
							<i class="<?php wpl_esc::attr($forgeticon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Forgot Password'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (get_option('users_can_register') && $this->register_link): ?>
					<li>
						<a href="<?php wpl_esc::url(wp_registration_url()); ?>">
							<i class="<?php wpl_esc::attr($registericon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Register'); ?>
						</a>
					</li>
				<?php endif; ?>
			<?php else: ?>
				<?php if ($this->login_link): ?>
					<li>
						<a href="<?php wpl_esc::url(wp_logout_url()); ?>">
							<i class="<?php wpl_esc::attr($logouticon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Logout'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (wpl_global::check_addon('membership') and $this->dashboard_link): ?>
					<?php $membership = new wpl_addon_membership(); ?>
					<li>
						<a href="<?php wpl_esc::url($membership->URL('dashboard')); ?>">
							<i class="<?php wpl_esc::attr($dashboardicon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Dashboard'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (wpl_global::check_addon('save_searches') and $this->save_search_link): ?>
					<li>
						<a href="<?php wpl_esc::url($this->save_search_url); ?>">
							<i class="<?php wpl_esc::attr($savesearchicon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Saved Searches'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (wpl_global::check_addon('pro') and $this->compare_link): ?>
					<li>
						<a href="<?php wpl_esc::url($this->compare_url); ?>">
							<i class="<?php wpl_esc::attr($compareicon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Compare'); ?>
						</a>
					</li>
				<?php endif; ?>
				<?php if (wpl_global::check_addon('pro') and $this->favorite_link): ?>
					<li>
						<a href="<?php wpl_esc::url($this->favorite_url); ?>">
							<i class="<?php wpl_esc::attr($favoriteicon); ?>" aria-hidden="true"></i>
							<?php wpl_esc::html_t('Favorites'); ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
	</div>
</div>