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

$moduleList = array();
// this list defines the modules shown in the top tab list of the app
//the order of this list is the default order displayed - do not change the order unless it is on purpose
$moduleList[] = 'Home';
$moduleList[] = 'Calendar';
$moduleList[] = 'Calls';
$moduleList[] = 'Meetings';
$moduleList[] = 'Tasks';
$moduleList[] = 'Notes';
$moduleList[] = 'Reports';
$moduleList[] = 'Leads';
$moduleList[] = 'Contacts';
$moduleList[] = 'Accounts';
$moduleList[] = 'Opportunities';

$moduleList[] = 'Emails';
$moduleList[] = 'Campaigns';
$moduleList[] = 'Prospects';
$moduleList[] = 'ProspectLists';

$moduleList[] = 'Quotes';
$moduleList[] = 'Products';
$moduleList[] = 'Forecasts';
$moduleList[] = 'Contracts';
$moduleList[] = 'pmse_Project';
$moduleList[] = 'pmse_Inbox';
$moduleList[] = 'pmse_Business_Rules';
$moduleList[] = 'pmse_Emails_Templates';

$moduleList[] = 'Documents';
$moduleList[] = 'Cases';
$moduleList[] = 'Project';
$moduleList[] = 'Bugs';
$moduleList[] = 'OutboundEmail';
$moduleList[] = 'DataPrivacy';
// this list defines all of the module names and bean names in the app
// to create a new module's bean class, add the bean definition here
$beanList = array();
//ACL Objects
$beanList['ACLRoles']       = 'ACLRole';
$beanList['ACLRoleSets']    = 'ACLRoleSet';
$beanList['ACLActions']     = 'ACLAction';
$beanList['ACLFields']       = 'ACLField';
//END ACL OBJECTS
$beanList['Leads']          = 'Lead';
$beanList['Cases']          = 'aCase';
$beanList['Bugs']           = 'Bug';
$beanList['ProspectLists']      = 'ProspectList';
$beanList['Prospects']  = 'Prospect';
$beanList['Project']            = 'Project';
$beanList['ProjectTask']            = 'ProjectTask';
$beanList['Campaigns']          = 'Campaign';
$beanList['EmailMarketing']  = 'EmailMarketing';
$beanList['CampaignLog']        = 'CampaignLog';
$beanList['CampaignTrackers']   = 'CampaignTracker';
$beanList['Releases']       = 'Release';
$beanList['Groups'] = 'Group';
$beanList['EmailMan'] = 'EmailMan';
$beanList['Schedulers']  = 'Scheduler';
$beanList['SchedulersJobs']  = 'SchedulersJob';
$beanList['Contacts']       = 'Contact';
$beanList['Accounts']       = 'Account';
$beanList['DynamicFields']  = 'DynamicField';
$beanList['EditCustomFields']   = 'FieldsMetaData';
$beanList['Opportunities']  = 'Opportunity';

$beanList['EmailTemplates']     = 'EmailTemplate';
$beanList['UserSignatures'] = 'UserSignature';
$beanList['Notes']          = 'Note';
$beanList['Calls']          = 'Call';
$beanList['Emails']         = 'Email';
$beanList['Meetings']       = 'Meeting';
$beanList['Tasks']          = 'Task';
$beanList['Users']          = 'User';
$beanList['Currencies']     = 'Currency';
$beanList['Trackers']       = 'Tracker';
$beanList['Connectors']     = 'Connectors';
$beanList['TrackerSessions']= 'TrackerSession';
$beanList['TrackerPerfs']   = 'TrackerPerf';
$beanList['TrackerQueries'] = 'TrackerQuery';
$beanList['Import_1']         = 'ImportMap';
$beanList['Import_2']       = 'UsersLastImport';
$beanList['Versions']       = 'Version';
$beanList['Administration'] = 'Administration';
$beanList['vCals']          = 'vCal';
$beanList['CustomFields']       = 'CustomFields';


$beanList['Documents']  = 'Document';
$beanList['DocumentRevisions']  = 'DocumentRevision';
$beanList['Roles']  = 'Role';

$beanList['Audit']  = 'Audit';

$beanList['Styleguide'] = 'Styleguide';
// deferred
//$beanList['Queues'] = 'Queue';

$beanList['InboundEmail'] = 'InboundEmail';


