<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.activities');

class wpl_links_widget extends wpl_widget
{
    public $wpl_tpl_path = 'widgets.links.tmpl';
    public $wpl_backend_form = 'widgets.links.form';
    public $layout;
    public $register_link;
    public $login_link;
    public $forget_password_link;
    public $compare_url;
    public $compare_link;
    public $dashboard_link;
    public $favorite_link;
    public $favorite_url;
    public $save_search_link;
    public $save_search_url;

    /**
     * @var wpl_addon_membership
     */
    public $membership;

    public function __construct()
    {
        parent::__construct('wpl_links_widget', wpl_esc::return_html_t('(WPL) Links'), array('description'=>wpl_esc::return_html_t('Let you to show Register/Login/Forgot Password Links.')));
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $this->widget_id = $this->number;
        if($this->widget_id < 0) $this->widget_id = abs($this->widget_id)+1000;

        // Fix Widget ID in some cases
        if($this->widget_id === false) $this->widget_id = mt_rand(100, 999);
        
        $this->widget_uq_name = 'wplus'.$this->widget_id;
        $this->instance = $instance;

        wpl_esc::e($args['before_widget']);

        // Widget Title
		wpl_esc::e($args['before_title'].apply_filters('widget_title', $instance['title']).$args['after_title']);

        $this->data = $instance['data'];
        $this->css_class = $this->data['css_class'] ?? '';
        $this->layout = $instance['layout'] ?? 'default';
        $this->register_link = $instance['register_link'] ?? '1';
        $this->login_link = $instance['login_link'] ?? '1';
        $this->forget_password_link = $instance['forget_password_link'] ?? '1';
        $this->dashboard_link = $instance['dashboard_link'] ?? '1';

        $this->compare_link = $instance['compare_link'] ?? '1';
        if(wpl_global::check_addon('pro')) $this->compare_url = wpl_addon_pro::compare_get_url();

        $this->favorite_link = $instance['favorite_link'] ?? '1';
        $this->save_search_link = $instance['save_search_link'] ?? '1';

        if(wpl_global::check_addon('membership'))
        {
            $this->membership = new wpl_addon_membership();

            if(wpl_global::check_addon('save_searches')) $this->save_search_url = $this->membership->URL('searches');
            if(wpl_global::check_addon('pro')) $this->favorite_url = $this->membership->URL('favorites');
        }

        $layout = 'widgets.links.tmpl.default';
        $layout = _wpl_import($layout, true, true);
        
        if(wpl_file::exists($layout)) require $layout;
        else wpl_esc::html_t('Widget Layout Not Found!');

		wpl_esc::e($args['after_widget']);
    }

    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        $this->widget_id = $this->number;
        
        /** Set up some default widget settings. **/
        if(!isset($instance['layout']))
        {
            $instance = array('title'=>wpl_esc::return_html_t('Links'), 'layout'=>'default',
                'data'=>array(
                    'css_class'=>'',
            ));

			$defaults = array();
            $instance = wp_parse_args((array) $instance, $defaults);
        }
        
        $path = _wpl_import($this->wpl_backend_form, true, true);

        ob_start();
        include $path;
		wpl_esc::e(ob_get_clean());
    }

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title'] ?? '');
        $instance['layout'] = $new_instance['layout'];
        $instance['register_link'] = $new_instance['register_link'];
        $instance['login_link'] = $new_instance['login_link'];
        $instance['forget_password_link'] = $new_instance['forget_password_link'];
        $instance['dashboard_link'] = $new_instance['dashboard_link'];
        $instance['compare_link'] = $new_instance['compare_link'];
        $instance['favorite_link'] = $new_instance['favorite_link'];
        $instance['save_search_link'] = $new_instance['save_search_link'];
        $instance['data'] = (array) $new_instance['data'];

        return $instance;
    }
}