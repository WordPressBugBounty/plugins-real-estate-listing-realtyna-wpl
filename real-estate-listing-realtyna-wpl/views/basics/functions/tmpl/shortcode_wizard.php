<?php

/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="short-code-wp wpl_shortcode_wizard_container" id="wpl_shortcode_wizard_container" style="margin: 0 20px;">
    <h2>
        <i class="icon-shortcode"></i>
        <span><?php wpl_esc::html_t('WPL Shortcodes'); ?></span>
        <button class="wpl-button button-1" onclick="insert_shortcode();"><?php wpl_esc::html_t('Insert'); ?></button>
    </h2>
    <div class="short-code-body">

        <div class="plugin-row wpl_select_view">
            <label for="view_selectbox"><?php wpl_esc::html_t('View'); ?></label>
            <select id="view_selectbox" onchange="wpl_view_selected(this.value);">
                <option value="property_listing"><?php wpl_esc::html_t('Property Listing'); ?></option>
                <option value="property_show"><?php wpl_esc::html_t('Property Show'); ?></option>
                <option value="profile_listing"><?php wpl_esc::html_t('Profile/Agent Listing'); ?></option>
                <option value="profile_show"><?php wpl_esc::html_t('Profile/Agent Show'); ?></option>
                <option value="profile_wizard"><?php wpl_esc::html_t('My Profile'); ?></option>
                <?php if (wpl_global::check_addon('pro')) : ?><option value="widget_shortcode"><?php wpl_esc::html_t('Widget Shortcode'); ?></option><?php endif; ?>
                <?php if (wpl_global::check_addon('save_searches')) : ?><option value="save_searches"><?php wpl_esc::html_t('Saved Searches'); ?></option><?php endif; ?>
                <?php if (wpl_global::check_addon('mortgage_calculator')) : ?><option value="mortgage_calculator"><?php wpl_esc::html_t('Mortgage Calculator'); ?></option><?php endif; ?>
            </select>
        </div>

        <h3><?php wpl_esc::html_t('Filter Options'); ?></h3>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $kinds = wpl_flex::get_kinds('wpl_properties'); ?>
            <label for="pr_kind_selectbox"><?php wpl_esc::html_t('Kind'); ?></label>
            <select id="pr_kind_selectbox" <?php if (wpl_global::check_addon('PRO')) : ?>onchange="wpl_kind_selected(this.value);" <?php endif; ?> name="kind">
                <?php foreach ($kinds as $kind) : ?>
                    <option value="<?php wpl_esc::attr($kind['id']); ?>"><?php wpl_esc::html_t($kind['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $listings = wpl_global::get_listings(); ?>
            <label for="pr_listing_type_selectbox"><?php wpl_esc::html_t('Listing type'); ?></label>
            <select id="pr_listing_type_selectbox" name="sf_select_listing">
                <option value="-1"><?php wpl_esc::html_t('All'); ?></option>
                <?php foreach ($listings as $listing) : ?>
                    <option value="<?php wpl_esc::attr($listing['id']); ?>"><?php wpl_esc::html_t($listing['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $property_types = wpl_global::get_property_types(); ?>
            <label for="pr_property_type_selectbox"><?php wpl_esc::html_t('Property type'); ?></label>
            <select id="pr_property_type_selectbox" name="sf_select_property_type">
                <option value="-1"><?php wpl_esc::html_t('All'); ?></option>
                <?php foreach ($property_types as $property_type) : ?>
                    <option value="<?php wpl_esc::attr($property_type['id']); ?>"><?php wpl_esc::html_t($property_type['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $location_settings = wpl_global::get_settings('3'); # location settings 
            ?>
            <label for="pr_location_textsearch"><?php wpl_esc::html_t('Location'); ?></label>
            <input type="text" id="pr_location_textsearch" name="sf_locationtextsearch" placeholder="<?php wpl_esc::attr_t($location_settings['locationzips_keyword'] . ', ' . $location_settings['location3_keyword'] . ', ' . $location_settings['location1_keyword']); ?>" />
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <label for="pr_price_min"><?php wpl_esc::html_t('Price (Min)'); ?></label>
            <input type="text" id="pr_price_min" name="sf_min_price" />
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <label for="pr_price_max"><?php wpl_esc::html_t('Price (Max)'); ?></label>
            <input type="text" id="pr_price_max" name="sf_max_price" />
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $units = wpl_units::get_units(4); ?>
            <label for="pr_price_unit_selectbox"><?php wpl_esc::html_t('Price Unit'); ?></label>
            <select id="pr_price_unit_selectbox" name="sf_unit_price">
                <?php foreach ($units as $unit) : ?>
                    <option value="<?php wpl_esc::attr($unit['id']); ?>"><?php wpl_esc::html_t($unit['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php $tags = wpl_flex::get_tag_fields(0);
        foreach ($tags as $tag) : ?>
            <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
                <label for="pr_only_<?php wpl_esc::attr($tag->table_column); ?>_selectbox"><?php wpl_esc::html_t($tag->name); ?></label>
                <select id="pr_only_<?php wpl_esc::attr($tag->table_column); ?>_selectbox" name="sf_select_<?php wpl_esc::attr($tag->table_column); ?>">
                    <option value="-1"><?php wpl_esc::html_t('Any'); ?></option>
                    <option value="0"><?php wpl_esc::html_t('No'); ?></option>
                    <option value="1"><?php wpl_esc::html_t('Yes'); ?></option>
                </select>
            </div>
        <?php endforeach; ?>

        <div class="plugin-row wpl_shortcode_parameter pr_property_listing pr_profile_show">
            <?php $wpl_users = wpl_users::get_wpl_users(); ?>
            <label for="pr_target_page_selectbox"><?php wpl_esc::html_t('User'); ?></label>
            <select id="pr_target_page_selectbox" name="sf_select_user_id" data-has-chosen>
                <option value="">-----</option>
                <?php foreach ($wpl_users as $wpl_user) : ?>
                    <option value="<?php wpl_esc::attr($wpl_user->ID); ?>"><?php wpl_esc::html_t($wpl_user->user_login . ((trim($wpl_user->first_name) != '' or trim($wpl_user->last_name) != '') ? ' (' . $wpl_user->first_name . ' ' . $wpl_user->last_name . ')' : '')); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $user_types = wpl_users::get_user_types(); ?>
            <label for="pr_user_type_selectbox"><?php wpl_esc::html_t('User Type'); ?></label>
            <select id="pr_user_type_selectbox" name="sf_select_membership_type">
                <option value="">-----</option>
                <?php foreach ($user_types as $user_type) : ?>
                    <option value="<?php wpl_esc::attr($user_type->id); ?>"><?php wpl_esc::html_t($user_type->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (wpl_global::check_addon('membership')) : ?>
            <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
                <?php $memberships = wpl_users::get_wpl_memberships(); ?>
                <label for="pr_membership_selectbox"><?php wpl_esc::html_t('Membership'); ?></label>
                <select id="pr_membership_selectbox" name="sf_select_membership_id">
                    <option value="">-----</option>
                    <?php foreach ($memberships as $membership) : ?>
                        <option value="<?php wpl_esc::attr($membership->id); ?>"><?php wpl_esc::html_t($membership->membership_name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_show">
            <label for="pr_mls_id_textbox"><?php wpl_esc::html_t('Listing ID'); ?></label>
            <input type="text" id="pr_mls_id_textbox" name="mls_id" />
        </div>

        <div class="plugin-row wpl-advanced-filtering">

            <h3><?php wpl_esc::html_t('Advanced Filtering'); ?></h3>

            <?php $fields = wpl_db::select("SELECT * FROM `#__wpl_dbst` WHERE 1 AND `enabled`>='1' AND `kind`='0' AND `type` IN ('select','feature','text','number','area','length','price','boolean','checkbox') AND `table_column`!='' ORDER BY `category` ASC, `index` ASC", 'loadObjectList'); ?>
            <label for="wpl_shortcode_wizard_filter"><?php wpl_esc::html_t('Filter'); ?></label>
            <select id="wpl_shortcode_wizard_filter" onchange="wpl_filter_add(this.value);" autocomplete="off" data-has-chosen>
                <option value="">-----</option>
                <?php foreach ($fields as $field) : ?>
                    <option value="<?php wpl_esc::attr($field->id); ?>"><?php wpl_esc::html_t($field->name); ?></option>
                <?php endforeach; ?>
            </select>
            <div id="wpl_shortcode_wizard_filters" style="margin: 15px 0;">
            </div>
        </div>

        <h3><?php wpl_esc::html_t('Display Options'); ?></h3>

        <!-- View Layouts -->
        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $property_listing_layouts = wpl_global::get_layouts(); ?>
            <label for="pr_tpl_selectbox"><?php wpl_esc::html_t('Layout'); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach ($property_listing_layouts as $layout) : ?>
                    <option value="<?php wpl_esc::attr($layout != 'default' ? $layout : ''); ?>" <?php if ($layout == 'default') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($layout); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_show">
            <?php $property_show_layouts = wpl_global::get_layouts('property_show'); ?>
            <label for="pr_tpl_selectbox"><?php wpl_esc::html_t('Layout'); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach ($property_show_layouts as $layout) : ?>
                    <option value="<?php wpl_esc::attr($layout != 'default' ? $layout : ''); ?>" <?php if ($layout == 'default') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($layout); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $profile_listing_layouts = wpl_global::get_layouts('profile_listing'); ?>
            <label for="pr_tpl_selectbox"><?php wpl_esc::html_t('Layout'); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach ($profile_listing_layouts as $layout) : ?>
                    <option value="<?php wpl_esc::e(($layout != 'default' ? $layout : '')); ?>" <?php if ($layout == 'default') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($layout); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_show">
            <?php $profile_show_layouts = wpl_global::get_layouts('profile_show'); ?>
            <label for="pr_tpl_selectbox"><?php wpl_esc::html_t('Layout'); ?></label>
            <select id="pr_tpl_selectbox" name="tpl">
                <?php foreach ($profile_show_layouts as $layout) : ?>
                    <option value="<?php wpl_esc::e($layout != 'default' ? $layout : ''); ?>" <?php if ($layout == 'default') wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($layout); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter pr_property_listing pr_profile_listing pr_profile_show">
            <?php $pages = wpl_global::get_wp_pages(); ?>
            <label for="pr_target_page_selectbox"><?php wpl_esc::html_t('Target page'); ?></label>
            <select id="pr_target_page_selectbox" name="wpltarget" data-has-chosen>
                <option value="">-----</option>
                <?php foreach ($pages as $page) : ?>
                    <option value="<?php wpl_esc::attr($page->ID); ?>"><?php wpl_esc::html_t($page->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing">
            <?php $page_sizes = explode(',', trim($this->settings['page_sizes'], ', ')); ?>
            <label for="pr_limit_selectbox"><?php wpl_esc::html_t('Page Size'); ?></label>
            <select id="pr_limit_selectbox" name="limit">
                <?php foreach ($page_sizes as $page_size) : ?>
                    <option value="<?php wpl_esc::attr($page_size); ?>" <?php if ($this->settings['default_page_size'] == $page_size) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($page_size); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (wpl_global::check_addon('pro')) : ?>
            <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing pr_profile_show">
                <label for="pr_wplpagination_selectbox"><?php wpl_esc::html_t('Pagination Type'); ?></label>
                <select id="pr_wplpagination_selectbox" name="wplpagination">
                    <option value="">-----</option>
                    <option value="scroll"><?php wpl_esc::html_t('Scroll Pagination'); ?></option>
                </select>
            </div>
        <?php endif; ?>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing">
            <?php $sort_options = wpl_sort_options::render(wpl_sort_options::get_sort_options(0, 1));
            /** getting enaled sort options **/ ?>
            <label for="pr_orderby_selectbox"><?php wpl_esc::html_t('Order by'); ?></label>
            <select id="pr_orderby_selectbox" name="wplorderby">
                <?php foreach ($sort_options as $value_array) : ?>
                    <option value="<?php wpl_esc::attr($value_array['field_name']); ?>" <?php if ($this->settings['default_orderby'] == $value_array['field_name']) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($value_array['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_profile_listing">
            <?php $sort_options = wpl_sort_options::render(wpl_sort_options::get_sort_options(2, 1));
            /** getting enaled sort options **/ ?>
            <label for="pr_orderby_user_selectbox"><?php wpl_esc::html_t('Order by'); ?></label>
            <select id="pr_orderby_user_selectbox" name="wplorderby">
                <?php foreach ($sort_options as $value_array) : ?>
                    <option value="<?php wpl_esc::attr($value_array['field_name']); ?>" <?php if ($this->settings['default_profile_orderby'] == $value_array['field_name']) wpl_esc::e('selected="selected"'); ?>><?php wpl_esc::html_t($value_array['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing">
            <label for="pr_order_selectbox"><?php wpl_esc::html_t('Order'); ?></label>
            <select id="pr_order_selectbox" name="wplorder">
                <option value="DESC"><?php wpl_esc::html_t('DESC'); ?></option>
                <option value="ASC"><?php wpl_esc::html_t('ASC'); ?></option>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_widget_shortcode">
            <?php $widgets_list = wpl_widget::get_existing_widgets(); ?>
            <label for="pr_widget_selectbox"><?php wpl_esc::html_t('Widget'); ?></label>
            <select id="pr_widget_selectbox" name="id">
                <option value="">-----</option>
                <?php foreach ($widgets_list as $sidebar => $widgets) : if ($sidebar == 'wp_inactive_widgets') continue; ?>
                    <?php foreach ($widgets as $widget) : if (strpos($widget['id'] ?? '', 'wpl_') === false) continue; ?>
                        <option value="<?php wpl_esc::attr($widget['id']); ?>"><?php wpl_esc::html_t(ucwords(str_replace('_', ' ', $widget['id'] ?? ''))); ?></option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label for="pr_total_price"><?php wpl_esc::html_t('Total Price'); ?></label>
            <input type="text" id="pr_total_price" name="total_price" value="300,000" />
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label for="wpl_o_down_payment_type"><?php wpl_esc::html_t('D Payment Type'); ?></label>
            <select id="wpl_o_down_payment_type" name="down_payment_type" onchange="wpl_mcalc_onchange_input(this)">
                <option value="0"><?php wpl_esc::html_t('Percent'); ?></option>
                <option value="1"><?php wpl_esc::html_t('Amount'); ?></option>
                <option value="2"><?php wpl_esc::html_t('Both'); ?></option>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <?php
            // Get WPL currency
            $default_unit = \wpl_units::get_default_unit(4);
            $symbol = $default_unit['name'];
            ?>
            <label><?php wpl_esc::html_t('Down Payment'); ?></label>
            <span id="wpl_span_down_payment_amt">
                <input type="text" name="down_payment_amt" />
                <?php wpl_esc::html_t($symbol); ?>
            </span>
            <span id="wpl_span_down_payment_percent">
                <input type="text" name="down_payment_percent" />
                <?php wpl_esc::html_t("%"); ?>
            </span>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label for="pr_loan_term"><?php wpl_esc::html_t('Loan Term'); ?></label>
            <input type="text" id="pr_loan_term" name="loan_term" value="30" />
            <?php wpl_esc::html_t('years'); ?>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label for="pr_interest_rate"><?php wpl_esc::html_t('Loan Term'); ?></label>
            <input type="text" id="pr_interest_rate" name="interest_rate" value="4.5" />
            <?php wpl_esc::html_t("%"); ?>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label for="wpl_o_tax_type"><?php wpl_esc::html_t('Tax Type'); ?></label>
            <select id="wpl_o_tax_type" name="tax_type" onchange="wpl_mcalc_onchange_input(this)">
                <option value="0"><?php wpl_esc::html_t('Percent'); ?></option>
                <option value="1"><?php wpl_esc::html_t('Amount'); ?></option>
                <option value="2"><?php wpl_esc::html_t('Both'); ?></option>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label><?php wpl_esc::html_t('Tax'); ?></label>
            <span id="wpl_span_tax_amt">
                <input type="text" name="tax_amt" />
                <?php wpl_esc::html_t($symbol); ?>
            </span>
            <span id="wpl_span_tax_percent">
                <input type="text" name="tax_percent" />
                <?php wpl_esc::html_t("%"); ?>
            </span>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label for="wpl_o_insurance_type"><?php wpl_esc::html_t('Insurance Type'); ?></label>
            <select id="wpl_o_insurance_type" name="insurance_type" onchange="wpl_mcalc_onchange_input(this)">
                <option value="0"><?php wpl_esc::html_t('Percent'); ?></option>
                <option value="1"><?php wpl_esc::html_t('Amount'); ?></option>
                <option value="2"><?php wpl_esc::html_t('Both'); ?></option>
            </select>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_mortgage_calculator">
            <label><?php wpl_esc::html_t('Insurance'); ?></label>
            <span id="wpl_span_insurance_amt">
                <input type="text" name="insurance_amt" />
                <?php wpl_esc::html_t($symbol); ?>
            </span>
            <span id="wpl_span_insurance_percent">
                <input type="text" name="insurance_percent" />
                <?php wpl_esc::html_t("%"); ?>
            </span>
        </div>

        <div class="plugin-row wpl_shortcode_parameter wpl_hidden_element pr_property_listing pr_profile_listing">
            <label for="pr_columns_count_selectbox"><?php wpl_esc::html_t('Columns Count'); ?></label>
            <select id="pr_columns_count_selectbox" name="wplcolumns">
                <option value="1">1 <?php wpl_esc::html_t('Column'); ?></option>
                <option value="2">2 <?php wpl_esc::html_t('Columns'); ?></option>
                <option value="3" selected="selected">3 <?php wpl_esc::html_t('Columns'); ?></option>
                <option value="4">4 <?php wpl_esc::html_t('Columns'); ?></option>
                <option value="6">6 <?php wpl_esc::html_t('Columns'); ?></option>
            </select>
        </div>

    </div>
</div>
<script id="wpl-filter" type="text/x-handlebars-template">
    <div class="wpl-shortcode-wizard-filters-wp" id="wpl_shortcode_wizard_filters_wp_{{field.id}}">
        <label for="wpl_shortcode_wizard_filters_wp_options_{{field.id}}">
            <span class="wpl-shortcode-wizard-filter-label">{{field.name}}</span>
        </label>

        {{#if operators}}
        <span class="wpl-shortcode-wizard-filters-operators">
            <select title="<?php wpl_esc::attr_t('Operator'); ?>">
                {{{wploptions operators}}}
            </select>
        </span>
        {{/if}}

        <span class="wpl-shortcode-wizard-filter-options">
            {{#if options}}
                <select class="wpl-shortcode-wizard-filter-value" multiple="multiple" data-chosen-opt="width:40%" id="wpl_shortcode_wizard_filters_wp_options_{{field.id}}">
                    {{{wploptions options}}}
                </select>
            {{else}}
                <input class="wpl-shortcode-wizard-filter-value" type="text" value="{{values}}" id="wpl_shortcode_wizard_filters_wp_options_{{field.id}}" />
            {{/if}}
        </span>
    </div>
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Handlebars.registerHelper('wploptions', function(options) {
            var output = '';
            for (var i = 0, len = options.length; i < len; i++) {
                output += '<option value="' + options[i].key + '">' + Handlebars.Utils.escapeExpression(options[i].name) + '</option>';
            }

            return new Handlebars.SafeString(output);
        });

        setTimeout(wpl_view_selected(wplj("#view_selectbox").val()), 1000);
    });

    function insert_shortcode() {
        var shortcode = '';
        var view = wplj("#view_selectbox").val();

        if (view === 'property_listing') shortcode += '[WPL';
        else if (view === 'property_show') shortcode += '[wpl_property_show';
        else if (view === 'profile_listing') shortcode += '[wpl_profile_listing';
        else if (view === 'profile_show') shortcode += '[wpl_profile_show';
        else if (view === 'profile_wizard') shortcode += '[wpl_my_profile';
        else if (view === 'widget_shortcode') shortcode += '[wpl_widget_instance';
        else if (view === 'save_searches') shortcode += '[wpl_addon_save_searches';
        else if (view === 'mortgage_calculator') shortcode += '[wpl_addon_mortgage_calculator';

        wplj("#wpl_shortcode_wizard_container .pr_" + view + " input:text, #wpl_shortcode_wizard_container .pr_" + view + " input[type='hidden'], #wpl_shortcode_wizard_container .pr_" + view + " select").each(function(ind, elm) {
            if (elm.name == '') return;
            if (wplj(elm).val() == '' || wplj(elm).val() == '-1') return;

            shortcode += ' ' + elm.name + '="' + wplj(elm).val() + '"';
        });

        if (view === 'property_listing') {
            wplj("#wpl_shortcode_wizard_container #wpl_shortcode_wizard_filters .wpl-shortcode-wizard-filters-wp").each(function(ind, elm) {
                var key = wplj(elm).find('.wpl-shortcode-wizard-filters-operators select').val();
                var value = wplj(elm).find('.wpl-shortcode-wizard-filter-options .wpl-shortcode-wizard-filter-value').val();

                if (value == '' || value == '-1') return;

                shortcode += ' ' + key + '="' + value + '"';
            });
        }

        shortcode += ']';

        // inserts the shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        // closes Thickbox
        tinyMCEPopup.close();
    }

    function wpl_view_selected(view) {
        if (!view) view = 'property_listing';

        wplj(".wpl_shortcode_wizard_container .wpl_shortcode_parameter").hide();
        wplj(".wpl_shortcode_wizard_container .pr_" + view).show();

        // Subjects
        if (view === 'profile_wizard' || view === 'save_searches' || view === 'mortgage_calculator' || view === 'widget_shortcode') wplj(".wpl_shortcode_wizard_container h3").hide();
        else wplj(".wpl_shortcode_wizard_container h3").show();

        // Advanced Filtering
        if (view === 'property_listing') wplj(".wpl_shortcode_wizard_container .wpl-advanced-filtering").show();
        else wplj(".wpl_shortcode_wizard_container .wpl-advanced-filtering").hide();

        <?php if (wpl_global::check_addon('PRO')) : ?>
            // Change columns count default value
            var listing_columns = <?php wpl_esc::numeric($this->settings['wpl_ui_customizer_property_listing_columns'] ?? 3); ?>;
            var profile_columns = <?php wpl_esc::numeric($this->settings['wpl_ui_customizer_profile_listing_columns'] ?? 3); ?>;

            if (view === 'property_listing') wpl_columns_count_default(listing_columns);
            else if (view === 'profile_listing') wpl_columns_count_default(profile_columns);
        <?php endif; ?>
    }

    function wpl_filter_add(field_id) {
        if (wplj("#wpl_shortcode_wizard_filters_wp_" + field_id).length) return false;

        var ajax_loader_element = '#wpl_shortcode_wizard_filter_chosen';

        /** Show AJAX loader **/
        var wpl_ajax_loader = Realtyna.ajaxLoader.show(ajax_loader_element, 'tiny', 'rightOut');

        wplj.when(wpl_filter_get_field_data(field_id))
            .fail(function() {
                /** Remove AJAX loader **/
                Realtyna.ajaxLoader.hide(wpl_ajax_loader);
            })
            .done(function(response) {
                if (response.success) {
                    var field = Handlebars.compile(wplj("#wpl-filter").html())
                        ({
                            field: response.data.field,
                            options: response.data.options,
                            operators: response.data.operators
                        });

                    wplj("#wpl_shortcode_wizard_filters").append(field);
                }

                /** Remove AJAX loader **/
                Realtyna.ajaxLoader.hide(wpl_ajax_loader);
            });
    }

    function wpl_filter_get_field_data(field_id) {
        var request_str = 'wpl_format=b:settings:ajax&wpl_function=get_field_options&id=' + field_id + '&_wpnonce=<?php wpl_esc::js(wpl_security::create_nonce('wpl_settings')); ?>';

        return wplj.ajax({
            type: "POST",
            url: '<?php wpl_esc::url(wpl_global::remove_qs_var('wpl_format', wpl_global::remove_qs_var('wpl_format', wpl_global::get_full_url()))); ?>',
            data: request_str,
            dataType: 'JSON',
            cache: false,
            success: function(data) {},
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    <?php if (wpl_global::check_addon('PRO')) : ?>

        function wpl_kind_selected(kind) {
            // Change columns count default value
            var listing_columns = <?php wpl_esc::numeric($this->settings['wpl_ui_customizer_property_listing_columns'] ?? 3); ?>;
            var neighborhood_columns = <?php wpl_esc::numeric($this->settings['wpl_ui_customizer_neighborhood_columns'] ?? 3); ?>;

            if (kind === '4') wpl_columns_count_default(neighborhood_columns);
            else wpl_columns_count_default(listing_columns);
        }

        function wpl_columns_count_default(count) {
            wplj("#pr_columns_count_selectbox option").removeAttr("selected");
            wplj("#pr_columns_count_selectbox option[value=" + count + "]").attr("selected", "selected");
        }
    <?php endif; ?>

    // Mortgage Calculator
    wplj("#wpl_o_down_payment_type").change();
    wplj("#wpl_o_tax_type").change();
    wplj("#wpl_o_insurance_type").change();

    function wpl_mcalc_onchange_input(sender) {
        var input_id = wplj(sender).attr("id");
        var input_val = wplj(sender).val();

        if (input_id == 'wpl_o_down_payment_type') {
            wpl_toggle_amt_percent_inputs('dp', input_val);
        } else if (input_id == 'wpl_o_tax_type') {
            wpl_toggle_amt_percent_inputs('tax', input_val);
        } else if (input_id == 'wpl_o_insurance_type') {
            wpl_toggle_amt_percent_inputs('ins', input_val);
        }
    }

    function wpl_toggle_amt_percent_inputs(opt, selected) {
        if (opt == 'dp') {
            amt_span = "wpl_span_down_payment_amt";
            percent_span = "wpl_span_down_payment_percent";
        } else if (opt == 'tax') {
            amt_span = "wpl_span_tax_amt";
            percent_span = "wpl_span_tax_percent";
        } else if (opt == 'ins') {
            amt_span = "wpl_span_insurance_amt";
            percent_span = "wpl_span_insurance_percent";
        }

        wplj("#" + amt_span + ", #" + percent_span).hide();

        if (selected == '1') {
            wplj("#" + amt_span).show();
        } else {
            wplj("#" + percent_span).show();
        }
    }
</script>