$beanList['SavedSearch']            = 'SavedSearch';
$beanList['UserPreferences']        = 'UserPreference';
$beanList['MergeRecords'] = 'MergeRecord';
$beanList['EmailAddresses'] = 'EmailAddress';
$beanList['EmailText'] = 'EmailText';
$beanList['Relationships'] = 'Relationship';
$beanList['Employees']      = 'Employee';
$beanList['Reports']        = 'SavedReport';
$beanList['Reports_1']      = 'SavedReport';
$beanList['Teams']          = 'Team';
$beanList['TeamMemberships']            = 'TeamMembership';
$beanList['TeamSets']            = 'TeamSet';
$beanList['TeamSetModules']            = 'TeamSetModule';
$beanList['Quotes']         = 'Quote';
$beanList['RevenueLineItems'] = 'RevenueLineItem';
$beanList['Products']       = 'Product';
$beanList['ProductBundles']     = 'ProductBundle';
$beanList['ProductBundleNotes'] = 'ProductBundleNote';
$beanList['ProductTemplates']= 'ProductTemplate';
$beanList['ProductTypes']   = 'ProductType';
$beanList['ProductCategories']= 'ProductCategory';
$beanList['Manufacturers']  = 'Manufacturer';
$beanList['Shippers']       = 'Shipper';
$beanList['TaxRates']       = 'TaxRate';
$beanList['TeamNotices']        = 'TeamNotice';
$beanList['TimePeriods']    = 'TimePeriod';
$beanList['AnnualTimePeriods'] = 'AnnualTimePeriod';
$beanList['QuarterTimePeriods']    = 'QuarterTimePeriod';
$beanList['Quarter544TimePeriods']    = 'Quarter544TimePeriod';
$beanList['Quarter445TimePeriods']    = 'Quarter445TimePeriod';
$beanList['Quarter454TimePeriods']    = 'Quarter454TimePeriod';
$beanList['MonthTimePeriods']    = 'MonthTimePeriod';
$beanList['Forecasts']  = 'Forecast';
$beanList['ForecastWorksheets']  = 'ForecastWorksheet';
$beanList['ForecastManagerWorksheets']  = 'ForecastManagerWorksheet';
$beanList['ForecastOpportunities']  = 'ForecastOpportunities';
$beanList['ForecastDirectReports'] = 'ForecastDirectReports';
$beanList['Quotas']     = 'Quota';
$beanList['WorkFlow']  = 'WorkFlow';
$beanList['WorkFlowTriggerShells']  = 'WorkFlowTriggerShell';
$beanList['WorkFlowAlertShells']  = 'WorkFlowAlertShell';
$beanList['WorkFlowAlerts']  = 'WorkFlowAlert';
$beanList['WorkFlowActionShells']  = 'WorkFlowActionShell';
$beanList['WorkFlowActions']  = 'WorkFlowAction';
$beanList['Expressions']  = 'Expression';
$beanList['Contracts']  = 'Contract';
$beanList['ContractTypes']  = 'ContractType';
$beanList['Holidays'] = 'Holiday';
$beanList['SessionManager'] = 'SessionManager';

$beanList['CustomQueries']  = 'CustomQuery';
$beanList['DataSets']  = 'DataSet';
$beanList['DataSet_Attribute']  = 'DataSet_Attribute';
$beanList['ReportMaker']  = 'ReportMaker';
$beanList['pmse_Project'] = 'pmse_Project';
$beanList['pmse_Inbox'] = 'pmse_Inbox';
$beanList['pmse_Business_Rules'] = 'pmse_Business_Rules';
$beanList['pmse_Emails_Templates'] = 'pmse_Emails_Templates';
$beanList['pmse_BpmnActivity'] = 'pmse_BpmnActivity';
$beanList['pmse_BpmnArtifact'] = 'pmse_BpmnArtifact';
$beanList['pmse_BpmnBound'] = 'pmse_BpmnBound';
$beanList['pmse_BpmnData'] = 'pmse_BpmnData';
$beanList['pmse_BpmnDiagram'] = 'pmse_BpmnDiagram';
$beanList['pmse_BpmnDocumentation'] = 'pmse_BpmnDocumentation';
$beanList['pmse_BpmnEvent'] = 'pmse_BpmnEvent';
$beanList['pmse_BpmnExtension'] = 'pmse_BpmnExtension';
$beanList['pmse_BpmnFlow'] = 'pmse_BpmnFlow';
$beanList['pmse_BpmnGateway'] = 'pmse_BpmnGateway';
$beanList['pmse_BpmnLane'] = 'pmse_BpmnLane';
$beanList['pmse_BpmnLaneset'] = 'pmse_BpmnLaneset';
$beanList['pmse_BpmnParticipant'] = 'pmse_BpmnParticipant';
$beanList['pmse_BpmnProcess'] = 'pmse_BpmnProcess';
$beanList['pmse_BpmFlow'] = 'pmse_BpmFlow';
$beanList['pmse_BpmThread'] = 'pmse_BpmThread';
$beanList['pmse_BpmNotes'] = 'pmse_BpmNotes';
$beanList['pmse_BpmRelatedDependency'] = 'pmse_BpmRelatedDependency';
$beanList['pmse_BpmActivityUser'] = 'pmse_BpmActivityUser';
$beanList['pmse_BpmEventDefinition'] = 'pmse_BpmEventDefinition';
$beanList['pmse_BpmGatewayDefinition'] = 'pmse_BpmGatewayDefinition';
$beanList['pmse_BpmActivityDefinition'] = 'pmse_BpmActivityDefinition';
$beanList['pmse_BpmActivityStep'] = 'pmse_BpmActivityStep';
$beanList['pmse_BpmFormAction'] = 'pmse_BpmFormAction';
$beanList['pmse_BpmDynaForm'] = 'pmse_BpmDynaForm';
$beanList['pmse_BpmProcessDefinition'] = 'pmse_BpmProcessDefinition';
$beanList['pmse_BpmConfig'] = 'pmse_BpmConfig';
$beanList['pmse_BpmGroup'] = 'pmse_BpmGroup';
$beanList['pmse_BpmGroupUser'] = 'pmse_BpmGroupUser';
$beanList['Empty'] = 'EmptyBean';
$beanList['UpgradeHistory'] = 'UpgradeHistory';
$beanList['OutboundEmail'] = 'OutboundEmail';
$beanList['EmailParticipants'] = 'EmailParticipant';
$beanList['DataPrivacy'] = 'DataPrivacy';
// this list defines all of the files that contain the SugarBean class definitions from $beanList
// to create a new module's bean class, add the file definition here
$beanFiles = array();

