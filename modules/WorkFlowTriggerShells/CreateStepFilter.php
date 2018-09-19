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
require_once('include/workflow/workflow_utils.php');

global $theme;
global $app_strings;
global $app_list_strings;
global $mod_strings;

global $urlPrefix;
global $currentModule;

$log = LoggerManager::getLogger('workflow_alerts');


if(!empty($_REQUEST['workflow_id'])) {
    $workflow_object = BeanFactory::retrieveBean('WorkFlow', $_REQUEST['workflow_id']);
}
if(empty($workflow_object)) {
	sugar_die("You shouldn't be here");
}

$focus = BeanFactory::newBean('WorkFlowTriggerShells');

if(!empty($_REQUEST['record']) ) {
    $focus->retrieve($_REQUEST['record']);
}
if(!empty($_REQUEST['rel_module'])) {
   $focus->rel_module = $_REQUEST['rel_module'];
}
if(!empty($_REQUEST['type'])) {
   $focus->type = $_REQUEST['type'];
}

////////////////////////////////////////////////////////
// Start the output
////////////////////////////////////////////////////////
	$form =new XTemplate ('modules/WorkFlowTriggerShells/CreateStepFilter.html');
$js_include = getVersionedScript('cache/include/javascript/sugar_grp1.js')
    . getVersionedScript('include/workflow/jutils.js');
	$log->debug("using file modules/WorkFlowTriggerShells/CreateStepFilter.html");
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
$form->assign('JS_INCLUDE', $js_include);
	$form->assign("JAVASCRIPT_LANGUAGE_FILES", $javascript_language_files);
	$form->assign("MODULE_NAME", $currentModule);
	$form->assign("GRIDLINE", $gridline);
	$form->assign("SET_RETURN_JS", $the_javascript);

	$form->assign("BASE_MODULE", $workflow_object->base_module);
	$form->assign("WORKFLOW_ID", $workflow_object->id);
	$form->assign("ID", $focus->id);
	$form->assign("REL_MODULE", $focus->rel_module);
	$form->assign("PARENT_ID", $workflow_object->id);
	$form->assign("TRIGGER_TYPE", $workflow_object->type);
	$form->assign("TYPE", $focus->type);

	//Check multi_trigger filter conditions
	if(!empty($_REQUEST['frame_type'])){
		$form->assign("FRAME_TYPE", $_REQUEST['frame_type']);
	} else {
		$form->assign("FRAME_TYPE", $focus->frame_type);
	}



insert_popup_header($theme);

$form->parse("embeded");
$form->out("embeded");


////////Middle Items/////////////////////////////
/*
//SET Previous Display Text
	$ProcessView = new ProcessView($workflow_object, $focus);
	$prev_display_text = $ProcessView->get_prev_text("TriggersCreateStep1", $focus->type);
	$form->assign("PREV_DISPLAY_TEXT", $prev_display_text);
*/

/////Top secondary items////////////////

	if($focus->type=="filter_rel_field"){

		//process out the actual name of the rel module


		///Build the relationship information using the Relationship handler
		$rel_handler = $workflow_object->call_relationship_handler("base_module", true);
		$rel_handler->set_rel_vardef_fields(strtolower($focus->rel_module));
		$rel_handler->build_info(false);
		$rel_handler->build_module_labels(false);

		$target_module = $rel_handler->rel1_module;

		$rel_module_type_options =get_select_options_with_id($app_list_strings['wflow_relfilter_type_dom'],$focus->rel_module_type);
		$rel_module_type_select = "<select name='rel_module_type' tabindex='2'>$rel_module_type_options</select>";
		$form->assign("FILTER_TOP_DISPLAY", $mod_strings['LBL_FILTER_FIELD_PART1']." ".$app_list_strings['wflow_relfilter_type_dom'][$focus->rel_module_type]." ".$rel_handler->rel1_array['slabel'].$mod_strings['LBL_SPECIFIC_FIELD']);
		$form->assign("FILTER_BOTTOM_DISPLAY", $mod_strings['LBL_FILTER_FIELD_PART1']." ".$rel_module_type_select." ".$rel_handler->rel1_array['slabel'].$mod_strings['LBL_APOSTROPHE_S']);

	} else {
		$target_module = $workflow_object->base_module;
		$form->assign("FILTER_TOP_DISPLAY", $mod_strings['LBL_WHEN_VALUE1']);
		$form->assign("FILTER_BOTTOM_DISPLAY", $mod_strings['LBL_WHEN_VALUE2']);
		//end if else filter_rel_field or filter_field
	}



//////////////////BEGIN 1st Filter Object	/////////////////////////////////

		$filter1_object = BeanFactory::newBean('Expressions');
		//only try to retrieve if there is a base object set
		if(isset($focus->id) && $focus->id!="") {
			$filter_list = $focus->get_linked_beans('expressions','Expression');
			if(isset($filter_list[0])) {
				$filter1_id = $filter_list[0]->id;
			}

			if(isset($filter1_id) && $filter1_id!="") {
  		  		$filter1_object->retrieve($filter1_id);
  		  		//$target_module = $focus->target_module;
				$display_array = $filter1_object->get_display_array_using_name();
				$filter1_expression_text = $display_array['lhs_field']." ".$display_array['operator']." ".$display_array['rhs_value'];
			} else {
				$filter1_expression_text = $mod_strings['LBL_SPECIFIC_FIELD_LNK'];
			}
		//end if base_id is there
		} else {
			$filter1_expression_text = $mod_strings['LBL_SPECIFIC_FIELD_LNK'];
		}

		$form->assign("TRIGGER_EXP_ID", $filter1_object->id);
		$form->assign("TRIGGER_RHS_VALUE", $filter1_object->rhs_value);
		$form->assign("TRIGGER_TEXT", $filter1_expression_text);
		$form->assign("TRIGGER_OPERATOR", $filter1_object->operator);
		$form->assign("TRIGGER_EXP_TYPE", $filter1_object->exp_type);
		$form->assign("TRIGGER_LHS_MODULE", $target_module);
		$form->assign("TRIGGER_LHS_FIELD", $filter1_object->lhs_field);


/////////////////END Filter1 Object/////////////////////////////////

/////////////////End Items 	//////////////////////

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
