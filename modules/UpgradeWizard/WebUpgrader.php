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
require_once dirname(__FILE__) . '/UpgradeDriver.php';

/**
 * Web driver
 *
 */
class WebUpgrader extends UpgradeDriver
{
    /**
     * License file content
     * @var string
     */
    public $license;
    /**
     * Readme file content
     * @var string
     */
    public $readme;

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

    /**
     * IIS configuration file name
     */
    const IIS_CONFIG = 'web.config';

    /**
     * maxAllowedContentLength value for IIS. 100M
     */
    const IIS_CONTENT_LENGTH = 104857600;

    /**
     * Updating IIS config if maxAllowedContentLength < 100M or not set
     */
    protected function updateMaxAllowedContentLength()
    {
        if (!file_exists(self::IIS_CONFIG)) {
            return;
        }

        $saveConfig = false;

        $config = new DOMDocument('1.0', 'UTF-8');
        $config->formatOutput = true;
        $config->load(self::IIS_CONFIG);

        $xpath = new DOMXPath($config);
        $path = 'configuration/system.webServer/security/requestFiltering/requestLimits';
        $lengthAttribute = $xpath->query('//' . $path . '/@maxAllowedContentLength');

        if ($lengthAttribute->length) {

            $currentLength = $lengthAttribute->item(0)->value;
            if ($currentLength < self::IIS_CONTENT_LENGTH) {
                $lengthAttribute->item(0)->value = self::IIS_CONTENT_LENGTH;
                $saveConfig = true;
            }
        } else {

            $elements = explode('/', $path);
            $currentPath = '/';
            $currentNode = null;
            foreach ($elements as $nodeName) {

                $currentPath .= '/' . $nodeName;
                if (!$xpath->query($currentPath)->length) {
                    $newChild = $config->createElement($nodeName, null);
                    $currentNode = $currentNode->appendChild($newChild);
                } else {
                    $currentNode = $xpath->query($currentPath)->item(0);
                }
            }

            if ($currentNode) {
                $lengthAttribute = $config->createAttribute('maxAllowedContentLength');
                $lengthAttribute->value = self::IIS_CONTENT_LENGTH;
                $currentNode->appendChild($lengthAttribute);
                $saveConfig = true;
            }
        }

        if ($saveConfig) {
            $config->save(self::IIS_CONFIG);
        }
    }

    public function runStage($stage)
    {
        return $this->run($stage);
    }

    public function __construct($dir)
    {
        $this->context['source_dir'] = $dir;
        $this->context['log'] = "UpgradeWizard.log";
        $this->context['HealthCheckLog'] = "HealthCheck.log";
        $this->context['zip'] = ''; // temporary
        parent::__construct();
    }

    public function start()
    {
        if (!isset($this->state['stage']) || !array_search('started', $this->state['stage'])) {
            list($version, $build) = self::getVersion();
            $this->log("WebUpgrader v.$version (build $build) starting");
        }
    }

    protected function initSession()
    {
        if (!isset($_SESSION)) {
            // Oauth token support
            if (isset($_SERVER['HTTP_OAUTH_TOKEN'])) {
                session_id($_SERVER['HTTP_OAUTH_TOKEN']);
            }
            session_start();
        }
    }

    /**
     * Check if we've started upgrade process and have correct token
     * If yes, setup current request
     * @param string $token
     * @return boolean
     */
    public function startRequest($token)
    {
        if (!$this->checkTokenAndAdmin($token)) {
            return false;
        }
        if (!empty($this->state['zip'])) {
            $this->context['zip'] = $this->state['zip'];
            $this->context['backup_dir'] = $this->config['upload_dir'] . "/upgrades/backup/" . pathinfo(
                    $this->context['zip'],
                    PATHINFO_FILENAME
                ) . "-restore";
        }
        return true;
    }

    /**
     * Checks token and Admin User
     * @param string $token
     * @return boolean
     */
    public function checkTokenAndAdmin($token)
    {
        return (!empty($token) && !empty($this->state['webToken'])
            && $token == $this->state['webToken'] && !empty($this->state['admin']));
    }

