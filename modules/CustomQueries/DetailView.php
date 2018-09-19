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




$header_text = '';
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $export_module;

$export_module='CustomQueries';

$focus = BeanFactory::newBean('CustomQueries');
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_TITLE']), true); 
$is_edit = false;
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
	if($result == null)
    {
		$is_edit=true;
    }
}
	

if(isset($_REQUEST['edit']) && $_REQUEST['edit']=='true') {
	$is_edit=true;
	//Only allow admins to enter this screen
	if (!is_admin($current_user)) {
		$GLOBALS['log']->error("Non-admin user ($current_user->user_name) attempted to enter the CustomQueries edit screen");
		session_destroy();
		include('modules/Users/Logout.php');
	}
}

$GLOBALS['log']->info("CustomQuery list view");
global $theme;


$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$button  = "<form border='0' action='index.php' method='post' name='form'>\n";
$button .= "<input type='hidden' name='module' value='CustomQueries'>\n";
$button .= "<input type='hidden' name='action' value='EditView'>\n";
$button .= "<input type='hidden' name='edit' value='true'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' type='submit' name='New' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '>\n";
$button .= "</form>\n";

///Todo: Add additional search functionality


$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/CustomQueries/ListView.html',$mod_strings);
$ListView->xTemplateAssign("DELETE_INLINE_PNG", SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_DELETE']));
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE'] . $header_text);
$ListView->setHeaderText($button);

//Temporary until we upgrade the export feature to multi
$ListView->show_export_button = false;

if (!is_admin($current_user)) {
    $ListView->show_select_menu = false;
    $ListView->show_delete_button = false;
}

$ListView->setQuery("", "", "name", "CUSTOMQUERY");
$ListView->processListView($focus, "main", "CUSTOMQUERY");

echo "<p>";

echo "<BR>";

$QueryView = new ReportListView();
$QueryView->initNewXTemplate( 'modules/CustomQueries/QueryView.html',$mod_strings);
$QueryView->setHeaderTitle($mod_strings['LBL_QUERYRESULT']);
$QueryView->setup($focus, "", "main", "CUSTOMQUERY");
$query_results = $QueryView->processDataSet();


if($query_results['result']=="Error"){
	echo "<font color=\"red\"><b>".$query_results['result_msg']."".$app_strings['ERROR_EXAMINE_MSG']."</font><BR>".$query_results['msg']."</b>";	
}	

if ($is_edit) {

		$edit_button ="<form name='EditView' method='POST' action='index.php'>\n";
		$edit_button .="<input type='hidden' name='module' value='CustomQueries'>\n";
		$edit_button .="<input type='hidden' name='record' value='$focus->id'>\n";
		$edit_button .="<input type='hidden' name='action'>\n";
		$edit_button .="<input type='hidden' name='edit'>\n";
		$edit_button .="<input type='hidden' name='isDuplicate'>\n";			
		$edit_button .="<input type='hidden' name='return_module' value='CustomQueries'>\n";
		$edit_button .="<input type='hidden' name='return_action' value='index'>\n";
		$edit_button .="<input type='hidden' name='return_id' value=''>\n";
		$edit_button .='<input title="'.$app_strings['LBL_SAVE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'Save\'; return check_form(\'EditView\');" type="submit" name="button" value="  '.$app_strings['LBL_SAVE_BUTTON_LABEL'].'  " >';
		$edit_button .=' <input title="'.$app_strings['LBL_SAVE_NEW_BUTTON_TITLE'].'"  class="button" onclick="this.form.action.value=\'Save\'; this.form.isDuplicate.value=\'true\'; this.form.edit.value=\'true\'; this.form.return_action.value=\'EditView\'; return check_form(\'EditView\')" type="submit" name="button" value="  '.$app_strings['LBL_SAVE_NEW_BUTTON_LABEL'].'  " >';
		if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
			$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&edit=true&from_action=EditView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDIT_LAYOUT'])."</a>";
		}
echo get_form_header($mod_strings['LBL_CUSTOMQUERY']." ".$focus->name . '&nbsp;' . $header_text,$edit_button , false); 


	$GLOBALS['log']->info("CustomQuery edit view");
	$xtpl=new XTemplate ('modules/CustomQueries/EditView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	
	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	$xtpl->assign("JAVASCRIPT", get_set_focus_js());
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign('NAME', $focus->name);
	$xtpl->assign('DESCRIPTION', $focus->description);
	$xtpl->assign('CUSTOM_QUERY', $focus->custom_query);

	if (empty($focus->list_order)) $xtpl->assign('LIST_ORDER', count($focus->get_custom_queries())+1); 
	else $xtpl->assign('LIST_ORDER', $focus->list_order);

// adding custom fields:

require_once('modules/DynamicFields/templates/Files/EditView.php');


	$xtpl->parse("main");
	$xtpl->out("main");
	
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
}
?>
