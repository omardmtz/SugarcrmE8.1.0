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
 $listViewDefs['module_loader']['packages'] = array(
    'name' => array(
        'width' => '5', 
        'label' => 'LBL_LIST_NAME', 
        'link' => false,
        'default' => true,
        'show' => true), 
    'description' => array(
        'width' => '32', 
        'label' => 'LBL_ML_DESCRIPTION', 
        'default' => true,
        'link' => false,
        'show' => true),
);

$listViewDefs['module_loader']['releases'] = array(
    'description' => array(
        'width' => '32', 
        'label' => 'LBL_LIST_SUBJECT', 
        'default' => true,
        'link' => false),
     'version' => array(
        'width' => '32', 
        'label' => 'LBL_LIST_SUBJECT', 
        'default' => true,
        'link' => false),
);
?>
