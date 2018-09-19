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

$dictionary['AddressBook'] = array ('table' => 'address_book',
	'fields' => array (
		'assigned_user_id' => array (
			'name' => 'assigned_user_id',
			'vname' => 'LBL_USER_ID',
			'type' => 'id',
			'required' => true,
			'reportable' => false,
		),
		'bean' => array (
			'name' => 'bean',
			'vname' => 'LBL_BEAN',
			'type' => 'varchar',
			'len' => '50',
			'required' => true,
			'reportable' => false,
		),
		'bean_id' => array (
			'name' => 'bean_id',
			'vname' => 'LBL_BEAN_ID',
			'type' => 'id',
			'required' => true,
			'reportable' => false,
		),
	),
	'indices' => array (
                array(
                    'name' => 'idx_address_book_pk',
                    'type' => 'primary',
                    'fields' => array(
                        'assigned_user_id',
                        'bean_id',
                    ),
                ),
		array(
			'name' => 'ab_user_bean_idx',
			'type' =>'index',
			'fields' => array(
				'assigned_user_id',
				'bean',
			)
		),
	), /* end indices */
);

$dictionary['AddressBookMailingList'] = array ('table' => 'address_book_lists',
	'fields' => array (
		'id' => array(
			'name'	=> 'id',
			'type'	=> 'id',
			'required'	=> true,
			'reportable' => false,
		),
		'assigned_user_id' => array (
			'name' => 'assigned_user_id',
			'vname' => 'LBL_USER_ID',
			'type' => 'id',
			'required' => true,
			'reportable' => false,
		),
		'list_name' => array(
			'name'	=> 'list_name',
			'vname'	=> 'LBL_MAILING_LIST',
			'type'	=> 'varchar',
			'len'	=> 100,
			'required'	=> true,
			'reportable'	=> false,
		),
	),
	'indices' => array (
		array(
			'name'	=> 'abl_pk',
			'type'	=> 'primary',
			'fields'	=> array(
				'id',
			),
		),
		array(
			'name' => 'abml_user_bean_idx',
			'type' =>'index',
			'fields' => array(
				'assigned_user_id',
			)
		),
	), /* end indices */
);

$dictionary['AddressBookMailingListItems'] = array ('table' => 'address_book_list_items',
	'fields' => array (
		'list_id' => array(
			'name'	=> 'list_id',
			'type'	=> 'id',
			'required'	=> true,
			'reportable' => false,
		),
		'bean_id' => array(
			'name'	=> 'bean_id',
			'type'	=> 'id',
			'required'	=> true,
			'reportable' => false,
		),
	),
	'indices' => array (
		array(
			'name' => 'abli_list_id_idx',
			'type' =>'index',
			'fields' => array(
				'list_id',
			)
		),
                array(
                    'name' => 'idx_abli_pk',
                    'type' =>'primary',
                    'fields' => array(
                        'list_id',
                        'bean_id',
                    )
                ),
	), /* end indices */
);
