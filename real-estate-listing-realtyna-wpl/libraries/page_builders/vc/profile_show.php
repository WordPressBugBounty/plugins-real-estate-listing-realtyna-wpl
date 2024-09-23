<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Show Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_vc_profile_show
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('Profile/Agent Show'),
            'description' =>wpl_esc::return_t('Profile/Agent Show Pages.'),
            'base' => "wpl_profile_show",
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
        
        $layouts = wpl_global::get_layouts('profile_show', array('message.php'), 'frontend');
        
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
        
        $wpl_users = wpl_users::get_wpl_users();
        
        $wpl_users_options = array();
        foreach($wpl_users as $wpl_user) $wpl_users_options[wpl_esc::return_html_t($wpl_user->user_login.((trim($wpl_user->first_name ?? '') != '' or trim($wpl_user->last_name ?? '') != '') ? ' ('.$wpl_user->first_name.' '.$wpl_user->last_name.')' : ''))] = $wpl_user->ID;
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('User'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'uid',
            'value'           => $wpl_users_options,
            'std'             => '',
            'admin_label'     => true,
            'description'     => wpl_esc::return_html_t('The agent to show'),
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

		return $fields;
	}
}