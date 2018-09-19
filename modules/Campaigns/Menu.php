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
if(ACLController::checkAccess('Campaigns', 'edit', true))
	$module_menu[] = array(
		"index.php?module=Campaigns&action=WizardHome&return_module=Campaigns&return_action=index", 
		$mod_strings['LNL_NEW_CAMPAIGN_WIZARD'],"CampaignsWizard"
	);
if(ACLController::checkAccess('Campaigns', 'edit', true))
	$module_menu[]=	array(
		"index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=index", 
		$mod_strings['LNK_NEW_CAMPAIGN'],"CreateCampaigns"
	);
if(ACLController::checkAccess('Campaigns', 'list', true))
	$module_menu[]=	array(
		"index.php?module=Campaigns&action=index&return_module=Campaigns&return_action=index", 
		$mod_strings['LNK_CAMPAIGN_LIST'],"Campaigns"
	);
if(ACLController::checkAccess('Campaigns', 'list', true))
	$module_menu[]= array(
		"index.php?module=Campaigns&action=newsletterlist&return_module=Campaigns&return_action=index", 
		$mod_strings['LBL_NEWSLETTERS'], "Newsletters"
	);
if(ACLController::checkAccess('EmailTemplates', 'edit', true))
	$module_menu[] = array(
		"index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView",
		$mod_strings['LNK_NEW_EMAIL_TEMPLATE'],"CreateEmails","Emails"
	);
if(ACLController::checkAccess('EmailTemplates', 'list', true))
	$module_menu[] = array(
		"index.php?module=EmailTemplates&action=index",
		$mod_strings['LNK_EMAIL_TEMPLATE_LIST'],"EmailFolder", 'Emails'
	);
if (is_admin($GLOBALS['current_user']) || is_admin_for_module($GLOBALS['current_user'],'Campaigns'))
	$module_menu[] = array(
		"index.php?module=Campaigns&action=WizardEmailSetup&return_module=Campaigns&return_action=index",
		$mod_strings['LBL_EMAIL_SETUP_WIZARD'],"EmailSetupWizard"
	);
if(ACLController::checkAccess('Campaigns', 'edit', true))
	$module_menu[] = array(
		"index.php?module=Campaigns&action=CampaignDiagnostic&return_module=Campaigns&return_action=index",
		$mod_strings['LBL_DIAGNOSTIC_WIZARD'],"EmailDiagnostic"
	);
if(ACLController::checkAccess('Campaigns', 'edit', true))
	$module_menu[] = array(
		"index.php?module=Campaigns&action=WebToLeadCreation&return_module=Campaigns&return_action=index",
		$mod_strings['LBL_WEB_TO_LEAD'],"CreateWebToLeadForm"
	);