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

$viewdefs['WorkFlow']['base']['menu']['header'] = array(
    array(
        'route'=>'#bwc/index.php?module=WorkFlow&action=EditView&return_module=WorkFlow&return_action=DetailView',
        'label' =>'LNK_NEW_WORKFLOW',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=WorkFlow&action=index&return_module=WorkFlow&return_action=DetailView',
        'label' =>'LNK_WORKFLOW',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=WorkFlow&action=WorkFlowListView&return_module=WorkFlow&return_action=index',
        'label' =>'LNK_ALERT_TEMPLATES',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-magic',
    ),
    array(
        'route'=>'#bwc/index.php?module=WorkFlow&action=ProcessListView&return_module=WorkFlow&return_action=index',
        'label' =>'LNK_PROCESS_VIEW',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => 'fa-sitemap',
    ),
);
