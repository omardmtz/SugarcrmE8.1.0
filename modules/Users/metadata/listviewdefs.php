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

 // $Id: listviewdefs.php 16292 2006-08-22 20:57:23 +0000 (Tue, 22 Aug 2006) awu $

$listViewDefs['Users'] = array(
    'NAME' => array(
        'width' => '30', 
        'label' => 'LBL_LIST_NAME', 
        'link' => true,
        'related_fields' => array('last_name', 'first_name'),
        'orderBy' => 'last_name',
        'default' => true),
    'USER_NAME' => array(
        'width' => '5', 
        'label' => 'LBL_USER_NAME', 
        'link' => true,
        'default' => true),
    'TITLE' => array(
        'width' => '15', 
        'label' => 'LBL_TITLE', 
        'link' => true,
        'default' => true),        
    'DEPARTMENT' => array(
        'width' => '15', 
        'label' => 'LBL_DEPARTMENT', 
        'link' => true,
        'default' => true),
    'EMAIL' => array(
        'width' => '30',
        'sortable' => false, 
        'label' => 'LBL_LIST_EMAIL', 
        'link' => true,
        'default' => true),
    'PHONE_WORK' => array(
        'width' => '25', 
        'label' => 'LBL_LIST_PHONE', 
        'link' => true,
        'default' => true),
    'STATUS' => array(
        'width' => '10', 
        'label' => 'LBL_STATUS', 
        'link' => false,
        'default' => true),
    'IS_ADMIN' => array(
        'width' => '10', 
        'label' => 'LBL_ADMIN', 
        'link' => false,
        'default' => true),
    'IS_GROUP' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_GROUP', 
        'link' => true,
        'default' => false),
);
?>
