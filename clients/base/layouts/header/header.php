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

$viewdefs['base']['layout']['header'] = array(
    'components' => array(
        array(
            'layout' => 'module-list',
        ),
        array(
            'layout' => 'quicksearch',
        ),
        array(
            'view' => 'notifications',
        ),
        array(
            'view' => 'profileactions',
        ),
        array(
            'view' => 'quickcreate',
        ),
    ),
    'last_state' => array(
        'id' => 'app-header',
        'defaults' => array(
            'last-home' => 'dashboard',
        ),
    )
);
