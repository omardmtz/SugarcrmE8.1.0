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
$module_name = 'Emails';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route' => '#' . $module_name . '/compose',
        'label' => 'LBL_COMPOSE_MODULE_NAME_SINGULAR',
        'acl_action' => 'create',
        'acl_module' => $module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route' => '#' . $module_name . '/create',
        'label' => 'LBL_CREATE_ARCHIVED_EMAIL',
        'acl_action' => 'create',
        'acl_module' => $module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route' => '#'.$module_name,
        'label' => 'LNK_EMAIL_LIST',
        'acl_action' => 'list',
        'acl_module' => $module_name,
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView',
        'label' =>'LNK_NEW_EMAIL_TEMPLATE',
        'acl_action'=>'create',
        'acl_module'=>'EmailTemplates',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=EmailTemplates&action=index',
        'label' =>'LNK_EMAIL_TEMPLATE_LIST',
        'acl_action'=>'list',
        'acl_module'=>'EmailTemplates',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#UserSignatures/create',
        'label' =>'LNK_NEW_EMAIL_SIGNATURE',
        'acl_action'=>'create',
        'acl_module' => 'Emails',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#UserSignatures',
        'label' =>'LNK_EMAIL_SIGNATURE_LIST',
        'acl_action' => 'create',
        'acl_module' => 'Emails',
        'icon' => 'fa-bars',
    ),
    array(
        'route' => '#OutboundEmail',
        'label' => 'LNK_EMAIL_SETTINGS_LIST',
        'acl_action' => 'list',
        'acl_module' => 'OutboundEmail',
        'icon' => 'fa-cog',
    ),
);
