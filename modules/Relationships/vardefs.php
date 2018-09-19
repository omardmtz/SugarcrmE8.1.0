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
$dictionary['Relationship'] =
    array(
        'table' => 'relationships',
        'fields' => array(
            'id' =>
                array(
                    'name' => 'id',
                    'vname' => 'LBL_ID',
                    'type' => 'id',
                    'required' => true,
                ),
            'relationship_name' =>
                array(
                    'name' => 'relationship_name',
                    'vname' => 'LBL_RELATIONSHIP_NAME',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 150,
                    'importable' => 'required',
                ),
            'lhs_module' =>
                array(
                    'name' => 'lhs_module',
                    'vname' => 'LBL_LHS_MODULE',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 100
                ),
            'lhs_table' =>
                array(
                    'name' => 'lhs_table',
                    'vname' => 'LBL_LHS_TABLE',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 64
                ),
            'lhs_key' =>
                array(
                    'name' => 'lhs_key',
                    'vname' => 'LBL_LHS_KEY',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 64
                ),
            'lhs_vname' =>
                array(
                    'name' => 'lhs_vname',
                    'vname' => 'LBL_LHS_VNAME',
                    'type' => 'varchar',
                    'required' => false,
                    'len' => 64
                ),
            'rhs_module' =>
                array(
                    'name' => 'rhs_module',
                    'vname' => 'LBL_RHS_MODULE',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 100
                ),
            'rhs_table' =>
                array(
                    'name' => 'rhs_table',
                    'vname' => 'LBL_RHS_TABLE',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 64
                ),
            'rhs_key' =>
                array(
                    'name' => 'rhs_key',
                    'vname' => 'LBL_RHS_KEY',
                    'type' => 'varchar',
                    'required' => true,
                    'len' => 64
                ),
            'rhs_vname' =>
                array(
                    'name' => 'rhs_vname',
                    'vname' => 'LBL_RHS_VNAME',
                    'type' => 'varchar',
                    'required' => false,
                    'len' => 64
                ),
            'join_table' =>
                array(
                    'name' => 'join_table',
                    'vname' => 'LBL_JOIN_TABLE',
                    'type' => 'varchar',
                    // Bug #41454 : Custom Relationships with Long Names do not Deploy Properly in MSSQL Environments
                    // Maximum length of identifiers for MSSQL, DB2 is 128 symbols
                    // @see e.g. MssqlManager :: $maxNameLengths property
                    // @see AbstractRelationship::getRelationshipMetaData()
                    'len' => 128
                ),
            'join_key_lhs' =>
                array(
                    'name' => 'join_key_lhs',
                    'vname' => 'LBL_JOIN_KEY_LHS',
                    'type' => 'varchar',
                    // Bug #41454 : Custom Relationships with Long Names do not Deploy Properly in MSSQL Environments
                    // Maximum length of identifiers for MSSQL, DB2 is 128 symbols
                    // @see e.g. MssqlManager :: $maxNameLengths property
                    // @see AbstractRelationship::getRelationshipMetaData()
                    'len' => 128
                ),
            'join_key_rhs' =>
                array(
                    'name' => 'join_key_rhs',
                    'vname' => 'LBL_JOIN_KEY_RHS',
                    'type' => 'varchar',
                    // Bug #41454 : Custom Relationships with Long Names do not Deploy Properly in MSSQL Environments
                    // Maximum length of identifiers for MSSQL, DB2 is 128 symbols
                    // @see e.g. MssqlManager :: $maxNameLengths property
                    // @see AbstractRelationship::getRelationshipMetaData()
                    'len' => 128
                ),
            'relationship_type' =>
                array(
                    'name' => 'relationship_type',
                    'vname' => 'LBL_RELATIONSHIP_TYPE',
                    'type' => 'varchar',
                    'len' => 64
                ),
            'relationship_role_column' =>
                array(
                    'name' => 'relationship_role_column',
                    'vname' => 'LBL_RELATIONSHIP_ROLE_COLUMN',
                    'type' => 'varchar',
                    'len' => 64
                ),
            'relationship_role_column_value' =>
                array(
                    'name' => 'relationship_role_column_value',
                    'vname' => 'LBL_RELATIONSHIP_ROLE_COLUMN_VALUE',
                    'type' => 'varchar',
                    'len' => 50
                ),
            'reverse' =>
                array(
                    'name' => 'reverse',
                    'vname' => 'LBL_REVERSE',
                    'type' => 'bool',
                    'default' => '0'
                ),
            'deleted' =>
                array(
                    'name' => 'deleted',
                    'vname' => 'LBL_DELETED',
                    'type' => 'bool',
                    'reportable' => false,
                    'default' => '0'
                ),
        ),
        'indices' => array(
            array('name' => 'relationshippk', 'type' => 'primary', 'fields' => array('id')),
            array('name' => 'idx_rel_name', 'type' => 'index', 'fields' => array('relationship_name')),
        ),

        'acls' => array('SugarACLDeveloperOrAdmin' => array('allowUserRead' => true))
    );

