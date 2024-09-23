<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.room_types');

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
        
		if($function == 'sort_rooms')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_rooms($sort_ids);
		}
		if($function == 'generate_new_room_type')
		{
			$this->generate_new_room_type();
		}
		elseif($function == 'room_types_enabled_state_change')
		{
			$id = wpl_request::getVar('id');
			$enabled_status = wpl_request::getVar('enabled_status');			
			$this->update($id, 'enabled', $enabled_status);
		}
		elseif($function == 'remove_room_type')
		{
			/** check permission **/
			wpl_global::min_access('administrator');
			
			$room_type_id = wpl_request::getVar('room_type_id');
			$confirmed = wpl_request::getVar('wpl_confirmed', 0);
			
			$this->remove_room_type($room_type_id, $confirmed);
		}
		elseif($function == 'change_room_type_name')
		{
			$id = wpl_request::getVar('id');
			$name = wpl_request::getVar('name');			
			$this->update($id, 'name', $name);
		}
		elseif($function == 'save_room_type')
		{
			$name = sanitize_text_field(wpl_request::getVar('name'));
			$this->save_room_type($name);
		}
	}
	
	/**
	*{tablename,id,key,value of key}
	**/
	private function update($id, $key, $value = '')
	{
		$res = wpl_room_types::update('wpl_room_types', $id, $key, sanitize_text_field($value));
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>null));
	}
	
	private function remove_room_type($id, $confirmed = 0)
	{
		if($confirmed) $res = wpl_room_types::remove_room_type($id);
		else $res = false;
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Room type removed from WPL successfully.') : wpl_esc::return_html_t('Error Occured.');
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>null));
	}
	
	private function sort_rooms($sort_ids)
	{
		if(trim($sort_ids ?? '') == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_room_types::sort_room_types($sort_ids);
        
		exit;
	}

	private function generate_new_room_type()
	{
		parent::render($this->tpl_path, 'internal_new_room_type');
		exit;
	}
	
	private function save_room_type($name)
	{
		$res = wpl_room_types::save_room_type($name);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>null));
	}
	
}
