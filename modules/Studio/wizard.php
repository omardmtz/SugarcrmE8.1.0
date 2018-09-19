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

 // $Id: wizard.php 18703 2006-12-15 09:42:43Z majed $

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

require_once 'modules/Studio/config.php';

$wizard = InputValidation::getService()->getValidInputRequest('wizard', null, 'StudioWizard');

if (file_exists('modules/Studio/wizards/'. $wizard . '.php')) {
    require_once FileLoader::validateFilePath('modules/Studio/wizards/'. $wizard . '.php');
    $thewiz = new $wizard();
} else {
    unset($_SESSION['studio']['lastWizard']);
    $thewiz = new StudioWizard();
}

if (!empty($_REQUEST['back'])) {
    $thewiz->back();
}
if (!empty($_REQUEST['option'])) {
    $thewiz->process($_REQUEST['option']);
} else {
    $thewiz->display();
}
