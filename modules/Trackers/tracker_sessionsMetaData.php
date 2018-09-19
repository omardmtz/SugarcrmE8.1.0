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

$dictionary['tracker_sessions'] = array(
    'table' => 'tracker_sessions',
    'fields' => array(
        'id'=>array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'int',
            'len' => '11',
            'reportable' => true,            
            'isnull' => 'false',
            'auto_increment' => true,
        ),       
        'session_id'=>array(
            'name' => 'session_id',
            'vname' => 'LBL_SESSION_ID',
            'type' => 'id',
            'isnull' => 'false',
        ),   
        'date_start'=>array(
            'name' => 'date_start',
            'vname' => 'LBL_DATE_START',
            'type' => 'datetime',
            'isnull' => 'false',
        ),
        'date_end'=>array(
            'name' => 'date_end',
            'vname' => 'LBL_DATE_LAST_ACTION',
            'type' => 'datetime',
            'isnull' => 'false',
        ),
        'seconds'=>array (
            'name' => 'seconds',
            'vname' => 'LBL_SECONDS',
            'type' => 'int',
            'len' => '9',
            'isnull' => 'false',
            'default' => '0',
        ) ,        
        'client_ip'=>array(
            'name' => 'client_ip',
            'vname' => 'LBL_CLIENT_IP',
            'type' => 'varchar',
            'len' => '45',
            'isnull' => 'false',
        ),
        'user_id'=>array(
            'name' => 'user_id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
            'len' => '36',
            'isnull' => 'false',
        ),             
        'active'=>array (
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'default' => '1',
        ),
	    'deleted' =>array (
		    'name' => 'deleted',
		    'vname' => 'LBL_DELETED',
		    'type' => 'bool',
		    'default' => '0',
		    'reportable'=>false,
		    'comment' => 'Record deletion indicator'
		),
		'assigned_user_link'=>array (
		    'name' => 'assigned_user_link',
		    'type' => 'link',
		    'relationship' => 'tracker_user_id',
		    'vname' => 'LBL_ASSIGNED_TO_USER',
		    'link_type' => 'one',
		    'module'=>'Users',
		    'bean_name'=>'User',
		    'source'=>'non-db',
		),		        
    ),
    //indices
    'indices' => array(
        array(
            'name' => 'tracker_sessions_pk',
            'type' => 'primary',
            'fields' => array(
                'id'
            )
        ),
        array(
            'name' => 'idx_tracker_sessions_s_id',
            'type' => 'index',
            'fields' => array(
                'session_id',
            ),
        ),
        array(
            'name' => 'idx_tracker_sessions_uas_id',
            'type' => 'index',
            'fields' => array(
                'user_id', 'active', 'session_id'
            ), 
        )    
    ),    
    //relationships
 	'relationships' => array (
	  'tracker_user_id' =>
		   array(
				'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
		   		'rhs_module'=> 'TrackerSessions', 'rhs_table'=> 'tracker', 'rhs_key' => 'user_id',
		   		'relationship_type'=>'one-to-many'
		   )
    ),
    'acls' => array('SugarACLStatic' => true),
);
