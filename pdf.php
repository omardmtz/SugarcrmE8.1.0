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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

/**
 * @deprecated This file will be removed in a next release
 */

global $locale;

if (!empty($_REQUEST['module']) && !empty($_REQUEST['action']) && !empty($_REQUEST['record'])) {

    $request = InputValidation::getService();
    $module = $request->getValidInputGet('module', 'Assert\Mvc\ModuleName');
    $record = $request->getValidInputGet('record', 'Assert\Guid');
    $action = $request->getValidInputGet('action');

} else {
    sugar_die("pdf.php - module, action, and record id all are required");
}

$GLOBALS['focus'] = BeanFactory::getBean($module, $record);

if (empty($GLOBALS['focus'])) {
    ACLController::displayNoAccess();
    sugar_die("pdf.php - record not found");
}

$includeFile = "modules/$module/$action.php";

if (!file_exists($includeFile)) {
    sugar_die("pdf.php - include file does not exist");
}

SugarAutoLoader::includeFile($includeFile);

