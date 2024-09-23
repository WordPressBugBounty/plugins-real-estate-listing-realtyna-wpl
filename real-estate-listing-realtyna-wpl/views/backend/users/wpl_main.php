<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.flex');
_wpl_import('libraries.activities');

class wpl_users_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.users.tmpl';
	public $tpl;
    public $error_code;

    public $nonce;
    public $show_all;
    public $filter;
    public $membership_id;
    public $parent;
    public $pagination;
    public $wp_users;
    public $memberships;
    public $message;
    public $kind;
    public $user_id;
    public $user_fields;
    public $user_data;

	public function user_manager()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_users');
        
		$possible_orders = array('u.id', 'u.user_registered', 'wpl.expiry_date');

        // Apply Filters
		@extract(wpl_filters::apply('wpl_b_users_possible_orders', array('possible_orders'=>$possible_orders)));
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order') ?? ''), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
		$page_size = trim(wpl_request::getVar('page_size') ?? '') != '' ? wpl_request::getVar('page_size') : NULL;
        $this->show_all = wpl_request::getVar('show_all', 0);
		$this->filter = wpl_request::getVar('filter', '');
        $this->membership_id = wpl_request::getVar('membership_id', '');
        $this->parent = wpl_request::getVar('parent', '');

		$where_query = wpl_db::create_query();
        if(trim($this->filter ?? '')) {
			$esc_filter = wpl_db::esc_like($this->filter);
			$where_query = wpl_db::prepare(' AND (`user_login` LIKE %s OR `user_email` LIKE %s OR `first_name` LIKE %s OR `last_name` LIKE %s)', $esc_filter, $esc_filter, $esc_filter, $esc_filter);
		}
        if(trim($this->membership_id ?? '' )) $where_query = wpl_db::prepare(' AND `membership_id` = %d', $this->membership_id);
        if(trim($this->parent ?? '')) $where_query = wpl_db::prepare(' AND `parent` = %s', $this->parent);

		$num_result = wpl_db::num('SELECT COUNT(u.ID) FROM `#__users` AS u ' .($this->show_all ? 'LEFT' : 'INNER')." JOIN `#__wpl_users` AS wpl ON u.ID = wpl.id WHERE 1 $where_query");
        
		$this->pagination = wpl_pagination::get_pagination($num_result, $page_size);
		$where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;

        if($this->show_all) $this->wp_users = wpl_users::get_wp_users($where_query);
        else $this->wp_users = wpl_users::get_wpl_users($where_query);
        
		$this->memberships = wpl_users::get_wpl_memberships();
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function profile($instance = array())
	{
		// Check access
		if(!wpl_users::check_access('profilewizard'))
		{
			/** import message tpl **/
			$this->message = wpl_esc::return_html_t("You don't have access to this menu!");
			return parent::render($this->tpl_path, 'message');
		}
        
        // Is it a WPL user?
		if(!wpl_users::is_wpl_user())
		{
			/** import message tpl **/
			$this->message = wpl_esc::return_html_t("You're not an agent/owner so you cannot access this profile.");
			return parent::render($this->tpl_path, 'message');
		}
		
        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_users');
        
		$this->tpl = 'profile';
		$this->kind = wpl_flex::get_kind_id('user');
        $this->user_id = wpl_users::get_cur_user_id();
        
        if(wpl_users::is_administrator($this->user_id) and wpl_request::getVar('id', 0))
        {
            $this->user_id = wpl_request::getVar('id');
        }
        
		$this->user_fields = wpl_flex::get_fields('', 1, $this->kind, 'pwizard', 1);
		$this->user_data = (array) wpl_users::get_wpl_data($this->user_id);
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
}