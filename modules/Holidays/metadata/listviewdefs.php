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

 // $Id: listviewdefs.php 16705 2006-09-12 23:59:52 +0000 (Tue, 12 Sep 2006) jenny $

$listViewDefs['Holidays'] = array(
	'HOLIDAY_DATE' => array(
		'width' => '20',  
		'label' => 'LBL_HOLIDAY_DATE', 
		'link' => true,
        'default' => true),
	'DESCRIPTION' => array(
		'width' => '40', 
		'label' => 'LBL_DESCRIPTION',
		'sortable' => false,
        'default' => true)
);
?>
