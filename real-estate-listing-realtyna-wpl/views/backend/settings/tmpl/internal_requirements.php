<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="wpl-requirements-container">
	<ul>
        <!-- Headers -->
        <li class="header">
            <span class="wpl-requirement-name"></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Requirement'); ?></span>
            <span class="wpl-requirement-current"><?php wpl_esc::html_t('Current'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<?php wpl_esc::html_t('Status'); ?>
            </span>
        </li>
        <!-- Web Server -->
        <?php
            $webserver_name = strtolower(wpl_request::getVar('SERVER_SOFTWARE', 'UNKNOWN', 'SERVER'));
            $webserver = (strpos($webserver_name, 'apache') !== false or strpos($webserver_name, 'nginx') !== false or strpos($webserver_name, 'speed') !== false) ? true : false;
        ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('Web server'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Standard'); ?></span>
            <span class="wpl-requirement-current"><?php $webserver ? wpl_esc::html_t('Yes') : wpl_esc::html_t('No'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($webserver ? 'confirm' : 'warning'); ?>"></i>
            </span>
		</li>
    	<!-- PHP version -->
        <?php $php_version = wpl_global::php_version(); ?>
    	<li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('PHP version'); ?></span>
            <span class="wpl-requirement-require">7.4.0</span>
            <span class="wpl-requirement-current"><?php wpl_esc::html($php_version); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr(!version_compare($php_version, '7.4.0', '>=') ? 'danger' : 'confirm'); ?>"></i>
            </span>
		</li>
        <!-- WP version -->
        <?php $wp_version = wpl_global::wp_version(); ?>
    	<li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('WP version'); ?></span>
            <span class="wpl-requirement-require">4.0.0</span>
            <span class="wpl-requirement-current"><?php wpl_esc::html($wp_version); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr(!version_compare($wp_version, '4.0.0', '>=') ? 'danger' : 'confirm'); ?>"></i>
            </span>
		</li>
        <!-- WP debug -->
        <?php $wp_debug = WP_DEBUG ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('WP debug'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Off'); ?></span>
            <span class="wpl-requirement-current"><?php $wp_debug ? wpl_esc::html_t('On') : wpl_esc::html_t('Off'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($wp_debug ? 'warning' : 'confirm'); ?>"></i>
            </span>
		</li>
        <!-- Upload directory permission -->
        <?php $wpl_writable = substr(sprintf('%o', fileperms(wpl_global::get_upload_base_path())), -4) >= '0755' ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('Upload dir'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Writable'); ?></span>
            <span class="wpl-requirement-current"><?php $wpl_writable ? wpl_esc::html_t('Yes') : wpl_esc::html_t('No'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($wpl_writable ? 'confirm' : 'danger'); ?>"></i>
            </span>
		</li>
        <!-- WPL temporary directory permission -->
        <?php $wpl_tmp_writable = is_writable(wpl_global::get_tmp_path()); ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('WPL tmp directory'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Writable'); ?></span>
            <span class="wpl-requirement-current"><?php $wpl_tmp_writable ? wpl_esc::html_t('Yes') : wpl_esc::html_t('No'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($wpl_tmp_writable ? 'confirm' : 'danger'); ?>"></i>
            </span>
		</li>
        <!-- GD library -->
        <?php $gd = (extension_loaded('gd') && function_exists('gd_info')) ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('GD library'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Installed'); ?></span>
            <span class="wpl-requirement-current"><?php $gd ? wpl_esc::html_t('Installed') : wpl_esc::html_t('Not Installed'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($gd ? 'confirm' : 'danger'); ?>"></i>
            </span>
		</li>
        <!-- CURL -->
        <?php $curl = function_exists('curl_version') ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('CURL'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Installed'); ?></span>
            <span class="wpl-requirement-current"><?php $curl ? wpl_esc::html_t('Installed') : wpl_esc::html_t('Not Installed'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($curl ? 'confirm' : 'danger'); ?>"></i>
            </span>
		</li>
        <!-- Multibyte String -->
        <?php $mb_string = function_exists('mb_get_info') ? true : false; ?>
        <li>
            <span class="wpl-requirement-name"><?php wpl_esc::html_t('Multibyte String'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Installed'); ?></span>
            <span class="wpl-requirement-current"><?php $mb_string ? wpl_esc::html_t('Installed') : wpl_esc::html_t('Not Installed'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
                <i class="icon-<?php wpl_esc::attr($mb_string ? 'confirm' : 'warning'); ?>"></i>
            </span>
        </li>
        <!-- Safe Mode -->
        <?php $safe = ini_get('safe_mode'); $safe_mode = (!$safe or strtolower($safe) == 'off') ? true : false; ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('Safe Mode'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Off'); ?></span>
            <span class="wpl-requirement-current"><?php $safe_mode ? wpl_esc::html_t('Off') : wpl_esc::html_t('On'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($safe_mode ? 'confirm' : 'warning'); ?>"></i>
            </span>
		</li>
        <!-- Memory Limit -->
        <?php $memory_limit = ini_get('memory_limit'); $memory_status = ((int) $memory_limit < 128) ? false : true; ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('Memory Limit'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('128M'); ?></span>
            <span class="wpl-requirement-current"><?php wpl_esc::html($memory_limit); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($memory_status ? 'confirm' : 'warning'); ?>"></i>
            </span>
		</li>
        <!-- Write Permission -->
        <?php $writable = (is_writable(WPL_ABSPATH.'libraries'.DS.'services'.DS.'sef.php') and is_writable(WPL_ABSPATH.'widgets'.DS.'search'.DS.'main.php') and is_writable(WPL_ABSPATH.'WPL.php')); ?>
        <li>
        	<span class="wpl-requirement-name"><?php wpl_esc::html_t('Write Permission'); ?></span>
            <span class="wpl-requirement-require"><?php wpl_esc::html_t('Yes'); ?></span>
            <span class="wpl-requirement-current"><?php $writable ? wpl_esc::html_t('Yes') : wpl_esc::html_t('No'); ?></span>
            <span class="wpl-requirement-status p-action-btn">
            	<i class="icon-<?php wpl_esc::attr($writable ? 'confirm' : 'danger'); ?>"></i>
            </span>
		</li>
        <!-- Server providers offers -->
        <li class="wpl_server_offers">
        	<a href="http://hosting.realtyna.com/" target="_blank">Cloud real estate web hosting</a><br />
            <a href="http://bluehost.com/track/realtyna" target="_blank">Bluehost WordPress</a>
		</li>
    </ul>
</div>