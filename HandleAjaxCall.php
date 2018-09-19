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
 * Used to call a generic method in a dashlet
 */
// Only define entry point type if it isnt already defined
if (!defined('ENTRY_POINT_TYPE')) {
    define('ENTRY_POINT_TYPE', 'gui');
}
 require_once('include/entryPoint.php');
if(!is_admin($GLOBALS['current_user'])){
	sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}
    $requestedMethod = $_REQUEST['method'];
    $pmc = new PackageController();

    if(method_exists($pmc, $requestedMethod)) {
        echo $pmc->$requestedMethod();
    }
    else {
        echo 'no method';
    }
   // sugar_cleanup();
?>
