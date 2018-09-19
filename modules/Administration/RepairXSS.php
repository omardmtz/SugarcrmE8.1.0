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

include("include/modules.php"); // provides $moduleList, $beanList, etc.

///////////////////////////////////////////////////////////////////////////////
////	UTILITIES
/**
 * Cleans all SugarBean tables of XSS - no asynchronous calls.  May take a LONG time to complete.
 * Meant to be called from a Scheduler instance or other timed or other automation.
 */
function cleanAllBeans() {
	
}
////	END UTILITIES
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	PAGE OUTPUT
if(isset($runSilent) && $runSilent == true) {
	// if called from Scheduler
	cleanAllBeans();
} else {
	$hide = array('Activities', 'Home', 'iFrames', 'Calendar', 'Dashboard');

	sort($moduleList);
	$options = array();
	$options[] = $app_strings['LBL_NONE'];
	$options['all'] = "--{$app_strings['LBL_MODULE_ALL']}--";
	
	foreach($moduleList as $module) {
		if(!in_array($module, $hide)) {
			$options[$module] = $module;
		}
	}
	
	$options = get_select_options_with_id($options, '');
	$beanDropDown = "<select onchange='SUGAR.Administration.RepairXSS.refreshEstimate(this);' id='repairXssDropdown'>{$options}</select>";
	
	echo getClassicModuleTitle('Administration', array($mod_strings['LBL_REPAIRXSS_TITLE']), false);
	echo "<script>var done = '{$mod_strings['LBL_DONE']}';</script>";
	
	$smarty = new Sugar_Smarty(); 
	$smarty->assign("mod", $mod_strings);
	$smarty->assign("beanDropDown", $beanDropDown);
	$smarty->display("modules/Administration/templates/RepairXSS.tpl");
} // end else