<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Search Widget for Elementor
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_elementor_search_widget extends \Elementor\Widget_Base
{
	public function get_name()
	{
		return 'wpl_search_widget';
	}

	public function get_title()
	{
		return wpl_esc::return_html_t('(WPL) Search');
	}

	public function get_icon()
	{
		return 'eicon-wordpress';
	}

	public function get_categories()
	{
        return array('wordpress');
	}

	protected function register_controls()
	{
        $widgets_list = wpl_widget::get_existing_widgets();

        $widgets_list_options = array();
        foreach($widgets_list as $sidebar=>$widgets)
        {
            if($sidebar == 'wp_inactive_widgets') continue;

            foreach($widgets as $widget)
            {
                if(strpos($widget['id'] ?? '', 'wpl_search_widget') === false) continue;

                $widgets_list_options[$widget['id']] = wpl_esc::return_html_t(ucwords(str_replace('_', ' ', $widget['id'] ?? '')));
            }
        }

        $this->start_controls_section('content_section', array(
            'label' => wpl_esc::return_html_t('Content'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        $this->add_control('wplid', array(
            'label' => wpl_esc::return_html_t('Widget'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $widgets_list_options,
            'description' => wpl_esc::return_html_t("You can configure your search widget in Appearance -> Widgets menu first and then select it here."),
        ));

        $this->end_controls_section();
    }

	protected function render()
	{
        $settings = $this->get_settings_for_display();

		wpl_esc::e(do_shortcode('[wpl_widget_instance id="'.$settings['wplid'].'"]'));
	}
}