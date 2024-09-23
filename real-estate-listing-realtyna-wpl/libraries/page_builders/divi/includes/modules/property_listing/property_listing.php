<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Property Listing Shortcode for Divi Builder
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_divi_property_listing extends ET_Builder_Module
{
    public $slug       = 'et_pb_wpl_property_listing';
    public $vb_support = 'on';

    public function init()
    {
        $this->name =wpl_esc::return_t('Property Listing');
        $this->slug = 'et_pb_wpl_property_listing';

		$this->fields_defaults = array(
			'wplcolumns' => array('3'),
		);

        // Global WPL Settings
		$this->settings = wpl_global::get_settings();
	}

    public function get_fields()
    {
        // Module Fields
        $fields = array();

        $kinds = wpl_flex::get_kinds('wpl_properties');

        $kinds_options = array();
        foreach($kinds as $kind) $kinds_options[$kind['id']] = wpl_esc::return_html_t($kind['name']);

        $fields['kind'] = array(
            'label'           => wpl_esc::return_html_t('Kind'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $kinds_options,
            'description'     => wpl_esc::return_html_t('Kind/Entity for filtering listings'),
        );

        $listings = wpl_global::get_listings();

        $listings_options = array();
        $listings_options['-1'] = '-----';

        foreach($listings as $listing) $listings_options[$listing['id']] = wpl_esc::return_html_t($listing['name']);

        $fields['sf_select_listing'] = array(
            'label'           => wpl_esc::return_html_t('Listing Type'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $listings_options,
        );

        $property_types = wpl_global::get_property_types();

        $property_types_options = array();
        $property_types_options['-1'] = '-----';

        foreach($property_types as $property_type) $property_types_options[$property_type['id']] = wpl_esc::return_html_t($property_type['name']);

        $fields['sf_select_property_type'] = array(
            'label'           => wpl_esc::return_html_t('Property Type'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $property_types_options,
        );

        $property_listing_layouts = wpl_global::get_layouts('property_listing', array('message.php'), 'frontend');

        $property_listing_layouts_options = array();
        foreach($property_listing_layouts as $property_listing_layout) $property_listing_layouts_options[$property_listing_layout] = wpl_esc::return_html_t($property_listing_layout);

        $fields['tpl'] = array(
            'label'           => wpl_esc::return_html_t('Layout'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $property_listing_layouts_options,
            'description'     => wpl_esc::return_html_t('Layout of the page'),
        );

        $location_settings = wpl_global::get_settings('3'); # location settings

        $fields['sf_locationtextsearch'] = array(
            'label'           => wpl_esc::return_html_t('Location'),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'description'     => wpl_esc::return_html_t($location_settings['locationzips_keyword'].', '.$location_settings['location3_keyword'].', '.$location_settings['location1_keyword']),
        );

        $units = wpl_units::get_units(4);

        $price_unit_options = array();
        foreach($units as $unit) $price_unit_options[$unit['id']] = wpl_esc::return_html_t($unit['name']);

        $fields['sf_tmin_price'] = array(
            'label'           => wpl_esc::return_html_t('Price (Min)'),
            'type'            => 'number',
            'option_category' => 'basic_option',
            'description'     => wpl_esc::return_html_t('Minimum Price of listings'),
        );

        $fields['sf_tmax_price'] = array(
            'label'           => wpl_esc::return_html_t('Price (Max)'),
            'type'            => 'number',
            'option_category' => 'basic_option',
            'description'     => wpl_esc::return_html_t('Maximum Price of listings'),
        );

        $fields['sf_unit_price'] = array(
            'label'           => wpl_esc::return_html_t('Price Unit'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $price_unit_options,
            'description'     => wpl_esc::return_html_t('Price Unit'),
        );

        $tags = wpl_flex::get_tag_fields(0);
        foreach($tags as $tag)
        {
            $fields['sf_select_'.$tag->table_column] = array(
                'label'           => wpl_esc::return_html_t($tag->name),
                'type'            => 'select',
                'default'         => '-1',
                'option_category' => 'basic_option',
                'options'         => array(
                    '-1' => wpl_esc::return_html_t('Any'),
                    '0' => wpl_esc::return_html_t('No'),
                    '1' => wpl_esc::return_html_t('Yes'),
                ),
            );
        }

        $wpl_users = wpl_users::get_wpl_users();

        $wpl_users_options = array();
        $wpl_users_options['-1'] = '-----';

        foreach($wpl_users as $wpl_user) $wpl_users_options[$wpl_user->ID] = wpl_esc::return_html_t($wpl_user->user_login.((trim($wpl_user->first_name ?? '') != '' or trim($wpl_user->last_name ?? '') != '') ? ' ('.$wpl_user->first_name.' '.$wpl_user->last_name.')' : ''));

        $fields['sf_select_user_id'] = array(
            'label'           => wpl_esc::return_html_t('User'),
            'type'            => 'select',
            'default'         => '-1',
            'option_category' => 'basic_option',
            'options'         => $wpl_users_options,
            'description'     => wpl_esc::return_html_t('Filter the listings by a certain agent'),
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

        $sorts = wpl_sort_options::render(wpl_sort_options::get_sort_options(0, 1));

        $sorts_options = array();
        foreach($sorts as $sort) $sorts_options[$sort['field_name']] = wpl_esc::return_html_t($sort['name']);

        $fields['wplorderby'] = array(
            'label'           => wpl_esc::return_html_t('Order By'),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => $sorts_options,
        );

        $fields['wplorder'] = array(
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
            'option_category' => 'basic_option',
            'options'         => array(
                '3' => '3',
                '1' => '1',
                '2' => '2',
                '4' => '4',
                '6' => '6'
            ),
        );

		$fields = apply_filters('wpl_page_builders_divi_property_listing/get_fields', $fields);

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

        return do_shortcode('[WPL'.(trim($shortcode_atts ?? '') ? ' '.trim($shortcode_atts ?? '') : '').']');
    }
}