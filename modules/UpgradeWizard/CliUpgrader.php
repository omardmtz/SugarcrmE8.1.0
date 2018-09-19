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
require_once dirname(__FILE__)  . '/UpgradeDriver.php';

/**
 * Command-line upgrader
 */
class CliUpgrader extends UpgradeDriver
{
    protected $options = array(
        // required, short, long
        "zip" => array(true, 'z', 'zip'),
        "log" => array(true, 'l', 'log'),
        "source_dir" => array(true, 's', 'source'),
        "admin" => array(true, 'u', 'user'),
        "backup" => array(false, 'b', 'backup'),
        "script_mask" => array(false, 'm', 'mask'),
        "stage" => array(false, 'S', 'stage'),
        "autoconfirm" => array(false, 'A', 'autoconfirm'),
        // Appears when stage was not specified and upgrader is running step by step.
        'all' => array(false, 'a', 'all'),
    );

    /**
     * Script mask types
     * @var array[int]
     */
    protected $maskTypes = array(
        'core' => UpgradeScript::UPGRADE_CORE,
        'db' => UpgradeScript::UPGRADE_DB,
        'custom' => UpgradeScript::UPGRADE_CUSTOM,
        'all' => UpgradeScript::UPGRADE_ALL,
        'none' => 0,
    );

    /**
     * Returns an exit code for given stage
     *
     * @var array
     */
    protected $stageExitCodes = array(
        'healthcheck' => 6,
        'unpack' => 1,
        'pre' => 2,
        'commit' => 3,
        'post' => 4,
        'cleanup' => 5
    );

    /**
     * {@inheritDoc}
     */
    public static $version = '1.0.0-dev';

    /**
     * {@inheritDoc}
     */
    public static $build = '999';

    /**
     * {@inheritDoc}
     */
    const VERSION_FILE = 'version.json';

    /*
     * CLI arguments: Zipfile Logfile Sugardir Adminuser [Stage]
     */
    public function runStage($stage)
    {
        $scriptPathInfo = pathinfo($this->context['script']);

        $argsToBeIncludedInCommand = $this->buildArgString( array('stage' => $stage, 'all' => true) );

        //Check if we're executing from a phar, if so we need to adjust the command executed
        if(substr($scriptPathInfo['dirname'] , 0, strlen('phar://')) === 'phar://') {

            $pharPath = substr($scriptPathInfo['dirname'] , strlen('phar://'));

            $cmd = "{$this->context['php']} {$pharPath}" . $argsToBeIncludedInCommand;
        }
        else {
            $cmd = "{$this->context['php']} -f {$this->context['script']} -- " . $argsToBeIncludedInCommand;
        }

        $this->log("Running $cmd");
        passthru($cmd, $retcode);
        return ($retcode == self::STOP_SIGNAL) ? self::STOP_SIGNAL : ($retcode == 0);
    }

    protected static function bannerError($msg)
    {
        echo "*******************************************************************************\n";
        echo "*** ERROR: $msg\n";
        echo "FAILURE\n";
    }

    protected function argError($msg)
    {
        $this->bannerError($msg);
        $this->usage();
        exit(1);
    }

    protected static function usage()
    {
        global $argv;
        list($version, $build) = self::getVersion();
        $usage = <<<eoq2
CLI Upgrader v.$version (build $build)
Usage:
php {$argv[0]} -z upgradeZipFile -l logFile -s pathToSugarInstance -u admin-user

Example:
    php [path-to-PHP/]{$argv[0]} -z [path-to-upgrade-package/]SugarEnt-Upgrade-6.5.x-to-7.5.0.zip -l [path-to-log-file/]silentupgrade.log -s path-to-sugar-instance/ -u admin

Arguments:
    -z/--zip upgradeZipFile              : Upgrade package file.
    -l/--log logFile                     : Upgrade log file (by default relative to instance dir)
    -s/--source pathToSugarInstance      : Sugar instance being upgraded.
    -u/--user admin-user                 : admin user performing the upgrade
Optional arguments:
    -m/--mask scriptMask                 : Script mask - which types of scripts to run.
                                           Supported types: core, db, custom, all, none. Default is all.
    -b/--backup 0/1                      : Create backup of deleted files? 0 means no backup, default is 1.
    -S/--stage stage                     : Run specific stage of the upgrader. 'continue' means start where it stopped last time.
    -A/--autoconfirm 0/1                 : Automatic confirm health check results (use with caution !). Default is 0.

eoq2;
        echo $usage;
    }

