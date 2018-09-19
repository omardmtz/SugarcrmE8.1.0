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
 * This is a rest entry point for rest version 4
 */
chdir('../..');
require 'include/entryPoint.php';
require_once('SugarWebServiceImplv4_1.php');
$webservice_class = 'SugarRestService';
$webservice_path = 'service/core/SugarRestService.php';
$webservice_impl_class = 'SugarWebServiceImplv4_1';
$registry_class = 'registry';
$location = '/service/v4_1/rest.php';
$registry_path = 'service/v4_1/registry.php';
require_once('service/core/webservice.php');
