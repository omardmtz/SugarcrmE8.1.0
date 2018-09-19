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

 // $Id: listviewdefs.php 54005 2010-01-25 20:32:59Z jmertic $

$listViewDefs['ProspectLists'] = array(
    'NAME' => array(
        'width' => '25', 
        'label' => 'LBL_LIST_PROSPECT_LIST_NAME', 
        'link' => true,
        'default' => true),
    'LIST_TYPE' => array(
        'width' => '15', 
        'label' => 'LBL_LIST_TYPE_LIST_NAME', 
        'default' => true),
    'DESCRIPTION' => array(
        'width' => '40', 
        'label' => 'LBL_LIST_DESCRIPTION', 
        'default' => true),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID', 
        'default' => true),
  	'DATE_ENTERED' => array (
	    'type' => 'datetime',
	    'label' => 'LBL_DATE_ENTERED',
	    'width' => '10',
	    'default' => true),
);
