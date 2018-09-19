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

$viewdefs['base']['view']['profileactions'] = array(
    array(
        'route' => '#profile',
        'label' => 'LBL_PROFILE',
        'css_class' => 'profileactions-profile',
        'acl_action' => 'view',
        'icon' => 'fa-user',
    ),
    array(
        'route'=> '#bwc/index.php?module=Employees&action=index&query=true',
        'label' => 'LBL_EMPLOYEES',
        'css_class' => 'profileactions-employees',
        'acl_action' => 'list',
        'icon' => 'fa-users',
    ),
    array(
        'route' => '#bwc/index.php?module=Administration&action=index',
        'label' => 'LBL_MODULE_NAME',
        'css_class' => 'administration',
        'module' => 'Administration',
        'acl_action' => 'admin',
        'icon' => 'fa-cogs',
    ),
    array(
        'route' => '#about',
        'label' => 'LNK_ABOUT',
        'css_class' => 'profileactions-about',
        'acl_action' => 'view',
        'icon' => 'fa-info-circle',
    ),
    array(
        'route' => '#logout/?clear=1',
        'label' => 'LBL_LOGOUT',
        'css_class' => 'profileactions-logout',
        'icon' => 'fa-sign-out',
    ),
);
