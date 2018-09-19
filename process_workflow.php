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
// $Id: process_workflow.php 56510 2010-05-17 18:54:49Z jenny $


global $app_list_strings, $app_strings, $current_language;

$mod_strings = return_module_language('en_us', 'WorkFlow');


//run as admin
global $current_user;
$current_user->getSystemUser();

$process_object = new WorkFlowSchedule();
$process_object->process_scheduled();
unset($process_object);


//sugar_cleanup(); // moved to cron.php
?>
