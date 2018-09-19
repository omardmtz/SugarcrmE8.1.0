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

 // $Id: MyContactsDashlet.data.php 56115 2010-04-26 17:08:09Z kjing $

global $current_user;
$dashletData['MyContactsDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
														  'title'			 => array('default' => ''),
														  'primary_address_country'  => array('default' => ''),
                                                          'team_id'          => array('default' => '', 'label'=>'LBL_TEAMS'),
                                                          'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                      'default' => $current_user->name,
																					  'label' => 'LBL_ASSIGNED_TO')); 
$dashletData['MyContactsDashlet']['columns'] = array('name' => array('width'   => '30', 
                                                                     'label'   => 'LBL_NAME',
                                                                     'link'    => true,
                                                                     'default' => true,
                                                                     'related_fields' => array('first_name', 'last_name', 'salutation')),
                                                     'account_name' => array('width' => '20',
                                                                             'label' => 'LBL_ACCOUNT_NAME',
                                                                             'sortable' => false,
                                                                             'link' => true,
                                                                             'module' => 'Accounts',
                                                                             'id' => 'ACCOUNT_ID',
                                                                             'ACLTag' => 'ACCOUNT'),
                                                     'title' => array('width' => '20s',
                                                                      'label' => 'LBL_TITLE',
																	  'default' => true),
                                                     'email' => array('width' => '10',
                                                                    'label' => 'LBL_EMAIL_ADDRESS',
                                                                    'sortable' => false,
                                                                    'customCode' => '{$EMAIL_LINK}{$EMAIL}</a>',),
                                                     'phone_work' => array('width'   => '15',
                                                                           'label'   => 'LBL_OFFICE_PHONE',
                                                                           'default' => true),
                                                     'phone_home' => array('width' => '10',
                                                                           'label' => 'LBL_HOME_PHONE'),
                                                     'phone_mobile' => array('width' => '10',
                                                                             'label' => 'LBL_MOBILE_PHONE'),
                                                     'phone_other' => array('width' => '10',
                                                                            'label' => 'LBL_OTHER_PHONE'),
                                                     'date_entered' => array('width'   => '15', 
                                                                             'label'   => 'LBL_DATE_ENTERED',
                                                                             'default' => true),
                                                     'date_modified' => array('width'   => '15', 
                                                                              'label'   => 'LBL_DATE_MODIFIED'),    
                                                     'created_by' => array('width'   => '8', 
                                                                           'label'   => 'LBL_CREATED'),
                                                     'assigned_user_name' => array('width'   => '15', 
                                                                                   'label'   => 'LBL_LIST_ASSIGNED_USER',
                                                                                   'default' => true),
                                                     'team_name' => array('width'   => '15', 
                                                                          'label'   => 'LBL_LIST_TEAM'),
                                                                             );
?>
