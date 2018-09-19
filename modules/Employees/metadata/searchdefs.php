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
  $searchdefs['Employees'] = array(
  					'templateMeta' => array('maxColumns' => '3', 'maxColumnsBasic' => '4',
                            'widths' => array('label' => '10', 'field' => '30'),
                           ),
                    'layout' => array(
                    	'basic_search' => array(
                    		array('name'=>'search_name','label' =>'LBL_NAME', 'type' => 'name'),
                            array('name'=>'open_only_active_users', 'label'=>'LBL_ONLY_ACTIVE', 'type' => 'bool'),
							),
                    	'advanced_search' => array(
                    	    'first_name',
                    	    'last_name',
                    	    'employee_status',
                    	    'title',
                    	    'phone' =>
                              array (
                                'name' => 'phone',
                                'label' => 'LBL_ANY_PHONE',
                                'type' => 'name',
                                'default' => true,
                                'width' => '10%',
                              ),
                    	    'department',
                    	    'email' =>
                              array (
                                'name' => 'email',
                                'label' => 'LBL_ANY_EMAIL',
                                'type' => 'name',
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

                    	    'address_country' =>
                              array (
                                'name' => 'address_country',
                                'label' => 'LBL_COUNTRY',
                                'type' => 'name',
                                'default' => true,
                                'width' => '10%',
                              ),
                    		),
					),
 			   );
