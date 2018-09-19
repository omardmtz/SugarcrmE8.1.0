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
define('ENTRY_POINT_TYPE', 'api');
require_once('include/entryPoint.php');
require_once('include/utils/file_utils.php');
ob_start();

require 'soap/SoapErrorDefinitions.php';
require_once('vendor/nusoap//nusoap.php');
//ignore notices
error_reporting(E_ALL ^ E_NOTICE);

checkSystemLicenseStatus();
checkSystemState();

$administrator = Administration::getSettings();

$NAMESPACE = 'http://www.sugarcrm.com/sugarcrm';
$server = new soap_server;
$server->configureWSDL('sugarsoap', $NAMESPACE, $sugar_config['site_url'].'/soap.php');

//New API is in these files
if(!empty($administrator->settings['portal_on'])) {
	require_once('soap/SoapPortalUsers.php');
}

require_once('soap/SoapSugarUsers.php');
//require_once('soap/SoapSugarUsers_version2.php');
require_once('soap/SoapData.php');
require_once('soap/SoapDeprecated.php');


require_once('soap/SoapUpgradeUtils.php');

/* Begin the HTTP listener service and exit. */
ob_clean();

$resourceManager = ResourceManager::getInstance();
$resourceManager->setup('Soap');
$observers = $resourceManager->getObservers();
//Call set_soap_server for SoapResourceObserver instance(s)
foreach($observers as $observer) {
   if(method_exists($observer, 'set_soap_server')) {
   	  $observer->set_soap_server($server);
   }
}

global $soap_server_object;
$soap_server_object = $server;
$body = file_get_contents('php://input');
$server->service($body);

$action = substr($server->SOAPAction, strpos($server->SOAPAction, 'soap.php/') + 9);
SugarMetric_Manager::getInstance()->setTransactionName('soap_' . $action);

ob_end_flush();
flush();
sugar_cleanup(true);
