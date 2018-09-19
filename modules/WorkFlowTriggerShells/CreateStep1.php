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

////////////////////Maybe move to separate function////////////////////
include_once('modules/WorkFlowTriggerShells/MetaArray.php');
///////////////////////////////////////////////////////////////////////

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

global $urlPrefix;
global $currentModule;

//close window and refresh parent if needed
if(!empty($_REQUEST['special_action']) && $_REQUEST['special_action'] == "refresh"){
    echo "<script>
              window.opener.document.DetailView.action.value = 'DetailView';
              window.opener.document.DetailView.submit();
              window.close();
          </script>";
    return;
}

if(!empty($_REQUEST['workflow_id'])) {
    $workflow_object = BeanFactory::retrieveBean('WorkFlow', $_REQUEST['workflow_id']);
}
if(empty($workflow_object)) {
	sugar_die("You shouldn't be here");
}

$focus = BeanFactory::newBean('WorkFlowTriggerShells');

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
	$form =new XTemplate ('modules/WorkFlowTriggerShells/CreateStep1.html');
$js_include = getVersionedScript('cache/include/javascript/sugar_grp1.js')
    . getVersionedScript('include/workflow/jutils.js');
	$GLOBALS['log']->debug("using file modules/WorkFlowTriggerShells/CreateStep1.html");
//Bug 12335: We need to include the javascript language file first.
        if(!is_file(sugar_cached('jsLanguage/') . $GLOBALS['current_language'] . '.js')) {
            jsLanguage::createAppStringsCache($GLOBALS['current_language']);
        }
        $javascript_language_files = getVersionedScript("cache/jsLanguage/{$GLOBALS['current_language']}.js",  $GLOBALS['sugar_config']['js_lang_version']);
        if(!is_file(sugar_cached('jsLanguage/') . $this->module . '/' . $GLOBALS['current_language'] . '.js')) {
                jsLanguage::createModuleStringsCache($this->module, $GLOBALS['current_language']);
        }
        $javascript_language_files .= getVersionedScript("cache/jsLanguage/{$this->module}/{$GLOBALS['current_language']}.js", $GLOBALS['sugar_config']['js_lang_version']);

        $the_javascript  = "<script type='text/javascript' language='JavaScript'>\n";
        $the_javascript .= "function set_return() {\n";
        $the_javascript .= "    window.opener.document.EditView.submit();";
        $the_javascript .= "}\n";
        $the_javascript .= "</script>\n";
//BEGIN - WFLOW PLUGINS INFORMATION//////
global $process_dictionary;
$plugin_array = get_plugin("workflow", "trigger_createstep1", $focus);
if(!empty($plugin_array)){
	$form->assign("PLUGIN_JAVASCRIPT1", $plugin_array['jscript_part1']);
	$form->assign("PLUGIN_JAVASCRIPT2", $plugin_array['jscript_part2']);
}
//END - WFLOW PLUGINS INFORMATION//////

	$form->assign("MOD", $mod_strings);
$form->assign('JS_INCLUDE', $js_include);
	$form->assign("APP", $app_strings);
	$form->assign("JAVASCRIPT_LANGUAGE_FILES", $javascript_language_files);
	$form->assign("MODULE_NAME", $currentModule);
	$form->assign("GRIDLINE", $gridline);
	$form->assign("SET_RETURN_JS", $the_javascript);

	$form->assign("SUGAR_VERSION", $sugar_version);
	$form->assign("JS_CUSTOM_VERSION", $sugar_config['js_custom_version']);

	$form->assign("BASE_MODULE", $workflow_object->base_module);
	$form->assign("WORKFLOW_ID", $workflow_object->id);
	$form->assign("PARENT_ID", $workflow_object->id);
	$form->assign("ID", $focus->id);
	$form->assign("FIELD", $focus->field);
	$form->assign("REL_MODULE", $focus->rel_module);


	if(!empty($workflow_object->type) && $workflow_object->type=="Normal"){
		$meta_array_type = "normal_trigger";
	} else {
		$meta_array_type = "time_trigger";
	}
	$form->assign("META_FILTER_NAME", $meta_array_type);



insert_popup_header($theme);

$form->parse("embeded");
$form->out("embeded");


//////////New way of processing page
	$ProcessView = new ProcessView($workflow_object, $focus);

	//Check multi_trigger filter conditions
	if(!empty($_REQUEST['frame_type']) && $_REQUEST['frame_type']=="Secondary"){
		$ProcessView->add_filter = true;
		$form->assign("FRAME_TYPE", $_REQUEST['frame_type']);
	} else {
		$form->assign("FRAME_TYPE", "Primary");
	}

	$ProcessView->write("TriggersCreateStep1");

	$form->assign("TOP_BLOCK", $ProcessView->top_block);
	$form->assign("BOTTOM_BLOCK", $ProcessView->bottom_block);


if(!empty($_REQUEST['frame_type']) && $_REQUEST['frame_type']=="Secondary"){
			echo getClassicModuleTitle($mod_strings['LBL_FILTER_FORM_TITLE'], array($mod_strings['LBL_FILTER_FORM_TITLE'],$mod_strings['LBL_FILTER_FORM_TITLE']), false);
		} else {
			echo getClassicModuleTitle($mod_strings['LBL_TRIGGER_FORM_TITLE'], array($mod_strings['LBL_TRIGGER_FORM_TITLE'],$mod_strings['LBL_TRIGGER_FORM_TITLE']), false);
		}
$form->parse("main");
$form->out("main");

?>

<?php insert_popup_footer(); ?>