$beanFiles['ACLAction'] = 'modules/ACLActions/ACLAction.php';
$beanFiles['ACLRole'] = 'modules/ACLRoles/ACLRole.php';
$beanFiles['Relationship']  = 'modules/Relationships/Relationship.php';

// do not add any bean files before this point, otherwise the application may fail to install
$beanFiles['ACLRoleSet'] = 'modules/ACLRoles/ACLRoleSet.php';
$beanFiles['Lead']          = 'modules/Leads/Lead.php';
$beanFiles['aCase']         = 'modules/Cases/Case.php';
$beanFiles['Bug']           = 'modules/Bugs/Bug.php';
$beanFiles['Group'] = 'modules/Groups/Group.php';
$beanFiles['CampaignLog']  = 'modules/CampaignLog/CampaignLog.php';
$beanFiles['Project']           = 'modules/Project/Project.php';
$beanFiles['ProjectTask']           = 'modules/ProjectTask/ProjectTask.php';
$beanFiles['Campaign']          = 'modules/Campaigns/Campaign.php';
$beanFiles['ProspectList']      = 'modules/ProspectLists/ProspectList.php';
$beanFiles['Prospect']  = 'modules/Prospects/Prospect.php';

$beanFiles['EmailMarketing']          = 'modules/EmailMarketing/EmailMarketing.php';
$beanFiles['CampaignTracker']  = 'modules/CampaignTrackers/CampaignTracker.php';
$beanFiles['Release']           = 'modules/Releases/Release.php';
$beanFiles['EmailMan']          = 'modules/EmailMan/EmailMan.php';

$beanFiles['Scheduler']  = 'modules/Schedulers/Scheduler.php';
$beanFiles['SchedulersJob']  = 'modules/SchedulersJobs/SchedulersJob.php';
$beanFiles['Contact']       = 'modules/Contacts/Contact.php';
$beanFiles['Account']       = 'modules/Accounts/Account.php';
$beanFiles['Opportunity']   = 'modules/Opportunities/Opportunity.php';
$beanFiles['EmailTemplate']         = 'modules/EmailTemplates/EmailTemplate.php';
$beanFiles['UserSignature'] = 'modules/UserSignatures/UserSignature.php';
$beanFiles['Note']          = 'modules/Notes/Note.php';
$beanFiles['Call']          = 'modules/Calls/Call.php';
$beanFiles['Email']         = 'modules/Emails/Email.php';
$beanFiles['Meeting']       = 'modules/Meetings/Meeting.php';
$beanFiles['Task']          = 'modules/Tasks/Task.php';
$beanFiles['User']          = 'modules/Users/User.php';
$beanFiles['Employee']      = 'modules/Employees/Employee.php';
$beanFiles['Currency']          = 'modules/Currencies/Currency.php';
$beanFiles['Tracker']          = 'modules/Trackers/Tracker.php';
$beanFiles['TrackerPerf']      = 'modules/Trackers/TrackerPerf.php';
$beanFiles['TrackerSession']   = 'modules/Trackers/TrackerSession.php';
$beanFiles['TrackerQuery']     = 'modules/Trackers/TrackerQuery.php';
$beanFiles['ImportMap']     = 'modules/Import/maps/ImportMap.php';
$beanFiles['UsersLastImport']= 'modules/Import/UsersLastImport.php';
$beanFiles['Administration']= 'modules/Administration/Administration.php';
$beanFiles['UpgradeHistory']= 'modules/Administration/UpgradeHistory.php';
$beanFiles['vCal']          = 'modules/vCals/vCal.php';

$beanFiles['Version']           = 'modules/Versions/Version.php';

$beanFiles['Role']          = 'modules/Roles/Role.php';

