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

$moduleName = 'Calls';
$viewdefs[$moduleName]['base']['menu']['header'] = array(
    array(
        'route' => "#{$moduleName}/create",
        'label' => 'LNK_NEW_CALL',
        'acl_action' => 'create',
        'acl_module' => $moduleName,
        'icon' => 'fa-plus',
    ),
    array(
        'route' => "#{$moduleName}",
        'label' => 'LNK_CALL_LIST',
        'acl_action' => 'list',
        'acl_module' => $moduleName,
        'icon' => 'fa-bars',
    ),
    array(
        'route' => '#bwc/index.php?' . http_build_query(
            array(
                'module' => 'Import',
                'action' => 'Step1',
                'import_module' => $moduleName,
                'query' => 'true',
                'report_module' => $moduleName,
            )
        ),
        'label' => 'LNK_IMPORT_CALLS',
        'acl_action' => 'import',
        'acl_module' => $moduleName,
        'icon' => 'fa-arrow-circle-o-up',
    ),
    array(
        'route' => '#Reports?filterModule=' . $moduleName,
        'label' => 'LBL_ACTIVITIES_REPORTS',
        'acl_action' => 'list',
        'acl_module' => 'Reports',
        'icon' => 'fa-bar-chart-o',
    ),
);
