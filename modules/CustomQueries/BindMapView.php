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

$focus = BeanFactory::newBean('CustomQueries');

	if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    	$focus->retrieve($_REQUEST['record']);
	}
	
	if(isset($_REQUEST['old_column_array']) && $_REQUEST['old_column_array']!="") {
		$old_column_array = $_REQUEST['old_column_array'];
	}


if (!is_admin($current_user))
{
   sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}

global $theme;


$GLOBALS['log']->info("DataSets edit view");

$xtpl=new XTemplate ('modules/CustomQueries/BindMapView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

	$temp_select = $focus->repair_column_binding();
	$temp_select2 = $temp_select;

	foreach($_SESSION['old_column_array'] as $key => $value){
		//eliminate direct matches
		if(!empty($temp_select2[$value])){
				unset($temp_select2[$value]);
		//end eliminate direct matches
		}
	//foreach	
	}	

	foreach($_SESSION['old_column_array'] as $key => $value){
		//only show if there is no direct match
		if(empty($temp_select[$value])){
			$selectdropdown = get_select_options_with_id($temp_select2,$value);
			$xtpl->assign("OLD_COLUMN_NAME", $value);
			$xtpl->assign("SELECT_NAME","column_".$key);
			$xtpl->assign("SELECT_OPTIONS",$selectdropdown);
			$xtpl->parse("main.row");
		//end if only show if no direct match
		} else {
			//remove this element from the array
			unset($temp_select[$value]);	
		}	

	//foreach	
	}	
	
	$xtpl->assign("ID", $_REQUEST['record']);

	$xtpl->parse("main");
	$xtpl->out("main");

?>
