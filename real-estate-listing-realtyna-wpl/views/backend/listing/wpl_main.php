<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');

class wpl_listing_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.listing.tmpl';
	public $tpl;
	public $nonce;
	public $layout;
	public $message;
	public $kind;
	public $property_id;
	public $mode;
	public $values;
	public $finalized;
	public $field_categories;
	public $kind_label;
	public $fields;
	public $field_category;

	public function wizard($instance = array())
	{
        /** load assets **/
        $this->load_assets();
        
        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_listing');

		// Property wizard layout: Horizontal or Vertical
		$layout = wpl_global::get_setting('wpl_property_wizard_layout');
		$this->layout = trim($layout ?? "") ? $layout : 'vertical';
        
        /** check access **/
		if(!wpl_users::check_access('propertywizard'))
		{
			/** import message tpl **/
			$this->message = wpl_esc::return_html_t("You don't have access to this menu! Maybe your user is not added to WPL as an agent. You can contact to website admin for this.");
			return parent::render($this->tpl_path, 'message');
		}
		
		$this->kind = trim(wpl_request::getVar('kind') ?? "") != '' ? wpl_request::getVar('kind') : 0;
		
        // Check Complex Access
        if($this->kind == 1 and !wpl_users::check_access('complex_addon'))
        {
            /** import message tpl **/
			$this->message = wpl_esc::return_html_t("You don't have access to complexes/condos. You can request access from website admin.");
			parent::render($this->tpl_path, 'message');
            
            return false;
        }
        
        // Check Neighborhood Access
        if($this->kind == 4 and !wpl_users::check_access('neighborhoods'))
        {
            /** import message tpl **/
			$this->message = wpl_esc::return_html_t("You don't have access to neighborhoods. You can request access from website admin.");
			parent::render($this->tpl_path, 'message');
            
            return false;
        }
        
		$this->property_id = trim(wpl_request::getVar('pid')) != '' ? wpl_request::getVar('pid') : 0;
		$this->mode = $this->property_id ? 'edit' : 'add';
		
		if($this->mode == 'add')
		{
			/** checking access **/
			if(!wpl_users::check_access($this->mode))
			{
				$this->message = wpl_esc::return_html_t("Limit reached. You can not add more property!");
				return parent::render($this->tpl_path, 'message');
			}
            
            if(wpl_global::check_addon('membership'))
            {
                _wpl_import('libraries.addon_membership');
                $membership = new wpl_addon_membership();
                
                if($membership->is_expired())
                {
                    $this->message = wpl_esc::return_html_t("Your membership expired. You cannot add new listings.");
                    return parent::render($this->tpl_path, 'message');
                }
            }
			
			/** generate new property **/
			$this->property_id = wpl_property::create_property_default('', $this->kind);
		}
        
        /** Multisite **/
		if(wpl_global::is_multisite())
		{
            $sqlParser = wpl_sql_parser::getInstance();
            $sqlParser->criteria('off');
		}

		$this->values = wpl_property::get_property_raw_data($this->property_id);
		$owner_id = $this->values['user_id'];
		$current_user_id = wpl_users::get_cur_user_id();

		if(wpl_global::check_addon('multi_agents') && strpos($this->values['additional_agents'] ?? '', ",$current_user_id,") !== false) {
			$owner_id = $current_user_id;
		}

        /** Multisite **/
		if(wpl_global::is_multisite())
		{
            $sqlParser->criteria('on');
		}
        
		$this->finalized = isset($this->values['finalized']) ? $this->values['finalized'] : 0;
        $this->kind = isset($this->values['kind']) ? $this->values['kind'] : 0;
        
        $this->field_categories = wpl_flex::get_categories(1, $this->kind);
		$this->field_categories = apply_filters('wpl_view/backend/listing/main/field_categories',$this->field_categories, $this->kind);
		$this->kind_label = wpl_flex::get_kind_label($this->kind);
        
		if($this->mode == 'edit')
		{
			if(!$this->values)
			{
				$this->message = wpl_esc::return_html_t("Property does not exist!");
				return parent::render($this->tpl_path, 'message');
			}
			
			/** checking access **/
			if(!wpl_users::check_access($this->mode, $owner_id))
			{
				$this->message = wpl_esc::return_html_t("You can not edit this property.");
				return parent::render($this->tpl_path, 'message');
			}
		}
		
		/** import tpl **/
		$this->tpl = wpl_flex::get_kind_tpl($this->tpl_path, 'wizard', $this->kind);
		parent::render($this->tpl_path, $this->tpl);
	}
	
	protected function generate_slide($category)
	{
		$tpl = wpl_flex::get_kind_tpl($this->tpl_path, 'internal_slide', $this->kind);
        
		$this->fields = wpl_property::get_pwizard_fields($category->id, $this->kind, 1);
		$this->field_category = $category;
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
    
    protected function load_assets()
	{
		/** add scripts and style sheet for uploaders **/
		wp_enqueue_script('jquery-ui-widget');

        $scripts = array();
        $scripts[] = (object) array('param1'=>'jquery_file_upload', 'param2'=>'packages/ajax_uploader/jquery.fileupload.min.js','param4'=>'1');
        foreach($scripts as $script) wpl_extensions::import_javascript($script);
	}
}