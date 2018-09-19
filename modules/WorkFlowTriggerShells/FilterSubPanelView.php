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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





global $app_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'WorkFlowTriggerShells');

global $currentModule;
global $focus;
global $action;

// focus_triggers_list is the means of passing data to a SubPanelView.
global $focus_trigger_filters_list;

$button  = "<form  action='index.php' method='post' name='form_trigger_filter' id='form_trigger_filter'>\n";
$button .= "<input type='hidden' name='module' value='WorkFlowTriggerShells'>\n";
$button .= "<input type='hidden' name='workflow_id' value='$focus->id'>\n<input type='hidden' name='trigger_name' value='$focus->name'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";

$primary_present = false;

	$button .= "<input title='".$current_module_strings['LBL_NEW_FILTER_BUTTON_TITLE']."' accessKey='".$current_module_strings['LBL_NEW_FILTER_BUTTON_KEY']."' class='button' type='button' name='New' value='  ".$current_module_strings['LBL_NEW_FILTER_BUTTON_LABEL']."'";
	$button .= "LANGUAGE=javascript onclick='window.open(\"index.php?module=WorkFlowTriggerShells&action=CreateStep1&sugar_body_only=true&form=ComponentView&workflow_id=$focus->id&frame_type=Secondary\",\"new\",\"width=400,height=500,resizable=1,scrollbars=1\");'";


$button .= "</form>\n";
$ListView = new ListView();
$header_text = '';
		
	$ListView->initNewXTemplate( 'modules/WorkFlowTriggerShells/FilterSubPanelView.html',$current_module_strings);
$ListView->xTemplateAssign("WORKFLOW_ID", $focus->id);
$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id={$_REQUEST['record']}");
$ListView->xTemplateAssign("EDIT_INLINE_PNG",  SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));
$ListView->setHeaderTitle($current_module_strings['LBL_TRIGGER_FILTER_TITLE'] . $header_text);
$ListView->setHeaderText($button);
$ListView->processListView($focus_trigger_filters_list, "main", "TRIGGER");
?>
