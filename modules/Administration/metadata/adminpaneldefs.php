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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication;

global $current_user,$admin_group_header;

//users and security.
$admin_option_defs=array();
$admin_option_defs['Users']['user_management']= array('Users','LBL_MANAGE_USERS_TITLE','LBL_MANAGE_USERS','./index.php?module=Users&action=index');
$admin_option_defs['Users']['roles_management']= array('Roles','LBL_MANAGE_ROLES_TITLE','LBL_MANAGE_ROLES','./index.php?module=ACLRoles&action=index');
$admin_option_defs['Users']['teams_management']= array('Teams','LBL_MANAGE_TEAMS_TITLE','LBL_MANAGE_TEAMS','./index.php?module=Teams&action=index');

$idpConfig = new Authentication\Config(\SugarConfig::getInstance());
$idmModeConfig = $idpConfig->getIDMModeConfig();
if ($idpConfig->isIDMModeEnabled()) {
    $passwordManagerUrl = $idpConfig->buildCloudConsoleUrl('passwordManagement');
    $passwordManagerTarget = '_blank';
    $passwordManagerOnClick = sprintf(
        'onclick = "app.alert.show(\'disabled-for-idm-mode\', {level: \'warning\', messages: \'%s\'});"',
        $GLOBALS['app_strings']['ERR_DISABLED_FOR_IDM_MODE']
    );
} else {
    $passwordManagerUrl = './index.php?module=Administration&action=PasswordManager';
    $passwordManagerTarget = '_self';
    $passwordManagerOnClick = null;
}

$admin_option_defs['Administration']['password_management'] = array(
    'Password',
    'LBL_MANAGE_PASSWORD_TITLE',
    'LBL_MANAGE_PASSWORD',
    $passwordManagerUrl,
    null,
    $passwordManagerOnClick,
    $passwordManagerTarget,
);

$admin_option_defs['Users']['tba_management'] = array('TbACLs', 'LBL_TBA_CONFIGURATION', 'LBL_TBA_CONFIGURATION_DESC', './index.php?module=Teams&action=tba');
$admin_group_header[]= array('LBL_USERS_TITLE','',false,$admin_option_defs, 'LBL_USERS_DESC');
$license_management = false;
    if (!isset($GLOBALS['sugar_config']['hide_admin_licensing']) || !$GLOBALS['sugar_config']['hide_admin_licensing']) {
        $license_management = array('License','LBL_MANAGE_LICENSE_TITLE','LBL_MANAGE_LICENSE','./index.php?module=Administration&action=LicenseSettings');
    }


//Sugar Connect
$admin_option_defs=array();
$license_key = 'no_key';

$admin_option_defs['Administration']['license_management']= $license_management;
$focus = Administration::getSettings();
$license_key = $focus->settings['license_key'];

$admin_option_defs['Administration']['update'] = array('sugarupdate','LBL_SUGAR_UPDATE_TITLE','LBL_SUGAR_UPDATE','./index.php?module=Administration&action=Updater');
$admin_option_defs['Administration']['documentation']= array('OnlineDocumentation','LBL_DOCUMENTATION_TITLE','LBL_DOCUMENTATION',
        'javascript:void window.open("index.php?module=Administration&action=SupportPortal&view=documentation&help_module=Administration&edition='.$sugar_flavor.'&key='.$server_unique_key.'&language='.$current_language.'", "helpwin","width=600,height=600,status=0,resizable=1,scrollbars=1,toolbar=0,location=0")');
if(!empty($license->settings['license_latest_versions'])){
	$encodedVersions = $license->settings['license_latest_versions'];
	$versions = unserialize(base64_decode( $encodedVersions));
	include('sugar_version.php');
	if(!empty($versions)){
		foreach($versions as $version){
			if(compareVersions($version['version'], $sugar_version))
			{
				$admin_option_defs['Administration']['update'][] ='red';
				if(!isset($admin_option_defs['Administration']['update']['additional_label']))$admin_option_defs['Administration']['update']['additional_label']= '('.$version['version'].')';

			}
		}
	}
}



$admin_group_header[]= array('LBL_SUGAR_NETWORK_TITLE','',false,$admin_option_defs, 'LBL_SUGAR_NETWORK_DESC');


