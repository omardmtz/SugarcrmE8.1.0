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
$OBJECT_NAME = '<OBJECT_NAME>';
$listViewDefs[$module_name] = array(
	'NAME' => array(
		'width'   => '30',
		'label'   => 'LBL_LIST_SALE_NAME',
		'link'    => true,
        'default' => true),

	'SALES_STAGE' => array(
		'width'   => '10',
		'label'   => 'LBL_LIST_SALE_STAGE',
        'default' => true),
	'AMOUNT_USDOLLAR' => array(
		'width'   => '10',
		'label'   => 'LBL_LIST_AMOUNT',
        'align'   => 'right',
        'default' => true,
        'currency_format' => true,
	),
    $OBJECT_NAME.'_TYPE' => array(
        'width' => '15',
        'label' => 'LBL_TYPE'),
    'LEAD_SOURCE' => array(
        'width' => '15',
        'label' => 'LBL_LEAD_SOURCE'),
    'NEXT_STEP' => array(
        'width' => '10',
        'label' => 'LBL_NEXT_STEP'),
    'PROBABILITY' => array(
        'width' => '10',
        'label' => 'LBL_PROBABILITY'),
	'DATE_CLOSED' => array(
		'width' => '10',
		'label' => 'LBL_LIST_DATE_CLOSED',
        'default' => true),
    'DATE_ENTERED' => array(
        'width' => '10',
        'label' => 'LBL_DATE_ENTERED'),
    'CREATED_BY_NAME' => array(
        'width' => '10',
        'label' => 'LBL_CREATED'),
	'TEAM_NAME' => array(
		'width' => '5',
		'label' => 'LBL_LIST_TEAM',
        'default' => false),
	'ASSIGNED_USER_NAME' => array(
		'width' => '5',
		'label' => 'LBL_LIST_ASSIGNED_USER',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),
    'MODIFIED_BY_NAME' => array(
        'width' => '5',
        'label' => 'LBL_MODIFIED')
);

