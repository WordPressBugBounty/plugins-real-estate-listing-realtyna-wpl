<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');

abstract class wpl_profile_show_controller_abstract extends wpl_controller
{
	public $tpl_path = 'views.frontend.profile_show.tmpl';
	public $tpl;
	public $uid;
	public $wplmethod;

    /**
     * @var wpl_security
     */
	public $wpl_security;
	public $message;
	public $kind;
	public $user_type;
	public $wplraw;

	public function display($instance = array())
	{
        $this->wplmethod = wpl_request::getVar('wplmethod', NULL);
        $this->wpl_security = new wpl_security();

        if($this->wplmethod == 'login') $output = $this->login();
        else
        {
            $this->uid = wpl_request::getVar('uid', 0);
            if(!$this->uid)
            {
                $this->uid = wpl_request::getVar('sf_select_user_id', 0);
                wpl_request::setVar('uid', $this->uid);
            }
            
            $this->uid = apply_filters('wpl_profile_show_controller_abstract/display/uid', $this->uid);

			$check_access = wpl_global::check_access('public_profile',$this->uid);
            /** check user id **/
            if(!$this->uid or !$check_access)
            {
                /** import message tpl **/
                $this->message = wpl_esc::return_html_t("No profile found or it's not available now!");
                return parent::render($this->tpl_path, 'message', false, true);
            }

            /** set the user id to search credentials **/
            wpl_request::setVar('sf_select_user_id', $this->uid);

            /** set the kind **/
            $this->kind = wpl_request::getVar('kind', '0');
            wpl_request::setVar('kind', $this->kind);

            /** User Type **/
            $this->user_type = wpl_users::get_user_user_type($this->uid);

            /** trigger event **/
            wpl_global::event_handler('profile_show', array('id'=>$this->uid, 'kind'=>$this->kind));

            /** import tpl **/
            $this->tpl = wpl_users::get_user_type_tpl($this->tpl_path, $this->tpl, $this->user_type);
            $output = parent::render($this->tpl_path, $this->tpl, false, true);
        }

        if($this->wplraw)
        {
            wpl_esc::e($output);
            exit;
        }
        else
        {
            /** Return **/
            return $output;
        }
	}

    private function login()
    {
        $this->tpl = wpl_request::getVar('tpl', 'internal_login');

        /** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
    }
}