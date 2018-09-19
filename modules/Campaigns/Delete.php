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

if(!isset($_REQUEST['record']))
{
	sugar_die("A record number must be specified to delete the campaign.");
}

$focus = BeanFactory::getBean('Campaigns', $_REQUEST['record']);

if (isset($_REQUEST['mode']) and $_REQUEST['mode']=='Test') {
	//deletes all data associated with the test run.
    $deleteTest = new DeleteTestCampaigns();
    $deleteTest->deleteTestRecords($focus);
} else {
	if(!$focus->ACLAccess('Delete')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
	$focus->mark_deleted($_REQUEST['record']);
}

$return_id=!empty($_REQUEST['return_id'])?$_REQUEST['return_id']:$focus->id;
require_once ('include/formbase.php');
handleRedirect($return_id, $_REQUEST['return_module']);