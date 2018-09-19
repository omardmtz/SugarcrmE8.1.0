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
$dictionary['ProductType'] = array(
    'table' => 'product_types',
    'favorites' => false,
    'comment' => 'Types of products',
    'fields' => array (
        'description' => array (
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'Product type description',
            'massupdate' => true,
            'sortable' => false,
        ),
        'list_order' => array (
            'name' => 'list_order',
            'vname' => 'LBL_LIST_ORDER',
            'type' => 'int',
            'len' => '4',
            'comment' => 'Order within list',
            'importable' => 'required',
            'required' => true,
        ),
    ),
    'acls' => array('SugarACLDeveloperOrAdmin' => array('aclModule' => 'Products', 'allowUserRead' => true)),
    'uses' => array(
        'basic',
    ),
);

VardefManager::createVardef(
    'ProductTypes',
    'ProductType'
);

$dictionary['ProductType']['fields']['tag']['massupdate'] = false;
