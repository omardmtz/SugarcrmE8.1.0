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

global $current_user;

$dashletData['MyDocumentsDashlet']['searchFields'] = array('date_entered'    => array('default' => ''),
                                                          'document_name'    => array('default' => ''),
                                                          'category_id'      => array('default' => ''),
 														  'doc_type'  => array('default' => ''),
 														  'status_id'     => array('default' => ''),
 														  'active_date'      => array('default' => ''),
                                                          'team_id'          => array('default' => '', 'label'=>'LBL_TEAMS'),

                                                          'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                      'default' => $current_user->name,
																					  'label' => 'LBL_ASSIGNED_TO'));



$dashletData['MyDocumentsDashlet']['columns'] =  array('document_name' => array('width'   => '40', 
                                                                      'label'   => 'LBL_DOCUMENT_NAME',
                                                                      'link'    => true,
                                                                      'default' => true),
                                                      'category_id' => array('width' => '8',
                                                                         'label' => 'LBL_CATEGORY',
																		 'default' => true), 
                                                      'subcategory_id' => array('width' => '8',
                                                                         'label' => 'LBL_SUBCATEGORY',
																		 'default' => false),
                                                      'template_type' => array('width' => '8',
                                                                         'label' => 'LBL_TEMPLATE_TYPE',
																		 'default' => true), 
                                                      'is_template' => array('width' => '8',
                                                                         'label' => 'LBL_IS_TEMPLATE',
																		 'default' => false), 
													  'status_id' => array('width' => '8',
                                                                         'label' => 'LBL_STATUS',
																		 'default' => true), 
													  'active_date' => array('width' => '8',
                                                                         'label' => 'LBL_ACTIVE_DATE',
																		 'default' => true),
													  'doc_type' => array('width' => '8',
                                                                         'label' => 'LBL_DOC_TYPE',
																		 'default' => false), 
													  'exp_date' => array('width' => '8',
                                                                         'label' => 'LBL_EXPIRATION_DATE',
																		 'default' => false), 
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
                                                      'FILENAME' => array (
                                                                    'width' => '20%',
                                                                    'label' => 'LBL_FILENAME',
                                                                    'link' => true,
                                                                    'default' => false,
                                                                    'bold' => false,
                                                                    'displayParams' => array ( 'module' => 'Documents', ),
                                                                    'related_fields' =>
                                                                    array (
                                                                        0 => 'document_revision_id',
                                                                        1 => 'doc_id',
                                                                        2 => 'doc_type',
                                                                        3 => 'doc_url',
                                                                    ),
                                                                  ),
                                               );
?>