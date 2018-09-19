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


require_once('include/formbase.php');

$focus = BeanFactory::newBean('Holidays');
global $current_user;

$focus->disable_row_level_security = true;	
$focus->retrieve($_POST['record']);

$focus = populateFromPost('', $focus);

if ($focus->id != $_REQUEST['relate_id']) {
    if ($_REQUEST['return_module'] != 'Project') {
        $focus->person_id = $_REQUEST['relate_id'];
        $focus->person_type = "Users";
    } elseif ($_REQUEST['return_module'] == 'Project') {
        $focus->related_module = 'Project';
        $focus->related_module_id = $_REQUEST['relate_id'];
    }
}

if (!$focus->id && !empty($_REQUEST['duplicateId'])) {
    $original_focus = BeanFactory::newBean('Holidays');
    $original_focus->retrieve($_REQUEST['duplicateId']);

    $focus->person_id = $original_focus->person_id;
    $focus->person_type = $original_focus->person_type;
    $focus->related_module = $original_focus->related_module;
    $focus->related_module_id = $original_focus->related_module_id;
}

$check_notify = FALSE;

$focus->save($check_notify);
$return_id = $focus->id;

if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Holidays";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

handleRedirect($return_id,'Holidays');
?>
