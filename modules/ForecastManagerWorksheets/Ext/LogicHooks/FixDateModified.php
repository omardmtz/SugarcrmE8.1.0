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

/**
 * Reset the date_modified so we have the seconds on it
 */
$hook_array['process_record'][] = array(
    1,
    'fixDateModified',
    'modules/ForecastManagerWorksheets/ForecastManagerWorksheetHooks.php',
    'ForecastManagerWorksheetHooks',
    'fixDateModified',
);

$hook_array['after_retrieve'][] = array(
    1,
    'fixDateModified',
    'modules/ForecastManagerWorksheets/ForecastManagerWorksheetHooks.php',
    'ForecastManagerWorksheetHooks',
    'fixDateModified',
);
