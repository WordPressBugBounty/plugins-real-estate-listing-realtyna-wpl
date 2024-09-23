<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-12 side-addons" id="wpl_dashboard_side_addons">
    <div class="panel-wp">
		<h3><?php wpl_esc::html_t('Purchased Add Ons'); ?></h3>
        
        <div class="panel-body">
            <?php if(!wpl_global::check_addon('pro')): ?>
				<p class="pro-message"><?php wpl_esc::html_t('You cannot install any add-ons on WPL basic! Please upgrade to WPL PRO.'); ?></p>
            <?php else: ?>
            <div class="wpl-addons-install-wp wpl_install_addons_container">
                <div class="wpl_realtyna_credentials_container">
						<input type="text" name="realtyna_username" id="realtyna_username"
							   value="<?php if (isset($settings['realtyna_username'])) wpl_esc::attr($settings['realtyna_username']); ?>"
							   placeholder="<?php wpl_esc::attr_t('Billing username'); ?>"
							   autocomplete="off"/>
						<input type="password" name="realtyna_password" id="realtyna_password"
							   value="<?php if (isset($settings['realtyna_password'])) wpl_esc::attr($settings['realtyna_password']); ?>"
							   placeholder="<?php wpl_esc::attr_t('Billing password'); ?>"
							   autocomplete="new-password"/>
						<input class="wpl-button button-1" type="button" onclick="save_realtyna_credentials();"
							   value="<?php wpl_esc::attr_t('Save'); ?>"/>
						<span id="wpl_realtyna_credentials_check"><span
									class="action-btn <?php wpl_esc::attr(((isset($settings['realtyna_verified']) and $settings['realtyna_verified']) ? 'icon-enabled' : 'icon-disabled')); ?>"></span></span>
                    <br/>
						<span class="wpl_realtyna_credentials_tip"><?php wpl_esc::html_t('Your username and password in Realtyna Billing system is necessary for Premium Support and Add-on updates!'); ?></span>
                </div>

                <?php if($settings['realtyna_verified'] == 0): ?>
                    <?php if(!isset($settings['realtyna_envato_purchase'])): ?>
							<div class="wpl_realtyna_envato_container_drop"
								 onclick="dropdown_envato_purchase_form();"><?php wpl_esc::html_t('Did you buy WPL from <i>CodeCanyon</i>? Click Here to get your username and password.'); ?></div>
                        <div class="wpl_realtyna_envato_container">
								<input type="text" name="realtyna_envato_fullname" id="realtyna_envato_fullname"
									   value="<?php wpl_esc::attr(trim($user->user_firstname . ' ' . $user->user_lastname)); ?>"
									   placeholder="<?php wpl_esc::attr_t('Full Name'); ?>"
									   autocomplete="off"/>
								<input type="text" name="realtyna_envato_email" id="realtyna_envato_email"
									   value="<?php wpl_esc::attr($user->user_email); ?>"
									   placeholder="<?php wpl_esc::attr_t('Email'); ?>"
									   autocomplete="off"/>
								<input type="text" name="realtyna_envato_purchase" id="realtyna_envato_purchase"
									   value=""
									   placeholder="<?php wpl_esc::attr_t('Purchase Code'); ?>"
									   autocomplete="off"/>
								<input class="wpl-button button-1" type="button"
									   onclick="check_envato_purchase_code('submit');"
									   value="<?php wpl_esc::attr_t('Save'); ?>"/>
								<span id="wpl_realtyna_envato_check"><span
											class="action-btn <?php wpl_esc::attr((isset($settings['realtyna_verified']) and $settings['realtyna_verified']) ? 'icon-enabled' : 'icon-disabled'); ?>"></span></span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>             
					<label for="wpl_addon_file"><?php wpl_esc::html_t('Install Add On'); ?>
						: </label>
                <?php
					$params = array('html_element_id' => 'wpl_addon_file', 'html_path_message' => '.wpl_addons_message .wpl_show_message', 'html_ajax_loader' => '#wpl_install_addon_ajax_loader', 'request_str' => 'admin.php?wpl_format=b:wpl:ajax&wpl_function=install_package&_wpnonce='.$nonce, 'valid_extensions' => array('zip'));
					wpl_global::import_activity('ajax_file_upload:default', '', $params);
                ?>
                <span id="wpl_install_addon_ajax_loader"></span>
            </div>
            <div class="wpl-addons-wp wpl_addons_container">
					<div class="wpl_addons_message">
						<div class="wpl_show_message"></div>
					</div>
                <?php foreach($addons as $addon):
					$changelog = WPL_ABSPATH.'assets'.DS.'changelogs'.DS.($addon['addon_name'] != 'pro' ? 'addon_'.$addon['addon_name'] : 'real-estate-listing-realtyna-wpl').'.php';
				?>
						<div class="wpl-addon-row wpl_addon_container"
							 id="wpl_addon_container<?php wpl_esc::attr($addon['id']); ?>">
							<label class="wpl_addon_name"><?php wpl_esc::attr($addon['name']); ?><?php if (wpl_file::exists($changelog)): ?>
									<a href="#" class="wpl-changelog-link">
									(<?php wpl_esc::html_t('ChangeLog'); ?>
									)</a><?php endif; ?></label>
                    <span class="wpl_addon_info">
                        <?php if(trim($addon['message'] ?? '') != ''): ?>
                        <span class="wpl_addon_message"><?php wpl_esc::attr($addon['message']); ?></span>
                        <?php endif; ?>
                        <span title="<?php wpl_esc::attr_t('Version'); ?>"><?php wpl_esc::html($addon['version']); ?></span>
                        <?php if($addon['updatable']): ?>
							<span class="action-btn icon-recycle-2"
								  onclick="check_addon_update(<?php wpl_esc::numeric($addon['id']); ?>)"
								  title="<?php wpl_esc::attr_t('Update'); ?>"></span>
                        <?php endif; ?>
                    </span>
                    
                    <?php if(wpl_file::exists($changelog)): ?>
                    <div class="wpl-addon-changelog wpl-scrollbar">
                        <?php wpl_esc::e(wpl_file::read($changelog)); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>