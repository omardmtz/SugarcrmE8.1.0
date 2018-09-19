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

use Elastica\Client as Client;
use Elastica\Request as Request;

/**
 * Check that the Sugar FTS Engine configuration is valid
 */
class SugarUpgradeCheckFTSConfig extends UpgradeScript
{
    public $order = 200;
    public $version = '7.1.5';

    /**
     * User-agent settings
     */
    const USER_AGENT = 'SugarCRM';
    const VERSION_UNKNOWN = 'unknown';

    /**
     * ES supported versions. Same as the version checking in src/Elasticsearch/Adapter/Client.php.
     * @var array
     */
    protected $supportedVersions = array(
        array('version' =>'5.4', 'operator' => '>='),
        array('version' => '5.7', 'operator' => '<'),
    );

    public function run()
    {
        global $sugar_config;

        $ftsConfig = isset($sugar_config['full_text_engine']) ? $sugar_config['full_text_engine'] : null;
        // Check that Elastic info is set (only currently supported search engine)
        if (empty($ftsConfig) || empty($ftsConfig['Elastic']) ||
            empty($ftsConfig['Elastic']['host']) || empty($ftsConfig['Elastic']['port'])
        ) {
            // error implies fail
            $this->error('Elastic Full Text Search engine needs to be configured on this Sugar instance prior to upgrade.');
            $this->error('Access Full Text Search configuration under Administration > Search.');
        } else {
            // Test Elastic FTS connection
            $ftsStatus = $this->getServerStatusElastic($ftsConfig['Elastic']);

            if (!$ftsStatus['valid']) {
                $this->error('Connection test for Elastic Full Text Search engine failed.  Check your FTS configuration.');
                $this->error('Access Full Text Search configuration under Administration > Search.');
            }
        }
    }

    /**
     * Get the status of the Elastic server before the upgrade, using the raw Elastica library calls.
     *
     * Here we don't use src/Elasticsearch/Adapter/Client.php::verifyConnectivity() directly,
     * because the version checking there is tied to the from version of the upgrade. However, the Elastic
     * version may be changed during the upgrade. For instance, from Sugar 7.9 to 7.10, Elastic is
     * upgraded from 1.x to 5.x).
     *
     * @param array $config the Elastic server's host and port.
     * @return array
     */
    protected function getServerStatusElastic($config)
    {
        global $app_strings;
        $isValid = false;

        try {
            //add config for sugar version
            $config = $this->setSugarVersion($config);

            $client = new Client($config);
            $data = $client->request('', Request::GET)->getData();

            if (!empty($data['version']['number']) && $this->isSupportedVersion($data['version']['number'])) {
                $isValid = true;
                $displayText = $app_strings['LBL_EMAIL_SUCCESS'];
            } else {
                $displayText = $app_strings['ERR_ELASTIC_TEST_FAILED'];
                $this->error("Elastic version is unknown or unsupported!");
            }
        } catch (Exception $e) {
            $displayText = $e->getMessage();
            $this->error("Unable to get server status: $displayText");
        }

        return array('valid' => $isValid, 'status' => $displayText);
    }

    /**
     * Verify if Elasticsearch version meets the supported list.
     *
     * @param string $version Elasticsearch version to be checked
     * @return boolean
     */
    protected function isSupportedVersion($version)
    {
        $result = true;
        foreach ($this->supportedVersions as $check) {
            $result = $result && version_compare($version, $check['version'], $check['operator']);
        }
        return $result;
    }

    /**
     * Add the upgrade-to-version of sugar instance to the client header "User-Agent".
     *
     * @param array $config the Elastic server's configuration.
     * @return array
     */
    protected function setSugarVersion(array $config)
    {
        $config = empty($config)? array(): $config;
        $config['curl'][CURLOPT_USERAGENT] = self::USER_AGENT . '/' . $this->getSugarVersion();
        return $config;
    }

    /**
     * Get the upgrade-to-version of sugar instance, returns "unknown" if not available.
     * @return string
     */
    protected function getSugarVersion()
    {
        $version = $this->to_version;
        return empty($version) ? self::VERSION_UNKNOWN : $version;
    }
}
