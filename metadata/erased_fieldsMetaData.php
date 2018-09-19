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

$dictionary['erased_fields'] = array(
    'table' => 'erased_fields',
    'fields' => array(
        'bean_id' => array(
            'name' => 'bean_id',
            'type' => 'id',
            'required' => true,
        ),
        'table_name' => array(
            'name' => 'table_name',
            'type' => 'varchar',
            'len' => '128',
            'required' => true,
            'isnull' => false,
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'json',
            'dbType' => 'text',
            'required' => true,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_erased_fields_pk',
            'type' => 'primary',
            'fields' => array(
                'bean_id',
                'table_name',
            ),
        ),
    ),
);
