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

 * Description:
 ********************************************************************************/

global $theme;









global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;


$seed_object = BeanFactory::newBean('WorkFlow');

if(!empty($_REQUEST['workflow_id']) && $_REQUEST['workflow_id']!="") {
    $seed_object->retrieve($_REQUEST['workflow_id']);
} else {
	sugar_die("You shouldn't be here");	
}	



////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
if (!isset($_REQUEST['html'])) {
	$form =new XTemplate ('modules/WorkFlowActionShells/Selector.html');
	$GLOBALS['log']->debug("using file modules/WorkFlowActionShells/Selector.html");
}
else {
	$GLOBALS['log']->debug("_REQUEST['html'] is ".$_REQUEST['html']);
	$form =new XTemplate ('modules/WorkFlowActionShells/'.$_REQUEST['html'].'.html');
	$GLOBALS['log']->debug("using file modules/WorkFlowActionShells/".$_REQUEST['html'].'.html');
}

$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);

$focus = BeanFactory::newBean('WorkFlowActionShells');  


if(isset($_REQUEST['action_type']) && ($_REQUEST['action_type'])=='action_update_rel') {
	
	$temp_module = BeanFactory::newBean($seed_object->base_module);
	$temp_module->call_vardef_handler("rel_filter");
	$temp_module->vardef_handler->start_none=true;
	$temp_module->vardef_handler->start_none_lbl = "Please Select";

	$form->assign("SELECTOR_JSCRIPT_RETURN", "'href_action_update_rel', 'rel_module'");
	$form->assign("SELECTOR_TAG", $mod_strings['LBL_ACTION_UPDATE_REL']);
	$form->assign("SELECTOR_DROPDOWN", get_select_options_with_id($temp_module->vardef_handler->get_vardef_array(true),$_REQUEST['rel_module']));
//end if action type is set  
}
if(isset($_REQUEST['action_type']) && ($_REQUEST['action_type'])=='action_new') {
	
	$form->assign("SELECTOR_JSCRIPT_RETURN", "'href_action_new', 'action_module'");
	$form->assign("SELECTOR_TAG", $mod_strings['LBL_ACTION_NEW']);
	
	
	
	$form->assign("SELECTOR_DROPDOWN", get_select_options_with_id($seed_object->get_module_array(true, true),$_REQUEST['rel_module']));
}

$form->assign("MODULE_NAME", $currentModule);

$form->assign("GRIDLINE", $gridline);

insert_popup_header($theme);

$form->parse("embeded");
$form->out("embeded");


$form->parse("main");
$form->out("main");

?>

<?php insert_popup_footer(); ?>
