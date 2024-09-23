<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Class wpl_property_import
 * @author Howard R <howard@realtyna.com>
 * @since WPL4.4.1
 * @package WPL
 * @date 02/01/2019
 */
class wpl_property_import
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * @var string
     */
    private $unique_field;

    /**
     * @var integer
     */
    private $user_id;

    /**
     * @var string
     */
    private $source;

    /**
     * @var bool
     */
    private $finalize = true;

    /**
     * @var array
     */
    private $log_params = array();

    /**
     * @var array
     */
    private $properties_columns = array();

    /**
     * @var array
     */
    private $properties2_columns = array();

    /**
     * @var array
     */
    private $all_possible_columns = array();

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @var integer
     */
    private $unit1;

    /**
     * @var integer
     */
    private $unit2;

    /**
     * @var integer
     */
    private $unit3;

    /**
     * @var integer
     */
    private $unit4;

    /**
     * @var wpl_db
     */
    private $db;

    /**
     * wpl_property_import constructor.
     * @author Howard R <howard@realtyna.com>
     * @param array $data
     * @param string $unique_field
     * @param integer $user_id
     * @param string $source
     * @param bool $finalize
     * @param array $log_params
     */
	public function __construct($data, $unique_field = 'mls_id', $user_id = NULL, $source = 'mls', $finalize = true, $log_params = array())
	{
	    $this->data = $data;
	    $this->unique_field = $unique_field;
        $this->source = $source;
        $this->finalize = $finalize;
        $this->log_params = $log_params;

        // DB
        $this->db = new wpl_db();

        // Property User
        if(!$user_id) $user_id = wpl_users::get_cur_user_id();
        $this->user_id = $user_id;

        // Possible Columns
        $this->properties_columns = $this->db->columns('wpl_properties');
        $this->properties2_columns = $this->db->columns('wpl_properties2');

        // All Possible Columns
        $this->all_possible_columns = array_merge($this->properties_columns, $this->properties2_columns);

        // Default Values
        $this->unit1 = $this->get_default_unit(1);
        $this->unit2 = $this->get_default_unit(2);
        $this->unit3 = $this->get_default_unit(3);
        $this->unit4 = $this->get_default_unit(4);
    }

    /**
     * Import Data
     * @author Howard R <howard@realtyna.com>
     * @return array
     */
    public function start()
    {
        // Kind
        $kind = 0;

        $pids = array();
        $added = array(); // Used for logging results
        $updated = array(); // Used for logging results

        // Model
        $model = new wpl_property();

        // Loop Through Data
        foreach($this->data as $property_to_import)
        {
            $values = array(); // Properties Table
            $values2 = array(); // Properties2 Table
            $unique_value = '';

            foreach($property_to_import as $key=>$row)
            {
                $wpl_field = !empty($row['wpl_table_column']) ? $row['wpl_table_column'] : $key;
                $wpl_field_lang = isset($row['wpl_table_column_lang']) ? $row['wpl_table_column_lang'] : NULL;
                $wpl_value = isset($row['wpl_value']) ? $row['wpl_value'] : NULL;

                // Validation Table Column
                if(!in_array($wpl_field, $this->all_possible_columns)) continue;

                // Normalize The Value
                if (gettype($wpl_value) == 'string' and !empty($wpl_value)){
                    $wpl_value = !preg_match('!!u', $wpl_value) ? htmlentities(utf8_decode($wpl_value), ENT_NOQUOTES) : htmlentities($wpl_value, ENT_NOQUOTES);
                }

                // Set Unique Value
                if($wpl_field == $this->unique_field) $unique_value = $wpl_value;

                // Set User ID
                if($wpl_field == 'user_id') $this->user_id = $wpl_value;

                // Set Kind Value
                if($wpl_field == 'kind') $kind = $wpl_value;

				// Add to Values Properties
				if (in_array($wpl_field, $this->properties_columns)){
					$values[$wpl_field] = $wpl_value;
					//Check lang field
					if(!empty($wpl_field_lang) and in_array($wpl_field_lang, $this->properties_columns)){
						$values[$wpl_field_lang] = $wpl_value;
					}
				}
				// Add to Values Properties2
				if (in_array($wpl_field, $this->properties2_columns)){
					$values2[$wpl_field] = $wpl_value;
					//Check lang field
					if(!empty($wpl_field_lang) and in_array($wpl_field_lang, $this->properties2_columns)){
						$values2[$wpl_field_lang] = $wpl_value;
					}
				}
            }

            // Add source and last sync date
            if(in_array('source', $this->all_possible_columns) and in_array('last_sync_date', $this->all_possible_columns))
            {
                $last_sync_date = date('Y-m-d H:i:s');

                $values['source'] = $this->source;
                $values['last_sync_date'] = $last_sync_date;
            }

            $exists = wpl_db::select(wpl_db::prepare('SELECT COUNT(*) AS count FROM `#__wpl_properties` WHERE %i = %s', $this->unique_field, $unique_value), 'loadResult');
            if(!$exists) // Add new Property
            {
                $values = $this->add_values($values, $kind, 'wpl_properties');

                $q1 = '';
                $q2 = '';

                foreach($values as $col=>$val)
                {
                    $q1 .= wpl_db::prepare("%i,", $col);

                    if(in_array($col, array('geopoints'))) $q2 .= wpl_db::prepare('%1$s,', $val);
                    elseif (is_null($val) && wpl_db::isNullable('wpl_properties', $col)) $q2 .= "NULL,";
                    else
                    {
                        $q2 .= wpl_db::prepare("%s,", $val);
                    }
                }

                $query = "INSERT INTO `#__wpl_properties` (".trim($q1, ', ').") VALUES (".trim($q2, ', ').")";
                $pid = $this->db->runQuery($query, 'insert');
            }
            else // Update Existing Property
            {
                $pid = $model->pid($unique_value, $this->unique_field);

                $q = '';
                foreach($values as $col=>$val)
                {
                    if (is_null($val) && wpl_db::isNullable('wpl_properties', $col)) $q .= wpl_db::prepare("%i = NULL,", $col);
                    else {
                        $q .= wpl_db::prepare("%i = %s,", $col, $val);
                    }
                }

                if(trim($q))
                {
                    $this->db->runQuery(wpl_db::prepare("UPDATE `#__wpl_properties` SET ".trim($q, ', ')." WHERE `id`= %d", $pid));
                }
            }

            // Properties2 Table
            if($this->db->select("SELECT `id` FROM `#__wpl_properties2` WHERE `id`='".$pid."'", 'loadResult'))
            {
                // Update

                $u2 = '';
                foreach($values2 as $column=>$value) {
                    if (is_null($value) && wpl_db::isNullable('wpl_properties2', $column)) $u2 .= wpl_db::prepare("%i = NULL,", $column);
                    else{
                        $u2 .= wpl_db::prepare("%i = %s,", $column, $value);
                    }
                }

                if(trim($u2))
                {
                    $this->db->runQuery(wpl_db::prepare("UPDATE `#__wpl_properties2` SET ".trim($u2, ', ')." WHERE `id` = %d", $pid), 'update');
                }
            }
            else
            {
                // Insert
                $values2 = $this->add_values($values2, $kind, 'wpl_properties2');

                $u2_columns = "`id`,";
                $u2_values = "'$pid',";
                foreach($values2 as $column=>$value)
                {
                    $u2_columns .= wpl_db::prepare("%i,", $column);
                    if (is_null($value) && wpl_db::isNullable('wpl_properties2', $column)) $u2_values .= "NULL,";
                    else{
                        $u2_values .= wpl_db::prepare("%s,", $value);
                    }
                }

                $query = "INSERT INTO `#__wpl_properties2` (".trim($u2_columns, ', ').") VALUES (".trim($u2_values, ', ').")";
                $this->db->runQuery($query, 'insert');
            }

            // Add property id to response
            $pids[] = $pid;

            // Finalize The Property
            if($this->finalize)
            {
                $mode = $exists ? 'edit' : 'add';
                $model->finalize($pid, $mode, $this->user_id);
            }

            if(!$exists) $added[] = $unique_value;
            else $updated[] = $unique_value;
        }

        // Creating Log
        if($this->source == 'mls' and wpl_global::check_addon('mls'))
        {
            // Import MLS library
            _wpl_import('libraries.addon_mls');

            // Add Logs
            if(method_exists('wpl_addon_mls', 'log')) wpl_addon_mls::log($added, $updated, $this->log_params);
        }

        // WPL Import Event
        wpl_events::trigger('wpl_import', array('properties'=>$this->data, 'wpl_unique_field'=>$this->unique_field, 'user_id'=>$this->user_id, 'source'=>$this->source, 'added'=>$added, 'updated'=>$updated, 'log_params'=>$this->log_params, 'pids'=>$pids));

        // Return Property IDs
        return $pids;
    }

    /**
     * Get Fields of Certain Kind
     * @author Howard R <howard@realtyna.com>
     * @param integer $kind
     * @return array
     */
    private function get_fields($kind)
    {
        // Return from Cached Fields
        if(isset($this->fields[$kind])) return $this->fields[$kind];

        // Fetch
        $fields = wpl_flex::get_fields('', 1, $kind);

        // Set to Cached Fields
        $this->fields[$kind] = $fields;

        // Return Fields
        return $fields;
    }

    /**
     * Add necesarry data for insert
     * @author Howard R <howard@realtyna.com>
     * @param array $values
     * @param int $kind
     * @param string $storage
     * @return array
     */
    private function add_values($values, $kind = 0, $storage = 'wpl_properties')
    {
        if($storage == 'wpl_properties')
        {
            // Kind
            if(!isset($values['kind'])) $values['kind'] = $kind;

            // User ID
            if(!isset($values['user_id'])) $values['user_id'] = $this->user_id;

            // Add Date
            if(!isset($values['add_date'])) $values['add_date'] = date("Y-m-d H:i:s");

            // Finalized
            if(!isset($values['finalized'])) $values['finalized'] = 0;

            // MLS ID
            if(!isset($values['mls_id'])) $values['mls_id'] = wpl_property::get_new_mls_id();

            // Default Country
            if(!isset($values['location1_id']))
            {
                $country = NULL;
                if($this->db->num("SELECT COUNT(*) FROM `#__wpl_location1` WHERE `enabled`='1'") == 1) $country = $this->db->select("SELECT `id`, `name` FROM `#__wpl_location1` WHERE `enabled`='1' LIMIT 1", 'loadAssoc');

                if($country)
                {
                    $values['location1_id'] = $country['id'];
                    $values['location1_name'] = $country['name'];
                }
            }

            // Add default value for geopoints column
            if(wpl_global::check_addon('aps')) $values['geopoints'] = 'Point(0,0)';
        }

        $unit1 = $this->unit1;
        $unit2 = $this->unit2;
        $unit3 = $this->unit3;
        $unit4 = $this->unit4;

        // Default Values Per Agent
        if(wpl_global::check_addon('aps') and count($user_default_values = wpl_users::get_default_values($this->user_id)))
        {
            if(isset($user_default_values['unit1']) and trim($user_default_values['unit1'])) $unit1 = $user_default_values['unit1'];
            if(isset($user_default_values['unit2']) and trim($user_default_values['unit2'])) $unit2 = $user_default_values['unit2'];
            if(isset($user_default_values['unit3']) and trim($user_default_values['unit3'])) $unit3 = $user_default_values['unit3'];
            if(isset($user_default_values['unit4']) and trim($user_default_values['unit4'])) $unit4 = $user_default_values['unit4'];
        }

        // To insert default values for measuring units
        $fields = $this->get_fields($kind);
        foreach($fields as $field)
        {
            if($field->table_name != $storage) continue;

            if(($field->type == 'length' or $field->type == 'mmlength') and !isset($values[$field->table_column.'_unit'])) $values[$field->table_column.'_unit'] = $unit1;
            elseif(($field->type == 'area' or $field->type == 'mmarea') and !isset($values[$field->table_column.'_unit'])) $values[$field->table_column.'_unit'] = $unit2;
            elseif(($field->type == 'volume' or $field->type == 'mmvolume') and !isset($values[$field->table_column.'_unit'])) $values[$field->table_column.'_unit'] = $unit3;
            elseif(($field->type == 'price' or $field->type == 'mmprice') and !isset($values[$field->table_column.'_unit'])) $values[$field->table_column.'_unit'] = $unit4;
        }

        // All Values
        return $values;
    }

    /**
     * Get Default Unit
     * @param int $type
     * @return integer
     */
    private function get_default_unit($type = 4)
    {
        $condition = '';
        $condition .= wpl_db::prepare(" AND `type` = %s", $type);
        $condition .= " AND `enabled`>='1'";

        $query = "SELECT `id` FROM `#__wpl_units` WHERE 1 ".$condition." ORDER BY `index` ASC LIMIT 1";
        return $this->db->select($query, 'loadResult');
    }
}