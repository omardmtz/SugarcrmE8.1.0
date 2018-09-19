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

$dictionary['Category'] = array(
    'comment' => 'Category module',
    'table' => 'categories',
    'audited' => false,
    'activity_enabled' => false,
    'favorites' => false,
    'optimistic_locking' => false,
    'unified_search' => true,
    'full_text_search' => false,
    'unified_search_default_enabled' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'root' => array(
            'name' => 'root',
            'type' => 'id',
            'comment' => 'Root ID',
            'isnull' => true,
            'required' => false,
        ),
        'lft' => array(
            'name' => 'lft',
            'type' => 'int',
            'comment' => 'Left node index',
            'isnull' => false,
            'required' => true,
        ),
        'rgt' => array(
            'name' => 'rgt',
            'type' => 'int',
            'comment' => 'Right node index',
            'isnull' => false,
            'required' => true,
        ),
        'lvl' => array(
            'name' => 'lvl',
            'type' => 'int',
            'comment' => 'Node level',
            'isnull' => false,
            'required' => true,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => '255',
            'comment' => 'Category name',
            'required' => true,
        ),
        'is_external' => array(
            'name' => 'is_external',
            'vname' => 'LBL_IS_EXTERNAL',
            'type' => 'bool',
            'isnull' => 'true',
            'comment' => 'External category flag',
            'default' => 0,
            'duplicate_on_record_copy' => 'no',
        ),
    ),
    'relationships' => array(),
    'indices' => array(),
    'duplicate_check' => array(
        'enabled' => false,
    ),
    'uses' => array(
        'basic',
        'external_source',
    ),
    'ignore_templates' => array(
        'taggable',
    ),
);

VardefManager::createVardef(
    'Categories',
    'Category'
);
