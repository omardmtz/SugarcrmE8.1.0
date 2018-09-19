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
  $searchdefs['EmailTemplates'] = array(
					'templateMeta' => array(
							'maxColumns' => '2',
  							'maxColumnsBasic' => '2',
                            'widths' => array('label' => '10', 'field' => '30'),
                           ),
                    'layout' => array(
						'basic_search' => array(
						 	'name',
                            'type' => array('name' => 'type', 'type'=>'enum', 'function' => array('name' => 'EmailTemplate::getTypeOptionsForSearch'))
							),
					    'advanced_search' => array('name',
                                                        'type' => array('name' => 'type', 'type'=>'enum', 'function' => array('name' => 'EmailTemplate::getTypeOptionsForSearch')),
                                                        'subject','description',
                                					    'assigned_user_id' =>
                                					      array (
                                					        'name' => 'assigned_user_id',
                                					        'type' => 'enum',
                                					        'label' => 'LBL_ASSIGNED_TO',
                                					        'function' =>
                                					         array (
                                    					          'name' => 'get_user_array',
                                    					          'params' => array ( 0 => false,), ),
                                					        'default' => true
                                					      ),
					    )
					),
 			   );
?>
