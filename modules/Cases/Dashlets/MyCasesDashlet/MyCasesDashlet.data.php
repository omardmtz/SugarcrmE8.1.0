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

// $Id: MyCasesDashlet.data.php 56357 2010-05-10 22:48:37Z jenny $

global $current_user;

$dashletData['MyCasesDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
													   'priority'         => array('default' => ''),
                                                       'status'           => array('default' => array('Assigned', 'New', 'Pending Input')),
                                                       
													   'name'             => array('default' => ''),
												       'type'             => array('default' => ''),
                                                       //'date_modified'    => array('default' => ''),
                                                       'team_id'          => array('default' => '', 'label' => 'LBL_TEAMS'),
                                                       'assigned_user_id' => array('type'    => 'assigned_user_name',
																				   'label'   => 'LBL_ASSIGNED_TO',
                                                                                   'default' => $current_user->name));
$dashletData['MyCasesDashlet']['columns'] = array('case_number' => array('width'   => '6',
                                                                         'label'   => 'LBL_NUMBER',
                                                                         'default' => true),
                                                  'name' => array('width'    => '40', 
                                                                  'label'   => 'LBL_LIST_SUBJECT',
                                                                  'link'    => true,
                                                                  'default' => true), 
                                                  'account_name' => array('width' => '29',
                                                                          'link' => true,
                                                                          'module' => 'Accounts',
                                                                          'id' => 'ACCOUNT_ID',
                                                                          'ACLTag' => 'ACCOUNT', 
                                                                          'label' => 'LBL_ACCOUNT_NAME',
                                                                          'related_fields' => array('account_id')),
                                                  'priority' => array('width'   => '15', 
                                                                      'label'   => 'LBL_PRIORITY',
                                                                      'default' => true), 
                                                  'status' => array('width'   => '8', 
                                                                    'label'   => 'LBL_STATUS',
                                                                    'default' => true),                                            
                                                  'resolution' => array('width' => '8', 
                                                                        'label' => 'LBL_RESOLUTION'),
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
