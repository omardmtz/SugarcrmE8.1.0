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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $currentModule;

$request = InputValidation::getService();
$view = $request->getValidInputRequest('view', array('Assert\Type' => (array('type' => 'string'))));

switch ($view) {
	case "support_portal":
		if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
		$GLOBALS['log']->info("Administration SupportPortal");

		$iframe_url = add_http("www.sugarcrm.com/network/redirect.php?tmpl=network");

        echo getClassicModuleTitle(
            "Administration",
            array(
                "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
                $mod_strings['LBL_SUPPORT_TITLE'],
            ),
            false
        );

        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('iframeURL', $iframe_url);
        $sugar_smarty->assign('langHeader', get_language_header());
        echo $sugar_smarty->fetch('modules/Administration/SupportPortal.tpl');

		break;

	default:
		$send_version = $request->getValidInputRequest('version', null, '');
		$send_edition = $request->getValidInputRequest('edition', null, '');
		$send_lang = $request->getValidInputRequest('lang', 'Assert\Language', '');
		$send_module = $request->getValidInputRequest('help_module', 'Assert\Mvc\ModuleName', '');
        $send_action = $request->getValidInputRequest('help_action', null, '');
		$send_key = $request->getValidInputRequest('key', null, '');
		$send_anchor = '';
		// awu: Fixes the ProjectTasks pluralization issue -- must fix in later versions.
		if ($send_module == 'ProjectTasks')
			$send_module = 'ProjectTask';
        if ($send_module == 'ProductCatalog')
            $send_module = 'ProductTemplates';
        if ($send_module == 'TargetLists')
            $send_module = 'ProspectLists';
        if ($send_module == 'Targets')
            $send_module = 'Prospects';
        if($send_module == 'OAuthKeys') {
        	$send_module = 'Administration';
        	$send_action = 'OAuthKeys';
        }
		// FG - Bug 39819 - Check for custom help files
		$helpPath = SugarAutoLoader::existingCustomOne('modules/'.$send_module.'/language/'.$send_lang.'.help.'.$send_action.'.html');

		//go to the support portal if the file is not found.
		// FG - Bug 39820 - Devs can write help files also in english, so skip check for language not equals "en_us" !
		if (!empty($helpPath)) {
		    $sugar_smarty = new Sugar_Smarty();
		    $sugar_smarty->assign('helpFileExists', TRUE);
			$sugar_smarty->assign('MOD', $mod_strings);
			$sugar_smarty->assign('modulename', $send_module);
			$sugar_smarty->assign('helpPath', $helpPath);
			$sugar_smarty->assign('currentURL', getCurrentURL());
			$sugar_smarty->assign('title', $mod_strings['LBL_SUGARCRM_HELP'] . " - " . $send_module);
			$sugar_smarty->assign('styleSheet', SugarThemeRegistry::current()->getCSS());
			$sugar_smarty->assign('table', "<table class='tabForm'><tr><td>");
			$sugar_smarty->assign('endtable', "</td></tr></table>");
			$sugar_smarty->assign('charset', $app_strings['LBL_CHARSET']);
            $sugar_smarty->assign('langHeader', get_language_header());
			echo $sugar_smarty->fetchCustom('modules/Administration/SupportPortal.tpl');
		} else {
			if(empty($send_module)){
				$send_module = 'toc';
			}

			$dev_status = 'GA';
			//If there is an alphabetic portion between the decimal prefix and integer suffix, then use the
			//value there as the dev_status value
			$dev_status = getVersionStatus($GLOBALS['sugar_version']);
			$send_version = getMajorMinorVersion($GLOBALS['sugar_version']);
            $editionMap = array('ENT' => 'Enterprise', 'PRO' => 'Professional');
			if(!empty($editionMap[$send_edition])){
				$send_edition = $editionMap[$send_edition];
			}

			//map certain modules
			$sendModuleMap = array(
                'administration' => array(
                    array('name' => 'Administration', 'action' => 'supportportal', 'anchor' => '1910574'),
                    array('name' => 'Administration', 'action' => 'updater', 'anchor' => '1910574'),
                    array('name' => 'Administration', 'action' => 'licensesettings', 'anchor' => '1910574'),
                    array('name' => 'Administration', 'action' => 'diagnostic', 'anchor' => '1111949'),
                    array('name' => 'Administration', 'action' => 'enablewirelessmodules', 'anchor' => '1111949'),
                    array('name' => 'Administration', 'action' => 'backups', 'anchor' => '1111949'),
                    array('name' => 'Administration', 'action' => 'upgrade', 'anchor' => '1111949'),
                    array('name' => 'Administration', 'action' => 'locale', 'anchor' => '1111949'),
                    array('name' => 'Administration', 'action' => 'passwordmanager', 'anchor' => '1446494'),
                    array('name' => 'Administration', 'action' => 'upgradewizard', 'anchor' => '1168410'),
                    array('name' => 'Administration', 'action' => 'configuretabs', 'anchor' => '1168410'),
                    array('name' => 'Administration', 'action' => 'configuresubpanels', 'anchor' => '1168410'),
                    array('name' => 'Administration', 'action' => 'wizard', 'anchor' => '1168410'),
                ),
                'calls' => array(array('name' => 'Activities')),
                'tasks' => array(array('name' => 'Activities')),
                'meetings' => array(array('name' => 'Activities')),
                'notes' => array(array('name' => 'Activities')),
                'calendar' => array(array('name' => 'Activities')),
                'configurator' => array(array('name' => 'Administration', 'anchor' => '1878359')),
                'upgradewizard' => array(array('name' => 'Administration', 'anchor' => '1878359')),
                'schedulers' => array(array('name' => 'Administration', 'anchor' => '1878359')),
                'connectors' => array(array('name' => 'Administration', 'anchor' => '1878359')),
                'trackers' => array(array('name' => 'Administration', 'anchor' => '1878359')),
                'currencies' => array(array('name' => 'Administration', 'anchor' => '1878359')),
                'aclroles' => array(array('name' => 'Administration', 'anchor' => '1916499')),
                'roles' => array(array('name' => 'Administration', 'anchor' => '1916499')),
                'teams' => array(array('name' => 'Administration', 'anchor' => '1916499')),
                'users' => array(array('name' => 'Administration', 'anchor' => '1916499'), array('name' => 'Administration', 'action' => 'detailview', 'anchor' => '1916518')),
                'modulebuilder' => array(array('name' => 'Administration', 'anchor' => '1168410')),
                'studio' => array(array('name' => 'Administration', 'anchor' => '1168410')),
                'workflow' => array(array('name' => 'Administration', 'anchor' => '1168410')),
                'producttemplates' => array(array('name' => 'Administration', 'anchor' => '1957376')),
                'productcategories' => array(array('name' => 'Administration', 'anchor' => '1957376')),
                'producttypes' => array(array('name' => 'Administration', 'anchor' => '1957376')),
                'manufacturers' => array(array('name' => 'Administration', 'anchor' => '1957376')),
                'shippers' => array(array('name' => 'Administration', 'anchor' => '1957376')),
                'taxrates' => array(array('name' => 'Administration', 'anchor' => '1957376')),
                'releases' => array(array('name' => 'Administration', 'anchor' => '1868932')),
                'timeperiods' => array(array('name' => 'Administration', 'anchor' => '1957639')),
                'contracttypes' => array(array('name' => 'Administration', 'anchor' => '1957677')),
                'contracttype' => array(array('name' => 'Administration', 'anchor' => '1957677')),
                'emailman' => array(array('name' => 'Administration', 'anchor' => '1445484')),
                'inboundemail' => array(array('name' => 'Administration', 'anchor' => '1445484')),
                'emailtemplates' => array(array('name' => 'Emails')),
                'prospects' => array(array('name' => 'Campaigns')),
                'prospectlists' => array(array('name' => 'Campaigns')),
                'reportmaker' => array(array('name' => 'Reports')),
                'customqueries' => array(array('name' => 'Reports')),
                'quotas' => array(array('name' => 'Forecasts')),
                'projecttask' => array(array('name' => 'Projects')),
                'project' => array(array('name' => 'Projects'), array('name' => 'Dashboard', 'action' => 'dashboard'), ),
                'projecttemplate' => array(array('name' => 'Projects')),
                'datasets' => array(array('name' => 'Reports')),
                'dataformat' => array(array('name' => 'Reports')),
                'employees' => array(array('name' => 'Administration', 'anchor' => '1957677')),
            );

			if(!empty($sendModuleMap[strtolower($send_module)])) {
				$mappings = $sendModuleMap[strtolower($send_module)];

				foreach($mappings as $map) {
					if(!empty($map['action'])) {
						if($map['action'] == strtolower($send_action)) {
							$send_module = $map['name'];
							if(!empty($map['anchor'])) {
								$send_anchor = $map['anchor'];
							}
						}
					} else {
						$send_module = $map['name'];
						if(!empty($map['anchor'])) {
								$send_anchor = $map['anchor'];
						}
					}
				}
			}


            $iframe_url = get_help_url($send_edition, $send_version, $send_lang, $send_module, $send_action, $dev_status, $send_key, $send_anchor);

			header("Location: {$iframe_url}");
		}
		break;
}
