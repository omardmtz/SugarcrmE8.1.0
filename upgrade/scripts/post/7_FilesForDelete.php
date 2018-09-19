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
/**
 * Select files to delete during install
 */
class SugarUpgradeFilesForDelete extends UpgradeScript
{
    public $order = 7000;
    public $version = '7.1.5';
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        $files = array('themes/Sugar/js',
            //Remove the themes/Sugar/tpls directory
            'themes/Sugar/tpls',
            'themes/Sugar5',
            // remove the files moved to vendor
            'include/HTMLPurifier',
            'include/HTTP_WebDAV_Server',
            'include/Pear',
            'include/Smarty',
            'include/Sugar_Smarty.php',
            'XTemplate',
            'Zend',
            'include/lessphp',
            'log4php',
            'include/nusoap',
            'include/oauth2-php',
            'include/pclzip',
            'include/reCaptcha',
            'include/tcpdf',
            'include/ytree',
            'include/SugarSearchEngine/Elastic/Elastica',
            //Remove the SugarFeed files
            'modules/Cases/SugarFeeds',
            'modules/Contacts/SugarFeeds',
            'modules/Leads/SugarFeeds',
            'modules/Opportunities/SugarFeeds/OppFeed.php',
            'modules/SugarFeed',
            // remove old popup picker files from RLI
            'modules/RevenueLineItems/Popup_picker.html',
            'modules/RevenueLineItems/Popup_picker.php',
            // remove phpunit from vendor
            'vendor/phpunit',
            // remove the old base metadata file template for the dashablelist view
            'include/SugarObjects/templates/basic/clients/base/views/dashablelist/dashablelist.php',
            // old phpmailer in thr include directory is no longer needed or referenced as of 7.0
            'include/phpmailer',
            //remove old connectors
            'modules/Connectors/connectors/sources/ext/rest/zoominfocompany',
            'modules/Connectors/connectors/sources/ext/rest/zoominfoperson',
            'modules/Connectors/connectors/sources/ext/rest/linkedin',
            'modules/Connectors/connectors/sources/ext/rest/insideview',
            'modules/Connectors/connectors/sources/ext/eapm/facebook',
            'modules/Connectors/connectors/sources/ext/soap/hoovers',
            // Remove old less files from styleguide
            'styleguide/less/clients/mobile/fixed_variables.less',
            'styleguide/less/clients/mobile/font-awesome.less',
            'styleguide/less/clients/mobile/forms.less',
            'styleguide/less/clients/mobile/labels-badges.less',
            'styleguide/less/clients/mobile/navbar.less',
            'styleguide/less/clients/mobile/navs.less',
            'styleguide/less/clients/mobile/nomad.less',
            'styleguide/less/clients/mobile/sugarmobile.less',
            'styleguide/less/clients/portal/config.less',
            'styleguide/less/modules/nv.d3.less',
            'styleguide/less/sugar-bootstrap',
            'styleguide/less/sugar-specific/actions.less',
            'styleguide/less/sugar-specific/activitystreams.less',
            'styleguide/less/sugar-specific/chosen.less',
            'styleguide/less/sugar-specific/clickmenu.less',
            'styleguide/less/sugar-specific/dcmenu.less',
            'styleguide/less/sugar-specific/modulelist.less',
            'styleguide/less/sugar-specific/position.less',
            'styleguide/less/sugar-specific/progress.less',
            'styleguide/less/sugar-specific/quickcreate.less',
            'styleguide/less/sugar-specific/responsive-forecast.less',
            'styleguide/less/sugar-specific/responsive.less',
            'styleguide/less/sugar-specific/topline-forecast.less',
            'styleguide/less/sugar-specific/vcard.less',
            'styleguide/less/sugar-specific/yui.less',
            'styleguide/less/twitter-bootstrap/carousel.less',
            'styleguide/less/twitter-bootstrap/charts.less',
            'styleguide/less/twitter-bootstrap/chosen.less',
            'styleguide/less/twitter-bootstrap/datatables.less',
            'styleguide/less/twitter-bootstrap/pager.less',
            'styleguide/less/twitter-bootstrap/pagination.less',
            'styleguide/less/twitter-bootstrap/responsive.less',
            'styleguide/less/twitter-bootstrap/sprites.less',
            'styleguide/less/twitter-bootstrap/tiptip.less',
            'styleguide/less/twitter-bootstrap/toggle.less',
            // BR 796 api files
            'clients/mobile/api/MetadataMobileApi.php',
            'clients/portal/api/MetadataPortalApi.php',
            'clients/base/views/activitystream-bottom/activitystream-bottom.php',
            // NOMAD-1179 mobile search definitions
            'modules/Accounts/clients/mobile/views/search/search.php',
            'modules/Bugs/clients/mobile/views/search/search.php',
            'modules/Calls/clients/mobile/views/search/search.php',
            'modules/Cases/clients/mobile/views/search/search.php',
            'modules/Contacts/clients/mobile/views/search/search.php',
            'modules/Employees/clients/mobile/views/search/search.php',
            'modules/Leads/clients/mobile/views/search/search.php',
            'modules/Meetings/clients/mobile/views/search/search.php',
            'modules/Opportunities/clients/mobile/views/search/search.php',
            'modules/ProductTemplates/clients/mobile/views/search/search.php',
            'modules/Tasks/clients/mobile/views/search/search.php',
            'modules/Users/clients/mobile/views/search/search.php',
            'include/SugarObjects/templates/basic/clients/mobile/views/search/search.php',
            'include/SugarObjects/templates/company/clients/mobile/views/search/search.php',
            'include/SugarObjects/templates/file/clients/mobile/views/search/search.php',
            'include/SugarObjects/templates/issue/clients/mobile/views/search/search.php',
            'include/SugarObjects/templates/person/clients/mobile/views/search/search.php',
            'include/SugarObjects/templates/sale/clients/mobile/views/search/search.php',
            // NOMAD-1384 remove mobile views from modules which are not supported by mobile app
            'modules/Bugs/clients/mobile/',
            // NOMAD-1295
            'modules/Accounts/metadata/wireless.subpaneldefs.php',
            'modules/Bugs/metadata/wireless.listviewdefs.php',
            'modules/Bugs/metadata/wireless.listviewdefs.php',
            'modules/Calls/metadata/wireless.listviewdefs.php',
            'modules/Calls/metadata/wireless.subpaneldefs.php',
            'modules/Calls/clients/base/layouts/records/records.php',
            'modules/Cases/metadata/wireless.subpaneldefs.php',
            'modules/Contacts/metadata/wireless.subpaneldefs.php',
            'modules/Documents/metadata/wireless.editviewdefs.php',
            'modules/Documents/metadata/wireless.subpaneldefs.php',
            'modules/Leads/metadata/wireless.subpaneldefs.php',
            'modules/Meetings/metadata/wireless.listviewdefs.php',
            'modules/Meetings/metadata/wireless.subpaneldefs.php',
            'modules/Meetings/clients/base/layouts/records/records.php',
            'modules/Notes/metadata/wireless.editviewdefs.php',
            'modules/Notes/metadata/wireless.listviewdefs.php',
            'modules/Opportunities/metadata/wireless.subpaneldefs.php',
            'modules/ProductTemplates/metadata/wireless.detailviewdefs.php',
            'modules/ProductTemplates/metadata/wireless.editviewdefs.php',
            'modules/Products/metadata/wireless.detailviewdefs.php',
            'modules/Products/metadata/wireless.editviewdefs.php',
            'modules/Quotes/metadata/wireless.subpaneldefs.php',
            'modules/Tasks/metadata/wireless.listviewdefs.php',
            'modules/Tasks/metadata/wireless.subpaneldefs.php',
            'modules/Users/metadata/wireless.detailviewdefs.php',
            'modules/Users/metadata/wireless.editviewdefs.php',
            'modules/Users/metadata/wireless.listviewdefs.php',
            'modules/Users/metadata/wireless.searchdefs.php',
            'modules/Employees/views/view.wirelessedit.php',
            'modules/Opportunities/views/view.wirelessedit.php',
            'modules/Users/views/view.wirelesslogin.php',
            'modules/Users/views/view.wirelessmain.php',
            'modules/Calls/views/view.wirelesssave.php',
            'modules/Meetings/views/view.wirelesssave.php',
            'tests/include/SubPanel/Bug63486Test.php',
            'modules/Meetings/api/MeetingsApi.php',
            // MAR-1736 / SC-2611
            'modules/Emails/clients/base/views/panel-top/panel-top.js',
            // NOMAD-1799
            'modules/Meetings/clients/mobile/api/MobileMeetingsApi.php',
            // BR-1574 Move Elastica library to composer (new path vendor/ruflin/elastica)
            'vendor/Elastica/',
            'modules/EmailMan/Save.php',
            //CRYS-773 Delete version.json from upgrade wizard directory
            'modules/UpgradeWizard/version.json',
            // jira:MAR-2706 Move PHPMailer library to composer (new path vendor/phpmailer/phpmailer)
            'vendor/PHPMailer/',
            // PAT-2081 Move Google API library to composer
            'include/google-api-php-client',
            // Delete files that should have been deleted before
            'upgrade/scripts/post/5_MinifyJS.php',
            // MACAROON-1125
            'include/javascript/pmse/business_rules.js',
            // MACAROON-1328
            'modules/pmse_Inbox/engine/PMSEAccessManagement.php',
            // BR-4286 - Retire unused MVC action files
            'modules/Calendar/views/view.createinvitee.php',
            'modules/Calendar/views/view.getgr.php',
            'modules/Calendar/views/view.getgrusers.php',
            // MACAROON-1385
            'modules/pmse_Inbox/engine/PMSEHandlers/PMSECronHandler.php',
            // BR-3766 - Remove unused non-INT flagged file
            'modules/UpgradeWizard/populateColumns.php',
        );

