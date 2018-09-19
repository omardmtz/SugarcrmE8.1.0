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
global $theme;

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('workflow_alerts');

if(!empty($_REQUEST['workflow_id'])) {
    $workflow_object = BeanFactory::retrieveBean('WorkFlow', $_REQUEST['workflow_id']);
}
if(empty($workflow_object)) {
	sugar_die("You shouldn't be here");
}



////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
	$form =new XTemplate ('modules/Expressions/RelateSelector.html');
	$log->debug("using file modules/Expressions/RelateSelector.html");

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);
$form->assign("BASE_MODULE", $workflow_object->base_module);
$form->assign("TYPE", $_REQUEST['type']);
$form->assign("WORKFLOW_ID", $workflow_object->id);
$form->assign("PLEASE_SEL", $mod_strings['LBL_PLEASE_SEL_TARGET']);
$form->assign("ASSOCIATED_WITH", $mod_strings['LBL_ASSOCIATED_WITH']);

if($_REQUEST['type']=='new_rel') {

	$temp_module = BeanFactory::newBean($workflow_object->base_module);
	$temp_module->call_vardef_handler("rel_filter");
	$temp_module->vardef_handler->start_none=true;
	$temp_module->vardef_handler->start_none_lbl = $mod_strings['LBL_PLEASE_SELECT'];


	$form->assign("SELECTOR_JSCRIPT_RETURN", "'href_".$_REQUEST['type']."','rel_module','action_module'");
	$form->assign("SELECTOR_TAG", $mod_strings['LBL_REL1']);
    $var_def_array2 = $temp_module->vardef_handler->get_vardef_array(true, true, true, true, true);
	$form->assign("SELECTOR_DROPDOWN", get_select_options_with_id($var_def_array2,$_REQUEST['rel_module1']));

	if(!empty($_REQUEST['rel_module1']) && $_REQUEST['rel_module1']!=''){
		$rel_module_name = $_REQUEST['rel_module1'];

		$rel_module = $workflow_object->get_rel_module($rel_module_name);



		//ensures that rel2 is selected
		$form->assign("SELECT_REL2", "Yes");


	if(!empty($_REQUEST['rel_module2']) && $_REQUEST['rel_module2']!=""){
			$rel_module2 = $_REQUEST['rel_module2'];
		} else {
			$rel_module2 = "";
		}

		$temp_module2 = BeanFactory::newBean($rel_module);
		$temp_module2->call_vardef_handler("rel_filter");
		$temp_module2->vardef_handler->start_none=true;
		$temp_module2->vardef_handler->start_none_lbl = $mod_strings['LBL_PLEASE_SELECT'];

        $var_def_array2 = $temp_module2->vardef_handler->get_vardef_array(true, '', true, true, true);
		$rel_select2 = get_select_options_with_id($var_def_array2,$rel_module2);

		$form->assign("SELECTOR_TAG2", $mod_strings['LBL_REL2']);
		$form->assign("SELECTOR_DROPDOWN2", $rel_select2);

	//end if we should show second related
	}


//end if action type is set
}


$form->assign("MODULE_NAME", $currentModule);
//$form->assign("FORM", $_REQUEST['form']);
$form->assign("GRIDLINE", $gridline);

insert_popup_header($theme);
$form->parse("embeded");
$form->out("embeded");


$form->parse("main");
$form->out("main");

?>

<?php insert_popup_footer(); ?>
