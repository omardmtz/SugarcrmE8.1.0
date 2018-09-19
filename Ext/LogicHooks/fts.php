<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

// Full text search after_save hook to update/index a bean

$hook_array['after_save'][] = array(
    1,
    'fts',
    null,
    '\\Sugarcrm\\Sugarcrm\\SearchEngine\\HookHandler',
    'indexBean',
);

$hook_array['after_delete'][] = array(
    1,
    'fts',
    null,
    '\\Sugarcrm\\Sugarcrm\\SearchEngine\\HookHandler',
    'indexBean',
);

$hook_array['after_restore'][] = array(
    1,
    'fts',
    null,
    '\\Sugarcrm\\Sugarcrm\\SearchEngine\\HookHandler',
    'indexBean',
);
