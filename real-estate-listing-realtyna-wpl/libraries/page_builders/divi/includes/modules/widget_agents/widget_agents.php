<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('widgets.agents.main');

/**
 * Agents Widget Shortcode for Divi Builder
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_divi_widget_agents extends ET_Builder_Module
{
    public $fields_defaults;
    public $settings;
    public $vb_support = 'on';

    public function init()
    {
        $this->name =wpl_esc::return_t('WPL Agents Widget');
        $this->slug = 'et_pb_wpl_widget_agents';
		$this->fields_defaults = array('image_width'=>230, 'image_height'=>230);

        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
	}

    public function get_fields()
    {
        $agents = new wpl_agents_widget();

        // Module Fields
        $fields = array();

        $fields['title'] = array(
            'label'           => wpl_esc::return_html_t('Title'),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => wpl_esc::return_html_t('The widget title'),
        );

        $widget_layouts = $agents->get_layouts('agents');

        $widget_layouts_options = array();
        foreach($widget_layouts as $widget_layout) $widget_layouts_options[str_replace('.php', '', $widget_layout)] = wpl_esc::return_html_t(ucfirst(str_replace('.php', '', $widget_layout)));

        $fields['tpl'] = array(
            'label'           => wpl_esc::return_html_t('Layout'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $widget_layouts_options,
        );

        $fields['style'] = array(
            'label'           => wpl_esc::return_html_t('Style'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
                '1' => wpl_esc::return_html_t('Horizontal'),
                '2' => wpl_esc::return_html_t('Vertical'),
            ),
        );

        $pages = wpl_global::get_wp_pages();

        $pages_options = array();
        $pages_options['-1'] = '-----';

        foreach($pages as $page) $pages_options[$page->ID] = wpl_esc::return_html_t($page->post_title);

        $fields['wpltarget'] = array(
            'label'           => wpl_esc::return_html_t('Target Page'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $pages_options,
        );

        $fields['css_class'] = array(
            'label'           => wpl_esc::return_html_t('CSS Class'),
            'type'            => 'text',
            'option_category' => 'basic_option',
        );

        $fields['image_width'] = array(
            'label'           => wpl_esc::return_html_t('Image Width'),
            'type'            => 'text',
            'option_category' => 'basic_option',
        );

        $fields['image_height'] = array(
            'label'           => wpl_esc::return_html_t('Image Height'),
            'type'            => 'text',
            'option_category' => 'basic_option',
        );

        $fields['mailto_status'] = array(
            'label'           => wpl_esc::return_html_t('Mailto Status'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
                '0' => wpl_esc::return_html_t('No'),
                '1' => wpl_esc::return_html_t('Yes'),
            ),
        );

        if(wpl_global::check_addon('pro'))
        {
            $membership_types = wpl_users::get_user_types();

            $membership_types_options = array();
            $membership_types_options['-1'] = wpl_esc::return_html_t('All');

            foreach($membership_types as $membership_type) $membership_types_options[$membership_type->id] = wpl_esc::return_html_t($membership_type->name);

            $fields['user_type'] = array(
                'label'           => wpl_esc::return_html_t('User Type'),
                'type'            => 'select',
                'default'         => '-1',
                'option_category' => 'basic_option',
                'options'         => $membership_types_options,
            );

            $memberships = wpl_users::get_wpl_memberships();

            $memberships_options = array();
            $memberships_options['-1'] = wpl_esc::return_html_t('All');

            foreach($memberships as $membership) $memberships_options[$membership->id] = wpl_esc::return_html_t($membership->membership_name);

            $fields['membership'] = array(
                'label'           => wpl_esc::return_html_t('Membership'),
                'type'            => 'select',
                'default'         => '-1',
                'option_category' => 'basic_option',
                'options'         => $memberships_options,
            );
        }

        $fields['user_ids'] = array(
            'label'           => wpl_esc::return_html_t('User IDs'),
            'type'            => 'text',
            'option_category' => 'basic_option',
        );

        $fields['random'] = array(
            'label'           => wpl_esc::return_html_t('Random'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
                '0' => wpl_esc::return_html_t('No'),
                '1' => wpl_esc::return_html_t('Yes'),
            ),
        );

        $sort_options = wpl_sort_options::render(wpl_sort_options::get_sort_options(2));

        $sort_options_options = array();
        foreach($sort_options as $sort_option) $sort_options_options[urlencode($sort_option['field_name'])] = wpl_esc::return_html_t($sort_option['name']);

        $fields['orderby'] = array(
            'label'           => wpl_esc::return_html_t('Order By'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $sort_options_options,
        );

        $fields['order'] = array(
            'label'           => wpl_esc::return_html_t('Order'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
                'ASC' => wpl_esc::return_html_t('ASC'),
                'DESC' => wpl_esc::return_html_t('DESC'),
            ),
        );

        $fields['limit'] = array(
            'label'           => wpl_esc::return_html_t('Limit'),
            'type'            => 'text',
            'option_category' => 'basic_option',
        );

		return $fields;
	}

    public function render($atts, $content = NULL, $function_name = NULL)
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