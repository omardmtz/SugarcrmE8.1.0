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

$dictionary['users_feeds'] = array(
    'table' => 'users_feeds',
    'fields' => array(
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id',
        ),
        'feed_id' => array(
            'name' => 'feed_id',
            'type' => 'id',
        ),
        'rank' => array(
            'name' => 'rank',
            'type' => 'int',
            'required' => false,
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
            'required' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_ud_user_id',
            'type' => 'index',
            'fields' => array(
                'user_id',
                'feed_id',
            ),
        ),
    ),
);