$beanFiles['Document']  = 'modules/Documents/Document.php';
$beanFiles['DocumentRevision']  = 'modules/DocumentRevisions/DocumentRevision.php';
$beanFiles['FieldsMetaData']    = 'modules/DynamicFields/FieldsMetaData.php';
//$beanFiles['Audit']           = 'modules/Audit/Audit.php';

// deferred
//$beanFiles['Queue'] = 'modules/Queues/Queue.php';

$beanFiles['InboundEmail'] = 'modules/InboundEmail/InboundEmail.php';



$beanFiles['SavedSearch']  = 'modules/SavedSearch/SavedSearch.php';
$beanFiles['UserPreference']  = 'modules/UserPreferences/UserPreference.php';
$beanFiles['MergeRecord']  = 'modules/MergeRecords/MergeRecord.php';
$beanFiles['EmailAddress'] = 'modules/EmailAddresses/EmailAddress.php';
$beanFiles['EmailText'] = 'modules/EmailText/EmailText.php';
$beanFiles['SavedReport']   = 'modules/Reports/SavedReport.php';
$beanFiles['ACLField'] = 'modules/ACLFields/ACLField.php';
$beanFiles['Contract']  = 'modules/Contracts/Contract.php';
$beanFiles['Team']          = 'modules/Teams/Team.php';
$beanFiles['TeamMembership']            = 'modules/Teams/TeamMembership.php';
$beanFiles['TeamSet']            = 'modules/Teams/TeamSet.php';
$beanFiles['TeamSetModule']            = 'modules/Teams/TeamSetModule.php';
$beanFiles['TeamNotice']            = 'modules/TeamNotices/TeamNotice.php';
$beanFiles['ProductTemplate']= 'modules/ProductTemplates/ProductTemplate.php';
$beanFiles['ProductType']   = 'modules/ProductTypes/ProductType.php';
$beanFiles['ProductCategory']= 'modules/ProductCategories/ProductCategory.php';
$beanFiles['Manufacturer']  = 'modules/Manufacturers/Manufacturer.php';
$beanFiles['Quote']         = 'modules/Quotes/Quote.php';
$beanFiles['ProductBundleNote'] = 'modules/ProductBundleNotes/ProductBundleNote.php';
$beanFiles['Product']       = 'modules/Products/Product.php';
$beanFiles['ProductBundle']     = 'modules/ProductBundles/ProductBundle.php';
$beanFiles['RevenueLineItem'] = 'modules/RevenueLineItems/RevenueLineItem.php';
$beanFiles['Shipper']       = 'modules/Shippers/Shipper.php';
$beanFiles['TaxRate']       = 'modules/TaxRates/TaxRate.php';
$beanFiles['TimePeriod']        = 'modules/TimePeriods/TimePeriod.php';
$beanFiles['AnnualTimePeriod']        = 'modules/TimePeriods/AnnualTimePeriod.php';
$beanFiles['QuarterTimePeriod']    = 'modules/TimePeriods/QuarterTimePeriod.php';
$beanFiles['Quarter544TimePeriod']    = 'modules/TimePeriods/Quarter544TimePeriod.php';
$beanFiles['Quarter454TimePeriod']    = 'modules/TimePeriods/Quarter454TimePeriod.php';
$beanFiles['Quarter445TimePeriod']    = 'modules/TimePeriods/Quarter445TimePeriod.php';
$beanFiles['MonthTimePeriod']    = 'modules/TimePeriods/MonthTimePeriod.php';
$beanFiles['Forecast']      = 'modules/Forecasts/Forecast.php';
$beanFiles['ForecastWorksheet'] = 'modules/ForecastWorksheets/ForecastWorksheet.php';
$beanFiles['ForecastManagerWorksheet'] = 'modules/ForecastManagerWorksheets/ForecastManagerWorksheet.php';
$beanFiles['ForecastOpportunities']  = 'modules/Forecasts/ForecastOpportunities.php';
$beanFiles['ForecastDirectReports'] = 'modules/Forecasts/ForecastDirectReports.php';
$beanFiles['Quota']  = 'modules/Quotas/Quota.php';
$beanFiles['WorkFlow']  = 'modules/WorkFlow/WorkFlow.php';
$beanFiles['WorkFlowTriggerShell']  = 'modules/WorkFlowTriggerShells/WorkFlowTriggerShell.php';
$beanFiles['WorkFlowAlertShell']  = 'modules/WorkFlowAlertShells/WorkFlowAlertShell.php';
$beanFiles['WorkFlowAlert']  = 'modules/WorkFlowAlerts/WorkFlowAlert.php';
$beanFiles['WorkFlowActionShell']  = 'modules/WorkFlowActionShells/WorkFlowActionShell.php';
$beanFiles['WorkFlowAction']  = 'modules/WorkFlowActions/WorkFlowAction.php';
$beanFiles['Expression']  = 'modules/Expressions/Expression.php';
$beanFiles['SessionManager']      = 'modules/Administration/SessionManager.php';
$beanFiles['ContractType']  = 'modules/ContractTypes/ContractType.php';
$beanFiles['Holiday'] = 'modules/Holidays/Holiday.php';
$beanFiles['CustomQuery']= 'modules/CustomQueries/CustomQuery.php';
$beanFiles['DataSet']= 'modules/DataSets/DataSet.php';
$beanFiles['DataSet_Attribute']= 'modules/DataSets/DataSet_Attribute.php';
$beanFiles['ReportMaker']= 'modules/ReportMaker/ReportMaker.php';
$beanFiles['pmse_Project'] = 'modules/pmse_Project/pmse_Project.php';
$beanFiles['pmse_Inbox'] = 'modules/pmse_Inbox/pmse_Inbox.php';
$beanFiles['pmse_Business_Rules'] = 'modules/pmse_Business_Rules/pmse_Business_Rules.php';
$beanFiles['pmse_Emails_Templates'] = 'modules/pmse_Emails_Templates/pmse_Emails_Templates.php';
$beanFiles['pmse_BpmnActivity'] = 'modules/pmse_Project/pmse_BpmnActivity/pmse_BpmnActivity.php';
$beanFiles['pmse_BpmnArtifact'] = 'modules/pmse_Project/pmse_BpmnArtifact/pmse_BpmnArtifact.php';
$beanFiles['pmse_BpmnBound'] = 'modules/pmse_Project/pmse_BpmnBound/pmse_BpmnBound.php';
$beanFiles['pmse_BpmnData'] = 'modules/pmse_Project/pmse_BpmnData/pmse_BpmnData.php';
$beanFiles['pmse_BpmnDiagram'] = 'modules/pmse_Project/pmse_BpmnDiagram/pmse_BpmnDiagram.php';
$beanFiles['pmse_BpmnDocumentation'] = 'modules/pmse_Project/pmse_BpmnDocumentation/pmse_BpmnDocumentation.php';
$beanFiles['pmse_BpmnEvent'] = 'modules/pmse_Project/pmse_BpmnEvent/pmse_BpmnEvent.php';
$beanFiles['pmse_BpmnExtension'] = 'modules/pmse_Project/pmse_BpmnExtension/pmse_BpmnExtension.php';
$beanFiles['pmse_BpmnFlow'] = 'modules/pmse_Project/pmse_BpmnFlow/pmse_BpmnFlow.php';
$beanFiles['pmse_BpmnGateway'] = 'modules/pmse_Project/pmse_BpmnGateway/pmse_BpmnGateway.php';
$beanFiles['pmse_BpmnLane'] = 'modules/pmse_Project/pmse_BpmnLane/pmse_BpmnLane.php';
$beanFiles['pmse_BpmnLaneset'] = 'modules/pmse_Project/pmse_BpmnLaneset/pmse_BpmnLaneset.php';
$beanFiles['pmse_BpmnParticipant'] = 'modules/pmse_Project/pmse_BpmnParticipant/pmse_BpmnParticipant.php';
$beanFiles['pmse_BpmnProcess'] = 'modules/pmse_Project/pmse_BpmnProcess/pmse_BpmnProcess.php';
$beanFiles['pmse_BpmFlow'] = 'modules/pmse_Project/pmse_BpmFlow/pmse_BpmFlow.php';
$beanFiles['pmse_BpmThread'] = 'modules/pmse_Project/pmse_BpmThread/pmse_BpmThread.php';
$beanFiles['pmse_BpmNotes'] = 'modules/pmse_Project/pmse_BpmNotes/pmse_BpmNotes.php';
$beanFiles['pmse_BpmRelatedDependency'] = 'modules/pmse_Project/pmse_BpmRelatedDependency/pmse_BpmRelatedDependency.php';
$beanFiles['pmse_BpmActivityUser'] = 'modules/pmse_Project/pmse_BpmActivityUser/pmse_BpmActivityUser.php';
$beanFiles['pmse_BpmEventDefinition'] = 'modules/pmse_Project/pmse_BpmEventDefinition/pmse_BpmEventDefinition.php';
$beanFiles['pmse_BpmGatewayDefinition'] = 'modules/pmse_Project/pmse_BpmGatewayDefinition/pmse_BpmGatewayDefinition.php';
$beanFiles['pmse_BpmActivityDefinition'] = 'modules/pmse_Project/pmse_BpmActivityDefinition/pmse_BpmActivityDefinition.php';
$beanFiles['pmse_BpmActivityStep'] = 'modules/pmse_Project/pmse_BpmActivityStep/pmse_BpmActivityStep.php';
$beanFiles['pmse_BpmFormAction'] = 'modules/pmse_Project/pmse_BpmFormAction/pmse_BpmFormAction.php';
$beanFiles['pmse_BpmDynaForm'] = 'modules/pmse_Project/pmse_BpmDynaForm/pmse_BpmDynaForm.php';
$beanFiles['pmse_BpmProcessDefinition'] = 'modules/pmse_Project/pmse_BpmProcessDefinition/pmse_BpmProcessDefinition.php';
$beanFiles['pmse_BpmConfig'] = 'modules/pmse_Project/pmse_BpmConfig/pmse_BpmConfig.php';
$beanFiles['pmse_BpmGroup'] = 'modules/pmse_Project/pmse_BpmGroup/pmse_BpmGroup.php';
$beanFiles['pmse_BpmGroupUser'] = 'modules/pmse_Project/pmse_BpmGroupUser/pmse_BpmGroupUser.php';

