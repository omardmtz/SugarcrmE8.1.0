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
global $current_language, $current_module_strings;
$current_module_strings = return_module_language($current_language, 'WorkFlowAlerts');

global $currentModule;
global $theme;
global $focus;
global $action;





// focus_alerts_lists is the means of passing data to a SubPanelView.
global $focus_alertcomp_list;

$button  = "<form  action='index.php' method='post' name='ComponentView' id='ComponentView'>\n";
$button .= "<input type='hidden' name='module' value='WorkFlowAlerts'>\n";
$button .= "<input type='hidden' name='parent_id' value='$focus->id'>\n<input type='hidden' name='alert_name' value='$focus->name'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";

$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."'  class='button' type='button' name='New' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."'";
$button .= "LANGUAGE=javascript onclick='window.open(\"index.php?module=WorkFlowAlerts&action=CreateStep1&sugar_body_only=true&form=ComponentView&parent_id=$focus->id&base_module=$workflow_object->base_module\",\"new\",\"width=400,height=500,resizable=1,scrollbars=1\");'";
$button .= ">\n";
$button .= "</form>\n";
$ListView = new ListView();
$header_text = '';

	
	$ListView->initNewXTemplate( 'modules/WorkFlowAlerts/SubPanelView.html',$current_module_strings);
$ListView->xTemplateAssign("PARENT_ID", $focus->id);

$target_workflow_object = $workflow_object->get_parent_object();
$ListView->xTemplateAssign("BASE_MODULE", $target_workflow_object->base_module);
$ListView->xTemplateAssign("WORKFLOW_ID", $workflow_object->id);

//meta array drive line below
$ListView->xTemplateAssign("LBL_LIST_STATEMENT", $current_module_strings[$focus->target_meta_array['statement_title']]);

$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id={$_REQUEST['record']}");
$ListView->xTemplateAssign("EDIT_INLINE_PNG",  SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));
$ListView->setHeaderTitle($current_module_strings[$focus->target_meta_array['sub_panel_title']] . $header_text);
$ListView->setHeaderText($button);
$ListView->processListView($focus_alertcomp_list, "main", "ALERTCOMP");
?>
