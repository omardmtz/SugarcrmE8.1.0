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

use Sugarcrm\IdentityProvider\App\Application;

ini_set('display_errors', 0);
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application(['env' => Application::ENV_PROD]);
$app->run();
