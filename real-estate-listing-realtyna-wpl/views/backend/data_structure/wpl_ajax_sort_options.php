<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.sort_options');

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
        
		if($function == 'sort_options')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_options($sort_ids);
		}
		elseif($function == 'sort_options_enabled_state_change')
		{
			$id = wpl_request::getVar('id');
			$status = wpl_request::getVar('enabled_status');
            $key = wpl_request::getVar('key', 'enabled');
            
			$this->update('wpl_sort_options', $id, $key, $status);
		}
        elseif($function == 'save_sort_option')
        {
            $id = wpl_request::getVar('id');
			$key = wpl_request::getVar('key', '');
			$value = wpl_request::getVar('value', '');
            
            $this->update('wpl_sort_options', $id, $key, $value);
        }
	}

    /**
     * this function call update function in units library and change value of a field
     * @param $table
     * @param $id
     * @param $key
     * @param string $value
     */
	private function update($table, $id, $key, $value = '')
	{
		$res = wpl_sort_options::update($table, $id, $key, sanitize_text_field($value));
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>null));
	}
	
	private function sort_options($sort_ids)
	{
		if(trim($sort_ids ?? '') == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_sort_options::sort_options($sort_ids);		
		exit;
	}
}