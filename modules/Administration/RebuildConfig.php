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




global $mod_strings;

// the initial settings for the template variables to fill
$config_check           = '';
$config_file_ready      = false;
$lbl_rebuild_config     = $mod_strings['LBL_REBUILD_CONFIG'];
$btn_rebuild_config     = $mod_strings['BTN_REBUILD_CONFIG'];
$disable_config_rebuild = 'disabled="disabled"';

// check the status of the config file
if( is_writable('config.php') ){
    $config_check = $mod_strings['MSG_CONFIG_FILE_READY_FOR_REBUILD'];
    $disable_config_rebuild = '';
    $config_file_ready = true;
}
else {
    $config_check = $mod_strings['MSG_MAKE_CONFIG_FILE_WRITABLE'];
}

// only do the rebuild if config file checks out and user has posted back
if( !empty($_POST['perform_rebuild']) && $config_file_ready ){

    // retrieve configuration from file so that contents of config_override.php
    // is not merged (bug #54403)
    $clean_config = loadCleanConfig();
    if ( rebuildConfigFile($clean_config, $sugar_version) ) {
    	$config_check = $mod_strings['MSG_CONFIG_FILE_REBUILD_SUCCESS'];
        $disable_config_rebuild = 'disabled="disabled"';
    }
    else {
        $config_check = $mod_strings['MSG_CONFIG_FILE_REBUILD_FAILED'];
    }
    SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
    $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
    $moduleInstallerClass::handleBaseConfig();
    $moduleInstallerClass::handlePortalConfig();
}

/////////////////////////////////////////////////////////////////////
// TEMPLATE ASSIGNING
$xtpl = new XTemplate('modules/Administration/RebuildConfig.html');
$xtpl->assign('LBL_CONFIG_CHECK', $mod_strings['LBL_CONFIG_CHECK']);
$xtpl->assign('CONFIG_CHECK', $config_check);
$xtpl->assign('LBL_PERFORM_REBUILD', $lbl_rebuild_config);
$xtpl->assign('DISABLE_CONFIG_REBUILD', $disable_config_rebuild);
$xtpl->assign('BTN_PERFORM_REBUILD', $btn_rebuild_config);
$xtpl->parse('main');
$xtpl->out('main');
?>
