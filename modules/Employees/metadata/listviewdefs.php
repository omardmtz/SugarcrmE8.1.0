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

$listViewDefs['Employees'] = array(
    'NAME' => array(
        'width' => '20', 
        'label' => 'LBL_LIST_NAME', 
        'link' => true,
        'related_fields' => array('last_name', 'first_name'),
        'orderBy' => 'last_name',
        'default' => true),
    'DEPARTMENT' => array(
        'width' => '10', 
        'label' => 'LBL_DEPARTMENT', 
        'link' => true,
        'default' => true),
    'TITLE' => array(
        'width' => '15', 
        'label' => 'LBL_TITLE', 
        'link' => true,
        'default' => true), 
    'REPORTS_TO_NAME' => array(
        'width' => '15', 
        'label' => 'LBL_LIST_REPORTS_TO_NAME', 
        'link' => true,
        'sortable' => false,
        'default' => true),
    'EMAIL' => array(
        'width' => '15', 
        'label' => 'LBL_LIST_EMAIL', 
        'link' => true,
        'customCode' => '{$EMAIL_LINK}{$EMAIL}</a>',
        'default' => true,
        'sortable' => false),
    'PHONE_WORK' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_PHONE', 
        'link' => true,
        'default' => true),
    'EMPLOYEE_STATUS' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_EMPLOYEE_STATUS', 
        'link' => false,
        'default' => true),    
	'DATE_ENTERED' => array (
	    'width' => '10',
	    'label' => 'LBL_DATE_ENTERED',
	    'default' => true),
);
?>
