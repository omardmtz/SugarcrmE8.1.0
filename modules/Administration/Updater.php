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

********************************************************************************/




global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_config;

$xtpl=new XTemplate ('modules/Administration/Updater.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['useraction']) && $_REQUEST['useraction']=='Save' ) {
	if(!empty($_REQUEST['type']) && $_REQUEST['type'] == 'automatic') {
		set_CheckUpdates_config_setting('automatic');
	}else{
		set_CheckUpdates_config_setting('manual');
	}

	$beat=false;
	if(!empty($_REQUEST['beat'])) {
		$beat=true;
	}
	if ($beat != get_sugarbeat()) {
		set_sugarbeat($beat);
	}
}

echo getClassicModuleTitle(
        "Administration", 
        array(
            "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
           $mod_strings['LBL_SUGAR_UPDATE_TITLE'],
           ), 
        false
        );

if (get_sugarbeat()) $xtpl->assign("SEND_STAT_CHECKED", "checked");

if (get_CheckUpdates_config_setting()=='automatic') {
	$xtpl->assign("AUTOMATIC_CHECKED", "checked");
}


if (isset($_REQUEST['useraction']) && $_REQUEST['useraction']=='CheckNow') {
	check_now(get_sugarbeat());
	loadLicense();

}

	/*

$xtpl->parse('main.stats');
	*/


$has_updates= false;
if(!empty($license->settings['license_latest_versions'])){

	$encodedVersions = $license->settings['license_latest_versions'];

	$versions = unserialize(base64_decode( $encodedVersions));
	include('sugar_version.php');
	if(!empty($versions)){
		foreach($versions as $version){
			if(compareVersions($version['version'], $sugar_version))
			{
				$has_updates = true;
				$xtpl->assign("VERSION", $version);
				$xtpl->parse('main.updates.version');
			}
		}
	}
	if(!$has_updates){
		$xtpl->parse('main.noupdates');
	}else{
		$xtpl->parse('main.updates');
	}
}

//return module and index.
$xtpl->assign("RETURN_MODULE", "Administration");
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->parse("main");
$xtpl->out("main");
?>
