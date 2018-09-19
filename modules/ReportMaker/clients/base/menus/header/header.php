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
$module_name = 'ReportMaker';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#bwc/index.php?module=ReportMaker&action=EditView&return_module=ReportMaker&return_action=DetailView',
        'label' =>'LNK_NEW_REPORTMAKER',
        'acl_action' => 'admin',
        'acl_module' => 'ReportMaker',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=ReportMaker&action=index&return_module=ReportMaker&return_action=index',
        'label' =>'LNK_LIST_REPORTMAKER',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=>'#bwc/index.php?module=CustomQueries&action=EditView&return_module=CustomQueries&return_action=DetailView',
        'label' =>'LNK_NEW_CUSTOMQUERY',
        'acl_action'=>'admin',
        'acl_module'=>'CustomQueries',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=CustomQueries&action=index&return_module=CustomQueries&return_action=DetailView',
        'label' =>'LNK_CUSTOMQUERIES',
        'acl_action'=>'admin',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=>'#bwc/index.php?module=DataSets&action=EditView&return_module=DataSets&return_action=DetailView',
        'label' =>'LNK_NEW_DATASET',
        'acl_action'=>'admin',
        'acl_module'=>'DataSets',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=DataSets&action=index&return_module=DataSets&return_action=index',
        'label' =>'LNK_LIST_DATASET',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
    array(
        'route'=> '#Reports',
        'label' =>'LBL_ALL_REPORTS',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-bar-chart-o',
    ),
);