$beanFiles['Configurator']          = 'modules/Configurator/Configurator.php';
$beanFiles['EmptyBean'] = 'data/EmptyBean.php';
$beanFiles['Styleguide'] = 'modules/Styleguide/Styleguide.php';
$beanFiles['OutboundEmail'] = 'include/OutboundEmail/OutboundEmail.php';
$beanFiles['EmailParticipant'] = 'modules/EmailParticipants/EmailParticipant.php';
$beanFiles['DataPrivacy'] = 'modules/DataPrivacy/DataPrivacy.php';
// added these lists for security settings for tabs
$modInvisList = array('Administration', 'CustomFields', 'Connectors',
    'Dropdown', 'Dynamic', 'DynamicFields', 'DynamicLayout', 'EditCustomFields',
    'Help', 'Import',  'MySettings', 'EditCustomFields','FieldsMetaData',
    'UpgradeWizard', 'Trackers', 'Connectors', 'Employees', 'Calendar',
    'Manufacturers', 'ProductBundles', 'ProductBundleNotes', 'ProductCategories', 'ProductTemplates', 'ProductTypes',
    'Shippers', 'TaxRates', 'TeamNotices', 'Teams', 'TimePeriods', 'ForecastOpportunities', 'Quotas',
    'ContractTypes',
    'ACLFields', 'Holidays', 'SNIP', 'ForecastDirectReports',
    'Releases','Sync',
    'Users',  'Versions', 'LabelEditor','Roles','EmailMarketing'
    ,'OptimisticLock', 'TeamMemberships', 'TeamSets', 'TeamSetModule', 'Audit', 'MailMerge', 'MergeRecords', 'EmailAddresses','EmailText',
    'Schedulers','Schedulers_jobs', /*'Queues',*/ 'EmailTemplates','UserSignature',
    'CampaignTrackers', 'CampaignLog', 'EmailMan', 'Prospects', 'ProspectLists',
    'Groups','InboundEmail',
    'ACLActions', 'ACLRoles',
    'DocumentRevisions',
    'Empty',
    'ProjectTask',
    'RevenueLineItems',
    'ModuleBuilder',
    'OutboundEmail',
    'pmse_BpmnActivity',
    'pmse_BpmnArtifact',
    'pmse_BpmnBound',
    'pmse_BpmnData',
    'pmse_BpmnDiagram',
    'pmse_BpmnDocumentation',
    'pmse_BpmnEvent',
    'pmse_BpmnExtension',
    'pmse_BpmnFlow',
    'pmse_BpmnGateway',
    'pmse_BpmnLane',
    'pmse_BpmnLaneset',
    'pmse_BpmnParticipant',
    'pmse_BpmnProcess',
    'pmse_BpmFlow',
    'pmse_BpmThread',
    'pmse_BpmNotes',
    'pmse_BpmRelatedDependency',
    'pmse_BpmActivityUser',
    'pmse_BpmEventDefinition',
    'pmse_BpmGatewayDefinition',
    'pmse_BpmActivityDefinition',
    'pmse_BpmActivityStep',
    'pmse_BpmFormAction',
    'pmse_BpmDynaForm',
    'pmse_BpmProcessDefinition',
    'pmse_BpmConfig',
    'pmse_BpmGroup',
    'pmse_BpmGroupUser',
    );
