<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('widgets.googlemap.main');

/**
 * Google Maps Widget Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL Core
 */
class wpl_page_builders_vc_widget_googlemap
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        // VC Widget Shortcode
        add_shortcode('wpl_vc_googlemap_widget', array($this, 'shortcode_callback'));
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('WPL Google Maps Widget'),
            'description' =>wpl_esc::return_t('WPL Google Maps Widget'),
            'base' => 'wpl_vc_googlemap_widget',
            'class' => '',
            'controls' => 'full',
            'icon' => 'wpb-wpl-icon',
            'category' =>wpl_esc::return_t('WPL'),
            'params' => $this->get_fields()
        ));
	}
    
    public function get_fields()
    {
        $googlemap = new wpl_googlemap_widget();
        
        // Module Fields
        $fields = array();
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Title'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'title',
            'value'           => '',
            'admin_label'     => true,
            'description'     => wpl_esc::return_html_t('The widget title'),
        );
        
        $widget_layouts = $googlemap->get_layouts('googlemap');
        
        $widget_layouts_options = array();
        foreach($widget_layouts as $widget_layout) $widget_layouts_options[wpl_esc::return_html_t(ucfirst(str_replace('.php', '', $widget_layout)))] = str_replace('.php', '', $widget_layout);
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Layout'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'tpl',
            'value'           => $widget_layouts_options,
            'std'             => '',
            'description'     => wpl_esc::return_html_t('Layout of the widget'),
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('CSS Class'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'css_class',
            'value'           => '',
        );
        
		return $fields;
	}
    
    public function shortcode_callback($atts)
    {
        ob_start();
        
        $googlemap = new wpl_googlemap_widget();
        $googlemap->widget(array(
            'before_widget'=>'',
            'after_widget'=>'',
            'before_title'=>'',
            'after_title'=>'',
        ),
        array
        (
            'title'=>isset($atts['title']) ? $atts['title'] : '',
            'layout'=>isset($atts['tpl']) ? $atts['tpl'] : '',
            'data'=>array(
                'css_class'=>isset($atts['css_class']) ? $atts['css_class'] : '',
            )
        ));
        
        return ob_get_clean();
    }
}