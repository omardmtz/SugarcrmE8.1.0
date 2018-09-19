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

/* @var array $params here */
require_once __DIR__ . '/parameters.php';

$config['db']['db.options'] = [
    'driver' => $params['db_driver'],
    'host' => $params['db_host'],
    'port' => $params['db_port'],
    'dbname' => $params['db_name'],
    'user' => $params['db_user'],
    'password' => $params['db_password'],
    'charset' => $params['db_charset'],
];

$config['logdir'] = $params['logdir'];

$config['passwordHash'] = isset($params['passwordHash']) ? $params['passwordHash'] : [];

$config['monolog'] = isset($params['monolog']) ? $params['monolog'] : [];

$config['sts'] = isset($params['sts']) ? $params['sts'] : [];

$config['idm'] = isset($params['idm']) ? $params['idm'] : [];

$config['session.storage.options'] = isset($params['session.storage.options']) ? $params['session.storage.options'] : [];
