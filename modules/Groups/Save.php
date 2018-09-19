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


//_ppd($_REQUEST);
$focus = BeanFactory::newBean('Groups');

// New user
	// track the current reports to id to be able to use it if it has changed
	$old_reports_to_id = $focus->reports_to_id;

// Update
if(isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
	$focus->retrieve($_REQUEST['record']);
}

foreach($focus->column_fields as $field) {
	if(isset($_POST[$field])) {
		$value = $_POST[$field];
		$focus->$field = $value;
	}
}

foreach($focus->additional_column_fields as $field) {
	if(isset($_POST[$field])) {
		$value = $_POST[$field];
		$focus->$field = $value;
	}
}
if(isset($_REQUEST['user_name']) && !empty($_REQUEST['user_name'])) {
	$focus->user_name	= $_REQUEST['user_name'];
	$focus->last_name	= $_REQUEST['user_name'];
}
$focus->description	= $_REQUEST['description'];
$focus->default_team = $_REQUEST['team_id'];
$focus->save();


if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Groups";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>