    protected function verifyArguments()
    {
        if (empty($this->context['source_dir']) || !is_dir($this->context['source_dir'])) {
            self::argError("Source directory parameter must be a valid directory.");
        }

        if (!is_file("{$this->context['source_dir']}/include/entryPoint.php") || !is_file(
                "{$this->context['source_dir']}/config.php"
            )
        ) {
            self::argError("{$this->context['source_dir']} is not a SugarCRM directory.");
        }

        if (!is_readable("{$this->context['source_dir']}/include/entryPoint.php") || !is_readable(
                "{$this->context['source_dir']}/config.php"
            )
        ) {
            self::argError("{$this->context['source_dir']} is not a accessible.");
        }

        if (!is_file($this->context['zip']) && !is_dir($this->context['zip'])) { // valid zip?
            self::argError("Zip file argument must be a full path to the patch file or directory.");
        }

        if (!is_readable($this->context['zip'])) { // valid zip?
            self::argError("Upgrade archive is not readable: {$this->context['zip']}");
        }
        return true;
    }

    /**
     * Parse script mask
     * @param string $mask
     * @return int
     */
    public function parseScriptMask($mask)
    {
        if (is_numeric($mask)) {
            return intval($mask);
        }
        if (empty($mask)) {
            $this->argError("Empty script mask");
            return $this->maskTypes['all'];
        }
        $parts = explode(',', $mask);
        $mask = 0;
        if (empty($parts)) {
            $this->argError("Empty script mask");
            return $this->maskTypes['all'];
        }
        foreach ($parts as $part) {
            if (!isset($this->maskTypes[$part])) {
                $this->argError("Unknown script mask: $part");
                continue;
            }
            $mask |= $this->maskTypes[$part];
        }
        return $mask;
    }

    /**
     * Fix values in the context
     * @param array $context
     * @return array
     */
    public function fixupContext($context)
    {
        $context['zip_as_dir'] = false;

        if (!empty($context['zip'])) {
            $context['zip'] = realpath($context['zip']);

            if (is_dir($context['zip'])) {
                $context['zip_as_dir'] = true;
                $this->clean_on_fail = false;
            }
        }

        if (!empty($context['source_dir'])) {
            $context['source_dir'] = realpath($context['source_dir']);
        }
        if (isset($context['script_mask'])) {
            $context['script_mask'] = $this->parseScriptMask($context['script_mask']);
        }
        if (!empty($context['log'])) {
            touch($context['log']);
            if (!file_exists($context['log'])) {
                $this->argError("Can not create log file: {$this->context['log']}");
                // does not return
            }
            $context['log'] = realpath($context['log']);
            if (empty($context['log'])) {
                $this->argError("Error resolving logfile name");
            }
        }
        return $context;
    }

    public function init()
    {
        parent::init();
        if (empty($this->context['autoconfirm'])) {
            $this->context['autoconfirm'] = false;
        }
        $this->context['sendlog'] = !empty($this->context['sendlog']);
        if ($this->context['zip_as_dir'] && !isset($this->context['extract_dir'])) {
            $this->context['extract_dir'] = $this->context['zip'];
            $this->context['health_check_path'] =
                realpath($this->context['extract_dir']) . self::DEFAULT_HEALTHCHECK_PATH;
        }
    }

    /**
     * (non-PHPdoc)
     * @see UpgradeDriver::preflightWriteUnzip()
     */
    protected function preflightWriteUnzip()
    {
        if ($this->context['zip_as_dir']) {
            // if we're using extracted zip, we don't need it to be writable
            return true;
        }
        return parent::preflightWriteUnzip();
    }

    /**
     * Allows to give pre-extracted directory as zip
     * @see UpgradeDriver::extractZip()
     */
    protected function extractZip($zip)
    {
        if ($this->context['zip_as_dir'] && is_dir($zip)) {
            // pre-extracted
            if (!file_exists("$zip/manifest.php")) {
                return $this->error("$zip does not contain manifest.php");
            }
            $this->context['extract_dir'] = $zip;
            $this->log("Using $zip as extracted ZIP directory");
            return true;
        }

        return parent::extractZip($zip);
    }

    /**
     * Map CLI arguments into context entries
     * @param array $argv
     * @return array
     */
    public function mapNamedArgs($argv)
    {
        $opt = '';
        $context = $longopt = array();
        foreach ($this->options as $ctx => $data) {
            $opt .= $data[1] . ':';
            $longopt[] = $data[2] . ':';
        }
        /* FIXME: getopt always uses global argv */
        $opts = @getopt($opt, $longopt);

        if (empty($opts)) {
            $this->argError("Invalid upgrader options");
            return array(); // never happens
        }

        foreach ($this->options as $ctx => $data) {
            $val = null;
            if (isset($opts[$data[1]])) {
                $val = $opts[$data[1]];
            } elseif (isset($opts[$data[2]])) {
                $val = $opts[$data[2]];
            }
            if (is_null($val)) {
                if ($data[0]) {
                    $this->argError("Required option '{$data[2]}' missing");
                }
                continue;
            } elseif (is_array($val)) {
                $this->argError("Multiple valued for '{$data[2]}' are not allowed");
            }

            $context[$ctx] = $val;
        }
        return $context;
    }

