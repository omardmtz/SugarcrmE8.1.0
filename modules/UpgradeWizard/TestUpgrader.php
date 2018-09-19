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
require_once 'modules/UpgradeWizard/UpgradeDriver.php';

/**
 * Test upgrader class
 *
 * Used for unit tests on upgrader
 */
class TestUpgrader extends UpgradeDriver
{
    /**
     * List of upgrade scripts
     * @var string
     */
    protected $scripts = array();

    public function __construct($admin)
    {
        $this->context = array(
            "admin" => $admin->user_name,
            "log" => "cache/upgrade.log",
            "source_dir" => realpath(dirname(__FILE__)."/../../"),
            'new_source_dir' => realpath(dirname(__FILE__)."/../../"),
            "zip" => "UNITTEST",
        );
        parent::__construct();
        $this->init();
        $this->start();
    }

    public function start()
    {
        list($version, $build) = static::getVersion();
        $this->log("TestUpgrader v.$version (build $build) starting");
    }

    public function cleanState()
    {
        $statefile = $this->cacheDir('upgrades/').self::STATE_FILE;
        if(file_exists($statefile)) {
            unlink($statefile);
        }
    }

    public function runStage($stage)
    {
        return $this->run($stage);
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Get script object for certain script
     * @param string $stage
     * @param string $script
     * @return UpgradeScript
     */
    public function getScript($stage, $script)
    {
        if(empty($this->scripts[$stage])) {
            $this->scripts[$stage] = $this->getScripts(dirname($script), $stage);
        }
        return $this->scripts[$stage][$script];
    }

    public function getTempDir()
    {
        if (empty($this->context['temp_dir'])) {
            $this->context['temp_dir'] = '';
        }
        return $this->context['temp_dir'];
    }

    public function setVersions($from, $flav_from, $to, $flav_to)
    {
        $this->from_version = $from;
        $this->from_flavor = $flav_from;
        $this->to_version = $to;
        $this->to_flavor = $flav_to;
    }

    protected function doHealthcheck()
    {
        return true;
    }
}
