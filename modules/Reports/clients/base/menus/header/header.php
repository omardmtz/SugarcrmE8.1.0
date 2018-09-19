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
$module_name = 'Reports';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route' => '#bwc/index.php?module=Reports&report_module=&action=index&page=report&Create+Custom+Report=Create+Custom+Report',
        'label' =>'LBL_CREATE_REPORT',
        'acl_action'=>'create',
        'acl_module'=>$module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#' . $module_name,
        'label' =>'LBL_ALL_REPORTS',
        'acl_action'=>'list',
        'acl_module'=>$module_name,
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=ReportMaker&action=index&return_module=ReportMaker&return_action=index',
        'label' =>'LNK_ADVANCED_REPORTING',
        'acl_action'=>'edit',
        'acl_module'=>$module_name,
        'icon' => 'fa-bar-chart-o',
    ),
);
