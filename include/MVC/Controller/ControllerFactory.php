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

/**
 * MVC Controller Factory
 * @api
 */
class ControllerFactory
{
	/**
	 * Obtain an instance of the correct controller.
	 *
     * @param string $module Module name
     * @return SugarController
	 */
    public static function getController($module)
	{
		if(SugarAutoLoader::requireWithCustom("modules/{$module}/controller.php")) {
		    $class = SugarAutoLoader::customClass(ucfirst($module).'Controller');
		} else {
		    SugarAutoLoader::requireWithCustom('include/MVC/Controller/SugarController.php');
		    $class = SugarAutoLoader::customClass('SugarController');
		}
		if(class_exists($class, false)) {
			$controller = new $class();
		}

		if(empty($controller)) {
		    $controller = new SugarController();
		}
		//setup the controller
		$controller->setup($module);
		return $controller;
	}
}
