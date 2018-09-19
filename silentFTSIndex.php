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

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\SearchEngine\Engine\Elastic;


//change directories to where this file is located.
chdir(dirname(__FILE__));
define('ENTRY_POINT_TYPE', 'api');
require_once('include/entryPoint.php');

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) != 'cli') {
    sugar_die("silentFTSIndex.php is CLI only.\n");
}

if (empty($current_language)) {
    $current_language = $sugar_config['default_language'];
}

$app_list_strings = return_app_list_strings_language($current_language);
$app_strings = return_application_language($current_language);

global $current_user;
$current_user = BeanFactory::newBean('Users');
$current_user->getSystemUser();

// Pop off the filename
array_shift($argv);

// Don't wipe the index if we're just doing individual modules
$clearData = empty($argv);

// Allows for php -f silentFTSIndex.php Bugs Cases
$modules = $argv;

try {
    $searchEngine = SearchEngine::getInstance()->getEngine();
    if (!$searchEngine instanceof Elastic) {
        echo "Administration not supported for non-Elasticsearch backend. \n";
        exit(1);
    }

    // if no modules are specified in the command arguments
    // example:
    // $php -f silentFTSIndex.php
    if ($clearData) {
        $searchEngine->runFullReindex(true);
        echo "Full Reindex is done! \n";
    } else {
        // if modules are specified in the command arguments
        // example:
        // $php -f silentFTSIndex.php Accounts Contacts Leads
        // Since the smart indexing is not implemented yet, index the selected modules
        // by clearing the data.
        $clearData = true;
        if (!$searchEngine->scheduleIndexing($modules, $clearData)) {
            echo "FTS index failed. Please check the sugarcrm.log for more details.\n";
            exit(1);
        } else {
            // Cron has to be run by the administrator afterwards
            // To check the completion, Elasticsearch queue consumers for the modules
            // in the database table 'job_queue' must show status as 'done'.
            echo "Scheduling indexing for the modules is almost done! \n";
            echo "Please run 'php -f cron.php' to complete indexing the documents.\n";
        }
    }

} catch (Exception $e) {
    echo "Exception: ".$e->getMessage()."\n";
    exit(1);
}
exit(0);
