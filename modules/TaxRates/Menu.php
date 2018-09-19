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

 * Description:  
 ********************************************************************************/

global $mod_strings, $app_strings;
$module_menu = Array();
if(is_admin_for_module($GLOBALS['current_user'],'Products'))$module_menu[]= Array("index.php?module=Shippers&action=EditView&return_module=Shippers&return_action=DetailView", $mod_strings['LNK_NEW_SHIPPER'],"Shippers");
                                                            $module_menu[]= Array("index.php?module=TaxRates&action=EditView&return_module=TaxRates&return_action=DetailView", $mod_strings['LNK_NEW_TAXRATE'],"TaxRates");if(ACLController::checkAccess('TaxRates', 'import', true))  $module_menu[] =Array("index.php?module=Import&action=Step1&import_module=TaxRates&return_module=TaxRates&return_action=index", $mod_strings['LNK_IMPORT_TAXRATES'],"Import", 'Contacts');

?>