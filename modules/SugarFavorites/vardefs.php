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
$dictionary['SugarFavorites'] = array(
	'table'=>'sugarfavorites',
	'audited'=>false,
	'fields'=>array (
  'module' => 
  array (
    'required' => false,
    'name' => 'module',
    'vname' => 'LBL_MODULE',
    'type' => 'varchar',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
    'len' => '50',
  ),
  'record_id' => 
  array (
    'required' => false,
    'name' => 'record_id',
    'vname' => 'LBL_RECORD_ID',
    'type' => 'parent_type',
    'dbType'=>'id',
  	'group'=>'record_name',
    'options'=> 'parent_type_display',
    'massupdate' => 0,
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => 0,
    'reportable' => 0,
  ),
  'record_name'=>
  array(
		'name'=> 'record_name',
		'parent_type'=>'record_type_display' ,
		'type_name'=>'module',
		'id_name'=>'record_id',
        'vname'=>'LBL_LIST_RELATED_TO',
		'type'=>'parent',
		'group'=>'record_name',
		'source'=>'non-db',
		'options'=> 'parent_type_display',
  ),
    'description' => 
  array (
    'name' => 'description',
    'type' => 'name',
    'dbType' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 50,
    'comment' => 'Name of the feed',
    'unified_search' => false,
    'audited' => false,
   
  ),
),
	'relationships'=>array (
),
    'indices' => array(
        array(
            'name' => 'idx_favs_date_entered', 
            'type'=>'index',
            'fields'=>array('date_entered','deleted'),
          ),
        array(
            'name' => 'idx_favs_user_module', 
            'type'=>'index',
            'fields'=>array('modified_user_id','module','deleted'),
          ),
        array(
            'name' => 'idx_favs_module_record_deleted', 
            'type'=>'index',
            'fields'=>array('module','record_id','deleted'),
          ),
        array(
        	'name' => 'idx_favs_id_record_id',
        	'type' => 'index',
        	'fields' => array('record_id', 'id')
          ),
        ),
	'optimistic_lock'=>true,
);
VardefManager::createVardef('SugarFavorites', 'SugarFavorites', array('basic','assignable'));
