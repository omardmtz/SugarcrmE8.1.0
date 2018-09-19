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
 * $Id: DetailView.php 13782 2006-06-06 17:58:55 +0000 (Tue, 06 Jun 2006) majed $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



require_once('modules/Holidays/Forms.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

$focus = BeanFactory::newBean('Holidays');

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("HOLIDAY", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}
echo getClassicModuleTitle($mod_strings['LBL_MODULE_NAME'], array($mod_strings['LBL_MODULE_NAME']), true);

$GLOBALS['log']->info("Holiday detail view");

$xtpl=new XTemplate ('modules/Holidays/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("RETURN_MODULE", "Holidays");
$xtpl->assign("RETURN_ACTION", "DetailView");
$xtpl->assign("ACTION", "EditView");

$xtpl->assign("NAME", $focus->holiday_date);
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));

$detailView->processListNavigation($xtpl, "HOLIDAY", $offset);

$xtpl->parse("main");
$xtpl->out("main");

?>