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
 


function additionalDetailsAccount($fields) {
	static $mod_strings;
	global $app_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Accounts');
	}
	$overlib_string = '';
	
	if(!empty($fields['BILLING_ADDRESS_STREET']) || !empty($fields['BILLING_ADDRESS_CITY']) ||
		!empty($fields['BILLING_ADDRESS_STATE']) || !empty($fields['BILLING_ADDRESS_POSTALCODE']) ||
		!empty($fields['BILLING_ADDRESS_COUNTRY']))
			$overlib_string .= '<b>' . $mod_strings['LBL_BILLING_ADDRESS'] . '</b><br>';
	if(!empty($fields['BILLING_ADDRESS_STREET'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_STREET_2'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET_2'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_STREET_3'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET_3'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_STREET_4'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET_4'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_CITY'])) $overlib_string .= $fields['BILLING_ADDRESS_CITY'] . ', ';
	if(!empty($fields['BILLING_ADDRESS_STATE'])) $overlib_string .= $fields['BILLING_ADDRESS_STATE'] . ' ';
	if(!empty($fields['BILLING_ADDRESS_POSTALCODE'])) $overlib_string .= $fields['BILLING_ADDRESS_POSTALCODE'] . ' ';
	if(!empty($fields['BILLING_ADDRESS_COUNTRY'])) $overlib_string .= $fields['BILLING_ADDRESS_COUNTRY'] . '<br>';
	
	if(strlen($overlib_string) > 0 && !(strrpos($overlib_string, '<br>') == strlen($overlib_string) - 4)) 
		$overlib_string .= '<br>';  
	
	if(!empty($fields['PHONE_FAX'])) $overlib_string .= '<b>'. $mod_strings['LBL_FAX'] . '</b> <span class="phone">' . $fields['PHONE_FAX'] . '</span><br>';
	if(!empty($fields['PHONE_ALTERNATE'])) $overlib_string .= '<b>'. $mod_strings['LBL_OTHER_PHONE'] . '</b> <span class="phone">' . $fields['PHONE_ALTERNATE'] . '</span><br>';
	if(!empty($fields['WEBSITE'])) 
		$overlib_string .= '<a target=_blank href=http://'. $fields['WEBSITE'] . '>' . $fields['WEBSITE'] . '</a><br>';
	if(!empty($fields['INDUSTRY'])) $overlib_string .= '<b>'. $mod_strings['LBL_INDUSTRY'] . '</b> ' . $fields['INDUSTRY'] . '<br>';
	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Accounts&return_module=Accounts&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Accounts&return_module=Accounts&record={$fields['ID']}");
}
 
 ?>
 
 