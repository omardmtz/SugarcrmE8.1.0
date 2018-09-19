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

$hook_array['before_relationship_add'][] = array(
    1,
    'AddManagerToTeam',
    'modules/Teams/TeamHooks.php',
    'TeamHooks',
    'addManagerToTeam',
);
$hook_array['after_relationship_delete'][] = array(
    2,
    'RemoveManagerFromTeam',
    'modules/Teams/TeamHooks.php',
    'TeamHooks',
    'removeManagerFromTeam',
);
