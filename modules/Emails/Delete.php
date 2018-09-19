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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;

$focus = BeanFactory::newBean('Emails');

if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_RCD_NUM_TO_DEL']);
$focus->retrieve($_REQUEST['record']);
$email_type = $focus->type;
if(!$focus->ACLAccess('Delete')){
	ACLController::displayNoAccess(true);
	sugar_cleanup(true);
}
$focus->mark_deleted($_REQUEST['record']);

// make sure assigned_user_id is set - during testing this isn't always set
if (!isset($_REQUEST['assigned_user_id'])) {
	$_REQUEST['assigned_user_id'] = '';
}

if ($email_type == 'archived') {
	global $current_user;
    $loc = 'Location: index.php?module=Emails';
} else {
$loc = 'Location: index.php?module='.$_REQUEST['return_module'].'&action='.$_REQUEST['return_action'].'&record='.$_REQUEST['return_id'].'&type='.$_REQUEST['type'].'&assigned_user_id='.$_REQUEST['assigned_user_id'];
}

header($loc);
?>
