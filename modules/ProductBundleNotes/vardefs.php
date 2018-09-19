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
$dictionary['ProductBundleNote'] = array(
    'table' => 'product_bundle_notes',
    'comment' => 'Group-level comments on quotes',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_NAME',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Unique identifier'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => false,
            'default' => '0',
            'reportable' => false,
            'comment' => 'Record deletion indicator'
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record created'
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'Date record last modified'
        ),
        'modified_user_id' => array(
            'name' => 'modified_user_id',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'dbType' => 'id',
            'reportable' => true,
            'comment' => 'User who last modified record'
        ),
        'created_by' => array(
            'name' => 'created_by',
            'rname' => 'user_name',
            'id_name' => 'modified_user_id',
            'vname' => 'LBL_ASSIGNED_TO',
            'type' => 'assigned_user_name',
            'table' => 'users',
            'isnull' => 'false',
            'dbType' => 'id',
            'comment' => 'User who created record'
        ),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'textarea',
            'dbType' => 'text',
            'comment' => 'Note content',
        ),
        'product_bundles' => array(
            'name' => 'product_bundles',
            'type' => 'link',
            'relationship' => 'product_bundle_note',
            'module' => 'ProductBundles',
            'bean_name' => 'ProductBundle',
            'source' => 'non-db',
            'rel_fields' => array(
                'note_index' => array(
                    'type' => 'integer'
                )
            ),
            'relationship_fields' => array(
                'note_index' => 'note_index'
            ),
            'vname' => 'LBL_NOTES',
        ),
        'position' => array(
            'massupdate' => false,
            'name' => 'position',
            'type' => 'integer',
            'studio' => false,
            'source' => 'non-db',
            'vname' => 'LBL_BUNDLE_NOTE_POSITION',
            'importable' => false,
            'link' => 'product_bundles',
            'rname_link' => 'note_index',
        ),

    ),
    'indices' => array(
        array('name' => 'procuct_bundle_notespk', 'type' => 'primary', 'fields' => array('id')),
    )
);
