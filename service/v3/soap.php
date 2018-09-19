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
 * This is a soap entry point for soap version 3
 */
chdir('../..');
require 'include/entryPoint.php';
require_once 'service/v3/SugarWebServiceImplv3.php';
$webservice_class = 'SugarSoapService2';
$webservice_path = 'service/v2/SugarSoapService2.php';
$registry_class = 'registry_v3';
$registry_path = 'service/v3/registry.php';
$webservice_impl_class = 'SugarWebServiceImplv3';
$location = '/service/v3/soap.php';
require_once 'service/core/webservice.php';
