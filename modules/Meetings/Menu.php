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


global $mod_strings,$app_strings;
if(ACLController::checkAccess('Meetings', 'edit', true))$module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_NEW_MEETING'],"CreateMeetings");
if(ACLController::checkAccess('Meetings', 'list', true))$module_menu[]=Array("index.php?module=Meetings&action=index&return_module=Meetings&return_action=DetailView", $mod_strings['LNK_MEETING_LIST'],"Meetings");
if(ACLController::checkAccess('Meetings', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Meetings&return_module=Meetings&return_action=index", $mod_strings['LNK_IMPORT_MEETINGS'],"Import", 'Meetings');



?>
