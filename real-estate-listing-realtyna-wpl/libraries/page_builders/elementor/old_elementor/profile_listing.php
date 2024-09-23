<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Listing Shortcode for Elementor
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_elementor_profile_listing extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wpl_profile_listing';
    }

    public function get_title()
    {
        return wpl_esc::return_t('Profile/Agent Listing');
    }

    public function get_icon()
    {
        return 'fa fa-users';
    }

    public function get_categories()
    {
        return array('wpl');
    }

    protected function _register_controls()
    {
        // Global WPL Settings
        $wpl_settings = wpl_global::get_settings();

        $this->start_controls_section('filter_section', array(
            'label' => wpl_esc::return_t('Filter'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // User Type options
        $user_types = wpl_users::get_user_types();

        $user_types_options = array();
        $user_types_options[''] = wpl_esc::return_html_t('Any');

        foreach($user_types as $user_type) $user_types_options[$user_type->id] = wpl_esc::return_html_t($user_type->name);

        $this->add_control('sf_select_membership_type', array(
            'label' => wpl_esc::return_html_t('User Type'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $user_types_options,
        ));

        // Membership Options
        $memberships = wpl_users::get_wpl_memberships();

        $memberships_options = array();
        $memberships_options[''] = wpl_esc::return_html_t('Any');

        foreach($memberships as $membership) $memberships_options[$membership->id] = wpl_esc::return_html_t($membership->membership_name);

        $this->add_control('sf_select_membership_id', array(
            'label' => wpl_esc::return_html_t('Membership'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $memberships_options,
        ));

        $this->end_controls_section();

        $this->start_controls_section('display_section', array(
            'label' => wpl_esc::return_html_t('Display'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // Layouts Options
        $layouts = wpl_global::get_layouts('profile_listing', array('message.php'), 'frontend');

        $layouts_options = array();
        foreach($layouts as $layout) $layouts_options[$layout] = wpl_esc::return_html_t($layout);

        $this->add_control('tpl', array(
            'label' => wpl_esc::return_html_t('Layout'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $layouts_options,
        ));

        // Target Options
        $pages = wpl_global::get_wp_pages();

        $pages_options = array();
        $pages_options[''] = wpl_esc::return_html_t('Any');
        
        foreach($pages as $page) $pages_options[$page->ID] = wpl_esc::return_html_t($page->post_title);

        $this->add_control('wpltarget', array(
            'label' => wpl_esc::return_html_t('Target Page'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $pages_options,
            'description' => wpl_esc::return_html_t("You don't need to select a target page in most of cases."),
        ));

        // Page Size Options
        $page_sizes = explode(',', trim($wpl_settings['page_sizes'] ?? '', ', '));

        $page_sizes_options = array();
        foreach($page_sizes as $page_size) $page_sizes_options[$page_size] = wpl_esc::return_html_t($page_size);

        $this->add_control('limit', array(
            'label' => wpl_esc::return_html_t('Page Size'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $page_sizes_options,
        ));

        // Pagination
        $this->add_control('wplpagination', array(
            'label' => wpl_esc::return_html_t('Pagination'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                '' => '-----',
                'scroll' => wpl_esc::return_html_t('Scroll Pagination'),
            ),
        ));

        // Order Options
        $sorts = wpl_sort_options::render(wpl_sort_options::get_sort_options(2, 1));

        $sorts_options = array();
        foreach($sorts as $sort) $sorts_options[$sort['field_name']] = wpl_esc::return_html_t($sort['name']);

        $this->add_control('orderby', array(
            'label' => wpl_esc::return_html_t('Order By'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $sorts_options,
        ));

        // Order By Options
        $this->add_control('order', array(
            'label' => wpl_esc::return_html_t('Order'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                'ASC' => wpl_esc::return_html_t('Ascending'),
                'DESC' => wpl_esc::return_html_t('Descending'),
            ),
        ));

        // Columns Options
        $this->add_control('wplcolumns', array(
            'label' => wpl_esc::return_html_t('Columns Count'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ),
            'description' => wpl_esc::return_html_t("Number of items per row."),
        ));

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $atts = '';
        foreach($settings as $key=>$value)
        {
            if(!in_array($key, array('tpl', 'sf_select_membership_type', 'sf_select_membership_id', 'wpltarget', 'limit', 'wplpagination', 'orderby', 'order', 'wplcolumns')) or trim($value ?? '') == '') continue;
            $atts .= $key.'="'.$value.'" ';
        }

		wpl_esc::e(do_shortcode('[wpl_profile_listing '.trim($atts ?? '').']'));
    }
}