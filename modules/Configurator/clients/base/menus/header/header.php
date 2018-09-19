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
$module_name = 'Configurator';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#bwc/index.php?module=Configurator&action=EditView',
        'label' =>'LBL_CONFIGURE_SETTINGS_TITLE',
        'acl_action'=>'',
        'acl_module'=>$module_name,
        'icon' => '',
    ),
    array(
        'route'=>'#bwc/index.php?module=Configurator&action=LogView',
        'label' =>'LBL_LOGVIEW',
        'acl_action'=>'',
        'acl_module'=>$module_name,
        'icon' => '',
    ),
);