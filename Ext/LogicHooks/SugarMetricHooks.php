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

$hook_array['after_entry_point'][] = array(1, 'smm', 'include/SugarMetric/HookManager.php', 'SugarMetric_HookManager', 'afterEntryPoint');
$hook_array['server_round_trip'][] = array(1, 'smm', 'include/SugarMetric/HookManager.php', 'SugarMetric_HookManager', 'serverRoundTrip');
