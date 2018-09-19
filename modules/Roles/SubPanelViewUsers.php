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
$current_module_strings = return_module_language($current_language, 'Users');

global $currentModule;

global $theme;
global $focus;
global $action;




// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<form action='index.php' method='post' name='UsersForm' id='UsersForm'>\n";
$button .= "<input type='hidden' name='module' value='Users'>\n";
if ($currentModule == 'Roles') {
	$button .= "<input type='hidden' name='record' value='$focus->id'>\n";
}
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input type='hidden' name='report_module'>\n";
$button .= "<input type='hidden' name='id'>\n";

if ($currentModule == 'Roles') 
	$button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']." ' type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE=javascript onclick='window.open(\"index.php?module=Users&action=PopupUsers&html=Popup_Users_picker&form=UsersForm&record=".$focus->id."&form_submit=true&query=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";

$button .= "</form>\n";



$ListView = new ListView();
$ListView->initNewXTemplate('modules/Roles/SubPanelViewUsers.html', $current_module_strings);

$ListView->xTemplateAssign("ROLE_RECORD", $focus->id);
$ListView->xTemplateAssign("EDIT_INLINE_PNG",  SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));
$ListView->xTemplateAssign("REMOVE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));

$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id=".$focus->id);
$ListView->setHeaderTitle($current_module_strings['LBL_MODULE_NAME'] );
$ListView->setQuery("", "", "user_name", "USER");
$ListView->setHeaderText($button);
$ListView->processListView($focus_list, "main", "USER");

?>