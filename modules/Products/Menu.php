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

if(ACLController::checkAccess('Products', 'edit', true))$module_menu[] = Array("index.php?module=Products&action=EditView&return_module=Products&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT'],"CreateProducts");
if(ACLController::checkAccess('Products', 'list', true))$module_menu[] =Array("index.php?module=Products&action=index&return_module=Products&return_action=DetailView", $mod_strings['LNK_PRODUCT_LIST'],"Price_List");
if(ACLController::checkAccess('Products', 'import', true))$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=Products&return_module=Products&return_action=index", $mod_strings['LNK_IMPORT_PRODUCTS'],"Import", 'Products');


?>