        // must be upgrading from between 710 to 722
        if (version_compare($this->from_version, '7.1.0', '>') && version_compare($this->from_version, '7.2.2', '<')) {
            // can be files or directories
            $files[] = 'modules/WebLogicHooks/clients/base/layouts/record/record.php';
            $files[] = 'modules/WebLogicHooks/clients/base/layouts/records/records.php';
            $files[] = 'modules/WebLogicHooks/clients/base/views/list-headerpane/headerpane.php';
        }

        if (version_compare($this->from_version, '7.2', '<')) {
            // SC-2664
            $files[] = 'modules/Notifications/clients/base/layouts/records/records.php';
            $files[] = 'modules/Notifications/clients/base/views/raw/raw.hbs';
            $files[] = 'modules/Notifications/clients/base/views/raw/raw.js';
            $files[] = 'modules/Notifications/clients/base/views/raw/raw.php';
        }

        $this->upgrader->fileToDelete($this->getStaleFilesBy7220(), $this);

        if (version_compare($this->from_version, '7.5', '<=')) {
            $files[] = 'modules/Home/clients/base/views/about-source-code/about-source-code.php';
            $files[] = 'portal2/portal-ui.js';
        }

        if (version_compare($this->from_version, '7.7', '<')) {
            $files[] = 'include/Expressions/Expression/Numeric/CurrencyAddExpression.php';
            $files[] = 'include/Expressions/Expression/Numeric/CurrencySubtractExpression.php';
            $files[] = 'include/Expressions/Expression/Numeric/CurrencyMultiplyExpression.php';
            $files[] = 'include/Expressions/Expression/Numeric/CurrencyDivideExpression.php';
            $files[] = 'include/Expressions/Expression/Numeric/CurrencySumRelatedExpression.php';
        }

        if (version_compare($this->from_version, '7.5', '<')) {
            $files[] = 'clients/portal/fields/date';
            $files[] = 'clients/portal/fields/datetimecombo';
            $files[] = 'include/MVC/View/views/view.gs.php';
            $files[] = 'include/MVC/View/views/view.wirelessdetail.php';
            $files[] = 'include/MVC/View/views/view.wirelessedit.php';
            $files[] = 'include/MVC/View/views/view.wirelesslist.php';
            $files[] = 'include/MVC/View/views/view.wirelessmodule.php';
            $files[] = 'include/MVC/View/views/view.wirelesssave.php';
            $files[] = 'include/SugarWireless';
            $files[] = 'modules/Notifications/Senders';
            $files[] = 'modules/Notifications/clients/base/api/NotificationsApi.php';
        }

        if (version_compare($this->from_version, '7.6', '<')) {
            $files[] = 'clients/base/fields/fieldset-with-labels';
            $files[] = 'clients/base/views/create-nodupecheck/create-nodupecheck.php';
            $files[] = 'modules/Contacts/clients/base/views/create-actions/create-actions.php';
            $files[] = 'modules/ForecastWorksheets/clients/base/views/list-bottom';
            $files[] = 'modules/Leads/clients/base/views/create-actions/create-actions.php';
            $files[] = 'modules/Opportunities/clients/base/views/create-actions/create-actions.php';
            $files[] = 'modules/Opportunities/clients/base/api/OpportunitiesApi.php';
            $files[] = 'modules/Opportunities/clients/base/api/help/OpportunitiesPipelineChartApi.html';
            $files[] = 'modules/Opportunities/Ext/LogicHooks/ProcessHiddenRevenueLineItem.php';
            $files[] = 'modules/Products/clients/base/views/create-actions/create-actions.php';
            $files[] = 'modules/RevenueLineItems/clients/base/views/create-actions/create-actions.php';
            $files[] = 'modules/ForecastSchedule';
            $files[] = 'modules/Reports/upgrade/scripts/post/2_FixOpportunityReports.php';
            $files[] = 'modules/ProductBundles/Ext/LogicHooks/RelationshipChange.php';
            $files[] = 'modules/ProductBundles/ProductBundleHooks.php';
            $files[] = 'modules/ProductBundles/ProductBundlesApiHelper.php';
            // UIUX-1687 Remove files orphaned by Font Awesome upgrade
            $files[] = 'styleguide/assets/css/loader-sugarone.css';
            $files[] = 'styleguide/less/twitter-bootstrap/font-awesome/bootstrap.less';
            $files[] = 'styleguide/assets/font';
            $files[] = 'vendor/lessphp/docs/docs.md';
            $files[] = 'vendor/lessphp/plessc';
        }

