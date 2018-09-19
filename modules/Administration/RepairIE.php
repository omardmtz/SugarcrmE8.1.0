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
/*********************************************************************************
 * $Id: Delete.php,v 1.22 2006/01/17 22:50:52 majed Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/


	
$db = DBManagerFactory::getInstance();

$badAccts = array();

$q = "SELECT id, name, email_password FROM inbound_email WHERE deleted=0 AND status='Active'";
$r = $db->query($q);

while($a = $db->fetchByAssoc($r)) {
	$ieX = BeanFactory::getBean('InboundEmail', $a['id'], array('disable_row_level_security' => true));
	if(!$ieX->repairAccount()) {
		// none of the iterations worked.  flag for display
		$badAccts[$a['id']] = $a['name'];
	}
}

if(empty($badAccts)) {
	echo $mod_strings['LBL_REPAIR_IE_SUCCESS'];
} else {
	echo "<div class='error'>{$mod_strings['LBL_REPAIR_IE_FAILURE']}</div><br />";
	foreach($badAccts as $id => $acctName) {
		echo "<a href='index.php?module=InboundEmail&action=EditView&record={$id}' target='_blank'>{$acctName}</a><br />";
	}
}

?>
