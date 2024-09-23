<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Show Shortcode for Elementor
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_elementor_profile_show extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wpl_profile_show';
    }

    public function get_title()
    {
        return wpl_esc::return_t('Profile/Agent Show');
    }

    public function get_icon()
    {
        return 'fa fa-user';
    }

    public function get_categories()
    {
        return array('wpl');
    }

    protected function _register_controls()
    {
        $this->start_controls_section('filter_section', array(
            'label' =>wpl_esc::return_t('Filter'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // Users Options
        $wpl_users = wpl_users::get_wpl_users();

        $wpl_users_options = array();
        foreach($wpl_users as $wpl_user){
			
			$usr = ( trim( $wpl_user->first_name ?? '' ) != '' or trim( $wpl_user->last_name ?? '' ) != '' ) ? ' ('.  $wpl_user->first_name . ' ' . $wpl_user->last_name . ')' : '' ;
			$wpl_users_options[$wpl_user->ID] = wpl_esc::return_html_t( $wpl_user->user_login.$usr);
		}

        $this->add_control('uid', array(
            'label' => wpl_esc::return_html_t('User'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $wpl_users_options,
            'description' => wpl_esc::return_html_t("Select an agent to show."),
        ));

        $this->end_controls_section();

        $this->start_controls_section('display_section', array(
            'label' => wpl_esc::return_html_t('Display'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ));

        // Layouts Options
        $layouts = wpl_global::get_layouts('profile_show', array('message.php'), 'frontend');

        $layouts_options = array();
        foreach($layouts as $layout) $layouts_options[$layout] = wpl_esc::return_html_t($layout);

        $this->add_control('tpl', array(
            'label' => wpl_esc::return_html_t('Layout'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $layouts_options,
        ));

        // Target Options
        $pages = wpl_global::get_wp_pages();

        $pages_options = array();
        $pages_options[''] = wpl_esc::return_html_t('Any');
        
        foreach($pages as $page) $pages_options[$page->ID] = wpl_esc::return_html_t($page->post_title);

        $this->add_control('wpltarget', array(
            'label' => wpl_esc::return_html_t('Target Page'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $pages_options,
            'description' => wpl_esc::return_html_t("You don't need to select a target page in most of cases."),
        ));

        // Pagination
        $this->add_control('wplpagination', array(
            'label' => wpl_esc::return_html_t('Pagination'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                '' => '-----',
                'scroll' => wpl_esc::return_html_t('Scroll Pagination'),
            ),
        ));

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $atts = '';
        foreach($settings as $key=>$value)
        {
            if(!in_array($key, array('uid', 'tpl', 'wpltarget', 'wplpagination')) or trim($value ?? '') == '') continue;
            $atts .= $key.'="'.$value.'" ';
        }

		wpl_esc::e(do_shortcode('[wpl_profile_show '.trim($atts ?? '').']'));
    }
}