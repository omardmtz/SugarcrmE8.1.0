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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

global $theme;








global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;


$seed_object = BeanFactory::newBean('WorkFlow');
$focus = BeanFactory::newBean('WorkFlowAlerts');

$base_module = InputValidation::getService()->getValidInputRequest('base_module', 'Assert\Mvc\ModuleName');
if (!empty($base_module)) {
    $seed_object->base_module = $base_module;
} else {
	sugar_die("You shouldn't be here");	
}	



////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
	$form =new XTemplate ('modules/WorkFlowAlerts/Selector.html');
	$GLOBALS['log']->debug("using file modules/WorkFlowAlerts/Selector.html");


$form->assign("MOD", $mod_strings);
$form->assign("APP", $app_strings);
$form->assign("BASE_MODULE", $_REQUEST['base_module']);
$form->assign("USER_TYPE", $_REQUEST['user_type']);

if($_REQUEST['user_type']=='rel_user' || $_REQUEST['user_type']=='rel_user_custom' || $_REQUEST['user_type']=='assigned_team_relate' ) {

	$temp_module = BeanFactory::newBean($seed_object->base_module);
	$temp_module->call_vardef_handler("alert_rel_filter");
	$temp_module->vardef_handler->start_none=true;
	$temp_module->vardef_handler->start_none_lbl =  $mod_strings['LBL_PLEASE_SELECT'];

	$form->assign("SELECTOR_JSCRIPT_RETURN", "'href_".$_REQUEST['user_type']."'");
	$form->assign("SELECTOR_TAG", $mod_strings['LBL_ALERT_REL1']);
	$form->assign("SELECTOR_DROPDOWN", get_select_options_with_id($temp_module->vardef_handler->get_vardef_array(true, '', true, true),$_REQUEST['rel_module1']));
	
	if(!empty($_REQUEST['rel_module1']) && $_REQUEST['rel_module1']!=''){
	$rel_module_name = $_REQUEST['rel_module1'];
	$rel_module = $focus->get_rel_module($seed_object->base_module, $rel_module_name);
		if(!empty($_REQUEST['rel_module2']) && $_REQUEST['rel_module2']!=""){
			$rel_module2 = $_REQUEST['rel_module2'];
		} else {
			$rel_module2 = "";	
		}	
		
		$temp_module2 = BeanFactory::newBean($rel_module);
		$temp_module2->call_vardef_handler("alert_rel_filter");
		$temp_module2->vardef_handler->start_none=true;
		$temp_module2->vardef_handler->start_none_lbl = $mod_strings['LBL_PLEASE_SELECT'];
		
		$rel_select2 = get_select_options_with_id($temp_module2->vardef_handler->get_vardef_array(true, '', true, true),$rel_module2);	
	
		$form->assign("SELECTOR_TAG2", $mod_strings['LBL_ALERT_REL2']);
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
