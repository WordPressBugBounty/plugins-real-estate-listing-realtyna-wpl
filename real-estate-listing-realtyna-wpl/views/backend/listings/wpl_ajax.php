<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');

class wpl_listings_controller extends wpl_controller
{
    public function display()
    {
        $function = wpl_request::getVar('wpl_function');
        $pid = wpl_request::getVar('pid');
        
        // Check Nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_listings')) {
			$this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('The security nonce is not valid!')));
		}
        
        if($function == 'purge_property')
		{
            $this->purge_property($pid);
		}
        elseif($function == 'revert_expired_property')
		{
            $this->revert_expired_property($pid);
		}		
        elseif($function == 'update_property')
        {
            $action = wpl_request::getVar('action');
            $value = wpl_request::getVar('value');
            
            $this->update_property($pid, $action, $value);
        }
        elseif($function == 'change_user')
        {
            $this->change_user();
        }
        elseif($function == 'additional_agents')
        {
            $this->additional_agents();
        }
        elseif($function == 'clone_property')
		{
            $this->clone_property();
		}
    }

    /**
     * author Chris A
     * @param int $pid
     * desctiption: revert expired property with the condition of property id
     */
    private function revert_expired_property($pid)
    {
		/** property data **/
		$property_data = wpl_property::get_property_raw_data($pid);
		
		/** revert expired property **/
		if(wpl_users::check_access('confirm', $property_data['user_id']))
		{
			$res = (int) wpl_property::revert($pid);
			$message = wpl_esc::return_html_t("Property reverted.");
		}
		else
		{
			$res = 0;
			$message = wpl_esc::return_html_t("You don't have access to this action.");
		}

		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>NULL));
		
    }	
    
    /**
     * author Francis
     * @param int $pid
     * desctiption: purge one property with the condition of property id
     */
    private function purge_property($pid)
    {
		/** property data **/
		$property_data = wpl_property::get_property_raw_data($pid);
		
		/** purge property **/
		if(wpl_users::check_access('delete', $property_data['user_id']))
		{
			$res = (int) wpl_property::purge($pid, true);
			$message = wpl_esc::return_html_t("Property purged.");
		}
		else
		{
			$res = 0;
			$message = wpl_esc::return_html_t("You don't have access to this action.");
		}

		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>NULL));
    }
    
    /**
     * author Francis
     * @param int $pid
     * @param string $action
     * @param int $value
     * description: update 'confirmed' and 'deleted' fields of one property
     */
    private function update_property($pid, $action, $value)
    {
		/** property data **/
		$property_data = wpl_property::get_property_raw_data($pid);

        $message = '';
        $res = 0;

        if($action == 'confirm')
		{
			if(wpl_users::check_access('confirm', $property_data['user_id']))
			{
				/** confirm property **/
		        $res = wpl_property::confirm($pid, $value, true);
				$message = wpl_esc::return_html_t("Operation was successful.");
			}
			else
			{
				$res = 0;
				$message = wpl_esc::return_html_t("You don't have access to this action.");
			}
		}
        elseif($action == 'trash')
        {
			if(wpl_users::check_access('delete', $property_data['user_id']))
			{
				/** delete property **/
		        $res = wpl_property::delete($pid, $value, true);
				$message = wpl_esc::return_html_t("Operation was successful.");
			}
			else
			{
				$res = 0;
				$message = wpl_esc::return_html_t("You don't have access to this action.");
			}
		}

		$res = (int) $res;
		$data = NULL;

		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>$data));
    }
    
    /**
     * author Howard
     * desctiption: change user of a property
     */
    private function change_user()
    {
        $pid = wpl_request::getVar('pid');
        $uid = wpl_request::getVar('uid');
		
		/** purge property **/
		if(wpl_users::check_access('change_user'))
		{
			$res = (int) wpl_property::change_user($pid, $uid);
			$message = wpl_esc::return_html_t("User changed.");
		}
		else
		{
			$res = 0;
			$message = wpl_esc::return_html_t("You don't have access to this action.");
		}

		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>NULL));
    }
    
    /**
     * Save additional agents
     * @author Howard R. <howard@realtyna.com>
     */
    private function additional_agents()
    {
        $pid = wpl_request::getVar('pid');
        $uids = explode(',', wpl_request::getVar('uids', ''));
		
        // Validation
        if(count($uids) == 1 and trim($uids[0] ?? '') == '') $uids = array();
        
		// Multi agents addon
		if(!wpl_global::check_addon('multi_agents'))
		{
			$res = 0;
			$message = wpl_esc::return_html_t("Multi Agents Add-on is not installed.");
		}
        elseif(wpl_users::check_access('multi_agents'))
		{
            _wpl_import('libraries.addon_multi_agents');
            
            $multi = new wpl_addon_multi_agents($pid);
            $multi->set_agents($uids);
            
			$res = 1;
			$message = wpl_esc::return_html_t("Additional agents added.");
		}
		else
		{
			$res = 0;
			$message = wpl_esc::return_html_t("You don't have access to this action.");
		}
		
		/** Response **/
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>NULL));
    }
    
    private function clone_property()
    {
        $pid = wpl_request::getVar('pid');
        $clone_id = 0;
        
		// PRO addon
		if(!wpl_global::check_addon('pro'))
		{
			$res = 0;
			$message = wpl_esc::return_html_t("PRO Add-on is not installed.");
		}
        elseif(!wpl_users::check_access('add'))
        {
            $res = 0;
            $message = wpl_esc::return_html_t("Limit Reached! You cannot add new listings!");
        }
        elseif(wpl_users::check_access('clone'))
		{
            $clone_id = wpl_property::clone_listing($pid);
            
			$res = 1;
			$message = wpl_esc::return_html_t("Listing cloned.");
		}
		else
		{
			$res = 0;
			$message = wpl_esc::return_html_t("You don't have access to this action.");
		}
		
		/** Response **/
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>array('id'=>$clone_id, 'edit_link'=>wpl_property::get_property_edit_link($clone_id))));
    }
}