<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Property Show Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_vc_property_show
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('Property Show'),
            'description' =>wpl_esc::return_t('Property Details Pages.'),
            'base' => "wpl_property_show",
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
        
        $layouts = wpl_global::get_layouts('property_show', array('message.php'), 'frontend');
        
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
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Listing ID'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'mls_id',
            'value'           => '',
            'admin_label'     => true,
            'description'     => wpl_esc::return_html_t('Insert the Listing ID that you want to show'),
        );

		return $fields;
	}
}