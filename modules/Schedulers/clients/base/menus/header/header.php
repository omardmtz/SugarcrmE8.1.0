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
$module_name = 'Schedulers';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#bwc/index.php?module=Schedulers&action=EditView',
        'label' =>'LNK_NEW_SCHEDULER',
        'acl_action'=>'admin',
        'acl_module'=>'',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=Schedulers&action=index',
        'label' =>'LNK_LIST_SCHEDULER',
        'acl_action'=>'admin',
        'acl_module'=>'',
        'icon' => 'fa-list',
    ),
);