    /**
     * {@inheritDoc}
     */
    protected function getUser()
    {
        $user = $this->loadUser(array(
            'id' => $this->state['admin'],
        ));

        if ($user->id) {
            $this->context['admin'] = $user->user_name;
        }

        return $user;
    }

    /**
     * Files that are used by the upgrade driver
     * We copy them so that upgrading would not mess them up
     * @var array
     */
    protected $upgradeFiles = array(
        'WebUpgrader.php',
        'UpgradeDriver.php',
        'upgrade_screen.php',
        'version.json'
    );

    /**
     * Start upgrade process
     * @return boolean
     */
    public function startUpgrade()
    {
        // Load admin user name
        $this->initSession();
        if (empty($_SESSION['authenticated_user_id'])) {
            return false;
        }
        $this->cleanState();
        $this->state['admin'] = $_SESSION['authenticated_user_id'];
        $this->initSugar();
        if (empty($GLOBALS['current_user']) || !$GLOBALS['current_user']->isAdmin()) {
            return $this->errorPrint('ERR_NOT_ADMIN');
        }
        if (!empty($GLOBALS['sugar_config']['disable_uw_upload'])) {
            return $this->errorPrint('ERR_NO_VIEW_ACCESS_REASON');
        }
        $this->state['webToken'] = create_guid();
        $this->saveState();
        // copy upgrader files
        $upg_dir = $this->cacheDir("upgrades/driver/");
        $this->ensureDir($upg_dir);
        $_SESSION['upgrade_dir'] = $upg_dir;
        foreach ($this->upgradeFiles as $ufile) {
            if (file_exists("modules/UpgradeWizard/$ufile")) {
                copy("modules/UpgradeWizard/$ufile", "{$upg_dir}{$ufile}");
            }
        }

        $this->updateMaxAllowedContentLength();

        return $this->state['webToken'];
    }

    /**
     * Get upgrade status
     * @return array
     */
    protected function getStatus()
    {
        $state = array();
        if (isset($this->state['stage'])) {
            $state['stage'] = $this->state['stage'];
        }
        if (isset($this->state['scripts'])) {
            $state['scripts'] = $this->state['scripts'];
        }
        if (isset($this->state['script_count'])) {
            $state['script_count'] = $this->state['script_count'];
        }
        return $state;
    }

    /**
     * Process upgrade action
     * @param string $action
     * @return next stage name or false on error
     */
    public function process($action)
    {
        if ($action == "status") {
            return $this->getStatus();
        }

        if ($action == 'sendlog') {
            // send Log to Sugar is disabled
            $this->log('Warning: send log file to Sugar is disabled');
            return true;
        }

        if (!in_array($action, $this->stages)) {
            return $this->error("Unknown stage $action", true);
        }
        if ($action == 'unpack') {
            // accept file upload
            if (!$this->handleUpload()) {
                return false;
            }
        }
        $res = $this->runStep($action);
        if ($res !== false) {
            if ($action == 'unpack') {
                $manifest = $this->getManifest();
                if (empty($manifest)) {
                    return false;
                }
                if (!empty($manifest['copy_files']['from_dir'])) {
                    $new_source_dir = $this->context['extract_dir'] . "/" . $manifest['copy_files']['from_dir'];
                } else {
                    $this->error("No from_dir in manifest", true);
                    return false;
                }
                if (is_file("$new_source_dir/LICENSE")) {
                    $this->license = file_get_contents("$new_source_dir/LICENSE");
                } elseif (is_file("$new_source_dir/LICENSE.txt")) {
                    $this->license = file_get_contents("$new_source_dir/LICENSE.txt");
                } elseif (is_file($this->context['source_dir'] . "/LICENSE.txt")) {
                    $this->license = file_get_contents($this->context['source_dir'] . "/LICENSE.txt");
                } elseif (is_file($this->context['source_dir'] . "/LICENSE")) {
                    $this->license = file_get_contents($this->context['source_dir'] . "/LICENSE");
                }
                if (is_file($this->context['extract_dir'] . "/README")) {
                    $this->readme = file_get_contents($this->context['extract_dir'] . "/README");
                } elseif (is_file($this->context['extract_dir'] . "/README.txt")) {
                    $this->readme = file_get_contents($this->context['extract_dir'] . "/README.txt");
                }
            }
            return $res;
        }
        return false;
    }

