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


class ViewPortalSync extends SugarView 
{
    public function __construct()
	{
	    $GLOBALS['log']->debug('ViewPortalSync constructor');
        parent::__construct();
	}

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

	function display() 
	{
        $smarty = new Sugar_Smarty();
        $smarty->assign('welcome', $GLOBALS['mod_strings']['LBL_SYNCP_WELCOME']);
        $smarty->assign('mod', $GLOBALS['mod_strings']);
        $label = $this->request->getValidInputRequest('label');
        if ($label !== null) {
            $smarty->assign('label', $label);
        }
        $options = (!empty($GLOBALS['system_config']->settings['system_portal_url'])) ? $GLOBALS['system_config']->settings['system_portal_url'] : 'https://';
        $smarty->assign('options',$options);
        $ajax = new AjaxCompose();
        $ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(translate('LBL_SYNCPORTAL', 'ModuleBuilder'), 'ModuleBuilder.getContent("module=ModuleBuilder&action=portalsync")');
        $ajax->addSection('center', translate('LBL_SYNCPORTAL', 'ModuleBuilder'), $smarty->fetch('modules/ModuleBuilder/tpls/portalsync.tpl'));
		$GLOBALS['log']->debug($smarty->fetch('modules/ModuleBuilder/tpls/portalsync.tpl'));
        echo $ajax->getJavascript();
	}
}
