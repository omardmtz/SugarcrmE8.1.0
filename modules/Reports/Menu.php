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
global $current_language;
$ent_mod_strings = return_module_language($current_language, "ReportMaker");
$module_menu = Array(

    Array("index.php?module=Reports&report_module=&action=index&page=report&Create+Custom+Report=Create+Custom+Report", $mod_strings['LBL_CREATE_REPORT'],"CreateReport", 'Reports'),
    //Array("index.php?module=Reports&favorite=1&action=index", $mod_strings['LBL_FAVORITE_REPORTS'], "FavoriteReports", 'Reports'),
    Array("index.php?module=Reports&action=index", $mod_strings['LBL_ALL_REPORTS'],"Reports", 'Reports'),
    /*
    Array("index.php?module=Reports&action=ActivitiesReports", $mod_strings['LBL_ACTIVITIES_REPORTS'],"Reports", 'Reports'),
	Array("index.php?module=Reports&action=index&report_module=Accounts&query=true", $mod_strings['LBL_ACCOUNT_REPORTS'],"AccountReports", 'Accounts'),
	Array("index.php?module=Reports&action=index&report_module=Contacts&query=true", $mod_strings['LBL_CONTACT_REPORTS'],"ContactReports", 'Contacts'),
	Array("index.php?module=Reports&action=index&report_module=Leads&query=true", $mod_strings['LBL_LEAD_REPORTS'],"LeadReports", 'Leads'),
	Array("index.php?module=Reports&action=index&report_module=Opportunities&query=true", $mod_strings['LBL_OPPORTUNITY_REPORTS'],"OpportunityReports", 'Opportunities'),
	Array("index.php?module=Reports&action=index&report_module=Quotes&query=true", $mod_strings['LBL_QUOTE_REPORTS'],"QuoteReports", 'Quotes'),
	Array("index.php?module=Reports&action=index&report_module=Cases&query=true", $mod_strings['LBL_CASE_REPORTS'],"CaseReports", 'Cases'),
	Array("index.php?module=Reports&action=index&report_module=Bugs&query=true", $mod_strings['LBL_BUG_REPORTS'],"BugReports", 'Bugs'),
	Array("index.php?module=Reports&action=index&report_module=Calls&query=true", $mod_strings['LBL_CALL_REPORTS'],"CallReports"),
	Array("index.php?module=Reports&action=index&report_module=Meetings&query=true", $mod_strings['LBL_MEETING_REPORTS'],"MeetingReports"),
	Array("index.php?module=Reports&action=index&report_module=Tasks&query=true", $mod_strings['LBL_TASK_REPORTS'],"TaskReports", 'Tasks'),
	Array("index.php?module=Reports&action=index&report_module=Emails&query=true", $mod_strings['LBL_EMAIL_REPORTS'],"EmailReports", 'Emails'),
	Array("index.php?module=Reports&action=index&report_module=Forecasts&query=true", $mod_strings['LBL_FORECAST_REPORTS'],"ForecastReports", 'Forecasts'),
	Array("index.php?module=Reports&action=index&report_module=ProjectTask&query=true", $mod_strings['LBL_PROJECT_TASK_REPORTS'],"TaskReports", 'Project'),
	Array("index.php?module=Reports&action=index&report_module=Prospects&query=true", $mod_strings['LBL_PROSPECT_REPORTS'],"TaskReports", 'Prospects'),
	Array("index.php?module=Reports&action=index&report_module=Contracts&query=true", $mod_strings['LBL_CONTRACT_REPORTS'],"ContractReports", 'Contracts'),
	*/

	Array("index.php?module=ReportMaker&action=index&return_module=ReportMaker&return_action=index", $ent_mod_strings['LNK_ADVANCED_REPORTING'],"ReportMaker"),
	);
	
if(!(ACLController::checkAccess('Reports', 'edit', true)))
{
    $module_menu = Array(
    Array("index.php?module=Reports&favorite=1&action=index", $mod_strings['LBL_FAVORITE_REPORTS'], "FavoriteReports", 'Reports'),
    Array("index.php?module=Reports&action=index", $mod_strings['LBL_ALL_REPORTS'],"Reports", 'Reports'),
    Array("index.php?module=ReportMaker&action=index&return_module=ReportMaker&return_action=index", $ent_mod_strings['LNK_ADVANCED_REPORTING'],"ReportMaker",'Reports'),
    );
}

?>
