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


$focus = BeanFactory::newBean('WorkFlowAlerts');

if(!empty($_REQUEST['parent_id']) && $_REQUEST['parent_id']!="") {
	$focus->parent_id = $_REQUEST['parent_id'];
} else {
	sugar_die("You shouldn't be here");
}

$workflow_object = $focus->get_workflow_object();


if(!empty($_REQUEST['record']) && $_REQUEST['record']!="") {
   $focus->retrieve($_REQUEST['record']);
}


$target_workflow_object = $workflow_object->get_parent_object();



////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
	$form =new XTemplate ('modules/WorkFlowAlerts/CreateStep1.html');
	$GLOBALS['log']->debug("using file modules/WorkFlowAlerts/CreateStep1.html");

		//Bug 12335: We need to include the javascript language file first. And also the language file in WorkFlow is needed.
        if(!is_file(sugar_cached('jsLanguage/') . $GLOBALS['current_language'] . '.js')) {
            jsLanguage::createAppStringsCache($GLOBALS['current_language']);
        }
        $javascript_language_files = getVersionedScript("cache/jsLanguage/{$GLOBALS['current_language']}.js",  $GLOBALS['sugar_config']['js_lang_version']);
        if(!is_file(sugar_cached('jsLanguage/') . $this->module . '/' . $GLOBALS['current_language'] . '.js')) {
                jsLanguage::createModuleStringsCache($this->module, $GLOBALS['current_language']);
        }
        $javascript_language_files .= getVersionedScript("cache/jsLanguage/{$this->module}/{$GLOBALS['current_language']}.js", $GLOBALS['sugar_config']['js_lang_version']);
        if(!is_file(sugar_cached('jsLanguage/WorkFlow/') . $GLOBALS['current_language'] . '.js')) {
            jsLanguage::createModuleStringsCache('WorkFlow', $GLOBALS['current_language']);
        }
        $javascript_language_files .= getVersionedScript("cache/jsLanguage/WorkFlow/{$GLOBALS['current_language']}.js", $GLOBALS['sugar_config']['js_lang_version']);


        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return() {\n";
        $the_javascript .= "    window.opener.document.EditView.submit();";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";

	$form->assign("MOD", $mod_strings);
	$form->assign("APP", $app_strings);
	$form->assign("JAVASCRIPT_LANGUAGE_FILES", $javascript_language_files);
	$form->assign("MODULE_NAME", $currentModule);
	$form->assign("GRIDLINE", $gridline);
	$form->assign("SET_RETURN_JS", $the_javascript);

//	$form->assign("BASE_MODULE", $_REQUEST['base_module']);
	$form->assign("BASE_MODULE", $target_workflow_object->base_module);
	$form->assign("PARENT_ID", $focus->parent_id);
	$form->assign("ID", $focus->id);
	$form->assign("REL_MODULE1", $focus->rel_module1);
	$form->assign("REL_MODULE2", $focus->rel_module2);
	$form->assign("FIELD_VALUE", $focus->field_value);

////////Middle Items/////////////////////////////


/////////////////End Items 	//////////////////////

insert_popup_header($theme);

$form->parse("embeded");
$form->out("embeded");


//////////New way of processing page

	//Check to see if this workflow object is bridging, and if so, use its parent workflow object as the object


	$ProcessView = new ProcessView($target_workflow_object, $focus);
	$ProcessView->no_count = true;
	$ProcessView->write("AlertsCreateStep1");

	$form->assign("TOP_BLOCK", $ProcessView->top_block);
	$form->assign("BOTTOM_BLOCK", $ProcessView->bottom_block);





//close window and refresh parent if needed

if(!empty($_REQUEST['special_action']) && $_REQUEST['special_action'] == "refresh"){

	$special_javascript = "window.opener.document.DetailView.action.value = 'DetailView'; \n";
	$special_javascript .= "window.opener.document.DetailView.submit(); \n";
	$special_javascript .= "window.close();";
	$form->assign("SPECIAL_JAVASCRIPT", $special_javascript);

}

$form->parse("main");
$form->out("main");

?>

<?php insert_popup_footer(); ?>
