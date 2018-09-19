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
$dictionary['UserSignature'] = array(
    'table' => 'users_signatures',
    'hidden_to_role_assignment' => true,
    'favorites' => false,
    'fields' => array(
        'user_id' => array(
            'name' => 'user_id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
            'len' => 36,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'dbType' => 'varchar',
            'len' => 255,
            'unified_search' => true,
            'required' => true,
            'importable' => 'required',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'selected',
            'duplicate_on_record_copy' => 'always',
        ),
        'signature' => array(
            'name' => 'signature',
            'vname' => 'LBL_SIGNATURE',
            'type' => 'text',
            'reportable' => false,
        ),
        'signature_html' => array(
            'name' => 'signature_html',
            'vname' => 'LBL_SIGNATURE_HTML',
            'type' => 'text',
            'reportable' => false,
        ),
        'is_default' => array(
            'name' => 'is_default',
            'vname' => 'LBL_DEFAULT_SIGNATURE',
            'type' => 'bool',
            'sortable' => false,
            'source' => 'non-db',
            'duplicate_on_record_copy' => 'no',
            'massupdate' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_usersig_uid',
            'type' => 'index',
            'fields' => array('user_id'),
        ),
        array(
            'name' => 'idx_usersig_created_by',
            'type' => 'index',
            'fields' => array('created_by'),
        ),
    ),
    'visibility' => array('OwnerVisibility' => true),
);
VardefManager::createVardef('UserSignatures', 'UserSignature', array('default'));
