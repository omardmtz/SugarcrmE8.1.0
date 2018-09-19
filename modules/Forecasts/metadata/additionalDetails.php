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

function additionaldetailsforecastopportunities($fields) {
    global $current_language, $app_list_strings;
    $mod_strings = return_module_language($current_language, 'Opportunities');
    $overlib_string = '';
    
    if(!empty($fields['ACCOUNT_NAME'])) $overlib_string .= '<b>'. $mod_strings['LBL_ACCOUNT_NAME'] . '</b> ' . $fields['ACCOUNT_NAME'] . '<br>';
    if(!empty($fields['PROBABILITY'])) $overlib_string .= '<b>'. $mod_strings['LBL_OW_PROBABILITY'] . '</b> ' . $fields['PROBABILITY'] . '<br>';
    if(!empty($fields['NEXT_STEP'])) $overlib_string .= '<b>'. $mod_strings['LBL_NEXT_STEP'] . '</b> ' . $fields['NEXT_STEP'] . '<br>';
    if(!empty($fields['OPPORTUNITY_TYPE'])) $overlib_string .= '<b>'. $mod_strings['LBL_TYPE'] . '</b> ' . $app_list_strings['opportunity_type_dom'][$fields['OPPORTUNITY_TYPE']] . '<br>';

    if(!empty($fields['DESCRIPTION'])) {
        $overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ';
        $overlib_string .= substr($fields['DESCRIPTION'], 0, 300);
        if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
    }   
    return array('fieldToAddTo' => 'NAME', 
                 'string' => $overlib_string, 
                 'editLink' => "index.php?action=EditView&module=Opportunities&return_module=Forecasts&return_action=index&record={$fields['ID']}", 
                 'viewLink' => "index.php?action=DetailView&module=Opportunities&return_module=Forecasts&return_action=index&record={$fields['ID']}");
}
 
function additionaldetailsforecastdirectreports($fields) {

    global $current_language;
    $mod_strings = return_module_language($current_language, 'Forecasts');
    $overlib_string = '';

    if(isset($fields['OPP_COUNT'])) $overlib_string .= '<b>'. $mod_strings['LBL_FDR_OPPORTUNITIES'] . '</b> ' . $fields['OPP_COUNT'] . '<br>';
    if(isset($fields['OPP_WEIGH_VALUE'])) $overlib_string .= '<b>'. $mod_strings['LBL_FDR_WEIGH'] . '</b> ' . $fields['OPP_WEIGH_VALUE'] . '<br>';
    if(isset($fields['DATE_ENTERED'])) $overlib_string .= '<b>'. $mod_strings['LBL_FDR_DATE_COMMIT'] . '</b> ' . $fields['DATE_ENTERED'] . '<br>';

    return array('fieldToAddTo' => 'USER_NAME', 
                 'string' => $overlib_string, 
                 'editLink' => false, 
                 'viewLink' => false);
}
 
 ?>
 
