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
 

function additionalDetailsOpportunity($fields) {
	global $current_language;
	$mod_strings = return_module_language($current_language, 'Opportunities');
	$overlib_string = '';
	
	if(!empty($fields['LEAD_SOURCE'])) $overlib_string .= '<b>'. $mod_strings['LBL_LEAD_SOURCE'] . '</b> ' . $fields['LEAD_SOURCE'] . '<br>';
	if(!empty($fields['PROBABILITY'])) $overlib_string .= '<b>'. $mod_strings['LBL_PROBABILITY'] . '</b> ' . $fields['PROBABILITY'] . '<br>';
	if(!empty($fields['NEXT_STEP'])) $overlib_string .= '<b>'. $mod_strings['LBL_NEXT_STEP'] . '</b> ' . $fields['NEXT_STEP'] . '<br>';
	if(!empty($fields['OPPORTUNITY_TYPE'])) $overlib_string .= '<b>'. $mod_strings['LBL_TYPE'] . '</b> ' . $fields['OPPORTUNITY_TYPE'] . '<br>';

	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ';
		$overlib_string .= substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Opportunities&return_module=Opportunities&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Opportunities&return_module=Opportunities&record={$fields['ID']}");
}
 
 ?>
 