        if (version_compare($this->from_version, '7.6', '<=')) {
            $files[] = 'modules/RevenueLineItems/clients/base/api/RevenueLineItemsPipelineChartApi.php';
            $files[] = 'modules/Products/clients/base/api/ProductsPipelineChartApi.php';
            $files[] = 'modules/Opportunities/clients/base/api/OpportunitiesPipelineChartApi.php';
            $files[] = 'modules/ForecastManagerWorksheets/clients/base/fields/rowactions';
            $files[] = 'modules/Tasks/clients/base/fields/closebutton';
            $files[] = 'include/SugarFields/Fields/Username';
        }

        //CRYS-806 Remove Healthcheck
        if (version_compare($this->from_version, '7.5', '>=') && version_compare($this->from_version, '7.6', '<')) {
            $files[] = 'modules/HealthCheck';
            if (!$this->upgrader->context['case_insensitive_fs']) {
                $files[] = 'vendor/OneLogin';
            } else {
                $files[] = 'vendor/OneLogin/Saml';
            }
        }

        if (version_compare($this->from_version, '7.7', '<')) {
            $files[] = 'clients/base/views/interactionschart';
            $files[] = 'include/javascript/sugar7/plugins/Timeago.js';
            $files[] = 'modules/Accounts/clients/base/layouts/create-actions';
            $files[] = 'modules/Accounts/clients/base/views/create-actions';
            $files[] = 'modules/Calls/clients/base/views/create-actions';
            $files[] = 'modules/Contacts/clients/base/views/create-actions';
            $files[] = 'modules/Leads/clients/base/views/create-actions';
            $files[] = 'modules/Meetings/clients/base/views/create-actions';
            $files[] = 'modules/Opportunities/clients/base/fields/quickcreate';
            $files[] = 'modules/Opportunities/clients/base/layouts/create-actions';
            $files[] = 'modules/Opportunities/clients/base/views/create-actions';
            $files[] = 'modules/Products/clients/base/views/create-actions';
            $files[] = 'modules/RevenueLineItems/clients/base/views/create-actions';
            $files[] = 'modules/Styleguide/clients/base/views/create-actions';
            $files[] = 'modules/Tasks/clients/base/views/create-actions';
            $files[] = 'modules/pmse_Business_Rules/clients/base/views/create-actions';
            $files[] = 'modules/pmse_Emails_Templates/clients/base/views/create-actions';
            $files[] = 'modules/pmse_Project/clients/base/views/create-actions';
            $files[] = 'clients/base/views/globalsearch';
            $files[] = 'clients/base/layouts/list-sidebar/list-sidebar.php';
            $files[] = 'modules/Accounts/clients/base/layouts/list-sidebar/list-sidebar.php';
            $files[] = 'modules/Bugs/clients/base/layouts/list-sidebar/list-sidebar.php';
            $files[] = 'modules/Cases/clients/base/layouts/list-sidebar/list-sidebar.php';
            $files[] = 'modules/Leads/clients/base/layouts/convert-sidebar/convert-sidebar.php';
            $files[] = 'modules/Accounts/clients/base/layouts/new-sidebar/new-sidebar.php';
            $files[] = 'modules/Accounts/clients/base/layouts/sidebar/sidebar.php';
            $files[] = 'modules/Bugs/clients/base/layouts/new-sidebar/new-sidebar.php';
            $files[] = 'modules/Bugs/clients/base/layouts/sidebar/sidebar.php';
            $files[] = 'modules/Cases/clients/base/layouts/new-sidebar/new-sidebar.php';
            $files[] = 'modules/Cases/clients/base/layouts/sidebar/sidebar.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetAbstract.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetFactory.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetFilter.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetHandler.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetInterface.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetMyitems.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetRange.php';
            $files[] = 'include/SugarSearchEngine/Elastic/Facets/FacetTerms.php';
            $files[] = 'include/SugarSearchEngine/Elastic/SugarSearchEngineElasticIndexStrategyBase.php';
            $files[] = 'include/SugarSearchEngine/Elastic/SugarSearchEngineElasticIndexStrategyFactory.php';
            $files[] = 'include/SugarSearchEngine/Elastic/SugarSearchEngineElasticIndexStrategyInterface.php';
            $files[] = 'include/SugarSearchEngine/Elastic/SugarSearchEngineElasticIndexStrategyMulti.php';
            $files[] = 'include/SugarSearchEngine/Elastic/SugarSearchEngineElasticIndexStrategySingle.php';
            $files[] = 'include/SugarSearchEngine/Elastic/SugarSearchEngineElasticMapping.php';
            $files[] = 'include/SugarSearchEngine/SugarSearchEngineFullIndexer.php';
            $files[] = 'include/SugarSearchEngine/SugarSearchEngineIndexerBase.php';
            $files[] = 'silentFTSIndex.php';
            $files[] = 'upgrade/scripts/post/5_FTSHook.php';
            $files[] = 'modules/pmse_Business_Rules/clients/base/views/preview/preview.js';
        }

