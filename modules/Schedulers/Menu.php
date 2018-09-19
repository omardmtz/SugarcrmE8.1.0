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
global $current_user;
//array("index.php?module=ProductTemplates&action=EditView&return_module=ProductTemplates&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT'],"Products"),
if(is_admin($current_user)){
$module_menu = array( 
	array("index.php?module=Schedulers&action=index", $mod_strings['LNK_LIST_SCHEDULER'],"Schedulers"),
	array("index.php?module=Schedulers&action=EditView", $mod_strings['LNK_NEW_SCHEDULER'],"CreateScheduler"),
	
);
}
?>
