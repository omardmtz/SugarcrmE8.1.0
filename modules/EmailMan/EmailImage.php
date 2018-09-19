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

if(empty($_REQUEST['id']) || !preg_match("/^[\w\d\-]+$/", $_REQUEST['id'])) {
	die("Not a Valid Entry Point");
}
global $mod_strings;
$note = BeanFactory::newBean('Notes');
//check if file is an email image
if (!$note->retrieve_by_string_fields(array('id' => $_REQUEST['id'], 'email_type' => "Emails"))) {
	die($mod_strings['LBL_INVALID_ENTRY_POINT']);
}

$location = $GLOBALS['sugar_config']['upload_dir']."/" . $_REQUEST['id'];

$mime = getimagesize($location);

if(!empty($mime)) {
	header("Content-Type: {$mime['mime']}");
} else {
	header("Content-Type: image/png");
}


readfile($location);


