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

if (!is_admin($current_user))
{
   sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}

global $theme;

$GLOBALS['log']->info("DataSets edit view");

$xtpl=new XTemplate ('modules/CustomQueries/RepairQuery.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

	$xtpl->assign("ID", $_REQUEST['record']);
	$xtpl->assign("QUERY_MSG", $_REQUEST['error_msg']);
	
	if(!empty($_REQUEST['edit'])){
		$xtpl->assign("EDIT", $_REQUEST['edit']);
	}
		$xtpl->assign("REPAIR", "repair");	
	
	$xtpl->parse("main");
	$xtpl->out("main");

?>
