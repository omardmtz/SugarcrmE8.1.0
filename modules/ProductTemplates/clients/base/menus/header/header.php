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
$module_name = 'ProductTemplates';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#'.$module_name.'/create',
        'label' =>'LNK_NEW_PRODUCT',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#' . $module_name,
        'label' =>'LNK_PRODUCT_LIST',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=>'#Manufacturers',
        'label' =>'LNK_NEW_MANUFACTURER',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=>'#ProductCategories',
        'label' =>'LNK_NEW_PRODUCT_CATEGORY',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=>'#ProductTypes',
        'label' =>'LNK_NEW_PRODUCT_TYPE',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=>'#bwc/index.php?module=Import&action=Step1&import_module=ProductTemplates&return_module=ProductTemplates&return_action=index',
        'label' =>'LNK_IMPORT_PRODUCT_CATALOG',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-arrow-circle-o-up',
    ),
);
