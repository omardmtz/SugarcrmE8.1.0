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

function additionalDetailsCampaign($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Campaigns');
	}
		
	$overlib_string = '';
	
	if(!empty($fields['START_DATE'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_CAMPAIGN_START_DATE'] . '</b> ' . $fields['START_DATE'] . '<br>';

	if(!empty($fields['TRACKER_TEXT'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_TRACKER_TEXT'] . '</b> ' . $fields['TRACKER_TEXT'] . '<br>';
	if(!empty($fields['REFER_URL'])) 
		$overlib_string .= '<a target=_blank href='. $fields['REFER_URL'] . '>' . $fields['REFER_URL'] . '</a><br>';
	
	if(!empty($fields['OBJECTIVE'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_CAMPAIGN_OBJECTIVE'] . '</b> ' . substr($fields['OBJECTIVE'], 0, 300);
		if(strlen($fields['OBJECTIVE']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}	
	if(!empty($fields['CONTENT'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_CAMPAIGN_CONTENT'] . '</b> ' . substr($fields['CONTENT'], 0, 300);
		if(strlen($fields['CONTENT']) > 300) $overlib_string .= '...';
	}	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Campaigns&return_module=Campaigns&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Campaigns&return_module=Campaigns&record={$fields['ID']}");
}
 
 ?>
 
 