//system.
$admin_option_defs=array();
$admin_option_defs['Administration']['configphp_settings']= array('Administration','LBL_CONFIGURE_SETTINGS_TITLE','LBL_CONFIGURE_SETTINGS','./index.php?module=Configurator&action=EditView');
$admin_option_defs['Administration']['import']= array('Import','LBL_IMPORT_WIZARD','LBL_IMPORT_WIZARD_DESC','./index.php?module=Import&action=step1&import_module=Administration');
$admin_option_defs['Administration']['locale']= array('Currencies','LBL_MANAGE_LOCALE','LBL_LOCALE','./index.php?module=Administration&action=Locale&view=default');

if (!isset($GLOBALS['sugar_config']['disable_uw_upload']) || !$GLOBALS['sugar_config']['disable_uw_upload']) {
    $admin_option_defs['Administration']['upgrade_wizard']= array('Upgrade','LBL_UPGRADE_WIZARD_TITLE','LBL_UPGRADE_WIZARD','./UpgradeWizard.php');
}

$admin_option_defs['Administration']['currencies_management']= array('Currencies','LBL_MANAGE_CURRENCIES','LBL_CURRENCY','javascript:parent.SUGAR.App.router.navigate("Currencies", {trigger: true});');

if (!isset($GLOBALS['sugar_config']['hide_admin_backup']) || !$GLOBALS['sugar_config']['hide_admin_backup'])
{
    $admin_option_defs['Administration']['backup_management']= array('Backups','LBL_BACKUPS_TITLE','LBL_BACKUPS','./index.php?module=Administration&action=Backups');
}

$admin_option_defs['Administration']['languages']= array('Currencies','LBL_MANAGE_LANGUAGES','LBL_LANGUAGES','./index.php?module=Administration&action=Languages&view=default');

$admin_option_defs['Administration']['repair']= array('Repair','LBL_UPGRADE_TITLE','LBL_UPGRADE','./index.php?module=Administration&action=Upgrade');

$admin_option_defs['Administration']['global_search']=array('icon_SearchForm','LBL_GLOBAL_SEARCH_SETTINGS','LBL_GLOBAL_SEARCH_SETTINGS_DESC','./index.php?module=Administration&action=GlobalSearchSettings');

if (!isset($GLOBALS['sugar_config']['hide_admin_diagnostics']) || !$GLOBALS['sugar_config']['hide_admin_diagnostics'])
{
    $admin_option_defs['Administration']['diagnostic']= array('Diagnostic','LBL_DIAGNOSTIC_TITLE','LBL_DIAGNOSTIC_DESC','./index.php?module=Administration&action=Diagnostic');
}

// Connector Integration
$admin_option_defs['Administration']['connector_settings']=array('icon_Connectors','LBL_CONNECTOR_SETTINGS','LBL_CONNECTOR_SETTINGS_DESC','./index.php?module=Connectors&action=ConnectorSettings');

$admin_option_defs['Administration']['tracker_settings']=array('Trackers','LBL_TRACKER_SETTINGS','LBL_TRACKER_SETTINGS_DESC','./index.php?module=Trackers&action=TrackerSettings');

$admin_option_defs['Administration']['scheduler'] = array('Schedulers','LBL_SUGAR_SCHEDULER_TITLE','LBL_SUGAR_SCHEDULER','./index.php?module=Schedulers&action=index');

$admin_option_defs['Administration']['pdfmanager']= array('icon_PdfManager','LBL_PDFMANAGER_SETTINGS','LBL_PDFMANAGER_SETTINGS_DESC','./index.php?module=PdfManager&action=index');

// Enable/Disable wireless modules
$admin_option_defs['Administration']['enable_wireless_modules']=array('icon_AdminMobile','LBL_WIRELESS_MODULES_ENABLE','LBL_WIRELESS_MODULES_ENABLE_DESC','./index.php?module=Administration&action=EnableWirelessModules');
$admin_option_defs['Administration']['web_logic_hooks']=array('Administration','LBL_WEB_LOGIC_HOOKS','LBL_WEB_LOGIC_HOOKS_DESC','javascript:parent.SUGAR.App.router.navigate("WebLogicHooks", {trigger: true});');



if(SugarOAuthServer::enabled()) {
    $admin_option_defs['Administration']['oauth']= array('Password','LBL_OAUTH_TITLE','LBL_OAUTH','./index.php?module=OAuthKeys&action=index');
}


