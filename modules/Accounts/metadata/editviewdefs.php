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

$viewdefs['Accounts']['EditView'] = array(
    'templateMeta' => array(
                            'form' => array('buttons'=>array('SAVE', 'CANCEL')),
                            'maxColumns' => '2', 
                            'useTabs' => true,
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30'),
                                            ),
                            'includes'=> array(
                                            array('file'=>'modules/Accounts/Account.js'),
                                         ),
                           ),
                           
    'panels' => array(
    
      'lbl_account_information' => 
      array (
        array (
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          array (
            'name' => 'phone_office',
            'label' => 'LBL_PHONE_OFFICE',
          ),
        ),

        array (

          array (
            'name' => 'website',
            'type' => 'link',
            'label' => 'LBL_WEBSITE',
          ),

          array (
            'name' => 'phone_fax',
            'label' => 'LBL_FAX',
          ),
        ),

        array (

          array (
            'name' => 'billing_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),

          array (
            'name' => 'shipping_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
              'copy' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
        ),

        array (

          array (
            'name' => 'email',
            'studio' => 'false',
            'label' => 'LBL_EMAIL',
          ),
        ),

        array (

          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'LBL_PANEL_ADVANCED' => 
      array (

        array (
          'account_type',
          'industry'
        ),

        array (
          'annual_revenue',
          'employees'
        ),

        array (
          'sic_code',
          'ticker_symbol'
        ),

        array (
          'parent_name',
          'ownership'
        ),

        array (
          'campaign_name',
          'rating'
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (

        array (
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
		  array (
		    'name'=>'team_name', 'displayParams'=>array('display'=>true),
		  ),
        ),
      ),
    )
);
?>
