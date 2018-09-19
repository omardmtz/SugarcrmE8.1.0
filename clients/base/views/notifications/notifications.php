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

$viewdefs['base']['view']['notifications'] = array(
    // currently we don't support different filters per module
    // (Calls and Meetings) because this is temporary code.
    'remindersFilterDef' => array(
        'reminder_time' => array(
            '$gte' => 0,
        ),
        'status' => array(
            '$equals' => 'Planned',
        ),
        'accept_status_users' => array(
            '$not_equals' => 'decline',
        ),
    ),
    'remindersLimit' => 100,
    'fields' => array(
        'severity' => array(
            'name' => 'severity',
            'type' => 'severity',
        ),
    ),
);
