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
$dictionary['Audit'] = array('fields' =>
array(
    'parent_id' => array(
        'name' => 'parent_id',
        'type' => 'id',
        'source' => 'non-db',
    ),
    'date_created' => array(
        'name' => 'date_created',
        'type' => 'datetime',
        'source' => 'non-db',
    ),
    'created_by' => array(
        'name' => 'created_by',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'created_by_username' => array(
        'name' => 'created_by_username',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'field_name' => array(
        'name' => 'field_name',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'data_type' => array(
        'name' => 'data_type',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'before_value_string' => array(
        'name' => 'before_value_string',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'after_value_string' => array(
        'name' => 'after_value_string',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'before' => array(
        'name' => 'before',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'after' => array(
        'name' => 'after',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
    'data_type' => array(
        'name' => 'data_type',
        'type' => 'varchar',
        'source' => 'non-db',
    ),
));
