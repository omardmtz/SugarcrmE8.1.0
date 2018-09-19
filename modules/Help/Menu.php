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
	Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=DetailView", $mod_strings['LNK_NEW_CONTACT'],"Contacts"),
	Array("index.php?module=Accounts&action=EditView&return_module=Accounts&return_action=DetailView", $mod_strings['LNK_NEW_ACCOUNT'],"Accounts"),
	Array("index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=DetailView", $mod_strings['LNK_NEW_OPPORTUNITY'],"Opportunities"),
	Array("index.php?module=Cases&action=EditView&return_module=Cases&return_action=DetailView", $mod_strings['LNK_NEW_CASE'],"Cases"),
	Array("index.php?module=Notes&action=EditView&return_module=Notes&return_action=DetailView", $mod_strings['LNK_NEW_NOTE'],"Notes"),
	Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView", $mod_strings['LNK_NEW_CALL'],"Calls"),
	Array("index.php?module=Emails&action=Compose", $mod_strings['LNK_NEW_EMAIL'],"Emails"),
	Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_NEW_MEETING'],"Meetings"),
	Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_NEW_TASK'],"Tasks")
	);

?>