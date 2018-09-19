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

$idpConfig = new Authentication\Config(\SugarConfig::getInstance());

$moduleName = 'Administration';
$adminRoute = '#bwc/index.php?module=Administration&action=';
$viewdefs[$moduleName]['base']['menu']['sweetspot'] = array(
    // Users and security
    // User Management
    array(
        'label' => 'LBL_MANAGE_USERS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Users&action=index',
    ),
    // Role Management
    array(
        'label' => 'LBL_MANAGE_ROLES_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=ACLRoles&action=index',
    ),
    // Team Management
    array(
        'label' => 'LBL_MANAGE_TEAMS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Teams&action=index',
    ),

    // Team-based Permissions.
    array(
        'label' => 'LBL_TBA_CONFIGURATION',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Teams&action=tba',
    ),
    
    // Password Management
    array(
        'label' => 'LBL_MANAGE_PASSWORD_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'PasswordManager',
        'idm_mode_link' =>
            $idpConfig->isIDMModeEnabled() ? $idpConfig->buildCloudConsoleUrl('passwordManagement') : null,
    ),

    // Sugar Connect
    // License Management
    array(
        'label' => 'LBL_MANAGE_LICENSE_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'LicenseSettings',
    ),
    // Sugar Updates
    array(
        'label' => 'LBL_SUGAR_UPDATE_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'Updater',
    ),

    // System
    // System settings
    array(
        'label' => 'LBL_CONFIGURE_SETTINGS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Configurator&action=EditView',
    ),
    // Import Wizard
    array(
        'label' => 'LBL_IMPORT_WIZARD',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Import&action=step1&import_module=Administration',
    ),

    // Locale
    array(
        'label' => 'LBL_MANAGE_LOCALE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'Locale&view=default',
    ),

    // Currencies
    array(
        'label' => 'LBL_MANAGE_CURRENCIES',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#Currencies',
    ),
    // Backups
    array(
        'label' => 'LBL_BACKUPS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'Backups',
    ),

    // Languages
    array(
        'label' => 'LBL_MANAGE_LANGUAGES',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'Languages&view=default',
    ),
    // Repair
    array(
        'label' => 'LBL_UPGRADE_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'Upgrade',
    ),
    // -- Quick Repair and Rebuild
    array(
        'label' => 'LBL_QUICK_REPAIR_AND_REBUILD',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'repair',
    ),

    // Search
    array(
        'label' => 'LBL_GLOBAL_SEARCH_SETTINGS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'GlobalSearchSettings',
    ),
    // Diagnostic Tool
    array(
        'label' => 'LBL_DIAGNOSTIC_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'Diagnostic',
    ),

    // Connectors
    array(
        'label' => 'LBL_CONNECTOR_SETTINGS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Connectors&action=ConnectorSettings',
    ),
    // Tracker
    array(
        'label' => 'LBL_TRACKER_SETTINGS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Trackers&action=TrackerSettings',
    ),

    // Scheduler
    array(
        'label' => 'LBL_REBUILD_SCHEDULERS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Schedulers&action=index',
    ),
    // PDF Manager
    array(
        'label' => 'LBL_PDFMANAGER_SETTINGS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=PdfManager&action=index',
    ),
    // Mobile
    array(
        'label' => 'LBL_WIRELESS_MODULES_ENABLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'EnableWirelessModules',
    ),
    // Web Logic Hooks
    array(
        'label' => 'LBL_WEB_LOGIC_HOOKS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#WebLogicHooks',
    ),
    // OAuth Keys
    array(
        'label' => 'LBL_OAUTH_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=OAuthKeys&action=index',
    ),

    // Email
    // Email Settings
    array(
        'label' => 'LBL_MASS_EMAIL_CONFIG_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=EmailMan&action=config',
    ),
    // Imbound Email
    array(
        'label' => 'LBL_INBOUND_EMAIL_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=InboundEmail&action=index',
    ),

    // Related Contacts Emails
    array(
        'label' => 'LBL_HISTORY_CONTACTS_EMAILS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Configurator&action=historyContactsEmails',
    ),
    // Campaign Email Settings
    array(
        'label' => 'LBL_CAMPAIGN_CONFIG_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=EmailMan&action=campaignconfig',
    ),

    // Email Queue
    array(
        'label' => 'LBL_MASS_EMAIL_MANAGER_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=EmailMan&action=index',
    ),
    // Email Archiving
    array(
        'label' => 'LBL_CONFIGURE_SNIP',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=SNIP&action=ConfigureSnip',
    ),

    // Developer Tools
    // Studio
    array(
        'label' => 'LBL_STUDIO',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=ModuleBuilder&action=index&type=studio',
    ),
    // Rename modules
    array(
        'label' => 'LBL_RENAME_TABS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs',
    ),

    // Module Builder
    array(
        'label' => 'LBL_MODULEBUILDER',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=ModuleBuilder&action=index&type=mb',
    ),
    // Display Modules and Subpanels
    array(
        'label' => 'LBL_CONFIG_TABS',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'ConfigureTabs',
    ),

    // Module Loader
    array(
        'label' => 'LBL_MODULE_LOADER_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'UpgradeWizard&view=module',
    ),
    // Configure Navigation Bar Quick Create
    array(
        'label' => 'LBL_CONFIGURE_SHORTCUT_BAR',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => $adminRoute . 'ConfigureShortcutBar',
    ),
    // Sugar Portal
    array(
        'label' => 'LBL_SUGARPORTAL',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=ModuleBuilder&action=index&type=sugarportal',
    ),
    // Styleguide
    array(
        'label' => 'LBL_MANAGE_STYLEGUIDE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#Styleguide',
    ),

    // Dropdown Editor
    array(
        'label' => 'LBL_DROPDOWN_EDITOR',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=ModuleBuilder&action=index&type=dropdowns',
    ),
    // Workflow Management
    array(
        'label' => 'LBL_WORKFLOW_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=WorkFlow&action=ListView',
    ),

    // Product Catalog
    array(
        'label' => 'LBL_PRODUCTS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#ProductTemplates',
    ),
    // Manufacturers
    array(
        'label' => 'LBL_MANUFACTURERS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#Manufacturers',
    ),

    // Product Categories
    array(
        'label' => 'LBL_PRODUCT_CATEGORIES_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#ProductCategories',
    ),
    // Shipping Providers
    array(
        'label' => 'LBL_SHIPPERS_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#Shippers',
    ),

    // Product Types
    array(
        'label' => 'LBL_PRODUCT_TYPES_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#ProductTypes',
    ),
    // Tax Rates
    array(
        'label' => 'LBL_TAXRATES_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#TaxRates',
    ),

    // Releases
    array(
        'label' => 'LBL_MANAGE_RELEASES',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#bwc/index.php?module=Releases&action=index',
    ),

    // Contract Types
    array(
        'label' => 'LBL_MANAGE_CONTRACTEMPLATES_TITLE',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#ContractTypes',
    ),

    // Process Management
    array(
        'label' => 'LBL_PMSE_ADMIN_TITLE_CASESLIST',
        'acl_action' => 'studio',
        'module' => $moduleName,
        'icon' => 'fa-cogs',
        'route' => '#pmse_Inbox/layout/casesList',
    ),
);
