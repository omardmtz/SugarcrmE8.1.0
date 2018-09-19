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

$dictionary['queues_beans'] = array('table' => 'queues_beans', 
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
		),
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
			'reportable'=>false,
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required' => true,
		),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
		),
		'queue_id' => array (
			'name' => 'queue_id',
			'vname' => 'LBL_QUEUE_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
		),
		'module_dir' => array (
			'name' => 'module_dir',
			'vname' => 'LBL_MODULE_DIR',
			'type' => 'varchar',
			'len'	=> '30',
			'required' => true,
			'reportable'=>false,
		),
		'object_id' => array (
			'name' => 'object_id',
			'vname' => 'LBL_OBJECT_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
		),
	),
	'relationships' => array (
		'queues_emails_rel' => array(
			'lhs_module'					=> 'Queues',
			'lhs_table'						=> 'queues',
			'lhs_key' 						=> 'id',
			'rhs_module'					=> 'Emails',
			'rhs_table'						=> 'emails',
			'rhs_key' 						=> 'id',
			'relationship_type' 			=> 'many-to-many',
			'join_table'					=> 'queues_beans', 
			'join_key_rhs'					=> 'object_id', 
			'join_key_lhs'					=> 'queue_id',
			'relationship_role_column'		=> 'module_dir',
			'relationship_role_column_value'=> 'Emails'		
		),
	), /* end relationship definitions */
	'indices' => array (
		array(
			'name' => 'queues_itemspk',
			'type' =>'primary',
			'fields' => array(
				'id'
			)
		),
		array(
		'name' =>'idx_queue_id',
		'type'=>'index',
		'fields' => array(
			'queue_id'
			)
		),
		array(
		'name' =>'idx_object_id',
		'type'=>'index',
		'fields' => array(
			'object_id'
			)
		),
	), /* end indices */
);

?>
