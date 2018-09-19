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

// These are required elements of app list strings. Supports being able to control
// which list items are allowed to be manipulated in the dropdown editor in 
// studio. The items in the required list map to the keys of the app list string
// list.
$app_list_strings_required = array(
    'sales_stage_dom' => array(
        'Closed Won',
        'Closed Lost',
    ),
    'sales_status_dom' => array(
        'New',
        'In Progress',
        'Closed Won',
        'Closed Lost',
    ),
);