$admin_group_header[]= array('LBL_ADMINISTRATION_HOME_TITLE','',false,$admin_option_defs, 'LBL_ADMINISTRATION_HOME_DESC');


//email manager.
$admin_option_defs=array();
$admin_option_defs['Emails']['mass_Email_config']= array('EmailMan','LBL_MASS_EMAIL_CONFIG_TITLE','LBL_MASS_EMAIL_CONFIG_DESC','./index.php?module=EmailMan&action=config');

$admin_option_defs['Campaigns']['campaignconfig']= array('Campaigns','LBL_CAMPAIGN_CONFIG_TITLE','LBL_CAMPAIGN_CONFIG_DESC','./index.php?module=EmailMan&action=campaignconfig');

$admin_option_defs['Emails']['mailboxes']= array('InboundEmail','LBL_MANAGE_MAILBOX','LBL_MAILBOX_DESC','./index.php?module=InboundEmail&action=index');
$admin_option_defs['Campaigns']['mass_Email']= array('EmailMan','LBL_MASS_EMAIL_MANAGER_TITLE','LBL_MASS_EMAIL_MANAGER_DESC','./index.php?module=EmailMan&action=index');
$admin_option_defs['Emails']['history_contacts_emails'] = array('ConfigureTabs', 'LBL_HISTORY_CONTACTS_EMAILS', 'LBL_HISTORY_CONTACTS_EMAILS_DESC', './index.php?module=Configurator&action=historyContactsEmails');

$admin_option_defs['Campaigns']['register_snip']=array('icon_AdminThemes','LBL_CONFIGURE_SNIP','LBL_CONFIGURE_SNIP_DESC','./index.php?module=SNIP&action=ConfigureSnip');

$admin_group_header[]= array('LBL_EMAIL_TITLE','',false,$admin_option_defs, 'LBL_EMAIL_DESC');




//studio.
$admin_option_defs=array();
$admin_option_defs['studio']['studio']= array('Studio','LBL_STUDIO','LBL_STUDIO_DESC','./index.php?module=ModuleBuilder&action=index&type=studio');
if(isset($GLOBALS['beanFiles']['iFrame'])) {
	$admin_option_defs['Administration']['portal']= array('iFrames','LBL_IFRAME','DESC_IFRAME','./index.php?module=iFrames&action=index');
}
$admin_option_defs['Administration']['rename_tabs']= array('RenameTabs','LBL_RENAME_TABS','LBL_CHANGE_NAME_MODULES',"./index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs");
$admin_option_defs['Administration']['moduleBuilder']= array('ModuleBuilder','LBL_MODULEBUILDER','LBL_MODULEBUILDER_DESC','./index.php?module=ModuleBuilder&action=index&type=mb');
$admin_option_defs['Administration']['configure_tabs']= array('ConfigureTabs','LBL_CONFIGURE_TABS_AND_SUBPANELS','LBL_CONFIGURE_TABS_AND_SUBPANELS_DESC','./index.php?module=Administration&action=ConfigureTabs');
$admin_option_defs['Administration']['module_loader'] = array('ModuleLoader','LBL_MODULE_LOADER_TITLE','LBL_MODULE_LOADER','./index.php?module=Administration&action=UpgradeWizard&view=module');


$admin_option_defs['Administration']['config_prod_bar']=array('icon_ShortcutBar','LBL_CONFIGURE_SHORTCUT_BAR','LBL_CONFIGURE_SHORTCUT_BAR_DESC','./index.php?module=Administration&action=ConfigureShortcutBar');

$admin_option_defs['any']['dropdowneditor']= array('Dropdown','LBL_DROPDOWN_EDITOR','DESC_DROPDOWN_EDITOR','./index.php?module=ModuleBuilder&action=index&type=dropdowns');

$admin_option_defs['Administration']['sugarportal']= array('SugarPortal','LBL_SUGARPORTAL','LBL_SUGARPORTAL_DESC','./index.php?module=ModuleBuilder&action=index&type=sugarportal');

//$admin_option_defs['migrate_custom_fields']= array('MigrateFields','LBL_EXTERNAL_DEV_TITLE','LBL_EXTERNAL_DEV_DESC','./index.php?module=Administration&action=Development');

