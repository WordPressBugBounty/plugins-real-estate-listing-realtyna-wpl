<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');


class wpl_flex_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.flex.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		$function = wpl_request::getVar('wpl_function');

        // Check Nonce
		if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_flex')) {
			$this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('The security nonce is not valid!')));
		}

        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_flex');
        
		if($function == 'save_dbst') $this->save_dbst();
		elseif($function == 'remove_dbst')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$this->remove_dbst($dbst_id);
		}
		elseif($function == 'generate_params_page')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$this->generate_params_page($dbst_id);
		}
		elseif($function == 'enabled')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$enabled_status = wpl_request::getVar('enabled_status');
			
			$this->enabled($dbst_id, $enabled_status);
		}
		elseif($function == 'sort_flex')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			
			$this->sort_flex($sort_ids);
		}
		elseif($function == 'mandatory')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$mandatory_status = wpl_request::getVar('mandatory_status');
			
			$this->mandatory($dbst_id, $mandatory_status);
		}
        elseif($function == 'convert_dbst') $this->convert_dbst();
		elseif($function == 'sort_option')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$kind = wpl_request::getVar('kind');
			$status = (int) wpl_request::getVar('status');
			
			$this->sort_option($dbst_id, $kind, $status);
		}
		elseif($function == 'sort_categories') $this->sort_categories();
		elseif($function == 'toggle_category_status') $this->toggle_category_status();
		elseif($function == 'category_form') $this->category_form();
		elseif($function == 'update_category') $this->update_category();
		elseif($function == 'remove_category') $this->remove_category();
        // Used in RETS and Franchise addon
        elseif($function == 'get_field_options')
        {
            /** check permission **/
            wpl_global::min_access('administrator');
            
            $this->get_field_options();
        }
		elseif($function == 'get_field_values') $this->get_field_values();
		elseif($function == 'makeNullable') {
			$flexId = wpl_request::getVar('flexId');
			$this->makeNullable($flexId);
		}
	}
	
	private function mandatory($dbst_id, $mandatory_status)
	{
		$res = wpl_flex::update('wpl_dbst', $dbst_id, 'mandatory', $mandatory_status);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$this->response(['success'=>$res, 'message'=>$message, 'data' => null]);
	}

	private function category_form()
	{
		$tpl = 'internal_category_form';
		$cat_id = wpl_request::getVar('cat_id');

		if($cat_id)
		{
			$category = wpl_db::select(wpl_db::prepare('SELECT * FROM `#__wpl_dbcat` WHERE `id` = %d', $cat_id), 'loadObject');
			$this->category = $category;
		}
		
		parent::render($this->tpl_path, $tpl);
	}

	private function update_category()
	{
		$cat_id = wpl_request::getVar('cat_id');
		$name = wpl_request::getVar('category_name');

		if($cat_id)
		{
			if($name)
			{
				$category_details = array('name'=>$name);
				wpl_db::update('wpl_dbcat', $category_details, 'id', $cat_id);
				
				$success = 1;
				$message = wpl_esc::return_html_t('Category updated successfully.');
			}
			else
			{
				$success = 0;
				$message = wpl_esc::return_html_t('Category name cannot be empty.');
			}
		}
		else
		{
			if($name)
			{
				wpl_db::q(wpl_db::prepare("INSERT INTO `#__wpl_dbcat` (`name`,`prefix`,`kind`) VALUES (%s, %s, %d)", $name, 'cust', wpl_request::getVar('category_kind')), 'insert');
				
				$success = 1;
				$message = wpl_esc::return_html_t('Category updated successfully.');
			}
			else
			{
				$success = 0;
				$message = wpl_esc::return_html_t('Category name cannot be empty.');
			}
		}

		$this->response(array('success'=>$success, 'message'=>$message, 'data'=>NULL));
	}

	private function remove_category()
	{
		$cat_id = wpl_request::getVar('cat_id');
		if(!$cat_id || !is_numeric($cat_id))
		{
			$success = 0;
			$message = wpl_esc::return_html_t('The ID should not be empty.');
		}
		else
		{
			$satus = wpl_db::delete('wpl_dbcat', $cat_id);

			if($satus)
			{
				$success = 1;
				$message = wpl_esc::return_html_t('Category removed successfully.');
			}
			else
			{
				$success = 0;
				$message = wpl_esc::return_html_t('An error has occurred.');
			}
		}

		$this->response(array('success'=>$success, 'message'=>$message, 'data'=>NULL));
	}

	private function sort_categories()
	{
		$sort_ids = wpl_request::getVar('sort_ids' , '');
		if(trim($sort_ids) == '') return;

		wpl_flex::sort_flex_categories($sort_ids);
	}
	
	private function sort_flex($sort_ids)
	{
		if(trim($sort_ids ?? '') == '') $sort_ids = wpl_request::getVar('sort_ids');

		wpl_flex::sort_flex($sort_ids);
		exit;
	}

	private function toggle_category_status()
	{
		$cat_id = wpl_request::getVar('cat_id');
		$enabled = wpl_db::select(wpl_db::prepare("SELECT `enabled` FROM `#__wpl_dbcat` WHERE `id` = %d", $cat_id), 'loadResult');

		if($enabled == 1) $enabled = 0;
		else $enabled = 1;

		wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_dbcat` SET `enabled` = %s WHERE `id` = %d", $enabled, $cat_id), 'update');
		exit;
	}

	private function save_dbst()
	{
		$dbst_id = wpl_request::getVar('dbst_id', 0);
		$post = wpl_request::get('post');
		$post = stripslashes_deep($post);
		$mode = 'edit';

        // Field should be added to network
        $multisite_modify_status = wpl_request::getVar('fld_multisite_modify_status', 0);
        $current_blog_id = wpl_global::get_current_blog_id();

        // Storage
        $storage = wpl_request::getVar('fld_storage', '');
        if(trim($storage) and !in_array($storage, array('wpl_properties', 'wpl_properties2'))) $storage = 'wpl_properties';

        // Normal Storage
        if($storage == 'wpl_properties2')
        {
            $post['fld_table_name'] = 'wpl_properties2';
            $post['fld_searchmod'] = 0;
            $post['fld_text_search'] = 0;
            $post['fld_plisting'] = 0;
            $post['fld_sortable'] = 0;
        }
        // Search-able Storage
        else
        {
            $post['fld_sortable'] = 1;
        }

		/** insert new field **/
		if(!$dbst_id)
		{
			$mode = 'add';
			$dbst_id = wpl_flex::create_default_dbst();
		}

		// Previous Storage
        $previous_storage = ($mode == 'add' ? $storage : wpl_flex::get_dbst_key('table_name', $dbst_id));

        $available_columns = wpl_db::columns('wpl_dbst');

		$q = '';
		foreach($post as $field => $value)
		{
			if(substr($field, 0 ,4) != 'fld_') continue;

			$key = substr($field, 4);
            if(trim($key ?? '') == '') continue;
            if(!in_array($key, $available_columns)) continue;

			$q .= wpl_db::prepare('%i = %s, ', $key, $value);
		}
		$field_options = wpl_flex::get_field_options($dbst_id);
		if(empty($field_options)) {
		    $field_options = [];
		}

		/** add options to query **/
		$options = wpl_flex::get_encoded_options($post, 'opt_', $field_options);

		if(wpl_request::getVar('fld_type') == 'upload' && wpl_request::getVar('fld_kind') == 2) {
			$options = json_decode($options, true);
			$options['preview'] = 1;
			$options['remove_str'] = 'wpl_format=b:users:ajax&wpl_function=delete_file&item_id=[item_id]';
			$options['params'] = [
				'request_str' => "?wpl_format=b:users:ajax&wpl_function=upload_file&file_name=[html_element_id]&item_id=[item_id]&field_id=$dbst_id",
			];
			$options = json_encode($options);
		}
		$q .= wpl_db::prepare('`options` = %s, ', $options);

        if($mode == 'add' and $multisite_modify_status and wpl_global::is_multisite()) $q .= wpl_db::prepare('`source_id` = %s, ', $dbst_id . ':' . $current_blog_id);

		$q = trim($q ?? '', ", ");
		$query = wpl_db::prepare("UPDATE `#__wpl_dbst` SET " . $q . " WHERE `id` = %d", $dbst_id);

		wpl_db::q($query, 'update');

		$dbst_type = wpl_request::getVar('fld_type');
		$dbst_kind = wpl_flex::get_dbst_key('kind', $dbst_id);
		$kind_table = wpl_flex::get_kind_table($dbst_kind);

		$table_name = wpl_flex::get_dbst_key('table_name', $dbst_id);
		$table_column = wpl_flex::get_dbst_key('table_column', $dbst_id);

		if( $mode == 'edit' and ($dbst_type == 'boolean' || $dbst_type == 'select') ) {
			$saved_default_value = (!empty($field_options) && !empty($field_options['default_value'])) ? $field_options['default_value'] : '';
			$new_default_value = $post['opt_default_value'];
			if($saved_default_value != $new_default_value) {
				$new_default_value = $new_default_value === '' ? 'null' : $new_default_value;
				wpl_db::q("ALTER TABLE `#__{$table_name}` ALTER $table_column SET DEFAULT $new_default_value;");
			}
		}

		/** run queries **/
		if($mode == 'add') wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'add', $table_name);

        /** Multilingual **/
		if(wpl_global::check_addon('pro')) wpl_addon_pro::multilingual($dbst_id);

        // Normal Storage
        if($kind_table == 'wpl_properties' and trim($storage ?? '') and trim($previous_storage ?? '') and $storage != $previous_storage)
        {
            wpl_flex::change_storage($dbst_id, $storage);
        }

		/** trigger event **/
		wpl_global::event_handler('dbst_modified', array('id'=>$dbst_id, 'mode'=>$mode, 'kind'=>$dbst_kind, 'type'=>$dbst_type));

        if($multisite_modify_status and wpl_global::is_multisite())
        {
            $q .= wpl_db::prepare(", `table_column` = %s, ", $table_column);
            $q = trim($q, ', ');

            $blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
            foreach($blogs as $blog_id)
            {
                if($blog_id == $current_blog_id) continue;

                switch_to_blog($blog_id);

                if($mode == 'add')
                {
                    $dbst_id = wpl_flex::create_default_dbst();
                    $where = wpl_db::prepare('`id` = %d', $dbst_id);
                }
                else $where = wpl_db::prepare('`table_column` = %s', $table_column);

                wpl_db::q("UPDATE `#__wpl_dbst` SET ".$q." WHERE ".$where, 'update');
            }

            switch_to_blog($current_blog_id);
        }

		$this->response(array('success'=>1, 'message'=>wpl_esc::return_html_t('Field saved.'), 'data'=>NULL));
	}
	
	private function generate_params_page($dbst_id)
	{
		$params = array('element_class'=>'wpl_params_cnt', 'js_function'=>'wpl_save_params', 'id'=>$dbst_id, 'table'=>'wpl_dbst', 'html_path_message'=>'dont_show', 'close_fancybox'=>true);
		wpl_global::import_activity('params:default', '', $params);
		exit;
	}
	
	private function remove_dbst($dbst_id)
	{
		$dbst_type = wpl_flex::get_dbst_key('type', $dbst_id);
		$dbst_kind = wpl_flex::get_dbst_key('kind', $dbst_id);
		$table_name = wpl_flex::get_dbst_key('table_name', $dbst_id);
		$is_deletable = wpl_flex::get_dbst_key('deletable', $dbst_id);
		
		if($is_deletable and wpl_users::is_super_admin())
		{
			/** delete dbst row **/
			wpl_flex::remove_dbst($dbst_id);
			
			/** run queries **/
			wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'delete', $table_name);
        
			/** trigger event **/
			wpl_global::event_handler('dbst_deleted', array('id'=>$dbst_id, 'kind'=>$dbst_kind, 'type'=>$dbst_type));
			
			$success = 1;
			$message = wpl_esc::return_html_t('Field deleted.');
		}
        elseif($is_deletable and wpl_global::is_multisite())
        {
            wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_dbst` SET `enabled`='0' AND `flex`='0' WHERE `id` = %d", $dbst_id), "UPDATE");

            $success = 1;
            $message = wpl_esc::return_html_t('Field deleted.');
        }
		else
		{
			$success = 0;
			$message = wpl_esc::return_html_t('Field is not deletable.');
		}

		$this->response(array('success'=>$success, 'message'=>$message, 'data'=>NULL));
	}
	
	private function enabled($dbst_id, $enabled_status)
	{
		$res = wpl_flex::update('wpl_dbst', $dbst_id, 'enabled', $enabled_status);
		
		$res = (int) $res;
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);

		$this->response($response);
	}
    
    private function convert_dbst()
	{
		$dbst_id = wpl_request::getVar('dbst_id', 0);
        $new_type = wpl_request::getVar('type', 'select');
        
        $field_data = wpl_flex::get_field($dbst_id);
        
        $dbst_type = $field_data->type;
		$dbst_kind = $field_data->kind;
        $table_column = $field_data->table_column;
        $table_name = $field_data->table_name;
        
        $multilingual_status = wpl_global::check_multilingual_status();
        if($field_data->multilingual and $multilingual_status)
        {
            $table_column = wpl_addon_pro::get_column_lang_name($table_column, wpl_global::get_current_language(), false);
        }
        
        $values = wpl_db::select(wpl_db::prepare('SELECT %i FROM %i WHERE `kind` = %d AND %i != %s GROUP BY %i ORDER BY %i ASC', $table_column, "#__$table_name", $dbst_kind, $table_column, '', $table_column, $table_column), 'loadColumn');

		$options = array();

		if($new_type == 'feature')
		{
			if(!wpl_db::columns($table_name, 'f_'.$dbst_id))
			{
				// make a new feature column for dbst
				wpl_db::q(wpl_db::prepare('ALTER TABLE %i ADD %i text NULL, ADD %i tinyint(4) NULL;', "#__$table_name", "f_{$dbst_id}_options", "f_{$dbst_id}"));
			}

			$select_dbst_values = wpl_flex::get_field_values($dbst_id);

			$options['type'] = 'single';
			$options['values'] = array();

			$arrange_dbst_value = array();
			foreach($select_dbst_values as $select_dbst_value) $arrange_dbst_value[$select_dbst_value['value']] = $select_dbst_value;

			foreach($values as $value)
			{
				$dbst_value = $arrange_dbst_value[$value]['label'] ?? '';
				if(trim($dbst_value) == '') continue;

				$dbst_value = trim($dbst_value, ',');

				$keys = array();
				if(stristr($dbst_value, ','))
				{
					$exp_values = explode(',', $dbst_value);

					foreach($exp_values as $exp_value)
					{
						$last_key = count($options['values'])+1;

						$duplicate = 0;

						foreach($options['values'] as $key => $option_value)
						{
							if(strtolower($option_value['value']) == strtolower($exp_value))
							{
								$keys[] = $key;
								$duplicate = 1;
							}
						}

						if(!$duplicate)
						{
							$options['values'][$last_key] = array('value'=>$exp_value, 'key'=>$last_key, 'enabled'=>1);
							$keys[] = $last_key;
						}

					}
				}
				else
				{
					$max_id = count($options['values'])+1;

					$duplicate = 0;

					foreach($options['values'] as $key => $option_value)
					{
						if(strtolower($option_value['value']) == strtolower($dbst_value))
						{
							$keys[] = $key;
							$duplicate = 1;
						}
					}

					if(!$duplicate)
					{
						$options['values'][$max_id] = array('value'=>$dbst_value, 'key'=>$max_id, 'enabled'=>1);
						$keys[] = $max_id;
					}
				}

				//update the data with the old value
				wpl_db::q(wpl_db::prepare("UPDATE %i SET %i = %s, %i = 1 WHERE %i = %s", "#__$table_name", "f_{$dbst_id}_options", ','.implode(',',array_unique($keys)).',', "f_$dbst_id", $table_column, $value));
			}

			//update the DBST column and change to feature type
			wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_dbst` SET `table_name` = %s, `table_column` = %s, `type` = 'feature', `options` = %s WHERE `id` = %d", $table_name, "f_$dbst_id", json_encode($options ?? ''), $dbst_id));
		}
		else
		{
            $options['params'] = array();

            $i = 0;
            foreach($values as $value)
            {
                if(trim($value ?? '') == '') continue;

                $value = stripslashes($value);

                $i++;
                $options['params'][$i] = array('key'=>$i, 'enabled'=>1, 'value'=>$value);

                if($field_data->multilingual  and $multilingual_status)
                {
                    $columns = wpl_global::get_multilingual_columns(array($table_column), true, $table_name);
                    foreach($columns as $column)
                    {
                        wpl_db::q(wpl_db::prepare("UPDATE %i SET %i = %s WHERE `kind` = %d AND %i = %s", "#__$table_name", $column, $i, $dbst_kind, $column, $value));
                    }
                }
                else wpl_db::q(wpl_db::prepare("UPDATE %i SET %i = %s WHERE `kind` = %d AND %i = %s", "#__$table_name", $table_column, $i, $dbst_kind, $table_column, $value));
            }

            wpl_db::q(wpl_db::prepare("UPDATE `#__wpl_dbst` SET `options` = %s, `type` = %s WHERE `id` = %d", json_encode($options ?? ''), $new_type, $dbst_id));
		}

		/** trigger event **/
		wpl_global::event_handler('dbst_converted', array('id'=>$dbst_id, 'new_type'=>$new_type, 'kind'=>$dbst_kind, 'previous_type'=>$dbst_type));

		$this->response(array('success'=>1, 'message'=>wpl_esc::return_html_t('Field converted.'), 'data'=>NULL));
	}
	
	private function sort_option($dbst_id, $kind, $status)
	{
		$res = intval($status) ? wpl_sort_options::add_sort_option($dbst_id, $kind) : wpl_sort_options::remove_sort_option($dbst_id, $kind);
		
		$message = $res ? wpl_esc::return_html_t('Operation was successful.') : wpl_esc::return_html_t('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);

		$this->response($response);
	}
    
    public function get_field_options()
    {
        $id = wpl_request::getVar('id', 0);
        $field = (array) wpl_flex::get_field($id);
        
        $data = array();
        $data['field'] = $field;
        $data['operators'] = array();
        $data['options'] = array();
        
        if(in_array($field['type'], array('select', 'listings', 'property_types')))
        {
            $data['operators'] = array(array('name'=>wpl_esc::return_html_t('Include'), 'key'=>'IN'), array('name'=>wpl_esc::return_html_t('Exclude'), 'key'=>'NOTIN'));
            $options = array();
            
            if($field['type'] == 'select')
            {
                $options = json_decode($field['options'] ?? '', true);
                
                $params = array();
                foreach($options['params'] as $param) $params[] = array('key'=>$param['key'], 'name'=>wpl_esc::return_html_t($param['value']));
                
                $data['options'] = $params;
            }
            elseif($field['type'] == 'listings')
            {
                $raw_listings = wpl_global::get_listings();
                
                $listings = array();
                foreach($raw_listings as $raw_listing) $listings[] = array('key'=>$raw_listing['id'], 'name'=>wpl_esc::return_html_t($raw_listing['name']));
                
                $data['options'] = $listings;
            }
            elseif($field['type'] == 'property_types')
            {
                $raw_property_types = wpl_global::get_property_types();
                
                $property_types = array();
                foreach($raw_property_types as $raw_property_type) $property_types[] = array('key'=>$raw_property_type['id'], 'name'=>wpl_esc::return_html_t($raw_property_type['name']));
                
                $data['options'] = $property_types;
            }
        }
        elseif(in_array($field['type'], array('number', 'area', 'price', 'length', 'volume'))) $data['operators'] = array(array('name'=>wpl_esc::return_html_t('Greater'), 'key'=>'GREATER'), array('name'=>wpl_esc::return_html_t('Smaller'), 'key'=>'SMALLER'), array('name'=>wpl_esc::return_html_t('Include'), 'key'=>'IN'), array('name'=>wpl_esc::return_html_t('Exclude'), 'key'=>'NOTIN'));
        elseif(in_array($field['type'], array('locations'))) $data['operators'] = array(array('name'=>wpl_esc::return_html_t('Contains'), 'key'=>'LIKE'));
        else $data['operators'] = array(array('name'=>wpl_esc::return_html_t('Contains'), 'key'=>'LIKE'), array('name'=>wpl_esc::return_html_t('Include'), 'key'=>'IN'), array('name'=>wpl_esc::return_html_t('Exclude'), 'key'=>'NOTIN'));

		$this->response(array('success'=>1, 'data'=>$data));
    }

	private function get_field_values()
    {
		// Check Nonce
		if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_flex')) $this->response(array('success'=>0, 'message'=>wpl_esc::return_html_t('The security nonce is not valid!')));

		$id = wpl_request::getVar('dbst_id', 0);
        $field = (array) wpl_flex::get_field($id);

		if(in_array($field['type'], array('neighborhood', 'boolean', 'checkbox'))){
			$data = array(
				"values" => array(
					array("key" => "0", "value" => wpl_esc::return_html_t('No')),
					array("key" => "1", "value" => wpl_esc::return_html_t('Yes'))
				)
			);
		}else{
			$data = json_decode($field['options']);
		}
		$this->response(array('success'=>1, 'data'=>$data));
	}

	private function makeNullable($flexId)
	{
		wpl_flex::makeNullable($flexId);
		$this->response(array('success'=> true, 'message'=> wpl_esc::return_html_t('Operation was successful.')));
	}
}