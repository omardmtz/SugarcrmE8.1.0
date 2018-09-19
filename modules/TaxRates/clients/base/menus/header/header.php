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
$module_name = 'TaxRates';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#Shippers',
        'label' =>'LNK_NEW_SHIPPER',
        'acl_action'=>'admin',
        'acl_module'=>'Products',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#TaxRates/create',
        'label' =>'LNK_NEW_TAXRATE',
        'acl_action'=>'admin',
        'acl_module'=>'Products',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=Import&action=Step1&import_module=TaxRates&return_module=TaxRates&return_action=index',
        'label' =>'LNK_IMPORT_TAXRATES',
        'acl_action'=>'admin',
        'acl_module'=>'Products',
        'icon' => 'fa-arrow-circle-o-up',

    )
);
