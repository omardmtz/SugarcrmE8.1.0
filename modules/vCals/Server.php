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

// SugarCRM free/busy server
// put and get free/busy information for sugarcrm users in vCalendar format.
// Uses WebDAV for HTTP PUT and GET methods of access
// REQUIRED PHP packages:
// 1. PEAR
//
// Saves PUTs as Freebusy SugarBeans
//
// documentation on Free/Busy from Microsoft:
// http://support.microsoft.com/kb/196484
//
// other docs:
// http://www.windowsitpro.com/MicrosoftExchangeOutlook/Article/ArticleID/7697/7697.html
//
// excerpt:
// You must install the Microsoft Internet Explorer (IE) Web Publishing Wizard to get
// the functionality you need to publish Internet free/busy data to a server or the Web.
// You can install this wizard from Control Panel, Add/Remove Programs, Microsoft Internet
// Explorer, Web Publishing Wizard. For every user, you must configure the path and filename
// where you want Outlook to store free/busy information. You configure this location on the
// Free/Busy Options dialog box you see in Screen 2. You must initiate publishing manually by
// using Tools, Send/Receive, Free/Busy Information in Outlook.
//
// To access a non-Exchange Server user's free/busy information, you must configure the
// appropriate path on each contact's Details tab. For example, you enter
// "http://servername/sugarcrm/index.php?entryPoint=vcal_server/type=vfb&source=outlook&email=myemail@servername.com".
// If you don't configure this information correctly, the client software looks up the entry
// in the Search at this URL window on the Free/Busy Options dialog box.
//
// Setup for: Microsoft Outlook XP
// In Tools > Options > Calendar Options > Free/Busy
//
// Global search path:
// %USERNAME% and %SERVER% are Outlook replacement variables to construct the email address:
// http://servername/sugarcrm/index.php?entryPoint=vcal_server/type=vfb&source=outlook&email=%NAME%@%SERVER%
// or contact by contact by editing the details and entering its Free/Busy URL:
// http://servername/sugarcrm/index.php?entryPoint=vcal_server/type=vfb&source=outlook&email=user@email.com
// or
// http://servername/sugarcrm/index.php?entryPoint=vcal_server/type=vfb&source=outlook&user_name=user_name
// or:
// http://servername/sugarcrm/index.php?entryPoint=vcal_server/type=vfb&source=outlook&user_id=user_id
	$server = new HTTP_WebDAV_Server_vCal();
	$server->ServeRequest();
	sugar_cleanup();
?>
