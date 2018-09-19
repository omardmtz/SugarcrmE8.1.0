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

$dictionary['kbusefulness'] = array(
    'table' => 'kbusefulness',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'len' => 36,
            'required' => true,
        ),
        'kbarticle_id' => array(
            'name' => 'kbarticle_id',
            'type' => 'id',
            'len' => 36,
            'required' => true,
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
            'len' => 36,
            'required' => true,
        ),
        'contact_id' => array(
            'name' => 'contact_id',
            'type' => 'id',
            'len' => 36,
            'required' => false,
            'isnull' => true,
        ),
        'vote' => array(
            'name' => 'vote',
            'type' => 'smallint',
            'isnull' => 'true',
        ),
        'zeroflag' => array(
            'name' => 'zeroflag',
            'type' => 'tinyint',
            'isnull' => 'true',
        ),
        'ssid' => array(
            'name' => 'ssid',
            'type' => 'id',
            'isnull' => 'true',
        ),

        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),

        'deleted' => array (
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'kbusefulness_pk',
            'type' => 'primary',
            'fields' => array('id'),
        ),
        array(
            'name' => 'kbusefulness_user',
            'type' => 'index',
            'fields' => array('kbarticle_id', 'user_id'),
        ),
    ),

    'relationships' => array(
        'kbusefulness' => array (
            'lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'KBContents',
            'rhs_table' => 'kbcontents',
            'rhs_key' => 'kbarticle_id',
            'join_key_rhs' => 'kbarticle_id',
            'join_key_lhs' => 'user_id',
            'true_relationship_type' => 'many-to-many',
            'primary_flag_column' => 'zeroflag',
            'relationship_class' => 'KBUsefulnessRelationship',
            'relationship_file' => 'modules/KBContents/KBUsefulnessRelationship.php',
        ),
    )
);