    /**
     * Messages for upload errors
     * @var array
     */
    protected $upload_errors = array(
        0 => "There is no error, the file uploaded with success",
        1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3 => "The uploaded file was only partially uploaded",
        4 => "No file was uploaded",
        6 => "Missing a temporary folder",
        7 => "Failed to write file to disk",
        8 => "A PHP extension stopped the file upload",

    );

    /**
     * Handle zip file upload
     * @return boolean
     */
    protected function handleUpload()
    {
        if (empty($_FILES['zip'])) {
            return $this->error("Expected file upload", true);
        }
        if ($_FILES['zip']['error'] != UPLOAD_ERR_OK) {
            return $this->error(
                "File upload error: {$this->upload_errors[$_FILES['zip']['error']]} ({$_FILES['zip']['error']})",
                true
            );
        }
        if (!is_uploaded_file($_FILES['zip']['tmp_name'])) {
            return $this->error("Upload failed", true);
        }
        $this->ensureDir($this->config['upload_dir'] . "/upgrades");
        $this->context['zip'] = $this->config['upload_dir'] . "/upgrades/" . basename($_FILES['zip']['name']);
        if (move_uploaded_file($_FILES['zip']['tmp_name'], $this->context['zip'])) {
            $this->state['zip'] = $this->context['zip'];
            $this->context['backup_dir'] = $this->config['upload_dir'] . "/upgrades/backup/" . pathinfo(
                    $this->context['zip'],
                    PATHINFO_FILENAME
                ) . "-restore";
            $this->saveState();
            return true;
        } else {
            return $this->error("Failed to move uploaded file to {$this->context['zip']}", true);
        }

    }

    /**
     * Display upgrade screen page
     */
    public function displayUpgradePage()
    {
        global $token;
        $upgraderVesion = $this->context['versionInfo'][0];
        $upgraderBuild = $this->context['versionInfo'][1];
        $this->log("WebUpgrader v." . $upgraderVesion . " (build " . $upgraderBuild . ") starting");
        include dirname(__FILE__) . '/upgrade_screen.php';
    }

    /**
     * Remove temp files for upgrader
     */
    public function removeTempFiles()
    {
        parent::removeTempFiles();
        $this->removeDir($this->cacheDir("upgrades/driver/"));
    }

    /**
     * @see UpgradeDriver::doHealthcheck()
     */
    protected function doHealthcheck()
    {
        $scanner = $this->getHealthCheckScanner('web');
        if (!$scanner) {
            return $this->error("Cannot find health check scanner", true);
        }
        $scanner->setLogFile($this->context['HealthCheckLog']);

        $scanner->scan();

        $logsInfo = $scanner->getLogMeta();
        $this->state['healthcheck'] = $logsInfo;
        $this->saveState();

        if ($logsInfo) {
            $this->log('*** START HEALTHCHECK ISSUES ***');
            foreach ($scanner->getLogMeta() as $key => $entry) {
                $issueNo = $key + 1;
                $this->log(
                    " => {$entry['bucket']}: [Issue {$issueNo}][{$entry['flag_label']}][{$entry['report']}][{$entry['id']}][{$entry['title']}] {$entry['descr']}"
                );
            }
            $this->log('*** END HEALTHCHECK ISSUES ***');
        }

        $this->getHelper()->pingHeartbeat(array('bucket' => $scanner->getStatus(), 'flag' => $scanner->getFlag()));

        if ($scanner->isFlagRed()) {
            $logDetails = array();
            foreach ($logsInfo as $key => $log) {
                if ($log['flag'] == HealthCheckScannerMeta::FLAG_RED) {
                    $logDetails = $log;
                    break;
                }
            }
            return $this->error("The health check didn't pass: " . $logDetails['log'], true);
        }
        return true;
    }

    /**
     * @return HealthCheckHelper
     */
    protected function getHelper()
    {
        require_once 'include/SugarSystemInfo/SugarSystemInfo.php';
        require_once 'include/SugarHeartbeat/SugarHeartbeatClient.php';
        require_once 'HealthCheckHelper.php';
        if (!class_exists('HealthCheckClient')) {
            require_once 'HealthCheckClient.php';
        }
        return HealthCheckHelper::getInstance();
    }
}
