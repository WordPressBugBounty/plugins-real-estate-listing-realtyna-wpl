<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('widgets.search.main');

/**
 * Search Widget Shortcode for Divi Builder
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_divi_widget_search extends ET_Builder_Module
{
    public $fields_defaults;
    public $settings;

    public $vb_support = 'on';
    
    public function init()
    {
        $this->name =wpl_esc::return_t('WPL Search Widget');
        $this->slug = 'et_pb_wpl_widget_search';
		$this->fields_defaults = array();

        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
	}

    public function get_fields()
    {
        // Module Fields
        $fields = array();

        $widgets_list = wpl_widget::get_existing_widgets();

        $widgets_list_options = array();
        $widgets_list_options['-1'] = '-----';

        foreach($widgets_list as $sidebar=>$widgets)
        {
            if($sidebar == 'wp_inactive_widgets') continue;

            foreach($widgets as $widget)
            {
                if(strpos($widget['id'] ?? '', 'wpl_search_widget') === false) continue;
                $widgets_list_options[$widget['id']] = wpl_esc::return_html_t(ucwords(str_replace('_', ' ', $widget['id'] ?? '')));
            }
        }

        $fields['id'] = array(
            'label'           => wpl_esc::return_html_t('Widget'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $widgets_list_options,
            'description'     => wpl_esc::return_html_t('Select your desired search widget to show. if there is no widget in the list, Please configure some in Appearance->Widgets menu. You can put them inside of WPL Hidden sidebar.'),
        );

		return $fields;
	}

    public function render($atts, $content = NULL, $function_name = NULL)
    {
        $shortcode_atts = '';
        foreach($atts as $key=>$value)
        {
            if(trim($value ?? '') == '' or $value == '-1') continue;

            $shortcode_atts .= $key.'="'.$value.'" ';
        }

        return do_shortcode('[wpl_widget_instance'.(trim($shortcode_atts) ? ' '.trim($shortcode_atts) : '').']');
    }
}