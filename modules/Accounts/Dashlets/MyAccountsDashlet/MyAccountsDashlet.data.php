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

// $Id: MyAccountsDashlet.data.php 56127 2010-04-27 00:03:05Z clee $

global $current_user;

$dashletData['MyAccountsDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
                                                          'account_type'    => array('default' => ''),
 														  'industry'    => array('default' => ''),
														  'billing_address_country' => array('default'=>''),
                                                          'team_id'          => array('default' => '', 'label'=>'LBL_TEAMS'),
                                                          'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                      'default' => $current_user->name,
																					  'label' => 'LBL_ASSIGNED_TO'));
$dashletData['MyAccountsDashlet']['columns'] =  array('name' => array('width'   => '40', 
                                                                      'label'   => 'LBL_LIST_ACCOUNT_NAME',
                                                                      'link'    => true,
                                                                      'default' => true),
                                                      'website' => array('width' => '8',
                                                                         'label' => 'LBL_WEBSITE',
																		 'default' => true), 
                                                      'phone_office' => array('width'   => '15', 
                                                                              'label'   => 'LBL_LIST_PHONE',
                                                                              'default' => true),
                                                      'phone_fax' => array('width' => '8',
                                                                          'label' => 'LBL_PHONE_FAX'),
                                                      'phone_alternate' => array('width' => '8',
                                                                                 'label' => 'LBL_OTHER_PHONE'),
                                                      'billing_address_city' => array('width' => '8',
                                                                                      'label' => 'LBL_BILLING_ADDRESS_CITY'),
                                                      'billing_address_street' => array('width' => '8',
                                                                                        'label' => 'LBL_BILLING_ADDRESS_STREET'),
                                                      'billing_address_state' => array('width' => '8',
                                                                                       'label' => 'LBL_BILLING_ADDRESS_STATE'),
                                                      'billing_address_postalcode' => array('width' => '8',
                                                                                            'label' => 'LBL_BILLING_ADDRESS_POSTALCODE'),
                                                      'billing_address_country' => array('width' => '8',
                                                                                         'label' => 'LBL_BILLING_ADDRESS_COUNTRY',
																					     'default' => true),
                                                      'shipping_address_city' => array('width' => '8',
                                                                                       'label' => 'LBL_SHIPPING_ADDRESS_CITY'),
                                                      'shipping_address_street' => array('width' => '8',
                                                                                        'label' => 'LBL_SHIPPING_ADDRESS_STREET'),
                                                      'shipping_address_state' => array('width' => '8',
                                                                                        'label' => 'LBL_SHIPPING_ADDRESS_STATE'),
                                                      'shipping_address_postalcode' => array('width' => '8',
                                                                                             'label' => 'LBL_SHIPPING_ADDRESS_POSTALCODE'),
                                                      'shipping_address_country' => array('width' => '8',
                                                                                          'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY'),
                                                      'email1' => array('width' => '8',
                                                                        'label' => 'LBL_EMAIL_ADDRESS_PRIMARY'),
                                                      'parent_name' => array('width'    => '15',
                                                                              'label'    => 'LBL_MEMBER_OF',
                                                                              'sortable' => false),
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
