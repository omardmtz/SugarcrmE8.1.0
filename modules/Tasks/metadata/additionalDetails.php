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
 
function additionalDetailsTask($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Tasks');
	}

	$overlib_string = '';
    if(!empty($fields['DATE_START'])) $overlib_string .= '<b>'. $mod_strings['LBL_START_DATE_AND_TIME'] . '</b> ' . $fields['DATE_START'] .  '<br>';
	if(!empty($fields['DATE_DUE'])) $overlib_string .= '<b>'. $mod_strings['LBL_DUE_DATE_AND_TIME'] . '</b> ' . $fields['DATE_DUE'] .  '<br>';
	if(!empty($fields['PRIORITY'])) $overlib_string .= '<b>'. $mod_strings['LBL_PRIORITY'] . '</b> ' . $fields['PRIORITY'] . '<br>';
	if(!empty($fields['STATUS'])) $overlib_string .= '<b>'. $mod_strings['LBL_STATUS'] . '</b> ' . $fields['STATUS'] . '<br>';
		
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	
	
		$editLink = "index.php?action=EditView&module=Tasks&record={$fields['ID']}"; 
	$viewLink = "index.php?action=DetailView&module=Tasks&record={$fields['ID']}";	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => $editLink, 
				 'viewLink' => $viewLink);

}
