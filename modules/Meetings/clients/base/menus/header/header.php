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
$module_name = 'Meetings';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route' => "#{$module_name}/create",
        'label' => 'LNK_NEW_MEETING',
        'acl_action' => 'create',
        'acl_module' => $module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route' => "#{$module_name}",
        'label' => 'LNK_MEETING_LIST',
        'acl_action' => 'list',
        'acl_module' => $module_name,
        'icon' => 'fa-bars',
    ),
    array(
        'route' => "#bwc/index.php?module=Import&action=Step1&import_module={$module_name}&return_module={$module_name}&return_action=index",
        'label' => 'LNK_IMPORT_MEETINGS',
        'acl_action' => 'import',
        'acl_module' => $module_name,
        'icon' => 'fa-arrow-circle-o-up',
    ),
);
