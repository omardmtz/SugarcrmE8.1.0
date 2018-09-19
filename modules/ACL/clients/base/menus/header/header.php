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
$module_name = 'ACLRoles';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#'.$module_name,
        'label' =>'LIST_ROLES',
        'acl_module'=>$module_name,
        'acl_action'=>'list',
        'icon' => '',
    ),
    array(
        'route'=>'#bwc/index.php?module=ACLRoles&action=ListUsers',
        'label' =>'LIST_ROLES_BY_USER',
        'acl_module'=>$module_name,
        'acl_action'=>'list',
        'icon' => 'fa-bars',
    ),
);
