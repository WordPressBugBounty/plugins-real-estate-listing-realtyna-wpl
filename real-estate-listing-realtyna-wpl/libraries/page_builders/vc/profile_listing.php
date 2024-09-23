<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Listing Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_vc_profile_listing
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('Profile Listing'),
            'description' =>wpl_esc::return_t('Profile Listing Pages.'),
            'base' => "wpl_profile_listing",
            'class' => '',
            'controls' => 'full',
            'icon' => 'wpb-wpl-icon',
            'category' =>wpl_esc::return_t('WPL'),
            'params' => $this->get_fields()
        ));
	}
    
    public function get_fields()
    {
        // Module Fields
        $fields = array();
        
        $layouts = wpl_global::get_layouts('profile_listing', array('message.php'), 'frontend');
        
        $layouts_options = array();
        foreach($layouts as $layout) $layouts_options[wpl_esc::return_html_t($layout)] = $layout;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Layout'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'tpl',
            'value'           => $layouts_options,
            'std'             => '',
            'description'     => wpl_esc::return_html_t('Layout of the page'),
        );
        
        $user_types = wpl_users::get_user_types();
        
        $user_types_options = array();
        $user_types_options['-----'] = '';
        
        foreach($user_types as $user_type) $user_types_options[wpl_esc::return_html_t($user_type->name)] = $user_type->id;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('User Type'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_select_membership_type',
            'value'           => $user_types_options,
            'std'             => '',
            'admin_label'     => true,
            'description'     => wpl_esc::return_html_t('You can select different user type for filtering the users'),
        );
        
        $memberships = wpl_users::get_wpl_memberships();
        
        $memberships_options = array();
        $memberships_options['-----'] = '';
        
        foreach($memberships as $membership) $memberships_options[wpl_esc::return_html_t($membership->membership_name)] = $membership->id;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Membership'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'sf_select_membership_id',
            'value'           => $memberships_options,
            'std'             => '',
            'description'     => wpl_esc::return_html_t('You can filter the users by their membership package'),
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
        foreach($sorts as $sort) $sorts_options[wpl_esc::return_html_t($sort['name'])] = $sort['field_name'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Order By'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'orderby',
            'value'           => $sorts_options,
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Order'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'order',
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

		return $fields;
	}
}