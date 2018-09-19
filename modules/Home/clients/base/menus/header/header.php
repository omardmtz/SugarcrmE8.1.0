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
$module_name = 'Home';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route' => '#' . $module_name . '/create',
        'label' => 'LBL_CREATE_DASHBOARD_MENU',
        'acl_action' => 'edit',
        'acl_module' => $module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route' => '#activities',
        'label' => 'LBL_ACTIVITIES',
        'icon' => 'fa-clock-o',
    ),
    array(
        'type' => 'divider',
    ),
    array(
        'route' => '#Dashboards?moduleName=Home',
        'label' => 'LBL_MANAGE_DASHBOARDS',
        'acl_action' => 'read',
        'acl_module' => 'Dashboards',
        'icon' => 'fa-bars',
        'label_module' => 'Dashboards',
    ),
);
