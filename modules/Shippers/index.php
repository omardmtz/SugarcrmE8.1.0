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

require_once 'include/SugarSmarty/plugins/function.sugar_csrf_form_token.php';

$header_text = '';
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if((!is_admin($GLOBALS['current_user']) && (!is_admin_for_module($GLOBALS['current_user'],'Products')))) 
{
   sugar_die("Unauthorized access to administration.");
}

$focus = BeanFactory::newBean('Shippers');
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_TITLE']), true); 
$is_edit = false;
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
	$is_edit=true;
}
if(isset($_REQUEST['edit']) && $_REQUEST['edit']=='true') {
	$is_edit=true;
	//Only allow admins to enter this screen
	if (!is_admin($current_user)&& !is_admin_for_module($GLOBALS['current_user'],'Products')) {
		$GLOBALS['log']->error("Non-admin user ($current_user->user_name) attempted to enter the Shippers edit screen");
		session_destroy();
		include('modules/Users/Logout.php');
	}
}

$GLOBALS['log']->info("Shipper list view");

$ListView = new ListView();
$module = InputValidation::getService()->getValidInputRequest('module', 'Assert\Mvc\ModuleName');

$button  = "<form border='0' action='index.php' method='post' name='form'>\n";
$button .= smarty_function_sugar_csrf_form_token(array(), $ListView);
$button .= "<input type='hidden' name='module' value='Shippers'>\n";
$button .= "<input type='hidden' name='action' value='EditView'>\n";
$button .= "<input type='hidden' name='edit' value='true'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button' type='submit' name='New' value='  ".$app_strings['LBL_NEW_BUTTON_LABEL']."  '>\n";
$button .= "</form>\n";

if((is_admin($current_user)|| is_admin_for_module($GLOBALS['current_user'],'Products')) && $module != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".htmlspecialchars($module, ENT_QUOTES, 'UTF-8') ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'"
,null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
}
$ListView->initNewXTemplate( 'modules/Shippers/ListView.html',$mod_strings);
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_DELETE']));
$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE'] . $header_text);
$ListView->setHeaderText($button);
$ListView->show_export_button = false;
$ListView->show_mass_update = false;
$ListView->show_select_menu = false;
$ListView->show_delete_button = false;
$ListView->setQuery("", "", "list_order", "SHIPPER");
$ListView->processListView($focus, "main", "SHIPPER");

if ($is_edit) {
	
		$edit_button ="<form name='EditView' method='POST' action='index.php'>\n";
        $edit_button .= smarty_function_sugar_csrf_form_token(array(), $ListView);
		$edit_button .="<input type='hidden' name='module' value='Shippers'>\n";
		$edit_button .="<input type='hidden' name='record' value='$focus->id'>\n";
		$edit_button .="<input type='hidden' name='action'>\n";
		$edit_button .="<input type='hidden' name='edit'>\n";
		$edit_button .="<input type='hidden' name='isDuplicate'>\n";			
		$edit_button .="<input type='hidden' name='return_module' value='Shippers'>\n";
		$edit_button .="<input type='hidden' name='return_action' value='index'>\n";
		$edit_button .="<input type='hidden' name='return_id' value=''>\n";
		$edit_button .='<input title="'.$app_strings['LBL_SAVE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'Save\'; return check_form(\'EditView\');" type="submit" name="button" value="  '.$app_strings['LBL_SAVE_BUTTON_LABEL'].'  " >';
		$edit_button .=' <input title="'.$app_strings['LBL_SAVE_NEW_BUTTON_TITLE'].'" class="button" onclick="this.form.action.value=\'Save\'; this.form.isDuplicate.value=\'true\'; this.form.edit.value=\'true\'; this.form.return_action.value=\'EditView\'; return check_form(\'EditView\')" type="submit" name="button" value="  '.$app_strings['LBL_SAVE_NEW_BUTTON_LABEL'].'  " >';
		if((is_admin($current_user)|| is_admin_for_module($GLOBALS['current_user'],'Products')) && $module != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&edit=true&from_action=EditView&from_module=".htmlspecialchars($module, ENT_QUOTES, 'UTF-8') ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
		}
		echo get_form_header($mod_strings['LBL_SHIPPER']." ".$focus->name. '&nbsp;' . $header_text,$edit_button , false); 


	$GLOBALS['log']->info("Shippers edit view");
	$xtpl=new XTemplate ('modules/Shippers/EditView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);

	$returnModule = InputValidation::getService()->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName');
	$returnAction = InputValidation::getService()->getValidInputRequest('return_action');
	$returnId = InputValidation::getService()->getValidInputRequest('return_id', 'Assert\Guid');
	if (!empty($returnModule)) {
		$xtpl->assign("RETURN_MODULE", htmlspecialchars($returnModule, ENT_QUOTES, 'UTF-8'));
	}
	if (!empty($returnAction)) {
		$xtpl->assign("RETURN_ACTION", htmlspecialchars($returnAction, ENT_QUOTES, 'UTF-8'));
	}
	if (!empty($returnId)) {
		$xtpl->assign("RETURN_ID", htmlspecialchars($returnId, ENT_QUOTES, 'UTF-8'));
	}
	$xtpl->assign("JAVASCRIPT", get_set_focus_js());
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign('NAME', $focus->name);
	$xtpl->assign('STATUS', $focus->status);

	if (empty($focus->list_order)) $xtpl->assign('LIST_ORDER', count($focus->get_shippers(false,'All'))+1); 
	else $xtpl->assign('LIST_ORDER', $focus->list_order);
	$xtpl->assign('STATUS_OPTIONS', get_select_options_with_id($mod_strings['shipper_status_dom'], $focus->status));

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
