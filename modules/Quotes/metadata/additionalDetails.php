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
 


function additionalDetailsQuote($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Quotes');
	}
		
	$overlib_string = '';
	
	if(!empty($fields['PURCHASE_ORDER_NUM'])) $overlib_string .= '<b>'. $mod_strings['LBL_PURCHASE_ORDER_NUM'] . '</b> ' . $fields['PURCHASE_ORDER_NUM'] . '<br>';
	if(!empty($fields['ORIGINAL_PO_DATE'])) $overlib_string .= '<b>'. $mod_strings['LBL_ORIGINAL_PO_DATE'] . '</b> ' . $fields['ORIGINAL_PO_DATE'] . '<br>';
	
	if(!empty($fields['BILLING_ADDRESS_STREET']) || !empty($fields['BILLING_ADDRESS_CITY']) ||
		!empty($fields['BILLING_ADDRESS_STATE']) || !empty($fields['BILLING_ADDRESS_POSTALCODE']) ||
		!empty($fields['BILLING_ADDRESS_COUNTRY']))
			$overlib_string .= '<b>' . $mod_strings['LBL_BILLING_ADDRESS_STREET'] . '</b><br>';
	if(!empty($fields['BILLING_ADDRESS_STREET'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_CITY'])) $overlib_string .= $fields['BILLING_ADDRESS_CITY'] . ', ';
	if(!empty($fields['BILLING_ADDRESS_STATE'])) $overlib_string .= $fields['BILLING_ADDRESS_STATE'] . ' ';
	if(!empty($fields['BILLING_ADDRESS_POSTALCODE'])) $overlib_string .= $fields['BILLING_ADDRESS_POSTALCODE'] . ' ';
	if(!empty($fields['BILLING_ADDRESS_COUNTRY'])) $overlib_string .= $fields['BILLING_ADDRESS_COUNTRY'] . '<br>';
	if(strlen($overlib_string) > 0 && !(strrpos($overlib_string, '<br>') == strlen($overlib_string) - 4)) 
		$overlib_string .= '<br>';  
	
	if(!empty($fields['SHIPPING_ADDRESS_STREET']) || !empty($fields['SHIPPING_ADDRESS_CITY']) ||
		!empty($fields['SHIPPING_ADDRESS_STATE']) || !empty($fields['SHIPPING_ADDRESS_POSTALCODE']) ||
		!empty($fields['SHIPPING_ADDRESS_COUNTRY']))
			$overlib_string .= '<b>' . $mod_strings['LBL_SHIPPING_ADDRESS_STREET'] . '</b><br>';
	if(!empty($fields['SHIPPING_ADDRESS_STREET'])) $overlib_string .= $fields['SHIPPING_ADDRESS_STREET'] . '<br>';
	if(!empty($fields['SHIPPING_ADDRESS_CITY'])) $overlib_string .= $fields['SHIPPING_ADDRESS_CITY'] . ', ';
	if(!empty($fields['SHIPPING_ADDRESS_STATE'])) $overlib_string .= $fields['SHIPPING_ADDRESS_STATE'] . ' ';
	if(!empty($fields['SHIPPING_ADDRESS_POSTALCODE'])) $overlib_string .= $fields['SHIPPING_ADDRESS_POSTALCODE'] . ' ';
	if(!empty($fields['SHIPPING_ADDRESS_COUNTRY'])) $overlib_string .= $fields['SHIPPING_ADDRESS_COUNTRY'] . '<br>';
	if(strlen($overlib_string) > 0 && !(strrpos($overlib_string, '<br>') == strlen($overlib_string) - 4)) 
		$overlib_string .= '<br>';  
		
	$overlib_string .= '<table cellpadding=1 cellspacing=0 width=100%>';
	if(!empty($fields['SUBTOTAL'])) $overlib_string .= '<tr><td width=1%><b>'. $mod_strings['LBL_SUBTOTAL'] . '</b></td><td align=right>' . $fields['SUBTOTAL'] . '</td></tr>';
    if(!empty($fields['DISCOUNT'])) $overlib_string .= '<tr><td width=1%><b>'. $mod_strings['LBL_DISCOUNT_TOTAL'] . '</b></td><td align=right>' . $fields['DISCOUNT'] . '</td></tr>';	
    if(!empty($fields['NEW_SUBTOTAL'])) $overlib_string .= '<tr><td width=1%><b>'. $mod_strings['LBL_NEW_SUBTOTAL'] . '</b></td><td align=right>' . $fields['NEW_SUBTOTAL'] . '</td></tr>';   
    if(!empty($fields['TAX'])) $overlib_string .= '<tr><td width=1%><b>'. $mod_strings['LBL_TAX'] . '</b></td><td align=right>' . $fields['TAX'] . '</td></tr>';
	if(!empty($fields['SHIPPING'])) $overlib_string .= '<tr><td width=1%><b>'. $mod_strings['LBL_SHIPPING'] . '</b></td><td align=right>' . $fields['SHIPPING'] . '</td></tr>';
	if(!empty($fields['TOTAL'])) $overlib_string .= '<tr><td width=1%><b>'. $mod_strings['LBL_TOTAL'] . '</b></td><td align=right>' . $fields['TOTAL'] . '</td></tr><br>';
	$overlib_string .= '</table>';
		
	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Quotes&return_module=Quotes&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Quotes&return_module=Quotes&record={$fields['ID']}");
}
 
 ?>
 
 