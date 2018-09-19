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
$viewdefs['Employees']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
                            'form' => array(
                                'headerTpl'=>'modules/Employees/tpls/EditViewHeader.tpl',
                            ),
                            ),
 'panels' =>array (

  'default'=>array (
	    array (
	      'employee_status',
            array (
              'name'=>'picture',
              'label'=>'LBL_PICTURE_FILE',
            ),
	    ),
	    array (
	      'first_name',
	      array('name'=>'last_name', 'displayParams'=>array('required'=>true)),
	    ),
	    array (
          'title',
	      array('name'=>'phone_work','label'=>'LBL_OFFICE_PHONE'),
	    ),
	    array (
	      'department', 
	      'phone_mobile',
	    ),
	    array (
	      'reports_to_name',
	      'phone_other',
	    ),
	    array (
	      '',
	      array('name'=>'phone_fax', 'label'=>'LBL_FAX'),
	    ),
	    array (
	      '',
	      'phone_home',
	    ),
	    array (
	      'messenger_type',
	    ),
	    array (
	      'messenger_id',
	    ),
	    array (
	      array('name'=>'description', 'label'=>'LBL_NOTES'),
	    ),
	    array (
	      array('name'=>'address_street', 'type'=>'text', 'label'=>'LBL_PRIMARY_ADDRESS', 'displayParams'=>array('rows'=>2, 'cols'=>30)),
	      array('name'=>'address_city', 'label'=>'LBL_CITY'),
	    ),
	    array (
	      array('name'=>'address_state', 'label'=>'LBL_STATE'),
	      array('name'=>'address_postalcode', 'label'=>'LBL_POSTAL_CODE'),
	    ),
	    array (
	      array('name'=>'address_country', 'label'=>'LBL_COUNTRY'),
	    ),
        array(
          array (
              'name' => 'email',
              'label' => 'LBL_EMAIL',
            ),
  		),
   ),
),

);
?>