$admin_option_defs['any']['workflow_management']= array('WorkFlow','LBL_MANAGE_WORKFLOW','LBL_WORKFLOW_DESC','./index.php?module=WorkFlow&action=ListView');
$admin_option_defs['Administration']['api_platforms'] = [
    'Administration',
    'LBL_CONFIGURE_CUSTOM_API_PLATFORMS',
    'LBL_CUSTOM_API_PLATFORMS_DESC',
    './index.php?module=Administration&action=apiplatforms',
];

$admin_option_defs['Administration']['styleguide'] = array(
    'Documents',
    'LBL_MANAGE_STYLEGUIDE',
    'LBL_MANAGE_STYLEGUIDE_TITLE',
    'javascript:parent.SUGAR.App.router.navigate("Styleguide", {trigger: true});',
);

$admin_group_header[]= array('LBL_STUDIO_TITLE','',false,$admin_option_defs, 'LBL_TOOLS_DESC');


//product catalog.

$admin_option_defs=array();
$admin_option_defs['Products']['product_catalog']= array('Products','LBL_PRODUCTS_TITLE','LBL_PRODUCTS','javascript:parent.SUGAR.App.router.navigate("ProductTemplates", {trigger: true});');
$admin_option_defs['Products']['manufacturers']= array('Manufacturers','LBL_MANUFACTURERS_TITLE','LBL_MANUFACTURERS','javascript:parent.SUGAR.App.router.navigate("Manufacturers", {trigger: true});');
$admin_option_defs['Products']['product_categories']= array('Product_Categories','LBL_PRODUCT_CATEGORIES_TITLE','LBL_PRODUCT_CATEGORIES','javascript:parent.SUGAR.App.router.navigate("ProductCategories", {trigger: true});');
$admin_option_defs['Products']['shipping_providers']= array('Shippers','LBL_SHIPPERS_TITLE','LBL_SHIPPERS','javascript:parent.SUGAR.App.router.navigate("Shippers", {trigger: true});');
$admin_option_defs['Products']['product_types']= array('Product_Types','LBL_PRODUCT_TYPES_TITLE','LBL_PRODUCT_TYPES','javascript:parent.SUGAR.App.router.navigate("ProductTypes", {trigger: true});');

$admin_option_defs['Quotes']['tax_rates']= array('TaxRates','LBL_TAXRATES_TITLE','LBL_TAXRATES','javascript:parent.SUGAR.App.router.navigate("TaxRates", {trigger: true});');

$admin_group_header[]= array('LBL_PRICE_LIST_TITLE','',false,$admin_option_defs, 'LBL_PRICE_LIST_DESC');
//bug tracker.
$admin_option_defs=array();
$admin_option_defs['Bugs']['bug_tracker']= array('Releases','LBL_MANAGE_RELEASES','LBL_RELEASE','./index.php?module=Releases&action=index');
$admin_group_header[]= array('LBL_BUG_TITLE','',false,$admin_option_defs, 'LBL_BUG_DESC');

//Forecasting
$admin_option_defs=array();
$admin_option_defs['Forecasts']['forecast_setup'] = array('ForecastReports','LBL_MANAGE_FORECASTS_TITLE', 'LBL_MANAGE_FORECASTS', 'javascript:void(parent.SUGAR.App.router.navigate("Forecasts/config", {trigger: true}));');
$admin_group_header[]= array('LBL_FORECAST_TITLE', '', false, $admin_option_defs, 'LBL_FORECAST_DESC');

//Opportunities
$admin_option_defs=array();
$admin_option_defs['Opportunities']['opportunities_setup'] = array('Opportunities','LBL_MANAGE_OPPORTUNITIES_TITLE', 'LBL_MANAGE_OPPORTUNITIES_DESC', 'javascript:void(parent.SUGAR.App.router.navigate("Opportunities/config", {trigger: true}));');
$admin_group_header[]= array('LBL_MANAGE_OPPORTUNITIES_TITLE', '', false, $admin_option_defs, 'LBL_OPPORTUNITIES_DESC');

//Contracts
$admin_option_defs=array();
$admin_option_defs['Contracts']['contract_type_management']= array('Contracts','LBL_MANAGE_CONTRACTEMPLATES_TITLE','LBL_CONTRACT_TYPES','javascript:parent.SUGAR.App.router.navigate("ContractTypes", {trigger: true});');

