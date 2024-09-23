<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js');
$this->_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48">
        </div>
        <h2><?php wpl_esc::html_t('WPL Settings'); ?></h2>
    </header>
    <div class="wpl_settings_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp wpl-gen-side-tabs-wp">
            <ul>
                <?php foreach ($this->setting_categories as $category): ?>
                    <li>
                        <a href="#<?php wpl_esc::attr(str_replace(' ', '_', $category->name ?? '')); ?>" class="wpl_slide_label wpl-icon-side-setting-<?php wpl_esc::attr($category->id); ?>"
                           id="wpl_slide_label_id<?php wpl_esc::attr($category->id); ?>" 
                           onclick="rta.internal.slides.open('<?php wpl_esc::attr($category->id); ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');"><?php wpl_esc::html_t($category->name); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="side-12 side-content-wp">
            <?php foreach ($this->setting_categories as $category): ?>
                <div class="pwizard-panel settings-wp wpl_slide_container wpl_slide_container<?php wpl_esc::attr($category->id); ?>" id="wpl_slide_container_id<?php wpl_esc::attr($category->id); ?>">
                    <?php $this->generate_slide($category); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="clearit"></div>
        
        <div class="wpl-bottom-nav">
            <div class="wpl-util-side-left-menu-spacer"></div>

            <div class="wpl-util-side-12 wpl-util-clearfix">
                
                <div class="wpl-util-side-<?php wpl_esc::attr(wpl_global::check_addon('pro') ? '6 wpl-util-side-left' : '12 wpl-util-side-none side-no-padding'); ?> side-maintenance">
                    <div class="panel-wp">
                        <h3><?php wpl_esc::html_t('Sample Properties'); ?></h3>
                        <div class="panel-body">
                            <?php $this->generate_internal('sampleproperties'); ?>
                        </div>
                    </div>
                </div>
                
                <?php if(wpl_global::check_addon('pro')): ?>
                <div class="wpl-util-side-6 wpl-util-side-right side-requirements">
                    <div class="panel-wp">
                        <h3><?php wpl_esc::html_t('Import/Export'); ?></h3>
                        <div class="panel-body">
                            <?php $this->generate_internal('importexport'); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(wpl_global::check_addon('rank')): ?>
                <div class="wpl-util-side-12 wpl-util-side-none side-requirements" id="wpl_addon_rank_update_panel">
                    <div class="panel-wp">
                        <h3><?php wpl_esc::html_t('Property Ranks'); ?></h3>
                        <div class="panel-body">
                            <?php $this->generate_internal('addon_rank'); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="wpl-util-side-12 wpl-util-side-none side-requirements" id="wpl_cronjobs_panel">
                    <div class="panel-wp">
                        <h3><?php wpl_esc::html_t('WPL Continues Jobs'); ?></h3>
                        <div class="panel-body">
                            <?php $this->generate_internal('cronjobs'); ?>
                        </div>
                    </div>
                </div>

                <?php if(count($activities = wpl_activity::get_activities('settings_bottom', 1))): ?>
                <div id="settings_bottom_activities_container">
                    <?php
                        foreach($activities as $activity)
                        {
                            $content = wpl_activity::render_activity($activity);
                            if(trim($content ?? '') == '') continue;
                            ?>
                            <div class="wpl-util-side-12 wpl-util-side-none side-requirements">
                                <div class="panel-wp margin-top-1p">
                                    <?php if($activity->show_title and trim($activity->title ?? '') != ''): ?>
                                    <h3><?php wpl_esc::html_t($activity->title); ?></h3>
                                    <?php endif; ?>
                                    <div class="panel-body"><?php wpl_esc::e($content); ?></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
                <?php endif; ?>
                
            </div>

            <div class="wpl-util-side-12 wpl-util-clearfix">
                <div class="wpl-util-side-6 wpl-util-side-left side-maintenance">
                    <div class="panel-wp">
                        <h3><?php wpl_esc::html_t('Maintenance'); ?></h3>
                        <div class="panel-body">
                            <?php $this->generate_internal('maintenance'); ?>
                        </div>
                    </div>
                </div>

                <div class="wpl-util-side-6 wpl-util-side-right side-requirements">
                    <div class="panel-wp">
                        <h3><?php wpl_esc::html_t('Server requirements'); ?></h3>
                        <div class="panel-body">
                            <?php $this->generate_internal('requirements'); ?>
                        </div>
                    </div>
                </div>
            </div>

		</div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>