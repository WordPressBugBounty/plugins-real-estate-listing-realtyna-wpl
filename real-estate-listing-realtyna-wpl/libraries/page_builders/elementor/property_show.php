<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Property Show Shortcode for Elementor
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_elementor_property_show extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wpl_property_show';
    }

    public function get_title()
    {
        return wpl_esc::return_t('Property Show');
    }

    public function get_icon()
    {
        return 'fa fa-home';
    }

    public function get_categories()
    {
        return array('wpl');
    }

    protected function register_controls()
    {
        $this->start_controls_section('filter_section', array(
            'label' =>wpl_esc::return_t('Filter'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        $this->add_control('mls_id', array(
            'label' => wpl_esc::return_html_t('Listing ID'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => wpl_esc::return_html_t("Insert the Listing ID that you want to show."),
        ));

        $this->end_controls_section();

        $this->start_controls_section('display_section', array(
            'label' =>wpl_esc::return_t('Display'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // Layouts Options
        $layouts = wpl_global::get_layouts('property_show', array('message.php'), 'frontend');

        $layouts_options = array();
        foreach($layouts as $layout) $layouts_options[$layout] = wpl_esc::return_html_t($layout);

        $this->add_control('tpl', array(
            'label' => wpl_esc::return_html_t('Layout'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $layouts_options,
        ));

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $atts = '';
        foreach($settings as $key=>$value)
        {
            if(!in_array($key, array('tpl', 'mls_id')) or trim($value ?? '') == '') continue;
            $atts .= $key.'="'.$value.'" ';
        }

		wpl_esc::e(do_shortcode('[wpl_property_show '.trim($atts).']'));
    }
}