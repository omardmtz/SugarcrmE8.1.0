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

// $Id: FieldsMetaData.php 45763 2009-04-01 19:16:18Z majed $










class FieldsMetaData extends SugarBean {
	// database table columns
	var $id;
	var $name;
	var $vname;
  	var $custom_module;
  	var $type;
  	var $len;
  	var $required;
  	var $default_value;
  	var $deleted;
  	var $ext1;
  	var $ext2;
  	var $ext3;
	var $audited;
    var $duplicate_merge;
    var $reportable;
	var $required_fields =  array("name"=>1, "date_start"=>2, "time_start"=>3,);

	var $table_name = 'fields_meta_data';
	var $object_name = 'FieldsMetaData';
	var $module_dir = 'DynamicFields';
	var $column_fields = array(
		'id',
		'name',
		'vname',
		'custom_module',
		'type',
		'len',
		'required',
		'default_value',
		'deleted',
		'ext1',
		'ext2',
		'ext3',
		'audited',
		'massupdate',
        'duplicate_merge',
        'reportable',
	);

	var $list_fields = array(
		'id',
		'name',
		'vname',
		'type',
		'len',
		'required',
		'default_value',
		'audited',
		'massupdate',
        'duplicate_merge',
        'reportable',
	);

	var $new_schema = true;
	public $disable_row_level_security = true;

	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////


	public function __construct()
	{
		parent::__construct();
		$this->disable_row_level_security = true;
	}
	
	function mark_deleted($id)
	{
        $query = "DELETE FROM {$this->table_name} WHERE id = ? ";
        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($id));
		$this->mark_relationships_deleted($id);

	}

	function get_list_view_data(){
	    $data = parent::get_list_view_data();
	    $data['VNAME'] = translate($this->vname, $this->custom_module);
	    $data['NAMELINK'] = '<input class="checkbox" type="checkbox" name="remove[]" value="' . $this->id . '">&nbsp;&nbsp;<a href="index.php?module=Studio&action=wizard&wizard=EditCustomFieldsWizard&option=EditCustomField&record=' . $this->id . '" >';
	    return $data;
	}


	function get_summary_text()
	{
		return $this->name;
	}
}
?>
