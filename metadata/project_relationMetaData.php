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

// $Id: project_relationMetaData.php 51971 2009-10-28 15:03:44Z clee $

$dictionary['project_relation'] = array(
	'table' => 'project_relation',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
		),
		'project_id' => array(
			'name' => 'project_id',
			'vname' => 'LBL_PROJECT_ID',
			'required' => true,
			'type' => 'id',
		),
		'relation_id' => array(
			'name' => 'relation_id',
			'vname' => 'LBL_PROJECT_NAME',
			'required' => true,
			'type' => 'id',
		),
		'relation_type' => array(
			'name' => 'relation_type',
			'vname' => 'LBL_PROJECT_NAME',
			'required' => true,
			'type' => 'enum',
			'options' => 'project_relation_type_options',
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
		),
	    'date_modified' => array (
    		'name' => 'date_modified',
    		'vname' => 'LBL_DATE_MODIFIED',
    		'type' => 'datetime',
    		'required'=>true,
  		),
	),
	'indices' => array(
		array(
			'name' =>'proj_rel_pk',
			'type' =>'primary',
			'fields'=>array('id')
		),
	),

 	'relationships' => 
 		array ('projects_accounts' => array('lhs_module'=> 'Accounts', 'lhs_table'=> 'accounts', 'lhs_key' => 'id',
		'rhs_module'=> 'Project', 'rhs_table'=> 'project', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'relation_id', 'join_key_rhs'=>'project_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Accounts'),
						  
		'projects_contacts' => array('lhs_module'=> 'Project', 'lhs_table'=> 'project', 'lhs_key' => 'id',
		'rhs_module'=> 'Contacts', 'rhs_table'=> 'contacts', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'project_id', 'join_key_rhs'=>'relation_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Contacts'),							  

		'projects_opportunities' => array('lhs_module'=> 'Project', 'lhs_table'=> 'project', 'lhs_key' => 'id',
		'rhs_module'=> 'Opportunities', 'rhs_table'=> 'opportunities', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'project_id', 'join_key_rhs'=>'relation_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Opportunities'),							  

		'projects_quotes' => array('lhs_module'=> 'Project', 'lhs_table'=> 'project', 'lhs_key' => 'id',
		'rhs_module'=> 'Quotes', 'rhs_table'=> 'quotes', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'project_id', 'join_key_rhs'=>'relation_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Quotes'),							  
		
		),
);

?>
