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

$viewdefs['Filters']['base']['layout']['filter'] = array(
    'components' => array(
        array(
            'view' => 'filter-module-dropdown',
        ),
        array(
            'view' => 'filter-filter-dropdown',
        ),
        array(
            'view' => 'filter-quicksearch',
        ),
    ),
    'last_state' => array(
        'id' => 'filter',
    ),
    'layoutType' => 'records',
);
