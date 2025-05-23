<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.locations');

class wpl_profile_listing_controller extends wpl_controller
{
	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
        
        if($function == 'contact_profile') $this->contact_profile();
	}
    
    private function contact_profile()
    {
        $fullname = wpl_request::getVar('fullname', '');
        $phone = wpl_request::getVar('phone', '');
        $email = wpl_request::getVar('email', '');
        $message = wpl_request::getVar('message', '');
        $user_id = wpl_request::getVar('user_id', '');
        $gre = wpl_request::getVar('g-recaptcha-response', '');
        
        $parameters = array(
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email,
            'message' => $message,
            'user_id' => $user_id
        );

        // check recaptcha 
        $gre_response = wpl_global::verify_google_recaptcha($gre, 'gre_user_contact_activity');

        $returnData = array();
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = wpl_esc::return_html_t('Your email is not a valid email!');
        }
        elseif(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_user_contact_form'))
        {
            $returnData['success'] = 0;
            $returnData['message'] = wpl_esc::return_html_t('The security nonce is not valid!');
        }
        elseif($gre_response['success'] === 0)
        {
            $returnData['success'] = 0;
            $returnData['message'] = $gre_response['message'];
        }
        else
        {
            wpl_events::trigger('contact_profile', $parameters);
            
            $returnData['success'] = 1;
            $returnData['message'] = wpl_esc::return_html_t('Information sent to agent.');
        }
        
        $this->response($returnData);
    }
}