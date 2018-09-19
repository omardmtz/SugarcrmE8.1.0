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
$current_module_strings = return_module_language($current_language, 'WorkFlowActionShells');

global $currentModule;
global $theme;
global $focus;
global $action;

// focus_alerts_lists is the means of passing data to a SubPanelView.
global $focus_actions_list;

$buttons = array(
    "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."'  class='button' type='button' name='NewWorkFlowActionShells' id='NewWorkFlowActionShells' value='".$app_strings['LBL_NEW_BUTTON_LABEL']."'" .
        "LANGUAGE=javascript onclick='window.open(\"index.php?module=WorkFlowActionShells&action=CreateStep1&html=CreateStep1&form=ComponentView&form_submit=false&query=true&sugar_body_only=true&workflow_id=$focus->id\",\"new\",\"width=525,height=500,resizable=1,scrollbars=1\");'" .
        ">\n",
);


$button  = "<form  action='index.php' method='post' name='ComponentView' id='ComponentView'>\n";
$button .= "<input type='hidden' name='module' value='WorkFlowActionShells'>\n";
$button .= "<input type='hidden' name='workflow_id' value='$focus->id'>\n<input type='hidden' name='alert_name' value='$focus->name'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";

require_once('include/SugarSmarty/plugins/function.sugar_action_menu.php');
$button .= smarty_function_sugar_action_menu(array(
    'id' => 'ACLRoles_EditView_action_menu',
    'buttons' => $buttons,
), $xtpl);

$button .= "</form>\n";
$ListView = new ListView();
$header_text = '';

	$ListView->initNewXTemplate( 'modules/WorkFlowActionShells/SubPanelView.html',$current_module_strings);
$ListView->xTemplateAssign("WORKFLOW_ID", $focus->id);
$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id={$_REQUEST['record']}");
$ListView->xTemplateAssign("EDIT_INLINE_PNG",  SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));
$ListView->setHeaderTitle($current_module_strings['LBL_MODULE_NAME'] . $header_text);
$ListView->setHeaderText($button);
$ListView->processListView($focus_actions_list, "main", "ACTION");
?>