        // MACAROON-901... remove quickcreate files for PMSE modules
        if (version_compare($this->to_version, '7.6.1', '>=')) {
            $files[] = 'modules/pmse_Business_Rules/metadata/quickcreatedefs.php';
            $files[] = 'modules/pmse_Emails_Templates/metadata/quickcreatedefs.php';
            $files[] = 'modules/pmse_Inbox/metadata/quickcreatedefs.php';
            $files[] = 'modules/pmse_Project/metadata/quickcreatedefs.php';
        }

        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $files[] = 'modules/Forecasts/clients/base/view/forecast-pipeline/forecast-pipeline.hbs';
            $files[] = 'include/javascript/sugar7/plugins/QuickSearchFilter.js';
            $files[] = 'styleguide/assets/css/sugar.css';
            $files[] = 'styleguide/assets/css/bootstrap.css';
            //MACAROON-1005 remove casesList-list.js
            $files[] = 'modules/pmse_Inbox/clients/base/layouts/casesList-list/casesList-list.js';
            $files[] = 'clients/base/views/passwordmodal';
            $files[] = 'clients/portal/views/passwordmodal';
            $files[] = 'modules/Notifications/controller.php';
            $files[] = 'include/api/AttachmentListApi.php';
            $files[] = 'clients/base/views/pmse-case/businesscard.hbs';
            $files[] = 'clients/base/views/pmse-case/headerpane.hbs';
            $files[] = 'clients/base/views/pmse-case/tabspanels.hbs';
            $files[] = 'clients/base/layouts/create-actions';
            $files[] = 'clients/base/views/create-actions';
            $files[] = 'clients/base/views/news';
            $files[] = 'styleguide/assets/css/jsduck.css';
            $files[] = 'styleguide/less/sugar-specific/rtl.less';
            $files[] = 'styleguide/less/twitter-bootstrap/alerts.less';
            $files[] = 'themes/default/css/ie.css';
            $files[] = 'jssource/src_files';
            $files[] = 'clients/base/layouts/create-dupecheck/create-dupecheck.js';
            $files[] = 'clients/base/layouts/dashlet-preview/dashlet-preview.js';
            $files[] = 'clients/base/layouts/first-login-wizard/first-login-wizard.js';
            $files[] = 'clients/base/layouts/multi-selection-list-link/multi-selection-list-link.js';
            $files[] = 'clients/base/layouts/multi-selection-list/multi-selection-list.js';
            $files[] = 'clients/base/views/help-dashboard-headerpane/help-dashboard-headerpane.js';
            $files[] = 'modules/Accounts/clients/base/views/panel-top-for-prospectlists/panel-top-for-prospectlists.js';
            $files[] = 'modules/Contacts/clients/base/views/panel-top-for-cases/panel-top-for-cases.js';
            $files[] = 'modules/Contacts/clients/base/views/panel-top-for-prospectlists/panel-top-for-prospectlists.js';
            $files[] = 'modules/Leads/clients/base/views/panel-top-for-prospectlists/panel-top-for-prospectlists.js';
            $files[] = 'modules/Prospects/clients/base/views/panel-top-for-prospectlists/' .
                'panel-top-for-prospectlists.js';
            $files[] = 'modules/Quotes/clients/base/views/panel-top-for-accounts/panel-top-for-accounts.js';
            $files[] = 'modules/Users/clients/base/views/panel-top-for-prospectlists/panel-top-for-prospectlists.js';
            $files[] = 'modules/Leads/clients/base/views/panel-top-for-prospectlists/panel-top-for-prospectlists.js';
            $files[] = 'modules/Prospects/clients/base/views/panel-top-for-prospectlists/' .
                'panel-top-for-prospectlists.js';
            $files[] = 'modules/Quotes/clients/base/views/panel-top-for-accounts/panel-top-for-accounts.js';
            $files[] = 'modules/Users/clients/base/views/panel-top-for-prospectlists/panel-top-for-prospectlists.js';
            $files[] = 'modules/ACLRoles/clients/base/layouts/records/records.js';
            $files[] = 'modules/Administration/clients/base/layouts/records/records.js';
            $files[] = 'modules/Calendar/clients/base/layouts/records/records.js';
            $files[] = 'modules/Campaigns/clients/base/layouts/records/records.js';
            $files[] = 'modules/ContractTypes/clients/base/layouts/records/records.js';
            $files[] = 'modules/Contracts/clients/base/layouts/records/records.js';
            $files[] = 'modules/DocumentRevisions/clients/base/layouts/records/records.js';
            $files[] = 'modules/Documents/clients/base/layouts/records/records.js';
            $files[] = 'modules/EmailTemplates/clients/base/layouts/records/records.js';
            $files[] = 'modules/Emails/clients/base/layouts/records/records.js';
            $files[] = 'modules/Employees/clients/base/layouts/records/records.js';
            $files[] = 'modules/InboundEmail/clients/base/layouts/records/records.js';
            $files[] = 'modules/Manufacturers/clients/base/layouts/records/records.js';
            $files[] = 'modules/OAuthKeys/clients/base/layouts/records/records.js';
            $files[] = 'modules/PdfManager/clients/base/layouts/records/records.js';
            $files[] = 'modules/Project/clients/base/layouts/records/records.js';
            $files[] = 'modules/ProjectTask/clients/base/layouts/records/records.js';
            $files[] = 'modules/Quotas/clients/base/layouts/records/records.js';
            $files[] = 'modules/Quotes/clients/base/layouts/records/records.js';
            $files[] = 'modules/Reports/clients/base/layouts/records/records.js';
            $files[] = 'modules/TaxRates/clients/base/layouts/records/records.js';
            $files[] = 'modules/TeamNotices/clients/base/layouts/records/records.js';
            $files[] = 'modules/Teams/clients/base/layouts/records/records.js';
            $files[] = 'modules/UserSignatures/clients/base/layouts/records/records.js';
            $files[] = 'modules/Users/clients/base/layouts/records/records.js';
            $files[] = 'clients/base/layouts/history-summary-preview/history-summary-preview.js';
            $files[] = 'clients/base/views/history-summary-preview-header/history-summary-preview-header.js';
            $files[] = 'clients/base/views/history-summary/history-summary.hbs';
            $files[] = 'mobile/js/sidecar.js';
            $files[] = 'modules/pmse_Inbox/engine/Crypt.php';
            $files[] = 'modules/pmse_Inbox/engine/PMSEAccessManager.php';
            $files[] = 'modules/pmse_Inbox/engine/PMSELicenseManager.php';
            $files[] = 'modules/pmse_Project/pmse_BpmAccessManagement';
            $files[] = 'themes/default/images/ical-settings-icon.gif';
            $files[] = 'modules/Categories/clients/base/views/tree/tree.php';
            $files[] = 'modules/Forecasts/clients/base/views/forecast-pipeline/forecast-pipeline.hbs';
            $files[] = 'modules/ProductTemplates/clients/base/layouts/subpanels';
            $files[] = 'modules/RevenueLineItems/clients/base/views/filter-rows/filter-rows.php';
            $files[] = 'modules/RevenueLineItems/clients/base/views/subpanel-list-with-massupdate/' .
                'subpanel-list-with-massupdate.js';
            $files[] = 'src/JobQueue';
            $files[] = 'queueManager.php';
        }

        //Remove NotificationCenter, iCal and JobQ.
        if (version_compare($this->from_version, '7.8.0.0RC3', '>=') &&
            version_compare($this->from_version, '7.8.0.0', '<')
        ) {
            $files[] = 'Ext/LogicHooks/Notifications.php';
            $files[] = 'caldav.php';
            $files[] = 'clients/base/api/ReminderApi.php';
            $files[] = 'clients/base/api/TokenVerificationApi.php';
            $files[] = 'data/acl/SugarACLAddressees.php';
            $files[] = 'include/api/help/reminder.html';
            $files[] = 'include/api/help/token_verification_help.html';
            $files[] = 'include/api/help/verify_socket_token_help.html';
            $files[] = 'include/api/help/verify_trigger_token_help.html';
            $files[] = 'include/javascript/sugar7/socket.js';
            $files[] = 'include/nmb.php';
            $files[] = 'include/sfr.php';
            $files[] = 'install/templates/triggerServerConfig.tpl';
            $files[] = 'install/templates/websocketConfig.tpl';
            $files[] = 'metadata/calls_addresseesMetaData.php';
            $files[] = 'metadata/meetings_addresseesMetaData.php';
            $files[] = 'modules/Addressees';
            $files[] = 'modules/Administration/ReExportEvents.php';
            $files[] = 'modules/Administration/RebuildReminders.php';
            $files[] = 'modules/Administration/templates/ReExportEvents.tpl';
            $files[] = 'modules/Administration/templates/RebuildReminders.tpl';
            $files[] = 'modules/Administration/templates/TriggerServer.tpl';
            $files[] = 'modules/Administration/templates/WebSockets.tpl';
            $files[] = 'modules/Administration/views/view.triggerserver.php';
            $files[] = 'modules/Administration/views/view.websockets.php';
            $files[] = 'modules/CalDav';
            $files[] = 'modules/Calls/Emitter.php';
            $files[] = 'modules/Calls/Ext/LogicHooks/logic_hooks.php';
            $files[] = 'modules/CarrierEmail';
            $files[] = 'modules/CarrierSugar';
            $files[] = 'modules/Meetings/Emitter.php';
            $files[] = 'modules/Meetings/Ext/LogicHooks/logic_hooks.php';
            $files[] = 'modules/NotificationCenter';
            $files[] = 'modules/Notifications/Ext/LogicHooks/logic_hooks.php';
            $files[] = 'modules/TriggerServer';
            $files[] = 'modules/UserPreferences/Ext/LogicHooks/logic_hooks.php';
            $files[] = 'modules/WebSockets';
            $files[] = 'queueManager.php';
            $files[] = 'src/Dav';
            $files[] = 'src/JobQueue';
            $files[] = 'src/Notification';
            $files[] = 'src/Socket';
            $files[] = 'src/Trigger';
            $files[] = 'styleguide/less/sugar-specific/notification-center.less';
            $files[] = 'upgrade/scripts/post/1_LockJobQueue.php';
            $files[] = 'upgrade/scripts/post/9_AddMeetingsAndCallsToEvents.php';
            $files[] = 'upgrade/scripts/post/9_ParticipantsLinksUpdate.php';
            $files[] = 'upgrade/scripts/post/9_RepairReminders.php';
            $files[] = 'upgrade/scripts/post/9_UnlockJobQueue.php';
            $files[] = 'upgrade/scripts/post/7_FixCallsMeetingsReminderSelection.php';
            $files[] = 'vendor/sabre';
        }

        if (version_compare($this->from_version, '7.9.0.0', '<')) {
            $files[] = 'sidecar/src/utils/file.js';
            $files[] = 'clients/base/fields/dnb-bal-import-menu-label';
            $files[] = 'clients/base/fields/dnbenum';
            $files[] = 'clients/base/layouts/dnb-bal';
            $files[] = 'clients/base/views/dnb';
            $files[] = 'clients/base/views/dnb-account-create';
            $files[] = 'clients/base/views/dnb-bal-header';
            $files[] = 'clients/base/views/dnb-bal-params';
            $files[] = 'clients/base/views/dnb-bal-results';
            $files[] = 'clients/base/views/dnb-company-info';
            $files[] = 'clients/base/views/dnb-competitors';
            $files[] = 'clients/base/views/dnb-contact-info';
            $files[] = 'clients/base/views/dnb-family-tree';
            $files[] = 'clients/base/views/dnb-financial-info';
            $files[] = 'clients/base/views/dnb-industry-info';
            $files[] = 'clients/base/views/dnb-lite-company-info';
            $files[] = 'clients/base/views/dnb-meter';
            $files[] = 'clients/base/views/dnb-news-and-media';
            $files[] = 'clients/base/views/dnb-premium-company-info';
            $files[] = 'clients/base/views/dnb-standard-company-info';
            $files[] = 'clients/base/api/DnbApi.php';
            $files[] = 'include/api/help/dnb_bulkimport_help.html';
            $files[] = 'include/api/help/dnb_get_help.html';
            $files[] = 'include/api/help/dnb_post_help.html';
            $files[] = 'include/externalAPI/Dnb';
            $files[] = 'modules/Accounts/clients/base/views/dnb-bal-params';
            $files[] = 'modules/Accounts/clients/base/views/dnb-bal-results';
            $files[] = 'modules/Connectors/connectors/formatters/ext/rest/dnb';
            $files[] = 'modules/Connectors/connectors/sources/ext/rest/dnb';
            $files[] = 'styleguide/less/clients/base/components/dnb-dashlets.less';
        }

        if (version_compare($this->from_version, '7.9.0.0', '<')) {
            $files[] = 'modules/Reports/clients/base/layouts/records';
        }

        $this->upgrader->fileToDelete($files, $this);
    }

    /**
     * This is the list of stale files that we want to make sure are removed
     * after 7.2.2.0
     *
     * @return array $files The list of files.
     */
    private function getStaleFilesBy7220()
    {
        $files = array();
        if (version_compare($this->from_version, '7.2.2.0', '<')) {
            $files = array(
                'clients/base/fields/date/detail.hbs',
                'clients/base/fields/date/list.hbs',
                'clients/base/fields/datetimecombo/detail.hbs',
                'clients/base/fields/datetimecombo/list.hbs',
                'clients/base/fields/listeditable',
                'clients/base/fields/shareaction/detail.hbs',
                'clients/base/layouts/link-selection',
                'clients/base/layouts/detail',
                'clients/base/layouts/edit',
                'clients/base/layouts/newrecord',
                'clients/base/views/agenda',
                'clients/base/views/activitystream-bottom/activitystream-bottom.php',
                'clients/base/views/detail',
                'clients/base/views/dnb-account-create/dnb-config.hbs',
                'clients/base/views/edit',
                'clients/base/views/funnel',
                'clients/base/views/influencers',
                'clients/base/views/leaderboard',
                'clients/base/views/link-headerpane',
                'clients/base/views/modulelist',
                'clients/base/views/property-table',
                'clients/base/views/raw',
                'clients/base/views/recommended-experts',
                'clients/base/views/results',
                'clients/base/views/similar-opportunities',
                'clients/base/views/treemap',
                'clients/portal/views/detail',
                'clients/portal/views/subdetail',
                'clients/portal/views/subnav',
                'include/SugarObjects/templates/basic/clients/base/layouts',
                'include/SugarSearchEngine/SugarSearchEngineMappingHelper.php',
                'include/SugarSearchEngine/SugarSearchEngineSyncIndexer.php',
                'include/VarDefHandler/listvardefoverride.php',
                'include/javascript/nvd3/src/models/multiBarChartAllAxis.js',
                'include/javascript/nvd3/src/models/multiBarHorizontal.js',
                'include/javascript/nvd3/src/models/paretoLegend.js',
                'include/javascript/select2-release-3.3.2',
                'include/javascript/sugar7/plugins/Overscroll.js',
                'include/javascript/yui/LICENSE.txt',
                'include/javascript/yui/README',
                'include/javascript/yui/index.html',
                'include/javascript/yui3/README',
                'include/javascript/yui3/build/anim',
                'include/javascript/yui3/build/assets/skins/sam/autocomplete.css',
                'include/javascript/yui3/build/assets/skins/sam/datatable.css',
                'include/javascript/yui3/build/assets/skins/sam/dt-arrow-dn.png',
                'include/javascript/yui3/build/assets/skins/sam/dt-arrow-up.png',
                'include/javascript/yui3/build/assets/skins/sam/rail-classic-x.png',
                'include/javascript/yui3/build/assets/skins/sam/rail-classic-y.png',
                'include/javascript/yui3/build/assets/skins/sam/resize.css',
                'include/javascript/yui3/build/assets/skins/sam/scrollview-paginator.css',
                'include/javascript/yui3/build/assets/skins/sam/skin.css',
                'include/javascript/yui3/build/assets/skins/sam/slider.css',
                'include/javascript/yui3/build/assets/skins/sam/test.css',
                'include/javascript/yui3/build/assets/skins/sam/thumb-classic-x.png',
                'include/javascript/yui3/build/assets/skins/sam/thumb-classic-y.png',
                'include/javascript/yui3/build/assets/skins/sam/tmp.txt',
                'include/javascript/yui3/build/assets/skins/sam/widget.css',
                'include/javascript/yui3/build/attribute',
                'include/javascript/yui3/build/autocomplete',
                'include/javascript/yui3/build/base',
                'include/javascript/yui3/build/cache',
                'include/javascript/yui3/build/charts',
                'include/javascript/yui3/build/collection',
                'include/javascript/yui3/build/compat',
                'include/javascript/yui3/build/console/assets/console-filters-core.css',
                'include/javascript/yui3/build/console/assets/skins/sam/console-filters-skin.css',
                'include/javascript/yui3/build/console/assets/skins/sam/console-filters.css',
                'include/javascript/yui3/build/console/console-filters-min.js',
                'include/javascript/yui3/build/console/console-filters.js',
                'include/javascript/yui3/build/cssbase/base-context-min.css',
                'include/javascript/yui3/build/cssbase/base-context.css',
                'include/javascript/yui3/build/cssbase/base-min.css',
                'include/javascript/yui3/build/cssbase/base.css',
                'include/javascript/yui3/build/cssfonts/fonts-context-min.css',
                'include/javascript/yui3/build/cssfonts/fonts-context.css',
                'include/javascript/yui3/build/cssfonts/fonts-min.css',
                'include/javascript/yui3/build/cssfonts/fonts.css',
                'include/javascript/yui3/build/cssgrids/grids-min.css',
                'include/javascript/yui3/build/cssgrids/grids.css',
                'include/javascript/yui3/build/cssgrids-deprecated',
                'include/javascript/yui3/build/cssreset/reset-context-min.css',
                'include/javascript/yui3/build/cssreset/reset-context.css',
                'include/javascript/yui3/build/cssreset/reset-min.css',
                'include/javascript/yui3/build/cssreset/reset.css',
                'include/javascript/yui3/build/dataschema',
                'include/javascript/yui3/build/datasource',
                'include/javascript/yui3/build/datatable',
                'include/javascript/yui3/build/datatype',
                'include/javascript/yui3/build/dd',
                'include/javascript/yui3/build/dom',
                'include/javascript/yui3/build/editor',
                'include/javascript/yui3/build/event',
                'include/javascript/yui3/build/event-custom',
                'include/javascript/yui3/build/event-gestures',
                'include/javascript/yui3/build/highlight',
                'include/javascript/yui3/build/history',
                'include/javascript/yui3/build/history-deprecated',
                'include/javascript/yui3/build/io',
                'include/javascript/yui3/build/json',
                'include/javascript/yui3/build/jsonp/jsonp-url-min.js',
                'include/javascript/yui3/build/jsonp/jsonp-url.js',
                'include/javascript/yui3/build/loader/loader-base-min.js',
                'include/javascript/yui3/build/loader/loader-base.js',
                'include/javascript/yui3/build/loader/loader-rollup-min.js',
                'include/javascript/yui3/build/loader/loader-rollup.js',
                'include/javascript/yui3/build/loader/loader-yui3-min.js',
                'include/javascript/yui3/build/loader/loader-yui3.js',
                'include/javascript/yui3/build/node',
                'include/javascript/yui3/build/pluginhost',
                'include/javascript/yui3/build/profiler',
                'include/javascript/yui3/build/querystring',
                'include/javascript/yui3/build/recordset',
                'include/javascript/yui3/build/resize',
                'include/javascript/yui3/build/scrollview/assets',
                'include/javascript/yui3/build/scrollview/scrollview-base-ie-min.js',
                'include/javascript/yui3/build/scrollview/scrollview-base-ie.js',
                'include/javascript/yui3/build/scrollview/scrollview-base-min.js',
                'include/javascript/yui3/build/scrollview/scrollview-base.js',
                'include/javascript/yui3/build/scrollview/scrollview-paginator-min.js',
                'include/javascript/yui3/build/scrollview/scrollview-paginator.js',
                'include/javascript/yui3/build/scrollview/scrollview-scrollbars-min.js',
                'include/javascript/yui3/build/scrollview/scrollview-scrollbars.js',
                'include/javascript/yui3/build/simpleyui',
                'include/javascript/yui3/build/slider',
                'include/javascript/yui3/build/sortable/sortable-scroll-min.js',
                'include/javascript/yui3/build/sortable/sortable-scroll.js',
                'include/javascript/yui3/build/tabview/assets',
                'include/javascript/yui3/build/tabview/tabview-base-min.js',
                'include/javascript/yui3/build/tabview/tabview-base.js',
                'include/javascript/yui3/build/tabview/tabview-plugin-min.js',
                'include/javascript/yui3/build/tabview/tabview-plugin.js',
                'include/javascript/yui3/build/test/assets',
                'include/javascript/yui3/build/text',
                'include/javascript/yui3/build/transition/transition-native-min.js',
                'include/javascript/yui3/build/transition/transition-native.js',
                'include/javascript/yui3/build/transition/transition-timer-min.js',
                'include/javascript/yui3/build/transition/transition-timer.js',
                'include/javascript/yui3/build/uploader/assets/uploader.swf',
                'include/javascript/yui3/build/widget',
                'include/javascript/yui3/build/yui/features-min.js',
                'include/javascript/yui3/build/yui/features.js',
                'include/javascript/yui3/build/yui/get-min.js',
                'include/javascript/yui3/build/yui/get.js',
                'include/javascript/yui3/build/yui/intl-base-min.js',
                'include/javascript/yui3/build/yui/intl-base.js',
                'include/javascript/yui3/build/yui/rls-min.js',
                'include/javascript/yui3/build/yui/rls.js',
                'include/javascript/yui3/build/yui/yui-base-min.js',
                'include/javascript/yui3/build/yui/yui-base.js',
                'include/javascript/yui3/build/yui/yui-later-min.js',
                'include/javascript/yui3/build/yui/yui-later.js',
                'include/javascript/yui3/build/yui/yui-log-min.js',
                'include/javascript/yui3/build/yui/yui-log.js',
                'include/javascript/yui3/build/yui/yui-throttle-min.js',
                'include/javascript/yui3/build/yui/yui-throttle.js',
                'include/javascript/yui3/index.html',
                'jssource/src_files/clients/base/fields/listeditable',
                'jssource/src_files/clients/base/views/agenda',
                'jssource/src_files/clients/base/views/detail',
                'jssource/src_files/clients/base/views/edit',
                'jssource/src_files/clients/base/views/funnel',
                'jssource/src_files/clients/base/views/influencers',
                'jssource/src_files/clients/base/views/leaderboard',
                'jssource/src_files/clients/base/views/link-headerpane',
                'jssource/src_files/clients/base/views/modulelist',
                'jssource/src_files/clients/base/views/property-table',
                'jssource/src_files/clients/base/views/raw',
                'jssource/src_files/clients/base/views/recommended-experts',
                'jssource/src_files/clients/base/views/results',
                'jssource/src_files/clients/base/views/similar-opportunities',
                'jssource/src_files/clients/base/views/subdetail',
                'jssource/src_files/clients/base/views/subnav',
                'jssource/src_files/clients/base/views/treemap',
                'jssource/src_files/clients/portal/views/detail',
                'jssource/src_files/clients/portal/views/subnav',
                'jssource/src_files/include/javascript/nvd3/src/models/multiBarChartAllAxis.js',
                'jssource/src_files/include/javascript/nvd3/src/models/multiBarHorizontal.js',
                'jssource/src_files/include/javascript/nvd3/src/models/paretoLegend.js',
                'jssource/src_files/include/javascript/nvd3/nv.d3.min.js',
                'jssource/src_files/include/javascript/select2-release-3.3.2',
                'jssource/src_files/include/javascript/sugar7/plugins/Overscroll.js',
                'jssource/src_files/include/javascript/yui3/assets',
                'jssource/src_files/include/javascript/yui3/build/anim',
                'jssource/src_files/include/javascript/yui3/build/attribute',
                'jssource/src_files/include/javascript/yui3/build/autocomplete',
                'jssource/src_files/include/javascript/yui3/build/base',
                'jssource/src_files/include/javascript/yui3/build/cache',
                'jssource/src_files/include/javascript/yui3/build/charts',
                'jssource/src_files/include/javascript/yui3/build/collection',
                'jssource/src_files/include/javascript/yui3/build/compat',
                'jssource/src_files/include/javascript/yui3/build/console/console-filters-min.js',
                'jssource/src_files/include/javascript/yui3/build/console/console-filters.js',
                'jssource/src_files/include/javascript/yui3/build/dataschema',
                'jssource/src_files/include/javascript/yui3/build/datasource',
                'jssource/src_files/include/javascript/yui3/build/datatable',
                'jssource/src_files/include/javascript/yui3/build/datatype',
                'jssource/src_files/include/javascript/yui3/build/dd',
                'jssource/src_files/include/javascript/yui3/build/dom',
                'jssource/src_files/include/javascript/yui3/build/editor',
                'jssource/src_files/include/javascript/yui3/build/event',
                'jssource/src_files/include/javascript/yui3/build/event-custom',
                'jssource/src_files/include/javascript/yui3/build/event-gestures',
                'jssource/src_files/include/javascript/yui3/build/highlight',
                'jssource/src_files/include/javascript/yui3/build/history',
                'jssource/src_files/include/javascript/yui3/build/history-deprecated',
                'jssource/src_files/include/javascript/yui3/build/io',
                'jssource/src_files/include/javascript/yui3/build/json',
                'jssource/src_files/include/javascript/yui3/build/jsonp/jsonp-url-min.js',
                'jssource/src_files/include/javascript/yui3/build/jsonp/jsonp-url.js',
                'jssource/src_files/include/javascript/yui3/build/loader/loader-base-min.js',
                'jssource/src_files/include/javascript/yui3/build/loader/loader-base.js',
                'jssource/src_files/include/javascript/yui3/build/loader/loader-rollup-min.js',
                'jssource/src_files/include/javascript/yui3/build/loader/loader-rollup.js',
                'jssource/src_files/include/javascript/yui3/build/loader/loader-yui3-min.js',
                'jssource/src_files/include/javascript/yui3/build/loader/loader-yui3.js',
                'jssource/src_files/include/javascript/yui3/build/node',
                'jssource/src_files/include/javascript/yui3/build/pluginhost',
                'jssource/src_files/include/javascript/yui3/build/profiler',
                'jssource/src_files/include/javascript/yui3/build/querystring',
                'jssource/src_files/include/javascript/yui3/build/recordset',
                'jssource/src_files/include/javascript/yui3/build/resize',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-base-ie-min.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-base-ie.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-base-min.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-base.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-paginator-min.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-paginator.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-scrollbars-min.js',
                'jssource/src_files/include/javascript/yui3/build/scrollview/scrollview-scrollbars.js',
                'jssource/src_files/include/javascript/yui3/build/simpleyui',
                'jssource/src_files/include/javascript/yui3/build/slider',
                'jssource/src_files/include/javascript/yui3/build/sortable/sortable-scroll-min.js',
                'jssource/src_files/include/javascript/yui3/build/sortable/sortable-scroll.js',
                'jssource/src_files/include/javascript/yui3/build/tabview/tabview-base-min.js',
                'jssource/src_files/include/javascript/yui3/build/tabview/tabview-base.js',
                'jssource/src_files/include/javascript/yui3/build/tabview/tabview-plugin-min.js',
                'jssource/src_files/include/javascript/yui3/build/tabview/tabview-plugin.js',
                'jssource/src_files/include/javascript/yui3/build/text',
                'jssource/src_files/include/javascript/yui3/build/transition/transition-native-min.js',
                'jssource/src_files/include/javascript/yui3/build/transition/transition-native.js',
                'jssource/src_files/include/javascript/yui3/build/transition/transition-timer-min.js',
                'jssource/src_files/include/javascript/yui3/build/transition/transition-timer.js',
                'jssource/src_files/include/javascript/yui3/build/widget',
                'jssource/src_files/include/javascript/yui3/build/yui/features-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/features.js',
                'jssource/src_files/include/javascript/yui3/build/yui/get-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/get.js',
                'jssource/src_files/include/javascript/yui3/build/yui/intl-base-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/intl-base.js',
                'jssource/src_files/include/javascript/yui3/build/yui/rls-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/rls.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-base-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-base.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-later-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-later.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-log-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-log.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-throttle-min.js',
                'jssource/src_files/include/javascript/yui3/build/yui/yui-throttle.js',
                'jssource/src_files/modules/Forecasts/clients/base/plugins/DisableMassdelete.js',
                'jssource/src_files/modules/Forecasts/forecast.js',
                'jssource/src_files/modules/Notifications/clients/base/fields/datetimecombo',
                'jssource/src_files/modules/Notifications/clients/base/views',
                'jssource/src_files/modules/Styleguide/clients/base/views/docs',
                'LICENSE.txt',
                'modules/Accounts/clients/base/views/detail',
                'modules/Accounts/clients/base/views/edit',
                'modules/Administration/templates/ConfigureAjaxUI.tpl',
                'modules/Audit/clients/base/menus',
                'modules/Bugs/clients/portal/layouts/edit',
                'modules/Cases/clients/portal/layouts/edit',
                'modules/Charts/clients',
                'modules/Connectors/connectors/formatters/ext/rest/dnb/dnb.jpg',
                'modules/EAPM/clients',
                'modules/EmailAddresses/clients',
                'modules/Emails/clients/base/views/panel-top/panel-top.js',
                'modules/Expressions/clients',
                'modules/Forecasts/clients/base/plugins/DisableMassdelete.js',
                'modules/Forecasts/forecast.js',
                'modules/History/clients/base/menus',
                'modules/Home/index.php',
                'modules/ModuleBuilder/clients',
                'modules/ModuleBuilder/tpls/portalpreview.tpl',
                'modules/ModuleBuilder/views/view.portalpreview.php',
                'modules/MySettings/clients',
                'modules/Notifications/clients/base/fields/datetimecombo',
                'modules/Notifications/clients/base/layouts',
                'modules/Notifications/clients/base/views/raw',
                'modules/Opportunities/metadata/portal',
                'modules/OutboundEmailConfiguration/clients/base/menus',
                'modules/ProductBundleNotes/clients',
                'modules/ProductBundles/clients',
                'modules/Products/clients/base/views/list-headerpane',
                'modules/ProjectResources',
                'modules/ProjectTask/clents',
                'modules/Relationships/clients',
                'modules/RevenueLineItems/clients/base/views/list-headerpane',
                'modules/SNIP/clients',
                'modules/SchedulersJobs/clients',
                'modules/Studio/clients',
                'modules/Styleguide/clients/base/views/content',
                'modules/Styleguide/clients/base/views/docs',
                'modules/Styleguide/clients/base/views/list/list_doc.hbs',
                'modules/SugarFavorites/clients',
                'modules/Trackers/clients',
                'modules/UserPreferences/clients',
                'modules/UserSignatures/clients/base/menus',
                'modules/Versions/clients',
                'modules/WebLogicHooks/clients/base/layouts',
                'modules/WebLogicHooks/clients/base/views/list-headerpane',
                'modules/WorkFlowAlertShells/clients',
                'modules/WorkFlowAlerts/clients',
                'modules/WorkFlowTriggerShells/clients',
                'modules/iCals/clients',
                'modules/vCals/clients',
                'portal2/tests',
                'styleguide/content/charts/data/bubble_data.js',
                'styleguide/content/charts/data/flare.js',
                'styleguide/content/charts/data/funnel_data.js',
                'styleguide/content/charts/data/gauge_data.js',
                'styleguide/content/charts/data/horizbar_data.js',
                'styleguide/content/charts/data/line_data.js',
                'styleguide/content/charts/data/multibar_data.js',
                'styleguide/content/charts/data/pareto_data.js',
                'styleguide/content/charts/data/pie_data.js',
                'styleguide/content/charts/data/sankey-wonlost-source.json',
                'styleguide/content/charts/data/top10_opportunities_debug_grp.json',
                'styleguide/content/charts/data/top10_opportunities_debug_ind.json',
                'styleguide/content/charts/data/top10_opportunities_preprocessed.json',
                'styleguide/content/charts/data/tree_data.js',
                'styleguide/content/charts/data/treemap_data.js',
                'styleguide/content/charts/data/us-states.json',
                'styleguide/content/charts/data/world-countries.json',
                'styleguide/content/charts/globeChart.html',
                'styleguide/content/charts/sankey.html',
                'styleguide/content/charts/svg/newcharticons.graffle',
                'styleguide/content/css',
                'styleguide/content/js/backbone-min.js',
                'styleguide/content/js/chart-utils.js',
                'styleguide/content/js/datatable-data.js',
                'styleguide/content/js/jquery-1.7.2.min.js',
                'styleguide/content/js/jquery-ui-1.10.0.custom.min.js',
                'styleguide/content/js/jquery-ui-1.8.18.custom.min.js',
                'styleguide/content/js/jquery.form.js',
                'styleguide/content/js/less-1.3.3.min.js',
                'styleguide/content/js/underscore-min.js',
                'styleguide/content/js/wizard.js',
                'styleguide/content/wizard-modal.html',
                'styleguide/less/clients/base/config.less',
                'styleguide/less/clients/mobile',
                'styleguide/less/clients/portal/alerts.less',
                'styleguide/less/clients/portal/card.less',
                'styleguide/less/clients/portal/drawer.less',
                'styleguide/less/clients/portal/subnav.less',
                'styleguide/less/sugar-specific/breadcrumbs.less',
                'styleguide/less/sugar-specific/editable.less',
                'styleguide/less/sugar-specific/jquery.timepicker.less',
                'styleguide/less/sugar-specific/quicklinks.less',
                'styleguide/less/sugar-specific/setup.less',
                'styleguide/less/sugar-specific/subnav.less',
                'styleguide/less/sugar-specific/sugar-forecast.less',
                'styleguide/less/sugar-specific/tables.less',
                'styleguide/less/sugar-specific/widgets.less',
                'styleguide/less/sugar-specific/wizard.less',
                'styleguide/themes/clients/base/custom chart colors',
                'vendor/Elastica/Exception/Abstract.php',
                'vendor/Elastica/Exception/BulkResponse.php',
                'vendor/Elastica/Exception/Client.php',
                'vendor/Elastica/Exception/Invalid.php',
                'vendor/Elastica/Exception/NotFound.php',
                'vendor/Elastica/Exception/NotImplemented.php',
                'vendor/Elastica/Exception/Response.php',
                'vendor/Elastica/Facet/Abstract.php',
                'vendor/Elastica/Filter/Abstract.php',
                'vendor/Elastica/Filter/And.php',
                'vendor/Elastica/Filter/Not.php',
                'vendor/Elastica/Filter/Or.php',
                'vendor/Elastica/Query/Abstract.php',
                'vendor/Elastica/Query/Array.php',
                'vendor/Elastica/Query/Field.php',
                'vendor/Elastica/Query/Text.php',
                'vendor/Elastica/Searchable.php',
                'vendor/Elastica/Transport/Abstract.php',
                'vendor/Elastica/Type/Abstract.php',
            );
        }
        return $files;
    }
}
