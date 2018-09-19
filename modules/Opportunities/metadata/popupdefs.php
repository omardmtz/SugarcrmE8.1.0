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

global $mod_strings;

$popupMeta = array('moduleMain' => 'Opportunity',
						'varName' => 'OPPORTUNITY',
						'orderBy' => 'name',
						'whereClauses' => 
							array('name' => 'opportunities.name', 
									'account_name' => 'accounts.name'),
						'searchInputs' =>
							array('name', 'account_name'),
						'listviewdefs' => array(
											'NAME' => array(
												'width'   => '30',  
												'label'   => 'LBL_LIST_OPPORTUNITY_NAME', 
												'link'    => true,
										        'default' => true),
											'ACCOUNT_NAME' => array(
												'width'   => '20', 
												'label'   => 'LBL_LIST_ACCOUNT_NAME', 
												'id'      => 'ACCOUNT_ID',
										        'module'  => 'Accounts',
										        'default' => true,
										        'sortable'=> true,
										        'ACLTag' => 'ACCOUNT'),
										    'OPPORTUNITY_TYPE' => array(
										        'width' => '15', 
										        'default' => true,
										        'label' => 'LBL_TYPE'),
											'SALES_STAGE' => array(
												'width'   => '10',  
												'label'   => 'LBL_LIST_SALES_STAGE',
										        'default' => true), 
										    'ASSIGNED_USER_NAME' => array(
												'width' => '5', 
												'label' => 'LBL_LIST_ASSIGNED_USER',
										        'default' => true),
											),
						'searchdefs'   => array(
										 	'name', 
											array('name' => 'account_name', 'displayParams' => array('hideButtons'=>'true', 'size'=>30, 'class'=>'sqsEnabled sqsNoAutofill')), 
											'opportunity_type',
											'sales_stage',
											array('name' => 'assigned_user_id', 'type' => 'enum', 'label' => 'LBL_ASSIGNED_TO', 'function' => array('name' => 'get_user_array', 'params' => array(false))),
										  )
						);


?>