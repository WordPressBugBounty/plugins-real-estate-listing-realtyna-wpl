<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * SEF service
 * @author Howard <howard@realtyna.com>
 * @date 08/19/2013
 * @package WPL
 */
class wpl_service_sef
{
    public $view;
    public $property_page_title;
    public $property_keywords;
    public $property_description;
    public $user_title;
    public $user_keywords;
    public $user_description;

    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
		// Global Settings
		$settings = wpl_global::get_settings();
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
        // Get view
		$this->view = wpl_sef::get_view($wpl_qs, $settings['sef_main_separator']);
        
        // Add classes to body
        if(trim($this->view ?? '')) add_filter('body_class', array($this, 'body_class'));
        
		// Set vars
		wpl_sef::setVars($this->view, $wpl_qs);
        
        // Trigger event
		wpl_global::event_handler('wplview_detected', array('wplview'=>$this->view));
        
		if($this->view == 'property_show')
		{
            if(trim($wpl_qs ?? '') != '')
            {
                $ex = explode('-', $wpl_qs);
                $exp = explode('-', $ex[0]);
                $property_id = $exp[0];
            }
			else
            {
                $property_id = wpl_request::getVar('pid', NULL);
                if(!$property_id) $property_id = wpl_request::getVar('property_id', NULL);
            }
			$property_id = apply_filters('wpl_service_sef/run/property_show/detect_property', $property_id, $wpl_qs);
            
			if(trim($wpl_qs ?? '') != '') $this->check_property_link($property_id);
			$this->set_property_page_params($property_id);
		}
		elseif($this->view == 'profile_show')
		{
            $ex = explode($settings['sef_main_separator'], $wpl_qs);
			$username = $ex[0] ?? null;
            $user_id = 0;

            if(trim($username ?? '') != '') $user_id = wpl_users::get_id_by_username($username);
            elseif(wpl_request::getVar('sf_select_user_id', 0)) $user_id = wpl_request::getVar('sf_select_user_id', 0);
            elseif(wpl_request::getVar('uid', 0)) $user_id = wpl_request::getVar('uid', 0);

			$user_id = apply_filters('wpl_service_sef/run/profile_show/user_id', $user_id);
			$this->set_profile_page_params($user_id);
		}
		elseif($this->view == 'property_listing')
        {
            $this->set_property_listing_page_params();
        }
		elseif($this->view == 'profile_listing')
        {
            $this->set_profile_listing_page_params();
        }
		elseif($this->view == 'features')
		{
			$function = str_replace('features/', '', $wpl_qs);
			if(!trim($function ?? '')) $function = wpl_request::getVar('wpltype');
            
			_wpl_import('views.basics.features.wpl_'.$function);
            
            if(!class_exists('wpl_features_controller'))
            {
                http_response_code(404);
				wpl_esc::html_t('Not Found!');
                exit;
            }
            
			$obj = new wpl_features_controller();
			$obj->display();
		}
        elseif($this->view == 'addon_crm')
		{
			_wpl_import('views.frontend.addon_crm.wpl_main');

			if(class_exists('wpl_addon_crm_controller'))
            {
                $obj = new wpl_addon_crm_controller();
                $obj->display();
            }
		}
        elseif($this->view == 'payments')
        {
            $this->set_payments_page_params();
        }
        elseif($this->view == 'addon_membership')
        {
            $this->set_addon_membership_page_params();
        }
        elseif($this->view == 'compare')
        {
            if(wpl_global::check_addon('pro'))
            {
                wpl_addon_pro::compare_display();
            }
        }
		do_action('wpl_service_sef/run/after', $this->view);
	}
	
    /**
     * Checke proeprty alias and 301 redirect the page to the correct link
     * @author Howard <howard@realtyna.com>
     * @param int $property_id
     */
	public function check_property_link($property_id)
	{
        // Global Settings
        $settings = wpl_global::get_settings();
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
		// Check property alias for avoiding duplicate content
		$called_alias = $wpl_qs;
        
        $column = 'alias';
        $field_id = wpl_flex::get_dbst_id($column, wpl_property::get_property_kind($property_id));
        $field = wpl_flex::get_field($field_id);
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        
        $alias = wpl_db::get($column, 'wpl_properties', 'id', $property_id);
        if(trim($alias ?? '') == '') $alias = wpl_property::update_alias(NULL, $property_id);
        
		$property_alias = $property_id.'-'.urldecode($alias);

		$property_alias = apply_filters('wpl_service_sef/check_property_link/property_alias', $property_alias, $property_id, $called_alias);

		if(trim($alias ?? '') and strtolower($called_alias) != strtolower($property_alias))
		{
            $url = rtrim(wpl_sef::get_wpl_permalink(true) ?? '', '/').'/'.urlencode($property_alias);
			
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$url);
			exit;
		}

		// 404 Redirect
		if(isset($settings['listing_404_redirect']) and $settings['listing_404_redirect'] and !wpl_db::exists($property_id, 'wpl_properties'))
		{
            $property_listing = wpl_property::get_property_listing_link();

            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$property_listing);
            exit;
        }
	}
	
    /**
     * Sets property single page parameters
     * @author Howard <howard@realtyna.com>
     * @param int $property_id
     */
	public function set_property_page_params($property_id)
	{
		if(class_exists('RankMath')) {
			remove_all_actions('rank_math/head', 20);
		}

		$this->remove_canonical();
		$this->remove_page_title_filters();
		$this->remove_page_meta_tags_filters();
		$this->remove_open_graph_filters();

		$property_data = wpl_property::get_property_raw_data($property_id);

		if(empty($property_data)) {
			return;
		}

        $locale = wpl_global::get_current_language();
		$this->property_page_title = wpl_property::update_property_page_title($property_data);
        
        $meta_keywords_column = 'meta_keywords';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($meta_keywords_column, $property_data['kind'])) $meta_keywords_column = wpl_addon_pro::get_column_lang_name($meta_keywords_column, $locale, false);
        
        $this->property_keywords = $property_data[$meta_keywords_column] ?? '';
        if(trim($this->property_keywords) == '') $this->property_keywords = wpl_property::get_meta_keywords($property_data);
        
        $meta_description_column = 'meta_description';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($meta_description_column, $property_data['kind'])) $meta_description_column = wpl_addon_pro::get_column_lang_name($meta_description_column, $locale, false);
        
        $this->property_description = $property_data[$meta_description_column] ?? '';
        if(trim($this->property_description) == '') $this->property_description = wpl_property::get_meta_description($property_data);
        
		$html = wpl_html::getInstance();
        
		// Set Title
		$html->set_title($this->property_page_title);
		
		// Set Meta Keywords
		$html->set_meta_keywords($this->property_keywords);
		
		// Set Meta Description
		$html->set_meta_description(apply_filters('wpl_service_sef/set_property_page_params/property_description', strip_tags($this->property_description ?? ''), $property_data));
        
        // SET Canonical URL
        $property_link = wpl_property::get_property_link($property_data);
        wpl_html::$canonical = $property_link;

		$metaTags = [];
		$metaTags['og:type'] = ['content' => 'article'];
		$metaTags['og:locale'] = ['content' => $locale];
        
        $content_column = 'field_308';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($content_column, $property_data['kind'])) $content_column = wpl_addon_pro::get_column_lang_name($content_column, $locale, false);

		$metaTags['og:url'] = ['content' => str_replace('&', '&amp;', $property_link)];
		$metaTags['og:title'] = ['data-page-subject' => 'true', 'content' => wpl_esc::return_attr($this->property_page_title)];
		$metaTags['og:description'] = ['content' => wpl_esc::return_attr(strip_tags(stripslashes($property_data[$content_column] ?? "")))];

		$metaTags['twitter:card'] = ['content' => 'summary'];
		$metaTags['twitter:title'] = ['content' => wpl_esc::return_attr($this->property_page_title)];
		$metaTags['twitter:description'] = ['content' => wpl_esc::return_attr(strip_tags(stripslashes($property_data[$content_column] ?? "")))];
		$metaTags['twitter:url'] = ['content' => str_replace('&', '&amp;', $property_link)];
        
        $gallery = wpl_items::get_gallery($property_id, $property_data['kind']);
        if(is_array($gallery) and count($gallery))
        {
            foreach($gallery as $image)
            {
				$metaTags['og:image'] = ['content' => $image['url']];
				$metaTags['twitter:image'] = ['content' => $image['url']];
                
                // Only print one og and twitter image (First Image)
                break;
            }
        }
		$metaTags = apply_filters('wpl_service_sef/set_property_page_params/set_meta_tags', $metaTags, $property_data);

		if(!empty($metaTags)) {
			foreach ($metaTags as $metaKey => $metaTag) {
				$props = ['property="' . $metaKey . '"'];
				foreach ($metaTag as $key => $value) {
					$props[] = $key . '="' . $value . '"';
				}
				$html->set_custom_tag('<meta ' . implode(' ', $props) . ' />');
			}
		}
	}
	
    /**
     * Sets profile single page parameters
     * @author Howard <howard@realtyna.com>
     * @param int $user_id
     */
	public function set_profile_page_params($user_id)
	{
		$user_data = (array) wpl_users::get_wpl_user($user_id);
		
		$this->user_title = '';
		$this->user_keywords = '';
		$this->user_description =wpl_esc::return_t('Listings of');
		
		if(trim($user_data['first_name'] ?? '') != '')
		{
			$this->user_title .= $user_data['first_name'];
			$this->user_keywords .= $user_data['first_name'].',';
			$this->user_description .= ' '.$user_data['first_name'];
		}
		
		if(trim($user_data['last_name'] ?? '') != '')
		{
			$this->user_title .= ' '.$user_data['last_name'];
			$this->user_keywords .= $user_data['last_name'].',';
			$this->user_description .= ' '.$user_data['last_name'];
		}
		
		if(trim($user_data['company_name'] ?? '') != '')
		{
			$this->user_title .= ' - '.$user_data['company_name'];
			$this->user_keywords .= $user_data['company_name'].',';
			$this->user_description .= ' - '.$user_data['company_name'];
		}
		
		$this->user_title .= ' '.wpl_esc::return_html_t('Listings');
		$this->user_keywords = trim($this->user_keywords ?? '', ', ');
		$this->user_description .= ' '.wpl_esc::return_html_t('which is located in').' '.$user_data['location_text'];
		
		$html = wpl_html::getInstance();

        //Remove Rank Math SEO canonical
        if(class_exists('RankMath')) {
            remove_all_actions('rank_math/head', 20);
        }
        
		wpl_html::$canonical = wpl_users::get_profile_link($user_id);
        
        // Remove canonical tags
        $this->remove_canonical();
        
        // Remove Page Title Filters
		$this->remove_page_title_filters();

        // Remove Page Title Filters
		$this->remove_page_meta_tags_filters();

        // Remove Open Graph Filters
        $this->remove_open_graph_filters();
        
		// Set Title
		$html->set_title($this->user_title);
		
		// Set Meta Keywords
		$html->set_meta_keywords($this->user_keywords);
		
		// Set Meta Description
		$html->set_meta_description($this->user_description);
	}
    
    /**
     * Sets property listing page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_property_listing_page_params()
    {
    }
    
    /**
     * Sets profile listing page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_profile_listing_page_params()
    {
    }
    
    /**
     * Sets payments page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_payments_page_params()
    {
        $html = wpl_html::getInstance();
        
		// Set Title
		$html->set_title(wpl_esc::return_html_t('Payments'));
    }
    
    /**
     * Sets addon membership page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_addon_membership_page_params()
    {
        $html = wpl_html::getInstance();
        
		// Set Title
		$html->set_title(wpl_esc::return_html_t('Members'));
    }
    
    /**
     * For removing canonical URLs from WPL pages
     * @author Howard <howard@realtyna.com>
     */
    public function remove_canonical()
    {
        // Remove Yoast Canonical URL
        add_filter('wpseo_canonical', '__return_false');
        add_filter('wpseo_metadesc', '__return_false' );

        // Remove All in One SEO Pack Canonical URL
        add_filter('aioseop_canonical_url', '__return_false');

        // All in One SEO new version
        add_filter('aioseo_twitter_tags', array( $this , 'aioseo_remove_twitter_tags' ) ); // twitter tags
        add_filter('aioseo_facebook_tags', array( $this , 'aioseo_remove_facebook_tags' ) ); // og tags
        add_filter('aioseo_canonical_url', array( $this , 'aioseo_remove_canonical_url' ) ); // aioseo_canonical_url
    }
	
	
	/**
	 * Remove Canonical URL for AIOSEO
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param string $url
	 *
	 * @return string empty string
	 */
	public function aioseo_remove_canonical_url( $url ) {
         return '';
	}
	
	/**
	 * Remove Facebook Tags for AIOSEO
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param array $facebookMeta
	 *
	 * @return array empty array
	 */
	public function aioseo_remove_facebook_tags( $facebookMeta ) {
         return array();
	}
	
	/**
	 * Remove Twitter Tags for AIOSEO
	 * @author Chris A <chris.a@realtyna.net>
	 *
	 * @param array $twitterMeta
	 *
	 * @return array empty array
	 */
	public function aioseo_remove_twitter_tags( $twitterMeta ) {
         return array();
	}	
    
    /**
     * For removing page title filters of WPL pages that applied by some third party plugins
     * @author Howard <howard@realtyna.com>
     */
    public function remove_page_title_filters()
	{
		// Remove Yoast page title filter
		if(class_exists('WPSEO_Frontend'))
		{
			$yoast = WPSEO_Frontend::get_instance();
            
			remove_filter('pre_get_document_title', array($yoast, 'title'), 15);
			remove_filter('wp_title', array($yoast, 'title'), 15);
		}
        if(function_exists('bridge_qode_wp_title')) {
            remove_filter('pre_get_document_title', 'bridge_qode_wp_title');
        }
        if(class_exists('\AIOSEO\Plugin\AIOSEO')) {
            remove_filter('pre_get_document_title', array(aioseo()->head, 'getTitle'), 99999);
            remove_filter('wp_title', array(aioseo()->head, 'getTitle'), 99999);
        }
	}

    /**
     * For removing page meta tags filters of WPL pages that applied by some third party plugins
     */
    public function remove_page_meta_tags_filters()
	{
        if(class_exists('RankMath')) {
            add_filter('rank_math/frontend/description', '__return_false');
        }
        if(function_exists( 'aioseo' )){
            add_filter('aioseo_description', '__return_false');
        }
	}
    
    /**
     * For removing open graph filters of WPL pages that applied by some third party plugins
     * @author Howard <howard@realtyna.com>
     */
    public function remove_open_graph_filters()
	{
		// Disable JetPack filters
		add_filter('jetpack_enable_open_graph', '__return_false');

		// Disable Yoast Presenters
        add_filter('wpseo_frontend_presenters', function($presenters)
        {
            $filtered = array();
            foreach($presenters as $presenter)
            {
                if($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Locale_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Type_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Title_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Description_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Url_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Site_Name_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Article_Publisher_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Article_Author_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Article_Published_Time_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Article_Modified_Time_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\Image_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Open_Graph\FB_App_ID_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Twitter\Card_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Twitter\Title_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Twitter\Description_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Twitter\Image_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Twitter\Creator_Presenter) continue;
                elseif($presenter instanceof Yoast\WP\SEO\Presenters\Twitter\Site_Presenter) continue;

                $filtered[] = $presenter;
            }

            return $filtered;
        }, 999999);

        // Disable Yoast Open Graph Tags
        add_filter('wpseo_locale' , '__return_false');
        add_filter('wpseo_opengraph_url' , '__return_false');
        add_filter('wpseo_opengraph_desc', '__return_false');
        add_filter('wpseo_opengraph_title', '__return_false');
        add_filter('wpseo_opengraph_type', '__return_false');
        add_filter('wpseo_opengraph_site_name', '__return_false');
        add_filter('wpseo_opengraph_image' , '__return_false');
        add_filter('wpseo_opengraph_author_facebook' , '__return_false');
        add_filter('wpseo_opengraph_admin' , '__return_false');
        add_filter('wpseo_opengraph_show_publish_date' , '__return_false');

        // Disable Yoast Twitter Card
        add_filter('wpseo_twitter_title' , '__return_false');
        add_filter('wpseo_twitter_description' , '__return_false');
        add_filter('wpseo_twitter_card' , '__return_false');
        add_filter('wpseo_twitter_card_type' , '__return_false');
        add_filter('wpseo_twitter_site' , '__return_false');
        add_filter('wpseo_twitter_image' , '__return_false');
        add_filter('wpseo_twitter_creator_account' , '__return_false');

        // Houzez
        remove_action('wp_head', 'houzez_add_opengraph', 5);

        //Rank Math SEO
        if(class_exists('RankMath')) {
            add_action('rank_math/head', function () {
                remove_all_actions('rank_math/opengraph/facebook');
                remove_all_actions('rank_math/opengraph/twitter');
            });
        }
	}
    
    /**
     * For adding HTML classes to HTML body tag
     * @author Howard <howard@realtyna.com>
     * @param array $classes
     * @return array
     */
    public function body_class($classes)
    {
        $classes[] = 'wpl-page';
        $classes[] = 'wpl_'.$this->view;

        if($this->view == 'property_show')
        {
            $pid = wpl_request::getVar('pid', 0);
            $property = wpl_property::get_property_raw_data($pid);

			$kind = $property['kind'] ?? 0;

            if($kind == 1) $tpl = wpl_global::get_setting('wpl_complex_propertyshow_layout');
            elseif($kind == 4) $tpl = wpl_global::get_setting('wpl_neighborhood_propertyshow_layout');
            else $tpl = wpl_global::get_setting('wpl_propertyshow_layout');
            
            if(trim($tpl ?? "") == '') $tpl = 'default';
            $classes[] = 'wpl_'.$this->view.'_'.$tpl;
        }

        // Add theme compability classes
        if(wpl_global::get_setting('wpl_theme_compatibility'))
        {
            $current_theme = get_option('template');
            
            if($current_theme == 'bridge') $classes[] = 'wpl-bridge-layout';
            elseif($current_theme == 'Avada') $classes[] = 'wpl-avada-layout';
            elseif($current_theme == 'enfold') $classes[] = 'wpl-enfold-layout';
            elseif($current_theme == 'betheme') $classes[] = 'wpl-be-layout';
            elseif($current_theme == 'x') $classes[] = 'wpl-x-layout';
            elseif($current_theme == 'genesis') $classes[] = 'wpl-genesis-layout';
            elseif($current_theme == 'houzez') $classes[] = 'wpl-houzez-layout';
            elseif($current_theme == 'webify') $classes[] = 'wpl-webify-layout';
        }

        if(is_rtl())
        {
            $classes[] = 'wpl_rtl';
        }

        return $classes;
    }
}