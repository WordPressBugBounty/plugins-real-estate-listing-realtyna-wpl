<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('widgets.agents.main');

/**
 * Agents Widget Shortcode for VC
 * @author Howard <howard@realtyna.com>
 * @package WPL Core
 */
class wpl_page_builders_vc_widget_agents
{
    public $settings;

    public function __construct()
    {
        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
        
        // VC Widget Shortcode
        add_shortcode('wpl_vc_agents_widget', array($this, 'shortcode_callback'));
        
        vc_map(array
        (
            'name' =>wpl_esc::return_t('WPL Agents Widget'),
            'description' =>wpl_esc::return_t('WPL Agents Widget'),
            'base' => 'wpl_vc_agents_widget',
            'class' => '',
            'controls' => 'full',
            'icon' => 'wpb-wpl-icon',
            'category' =>wpl_esc::return_t('WPL'),
            'params' => $this->get_fields()
        ));
	}
    
    public function get_fields()
    {
        $agents = new wpl_agents_widget();
        
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
        
        $widget_layouts = $agents->get_layouts('agents');
        
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
            'heading'         => wpl_esc::return_html_t('Style'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'style',
            'value'           => array(
                wpl_esc::return_html_t('Horizontal') => '1',
                wpl_esc::return_html_t('Vertical') => '2',
            ),
            'std'             => '',
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
            'heading'         => wpl_esc::return_html_t('CSS Class'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'css_class',
            'value'           => '',
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Image Width'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'image_width',
            'value'           => '230',
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Image Height'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'image_height',
            'value'           => '230',
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Mailto Status'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'mailto_status',
            'value'           => array(
                wpl_esc::return_html_t('No') => '0',
                wpl_esc::return_html_t('Yes') => '1',
            ),
            'std'             => '',
        );
        
        if(wpl_global::check_addon('pro'))
        {
            $membership_types = wpl_users::get_user_types();
        
            $membership_types_options = array();
            $membership_types_options['-----'] = '';

            foreach($membership_types as $membership_type) $membership_types_options[wpl_esc::return_html_t($membership_type->name)] = $membership_type->id;

            $fields[] = array(
                'heading'         => wpl_esc::return_html_t('User Type'),
                'type'            => 'dropdown',
                'value'           => $membership_types_options,
                'holder'          => 'div',
                'class'           => '',
                'param_name'      => 'user_type',
                'std'             => '',
                'admin_label'     => true,
            );
            
            $memberships = wpl_users::get_wpl_memberships();
        
            $memberships_options = array();
            $memberships_options['-----'] = '';

            foreach($memberships as $membership) $memberships_options[wpl_esc::return_html_t($membership->membership_name)] = $membership->id;

            $fields[] = array(
                'heading'         => wpl_esc::return_html_t('Membership'),
                'type'            => 'dropdown',
                'value'           => $memberships_options,
                'holder'          => 'div',
                'class'           => '',
                'param_name'      => 'membership',
                'std'             => '',
            );
        }
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('User IDs'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'user_ids',
            'value'           => '',
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Random'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'random',
            'value'           => array(
                wpl_esc::return_html_t('No') => '0',
                wpl_esc::return_html_t('Yes') => '1',
            ),
            'std'             => '',
        );
        
        $sorts = wpl_sort_options::render(wpl_sort_options::get_sort_options(2));
        
        $sorts_options = array();
        foreach($sorts as $sort) $sorts_options[wpl_esc::return_html_t($sort['name'])] = $sort['field_name'];
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Order By'),
            'type'            => 'dropdown',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'orderby',
            'value'           => $sorts_options,
            'std'             => ''
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
            'std'             => '',
        );
        
        $fields[] = array(
            'heading'         => wpl_esc::return_html_t('Limit'),
            'type'            => 'textfield',
            'holder'          => 'div',
            'class'           => '',
            'param_name'      => 'limit',
            'value'           => '6',
        );
        
		return $fields;
	}
    
    public function shortcode_callback($atts)
    {
        ob_start();
        
        $agents = new wpl_agents_widget();
        $agents->widget(array(
            'before_widget'=>'',
            'after_widget'=>'',
            'before_title'=>'',
            'after_title'=>'',
        ),
        array
        (
            'title'=>isset($atts['title']) ? $atts['title'] : '',
            'layout'=>isset($atts['tpl']) ? $atts['tpl'] : '',
            'wpltarget'=>isset($atts['wpltarget']) ? $atts['wpltarget'] : '',
            'data'=>array(
                'style'=>isset($atts['style']) ? $atts['style'] : 1,
                'css_class'=>isset($atts['css_class']) ? $atts['css_class'] : '',
                'image_width'=>isset($atts['image_width']) ? $atts['image_width'] : 230,
                'image_height'=>isset($atts['image_height']) ? $atts['image_height'] : 230,
                'mailto_status'=>isset($atts['mailto_status']) ? $atts['mailto_status'] : '',
                'user_type'=>isset($atts['user_type']) ? $atts['user_type'] : NULL,
                'membership'=>isset($atts['membership']) ? $atts['membership'] : NULL,
                'user_ids'=>isset($atts['user_ids']) ? $atts['user_ids'] : '',
                'random'=>isset($atts['random']) ? $atts['random'] : '',
                'orderby'=>isset($atts['orderby']) ? $atts['orderby'] : '',
                'order'=>isset($atts['order']) ? $atts['order'] : '',
                'limit'=>isset($atts['limit']) ? $atts['limit'] : 6,
            )
        ));
        
        return ob_get_clean();
    }
}