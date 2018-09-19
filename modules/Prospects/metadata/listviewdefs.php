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

 // $Id: listviewdefs.php 26522 2007-09-10 17:01:07Z clee $

$listViewDefs['Prospects'] = array(
	'FULL_NAME' => array(
		'width' => '20', 
		'label' => 'LBL_LIST_NAME', 
		'link' => true,
        'related_fields' => array('first_name', 'last_name'),
        'orderBy' => 'last_name',
        'default' => true),
    'TITLE' => array(
        'width' => '20', 
        'label' => 'LBL_LIST_TITLE', 
        'link' => false,
        'default' => true),   
    'EMAIL1' => array(
        'width' => '20', 
        'label' => 'LBL_LIST_EMAIL_ADDRESS',
        'sortable' => false, 
        'link' => false,
        'default' => true),           
    'PHONE_WORK' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_PHONE', 
        'link' => false,
        'default' => true), 
	'DATE_ENTERED' => array (
	    'type' => 'datetime',
	    'label' => 'LBL_DATE_ENTERED',
	    'width' => '10',
	    'default' => true,
	  ),  
);
?>
