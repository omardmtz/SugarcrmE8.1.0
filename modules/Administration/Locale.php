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


global $current_user, $sugar_config;
if (!is_admin($current_user)) {
    sugar_die("Unauthorized access to administration.");
}



echo getClassicModuleTitle(
    "Administration",
    array(
        "<a href='index.php?module=Administration&action=index'>" . translate(
            'LBL_MODULE_NAME',
            'Administration'
        ) . "</a>",
        $mod_strings['LBL_MANAGE_LOCALE'],
    ),
    false
);

$cfg = new Configurator();
$sugar_smarty = new Sugar_Smarty();
$errors = array();

///////////////////////////////////////////////////////////////////////////////
////	HANDLE CHANGES
if (isset($_REQUEST['process']) && $_REQUEST['process'] == 'true') {

    $previousDefaultLanguage = $sugar_config['default_language'];

    if (isset($_REQUEST['collation']) && !empty($_REQUEST['collation'])) {
        //kbrill Bug #14922
        if (array_key_exists(
                'collation',
                $sugar_config['dbconfigoption']
            ) && $_REQUEST['collation'] != $sugar_config['dbconfigoption']['collation']
        ) {
            $GLOBALS['db']->disconnect();
            $GLOBALS['db']->connect();
        }

        $GLOBALS['db']->setCollation($_REQUEST['collation']);
        $cfg->config['dbconfigoption']['collation'] = $_REQUEST['collation'];
    }
    $cfg->populateFromPost();
    $cfg->handleOverride();
    if ($locale->invalidLocaleNameFormatUpgrade()) {
        $locale->removeInvalidLocaleNameFormatUpgradeNotice();
    }

    // Metadata sections that have to be refreshed on `Save`.
    $refreshSections = array(
        MetaDataManager::MM_CURRENCIES,
        MetaDataManager::MM_LABELS,
        MetaDataManager::MM_ORDEREDLABELS,
    );
    $mm = MetaDataManager::getManager();
    $mm->refreshSectionCache($refreshSections);

    // Call `ping` API to refresh the metadata.
    echo "
        <script>
        var app = window.parent.SUGAR.App;
        app.api.call('read', app.api.buildURL('ping'));
        app.router.navigate('#bwc/index.php?module=Administration&action=index', {trigger:true, replace:true});
        </script>
    ";
} else {

///////////////////////////////////////////////////////////////////////////////
////	DB COLLATION
    $collationOptions = $GLOBALS['db']->getCollationList();
    if (!empty($collationOptions)) {
        if (!isset($sugar_config['dbconfigoption']['collation'])) {
            $sugar_config['dbconfigoption']['collation'] = $GLOBALS['db']->getDefaultCollation();
        }
        $sugar_smarty->assign(
            'collationOptions',
            get_select_options_with_id(
                array_combine($collationOptions, $collationOptions),
                $sugar_config['dbconfigoption']['collation']
            )
        );
    }
////	END DB COLLATION
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	PAGE OUTPUT
    $sugar_smarty->assign('MOD', $mod_strings);
    $sugar_smarty->assign('APP', $app_strings);
    $sugar_smarty->assign('APP_LIST', $app_list_strings);
    $sugar_smarty->assign('LANGUAGES', get_languages());
    $sugar_smarty->assign("JAVASCRIPT", get_set_focus_js());
    $sugar_smarty->assign('config', $sugar_config);
    $sugar_smarty->assign('error', $errors);
    $sugar_smarty->assign(
        "exportCharsets",
        get_select_options_with_id($locale->getCharsetSelect(), $sugar_config['default_export_charset'])
    );

    $sugar_smarty->assign('NAMEFORMATS', $locale->getUsableLocaleNameOptions($sugar_config['name_formats']));

    if ($locale->invalidLocaleNameFormatUpgrade()) {
        $sugar_smarty->assign('upgradeInvalidLocaleNameFormat', 'bad name format upgrade');
    } else {
        $sugar_smarty->clear_assign('upgradeInvalidLocaleNameFormat');
    }

    $sugar_smarty->assign('getNameJs', $locale->getNameJs());

    $sugar_smarty->display('modules/Administration/Locale.tpl');

}
