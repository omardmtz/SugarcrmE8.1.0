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

require_once 'include/SugarSmarty/plugins/function.sugar_action_menu.php';
require_once 'include/SugarSmarty/plugins/function.sugar_csrf_form_token.php';

$header_text = '';
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if (!is_admin($current_user) && (!is_admin_for_module($GLOBALS['current_user'],'Products')))
{
   sugar_die("Unauthorized access to administration.");
}

$focus = BeanFactory::newBean('ProductTypes');
echo getClassicModuleTitle($focus->module_dir, array($mod_strings['LBL_MODULE_NAME']), true);

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
		$GLOBALS['log']->error("Non-admin user ($current_user->user_name) attempted to enter the ProductTypes edit screen");
		session_destroy();
		include('modules/Users/Logout.php');
	}
}

$GLOBALS['log']->info("ProductType list view");
global $theme;

$ListView = new ListView();

$button  = "<form border='0' action='index.php' method='post' name='form'>\n";
$button .= smarty_function_sugar_csrf_form_token(array(), $ListView);
$button .= "<input type='hidden' name='module' value='ProductTypes'>\n";
$button .= "<input type='hidden' name='action' value='EditView'>\n";
$button .= "<input type='hidden' name='edit' value='true'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button primary' id='btn_create' type='submit' name='New' value='".$app_strings['LBL_NEW_BUTTON_LABEL']."'>\n";
$button .= "</form>\n";

if((is_admin($current_user)||is_admin_for_module($GLOBALS['current_user'],'Products')) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'"
,null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
}
$ListView->initNewXTemplate( 'modules/ProductTypes/ListView.html',$mod_strings);
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_DELETE']));

$action_button = smarty_function_sugar_action_menu(array(
    'id' => 'manufacturer_create_button',
    'buttons' => array($button),
), $ListView);

$ListView->setHeaderTitle($header_text.$action_button);

$ListView->show_export_button = false;
$ListView->show_mass_update = false;
$ListView->show_delete_button = false;
$ListView->setQuery("", "", "list_order", "PRODUCTTYPE");
$ListView->processListView($focus, "main", "PRODUCTTYPE");

if ($is_edit) {
	$GLOBALS['log']->info("ProductType edit view");

	$edit_button ="<form name='EditView' method='POST' action='index.php'>\n";
    $edit_button .= smarty_function_sugar_csrf_form_token(array(), $ListView);
	$edit_button .="<input type='hidden' name='module' value='ProductTypes'>\n";
	$edit_button .="<input type='hidden' name='record' value='$focus->id'>\n";
	$edit_button .="<input type='hidden' name='action'>\n";
	$edit_button .="<input type='hidden' name='edit'>\n";
	$edit_button .="<input type='hidden' name='isDuplicate'>\n";
	$edit_button .="<input type='hidden' name='return_module' value='ProductTypes'>\n";
	$edit_button .="<input type='hidden' name='return_action' value='index'>\n";
	$edit_button .="<input type='hidden' name='return_id' value=''>\n";
		$edit_button .='<input id="save_bttn" title="'.$app_strings['LBL_SAVE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_BUTTON_KEY'].'" class="button primary" onclick="this.form.action.value=\'Save\'; return check_form(\'EditView\');" type="submit" name="button" value="'.$app_strings['LBL_SAVE_BUTTON_LABEL'].'" >';
		$edit_button .=' <input id="save_and_create_bttn" title="'.$app_strings['LBL_SAVE_NEW_BUTTON_TITLE'].'" class="button" onclick="this.form.action.value=\'Save\'; this.form.isDuplicate.value=\'true\'; this.form.edit.value=\'true\'; this.form.return_action.value=\'EditView\'; return check_form(\'EditView\')" type="submit" name="button" value="'.$app_strings['LBL_SAVE_NEW_BUTTON_LABEL'].'" >';
	if((is_admin($current_user)||is_admin_for_module($GLOBALS['current_user'],'Products')) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&edit=true&from_action=EditView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
	}
	echo get_form_header($mod_strings['LBL_PRODUCTTYPE']." ".$focus->name . '&nbsp;' . $header_text,$edit_button , false);


	$GLOBALS['log']->info("ProductTypes edit view");
	$xtpl=new XTemplate ('modules/ProductTypes/EditView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);

	if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
	if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
	if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
	$xtpl->assign("JAVASCRIPT", get_set_focus_js());
	$xtpl->assign("ID", $focus->id);
	$xtpl->assign('NAME', $focus->name);
	$xtpl->assign('DESCRIPTION', $focus->description);

	if (empty($focus->list_order)) $xtpl->assign('LIST_ORDER', count($focus->get_product_types())+1);
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
