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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;

$module_name = 'Employees';
$idpConfig  = new Config(\SugarConfig::getInstance());
$isIDMModeEnabled  = $idpConfig->isIDMModeEnabled();
if ($isIDMModeEnabled) {
    $newEmployeeLink = [
        'route' => $idpConfig->buildCloudConsoleUrl('userCreate'),
        'openwindow' => true,
        'label' =>'LNK_NEW_EMPLOYEE',
        'acl_action'=>'admin',
        'acl_module'=>$module_name,
        'icon' => 'fa-plus',
    ];
} else {
    $newEmployeeLink = [
        'route' => '#bwc/index.php?' . http_build_query([
                'module' => $module_name,
                'action' => 'EditView',
            ]),
        'label' =>'LNK_NEW_EMPLOYEE',
        'acl_action'=>'admin',
        'acl_module'=>$module_name,
        'icon' => 'fa-plus',
    ];
}
$viewdefs[$module_name]['base']['menu']['header'] = array(
    $newEmployeeLink,
    array(
        'route'=>'#'.$module_name,
        'label' =>'LNK_EMPLOYEE_LIST',
        'acl_action'=>'list',
        'acl_module'=>$module_name,
        'icon' => 'fa-bars',
    ),
);
