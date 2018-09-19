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

 // $Id: listviewdefs.php 56123 2010-04-26 21:48:19Z asandberg $

$listViewDefs['ProjectTask'] = array(
    'NAME' => array(
        'width' => '40',  
        'label' => 'LBL_LIST_NAME', 
        'link' => true,
        'default' => true,
        'sortable' => true),       
    'PROJECT_NAME' => array(
        'width' => '25',  
        'label' => 'LBL_PROJECT_NAME', 
        'id'=>'PROJECT_ID',
        'link' => true,
        'default' => true,
        'sortable' => true,
        'module'  => 'Project',
        'ACLTag' => 'PROJECT',
        'related_fields' => array('project_id')),            
    'DATE_START' => array(
        'width' => '10',  
        'label' => 'LBL_DATE_START', 
        'default' => true,
        'sortable' => true),     
    'DATE_FINISH' => array(
        'width' => '10',  
        'label' => 'LBL_DATE_FINISH', 
        'default' => true,
        'sortable' => true),                   
    'PRIORITY' => array(
        'width' => '10',  
        'label' => 'LBL_LIST_PRIORITY', 
        'default' => true,
        'sortable' => true),  
    'PERCENT_COMPLETE' => array(
        'width' => '10',  
        'label' => 'LBL_LIST_PERCENT_COMPLETE', 
        'default' => true,
        'sortable' => true),                      
	'ASSIGNED_USER_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_ASSIGNED_USER_ID',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),
    'TEAM_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_TEAM',
//        'related_fields' => array('team_id'),        
        'default' => false),
);

?>
