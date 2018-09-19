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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

// Used for custom plugins
if (!empty($_REQUEST['plugin_action']) && !empty($_REQUEST['plugin_module'])) {

    $request = InputValidation::getService();
    $module = $request->getValidInputRequest('plugin_action');
    $action = $request->getValidInputRequest('plugin_action');

    $plugin = 'custom/workflow/plugins/'.$module.'/'.$action.'.php';

    if (SugarAutoLoader::existing($plugin)) {
        include_once FileLoader::validateFilePath($plugin);
    } else {
        echo "custom plugin file not found";
    }

} else {
    echo "A custom plugin step 2 was not specified";
}
