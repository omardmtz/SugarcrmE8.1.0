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
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $current_user;
$workflow_modules = get_workflow_admin_modules_for_user($current_user);
if (!is_admin($current_user) && empty($workflow_modules))
{
   sugar_die("Unauthorized access to WorkFlow.");
}

$seedEmailTemplate = BeanFactory::newBean('EmailTemplates');
$workflow_object = BeanFactory::newBean('WorkFlow');
global $app_strings;
global $app_list_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'EmailTemplates');
$header_text = '';
global $list_max_entries_per_page;
global $urlPrefix;
if(empty($_POST['mass']) && empty($where) && empty($_REQUEST['query'])){$_REQUEST['query']='true'; $_REQUEST['current_user_only']='checked'; };

global $currentModule;

// focus_list is the means of passing data to a ListView.
global $focus_list;

echo getClassicModuleTitle($mod_strings['LBL_MODULE_ID'], array($mod_strings['LBL_ALERT_TEMPLATES']), true); 

$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
	$storeQuery->loadQuery($currentModule);
	$storeQuery->populateRequest();
}else{
	$storeQuery->saveFromGet($currentModule);	
}

$where = " base_module IS NOT NULL";


echo "<p><p>";

//echo get_form_header($mod_strings['LBL_ALERT_TEMPLATES']. $header_text, "", false);



/////////////Display Alert Template Stuff//

	$template_form=new XTemplate ('modules/WorkFlow/TemplateForm.html');
	$template_form->assign("MOD", $current_module_strings);
	$template_form->assign("APP", $app_strings);
	$template_form->assign("BASE_MODULE", get_select_options_with_id($workflow_object->get_module_array(),""));
	$template_form->parse("main");
	$template_form->out("main");


global $title;
$display_title = $mod_strings['LBL_ALERT_TEMPLATES'];
if ($title) $display_title = $title;

$ListView = new ListView();

$ListView->initNewXTemplate( 'modules/WorkFlow/WorkFlowListView.html',$current_module_strings);
$ListView->setHeaderTitle($display_title . $header_text);
$ListView->show_export_button = false;
$ListView->show_delete_button = false;
$ListView->show_select_menu = false;
global $image_path;
$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=WorkFlowListView");
$ListView->xTemplateAssign("DELETE_INLINE_PNG", SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));
$workflow_strings = return_module_language($current_language, 'WorkFlow');
$ListView->xTemplateAssign("NTC_REMOVE_ALERT", $workflow_strings['NTC_REMOVE_ALERT']);
$ListView->setQuery($where, "", "email_templates.date_entered DESC", "EMAIL_TEMPLATE", true);
$ListView->processListView($seedEmailTemplate, "main", "EMAIL_TEMPLATE");
?>