    /**
     * Map CLI arguments into context entries
     * @param array $argv
     * @return array
     */
    public function mapArgs($argv)
    {
        if (!empty($argv[1]) && $argv[1][0] == '-') {
            /* named options */
            $context = $this->mapNamedArgs($argv);
        } else {
            $i = 1;
            $context = array();
            foreach ($this->options as $ctx => $data) {
                if (isset($argv[$i])) {
                    if (!$data[0] && $argv[$i][0] == '-') {
                        // if we're positional then no options
                        $this->argError("Positional and named arguments can not be mixed");
                        continue; // never happens
                    }
                    $context[$ctx] = $argv[$i];
                    $i++;
                } else {
                    if ($data[0]) {
                        $this->argError("Insufficient arguments");
                        continue; // never happens
                    } else {
                        break;
                    }
                }
            }
        }

        $context = $this->fixupContext($context);
        return $context;
    }

    /**
     * Parse CLI arguments into context
     * @param array $argv
     * @return array
     */
    public function parseArgs($argv)
    {
        if (defined('PHP_BINDIR')) {
            $php_path = PHP_BINDIR . "/";
        } else {
            $php_path = '';
        }
        if (!file_exists($php_path . 'php')) {
            $php_path = '';
        }
        $context = $this->mapArgs($argv);
        if (defined("PHP_BINARY")) {
            $context['php'] = PHP_BINARY;
        } elseif (!empty($_ENV['_'])) {
            $context['php'] = $_ENV['_'];
        } elseif (!empty($_SERVER['_'])) {
            $context['php'] = $_SERVER['_'];
        } else {
            $context['php'] = $php_path . "php";
        }
        if (empty($context['script'])) {
            if (class_exists('Phar')) {
                $pharPath = Phar::running(false);
            } else {
                $pharPath = null;
            }

            $context['script'] = $pharPath ? $pharPath : __FILE__;
        }
        $context['argv'] = $argv;
        $this->context = $context;
        $this->log("Setting context to: " . var_export($context, true));
        return $context;
    }

    /**
     * Get stage code depending on its order
     * @param string $stage
     * @return int
     */
    protected function getStageCode($stage)
    {
        if (isset($this->stageExitCodes[$stage])) {
            return $this->stageExitCodes[$stage];
        }
        return 99;
    }

    /**
     * Execution starts here
     */
    public function start()
    {
        global $argv;
        $class = get_class($this);
        $upgrader = new $class();
        $upgrader->parseArgs($argv);
        $upgrader->verifyArguments($argv);
        $upgrader->init();
        list($version, $build) = self::getVersion();
        $upgrader->log("CliUpgrader v.$version (build $build) starting");
        if (isset($upgrader->context['stage'])) {
            $stage = $upgrader->context['stage'];
        } else {
            $stage = null;
        }
        if ($stage && $stage != 'continue') {
            // Run one step
            if ($upgrader->run($stage)) {
                if ($upgrader->success) {
                    exit(0);
                } else {
                    exit(self::STOP_SIGNAL);
                }
            } else {
                if (!empty($upgrader->error)) {
                    echo "ERROR: {$upgrader->error}\n";
                }
                exit($upgrader->getStageCode($stage));
            }
        } else {
            // whole loop
            if ($stage != 'continue') {
                // reset state
                $upgrader->cleanState();
            } else {
                // remove 'continue' from the array
                array_pop($upgrader->context['argv']);
            }
            while (1) {
                $begin = time();
                $res = $upgrader->runStep($stage);
                $end = time();
                $duration = self::formatDuration($begin, $end);
                if ($res === false) {
                    if ($stage) {
                        echo "***************         Step \"{$stage}\" FAILED! - {$duration}\n";
                    }
                    exit($upgrader->getStageCode($stage));
                }
                if ($stage) {
                    echo "***************         Step \"{$stage}\" OK - {$duration}\n";
                }
                if ($res === true) {
                    // we're done successfully
                    echo "***************         SUCCESS!\n";
                    exit(0);
                }
                if ($res === self::STOP_SIGNAL) {
                    exit(0);
                }
                $stage = $res;
            }
        }
    }

    /**
     * Build argv string from an array
     * @param string $arguments
     * @return string
     */
    protected function buildArgString($arguments = array())
    {
        $argument_string = '';

        $arguments = array_merge($this->context, $arguments);

        foreach ($this->options as $ctx => $data) {
            if (!$data[0] && !isset($arguments[$ctx])) {
                continue;
            }

            $argument_string .= sprintf(" -%s %s", $data[1], escapeshellarg($arguments[$ctx]));
        }

        return $argument_string;
    }

