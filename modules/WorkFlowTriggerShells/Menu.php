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
	Array("index.php?module=WorkFlow&action=EditView&return_module=WorkFlow&return_action=DetailView", $mod_strings['LNK_NEW_WORKFLOW'],"CreateWorkflowDefinition"),
	Array("index.php?module=WorkFlow&action=index&return_module=WorkFlow&return_action=DetailView", $mod_strings['LNK_WORKFLOW'],"WorkFlow"),
	Array("index.php?module=WorkFlow&action=WorkFlowListView&return_module=WorkFlow&return_action=index", $mod_strings['LBL_ALERT_TEMPLATES'],"AlertEmailTemplates"),

	);

?>
