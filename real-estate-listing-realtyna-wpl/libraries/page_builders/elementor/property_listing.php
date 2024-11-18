<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.sort_options');

/**
 * Property Listing Shortcode for Elementor
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_elementor_property_listing extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wpl_property_listing';
    }

    public function get_title()
    {
        return wpl_esc::return_t('Property Listing');
    }

    public function get_icon()
    {
        return 'fa fa-list';
    }

    public function get_categories()
    {
        return array('wpl');
    }

    protected function register_controls()
    {
        // Global WPL Settings
        $wpl_settings = wpl_global::get_settings();

        $this->start_controls_section('filter_section', array(
            'label' =>wpl_esc::return_t('Filter'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // Kind Options
        $kinds = wpl_flex::get_kinds('wpl_properties');

        $kinds_options = array();
        foreach($kinds as $kind) $kinds_options[$kind['id']] = wpl_esc::return_html_t($kind['name']);

        $this->add_control('kind', array(
            'label' => wpl_esc::return_html_t('Kind'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $kinds_options,
            'default' => 0,
        ));

        // Listing Options
        $listings = wpl_global::get_listings();

        $listings_options = array();
        $listings_options[''] = wpl_esc::return_html_t('Any');

        foreach($listings as $listing) $listings_options[$listing['id']] = wpl_esc::return_html_t($listing['name']);

        $this->add_control('sf_select_listing', array(
            'label' => wpl_esc::return_html_t('Listing Type'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $listings_options,
        ));

        $property_types = wpl_global::get_property_types();

        $property_types_options = array();
        $property_types_options[''] = wpl_esc::return_html_t('Any');
        foreach($property_types as $property_type) $property_types_options[$property_type['id']] = wpl_esc::return_html_t($property_type['name']);

        $this->add_control('sf_select_property_type', array(
            'label' => wpl_esc::return_html_t('Property Type'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $property_types_options,
        ));

        // Location Text
        $location_settings = wpl_global::get_settings('3'); # location settings

        $this->add_control('sf_locationtextsearch', array(
            'label' => wpl_esc::return_html_t('Location'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => wpl_esc::return_html_t($location_settings['locationzips_keyword'].', '.$location_settings['location3_keyword'].', '.$location_settings['location1_keyword']),
        ));

        // Price Options
        $units = wpl_units::get_units(4);

        $default_unit = NULL;
        $price_unit_options = array();

        $p = 1;
        foreach($units as $unit)
        {
            if($p == 1) $default_unit = $unit['id'];

            $price_unit_options[$unit['id']] = wpl_esc::return_html_t($unit['name']);
            $p++;
        }

        $this->add_control('sf_min_price', array(
            'label' => wpl_esc::return_html_t('Price (Min)'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => '',
        ));

        $this->add_control('sf_max_price', array(
            'label' => wpl_esc::return_html_t('Price (Max)'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => '',
        ));

        $this->add_control('sf_unit_price', array(
            'label' => wpl_esc::return_html_t('Price Unit'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $price_unit_options,
            'default' => $default_unit,
        ));

        // Tags Options
        $tags = wpl_flex::get_tag_fields(0);
        foreach($tags as $tag)
        {
            $this->add_control('sf_select_'.$tag->table_column, array(
                'label' => wpl_esc::return_html_t($tag->name),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '-1' => wpl_esc::return_html_t('Any'),
                    '0' => wpl_esc::return_html_t('No'),
                    '1' => wpl_esc::return_html_t('Yes'),
                ),
            ));
        }

        // Users Options
        $wpl_users = wpl_users::get_wpl_users();

        $wpl_users_options = array();
        $wpl_users_options[''] = wpl_esc::return_html_t('Any');
        foreach($wpl_users as $wpl_user) $wpl_users_options[$wpl_user->ID] = wpl_esc::return_html_t($wpl_user->user_login.((trim($wpl_user->first_name ?? '') != '' or trim($wpl_user->last_name ?? '') != '') ? ' ('.$wpl_user->first_name.' '.$wpl_user->last_name.')' : ''));

        $this->add_control('sf_select_user_id', array(
            'label' => wpl_esc::return_html_t('User'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $wpl_users_options,
        ));

		do_action('wpl_page_builders_elementor_property_listing/register_controls/filter_section', $this);
        $this->end_controls_section();

        $this->start_controls_section('display_section', array(
            'label' => wpl_esc::return_html_t('Display'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // Layouts Options
        $layouts = wpl_global::get_layouts('property_listing', array('message.php'), 'frontend');

        $layouts_options = array();
        $layouts_options[''] = wpl_esc::return_html_t('-----');
        foreach($layouts as $layout) $layouts_options[$layout] = wpl_esc::return_html_t($layout);

        $this->add_control('tpl', array(
            'label' => wpl_esc::return_html_t('Layout'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $layouts_options,
        ));

        // Target Options
        $pages = wpl_global::get_wp_pages();

        $pages_options = array();
        $pages_options[''] = wpl_esc::return_html_t('-----');
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
        $sorts = wpl_sort_options::render(wpl_sort_options::get_sort_options(0, 1));

        $sorts_options = array();
        foreach($sorts as $sort) $sorts_options[$sort['field_name']] = wpl_esc::return_html_t($sort['name']);

        $this->add_control('wplorderby', array(
            'label' => wpl_esc::return_html_t('Order By'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $sorts_options,
        ));

        // Order By Options
        $this->add_control('wplorder', array(
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

		do_action('wpl_page_builders_elementor_property_listing/register_controls/display_section', $this);
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $atts = '';
        foreach($settings as $key=>$value)
        {
            $possible_fields = array('tpl', 'kind', 'sf_select_listing', 'sf_select_property_type', 'sf_locationtextsearch', 'sf_min_price', 'sf_max_price', 'sf_unit_price', 'sf_select_user_id', 'wpltarget', 'limit', 'wplpagination', 'wplorderby', 'wplorder', 'wplcolumns');

            $tags = wpl_flex::get_tag_fields(0);
            foreach($tags as $tag) $possible_fields[] = 'sf_select_'.$tag->table_column;

            if(!in_array($key, $possible_fields) or trim($value ?? '') == '') continue;
            $atts .= $key.'="'.$value.'" ';
        }

		wpl_esc::e(do_shortcode('[WPL '.trim($atts).']'));
    }
}