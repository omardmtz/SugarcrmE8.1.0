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
$module_name = 'Styleguide';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#'.$module_name.'/docs/index',
        'label' =>'Core Elements',
        'acl_action'=>'list',
        'acl_module'=>'Accounts',
        'icon' => 'fa-book',
    ),
    array(
        'route'=>'#'.$module_name.'/fields/index',
        'label' =>'Example Sugar7 Fields',
        'acl_action'=>'list',
        'acl_module'=>'Accounts',
        'icon' => 'fa-list-alt',
    ),
    array(
        'route'=>'#'.$module_name.'/views/index',
        'label' =>'Example Sugar7 Views',
        'acl_action'=>'list',
        'acl_module'=>'Accounts',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#'.$module_name.'/layout/records',
        'label' =>'Default Module List Layout',
        'acl_action'=>'list',
        'acl_module'=>'Accounts',
        'icon' => 'fa-columns',
    ),
    array(
        'route'=>'#'.$module_name.'/create',
        'label' =>'Default Record Create Layout',
        'acl_action'=>'list',
        'acl_module'=>'Accounts',
        'icon' => 'fa-plus',
    ),
);
