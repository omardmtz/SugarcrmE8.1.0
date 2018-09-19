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

// $Id: MyCallsDashlet.data.php 56115 2010-04-26 17:08:09Z kjing $

global $current_user;

$dashletData['MyCallsDashlet']['searchFields'] = array(
													   
                                                       'name'        => array('default' => ''),
                                                       'status'           => array('default' => array('Planned')),
                                                       'date_entered'       => array('default' => ''),
                                                       'date_start'             => array('default' => ''),
                                                       
                                                       'team_id'          => array('default' => '', 'label'=>'LBL_TEAMS'),
                                                       'assigned_user_id' => array('type'    => 'assigned_user_name',
                   																   'label'   => 'LBL_ASSIGNED_TO', 
                                                                                   'default' => $current_user->name));
$dashletData['MyCallsDashlet']['columns'] = array('set_complete' => array('width' => '5', 
                                                                          'label' => 'LBL_LIST_CLOSE',
                                                                          'default' => true,
                                                                          'sortable' => false,
                                                                          'related_fields' => array('status','recurring_source')),
                                                  'name' => array('width'   => '40', 
                                                                  'label'   => 'LBL_SUBJECT',
                                                                  'link'    => true,
                                                                  'default' => true),
                                                  'parent_name' => array('width' => '29', 
                                                                         'label' => 'LBL_LIST_RELATED_TO',
                                                                         'sortable' => false,
                                                                         'dynamic_module' => 'PARENT_TYPE',
                                                                         'link' => true,
                                                                         'id' => 'PARENT_ID',
                                                                         'ACLTag' => 'PARENT',
                                                                         'related_fields' => array('parent_id', 'parent_type'),
																		 'default' => true,
																		),
                                                  
                                                  'duration' => array('width'    => '10', 
                                                                      'label'    => 'LBL_DURATION',
                                                                      'sortable' => false,
                                                                      'related_fields' => array('duration_hours', 'duration_minutes')),
                                                  'direction' => array('width'   => '10', 
                                                                       'label'   => 'LBL_DIRECTION'),  
                                                  'date_start' => array('width'   => '15', 
                                                                        'label'   => 'LBL_DATE',
                                                                        'default' => true,
                                                                        'related_fields' => array('time_start')),
											'set_accept_links'=> array('width'    => '10', 
																	   'label'    => translate('LBL_ACCEPT_THIS', 'Meetings'),
																	   'sortable' => false,
																	    'related_fields' => array('status'),
																		'default' => true),
                                                  'status' => array('width'   => '8', 
                                                                    'label'   => 'LBL_STATUS',
																	'default'  => true),
                                                  'date_entered' => array('width'   => '15', 
                                                                          'label'   => 'LBL_DATE_ENTERED'),
                                                  'date_modified' => array('width'   => '15', 
                                                                          'label'   => 'LBL_DATE_MODIFIED'),    
                                                  'created_by' => array('width'   => '8', 
                                                                        'label'   => 'LBL_CREATED'),
                                                  'assigned_user_name' => array('width'   => '8', 
                                                                                'label'   => 'LBL_LIST_ASSIGNED_USER'),
                                                  'team_name' => array('width'   => '15', 
                                                                       'label'   => 'LBL_LIST_TEAM'),
                                                  );
?>
