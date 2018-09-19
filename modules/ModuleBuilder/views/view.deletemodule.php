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
require_once('modules/ModuleBuilder/MB/ModuleBuilder.php');
 
class Viewdeletemodule extends SugarView
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

	function display()
	{

		$module = $this->request->getValidInputRequest('module', 'Assert\ComponentName');
		$package = $this->request->getValidInputRequest('package', 'Assert\ComponentName');
		$ajax = new AjaxCompose();
		$ajax->addSection('center', 'Module Deleted', $module . ' was deleted from ' . $package);
		echo $ajax->getJavascript(); 
 	}
}