$adminOnlyList = array(
                    //module => list of actions  (all says all actions are admin only)
                   //'Administration'=>array('all'=>1, 'SupportPortal'=>'allow'),
                    'Dropdown'=>array('all'=>1),
                    'Dynamic'=>array('all'=>1),
                    'DynamicFields'=>array('all'=>1),
                    'Currencies'=>array('all'=>1),
                    'EditCustomFields'=>array('all'=>1),
                    'FieldsMetaData'=>array('all'=>1),
                    'LabelEditor'=>array('all'=>1),
                    'ACL'=>array('all'=>1),
                    'ACLActions'=>array('all'=>1),
                    'ACLRoles'=>array('all'=>1),
                    'ACLFields'=>array('all'=>1),
                    'UpgradeWizard' => array('all' => 1),
                    'Studio' => array('all' => 1),
                    'Schedulers' => array('all' => 1),
                    'WebLogicHooks' => array('all' => 1),
                    );

$apiModuleList = array('Users', 'ActivityStream');

$modInvisList[] = 'CustomQueries';
$modInvisList[] = 'DataSets';
$modInvisList[] = 'DataSet_Attribute';
$modInvisList[] = 'ReportMaker';

//$modInvisList[] = 'QueryBuilder';
$modInvisList[] = 'WorkFlow';
$modInvisList[] = 'WorkFlowTriggerShells';
$modInvisList[] = 'WorkFlowAlertShells';
$modInvisList[] = 'WorkFlowAlerts';
$modInvisList[] = 'WorkFlowActionShells';
$modInvisList[] = 'WorkFlowActions';
$modInvisList[] = 'Expressions';
$modInvisList[] = 'ACLFields';
$modInvisList[] = 'ForecastManagerWorksheets';
$modInvisList[] = 'ForecastWorksheets';
$modInvisList[] = 'ACL';
$modInvisList[] = 'ACLRoles';
$modInvisList[] = 'Configurator';
$modInvisList[] = 'UserPreferences';
$modInvisList[] = 'SavedSearch';
// deferred
//$modInvisList[] = 'Queues';
$modInvisList[] = 'Studio';
$modInvisList[] = 'Connectors';
$modInvisList[] = 'Styleguide';
$modInvisList[] = 'EmailParticipants';

