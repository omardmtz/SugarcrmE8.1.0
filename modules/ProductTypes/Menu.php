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

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=ProductTemplates&action=EditView&return_module=ProductTemplates&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT'],"Products"),
	Array("index.php?module=ProductTemplates&action=index&return_module=ProductTemplates&return_action=DetailView", $mod_strings['LNK_PRODUCT_LIST'],"Price_List"),
	Array("index.php?module=Manufacturers&action=EditView&return_module=Manufacturers&return_action=DetailView", $mod_strings['LNK_NEW_MANUFACTURER'],"Manufacturers"),
	Array("index.php?module=ProductCategories&action=EditView&return_module=ProductCategories&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT_CATEGORY'],"Product_Categories"),
	Array("index.php?module=ProductTypes&action=EditView&return_module=ProductTypes&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT_TYPE'],"Product_Types"),
    Array("index.php?module=Import&action=Step1&import_module=ProductTypes&return_module=ProductTypes&return_action=index", $mod_strings['LNK_IMPORT_PRODUCT_TYPES'],"Import"),
	);

?>
