<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_users_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.users.tmpl';
	public $tpl;

    /**
     * Extra columns wpl_flex stores next to a dbst field, keyed by field type.
     *
     * Mirrors the column list wpl_flex::change_storage() builds, so a field the profile wizard
     * renders is accepted together with the companion columns its editor posts.
     *
     * @var array
     */
    protected static $column_suffixes = array(
        'feature'      => array('_options'),
        'neighborhood' => array('_distance', '_distance_by'),
        'area'         => array('_si', '_unit'),
        'length'       => array('_si', '_unit'),
        'volume'       => array('_si', '_unit'),
        'price'        => array('_si', '_unit'),
        'mmarea'       => array('_si', '_max', '_max_si', '_unit'),
        'mmlength'     => array('_si', '_max', '_max_si', '_unit'),
        'mmvolume'     => array('_si', '_max', '_max_si', '_unit'),
        'mmprice'      => array('_si', '_max', '_max_si', '_unit'),
        'mmnumber'     => array('_max'),
    );

    /**
     * Confirms the caller is allowed to modify $user_id's record
     *
     * item_id/id arrived from the request and was never compared against the current user, so any
     * caller could act on any other user's record. Administrators keep managing every WPL user;
     * everybody else is limited to their own record.
     *
     * @param int|string $user_id
     * @return int The validated user id
     */
    private function assert_can_edit_user($user_id)
    {
        $user_id = (int) $user_id;

        if($user_id <= 0)
        {
            $this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('Invalid user.')));
        }

        if(!wpl_users::is_administrator() and $user_id !== (int) wpl_users::get_cur_user_id())
        {
            $this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('Permission denied.')));
        }

        return $user_id;
    }

    /**
     * Returns the tables and columns of the user kind, as table_name => array of columns
     *
     * @param boolean $wizard_only Limit to the fields the profile wizard renders
     * @return array
     */
    private function user_field_map($wizard_only = true)
    {
        $kind = wpl_flex::get_kind_id('user');

        $fields = $wizard_only
            ? wpl_flex::get_fields('', 1, $kind, 'pwizard', 1)
            : wpl_flex::get_fields('', 0, $kind);

        $map = array();

        foreach((array) $fields as $field)
        {
            if(!trim($field->table_name ?? '') or !trim($field->table_column ?? '')) continue;

            $map[$field->table_name][] = $field->table_column;

            foreach((self::$column_suffixes[$field->type] ?? array()) as $suffix)
            {
                $map[$field->table_name][] = $field->table_column.$suffix;
            }
        }

        return $map;
    }

    /**
     * Confirms $table_name/$table_column is a field these endpoints may write
     *
     * Both values arrived from the request, and the only guard compared $table_name against the
     * literal 'wpl_users'. Any other name was accepted, and wpl_db::set() passes it through
     * wpl_db::_prefix(), where '#__users' resolves to the WordPress users table, so an agent could
     * overwrite an administrator's user_pass. Deriving the allowed pairs from the dbst definitions
     * also stops a user writing the access_* and maccess_* columns that hold their own WPL
     * permissions, since those are not part of the profile wizard.
     *
     * @param string $table_name
     * @param string $table_column
     */
    private function assert_editable_user_field($table_name, $table_column)
    {
        $table_name = (string) $table_name;
        $table_column = (string) $table_column;

        /** administrators edit the whole record through the user manager **/
        $is_admin = wpl_users::is_administrator();
        $map = $this->user_field_map(!$is_admin);

        $allowed = isset($map[$table_name]);

        if($allowed and $is_admin)
        {
            /** any real column of a user table, so the user manager keeps editing non dbst columns **/
            $allowed = in_array($table_column, (array) wpl_db::columns($table_name), true);
        }
        elseif($allowed)
        {
            $allowed = in_array($table_column, $map[$table_name], true);
        }

        if(!$allowed)
        {
            $this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('This field cannot be edited here.')));
        }
    }

    /**
     * Returns a dbst field when it belongs to the user kind, otherwise NULL
     *
     * field_id arrived from the request and selected both the upload extension list and the target
     * column, so a field from another kind could be pointed at a user record.
     *
     * @param int|string $field_id
     * @return object|NULL
     */
    private function get_user_field($field_id)
    {
        if(!trim($field_id ?? '')) return NULL;

        $field = wpl_flex::get_field($field_id);
        if(!$field or !isset($field->kind)) return NULL;

        if((int) $field->kind !== (int) wpl_flex::get_kind_id('user')) return NULL;

        return $field;
    }

	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
		
        // Check Nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_users')) {
			$this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('The security nonce is not valid!')));
		}
        
		if($function == 'add_user_to_wpl')
		{
			/** check permission **/
			wpl_global::min_access('administrator');
		
			$user_id = wpl_request::getVar('user_id');
			$this->add_user_to_wpl($user_id);
		}
		elseif($function == 'del_user_from_wpl')
		{
			/** check permission **/
			wpl_global::min_access('administrator');
			
			$user_id = wpl_request::getVar('user_id');
			$confirmed = wpl_request::getVar('wpl_confirmed', 0);
			
			$this->del_user_from_wpl($user_id, $confirmed);
		}
		elseif($function == 'generate_edit_page')
		{
			/** check permission **/
			wpl_global::min_access('administrator');
			
			$user_id = wpl_request::getVar('user_id');
			$this->generate_edit_page($user_id);
		}
		elseif($function == 'save_user')
		{
			/** check permission **/
			wpl_global::min_access('administrator');
			
			$inputs = wpl_request::get('POST');
			$this->save_user($inputs);
		}
		elseif($function == 'save')
		{
            /** check permission **/
            wpl_global::min_access('agent');
        
			$table_name = wpl_request::getVar('table_name', 'wpl_users');
			$table_column = wpl_request::getVar('table_column');
			$value = wpl_request::getVar('value');
			$item_id = wpl_request::getVar('item_id');
			
			$this->save($table_name, $table_column, $value, $item_id);
		}
		elseif($function == 'change_membership')
		{
            /** changing a membership is an administrative action, not something an agent may do **/
            wpl_global::min_access('administrator');

			$user_id = wpl_request::getVar('id');
			$membership_id = wpl_request::getVar('membership_id');

			$this->change_membership($user_id, $membership_id);
		}
		elseif($function == 'location_save')
		{
            /** check permission **/
            wpl_global::min_access('agent');
        
			$table_name = wpl_request::getVar('table_name');
			$table_column = wpl_request::getVar('table_column');
			$value = wpl_request::getVar('value');
			$item_id = wpl_request::getVar('item_id');
			
			$this->location_save($table_name, $table_column, $value, $item_id);
		}
		elseif($function == 'finalize')
		{
            /** check permission **/
            wpl_global::min_access('agent');
            
			$item_id = wpl_request::getVar('item_id');
			$this->finalize($item_id);
		}
		elseif($function == 'upload_file')
		{
            /** check permission **/
            wpl_global::min_access('agent');
            
			$file_name = wpl_request::getVar('file_name');
			$user_id = wpl_request::getVar('item_id');
			
			$this->upload_file($file_name, $user_id);
		}
		elseif($function == 'delete_file')
		{
            /** check permission **/
            wpl_global::min_access('agent');
            
			$field_id = wpl_request::getVar('field_id');
			$user_id = wpl_request::getVar('item_id');
			
			$this->delete_file($field_id, $user_id);
		}
        elseif($function == 'save_multilingual')
        {
            /** check permission **/
            wpl_global::min_access('agent');
            
            $this->save_multilingual();
        }
        elseif($function == 'renew_membership')
        {
            /** renewing extends a paid membership, so it stays with administrators **/
            wpl_global::min_access('administrator');

            $this->renew_membership();
        }
        elseif($function == 'expire_membership')
        {
            /** check permission **/
            wpl_global::min_access('administrator');
            
            $this->expire_membership();
        }
        elseif($function == 'change_parent')
        {
            /** reparenting changes which agent a user reports to, so it stays with administrators **/
            wpl_global::min_access('administrator');

            $user_id = wpl_request::getVar('id');
            $parent = wpl_request::getVar('parent');

            $this->change_parent($user_id, $parent);
        }
        elseif($function == 'autocomplete')
        {
			wpl_global::min_access('administrator');
            $this->autocomplete();
        }
	}

	private function autocomplete() {
		$page = wpl_request::getVar('page');
		$offset = (intval($page) - 1) * 20;
		if($offset < 0) {
			$offset = 0;
		}
		$condition = '';
		$search = wpl_request::getVar('search');
		if(!empty($search)) {
			$condition = wpl_db::prepare(' AND user_login LIKE %s', wpl_db::esc_like($search));
		}
		$wpl_users = wpl_db::select("SELECT * FROM `#__users` AS u INNER JOIN `#__wpl_users` AS wpl ON u.ID = wpl.id WHERE 1 $condition order by wpl.id LIMIT $offset, 40");
		$response = [
			'success' => true,
			'data' => array_values(array_map(function ($user) {return ['id' => $user->ID, 'name' => $user->user_login];}, $wpl_users))
		];
		$this->response($response);
	}
	
	private function add_user_to_wpl($user_id)
	{
		$res = wpl_users::add_user_to_wpl($user_id);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('User added to WPL successfully.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function del_user_from_wpl($user_id, $confirmed = 0)
	{
		if($confirmed) $res = wpl_users::delete_user_from_wpl($user_id);
		else $res = false;
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('User removed from WPL successfully.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function generate_edit_page($user_id = '')
	{
		$this->user_info = wpl_users::get_user($user_id);
		$this->fields = wpl_db::columns('wpl_users');
        
        $this->user_data = wpl_users::get_wpl_user($user_id);
        $this->data = $this->user_data;
        
        $this->units = wpl_units::get_units(4);
		$this->listings = wpl_listing_types::get_listing_types();
		$this->property_types = wpl_property_types::get_property_types();
		$this->memberships = wpl_users::get_wpl_memberships();
		$this->membership_types = wpl_users::get_user_types();
		$this->users =wpl_users::get_wpl_users();
		parent::render($this->tpl_path, 'edit');
		exit;
	}
    
    public function generate_tab($tpl = 'internal_setting_advanced')
	{
		if($tpl == 'internal_setting_crm')
		{
			if(!wpl_global::check_addon('crm'))
			{
				wpl_esc::html_t('The CRM Add-on must be installed for this feature!');
				return;	
			}
		}
        elseif(!wpl_global::check_addon('membership')) /** checking PRO addon **/
		{
			wpl_esc::html_t('The Membership Add-on must be installed for this feature!');
			return;
		}
        
		/** include the layout **/
		parent::render($this->tpl_path, $tpl);
	}
	
	private function save_user($inputs)
	{
		$res = $this->save_user_do($inputs);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	public function save_user_do($inputs)
	{
		$restricted_fields = array('page', 'wpl_format', 'wpl_function', 'function', 'id');
        
		/** edit user **/
		$query = "";
		$id = $inputs['id'];
		$columns = wpl_db::columns('wpl_users');
        $crm_access = array();

		/** set restriction to none **/
		if(!isset($inputs['maccess_lrestrict'])) $inputs['maccess_listings'] = '';
		if(!isset($inputs['maccess_ptrestrict'])) $inputs['maccess_property_types'] = '';
		$crm_changed = false;
		foreach($inputs as $field=>$value)
		{
			if(substr($field, 0, 11) == 'maccess_crm')
			{
			    $crm_changed = true;
				if($value == 1)	$crm_access[] = substr($field, 11);
				continue;
			}
			
			if(in_array($field, $restricted_fields) or !in_array($field, $columns)) continue;
			
			$query .= wpl_db::prepare('%i = %s, ', $field, $value);
		}

		/** update CRM access list if available **/
		if(count($crm_access) > 0 || $crm_changed)
		{
			$query .= wpl_db::prepare('`maccess_crm` = %s, ', implode(',', $crm_access));
		}
        
        // RETS Addon
        if(isset($inputs['rets_prefilter']) and is_array($inputs['rets_prefilter']) and wpl_global::check_addon('rets'))
        {
            $valid_filters = array();
            foreach($inputs['rets_prefilter'] as $column=>$prefilter)
            {
                // Filter is Removed
                if(!isset($prefilter['removed']) or (isset($prefilter['removed']) and $prefilter['removed'])) continue;
                
                $valid_filters[$column] = $prefilter;
            }
            
            $query .= wpl_db::prepare('`maccess_rets_prefilters` = %s, ', json_encode($valid_filters));
        }
		
		$query = rtrim($query ?? '', ', ');
		$query = wpl_db::prepare("UPDATE `#__wpl_users` SET " . $query . " WHERE `id` = %d", $id);
		
		/** update user **/
		wpl_db::q($query);
        
        // Renew the user if period is set to unlimited
        if(isset($inputs['maccess_period']) and $inputs['maccess_period'] == '-1' and wpl_global::check_addon('membership'))
        {
            _wpl_import('libraries.addon_membership');
            
            $membership = new wpl_addon_membership();
            $membership->renew($id);
        }
        
		return true;
	}
	
	private function save($table_name, $table_column, $value, $item_id)
	{
	    $item_id = $this->assert_can_edit_user($item_id);
	    $this->assert_editable_user_field($table_name, $table_column);

		$field_type = wpl_global::get_db_field_type($table_name, $table_column);
		if($field_type == 'datetime' or $field_type == 'date') $value = wpl_render::derender_date($value);
		
		$res = wpl_db::set($table_name, $item_id, $table_column, $value, 'id');
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
    
    private function save_multilingual()
	{
		$dbst_id = wpl_request::getVar('dbst_id');
        $value = wpl_request::getVar('value');
        $item_id = $this->assert_can_edit_user(wpl_request::getVar('item_id'));
        $lang = wpl_request::getVar('lang');

        /** dbst_id came from the request, so keep it inside the user kind **/
        $field = $this->get_user_field($dbst_id);
        if(!$field)
        {
            $this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('This field cannot be edited here.')));
        }

        $table_name = $field->table_name;
        $table_column1 = wpl_addon_pro::get_column_lang_name($field->table_column, $lang, false);
        $default_language = wpl_addon_pro::get_default_language();
        
        $table_column2 = NULL;
        if(strtolower($default_language) == strtolower($lang)) $table_column2 = wpl_addon_pro::get_column_lang_name($field->table_column, $lang, true);
        
		wpl_db::set($table_name, $item_id, $table_column1, $value, 'id');
        if($table_column2) wpl_db::set($table_name, $item_id, $table_column2, $value, 'id');
        
		$res = 1;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function change_membership($user_id, $membership_id)
	{
		/** changing membership of the user **/
		wpl_users::change_membership($user_id, $membership_id);
		
		$res = 1;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function location_save($table_name, $table_column, $value, $item_id)
	{
	    $item_id = $this->assert_can_edit_user($item_id);
	    $this->assert_editable_user_field($table_name, $table_column);

		$location_settings = wpl_global::get_settings('3'); # location settings
		
		$location_level = str_replace('_id', '', $table_column ?? '');
		$location_level = substr($location_level, -1);
		
		if($table_column == 'zip_id') $location_level = 'zips';
		
		$location_data = wpl_locations::get_location($value, $location_level);
		$location_name_column = $location_level != 'zips' ? 'location'.$location_level.'_name' : 'zip_name';
		
		/** update property location data **/
		if($location_settings['location_method'] == 2 or ($location_settings['location_method'] == 1 and in_array($location_level, array(1, 2)))) $res = wpl_db::update($table_name, array($table_column=>$value, $location_name_column=>$location_data->name), 'id', $item_id);
		else $res = wpl_db::update($table_name, array($location_name_column=>$value), 'id', $item_id);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function finalize($user_id)
	{
	    $user_id = $this->assert_can_edit_user($user_id);

		wpl_users::finalize($user_id);
		
		$res = 1;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
	
	private function upload_file($file_name, $user_id)
	{
	    $user_id = $this->assert_can_edit_user($user_id);

		$file = wpl_request::getVar($file_name, '', 'FILES');

		/**
		 * $file['name'] is chosen by the client. It used to be written through unchanged, so a name
		 * carrying path segments escaped the item folder and could overwrite files elsewhere.
		 **/
		$filename = sanitize_file_name(basename((string) ($file['name'] ?? '')));

		$ext_array = array('jpg','png','gif','jpeg','webp');

		/** field_id also came from the request, so only a user kind field may widen the list **/
		$field_id = wpl_request::getVar('field_id');
		$field = $this->get_user_field($field_id);

		if($field)
		{
			$field_options = wpl_flex::get_field_options($field->id);
			if(!empty($field_options['ext_file']))
			{
				$ext_array = wpl_global::filter_extensions(explode(',', $field_options['ext_file']));
			}
		}
		else $field_id = '';

		$error = "";
		$message = "";

		if(!empty($file['error']) or (empty($file['tmp_name']) or ($file['tmp_name'] == 'none')))
		{
			$error = wpl_esc::return_html_t('An error ocurred uploading your file.');
		}
		elseif($filename === '')
		{
			$error = wpl_esc::return_html_t('An error ocurred uploading your file.');
		}
		else
		{
			// check the extension of the sanitized name, which is what gets written
			$extension = strtolower(wpl_file::getExt($filename));

			if(!in_array($extension, $ext_array, true))
			{
				$error = wpl_esc::return_html_t('File extension should be .jpg, .png or .gif.');
			}

			if($error == '')
			{
				if($file_name == 'wpl_c_912') # profile picture
				{
					/** delete previous file **/
					$this->delete_file(912, $user_id, false);
					
					$new_file_name = 'profile.'.$extension;
                    
					/** save into db and add to items **/
					wpl_db::set('wpl_users', $user_id, 'profile_picture', $new_file_name);
				}
				elseif($file_name == 'wpl_c_913') # company logo
				{
					/** delete previous file **/
					$this->delete_file(913, $user_id, false);
					
					$new_file_name = 'logo.'.$extension;
					
					/** save into db and add to items **/
					wpl_db::set('wpl_users', $user_id, 'company_logo', $new_file_name);
				}				
				elseif($file_name == 'wpl_c_4104') # cover image
				{
					/** delete previous file **/
					$this->delete_file(4104, $user_id, false);
					
					$new_file_name = 'cover.'.$extension;
					
					/** save into db and add to items **/
					wpl_db::set('wpl_users', $user_id, 'agent_cover', $new_file_name);
				}
				elseif($field and $field->table_name == 'wpl_users')
				{
					$new_file_name = $filename;

					$this->delete_file($field->id, $user_id, false);
					wpl_db::set('wpl_users', $user_id, $field->table_column, $new_file_name);
				}
				else
				{
					/** nothing identifies where this file belongs, so do not write it **/
					$error = wpl_esc::return_html_t('Invalid upload target.');
				}

				if($error == '')
				{
					$dest = wpl_items::get_path($user_id, 2). $new_file_name;
					wpl_file::upload($file['tmp_name'], $dest);
				}
			}
		}

		$res = $error == '' ? 1 : 0;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');

		$response = array('success'=>$res, 'error'=>$error, 'message'=>$message);
		$this->response($response);
	}

	private function delete_file($field_id, $user_id, $output = true)
	{
		$user_id = $this->assert_can_edit_user($user_id);

		/** field_id reaches here from the request, so keep it inside the user kind **/
		$field = $this->get_user_field($field_id);

		if(!$field or $field->table_name != 'wpl_users')
		{
			if(!$output) return;
			$this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('This field cannot be edited here.')));
		}

		$field_data = (array) $field;
		$user_data = (array) wpl_users::get_wpl_user($user_id);

		/** the stored value is a bare file name; strip any path in case an older row carries one **/
		$stored_name = basename((string) ($user_data[$field_data['table_column']] ?? ''));
		if($stored_name === '')
		{
			if(!$output) return;
			$this->response(array('success'=>1, 'message'=>wpl_esc::return_html_t('Saved.'), 'data'=>NULL));
		}

		$path = wpl_items::get_path($user_id, $field_data['kind'], null, false). $stored_name;

		/** delete file and reset db **/
		wpl_file::delete($path);
		wpl_db::set('wpl_users', $user_id, $field_data['table_column'], '');
        
        /** delete thumbnails **/
        wpl_users::remove_thumbnails($user_id);
		
		/** called from other functions (upload function) **/
		if(!$output) return;
		
		$res = 1;
		$message = $res ? wpl_esc::return_html_t('Saved.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
    
    private function renew_membership()
	{
        $user_id = wpl_request::getVar('id', 0);
        
        _wpl_import('libraries.addon_membership');
        $membership = new wpl_addon_membership();
        $membership->renew($user_id);
        
        $user_data = wpl_users::get_wpl_data($user_id);
		
		$res = 1;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		/** An unlimited membership has no expiry date, it is NULL on MySQL 8 and '0000-00-00 00:00:00' on databases that were not migrated **/
		$data = array('expiry_date'=>$membership->render_date($user_data->expiry_date ?? ''));
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		$this->response($response);
	}
    
    private function expire_membership()
	{
        $user_id = wpl_request::getVar('id', 0);

        if($user_id)
        {
			_wpl_import('libraries.addon_membership');
            
	        $membership = new wpl_addon_membership();
	        $res = $membership->expired($user_id);
            
			$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
			$response = array('success'=>$res, 'message'=>$message, 'data'=>NULL);
        }
        else
        {
        	$response = array('success'=>0, 'message'=>wpl_esc::return_html_t('Error Occured.'), 'data'=>NULL);
        }
		
		$this->response($response);
	}

    private function change_parent($user_id, $parent)
    {
        // Change Parent of User
        wpl_users::change_parent($user_id, $parent);

        $res = 1;
        $message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
        $data = NULL;

        $response = array('success'=>$res, 'message'=>$message, 'data'=>$data);

		$this->response($response);
    }
}