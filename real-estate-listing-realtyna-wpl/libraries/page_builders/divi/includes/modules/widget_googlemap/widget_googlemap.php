<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Tags Widget Shortcode for Divi Builder
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_divi_widget_googlemap extends ET_Builder_Module
{
    public $vb_support = 'on';

    public function init()
    {
        $this->name =wpl_esc::return_t('WPL Google Maps Widget');
        $this->slug = 'et_pb_wpl_widget_googlemap';
		$this->fields_defaults = array();

        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
	}

    public function get_fields()
    {
        $googlemap = new wpl_googlemap_widget();

        // Module Fields
        $fields = array();

        $fields['title'] = array(
            'label'           => wpl_esc::return_html_t('Title'),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => wpl_esc::return_html_t('The widget title'),
        );

        $widget_layouts = $googlemap->get_layouts('googlemap');

        $widget_layouts_options = array();
        foreach($widget_layouts as $widget_layout) $widget_layouts_options[str_replace('.php', '', $widget_layout)] = wpl_esc::return_html_t(ucfirst(str_replace('.php', '', $widget_layout)));

        $fields['tpl'] = array(
            'label'           => wpl_esc::return_html_t('Layout'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $widget_layouts_options,
        );

        $fields['css_class'] = array(
            'label'           => wpl_esc::return_html_t('CSS Class'),
            'type'            => 'text',
            'option_category' => 'basic_option',
        );

		return $fields;
	}

    public function render($atts, $content = NULL, $function_name = NULL)
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