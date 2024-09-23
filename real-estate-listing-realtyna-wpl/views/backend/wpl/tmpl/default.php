<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.css');
$this->_wpl_import($this->tpl_path . '.scripts.js');
?>
<div class="wrap wpl-wp wpl-dashboard-wp">

    <header>
        <div class="wpl-icon-header wpl-icon-dashboard"></div>
        <h2>
            <?php wpl_esc::html_t('WPL'); ?>&nbsp;<?php wpl_esc::attr(wpl_global::check_addon('pro') ? 'PRO' : 'Basic'); ?>
            <span class="wpl-dashboard-ver">v<?php wpl_esc::html(wpl_global::wpl_version()); ?></span>
        </h2>
    </header>

    <div class="wpl-flashes-container"><?php wpl_esc::e(wpl_flash::get()); ?></div>

    <!-- Generating announcements -->
    <?php $this->announcements(); ?>

    <div id="dashboard-links-wp">
        <ul>
            <?php foreach($submenus as $submenu): if(!wpl_users::has_menu_access($submenu->menu_slug, wpl_users::get_cur_user_id())) continue; ?>
                <li class="link-<?php wpl_esc::attr($submenu->id); ?>">
                    <a href="<?php wpl_esc::e(wpl_global::get_wp_admin_url()); ?>admin.php?page=<?php wpl_esc::attr($submenu->menu_slug); ?>">
                        <span class="box"><i></i></span>
                        <span class="title">
                            <?php wpl_esc::html_t($submenu->menu_title); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if(wpl_users::is_super_admin()): ?>
    <div class="sidebar-wp sidebar-float">
        <div class="side-ni-addons">
            <div class="sidebar-wp sidebar-float">

                <div class="rt-same-height sidebar-float">

                    <!-- Generating optional addons -->
                    <?php $this->not_installed_addons(); ?>

                    <!-- WPL change-log -->
                    <div class="side-7 side-changes js-full-height" data-minuse-size="56" id="wpl_dashboard_changelog">
                        <div class="panel-wp">
                            <h3><?php wpl_esc::html_t('Changelog'); ?></h3>

                            <div class="panel-body">
                                <?php _wpl_import('assets.changelogs.wpl'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rt-same-height sidebar-float">
                    <!-- Generating addons -->
                    <?php $this->generate_addons(); ?>
                </div>

                <!-- Generating statistic section -->
                <?php $this->knowledgebase(); ?>

                <div class="rt-same-height sidebar-float">
                    <!-- Generating support section -->
                    <?php $this->support(); ?>

                    <!-- Generating share box -->
                    <?php $this->sharebox(); ?>
                </div>

                <div class="rt-same-height sidebar-float">
                    <!-- Generating statistic section -->
                    <?php $this->statistic(); ?>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>

    <footer>
        <div class="logo"></div>
    </footer>
</div>