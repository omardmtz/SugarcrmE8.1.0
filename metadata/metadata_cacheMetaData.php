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

$dictionary['metadata_cache'] = array(
    'table' => 'metadata_cache',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'type' => array(
            'name' => 'type',
            'type' => 'varchar',
            'len' => '255',
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'longblob',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
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
            'name' => 'matadata_cache_primary',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'type_indx',
            'type' => 'index',
            'fields' => array(
                'type',
            ),
        ),
    ),
);
