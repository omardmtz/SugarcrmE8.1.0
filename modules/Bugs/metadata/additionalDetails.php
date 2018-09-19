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
 


function additionalDetailsBug($fields) {
	static $mod_strings;
	global $app_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Bugs');
	}
		
	$overlib_string = '';

	if(!empty($fields['DATE_ENTERED'])) 
		$overlib_string .= '<b>'. $app_strings['LBL_DATE_ENTERED'] . '</b> ' . $fields['DATE_ENTERED'] . '<br>';
	if(!empty($fields['SOURCE'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_SOURCE'] . '</b> ' . $fields['SOURCE'] . '<br>';
	if(!empty($fields['PRODUCT_CATEGORY'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_PRODUCT_CATEGORY'] . '</b> ' . $fields['PRODUCT_CATEGORY'] . '<br>';
	if(!empty($fields['RESOLUTION'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_RESOLUTION'] . '</b> ' . $fields['RESOLUTION'] . '<br>';
				
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}
		
	if(!empty($fields['WORK_LOG'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_WORK_LOG'] . '</b> ' . substr($fields['WORK_LOG'], 0, 300);
		if(strlen($fields['WORK_LOG']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Bugs&return_module=Bugs&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Bugs&return_module=Bugs&record={$fields['ID']}");

}
 
 ?>
 
 