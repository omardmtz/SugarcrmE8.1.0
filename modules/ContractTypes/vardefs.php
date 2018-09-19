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
$dictionary['ContractType'] = array(
    'favorites' => false,
    'table' => 'contract_types',
    'comment' => 'Specifies the types of contracts available',

    'fields' => array (
        'list_order' => array (
            'name' => 'list_order',
            'vname' => 'LBL_LIST_ORDER',
            'type' => 'int',
            'len' => '4',
            'comment' => 'Relative order in drop down list',
            'importable' => 'required',
        ),
        'documents' => array (
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'contracttype_documents',
            'source'=>'non-db',
            'vname'=>'LBL_DOCUMENTS',
        ),
    ),
    'acls' => array('SugarACLDeveloperOrAdmin' => array('aclModule'=>'Contracts')),
    'uses' => array(
        'basic',
    ),
);

VardefManager::createVardef(
    'ContractTypes',
    'ContractType'
);

$dictionary['ContractType']['fields']['tag']['massupdate'] = false;
