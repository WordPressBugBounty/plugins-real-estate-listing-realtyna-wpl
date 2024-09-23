<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Wizard Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_vc_profile_wizard
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('My Profile'),
            'description' =>wpl_esc::return_t('Profile Wizard Pages.'),
            'base' => "wpl_my_profile",
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

		return $fields;
	}
}