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

$vardefs = array(
    'fields' => array(
        'following' => array(
            'massupdate' => false,
            'name' => 'following',
            'vname' => 'LBL_FOLLOWING',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'Is user following this record',
            'studio' => 'false',
            'link' => 'following_link',
            'rname' => 'id',
            'rname_exists' => true,
        ),
        'following_link' => array(
            'name' => 'following_link',
            'type' => 'link',
            'relationship' => strtolower($module).'_following',
            'source' => 'non-db',
            'vname' => 'LBL_FOLLOWING',
            'reportable' => false,
        ),
    ),
    'relationships' => array(
        strtolower($module).'_following' => array(
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => $module,
            'rhs_table' => $table_name,
            'rhs_key' => 'id',
            'relationship_type'=>'user-based',
            'join_table'=> 'subscriptions',
            'join_key_lhs' => 'created_by',
            'join_key_rhs' => 'parent_id',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => $module,
            'user_field'=>'created_by',
        ),
    ),
    'indices' => array(
    ),
);

