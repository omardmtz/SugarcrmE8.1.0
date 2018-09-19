<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
 * This file intialize the service class and does all the setters based on the values provided in soap/rest entry point
 * and calls serve method which takes the request and send response back to the client
 */
ob_start();
chdir(dirname(__FILE__).'/../../');
define('ENTRY_POINT_TYPE', 'api');
require 'soap/SoapErrorDefinitions.php';
require_once 'service/core/SoapHelperWebService.php';
require_once($webservice_path);
require_once($registry_path);
if(isset($webservice_impl_class_path))
    require_once($webservice_impl_class_path);
$url = $GLOBALS['sugar_config']['site_url'].$location;
$service = new $webservice_class($url);
$service->registerClass($registry_class);
$service->register();
$service->registerImplClass($webservice_impl_class);

SugarMetric_Manager::getInstance()->setTransactionName('soap_' . (isset($_REQUEST['method'])? $_REQUEST['method'] : ""));

// set the service object in the global scope so that any error, if happens, can be set on this object
global $service_object;
$service_object = $service;

$service->serve();
