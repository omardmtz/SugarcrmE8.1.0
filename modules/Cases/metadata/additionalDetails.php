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
function additionaldetailscase($fields) {
    return additionalDetailsaCase($fields);
}
function additionalDetailsaCase($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Cases');
	}
		
	$overlib_string = '';
		
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}
	if(!empty($fields['RESOLUTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_RESOLUTION'] . '</b> ' . substr($fields['RESOLUTION'], 0, 300);
		if(strlen($fields['RESOLUTION']) > 300) $overlib_string .= '...';
	}		
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'width' => '400',
				 'editLink' => "index.php?action=EditView&module=Cases&return_module=Cases&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Cases&return_module=Cases&record={$fields['ID']}");
}
 
 ?>
 
 