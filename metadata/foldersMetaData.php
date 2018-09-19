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

$dictionary['folders'] = array(
    'table' => 'folders',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 25,
            'required' => true,
        ),
        'folder_type' => array(
            'name' => 'folder_type',
            'type' => 'varchar',
            'len' => 25,
            'default' => null,
        ),
        'parent_folder' => array(
            'name' => 'parent_folder',
            'type' => 'id',
            'required' => false,
        ),
        'has_child' => array(
            'name' => 'has_child',
            'type' => 'bool',
            'default' => '0',
        ),
        'is_group' => array(
            'name' => 'is_group',
            'type' => 'bool',
            'default' => '0',
        ),
        'is_dynamic' => array(
            'name' => 'is_dynamic',
            'type' => 'bool',
            'default' => '0',
        ),
        'dynamic_query' => array(
            'name' => 'dynamic_query',
            'type' => 'text',
        ),
        'assign_to_id' => array(
            'name' => 'assign_to_id',
            'type' => 'id',
            'required' => false,
        ),
        'team_id' => array(
            'name' => 'team_id',
            'type' => 'id',
            'required' => false,
        ),
        'team_set_id' => array(
            'name' => 'team_set_id',
            'type' => 'id',
            'required' => false,
        ),
        'acl_team_set_id' => array(
            'name' => 'acl_team_set_id',
            'type' => 'id',
            'required' => false,
        ),
        'created_by' => array(
            'name' => 'created_by',
            'type' => 'id',
            'required' => true,
        ),
        'modified_by' => array(
            'name' => 'modified_by',
            'type' => 'id',
            'required' => true,
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'folderspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_parent_folder',
            'type' => 'index',
            'fields' => array(
                'parent_folder',
            ),
        ),
    ),
);

$dictionary['folders_subscriptions'] = array(
    'table' => 'folders_subscriptions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'folder_id' => array(
            'name' => 'folder_id',
            'type' => 'id',
            'required' => true,
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'type' => 'id',
            'required' => true,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'folders_subscriptionspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_folder_id_assigned_user_id',
            'type' => 'index',
            'fields' => array(
                'folder_id',
                'assigned_user_id',
            ),
        ),
    ),
);

$dictionary['folders_rel'] = array(
    'table' => 'folders_rel',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
        ),
        'folder_id' => array(
            'name' => 'folder_id',
            'type' => 'id',
            'required' => true,
        ),
        'polymorphic_module' => array(
            'name' => 'polymorphic_module',
            'type' => 'varchar',
            'len' => 25,
            'required' => true,
        ),
        'polymorphic_id' => array(
            'name' => 'polymorphic_id',
            'type' => 'id',
            'required' => true,
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'folders_relpk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_poly_module_poly_id',
            'type' => 'index',
            'fields' => array(
                'polymorphic_module',
                'polymorphic_id',
            ),
        ),
        array(
            'name' => 'idx_fr_id_deleted_poly',
            'type' => 'index',
            'fields' => array(
                'folder_id',
                'deleted',
                'polymorphic_id',
            ),
        ),
    ),
);
