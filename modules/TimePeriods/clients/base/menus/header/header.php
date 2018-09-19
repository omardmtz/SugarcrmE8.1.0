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
$module_name = 'TimePeriods';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#bwc/index.php?module=TimePeriods&action=EditView&return_module=TimePeriods&return_action=DetailView',
        'label' =>'LNK_NEW_TIMEPERIOD',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => '',
    ),
    array(
        'route'=>'#bwc/index.php?module=TimePeriods&action=ListView&return_module=TimePeriods&return_action=DetailView',
        'label' =>'LNK_TIMEPERIOD_LIST',
        'acl_action'=>'',
        'acl_module'=>'',
        'icon' => '',
    ),
);