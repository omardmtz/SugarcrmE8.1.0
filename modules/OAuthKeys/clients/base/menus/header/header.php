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

$module_name = 'OAuthKeys';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route' => "#bwc/index.php?module=$module_name&action=EditView&return_module=$module_name&return_action=DetailView",
        'label' => 'LNK_NEW_RECORD',
        'acl_action' => 'create',
        'acl_module' => $module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route' => "#bwc/index.php?module=$module_name&action=index",
        'label' => 'LNK_LIST',
        'acl_action' => 'list',
        'acl_module' => $module_name,
        'icon' => 'fa-bars',
    ),
);
