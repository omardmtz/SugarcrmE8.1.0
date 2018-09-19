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



global $app_strings;
global $app_list_strings;
global $curent_language;


$mod_strings= return_module_language($current_language, $currentModule);

$focus = new UserSignature();

if(isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
$GLOBALS['log']->info('EmailTemplate detail view');

///////////////////////////////////////////////////////////////////////////////
////	OUTPUT 
echo insert_popup_header();
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_SIGNATURE'].' '.$focus->name), true); 


$xtpl = new XTemplate ('modules/Users/UserSignatureEditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
	
$xtpl->assign('CANCEL_SCRIPT', 'window.close()');

if(isset($_REQUEST['return_module'])) $xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign('RETURN_ACTION', $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
// handle Create $module then Cancel
if(empty($_REQUEST['return_id'])) {
	$xtpl->assign('RETURN_ACTION', 'index');
}
$xtpl->assign('INPOPUPWINDOW','true');
$xtpl->assign('JAVASCRIPT', get_set_focus_js());
$xtpl->assign('ID', $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('SIGNATURE_TEXT', !empty($focus->signature_html) ? $focus->signature_html : $focus->signature);

if(isset($_REQUEST['the_user_id']))
	$xtpl->assign('THE_USER_ID', $_REQUEST['the_user_id']);

$tiny = new SugarTinyMCE();
$xtpl->assign("tinyjs", $tiny->getInstance('sigText'));

$xtpl->parse('main.textarea');

//Add Custom Fields
$xtpl->parse('main');
$xtpl->out('main');
