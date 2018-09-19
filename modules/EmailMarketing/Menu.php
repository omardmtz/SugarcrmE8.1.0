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

global $mod_strings, $app_strings;
$module_menu = Array(
	Array("index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=index", $mod_strings['LNK_NEW_CAMPAIGN'],"CreateCampaigns"),
	Array("index.php?module=Campaigns&action=index&return_module=Campaigns&return_action=index", $mod_strings['LNK_CAMPAIGN_LIST'],"Campaigns"),
	Array("index.php?module=ProspectLists&action=EditView&return_module=ProspectLists&return_action=DetailView", $mod_strings['LNK_NEW_PROSPECT_LIST'],"CreateProspectLists"),
	Array("index.php?module=ProspectLists&action=index&return_module=ProspectLists&return_action=index", $mod_strings['LNK_PROSPECT_LIST_LIST'],"ProspectLists"),
	Array("index.php?module=Prospects&action=EditView&return_module=Prospects&return_action=DetailView", $mod_strings['LNK_NEW_PROSPECT'],"CreateProspects"),
	Array("index.php?module=Prospects&action=index&return_module=Prospects&return_action=index", $mod_strings['LNK_PROSPECT_LIST'],"Prospects"),
	
	
	);
?>