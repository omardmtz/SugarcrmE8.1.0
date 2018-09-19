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

// $Id: dashletviewdefs.php 16278 2006-08-22 19:09:18Z awu $

global $current_user;

$dashletData['SugarFavoritesDashlet']['searchFields'] = array();
$dashletData['SugarFavoritesDashlet']['columns'] =  array(   
                                                    'record_name' => array('width' => '29', 
                                                                         'label' => 'LBL_LIST_NAME',
                                                                         'sortable' => false,
                                                                         'dynamic_module' => 'MODULE',
                                                                         'link' => true,
                                                                         'id' => 'RECORD_ID',
                                                                         'ACLTag' => 'RECORD_NAME',
                                                                         'related_fields' => array('record_id', 'module'),
																		 'default' => true,
																		),
													
                                                      'module' => array('width'   => '15', 
                                                                              'label'   => 'LBL_LIST_MODULE',
                                                                              'default' => true),
                                                      'date_entered' => array('width'   => '15', 
                                                                              'label'   => 'LBL_DATE_ENTERED',
                                                                              'default' => true),
                                               );