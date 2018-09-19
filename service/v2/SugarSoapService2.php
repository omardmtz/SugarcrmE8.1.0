<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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
 * This is a service class for version 2
 */
require_once('service/core/NusoapSoap.php');
class SugarSoapService2 extends NusoapSoap{
		
	/**
	 * This method registers all the functions which you want to be available for SOAP.
	 *
	 * @param array $excludeFunctions - All the functions you don't want to register
	 */
	public function register($excludeFunctions = array()){
		$GLOBALS['log']->info('Begin: SugarSoapService2->register');
		$this->excludeFunctions = $excludeFunctions;
		$registryObject = new $this->registryClass($this);
		$registryObject->register();
		$this->excludeFunctions = array();
		$GLOBALS['log']->info('End: SugarSoapService2->register');
	} // fn
			
} // clazz
?>
