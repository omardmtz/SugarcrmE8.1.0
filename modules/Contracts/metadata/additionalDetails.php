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

// $Id: additionalDetails.php 13782 2006-06-06 17:58:55Z majed $

function additionalDetailsContract($fields) {
	static $mod_strings;

	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Contracts');
	}
		
	$overlib_string = '';
		
	if(!empty($fields['REFERENCE_CODE'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_REFERENCE_CODE'] . '</b> ' . substr($fields['REFERENCE_CODE'], 0, 300);
		if(strlen($fields['REFERENCE_CODE']) > 300) {
			$overlib_string .= '...';
		}
		$overlib_string .= '<br>';
	}		
	
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) {
			$overlib_string .= '...';
		}
	}

	$ret_val = array (
		'fieldToAddTo' => 'NAME', 
		'string' => $overlib_string, 
		'width' => '400',
		'editLink' => "index.php?module=Contracts&action=EditView&record={$fields['ID']}&return_module=Contracts", 
		'viewLink' => "index.php?module=Contracts&action=DetailView&record={$fields['ID']}"
	);

	return $ret_val;
}
 
?>
