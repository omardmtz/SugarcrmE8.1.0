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
$module_name = 'EmailMarketing';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#Campaigns/create',
        'label' =>'LNK_NEW_CAMPAIGN',
        'acl_action'=>'create',
        'acl_module'=>'Campaigns',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#Campaigns/',
        'label' =>'LNK_NEW_CAMPAIGN',
        'acl_action'=>'list',
        'acl_module'=>'Campaigns',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#ProspectLists/create',
        'label' =>'LNK_NEW_PROSPECT_LIST',
        'acl_action'=>'create',
        'acl_module'=>'ProspectLists',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#ProspectLists/',
        'label' =>'LNK_NEW_PROSPECT_LIST',
        'acl_action'=>'list',
        'acl_module'=>'ProspectLists',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#Prospects/create',
        'label' =>'LNK_NEW_PROSPECT',
        'acl_action'=>'create',
        'acl_module'=>'Prospects',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#Prospects/',
        'label' =>'LNK_NEW_PROSPECT',
        'acl_action'=>'list',
        'acl_module'=>'Prospects',
        'icon' => 'fa-bars',
    ),
);
