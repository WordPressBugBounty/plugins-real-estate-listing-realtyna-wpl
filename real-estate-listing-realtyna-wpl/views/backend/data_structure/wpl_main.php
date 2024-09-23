<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.units');
_wpl_import('libraries.sort_options');
_wpl_import('libraries.room_types');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$possible_orders = array('index', 'id', 'title');
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order') ?? ""), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
        // Create Nonce
        $nonce = wpl_security::create_nonce('wpl_data_structure');

        $this->setViewVars(compact('orderby', 'order', 'nonce'));
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function generate_property_types()
	{
		$tpl = 'internal_property_types';
        
		$property_types = wpl_property_types::get_property_types();
        $categories = wpl_property_types::get_property_type_categories();

        $this->setViewVars(compact('property_types', 'categories'));
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_sort_options()
	{
		$tpl = 'internal_sort_options';
		$sort_options = wpl_sort_options::get_sort_options('', 0, '', 'loadAssocList', true);

        $this->setViewVars(compact('sort_options'));
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_room_types()
	{
		$tpl = 'internal_room_types';
		$room_types = wpl_room_types::get_room_types("","");
		$folder = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'rooms';
		$icons = wpl_global::get_icons($folder);

        $this->setViewVars(compact('room_types', 'icons'));
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_listing_types()
	{
		$tpl = 'internal_listing_types';
		$listing_types = wpl_listing_types::get_listing_types();
		$listing_gicons = wpl_listing_types::get_map_icons();
		$get_caption_imgs = wpl_listing_types::get_caption_images();
		$listing_types_categories = wpl_listing_types::get_listing_type_categories();

        $this->setViewVars(compact(
            'listing_types',
            'listing_gicons',
            'get_caption_imgs',
            'listing_types_categories'
        ));
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_unit_manager()
	{
		$tpl = 'internal_unit_manager_default';
		$unit_types = wpl_units::get_unit_types();
		$units = wpl_units::get_units(4, '', '');

        $this->setViewVars(compact('unit_types', 'units'));
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_currency_page()
	{
		$units = wpl_units::get_units(4, '', '');

        $this->setViewVars(compact('units'));
		/** import tpl **/
		parent::render($this->tpl_path, 'internal_unit_manager_currency');
	}
}
