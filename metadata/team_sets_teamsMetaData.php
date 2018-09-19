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

$dictionary['team_sets_teams'] = array(
    'table' => 'team_sets_teams',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
        ),
        'team_set_id' => array(
            'name' => 'team_set_id',
            'type' => 'id',
            'required' => true,
        ),
        'team_id' => array(
            'name' => 'team_id',
            'type' => 'id',
            'required' => true,
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_ud_id',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_ud_set_id',
            'type' => 'unique',
            'fields' => array(
                'team_set_id',
                'team_id',
            ),
        ),
        array(
            'name' => 'idx_ud_team_id',
            'type' => 'index',
            'fields' => array(
                'team_id',
            ),
        ),
        array(
            'name' => 'idx_ud_team_set_id',
            'type' => 'index',
            'fields' => array(
                'team_set_id',
            ),
        ),
    ),
    'relationships' => array(
        'team_sets_teams' => array(
            'lhs_module' => 'TeamSets',
            'lhs_table' => 'team_sets',
            'lhs_key' => 'id',
            'rhs_module' => 'Teams',
            'rhs_table' => 'teams',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'team_sets_teams',
            'join_key_lhs' => 'team_set_id',
            'join_key_rhs' => 'team_id',
        ),
    ),
);
