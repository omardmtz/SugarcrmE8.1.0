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
/*********************************************************************************

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings, $sugar_config;
$module_menu = Array();
if(ACLController::checkAccess('Opportunities','edit',true)){
	$module_menu[]=	Array("index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=DetailView", $mod_strings['LNK_NEW_OPPORTUNITY'],"CreateOpportunities");
}
if(ACLController::checkAccess('Opportunities','list',true)){
	$module_menu[]=	Array("index.php?module=Opportunities&action=index&return_module=Opportunities&return_action=DetailView", $mod_strings['LNK_OPPORTUNITY_LIST'],"Opportunities");
}

if (ACLController::checkAccess('Opportunities', 'view', true)) {
    $module_menu[] = array(
        'index.php?module=Reports&action=index&view=opportunities',
        $mod_strings['LNK_OPPORTUNITY_REPORTS'],
        'OpportunityReports',
        'Opportunities',
    );
}

if(ACLController::checkAccess('Opportunities','import',true)){
	$module_menu[]=  Array("index.php?module=Import&action=Step1&import_module=Opportunities&return_module=Opportunities&return_action=index", $mod_strings['LNK_IMPORT_OPPORTUNITIES'],"Import");
}