    /**
     * Format stage duration
     *
     * @param int $begin Stage begin timestamp
     * @param int $end Stage end timestamp
     *
     * @return string
     */
    protected static function formatDuration($begin, $end)
    {
        $duration = $end - $begin;
        return $duration . ' second' . ($duration == 1 ? '' : 's');
    }

    /**
     *
     * Interactive dialog to confirm yellow flag
     * @param string $message
     * @return boolean
     */
    protected function confirmDialog($message = 'Continue?')
    {
        $output = "* $message (Yes/No) ";
        echo "\n" . $output;
        $line = trim(fgets(STDIN));
        $line = strtolower($line);
        if (in_array($line, array('yes', 'y'))) {
            return true;
        }

        return false;
    }

    /**
     * @see UpgradeDriver::doHealthcheck()
     */
    protected function doHealthcheck()
    {
        $scanner = $this->getHealthCheckScanner('cli', $this->fp, 0);
        if (!$scanner) {
            return $this->error('Cannot find health check scanner. Skipping health check stage');
        }
        $this->state['healthcheck'] = $scanner->scan();
        $this->saveState();
        $this->getHelper()->pingHeartbeat(array('bucket' => $scanner->getStatus(), 'flag' => $scanner->getFlag()));

        $scanner->dumpMeta();
        if ($scanner->isFlagRed()) {
            return $this->error(
                'Health check failed (red flags). Please refer to the log file ' . $this->context['log'],
                true
            );
        }
        if ($scanner->isFlagGreen() || $scanner->isFlagYellow()) {
            $flagLabel = $scanner->isFlagGreen() ? 'green' : 'yellow';
            echo "Health check passed ({$flagLabel} flags). Please refer to the log file {$this->context['log']}\n";

            if (isset($this->context['all']) && !$this->context['autoconfirm']) {
                if ($this->confirmDialog('Are you sure you want to continue?')) {
                    $this->log("User interactively confirmed {$flagLabel} flag(s) - proceeding");
                } else {
                    $this->log("User interactively disagreed {$flagLabel} flag(s) - aborting");
                    // The step is successful but stop upgrade.
                    $this->success = false;
                    return true;
                }
            }
        }
        return true;
    }

    /**
     * @return HealthCheckHelper
     */
    protected function getHelper()
    {
        /*
         * Loading SugarSystemInfo from health check only if version < 7.2.2
         * If version 7.2.2 and above SugarSystemInfo also loading in entryPoint.php
         */
        $sugar_version = '9.9.9';
        include "sugar_version.php";
        if (file_exists(dirname(__FILE__).'/SugarSystemInfo.php') && version_compare($sugar_version, '7.2.2', '<')) {
            require_once 'SugarSystemInfo.php';
        }
        
        if (!class_exists('SugarHeartbeatClient')) {
            require_once 'SugarHeartbeatClient.php';
        }
        if (!class_exists('HealthCheckClient')) {
            require_once 'HealthCheckClient.php';
        }
        if (!class_exists('HealthCheckHelper')) {
            require_once 'HealthCheckHelper.php';
        }

        return HealthCheckHelper::getInstance();
    }

    /**
     * @see UpgradeDriver::getPackageUid()
     * @return string
     */
    protected function  getPackageUid()
    {
        if ($this->context['zip_as_dir']) {
            $md5sum = $this->context['zip'] . DIRECTORY_SEPARATOR . 'md5sum';
            if (!file_exists($md5sum)) {
                return $this->error("md5sum file doesn't exist", true);
            }
            $md5sum = trim(file_get_contents($md5sum));
        } else {
            $md5sum = md5_file($this->context['zip']);
        }
        return $md5sum;
    }

    /**
     * Verify and unpack upgrade package
     * If we found that state file has pre step we clear state file
     * @param string $zip ZIP filename
     * @param string $dir Temp dir to use for zip files
     * @return bool|false
     */
    protected function verify($zip, $dir)
    {
        if (!empty($this->state['stage']['pre'])) {
            $this->cleanState();
        }
        return parent::verify($zip, $dir);
    }

    /**
     * Run given stage
     * If stage healthcheck and healthcheck doesn't exists we run unpack stage
     * @inheritdoc
     */
    public function run($stage)
    {
        if ($stage == 'healthcheck' &&
            (!file_exists($this->context['health_check_path']) || empty($this->state['stage']['unpack']) ||
                $this->state['stage']['unpack'] == 'failed') &&
            !$this->run('unpack')
        ) {
            return false;
        }

        return parent::run($stage);
    }
}

if (empty($argv[0]) || basename($argv[0]) != basename(__FILE__)) {
    return;
}

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) != 'cli') {
    die("This is command-line only script");
}
$upgrader = new CliUpgrader();
$upgrader->start();
