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
 * $Id$
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





global $app_strings;
global $mod_strings;

if (empty($_REQUEST['record'])) {
    header("Location: index.php?module=ContractTypes&action=index");
}

$focus = BeanFactory::getBean('ContractTypes', $_REQUEST['record']);

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME'],$focus->name), true);



$GLOBALS['log']->info("contract Types detail view");

$xtpl=new XTemplate ('modules/ContractTypes/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) {
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
} else {
	$xtpl->assign("RETURN_MODULE", 'ContractTypes');
}
if (isset($_REQUEST['return_action'])) {
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
} else {
	$xtpl->assign("RETURN_ACTION", 'DetailView');
}
if (isset($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	$xtpl->assign("RETURN_ID", $focus->id);
}
 
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);

$xtpl->assign("NAME", $focus->name);
$xtpl->assign("LIST_ORDER", $focus->list_order);

$xtpl->parse("main");
$xtpl->out("main");

$subpanel = new SubPanelTiles($focus, 'ContractTypes');
echo $subpanel->display();
?>