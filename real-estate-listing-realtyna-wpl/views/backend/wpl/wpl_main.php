<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_wpl_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.wpl.tmpl';
	public $tpl;
	public $user;
	
	public function admin_home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
        
		$submenus = wpl_global::get_menus('submenu', 'backend', 1, 1);
		$settings = wpl_global::get_settings();
		
        // Create Nonce
        $nonce = wpl_security::create_nonce('wpl_dashboard');

        // get current user
        $user = wp_get_current_user();

        $this->setViewVars(compact('submenus', 'settings', 'nonce', 'user'));
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function generate_addons()
	{
		$tpl = 'internal_addons';
		$addons = wpl_db::select("SELECT * FROM `#__wpl_addons` ORDER BY `id` ASC", 'loadAssocList');

        $this->setViewVars(compact('addons'));
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function not_installed_addons()
	{
		$tpl = 'internal_ni_addons';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function support()
	{
		$tpl = 'internal_support';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function knowledgebase()
	{
		$tpl = 'internal_knowledgebase';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
    
    public function statistic()
	{
		$tpl = 'internal_statistic';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
    
    public function announcements()
	{
		$tpl = 'internal_announcements';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}    

	public function sharebox()
	{
		$tpl = 'internal_sharebox';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
}