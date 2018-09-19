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
$module_name = '<module_name>';
$_object_name = '<_object_name>';
$viewdefs[$module_name]['EditView'] = array(
    'templateMeta' => array(
                            'form' => array('buttons'=>array('SAVE', 'CANCEL')),
                            'maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30'),
                                            ),
                            'includes'=> array(
                                            array('file'=>'modules/Accounts/Account.js'),
                                         ),
                           ),

    'panels' => array(
	   'lbl_account_information'=>array(
		        array('name','phone_office'),
		        array('website', 'phone_fax'),
		        array('ticker_symbol', 'phone_alternate'),
		        array('rating', 'employees'),
		        array('ownership','industry'),

		        array($_object_name . '_type', 'annual_revenue'),
			    array (
			      array('name'=>'team_name', 'displayParams'=>array('display'=>true)),
			      ''
			    ),
                array('assigned_user_name'),
	   ),
	   'lbl_address_information'=>array(
				array (
				      array (
					  'name' => 'billing_address_street',
				      'hideLabel'=> true,
				      'type' => 'address',
				      'displayParams'=>array('key'=>'billing', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),
				      ),
				array (
				      'name' => 'shipping_address_street',
				      'hideLabel' => true,
				      'type' => 'address',
				      'displayParams'=>array('key'=>'shipping', 'copy'=>'billing', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),
				      ),
				),
	   ),

  	   'lbl_email_addresses'=>array(
  				array('email1')
  	   ),

	   'lbl_description_information' =>array(
		        array('description'),
	   ),

    )
);
