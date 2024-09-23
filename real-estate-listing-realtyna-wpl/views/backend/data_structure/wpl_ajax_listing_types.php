<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;

	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');

        $this->verifyNonce(wpl_request::getVar('_wpnonce', ''), 'wpl_data_structure');
        $this->setViewVar('nonce', wpl_security::create_nonce('wpl_data_structure'));
        
		if($function == 'generate_new_page')
		{
			$this->generate_new_page();
		}
		elseif($function == 'generate_delete_page')
		{
			$this->generate_delete_page();
		}
		elseif($function == 'set_enabled_listing_type')
		{
			$listing_type_id = wpl_request::getVar('listing_type_id');
			$enabled_status = wpl_request::getVar('enabled_status');
			
			$this->set_enabled_listing_type($listing_type_id, $enabled_status);
		}
		elseif($function == 'remove_listing_type')
		{
			$listing_type_id = wpl_request::getVar('listing_type_id');
			$confirmed = wpl_request::getVar('wpl_confirmed', 0);
			
			$this->remove_listing_type($listing_type_id, $confirmed);
		}
		elseif($function == 'generate_edit_page')
		{
			$listing_type_id = wpl_request::getVar('listing_type_id');
			$this->generate_edit_page($listing_type_id);
		}
		elseif($function == 'sort_listing_types')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_listing_types($sort_ids);
		}
		elseif($function == 'gicon_delete')
		{
			$icon = wpl_request::getVar('icon');
			$this->gicon_delete($icon);
		}
        elseif($function == 'set_multiple_icon')
		{
			$icon = wpl_request::getVar('icon');
			$this->set_multiple_icon($icon);
		}
		elseif($function == 'gicon_upload_file')
		{
			$this->gicon_upload_file();
		}
        elseif($function == 'save_listing_type')
        {
            $this->save_listing_type();
        }
		elseif($function == 'insert_listing_type')
		{
			$this->insert_listing_type();
		}
		elseif($function == 'can_remove_listing_type')
		{
			$this->can_remove_listing_type();
		}
        elseif($function == 'purge_related_property')
		{
			$this->purge_related_property();
		}
		elseif($function == 'assign_related_properties')
		{
			$this->assign_related_properties();
		}
		elseif($function == 'generate_new_page_ltcategory')
		{
			$this->generate_new_page_ltcategory();
		}
        elseif($function == 'insert_ltcategory')
		{
			$this->insert_ltcategory();
		}
        elseif($function == 'generate_edit_page_ltcategory')
		{
			$ltcategory_id = wpl_request::getVar('ltcategory_id');
			$this->generate_edit_page_ltcategory($ltcategory_id);
		}
        elseif($function == 'remove_ltcategory')
		{
			$this->remove_ltcategory();
		}
	}
	
	private function gicon_upload_file()
	{
		$fileElementName = 'wpl_gicon_file';
		$file = wpl_request::getVar($fileElementName, '','FILES');
		
		$ext_array = array('jpg','png','gif','jpeg');
		$error = "";
		
		if((!empty($file['error'])) or (empty($file['tmp_name']) or $file['tmp_name'] == 'none'))
		{
			$error = wpl_esc::return_html_t("An error occurred while uploading your file!");
		}
		else
		{
			$extention = strtolower(wpl_file::getExt($file['name']));
			$name = strtolower(wpl_file::stripExt(wpl_file::getName($file['name'])));
			
			if(!in_array($extention, $ext_array))
			{
				$error = wpl_esc::return_html_t("File extension should be .jpg, .png or .gif.");
			}
			
			/** check the file size **/
			$filesize = @filesize($file['tmp_name']);
			
			if($filesize> 500*1024)
			{
				$error .= wpl_esc::return_html_t("Icons should not be bigger than 500KB!");
				@unlink($file);
			}
            
			if($error == "")
			{
				$dest = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'listing_types' . DS . 'gicon' . DS . $name . '.' .$extention;
				
				while(wpl_file::exists($dest))
				{
					$name .= '_copy';
					$dest = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'listing_types' . DS . 'gicon' . DS . $name . '.' .$extention;
				}
				
				wpl_file::upload($file['tmp_name'], $dest);
			}
		}
		
		$this->response(array('error'=>$error, 'message'=>''));
	}
	
	private function gicon_delete($icon)
	{
		if(trim($icon ?? '') == '') $icon = wpl_request::getVar('icon');
		$dest = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'listing_types' . DS . 'gicon' . DS . $icon;
		
		if (wpl_file::exists($dest)) wpl_file::delete($dest);
        
        /** trigger event **/
		wpl_global::event_handler('gicon_removed', array('icon'=>$icon));
		exit;
	}
    
    private function set_multiple_icon($icon)
	{
        // Update the setting
		wpl_settings::update_setting('multiple_marker_icon', $icon);
        
        /** trigger event **/
		wpl_global::event_handler('multiple_marker_icon_changed', array('icon'=>$icon));
		exit;
	}
	
	private function sort_listing_types($sort_ids)
	{
		if(trim($sort_ids ?? '') == '') $sort_ids = wpl_request::getVar('sort_ids');
		
		$res = wpl_listing_types::sort_listing_types($sort_ids);
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$response = array('success'=>1, 'message'=>$message, 'data'=>NULL);

		$this->response($response);
	}
	
	private function remove_listing_type($listing_type_id, $confirmed = 0)
	{
		if($confirmed) $res = wpl_listing_types::remove_listing_type($listing_type_id);
		else $res = false;
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Listing type removed from WPL successfully.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function set_enabled_listing_type($listing_type_id, $enabled_status)
	{
		$res = wpl_listing_types::update($listing_type_id, 'enabled', $enabled_status);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function generate_edit_page($listing_type_id = '')
	{
		if(trim($listing_type_id ?? '') == '') $listing_type_id = wpl_request::getVar('listing_type_id');

		$listing_type_data = wpl_global::get_listings($listing_type_id);
		$listing_types_category = wpl_listing_types::get_listing_type_categories();
		$listing_gicons = wpl_listing_types::get_map_icons();

		$this->setViewVars(compact(
            'listing_type_id',
            'listing_type_data',
            'listing_types_category',
            'listing_gicons'
        ));
		parent::render($this->tpl_path, 'internal_edit_listing_types');
		exit;
	}
	
	private function generate_new_page()
	{
		$listing_type_id = 10000;
		$listing_type_data = wpl_global::get_listings($listing_type_id);
		$listing_types_category = wpl_listing_types::get_listing_type_categories();
		$listing_gicons = wpl_listing_types::get_map_icons();

        $this->setViewVars(compact(
            'listing_type_id',
            'listing_type_data',
            'listing_types_category',
            'listing_gicons'
        ));
		parent::render($this->tpl_path, 'internal_edit_listing_types');
		exit;
	}
	
	private function generate_delete_page()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		$listing_type_data = wpl_global::get_listings($listing_type_id);
		$listing_types = wpl_listing_types::get_listing_types();

        $this->setViewVars(compact(
            'listing_type_id',
            'listing_type_data',
            'listing_types'
        ));
		parent::render($this->tpl_path, 'internal_delete_listing_types');
		exit;
	}
	
    private function insert_listing_type()
    {
		$parent = wpl_request::getVar('parent');
		$name = sanitize_text_field(wpl_request::getVar('name'));
		$gicon = wpl_request::getVar('gicon');
		
		$res = wpl_listing_types::insert_listing_type($parent, $name, $gicon);
		$res = (int) $res;
		
		if($res > 0) $res = 1;
		else $res = 0;
		
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
    
    private function save_listing_type()
    {
		$key = wpl_request::getVar('key');
		$value = sanitize_text_field(wpl_request::getVar('value'));
		$id = wpl_request::getVar('listing_type_id');

		$res = wpl_db::q(wpl_db::prepare('UPDATE `#__wpl_listing_types` SET %i = %s WHERE id = %d', $key, $value, $id));
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
    }
    
	private function can_remove_listing_type()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		wpl_esc::e(wpl_listing_types::have_properties($listing_type_id) > 0 ? 0 : 1);
		exit;
	}
    
	private function purge_related_property()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		$properties_list = wpl_property::get_properties_list('listing', $listing_type_id);
        
		foreach($properties_list as $property) wpl_property::purge($property['id']);
		$this->remove_listing_type($listing_type_id, 1);
	}
    
	private function assign_related_properties()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		$select_id = wpl_request::getVar('select_id');
        
		$j = wpl_property::update_properties('listing', $listing_type_id, $select_id);
		$this->remove_listing_type($listing_type_id, 1);
	}
	private function generate_new_page_ltcategory()
	{
		$ltcategory_id = 10000;
		$ltcategory_data = wpl_listing_types::get_category($ltcategory_id);

		$this->setViewVars(compact('ltcategory_id', 'ltcategory_data'));
		parent::render($this->tpl_path, 'internal_edit_ltcategory');
		exit;
	}
    
    private function insert_ltcategory()
    {
		$name = sanitize_text_field(wpl_request::getVar('name'));
		$res = (int) wpl_listing_types::insert_category($name);
		
		if($res > 0) $res = 1;
		else $res = 0;
        
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>$data));
	}
    
    private function generate_edit_page_ltcategory($ltcategory_id = '')
	{
		if(trim($ltcategory_id ?? '') == '') $ltcategory_id = wpl_request::getVar('ltcategory_id');

		$ltcategory_data = wpl_listing_types::get_category($ltcategory_id);

        $this->setViewVars(compact('ltcategory_id', 'ltcategory_data'));
		parent::render($this->tpl_path, 'internal_edit_ltcategory');
		exit;
	}
    
    public function remove_ltcategory()
    {
        $ltcategory_id = wpl_request::getVar('id');
        
        // Check to see if it has some listing types
		$has_listing_types = wpl_db::select(wpl_db::prepare('SELECT COUNT(id) as count FROM `#__wpl_listing_types` WHERE `parent` = %d', $ltcategory_id), 'loadResult');
        
        if($has_listing_types)
        {
            $res = 0;
            $message = sprintf(wpl_esc::return_html_t('There are %s assigned listing types. Please remove them or assign them to another category first.'), '<strong>'.$has_listing_types.'</strong>');
        }
        else
        {
            // Remove the category
            wpl_db::q(wpl_db::prepare('DELETE FROM `#__wpl_listing_types` WHERE `id` = %d', $ltcategory_id));
            
            $res = 1;
            $message = wpl_esc::return_html_t('The category removed.');
        }
        
        $this->response(array('success'=>$res, 'message'=>$message));
    }
}