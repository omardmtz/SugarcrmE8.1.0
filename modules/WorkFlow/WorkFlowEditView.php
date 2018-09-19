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
 * $Header$
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

global $current_user;
$workflow_modules = get_workflow_admin_modules_for_user($current_user);
if (!is_admin($current_user) && empty($workflow_modules))
{
   sugar_die("Unauthorized access to WorkFlow.");
}

///////////Show related config option
//Set to true - show related dynamic fields
//Set to false - do not show related dynamic fields
$show_related = true;

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = BeanFactory::newBean('EmailTemplates');

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$old_id = '';

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	if (! empty($focus->filename) )
	{	
	 $old_id = $focus->id;
	}
	$focus->id = "";
}



//setting default flag value so due date and time not required
if (!isset($focus->id)) $focus->date_due_flag = 1;

//needed when creating a new case with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif (!isset($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

if (isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
        $focus->filename = $_REQUEST['filename'];
}

echo getClassicModuleTitle($mod_strings['LBL_MODULE_ID'], array($mod_strings['LBL_ALERT_TEMPLATES'],$focus->name), true); 

$GLOBALS['log']->info("EmailTemplate detail view");

$xtpl=new XTemplate ('modules/WorkFlow/WorkFlowEditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("MOD_EMAILS", return_module_language($GLOBALS['current_language'], 'EmailTemplates'));

$xtpl->assign("TYPEDROPDOWN", get_select_options_with_id($mod_strings['LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW'],'workflow'));

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());
$xtpl->assign("ID", $focus->id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name); else $xtpl->assign("NAME", "");
if (isset($focus->description)) $xtpl->assign("DESCRIPTION", $focus->description); else $xtpl->assign("DESCRIPTION", "");
if (isset($focus->subject)) $xtpl->assign("SUBJECT", $focus->subject); else $xtpl->assign("SUBJECT", "");
$admin = Administration::getSettings();
if ($focus->from_name != "") $xtpl->assign("FROM_NAME", $focus->from_name); else $xtpl->assign("FROM_NAME",$admin->settings['notify_fromname']);
if ($focus->from_address != "") $xtpl->assign("FROM_ADDRESS", $focus->from_address); else $xtpl->assign("FROM_ADDRESS",$admin->settings['notify_fromaddress']);
if ( $focus->published == 'on')
{
$xtpl->assign("PUBLISHED","CHECKED");
}

///////////////////////////////////////
////    ATTACHMENTS
$attachments = '';
if (!empty($focus->id)) {
    $etid = $focus->id;
} elseif(!empty($old_id)) {
    $xtpl->assign('OLD_ID', $old_id);
    $etid = $old_id;
}
if(!empty($etid)) {
    $note = BeanFactory::newBean('Notes');
    //FIXME: notes.email_type should be EmailTemplates
    //FIXME: notes.filename IS NOT NULL is probably not necessary
    $notes_list = $note->get_full_list("", "notes.email_id=" . $GLOBALS['db']->quoted($etid) . " AND notes.filename IS NOT NULL", true);
    if (!empty($notes_list)) {
        for ($i = 0; $i < count($notes_list); $i++) {
            $the_note = $notes_list[$i];
            if (empty($the_note->filename)) {
                continue;
            }
            $secureLink = 'index.php?entryPoint=download&id=' . $the_note->id . '&type=Notes';
            $attachments .= '<input type="checkbox" name="remove_attachment[]" value="' . $the_note->id . '"> ' . $app_strings['LNK_REMOVE'] . '&nbsp;&nbsp;';
            $attachments .= '<a href="' . $secureLink . '" target="_blank">' . $the_note->filename . '</a><br>';
        }
    }
}
$attJs  = '<script type="text/javascript">';
$attJs .= 'var lnk_remove = "'.$app_strings['LNK_REMOVE'].'";';
$attJs .= '</script>';
$xtpl->assign('ATTACHMENTS', $attachments);
$xtpl->assign('ATTACHMENTS_JAVASCRIPT', $attJs);
////    END ATTACHMENTS
///////////////////////////////////////

/////////////////////////Workflow Area/////////////////////////////




$base_module = InputValidation::getService()->getValidInputRequest('base_module', 'Assert\Mvc\ModuleName');
if (!empty($base_module)) {
    $focus->base_module = $base_module;
}
//
$xtpl->assign("BASE_MODULE", $focus->base_module);
$xtpl->assign("BASE_MODULE_TRANSLATED", $app_list_strings['moduleList'][$focus->base_module]);
$xtpl->assign("VALUE_TYPE", get_select_options_with_id($app_list_strings['wflow_array_type_dom'],""));

//////////////////////////End WorkFlow Area////////////////////////////

$xtpl->assign("LBL_CONTACT",$app_list_strings['moduleList']['Contacts']);
$xtpl->assign("LBL_ACCOUNT",$app_list_strings['moduleList']['Accounts']);

$xtpl->assign("OLD_ID", $old_id );

if (isset($focus->parent_type) && $focus->parent_type != "") {
	$change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' tabindex='3' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));

if(!empty($focus->body)){
	$xtpl->assign("ALT_CHECKED", 'checked');
}

if (isset($focus->body)) $xtpl->assign("BODY", $focus->body); else $xtpl->assign("BODY", "");
if (isset($focus->body_html)) $xtpl->assign("BODY_HTML", $focus->body_html); else $xtpl->assign("BODY_HTML", "");

//////////////////////////////////
$tiny = new SugarTinyMCE();
$tiny->defaultConfig['apply_source_formatting']=true;
$tiny->defaultConfig['cleanup_on_startup']=true;
$tiny->defaultConfig['relative_urls']=false;
$tiny->defaultConfig['convert_urls']=false;
$ed = $tiny->getInstance('body_text');
$xtpl->assign("tiny", $ed);
$edValue = $focus->body_html ;
$xtpl->assign("HTMLAREA",$edValue);
$xtpl->parse("main.htmlarea");
//////////////////////////////////

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

/*
Only allowed the related module dynamic fields 
if the config setting "show_related" in this file is set to true
*/

	if($show_related==true){
		$xtpl->parse("main.special");
		$xtpl->parse("main.special2");
	}
	
	if($show_related==false){
		$xtpl->parse("main.normal");
	}

$xtpl->parse("main");

$xtpl->out("main");

$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

?>
