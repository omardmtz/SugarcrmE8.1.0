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

$dictionary['collections']['events'] = array(
    'name' => 'events',
    'modules' => array(
        'Calls',
        'Meetings',
        array(
            'name' => 'Tasks',
            'field_map' => array(
                'date_end' => 'date_due',
            )
        ),
    ),
    'order_by' => 'date_end:desc',
);
