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
  $searchdefs['Quotes'] = array(
					'templateMeta' => array(
							'maxColumns' => '3',
                            'maxColumnsBasic' => '4',
                            'widths' => array('label' => '10', 'field' => '30'),
                           ),
                    'layout' => array(
						'basic_search' => array(
								'name',
								array('name'=>'current_user_only', 'label'=>'LBL_CURRENT_USER_FILTER', 'type'=>'bool'),

								array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
							    array ('name' => 'open_only', 'label' => 'LBL_OPEN_ITEMS', 'type' => 'bool', 'default' => false, 'width' => '10%'),
							),
						'advanced_search' => array(
							'name',
							'quote_num',
							'account_name',
							array('name'=>'total_usdollar', 'label'=>'LBL_LIST_AMOUNT'),
							array('name' => 'quote_type', 'type'=>'enum', 'options'=>'quote_type_dom'),
							'date_quote_expected_closed',
							array('name' => 'assigned_user_id', 'type' => 'enum', 'label' => 'LBL_ASSIGNED_TO', 'function' => array('name' => 'get_user_array', 'params' => array(false))),
							'quote_stage',


		      array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
						),
					),
 			   );
?>
