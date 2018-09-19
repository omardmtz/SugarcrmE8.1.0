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

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=Teams&action=EditView&return_module=Teams&return_action=DetailView", $mod_strings['LNK_NEW_TEAM'], "CreateTeams"),
	Array("index.php?module=Teams&action=index", $mod_strings['LNK_LIST_TEAM'], "Teams"),
	Array("index.php?module=TeamNotices&action=index", $mod_strings['LNK_LIST_TEAMNOTICE'], "Teams"),
	Array("index.php?module=TeamNotices&action=EditView", $mod_strings['LNK_NEW_TEAM_NOTICE'], "Teams")
);

?>