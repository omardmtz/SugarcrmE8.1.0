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
 * This is an abstract class for all the web services.
 * All type of web services should provide proper implementation of all the abstract methods
 * @api
 */
abstract class SugarWebService{
	protected $server = null;
	protected $excludeFunctions = array();
	abstract function register($excludeFunctions = array());
	abstract function registerImplClass($class);
	abstract function getRegisteredImplClass();
	abstract function registerClass($class);
	abstract function getRegisteredClass();
	abstract function serve();
	abstract function error($errorObject);
}
