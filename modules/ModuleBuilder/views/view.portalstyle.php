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


class ViewPortalStyle extends SugarView 
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

	function display($params = array())
	{
        $smarty = new Sugar_Smarty();
        //$smarty->assign('welcome', $GLOBALS['mod_strings']['LBL_SP_UPLOADSTYLE']);
        $smarty->assign('mod', $GLOBALS['mod_strings']);
        $label = isset($params['label']) ? $params['label'] : $this->request->getValidInputRequest('label');
        if ($label !== null) {
            $GLOBALS['log']->debug('ViewPortalStyle->display(): label = ' . $label);
            $smarty->assign('label', $label);
        }
        $ajax = new AjaxCompose();
        $ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(translate('LBL_UP_STYLE_SHEET', 'ModuleBuilder'), 'ModuleBuilder.getContent("module=ModuleBuilder&action=portalstyle")');
        $ajax->addSection('center', translate('LBL_UP_STYLE_SHEET', 'ModuleBuilder'), $smarty->fetch('modules/ModuleBuilder/tpls/portalstyle.tpl'));
		$GLOBALS['log']->debug('ViewPortalStyle->display(): '.$ajax->getJavascript());
		echo $ajax->getJavascript();
	}
}