// fetch "Contracts" module name from localization data (bug #46740)
$admin_group_header[]= array($app_list_strings['moduleList']['Contracts'],'',false,$admin_option_defs, 'LBL_CONTRACT_DESC');


$admin_option_defs = array(
    'pmse_Project' => array(
        'Settings' => array(
            'Settings',
            'LBL_PMSE_ADMIN_TITLE_SETTINGS',
            'LBL_PMSE_ADMIN_DESC_SETTINGS',
            'javascript:parent.SUGAR.App.router.navigate("pmse_Inbox/layout/config", {trigger: true});',
        ),
        'CasesList' => array(
            'CasesList',
            'LBL_PMSE_ADMIN_TITLE_CASESLIST',
            'LBL_PMSE_ADMIN_DESC_CASESLIST',
            'javascript:parent.SUGAR.App.router.navigate("pmse_Inbox/layout/casesList", {trigger: true});',
        ),
        'EngineLogs' => array(
            'EngineLogs',
            'LBL_PMSE_ADMIN_TITLE_ENGINELOGS',
            'LBL_PMSE_ADMIN_DESC_ENGINELOGS',
            'javascript:parent.SUGAR.App.router.navigate("pmse_Inbox/layout/logView", {trigger: true});',
        ),
    )
);
$admin_group_header []= array(
    'LBL_PMSE_ADMIN_TITLE_MODULE',
    '',
    false,
    $admin_option_defs,
    'LBL_PMSE_ADMIN_DESC_MODULE'
);



if(SugarAutoLoader::existing('custom/modules/Administration/Ext/Administration/administration.ext.php')){
	include('custom/modules/Administration/Ext/Administration/administration.ext.php');
}

//For users with MLA access we need to find which entries need to be shown.
//lets process the $admin_group_header and apply all the access control rules.
$access = $current_user->getDeveloperModules();
foreach ($admin_group_header as $key=>$values) {
	$module_index = array_keys($values[3]);  //get the actual links..
	foreach ($module_index as $mod_key=>$mod_val) {
		if (is_admin($current_user) ||
			in_array($mod_val, $access) ||
		    $mod_val=='studio'||
		    ($mod_val=='Forecasts') ||
		    ($mod_val =='any')
		   ) {
		   	    if(!is_admin($current_user)&& isset($values[3]['Administration'])){
                    unset($values[3]['Administration']);
                }
                if(displayStudioForCurrentUser() == false) {
                    unset($values[3]['studio']);
                }

                if(displayWorkflowForCurrentUser() == false) {
                    unset($values[3]['any']['workflow_management']);
                }

                // Need this check because Quotes and Products share the header group
                if(!in_array('Quotes', $access)&& isset($values[3]['Quotes'])){
                    unset($values[3]['Quotes']);
                }
                if(!in_array('Products', $access)&& isset($values[3]['Products'])){
                    unset($values[3]['Products']);
                }

                // Need this check because Emails and Campaigns share the header group
                if(!in_array('Campaigns', $access)&& isset($values[3]['Campaigns'])){
                    unset($values[3]['Campaigns']);
                }

                // Unless a user is a system admin, or module admin, they cannot see Forecasts config links
                if($mod_val == 'Forecasts'
                    && !($current_user->isAdmin() || $current_user->isDeveloperForModule('Forecasts'))
                    && isset($values[3]['Forecasts']))
                {
                    unset($admin_group_header[$key][3][$mod_val]);
                }
                // Unless a user is a system admin, or module admin, they cannot see TBACLs config links
                if ($mod_val == 'Users'
                    && !$current_user->isAdminForModule('Users')
                    && isset($values[3]['Users']['tba_management'])
                ) {
                    unset($admin_group_header[$key][3][$mod_val]['tba_management']);
                }

                // Maintain same access for Opps as we have for Forecasts
                // Unless a user is a system admin, or module admin, they cannot see Forecasts config links
                if($mod_val == 'Opportunities'
                    && !($current_user->isAdmin() || $current_user->isDeveloperForModule('Opportunities'))
                    && isset($values[3]['Opportunities']))
                {
                    unset($admin_group_header[$key][3][$mod_val]);
                }

        } else {
        	//hide the link
        	unset($admin_group_header[$key][3][$mod_val]);
        }

	}
}
?>
