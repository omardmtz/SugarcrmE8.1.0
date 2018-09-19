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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $mod_strings;
$module_menu = Array();
$module_menu[]= array("index.php?module=InboundEmail&action=EditView", $mod_strings['LNK_LIST_CREATE_NEW_GROUP'],"CreateMailboxes");
$module_menu[]= array("index.php?module=InboundEmail&action=EditView&mailbox_type=bounce", $mod_strings['LNK_LIST_CREATE_NEW_BOUNCE'],"CreateBounceboxes");
$module_menu[]= array("index.php?module=InboundEmail&action=index", $mod_strings['LNK_LIST_MAILBOXES'],"InboundEmail");

if(is_admin($GLOBALS['current_user']))$module_menu[]= array("index.php?module=Schedulers&action=index", $mod_strings['LNK_LIST_SCHEDULER'],"Schedulers");
	//array("index.php?module=Queues&action=Seed", $mod_strings['LNK_SEED_QUEUES'],"CustomQueries"),
?>
