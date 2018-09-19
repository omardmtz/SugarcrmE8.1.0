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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$entry_point_registry = array(
	'emailImage' => array('file' => 'modules/EmailMan/EmailImage.php', 'auth' => false),
	'download' => array('file' => 'download.php', 'auth' => true),
	'export' => array('file' => 'export.php', 'auth' => true),
	'export_dataset' => array('file' => 'export_dataset.php', 'auth' => true),
	'Changenewpassword' => array('file' => 'modules/Users/Changenewpassword.php', 'auth' => false),
	'GeneratePassword' => array('file' => 'modules/Users/GeneratePassword.php', 'auth' => false),
	'vCard' => array('file' => 'vCard.php', 'auth' => true),
	'pdf' => array('file' => 'pdf.php', 'auth' => true),
	'minify' => array('file' => 'jssource/minify.php', 'auth' => true),
    'json_server' => array('file' => 'json_server.php', 'auth' => true),
    'get_url' => array('file' => 'get_url.php', 'auth' => true),
	'HandleAjaxCall' => array('file' => 'HandleAjaxCall.php', 'auth' => true),
	'TreeData' => array('file' => 'TreeData.php', 'auth' => true),
    'image' => array('file' => 'modules/Campaigns/image.php', 'auth' => false),
    'campaign_trackerv2' => array('file' => 'modules/Campaigns/Tracker.php', 'auth' => false),
    'WebToLeadCapture' => array('file' => 'modules/Campaigns/WebToLeadCapture.php', 'auth' => false),
    'removeme' => array('file' => 'modules/Campaigns/RemoveMe.php', 'auth' => false),
    'acceptDecline' => array('file' => 'modules/Contacts/AcceptDecline.php', 'auth' => false),
    'leadCapture' => array('file' => 'modules/Leads/Capture.php', 'auth' => false),
    'process_queue' => array('file' => 'process_queue.php', 'auth' => true),
	'process_workflow' => array('file' => 'process_workflow.php', 'auth' => true),
	'zipatcher' => array('file' => 'zipatcher.php', 'auth' => true),
    'mm_get_doc' => array('file' => 'modules/MailMerge/get_doc.php', 'auth' => true),
    'getImage' => array('file' => 'include/SugarTheme/getImage.php', 'auth' => false),
    'GenerateQuickComposeFrame' => array('file' => 'modules/Emails/GenerateQuickComposeFrame.php', 'auth' => true),
    'DetailUserRole' => array('file' => 'modules/ACLRoles/DetailUserRole.php', 'auth' => true),
    'getYUIComboFile' => array('file' => 'include/javascript/getYUIComboFile.php', 'auth' => false),
    'UploadFileCheck' => array('file' => 'modules/Configurator/UploadFileCheck.php', 'auth' => true),
    'SAML'=>  array('file' => 'modules/Users/authentication/SAMLAuthenticate/index.php', 'auth' => false),
    'tinymce_spellchecker_rpc' => array('file' => 'include/javascript/tiny_mce/plugins/spellchecker/rpc.php', 'auth' => true),
    'jslang'=>  array('file' => 'include/language/getJSLanguage.php', 'auth' => true),
);
