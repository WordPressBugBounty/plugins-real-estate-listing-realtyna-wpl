<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Divi Builder Compatibility
 * @author Howard <howard@realtyna.com>
 * @package WPL PRO
 */
class wpl_page_builders_divi extends DiviExtension
{
    public $gettext_domain = 'wpl';
    public $name = 'WPL';

    /**
     * Constructor method
     * @author Howard <howard@realtyna.com>
     * @param string $name
     * @param array $args
     */
    public function __construct($name = 'WPL', $args = array())
    {
        $this->plugin_dir = WPL_ABSPATH . 'libraries'.DS.'page_builders'.DS.'divi'.DS;
        $this->plugin_dir_url = wpl_global::get_wpl_url().'libraries/page_builders/divi/';

        parent::__construct($name, $args);
    }

    public function register_modules()
    {
        // Divi Builder is not installed or activated
        if(!class_exists('ET_Builder_Module')) return;

        // Include libraries
        _wpl_import('libraries.sort_options');

        // Property Listing Shortcode
        _wpl_import('libraries.page_builders.divi.includes.modules.property_listing.property_listing');
        new wpl_page_builders_divi_property_listing();

        // Property Show Shortcode
        _wpl_import('libraries.page_builders.divi.includes.modules.property_show.property_show');
        new wpl_page_builders_divi_property_show();

        // Profile Listing Shortcode
        _wpl_import('libraries.page_builders.divi.includes.modules.profile_listing.profile_listing');
        new wpl_page_builders_divi_profile_listing();

        // Profile Show Shortcode
        _wpl_import('libraries.page_builders.divi.includes.modules.profile_show.profile_show');
        new wpl_page_builders_divi_profile_show();

        // Profile Wizard Shortcode
        _wpl_import('libraries.page_builders.divi.includes.modules.profile_wizard.profile_wizard');
        new wpl_page_builders_divi_profile_wizard();

        // PRO Addon Modules
        if(wpl_global::check_addon('pro'))
        {
            // User Links Shortcode
            _wpl_import('libraries.page_builders.divi.includes.modules.user_links.user_links');
            if(class_exists('wpl_page_builders_divi_user_links')) new wpl_page_builders_divi_user_links();

            // WPL Favorites Widget
            _wpl_import('libraries.page_builders.divi.includes.modules.widget_favorites.widget_favorites');
            if(class_exists('wpl_page_builders_divi_widget_favorites')) new wpl_page_builders_divi_widget_favorites();

            // WPL Unit Switcher Widget
            _wpl_import('libraries.page_builders.divi.includes.modules.widget_unit_switcher.widget_unit_switcher');
            if(class_exists('wpl_page_builders_divi_widget_unit_switcher')) new wpl_page_builders_divi_widget_unit_switcher();
        }

        // Addon Save Searches Shortcode
        if(wpl_global::check_addon('save_searches'))
        {
            _wpl_import('libraries.page_builders.divi.includes.modules.addon_save_searches.addon_save_searches');
            if(class_exists('wpl_page_builders_divi_addon_save_searches')) new wpl_page_builders_divi_addon_save_searches();
        }

        // WPL Search Widget
        _wpl_import('libraries.page_builders.divi.includes.modules.widget_search.widget_search');
        new wpl_page_builders_divi_widget_search();

        // WPL Carousel Widget
        _wpl_import('libraries.page_builders.divi.includes.modules.widget_carousel.widget_carousel');
        new wpl_page_builders_divi_widget_carousel();

        // Tags Addon Modules
        if(wpl_global::check_addon('tags'))
        {
            // WPL Tags Widget
            _wpl_import('libraries.page_builders.divi.includes.modules.widget_tags.widget_tags');
            if(class_exists('wpl_page_builders_divi_widget_tags')) new wpl_page_builders_divi_widget_tags();
        }

        // WPL Google Maps Widget
        _wpl_import('libraries.page_builders.divi.includes.modules.widget_googlemap.widget_googlemap');

        _wpl_import('widgets.googlemap.main');
        if(class_exists('wpl_googlemap_widget')) new wpl_page_builders_divi_widget_googlemap();

        // WPL Agents Widget
        _wpl_import('libraries.page_builders.divi.includes.modules.widget_agents.widget_agents');
        new wpl_page_builders_divi_widget_agents();

        // APS Addon Modules
        if(wpl_global::check_addon('aps'))
        {
            // WPL Summary Widget
            _wpl_import('libraries.page_builders.divi.includes.modules.widget_summary.widget_summary');
            if(class_exists('wpl_page_builders_divi_widget_summary')) new wpl_page_builders_divi_widget_summary();
        }
    }
}

$divi = new wpl_page_builders_divi();
add_action('et_builder_ready', array($divi, 'register_modules'));

add_filter('et_pb_admin_excluded_shortcodes', 'wpl_exclude_divi_shortcodes');
function wpl_exclude_divi_shortcodes($shortcodes)
{
    $shortcodes[] = 'WPL';
    $shortcodes[] = 'et_pb_wpl_property_listing';

    $shortcodes[] = 'wpl_addon_save_searches';
    $shortcodes[] = 'et_pb_wpl_addon_save_searches';

    $shortcodes[] = 'wpl_my_profile';
    $shortcodes[] = 'et_pb_wpl_profile_wizard';

    $shortcodes[] = 'wpl_property_show';
    $shortcodes[] = 'et_pb_wpl_property_show';

    $shortcodes[] = 'wpl_profile_listing';
    $shortcodes[] = 'et_pb_wpl_profile_listing';

    $shortcodes[] = 'wpl_profile_show';
    $shortcodes[] = 'et_pb_wpl_profile_show';

    $shortcodes[] = 'wpl_user_links';
    $shortcodes[] = 'et_pb_wpl_user_links';

    $shortcodes[] = 'wpl_user_links';
    $shortcodes[] = 'et_pb_wpl_user_links';

    $shortcodes[] = 'et_pb_wpl_widget_search';
    $shortcodes[] = 'et_pb_wpl_widget_carousel';
    $shortcodes[] = 'wpl_widget_instance';

    $shortcodes[] = 'et_pb_wpl_widget_agents';
    $shortcodes[] = 'et_pb_wpl_widget_favorites';
    $shortcodes[] = 'et_pb_wpl_widget_googlemap';
    $shortcodes[] = 'et_pb_wpl_widget_summary';
    $shortcodes[] = 'et_pb_wpl_widget_tags';
    $shortcodes[] = 'et_pb_wpl_widget_unit_switcher';

    return $shortcodes;
}