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
// $Id: vardefs.php 42866 2008-12-31 02:07:55Z dwheeler $
$dictionary['FieldsMetaData'] = array (
	'table' => 'fields_meta_data',
	'fields' => array (
		'id'=>array('name' =>'id', 'type' =>'varchar', 'len'=>'255', 'reportable'=>false),
		'name'=>array('name' =>'name', 'vname'=>'COLUMN_TITLE_NAME', 'type' =>'varchar', 'len'=>'255'),
		'vname'=>array('name' =>'vname' ,'type' =>'varchar','vname'=>'COLUMN_TITLE_LABEL',  'len'=>'255'),
		'comments'=>array('name' =>'comments' ,'type' =>'varchar','vname'=>'COLUMN_TITLE_LABEL',  'len'=>'255'),
        'help'=>array('name' =>'help' ,'type' =>'varchar','vname'=>'COLUMN_TITLE_LABEL',  'len'=>'255'),
		'custom_module'=>array('name' =>'custom_module',  'type' =>'varchar', 'len'=>'255', ),
		'type'=>array('name' =>'type', 'vname'=>'COLUMN_TITLE_DATA_TYPE',  'type' =>'varchar', 'len'=>'255'),
		'len'=>array('name' =>'len','vname'=>'COLUMN_TITLE_MAX_SIZE', 'type' =>'int', 'len'=>'11', 'required'=>false, 'validation' => array('type' => 'range', 'min' => 1, 'max' => 255),),
		'required'=>array('name' =>'required', 'type' =>'bool', 'default'=>'0'),
		'default_value'=>array('name' =>'default_value', 'type' =>'varchar', 'len'=>'255', ),
		'date_modified'=>array('name' =>'date_modified', 'type' =>'datetime', 'len'=>'255',),		
		'deleted'=>array('name' =>'deleted', 'type' =>'bool', 'default'=>'0', 'reportable'=>false),
		'audited'=>array('name' =>'audited', 'type' =>'bool', 'default'=>'0'),		
		'massupdate'=>array('name' =>'massupdate', 'type' =>'bool', 'default'=>'0'),	
        'duplicate_merge'=>array('name' =>'duplicate_merge', 'type' =>'short', 'default'=>'0'),  
        'reportable' => array('name'=>'reportable', 'type'=>'bool', 'default'=>'1'),
        'importable' => array('name'=>'importable', 'type'=>'varchar', 'len'=>'255'),
		'ext1'=>array('name' =>'ext1', 'type' =>'varchar', 'len'=>'255', 'default'=>''),
		'ext2'=>array('name' =>'ext2', 'type' =>'varchar', 'len'=>'255', 'default'=>''),
		'ext3'=>array('name' =>'ext3', 'type' =>'varchar', 'len'=>'255', 'default'=>''),
		'ext4'=>array('name' =>'ext4', 'type' =>'text'),
	),
	'indices' => array (
		array('name' =>'fields_meta_datapk', 'type' =>'primary', 'fields' => array('id')),
		array('name' =>'idx_meta_id_del', 'type' =>'index', 'fields'=>array('id','deleted')),
		array('name' => 'idx_meta_cm_del', 'type' => 'index', 'fields' => array('custom_module', 'deleted')),
	),
);