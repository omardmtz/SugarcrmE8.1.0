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

require_once __DIR__ . '/pack_cli.php';

$files = array(
    "modules/UpgradeWizard/ShadowUpgrader.php" => 'ShadowUpgrader.php',
    'modules/HealthCheck/Scanner/Scanner.php' => 'HealthCheck/Scanner/Scanner.php',
    'modules/HealthCheck/Scanner/ScannerCli.php' => 'HealthCheck/Scanner/ScannerCli.php',
    'modules/HealthCheck/Scanner/ScannerMeta.php' => 'HealthCheck/Scanner/ScannerMeta.php',
    'modules/HealthCheck/Scanner/removed-php4-constructors.php' => 'HealthCheck/Scanner/removed-php4-constructors.php',
    'modules/HealthCheck/Scanner/package-checklist.php' => 'HealthCheck/Scanner/package-checklist.php',
    'modules/HealthCheck/HealthCheckClient.php' => 'HealthCheck/HealthCheckClient.php',
    'modules/HealthCheck/HealthCheckHelper.php' => 'HealthCheck/HealthCheckHelper.php',
    'modules/HealthCheck/language/en_us.lang.php' => 'HealthCheck/language/en_us.lang.php'
);

if (empty($argv[0]) || basename($argv[0]) != basename(__FILE__)) {
    return;
}

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) != 'cli') {
    die("This is command-line only script");
}

if (empty($argv[1])) {
    die("Use $argv[0] name (no zip or phar extension) [sugarVersion [buildNumber]]\n");
}

$pathinfo = pathinfo($argv[1]);

if (isset($pathinfo['extension']) && in_array($pathinfo['extension'], array('zip', 'phar'))) {
    $name = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'];
} else {
    $name = $argv[1];
}

$params = array();
if (isset($argv[2])) {
    $params['version'] = $argv[2];
}
if (isset($argv[3])) {
    $params['build'] = $argv[3];
}


$phar = new Phar($name . '.phar');

packUpgradeWizardCli($phar, $params, $files);

$stub = <<<'STUB'
<?php
Phar::mapPhar();
set_include_path('phar://' . __FILE__ . PATH_SEPARATOR . get_include_path());
require_once "ShadowUpgrader.php"; $upgrader = new ShadowUpgrader(); $upgrader->start(); __HALT_COMPILER();
STUB;
$phar->setStub($stub);

$zip = new ZipArchive();
$zip->open($name . '.zip', ZipArchive::CREATE);

packUpgradeWizardCli($zip, $params, $files);

$zip->close();

exit(0);
