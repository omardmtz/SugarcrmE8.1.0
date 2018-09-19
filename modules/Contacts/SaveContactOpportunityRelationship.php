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





$focus = new ContactOpportunityRelationship();

$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $field)
{
	safe_map($field, $focus, true);
}

foreach($focus->additional_column_fields as $field)
{
	safe_map($field, $focus, true);
}

// send them to the edit screen.
if(isset($_REQUEST['record']) && $_REQUEST['record'] != "")
{
    $recordID = $_REQUEST['record'];
}

$focus->save();
$recordID = $focus->id;

$GLOBALS['log']->debug("Saved record with id of ".$recordID);

$header_URL = "Location: index.php?action={$_REQUEST['return_action']}&module={$_REQUEST['return_module']}&record={$_REQUEST['return_id']}";
$GLOBALS['log']->debug("about to post header URL of: $header_URL");

header($header_URL);
?>