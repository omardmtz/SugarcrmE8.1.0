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
$searchdefs['Contacts'] = array(
  		  'templateMeta' => array(
  		  					'maxColumns' => '3', 
							'maxColumnsBasic' => '4',
                            'widths' => array('label' => '10', 'field' => '30'), 
                           ),
		  'layout' => array (
		    'basic_search' => 
		    array (
		      array('name'=>'search_name','label' =>'LBL_NAME', 'type' => 'name'),
		      array (
		        'name' => 'current_user_only',
		        'label' => 'LBL_CURRENT_USER_FILTER',
		        'type' => 'bool',
		      ),

		      array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
		    ),
		    'advanced_search' => 
		    array (
		      'first_name' => 
		      array (
		        'name' => 'first_name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'email' => 
		      array (
		        'name' => 'email',
		        'label' => 'LBL_ANY_EMAIL',
		        'type' => 'name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'phone' => 
		      array (
		        'name' => 'phone',
		        'label' => 'LBL_ANY_PHONE',
		        'type' => 'name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'last_name' => 
		      array (
		        'name' => 'last_name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'address_street' => 
		      array (
		        'name' => 'address_street',
		        'label' => 'LBL_ANY_ADDRESS',
		        'type' => 'name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'address_city' => 
		      array (
		        'name' => 'address_city',
		        'label' => 'LBL_CITY',
		        'type' => 'name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'account_name' => 
		      array (
		        'name' => 'account_name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'address_state' => 
		      array (
		        'name' => 'address_state',
		        'label' => 'LBL_STATE',
		        'type' => 'name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'address_postalcode' => 
		      array (
		        'name' => 'address_postalcode',
		        'label' => 'LBL_POSTAL_CODE',
		        'type' => 'name',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'assigned_user_id' => 
		      array (
		        'name' => 'assigned_user_id',
		        'type' => 'enum',
		        'label' => 'LBL_ASSIGNED_TO',
		        'function' => 
		        array (
		          'name' => 'get_user_array',
		          'params' => 
		          array (
		            0 => false,
		          ),
		        ),
		        'default' => true,
		        'width' => '10%',
		      ),
		      'primary_address_country' => 
		      array (
		        'name' => 'primary_address_country',
		        'label' => 'LBL_COUNTRY',
		        'type' => 'name',
		        'options' => 'countries_dom',
		        'default' => true,
		        'width' => '10%',
		      ),
		      'lead_source' => 
		      array (
		        'name' => 'lead_source',
		        'default' => true,
		        'width' => '10%',
		      ),
		      

		      array ('name' => 'favorites_only','label' => 'LBL_FAVORITES_FILTER','type' => 'bool',),
		    ),
		  )
);
?>
