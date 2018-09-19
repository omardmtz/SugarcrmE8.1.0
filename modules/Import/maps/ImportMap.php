<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/*********************************************************************************

 * Description: Bean for import_map table
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/



class ImportMap extends SugarBean
{
    /**
     * Fields in the table
     */
    public $id;
    public $name;
    public $module;
    public $source;
    public $delimiter;
    public $enclosure;
    public $content;
    public $default_values;
    public $has_header;
    public $deleted;
    public $date_entered;
    public $date_modified;
    public $assigned_user_id;
    public $is_published;

    /**
     * Set the default settings from Sugarbean
     */
    public $table_name  = "import_maps";
    public $object_name = "ImportMap";
    public $module_dir  = 'Import';
    public $new_schema  = true;
    var $disable_custom_fields = true;
    public $column_fields = array(
        "id",
        "name",
        "module",
        "source",
        "enclosure",
        "delimiter",
        "content",
        "has_header",
        "deleted",
        "date_entered",
        "date_modified",
        "assigned_user_id",
        "is_published",
        );

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->disable_row_level_security =true;
    }

    /**
     * Returns an array with the field mappings.
     *
     * @return array Converted $content.
     */
    public function getMapping()
    {
        return !empty($this->content) ? $this->convertFromCsv($this->content) : array();
    }

    /**
     * Sets $content with the mapping given.
     *
     * @param array $mapping
     */
    public function setMapping(array $mapping)
    {
        $this->content = $this->convertToCsv($mapping);
    }

    /**
     * Returns an array with the default field values.
     *
     * @return array Converted $default_values.
     */
    public function getDefaultValues()
    {
        return !empty($this->default_values) ? $this->convertFromCsv($this->default_values) : array();
    }

    /**
     * Sets $default_values with the default values given.
     *
     * @param array $defaultValues
     */
    public function setDefaultValues(array $defaultValues)
    {
        $this->default_values = $this->convertToCsv($defaultValues);
    }

    /**
     * @see SugarBean::retrieve()
     */
    public function retrieve($id = -1, $encode=true,$deleted=true)
	{
	    $returnVal = parent::retrieve($id,$encode,$deleted);

	    if ( !($returnVal instanceOf $this) ) {
	        return $returnVal;
	    }

	    if ( $this->source == 'tab' && $this->delimiter == '' ) {
	        $this->delimiter = "\t";
	    }

	    return $this;
	}

    /**
     * {@inheritDoc}
     * If one argument passed - calls standard SugarBean::save
     *
     * @param  string $owner_id
     * @param  string $name
     * @param  string $module
     * @param  string $source
     * @param  string $has_header
     * @param  string $delimiter
     * @param  string $enclosure
     * @return bool
     */
    public function save($check_notify = false)
    {
        if (func_num_args() <= 1) {
            // calling SugarBean::save()
            return call_user_func_array(array('parent', __FUNCTION__), func_get_args());
        }
        // keep backwards compatibility, old method expected these arguments
        list($owner_id, $name, $module, $source, $has_header, $delimiter, $enclosure) = func_get_args();
        $olddefault_values = $this->default_values;
        $oldcontent = $this->content;

        $this->retrieve_by_string_fields(
            array(
                'assigned_user_id'=>$owner_id,
                'name'=>$name),
            false
            );

        // Bug 23354 - Make sure enclosure gets saved as an empty string if
        // it is an empty string, instead of as a null
        if ( strlen($enclosure) <= 0 ) $enclosure = ' ';

        $this->assigned_user_id = $owner_id;
        $this->name             = $name;
        $this->source           = $source;
        $this->module           = $module;
        $this->delimiter        = $delimiter;
        $this->enclosure        = $enclosure;
        $this->has_header       = $has_header;
        $this->deleted          = 0;
        $this->default_values   = $olddefault_values;
        $this->content          = $oldcontent;
        parent::save();

        // Bug 29365 - The enclosure character isn't saved correctly if it's a tab using SQL Server adapter,
        // so resave it
        if ($enclosure == '\\t' && $this->db->dbType == 'mssql') {
            $this->enclosure = $enclosure;
            parent::save();
        }

        return 1;
    }

    /**
     * Checks to see if the user owns this mapping or is an admin first
     * If true, then call parent function
     *
     * @param $id
     */
    public function mark_deleted(
        $id
        )
    {
        global $current_user;

        if ( !is_admin($current_user) ) {
            $other_map = BeanFactory::newBean('Import_1');
            $other_map->retrieve_by_string_fields(array('id'=> $id), false);

            if ( $other_map->assigned_user_id != $current_user->id )
                return false;
        }

        return parent::mark_deleted($id);
    }

    /**
     * Mark an import map as published
     *
     * @param  string $user_id
     * @param  bool   $flag     true if we are publishing or false if we are unpublishing
     * @return bool
     */
    public function mark_published(
        $user_id,
        $flag
        )
    {
        global $current_user;

        if ( !is_admin($current_user) )
            return false;

        // check for problems
        if ($flag) {
            // if you are trying to publish your map
            // but there's another published map
            // by the same name
            $query_arr = array(
                'name'         =>$this->name,
                'is_published' =>'yes'
                );
        }
        else {
            // if you are trying to unpublish a map
            // but you own an unpublished map by the same name
            $query_arr = array(
                'name'             => $this->name,
                'assigned_user_id' => $user_id,
                'is_published'     => 'no'
                );
        }
        $other_map = BeanFactory::newBean('Import_1');
        $other_map->retrieve_by_string_fields($query_arr, false);

        // if we find this other map, quit
        if ( isset($other_map->id) )
            return false;

        // otherwise update the is_published flag
        $query = "UPDATE $this->table_name
                    SET is_published = '". ($flag?'yes':'no') . "',
                        assigned_user_id = '$user_id'
                    WHERE id = '{$this->id}'";

        $this->db->query($query,true,"Error marking import map published: ");

        return true;
    }

    /**
     * Similar to retrieve_by_string_fields, but returns multiple objects instead of just one.
     *
     * @param  array $fields_array
     * @return array $obj_arr
     */
    public function retrieve_all_by_string_fields(
        $fields_array
        )
    {
        $query = "SELECT *
                    FROM {$this->table_name}
                    " . $this->get_where($fields_array);

        $result = $this->db->query($query,true," Error: ");
        $obj_arr = array();

        while ($row = $this->db->fetchByAssoc($result,true) ) {
            $focus = BeanFactory::newBean('Import_1');

            foreach($this->column_fields as $field) {
                if(isset($row[$field])) {
                    $focus->$field = $row[$field];
                }
            }
            $focus->fill_in_additional_detail_fields();
            $obj_arr[]=$focus;
        }

        return $obj_arr;
    }

    /**
     * set and get field elements in request field to and from user preferences
     *
     * @param  array $fields_array
     * @return array $obj_arr
     */
    public function set_get_import_wizard_fields($ForceValsArr = '')
    {
        global $current_user;
        $set = false;
        //list of field values we track during import wizard
        $import_step_fields = array(
        //step1
          //  'import_module', 'source', 'custom_enclosure', 'custom_enclosure_other', 'custom_delimiter', 'type',
        //step2
           // 'custom_delimiter', 'custom_enclosure', 'type', 'source', 'source_id', 'import_module', 'has_header',
         //step3
            'display_tabs_def','custom_delimiter', 'custom_enclosure', 'import_type', 'source', 'source_id', 'import_module', 'has_header', 'importlocale_charset',
            'importlocale_dateformat', 'importlocale_timeformat', 'importlocale_timezone', 'importlocale_currency',
            'importlocale_default_currency_significant_digits', 'importlocale_num_grp_sep', 'importlocale_dec_sep',
        '   importlocale_default_locale_name_format');

        //retrieve user preferences and populate preference array
        $preference_values_str = $current_user->getPreference('field_values', 'import');
        $preference_values = json_decode($preference_values_str,true);

        foreach ($import_step_fields as $val){
            //overwrite preference array with new values from request if the value is different or new
            if((isset($_REQUEST[$val]) && !isset($preference_values[$val])) || (isset($_REQUEST[$val]) && $preference_values[$val] != $_REQUEST[$val])){
                $preference_values[$val] = $_REQUEST[$val];
                $set = true;
            }
        }

        //force the values to passed in array if array is set
        if(!empty($ForceValsArr) && is_array($ForceValsArr)){
            foreach ($ForceValsArr as $forceKey=>$forceVal){
                $preference_values[$forceKey] = $forceVal;
                $set = true;
            }
        }

        //set preferences if any changes were made and return the new array
        if($set){
            $preference_values_str =  json_encode($preference_values);
            $current_user->setPreference('field_values', $preference_values_str, 0, 'import');
        }
        if(empty($preference_values)){
            return array();
        }

        return $preference_values;
    }

    /**
     * Converts the given data to CSV format.
     *
     * @param array $mapping Array with data.
     * @return string CSV formatted string.
     */
    protected function convertToCsv(array $mapping)
    {
        $output = array ();
        foreach ($mapping as $key => $item) {
            $output[] = "$key=$item";
        }

        $stream = fopen('data://text/plain,', 'w+');
        fputcsv($stream, $output, $this->delimiter, $this->enclosure);
        rewind($stream);
        $source = trim(stream_get_contents($stream));
        fclose($stream);
        return $source;
    }

    /**
     * Converts the given CSV string to array.
     *
     * @param string $csvString CSV formatted string.
     * @return array Converted set.
     */
    protected function convertFromCsv($csvString)
    {
        $mapping = array();
        $pairs = str_getcsv(trim($csvString), $this->delimiter, $this->enclosure);
        foreach ($pairs as $pair) {
            list($name, $value) = explode('=', $pair);
            $mapping[trim($name)] = $value;
        }
        return $mapping;
    }
}


?>
