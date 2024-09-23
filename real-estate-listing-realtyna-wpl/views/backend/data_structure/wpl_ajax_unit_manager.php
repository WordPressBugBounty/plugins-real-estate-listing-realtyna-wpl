<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	public $unit_id;
	public $unit_type;
	public $units;
	public $nonce;

	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');

        $this->verifyNonce(wpl_request::getVar('_wpnonce', ''), 'wpl_data_structure');
        $this->setViewVar('nonce', wpl_security::create_nonce('wpl_data_structure'));
        
		if($function == 'generate_new_page')
		{
			$type = wpl_request::getVar('type');
			$this->generate_new_page($type);
		}
		elseif($function == 'sort_units')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_units($sort_ids);
		}
		elseif($function == 'unit_enabled_state_change')
		{
			$unit_id = wpl_request::getVar('unit_id');
			$enabled_status = wpl_request::getVar('enabled_status');

			// check if its last item for disable prevent from this action and Check listing if it has this unit price
			if($this->check_eligibility($unit_id, $enabled_status) === true) $this->update($unit_id, 'enabled', $enabled_status);
		}		
		elseif($function == 'unit_enabled_state_replace_form')
		{
			$this->unit_enabled_state_replace_form();
		}
		elseif($function == 'replaceunit_with_activeunit')
		{
			$this->replaceunit_with_activeunit();
		}
		elseif($function == 'update_exchange_rates')
		{			
			$this->update_exchange_rates();
		}
		elseif($function == 'update_a_exchange_rate')
		{			
			$unit_id = wpl_request::getVar('unit_id');			
			$currency_code = wpl_request::getVar('currency_code');

			$this->update_a_exchange_rate($unit_id, $currency_code);
		}
		elseif($function == 'modify_unit')
		{
			$id = wpl_request::getVar('id');
			$field = wpl_request::getVar('field');
			$value = wpl_request::getVar('value');

			$this->update($id, $field, $value);
		}		
	}

	/**
	* $type is a unit type for filtering
    * @param int $type
	**/
	private function generate_new_page($type)
	{		
		$this->setViewVar('units', wpl_units::get_units($type,"",""));
		
		if($type == 4) parent::render($this->tpl_path, 'internal_unit_manager_currency');
		else parent::render($this->tpl_path, 'internal_unit_manager_general');
		
		exit;
	}
	
	/**
	* this function call update function in units library and change value of a field
    * @param int $unit_id
    * @param string $key
    * @param string $value
	**/
	private function update($unit_id, $key, $value = '')
	{
		$res = wpl_units::update($unit_id, $key, sanitize_text_field($value));
		
		$res = (int) $res;
		$message = $res !== false ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$this->response(array('success'=>$res, 'message'=>$message, 'data'=>null));
	}
	
	private function sort_units($sort_ids)
	{
		if(trim($sort_ids ?? '') == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_units::sort_units($sort_ids);		
		exit;
	}	
	
	/**
	*	call wpl_units::update_exchange_rates for connect to yahoo
	*	server and exchange currency rates
	**/
	private function update_exchange_rates()
	{
		wpl_units::update_exchange_rates();			
	}

	/**
	* get a currency id and exchange rate it by unit library
    * @param int $unit_id
    * @param string $currency_code
	**/
	private function update_a_exchange_rate($unit_id, $currency_code)
	{
		$res = wpl_units::update_a_exchange_rate($unit_id, $currency_code);
		$this->response(array('success'=>(bool)$res, 'res'=>$res));
	}	

	/**
	* Check for eligibility for disable enable item action
    * @param int $unit_id
    * @param int $enabled
    * @return mixed
	**/
	private function check_eligibility($unit_id, $enabled)
	{
		$count = 0;
		$get_unit = wpl_units::get_unit($unit_id);
		$units = wpl_units::get_units('', 1, '');

		foreach($units as $id => $unit) 
		{
			if((int) $unit['enabled'] == 1 && $unit['type'] === $get_unit['type']) $count++;
		}

		if((int) $count === 1 && (int) $enabled === 0) wp_send_json(array('success'=>0, 'message'=>wpl_esc::return_html_t('You should have one active unit.'), 'data'=>''));

		// if unit is disabled and want to be enabled don't check rest of actions
		if((int) $enabled === 1) return true;

		// get table names
		$columns = wpl_property::get_units_table_cols($get_unit['type']);

		// check this unit id in whole columns
		$response = wpl_property::check_unit_columns($columns, $unit_id);
		if($response > 0) wp_send_json(array('success'=> -1, 'message'=> $get_unit['type'], 'data'=>''));

		return true;
	}

	/**
	*	Include form for replacing units
	**/
	private function unit_enabled_state_replace_form()
	{
		// get units
		$unit_type = wpl_request::getVar('unit_type');
		$unit_id   = wpl_request::getVar('unit_id');
		
		// get all units from db
		$units = wpl_units::get_units($unit_type, 1, '');

		$this->setViewVars(compact('unit_type', 'unit_id', 'units'));
		/** include the layout **/
		parent::render($this->tpl_path, 'internal_unit_replace');
		exit;
	}

	/**
	*	Replace old unit with new unit for active listings
	**/
	private function replaceunit_with_activeunit()
	{
		$old_unit = wpl_request::getVar('old_unit');
		$new_unit = wpl_request::getVar('new_unit');
		$unit_type = wpl_request::getVar('type');

		$response = wpl_property::update_listing_units($old_unit, $new_unit, $unit_type);

		// now disable the unit
		$this->update($old_unit, 'enabled', 0);

		// Check for response
		if($response > 0) wp_send_json(array('success'=> 1, 'message'=> wpl_esc::return_html_t('Listings changed successfully.'), 'data'=>$response));
		else wp_send_json(array('success'=> 0, 'message'=> wpl_esc::return_html_t('No listing found.'), 'data'=>''));
	}	
}