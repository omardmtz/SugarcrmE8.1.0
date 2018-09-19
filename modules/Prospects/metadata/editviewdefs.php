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
$viewdefs['Prospects']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2', 

                            'useTabs' => true,
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
     ),
 'panels' =>array (
  'lbl_prospect_information' => 
  array (
    
    array (
      array (
        'name' => 'first_name',
        'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}' 
      . '&nbsp;<input name="first_name"  id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
      ),
    ),
    
    array (
      array('name'=>'last_name',
            'displayParams'=>array('required'=>true),
      ),
      'phone_work',
    ),
    
    array (
      'title',
      'phone_mobile',
    ),
    
    array (
      'department',
      'phone_fax',
    ),
    
    array (
      'account_name',
    ),
    
    array (
      array (
	      'name' => 'primary_address_street',
          'hideLabel' => true,      
	      'type' => 'address',
	      'displayParams'=>array('key'=>'primary', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),
      ),
      
      array (
	      'name' => 'alt_address_street',
	      'hideLabel'=>true,
	      'type' => 'address',
	      'displayParams'=>array('key'=>'alt', 'copy'=>'primary', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),      
      ),
    ),
    array('email1'),
    array (
      array('name'=>'description', 
            'label'=>'LBL_DESCRIPTION'),
    ),
    ),
  'LBL_MORE_INFORMATION' => array(
    array (
      'do_not_call',
    ),
    ),
  'LBL_PANEL_ASSIGNMENT' => array(
    array (
	  'assigned_user_name',

	  array('name'=>'team_name','displayParams'=>array('required'=>true)),
    ),    

  ),
)


);
?>
