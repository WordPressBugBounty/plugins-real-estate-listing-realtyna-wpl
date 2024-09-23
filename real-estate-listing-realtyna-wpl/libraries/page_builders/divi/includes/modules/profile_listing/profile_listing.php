<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Profile Listing Shortcode for Divi Builder
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_divi_profile_listing extends ET_Builder_Module
{
    public $slug       = 'et_pb_wpl_profile_listing';
    public $vb_support = 'on';

    public function init()
    {
        $this->name =wpl_esc::return_t('Profile/Agent Listing');
        $this->slug = 'et_pb_wpl_profile_listing';

		$this->fields_defaults = array();

        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
	}

    public function get_fields()
    {
        // Module Fields
        $fields = array();

        $profile_listing_layouts = wpl_global::get_layouts('profile_listing', array('message.php'), 'frontend');

        $profile_listing_layouts_options = array();
        foreach($profile_listing_layouts as $profile_listing_layout) $profile_listing_layouts_options[$profile_listing_layout] = wpl_esc::return_html_t($profile_listing_layout);

        $fields['tpl'] = array(
            'label'           => wpl_esc::return_html_t('Layout'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $profile_listing_layouts_options,
            'description'     => wpl_esc::return_html_t('Layout of the page'),
        );

        $user_types = wpl_users::get_user_types();

        $user_types_options = array();
        $user_types_options['-1'] = '-----';

        foreach($user_types as $user_type) $user_types_options[$user_type->id] = wpl_esc::return_html_t($user_type->name);

        $fields['sf_select_membership_type'] = array(
            'label'           => wpl_esc::return_html_t('User Type'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $user_types_options,
            'description'     => wpl_esc::return_html_t('You can select different user type for filtering the users'),
        );

        $memberships = wpl_users::get_wpl_memberships();

        $memberships_options = array();
        $memberships_options['-1'] = '-----';

        foreach($memberships as $membership) $memberships_options[$membership->id] = wpl_esc::return_html_t($membership->membership_name);

        $fields['sf_select_membership_id'] = array(
            'label'           => wpl_esc::return_html_t('Membership'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $memberships_options,
            'description'     => wpl_esc::return_html_t('You can filter the users by their membership package'),
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

        $page_sizes = explode(',', trim($this->settings['page_sizes'] ?? '', ', '));

        $page_sizes_options = array();
        foreach($page_sizes as $page_size) $page_sizes_options[$page_size] = wpl_esc::return_html_t($page_size);

        $fields['limit'] = array(
            'label'           => wpl_esc::return_html_t('Page Size'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $page_sizes_options,
        );

        $fields['wplpagination'] = array(
            'label'           => wpl_esc::return_html_t('Pagination'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
                '' => '-----',
                'scroll' => wpl_esc::return_html_t('Scroll Pagination'),
            ),
        );

        $sorts = wpl_sort_options::render(wpl_sort_options::get_sort_options(2, 1));

        $sorts_options = array();
        foreach($sorts as $sort) $sorts_options[$sort['field_name']] = wpl_esc::return_html_t($sort['name']);

        $fields['orderby'] = array(
            'label'           => wpl_esc::return_html_t('Order By'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $sorts_options,
        );

        $fields['order'] = array(
            'label'           => wpl_esc::return_html_t('Order'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
                'ASC' => wpl_esc::return_html_t('Ascending'),
                'DESC' => wpl_esc::return_html_t('Descending'),
            ),
        );

        $fields['wplcolumns'] = array(
            'label'           => wpl_esc::return_html_t('Columns Count'),
            'type'            => 'select',
            'default'         => '3',
            'option_category' => 'basic_option',
            'options'         => array(
                '3' => 3,
                '1' => 1,
                '2' => 2,
                '4' => 4,
                '6' => 6,
            ),
        );

		return $fields;
	}

    public function render($atts, $content = NULL, $function_name = NULL)
    {
        $shortcode_atts = '';
        foreach($atts as $key=>$value)
        {
            if(trim($value ?? '') == '' or $value == '-1') continue;
            if($key == 'tpl' and $value == 'default') continue;

            $shortcode_atts .= $key.'="'.$value.'" ';
        }

        return do_shortcode('[wpl_profile_listing'.(trim($shortcode_atts ?? '') ? ' '.trim($shortcode_atts ?? '') : '').']');
    }
}