$report_include_modules = array();
//add prospects
$report_include_modules['Prospects']='Prospect';
$report_include_modules['DocumentRevisions'] = 'DocumentRevision';
$report_include_modules['ProductCategories'] = 'ProductCategory';
$report_include_modules['ProductTypes'] = 'ProductType';
$report_include_modules['Contracts']='Contract';
//add Tracker modules

$report_include_modules['Trackers']         = 'Tracker';
$report_include_modules['Tags']         = 'Tag';

$report_include_modules['TrackerPerfs']     = 'TrackerPerf';
$report_include_modules['TrackerSessions']  = 'TrackerSession';
$report_include_modules['TrackerQueries']   = 'TrackerQuery';
$report_include_modules['Quotas']    = 'Quota';


$beanList['Notifications'] = 'Notifications';
$beanFiles['Notifications'] = 'modules/Notifications/Notifications.php';
$modInvisList[] = 'Notifications';
// This is the mapping for modules that appear under a different module's tab
// Be sure to also add the modules to $modInvisList, otherwise their tab will still appear
$GLOBALS['moduleTabMap'] = array(
    'UpgradeWizard' => 'Administration',
    'KBDocuments' => 'KBContents',
    'KBArticles' => 'KBContents',
    'KBContentTemplates' => 'KBContents',
    'EmailMan' => 'Administration',
    'ModuleBuilder' => 'Administration',
    'Configurator' => 'Administration',
    'Studio' => 'Administration',
    'DocumentRevisions' => 'Documents',
    'EmailTemplates' => 'Emails',
    'OutboundEmail' => 'Emails',
    'DataSets' => 'ReportMaker',
    'CustomQueries' => 'ReportMaker',
    'EmailMarketing' => 'Campaigns',
    'CampaignTrackers' => 'Campaigns',
    'Quotas' => 'Forecasts',
    'TeamNotices' => 'Teams',
    'Activities' => 'Home',
    'WorkFlowAlertShells' => 'WorkFlow',
    'UserSignatures' => 'Emails',
 );
$beanList['EAPM'] = 'EAPM';
$beanFiles['EAPM'] = 'modules/EAPM/EAPM.php';
$modules_exempt_from_availability_check['EAPM'] = 'EAPM';
$modInvisList[] = 'EAPM';
$beanList['OAuthKeys'] = 'OAuthKey';
$beanFiles['OAuthKey'] = 'modules/OAuthKeys/OAuthKey.php';
$modules_exempt_from_availability_check['OAuthKeys'] = 'OAuthKeys';
$modInvisList[] = 'OAuthKeys';
$beanList['OAuthTokens'] = 'OAuthToken';
$beanFiles['OAuthToken'] = 'modules/OAuthTokens/OAuthToken.php';
$modules_exempt_from_availability_check['OAuthTokens'] = 'OAuthTokens';
$modInvisList[] = 'OAuthTokens';


$beanList['SugarFavorites'] = 'SugarFavorites';
$beanFiles['SugarFavorites'] = 'modules/SugarFavorites/SugarFavorites.php';
$modules_exempt_from_availability_check['SugarFavorites'] = 'SugarFavorites';
$modInvisList[] = 'SugarFavorites';


$beanList['WebLogicHooks'] = 'WebLogicHook';
$beanFiles['WebLogicHook'] = 'modules/WebLogicHooks/WebLogicHook.php';
$modInvisList[] = 'WebLogicHooks';

$beanList['Activities'] = 'Activity';
$beanFiles['Activity'] = 'modules/ActivityStream/Activities/Activity.php';
$modInvisList[] = 'Activities';

$beanList['Comments'] = 'Comment';
$beanFiles['Comment'] = 'modules/ActivityStream/Comments/Comment.php';
$modInvisList[] = 'Comments';

