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
 * Define the before_save hook that will check to make sure that opportunity_name and account_name and product_name
 * are all empty before saving if their related_ids are empty
 */
$hook_array['before_save'][] = array(
    1,
    'checkRelatedName',
    'modules/ForecastWorksheets/ForecastWorksheetHooks.php',
    'ForecastWorksheetHooks',
    'checkRelatedName',
);

/**
 * Handle removing the names if they id's are removed from the forecast worksheets since how the relationships are
 * updated now it doesn't resave the bean
 */
$hook_array['after_relationship_delete'][] = array(
    1,
    'afterRelationshipDelete',
    'modules/ForecastWorksheets/ForecastWorksheetHooks.php',
    'ForecastWorksheetHooks',
    'afterRelationshipDelete',
);
