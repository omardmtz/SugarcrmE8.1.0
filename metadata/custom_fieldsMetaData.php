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

$dictionary['custom_fields'] = array(
    'table' => 'custom_fields',
    'fields' => array(
        'bean_id' => array(
            'name' => 'bean_id',
            'type' => 'id',
            'len' => '36',
        ),
        'set_num' => array(
            'name' => 'set_num',
            'type' => 'int',
            'len' => '11',
            'default' => '0',
        ),
        'field0' => array(
            'name' => 'field0',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field1' => array(
            'name' => 'field1',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field2' => array(
            'name' => 'field2',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field3' => array(
            'name' => 'field3',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field4' => array(
            'name' => 'field4',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field5' => array(
            'name' => 'field5',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field6' => array(
            'name' => 'field6',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field7' => array(
            'name' => 'field7',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field8' => array(
            'name' => 'field8',
            'type' => 'varchar',
            'len' => '255',
        ),
        'field9' => array(
            'name' => 'field9',
            'type' => 'varchar',
            'len' => '255',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_beanid_set_num',
            'type' => 'index',
            'fields' => array(
                'bean_id',
                'set_num',
            ),
        ),
    ),
);
