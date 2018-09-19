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


class ViewPortalConfig extends SugarView
{
	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   translate('LBL_MODULE_NAME','Administration'),
    	   ModuleBuilderController::getModuleTitle(),
    	   );
    }

	// DO NOT REMOVE - overrides parent ViewEdit preDisplay() which attempts to load a bean for a non-existent module
	function preDisplay() 
	{
	}

    /**
     * This function loads portal config vars from db and sets them for the view
     * @see SugarView::display() for more info
   	 */
	function display() 
	{
        $portalFields = array('appStatus'=>'offline', 'logoURL'=>
        '', 'maxQueryResult'=>'20', 'maxSearchQueryResult'=>'5', 'defaultUser'=>'');
        $userList = get_user_array();
        $userList[''] = '';
        $controller = new TabController();
        $disabledModulesFlag = false;
        $disabledModules = array_diff($controller->getAllPortalTabs(), $controller->getPortalTabs());
        if (!empty($disabledModules)) {
            $disabledModulesFlag = true;
            array_walk($disabledModules, function (&$item) {
                $item = translate($item);
            });
        }

        $admin = Administration::getSettings();

        $portalConfig = $admin->getConfigForModule('portal','support', true);
        $portalConfig['appStatus'] = !empty($portalConfig['on']) ? 'online' : 'offline';
        $smarty = new Sugar_Smarty();
        $smarty->assign('disabledDisplayModulesList', $disabledModules);
        $smarty->assign('disabledDisplayModules', $disabledModulesFlag);
        foreach ($portalFields as $fieldName=>$fieldDefault) {
            if (isset($portalConfig[$fieldName])) {
                $smarty->assign($fieldName, html_entity_decode($portalConfig[$fieldName]));
            } else {
                $smarty->assign($fieldName,$fieldDefault);
            }
        }
        $smarty->assign('userList', $userList);
        $smarty->assign('welcome', $GLOBALS['mod_strings']['LBL_SYNCP_WELCOME']);
        $smarty->assign('mod', $GLOBALS['mod_strings']);
        $smarty->assign('siteURL', $GLOBALS['sugar_config']['site_url']);
        $label = $this->request->getValidInputRequest('label');
        if ($label !== null) {
            $smarty->assign('label', $label);
        }
        $options = (!empty($GLOBALS['system_config']->settings['system_portal_url'])) ? $GLOBALS['system_config']->settings['system_portal_url'] : 'https://';
        $smarty->assign('options',$options);
        $ajax = new AjaxCompose();
        $ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(ucwords(translate('LBL_PORTAL_CONFIGURE')), '');
        $ajax->addSection('center', translate('LBL_SUGARPORTAL', 'ModuleBuilder'), $smarty->fetch('modules/ModuleBuilder/tpls/portalconfig.tpl'));
		$GLOBALS['log']->debug($smarty->fetch('modules/ModuleBuilder/tpls/portalconfig.tpl'));
        echo $ajax->getJavascript();
	}
}
