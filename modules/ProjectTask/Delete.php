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

// $Id: Delete.php 45763 2009-04-01 19:16:18Z majed $





$sugarbean = BeanFactory::newBean('ProjectTask');

// perform the delete if given a record to delete

if(empty($_REQUEST['record']))
{
	$GLOBALS['log']->info('delete called without a record id specified');
}
else
{
	$record = $_REQUEST['record'];
	$sugarbean->retrieve($record);
	if(!$sugarbean->ACLAccess('Delete')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
	$GLOBALS['log']->info("deleting record: $record");
	$sugarbean->mark_deleted($record);
}

// handle the return location variables

$return_module = empty($_REQUEST['return_module']) ? 'ProjectTask'
	: $_REQUEST['return_module'];

$return_action = empty($_REQUEST['return_action']) ? 'index'
	: $_REQUEST['return_action'];

$return_id = empty($_REQUEST['return_id']) ? ''
	: $_REQUEST['return_id'];

$return_location = "index.php?module=$return_module&action=$return_action";

// append the return_id if given

if(!empty($return_id))
{
	$return_location .= "&record=$return_id";
}

// now that the delete has been performed, return to given location

header("Location: $return_location");
?>
