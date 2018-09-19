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

 // $Id: MyTasksDashlet.data.php 56965 2010-06-15 17:57:35Z jenny $

global $current_user;

$dashletData['MyTasksDashlet']['searchFields'] = array('name'           => array('default' => ''),
													   'priority'       => array('default' => ''),
                                                       'status'         => array('default' => array('Not Started', 'In Progress', 'Pending Input')),
                                                       'date_entered'   => array('default' => ''),
                                                       'date_start'       => array('default' => ''),                                                          
                                                       'date_due'       => array('default' => ''),
                                                       'team_id'          => array('default' => '', 'label' => 'LBL_TEAMS'),
                                                       'assigned_user_id' => array('type'    => 'assigned_user_name',
																				   'label'   => 'LBL_ASSIGNED_TO', 
                                                                                   'default' => $current_user->name));
$dashletData['MyTasksDashlet']['columns'] = array('set_complete' => array('width'    => '1', 
                                                                          'label'    => 'LBL_LIST_CLOSE',
                                                                          'default'  => true,
                                                                          'sortable' => false),
                                                   'name' => array('width'   => '40', 
                                                                   'label'   => 'LBL_SUBJECT',
                                                                   'link'    => true,
                                                                   'default' => true),
                                                  'parent_name' => array('width' => '30', 
                                                                         'label' => 'LBL_LIST_RELATED_TO',
                                                                         'sortable' => false,
                                                                         'dynamic_module' => 'PARENT_TYPE',
                                                                         'link' => true,
                                                                         'id' => 'PARENT_ID',
                                                                         'ACLTag' => 'PARENT',
                                                                         'related_fields' => array('parent_id', 'parent_type'),
																		 'default' => true,
																		),
                                                   'priority' => array('width'   => '10',
                                                                       'label'   => 'LBL_PRIORITY',
                                                                       'default' => true),
													'status' => array('width'   => '8', 
                                                                     'label'   => 'LBL_STATUS',
																	 'default' => true),                                                               
                                                   'date_start' => array('width'   => '15', 
                                                                         'label'   => 'LBL_START_DATE',
                                                                         'default' => true),                                                                                                       
                                                   'time_start' => array('width'   => '15', 
                                                                         'label'   => 'LBL_START_TIME',
                                                                         'default' => false),
                                                   'date_due' => array('width'   => '15', 
                                                                       'label'   => 'LBL_DUE_DATE',
                                                                       'default' => true),                               
                                                                     
                                                   'date_entered' => array('width'   => '15', 
                                                                           'label'   => 'LBL_DATE_ENTERED'),
                                                   'date_modified' => array('width'   => '15', 
                                                                           'label'   => 'LBL_DATE_MODIFIED'),    
                                                   'created_by' => array('width'   => '8', 
                                                                         'label'   => $GLOBALS['app_strings']['LBL_CREATED'],
                                                                         'sortable' => false),
                                                   'assigned_user_name' => array('width'   => '8', 
                                                                                 'label'   => 'LBL_LIST_ASSIGNED_USER'),
                                                   'contact_name' => array('width'   => '8', 
                                                                           'label'   => 'LBL_LIST_CONTACT',
																		    'link' =>  true,
																		    'id' => 'CONTACT_ID',//bug # 38712 it gave error on clicking on contacts from
        																    'module' => 'Contacts',//my open tasks dashlet because some of the parameters were not set
        																    'ACLTag' => 'CONTACT',// like id, link etc.
        																    'related_fields' => array('contact_id')),
                                                   'team_name' => array('width'   => '15', 
                                                                        'label'   => 'LBL_LIST_TEAM', 
                                                                        'sortable' => false),
                                                                         );


?>
