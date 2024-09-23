<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Property Listing Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_vc_property_listing
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('Property Listing'),
            'description' =>wpl_esc::return_t('Property Listing Pages. (only one per page)'),
            'base' => "WPL",
            'class' => '',
            'controls' => 'full',
            'icon' => 'wpb-wpl-icon',
            'show_settings_on_create' => true,
            'category' =>wpl_esc::return_t('WPL'),
            'params' => $this->get_fields()
        ));
	}
    
    public function get_fields()
    {
        // Module Fields
        $fields = array();
        
        $kinds = wpl_flex::get_kinds('wpl_properties');
        
        $kinds_options = array();
        $kinds_options['-----'] = '';
        foreach($kinds as $kind) $kinds_options[wpl_esc::return_html_t($kind['name'])] = $kind['id'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Kind'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'kind',
            'value'           => $kinds_options,
            'admin_label'     => true,
            'description'     => wpl_esc::return_html_t('Kind/Entity for filtering listings'),
        );
        
        $listings = wpl_global::get_listings();
        
        $listings_options = array();
        $listings_options['-----'] = '';
        
        foreach($listings as $listing) $listings_options[wpl_esc::return_html_t($listing['name'])] = $listing['id'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Listing Type'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_select_listing',
            'value'           => $listings_options,
            'std'             => '',
            'admin_label'     => false,
        );
        
        $property_types = wpl_global::get_property_types();
        
        $property_types_options = array();
        $property_types_options['-----'] = '';
        
        foreach($property_types as $property_type) $property_types_options[wpl_esc::return_html_t($property_type['name'])] = $property_type['id'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Property Type'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_select_property_type',
            'value'           => $property_types_options,
            'std'             => '',
        );
        
        $property_listing_layouts = wpl_global::get_layouts('property_listing', array('message.php'), 'frontend');
        
        $property_listing_layouts_options = array();
		$property_listing_layouts_options['-----'] = '';
        foreach($property_listing_layouts as $property_listing_layout) $property_listing_layouts_options[wpl_esc::return_html_t($property_listing_layout)] = $property_listing_layout;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Layout'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'tpl',
            'value'           => $property_listing_layouts_options,
            'std'             => '',
            'description'     => wpl_esc::return_html_t('Layout of the page'),
        );
        
        $location_settings = wpl_global::get_settings('3'); # location settings
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Location'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_locationtextsearch',
            'value'           => '',
            'description'     => wpl_esc::return_html_t($location_settings['locationzips_keyword'].', '.$location_settings['location3_keyword'].', '.$location_settings['location1_keyword']),
        );
        
        $units = wpl_units::get_units(4);
        
        $price_unit_options = array();
        $price_unit_options['-----'] = '';

        foreach($units as $unit) $price_unit_options[wpl_esc::return_html_t($unit['name'])] = $unit['id'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Price (Min)'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_min_price',
            'description'     => wpl_esc::return_html_t('Minimum Price of listings'),
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Price (Max)'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_max_price',
            'description'     => wpl_esc::return_html_t('Maximum Price of listings'),
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Price Unit'),
            'type'            => 'dropdown',
            'value'           => $price_unit_options,
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_unit_price',
            'description'     => wpl_esc::return_html_t('Price Unit'),
        );
        
        $tags = wpl_flex::get_tag_fields(0);
        foreach($tags as $tag)
        {
            $fields[] = array(
                'heading'         => wpl_esc::return_html_t($tag->name),
                'type'            => 'dropdown',
                'holder'          => 'div',
                'class'           => '',
                'param_name'      => 'sf_select_'.$tag->table_column,
                'value'           => array(
                    wpl_esc::return_html_t('Any') => '-1',
                    wpl_esc::return_html_t('No') => '0',
                    wpl_esc::return_html_t('Yes') => '1',
                ),
                'std'             => '-1',
            );
        }
        
        $wpl_users = wpl_users::get_wpl_users();
        
        $wpl_users_options = array();
        $wpl_users_options['-----'] = '';
        
        foreach($wpl_users as $wpl_user) $wpl_users_options[wpl_esc::return_html_t($wpl_user->user_login.((trim($wpl_user->first_name ?? '') != '' or trim($wpl_user->last_name ?? '') != '') ? ' ('.$wpl_user->first_name.' '.$wpl_user->last_name.')' : ''))] = $wpl_user->ID;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('User'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_select_user_id',
            'value'           => $wpl_users_options,
            'std'             => '',
            'description'     => wpl_esc::return_html_t('Filter the listings by a certain agent'),
        );
        
        $pages = wpl_global::get_wp_pages();
        
        $pages_options = array();
        $pages_options['-----'] = '';
        
        foreach($pages as $page) $pages_options[wpl_esc::return_html_t($page->post_title)] = $page->ID;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Target Page'),
            'type'            => 'dropdown',
            'value'           => $pages_options,
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'wpltarget',
            'std'             => '',
        );
        
        $page_sizes = explode(',', trim($this->settings['page_sizes'] ?? '', ', '));
        
        $page_sizes_options = array();
        $page_sizes_options['-----'] = '';
        foreach($page_sizes as $page_size) $page_sizes_options[wpl_esc::return_html_t($page_size)] = $page_size;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Page Size'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'limit',
            'value'           => $page_sizes_options,
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Pagination'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'wplpagination',
            'value'           => array(
                '-----' => '',
                wpl_esc::return_html_t('Scroll Pagination') => 'scroll',
            ),
            'std'             => '',
        );
        
        $sorts = wpl_sort_options::render(wpl_sort_options::get_sort_options(0, 1));
        
        $sorts_options = array();
        $sorts_options['-----'] = '';
        foreach($sorts as $sort) $sorts_options[wpl_esc::return_html_t($sort['name'])] = $sort['field_name'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Order By'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'wplorderby',
            'value'           => $sorts_options,
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Order'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'wplorder',
            'value'           => array(
                wpl_esc::return_html_t('Ascending') => 'ASC',
                wpl_esc::return_html_t('Descending') => 'DESC',
            ),
            'std'             => 'DESC',
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Columns Count'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'wplcolumns',
            'value'           => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '6' => '6',
            ),
            'std'             => '3',
        );

		$fields = apply_filters('wpl_page_builders_vc_property_listing/get_fields', $fields);

		return $fields;
	}
}