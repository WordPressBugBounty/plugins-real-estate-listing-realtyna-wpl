<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-content size-width-2">
    <h2><?php wpl_esc::html_t('Edit User'); ?></h2>
    <div class="wpl_show_message"></div>

    <div class="fanc-body">
        <div class="fanc-row fanc-button-row-2">
            <input type="button" class="wpl-button button-1" value="<?php wpl_esc::attr_t('Save'); ?>" onclick="wpl_save_user();" />
        </div>
        <div class="col-wp">
            <div class="col-fanc-left fanc-tabs-wp">
                <ul>
                    <li class="active">
                        <a href="#basic" class="tab-basic" id="wpl_slide_label_id_basic" onclick="rta.internal.slides.open('_basic','.fanc-tabs-wp','.fanc-content-body');"><?php wpl_esc::html_t('Basic'); ?></a>
                    </li>
                    <li>
                        <a href="#advanced" class="tab-advanced" id="wpl_slide_label_id_advanced" onclick="rta.internal.slides.open('_advanced','.fanc-tabs-wp','.fanc-content-body');"><?php wpl_esc::html_t('Advanced'); ?></a>
                    </li>
                    <li>
                        <a href="#pricing" class="tab-pricing" id="wpl_slide_label_id_pricing" onclick="rta.internal.slides.open('_pricing','.fanc-tabs-wp','.fanc-content-body');"><?php wpl_esc::html_t('Pricing'); ?></a>
                    </li>
                    <?php if(wpl_global::check_addon('brokerage') and $this->user_data->membership_type == 7): ?>
                    <li>
                        <a href="#brokerage" class="tab-brokerage" id="wpl_slide_label_id_brokerage" onclick="rta.internal.slides.open('_brokerage','.fanc-tabs-wp','.fanc-content-body');"><?php wpl_esc::html_t('Brokerage'); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if(wpl_global::check_addon('crm')): ?>
                    <li>
                        <a href="#crm" class="tab-crm" id="wpl_slide_label_id_crm" onclick="rta.internal.slides.open('_crm','.fanc-tabs-wp','.fanc-content-body');"><?php wpl_esc::html_t('CRM'); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if(wpl_global::check_addon('rets')): ?>
                    <li class="<?php wpl_esc::attr(wpl_users::check_access('rets', NULL, $this->user_data->id) ? '' : 'wpl-util-hidden'); ?>" id="wpl_edit_user_rets_tab">
                        <a href="#rets" class="tab-rets" id="wpl_slide_label_id_rets" onclick="rta.internal.slides.open('_rets','.fanc-tabs-wp','.fanc-content-body');"><?php wpl_esc::html_t('RETS Server'); ?></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-fanc-right fanc-content-wp" id="wpl_edit_user">
                <div class="fanc-content-body" id="wpl_slide_container_id_basic">
                    <div class="fanc-row">
                        <div class="mark-controls">
                            <input type="button" class="wpl-button button-2" value="<?php wpl_esc::attr_t('Toggle'); ?>" onclick="rta.util.checkboxes.toggle(wpl_slide_container_id_basic);" />
                            <input type="button" class="wpl-button button-2" value="<?php wpl_esc::attr_t('All'); ?>" onclick="rta.util.checkboxes.selectAll(wpl_slide_container_id_basic);" />
                            <input type="button" class="wpl-button button-2" value="<?php wpl_esc::attr_t('None'); ?>" onclick="rta.util.checkboxes.deSelectAll(wpl_slide_container_id_basic);" />
                        </div>
                        <div class="access-checkbox-wp">
                            <input type="hidden" id="id" name="id" value="<?php wpl_esc::attr($this->user_data->id); ?>" />
                            <?php
                            foreach($this->fields as $field=>$value)
                            {
                                if(substr($value, 0, 7) != 'access_') continue;

								wpl_esc::e( '<div class="checkbox-wp"><input type="checkbox" id="' . wpl_esc::return_attr($value) . '" value="' . wpl_esc::return_attr($this->user_data->{$value}) . '"' . ($this->user_data->{$value} == 1 ? 'checked="checked"' : ''));
								wpl_esc::e( ' /> <label for="' . wpl_esc::return_attr($value) . '">' . wpl_esc::return_html(str_replace('_', ' ', substr($value, 7))) . '</label></div>');
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="fanc-content-body" id="wpl_slide_container_id_advanced" style="display: none">
                    <div id="tab_setting_advance">
                        <?php $this->generate_tab('internal_setting_advanced'); ?>
                    </div>
                </div>
                <div class="fanc-content-body" id="wpl_slide_container_id_pricing" style="display: none">
                    <div id="tab_setting_pricing">
                        <?php $this->generate_tab('internal_setting_pricing'); ?>
                    </div>
                </div>
                <?php if(wpl_global::check_addon('brokerage') and $this->user_data->membership_type == 7): ?>
                <div class="fanc-content-body" id="wpl_slide_container_id_brokerage" style="display: none">
                    <div id="tab_setting_brokerage">
                        <?php $this->generate_tab('internal_setting_brokerage'); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(wpl_global::check_addon('crm')): ?>
                <div class="fanc-content-body" id="wpl_slide_container_id_crm" style="display: none">
                    <div id="tab_setting_crm">
                        <?php $this->generate_tab('internal_setting_crm'); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(wpl_global::check_addon('rets')): ?>
                <div class="fanc-content-body" id="wpl_slide_container_id_rets" style="display: none">
                    <div id="tab_setting_rets">
                        <?php $this->generate_tab('internal_setting_rets'); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>