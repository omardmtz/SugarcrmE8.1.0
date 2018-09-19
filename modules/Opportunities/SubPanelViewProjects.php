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
$current_module_strings = return_module_language($current_language, 'Opportunities');
$project_module_strings = return_module_language($current_language, 'Project');

global $currentModule;

global $theme;
global $focus;
global $action;





///////////////////////////////////////
///
/// SETUP PARENT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return_and_save',
	'form_name' => 'DetailView',
	'field_to_name_array' => array(
		'id' => 'opportunity_id',
		),
	);

$json = getJSONobj();
$encoded_popup_request_data = $json->encode($popup_request_data);

//
///////////////////////////////////////

// focus_list is the means of passing data to a SubPanelView.
global $focus_list;

$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='Opportunities'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='return_id' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']
	."' accessyKey='".$app_strings['LBL_SELECT_BUTTON_KEY']
	."' type='button' class='button' value='  "
	.$app_strings['LBL_SELECT_BUTTON_LABEL']
	."  ' name='button' onclick='open_popup(\"Opportunities\", 600, 400, \"\", false, true, {$encoded_popup_request_data});'>\n";
$button .= "</form>\n";
$header_text = '';
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SubPanelView&from_module=Opportunities&record=". $_REQUEST['record']."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
}
$ListView = new ListView();
$ListView->initNewXTemplate('modules/Opportunities/SubPanelViewProjects.html',$current_module_strings);
$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=DetailView&return_id={$_REQUEST['record']}");
$ListView->xTemplateAssign("REMOVE_INLINE_PNG", SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));
$ListView->setHeaderTitle($project_module_strings['LBL_OPPORTUNITY_SUBPANEL_TITLE']. $header_text );
$ListView->setHeaderText($button);
$ListView->processListView($focus_list, "main", "OPPORTUNITY");

?>