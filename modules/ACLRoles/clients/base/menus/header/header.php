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

$moduleName = 'ACLRoles';
$viewdefs[$moduleName]['base']['menu']['header'] = array(
    array(
        'route' => "#bwc/index.php?module=$moduleName&action=EditView",
        'label' => 'LBL_CREATE_ROLE',
        'acl_module' => $moduleName,
        'acl_action' => 'edit',
        'icon' => 'fa-plus',
    ),
    array(
        'route' => "#bwc/index.php?module=$moduleName&action=index",
        'label' => 'LIST_ROLES',
        'acl_module' => $moduleName,
        'acl_action' => 'list',
        'icon' => 'fa-bars',
    ),
    array(
        'route' => "#bwc/index.php?module=$moduleName&action=ListUsers",
        'label' => 'LIST_ROLES_BY_USER',
        'acl_module' => $moduleName,
        'acl_action' => 'list',
        'icon' => 'fa-bars',
    ),
);
