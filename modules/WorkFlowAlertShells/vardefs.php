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
$dictionary['WorkFlowAlertShell'] = array('table' => 'workflow_alertshells'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
  ),
   'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => false,
    'default' => '0',
    'reportable'=>false,
  ),
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => true,
  ),
    'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '50',
  ),
  'alert_text' => 
  array (
    'name' => 'alert_text',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
  'alert_type' => 
  array (
    'name' => 'alert_type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'required' => true,
    'options' => 'wflow_alert_type_dom',
    'len' => 100,
  ),
   'source_type' => 
  array (
    'name' => 'source_type',
    'vname' => 'LBL_SOURCE_TYPE',
    'type' => 'enum',
    'required' => true,
    'options' => 'wflow_source_type_dom',
    'len' => 100,
  ),
   'parent_id' => 
  array (
    'name' => 'parent_id',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
  ),
   'custom_template_id' => 
  array (
    'name' => 'custom_template_id',
    'type' => 'id',
    'required' => false,
    'reportable'=>false,
  ),
    'alert_components' => 
  array (
  	'name' => 'alert_components',
    'type' => 'link',
    'relationship' => 'alert_components',
    'module'=>'WorkFlowAlerts',
    'bean_name'=>'WorkFlowAlert',
    'source'=>'non-db',
  ),
'parent_base_module' => 
  array (
  	'name' => 'parent_base_module',
    'vname' => 'LBL_BASE_MODULE',
    'type' => 'varchar',
    'source'=>'non-db',
  ),
  'parent_type' => 
  array (
    'name' => 'parent_type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options' => 'wflow_type_dom',
    'source'=>'non-db',
  ),
),
'acls' => array('SugarACLDeveloperOrAdmin' => true),
'indices' => array (
       array('name' =>'workflowalertshell_k', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_workflowalertshell', 'type'=>'index', 'fields'=>array('name','deleted')),
                                                      )

, 'relationships' => array (
		'alert_components' => array('lhs_module'=> 'WorkFlowAlertShells', 'lhs_table'=> 'workflow_alertshells', 'lhs_key' => 'id',
							  'rhs_module'=> 'WorkFlowAlerts', 'rhs_table'=> 'workflow_alerts', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many')	
  )
  
                                                      
                            );
