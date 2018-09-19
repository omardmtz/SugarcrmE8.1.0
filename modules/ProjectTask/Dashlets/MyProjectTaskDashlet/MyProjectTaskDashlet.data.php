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

 // $Id: MyProjectTaskDashlet.data.php 56115 2010-04-26 17:08:09Z kjing $

global $current_user;

$dashletData['MyProjectTaskDashlet']['searchFields'] =  array(
                                                            'date_entered'     => array('default' => ''), 
															'priority'	     => array('default' => ''),                              
                                                            'date_start'        => array('default' => ''),
                                                            'date_finish'         => array('default' => ''),
                                                            'team_id'          => array('default' => '', 'label'=>'LBL_TEAMS'),
															'assigned_user_id' => array('type'    => 'assigned_user_name',
																						'label'   => 'LBL_ASSIGNED_TO', 
                                                                                        'default' => $current_user->name),

                                                            );
$dashletData['MyProjectTaskDashlet']['columns'] = array('name' => array('width'   => '45', 
                                                                       'label'   => 'LBL_NAME',
                                                                       'link'    => true,
                                                                       'default' => true), 
                                                       'priority' => array('width'   => '15',
                                                                           'label'   => 'LBL_PRIORITY',
																		   'default' => true,
                                                                          ),
                                                       'date_start' => array('width' => '15',
                                                                             'label' => 'LBL_DATE_START',
                                                                             'default' => true),
                                                       'time_start' => array('width' => '15',
                                                                             'label' => 'LBL_TIME_START'),
                                                       'date_finish' => array('width'   => '15',
                                                                           'label'   => 'LBL_DATE_FINISH',
                                                                           'default' => true),
                                                       'time_finish' => array('width' => '15',
                                                                           'label' => 'LBL_TIME_FINISH'),                   
                                                       'percent_complete' => array('width' => '10',
                                                                             'label' => 'LBL_PERCENT_COMPLETE',
                                                                             'default' => true),
                                                       'project_name' => array('width' => '30',
                                                                              'label' => 'LBL_PROJECT_NAME',
                                                                              'related_fields' => array('project_id')),
                                                       'milestone_flag' => array('width' => '10',
                                                                                 'label' => 'LBL_MILESTONE_FLAG'),
                                                       'date_entered' => array('width' => '15', 
                                                                               'label' => 'LBL_DATE_ENTERED'),
                                                       'date_modified' => array('width' => '15', 
                                                                                'label' => 'LBL_DATE_MODIFIED'),    
                                                       'created_by' => array('width' => '8', 
                                                                             'label' => 'LBL_CREATED'),
                                                       'assigned_user_name' => array('width'   => '8', 
                                                                                     'label'   => 'LBL_LIST_ASSIGNED_USER'),
                                                       'team_name' => array('width'   => '15', 
                                                                            'label'   => 'LBL_LIST_TEAM',
                                                                            'sortable' => false,),
                                                                           );

?>
