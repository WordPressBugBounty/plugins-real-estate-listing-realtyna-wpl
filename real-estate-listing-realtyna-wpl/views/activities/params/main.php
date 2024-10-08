<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_params extends wpl_activity
{
    public $tpl_path = 'views.activities.params.tmpl';
    
	public function start($layout, $params)
	{
        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_activity_params');
        
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('author');
		
		$function = wpl_request::getVar('wpl_function');
		
        // Check Nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_activity_params')) {
			$this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('The security nonce is not valid!')));
		}
        
		if($function == 'save_params') $this->save_params();
	}
	
	private function save_params()
	{
		$table = wpl_request::getVar('table');
		$id = wpl_request::getVar('id');
		
		$post = wpl_request::get('post');
		$keys = (isset($post['wpl_params']) and is_array($post['wpl_params']['keys'])) ? $post['wpl_params']['keys'] : array();
		$values = (isset($post['wpl_params']) and is_array($post['wpl_params']['values'])) ? $post['wpl_params']['values'] : array();
		
		$params = array();
		foreach($keys as $key=>$value)
		{
			if(trim($value ?? '') == '') continue;
			$params[$value] = stripslashes($values[$key] ?? '');
		}
        
		/** save params **/
		wpl_global::set_params($table, $id, $params);
		
		/** trigger event **/
		wpl_global::event_handler('params_saved', array('table'=>$table, 'id'=>$id, 'params'=>$params));
		
		$res = 1;
		$message = $res ? wpl_esc::return_html_t('Params Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
}