$beanList['Subscriptions'] = 'Subscription';
$beanFiles['Subscription'] = 'modules/ActivityStream/Subscriptions/Subscription.php';
$modInvisList[] = 'Subscriptions';

$beanList['Filters'] = 'Filters';
$beanFiles['Filters'] = 'modules/Filters/Filters.php';
$modInvisList[] = 'Filters';

$beanList['Dashboards'] = 'Dashboard';
$beanFiles['Dashboard'] = 'modules/Dashboards/Dashboard.php';

// Make Tags a visible module since all should have at least access to it
$moduleList[] = 'Tags';
$beanList['Tags'] = 'Tag';
$beanFiles['Tag'] = 'modules/Tags/Tag.php';

$beanList['Categories'] = 'Category';
$beanFiles['Category'] = 'modules/Categories/Category.php';
$modInvisList[] = 'Categories';

//Object list is only here to correct for modules that break
//the bean class name == dictionary entry/object name convention
//No future module should need an entry here.
$objectList = array();
$objectList['Cases'] =  'Case';
$objectList['Groups'] =  'Group';
$objectList['Users'] =  'User';
$objectList['ActivityStream/Activities'] = 'Activities';
$objectList['ActivityStream/Comments'] = 'Comments';
$objectList['TrackerSessions'] =  'tracker_sessions';
$objectList['TrackerPerfs'] =  'tracker_perf';
$objectList['TrackerQueries'] =  'tracker_queries';
$objectList['TeamNotices'] =  'TeamNotices';
$objectList['OutboundEmail'] =  'OutboundEmail';

$beanList['PdfManager']     = 'PdfManager';
$beanFiles['PdfManager']     = 'modules/PdfManager/PdfManager.php';
$modInvisList[] = 'PdfManager';
$adminOnlyList['PdfManager'] = array('all' => 1);

// TODO: this definition should be grouped with all the others definitions like $beanList, $beanFiles and so on
$bwcModules = array(
    'ACLFields',
    'ACLRoles',
    'ACLActions',
    'Administration',
    'Audit',
    'Calendar',
    'CampaignLog',
    'Campaigns',
    'CampaignTrackers',
    'Charts',
    'Configurator',
    'Connectors',
    'CustomQueries',
    'DataSets',
    'DocumentRevisions',
    'Documents',
    'EAPM',
    'EmailAddresses',
    'EmailMarketing',
    'EmailMan',
    'EmailTemplates',
    'Employees',
    'Exports',
    'Expressions',
    'Groups',
    'History',
    'Holidays',
    'iCals',
    'Import',
    'InboundEmail',
    'MergeRecords',
    'ModuleBuilder',
    'MySettings',
    'OAuthKeys',
    'OAuthTokens',
    'OptimisticLock',
    'OutboundEmailConfiguration',
    'PdfManager',
    'Project',
    'ProjectTask',
    'Quotas',
    'QueryBuilder',
    'Relationships',
    'Releases',
    'ReportMaker',
    'Roles',
    'SavedSearch',
    'Schedulers',
    'SchedulersJobs',
    'SNIP',
    'Studio',
    'SugarFavorites',
    'Teams',
    'TeamMemberships',
    'TeamSets',
    'TeamSetModules',
    'TeamNotices',
    'TimePeriods',
    'Trackers',
    'TrackerSessions',
    'TrackerPerfs',
    'TrackerQueries',
    'UserPreferences',
    'Users',
    'vCals',
    'vCards',
    'Versions',
    'WorkFlow',
    'WorkFlowActions',
    'WorkFlowActionShells',
    'WorkFlowAlerts',
    'WorkFlowAlertShells',
    'WorkFlowTriggerShells'
);

$beanList['KBDocuments'] = 'KBDocument';
$beanFiles['KBDocument'] = 'modules/KBDocuments/KBDocument.php';
$modInvisList[] = 'KBDocument';

$beanList['KBContents'] = 'KBContent';
$beanFiles['KBContent'] = 'modules/KBContents/KBContent.php';
$moduleList[] = 'KBContents';

$beanList['KBArticles'] = 'KBArticle';
$beanFiles['KBArticle'] = 'modules/KBArticles/KBArticle.php';
$modInvisList[] = 'KBArticles';

$beanList['KBContentTemplates'] = 'KBContentTemplate';
$beanFiles['KBContentTemplate'] = 'modules/KBContentTemplates/KBContentTemplate.php';
$modInvisList[] = 'KBContentTemplates';

$beanList['EmbeddedFiles'] = 'EmbeddedFile';
$beanFiles['EmbeddedFile'] = 'modules/EmbeddedFiles/EmbeddedFile.php';
$modInvisList[] = 'EmbeddedFiles';


// this module doesn't need a Bean
$modInvisList[] = 'Feedbacks';

foreach(SugarAutoLoader::existing('include/modules_override.php', SugarAutoLoader::loadExtension("modules")) as $modExtFile) {
    include $modExtFile;
}
