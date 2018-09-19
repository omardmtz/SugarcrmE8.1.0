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
  $searchdefs['EmailMan'] = array(
					'templateMeta' => array(
							'maxColumns' => '3', 'maxColumnsBasic' => '4',
                            'widths' => array('label' => '10', 'field' => '30'),
                           ),
                    'layout' => array(
						'basic_search' => array(
						    array('name'=>'campaign_name', 'label'=>'LBL_LIST_CAMPAIGN',),
						 	array('name'=>'current_user_only', 'label'=>'LBL_CURRENT_USER_FILTER', 'type'=>'bool'),
						),
						'advanced_search' => array(
						    array('name'=>'campaign_name', 'label'=>'LBL_LIST_CAMPAIGN',),
						 	array('name'=>'to_name', 'label'=>'LBL_LIST_RECIPIENT_NAME'),
						 	array('name'=>'to_email', 'label'=>'LBL_LIST_RECIPIENT_EMAIL'),
						 	array('name'=>'current_user_only', 'label'=>'LBL_CURRENT_USER_FILTER', 'type'=>'bool'),
						),
					),
 			   );
?>
