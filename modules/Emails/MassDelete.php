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

if(!empty($_REQUEST['grabbed'])) {
	
	$focus = BeanFactory::newBean('Emails');
	
	$emailIds = array();
	// CHECKED ONLY:
	$grabEx = explode('::',$_REQUEST['grabbed']);
	
	foreach($grabEx as $k => $emailId) {
		if($emailId != "undefined") {
			$focus->mark_deleted($emailId);
		}
	}
	
	header('Location: index.php?module=Emails&action=ListViewGroup');
} else {
	global $mod_strings;
	// error
	$error = $mod_strings['LBL_MASS_DELETE_ERROR'];
	header('Location: index.php?module=Emails&action=ListViewGroup&error='.$error);
}

?>
