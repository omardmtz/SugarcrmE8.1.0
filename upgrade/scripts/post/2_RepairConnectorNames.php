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

/**
 * Update custom conector configuration files to remove copyright symbols that
 * have broken some connector connections. Valid for any upgrades coming from
 * 7.6.0 and below to 7.6.1 and above.
 */
class SugarUpgradeRepairConnectorNames extends UpgradeScript
{
    public $order = 2150;
    public $type = self::UPGRADE_ALL;

    /**
     * List of files that need to be inspected for repair.
     *
     * @var array
     */
    public $configsToRepair = array();

    /**
     * The metadata file used to store connector metadata
     *
     * @var string
     */
    public $metadataFile = 'custom/modules/Connectors/metadata/connectors.php';

    public function run()
    {
        if (version_compare($this->from_version, '7.6.1', '<') && version_compare($this->to_version, '7.6.1', '>=')) {
            $this->setConfigsToRepair();
            $this->repairConfigs();
        }
    }

    /**
     * Looks for and sets the list of files that may need repair
     */
    protected function setConfigsToRepair()
    {
        // Look for custom configs to update, for example:
        // custom/modules/Connectors/connectors/sources/ext/rest/twitter/config.php
        $this->configsToRepair = glob('custom/modules/Connectors/connectors/sources/ext/*/*/config.php');
    }

    /**
     * Handles the actual repairing of the files
     */
    protected function repairConfigs()
    {
        $this->removeCopyrightsFromConfigs();
        $this->removeCopyrightsFromMetadata();
    }

    /**
     * Runs through the list of configs and sanitizes each file, saving the file
     * after it is cleansed.
     */
    protected function removeCopyrightsFromConfigs()
    {
        foreach ($this->configsToRepair as $path) {
            // This method does all the heavy lifting
            $config = $this->getSanitizedConfigFromFile($path);

            // Save the new config
            if (!$this->saveUpdatedConfigFile($config, $path)) {
                $this->log("CONNECTOR CONFIG UPGRADE: Could not save $path");
            }
        }
    }

    /**
     * Handles sanitization of the name elements in the metadata
     */
    protected function removeCopyrightsFromMetadata()
    {
        if (file_exists($this->metadataFile)) {
            require $this->metadataFile;
            if (isset($connectors) && is_array($connectors)) {
                foreach ($connectors as $name => $data) {
                    $connectors[$name] = $this->getSanitizedConfig($data);
                }

                if (!write_array_to_file('connectors', $connectors, $this->metadataFile)) {
                    $this->log("CONNECTOR UPGRADE: Could not save updated connector metadata");
                }
            }
        }
    }

    /**
     * Handles the actual removal of the copyright symbol from the name element
     * of the configuration data array
     * @param array $data Configuration data
     * @return array
     */
    public function getSanitizedConfig($data)
    {
        if (is_array($data) && isset($data['name'])) {
            $data['name'] = str_replace('&#169;', '', $data['name']);
        }

        return $data;
    }

    /**
     * Gets the current custom configuration from the the file. Sanitizes the
     * config array to ensure it matches what is in the OOTB config structure.
     * In upgrades to 7.6.1, connector metadata became mixed in certain
     * circumstances. This fixes that issue in addition to fixing the copyright
     * symbol in the config
     * @param string $path The custom config file path
     * @return array
     */
    protected function getSanitizedConfigFromFile($path)
    {
        // Gets the config array from the file path
        $custom = $this->getConfigFromFile($path);

        // Get the base path for use in comparisons
        $basepath = substr($path, 7);

        // If the path to the base file doesn't exist, send back the custom array
        // This will likely never happen, but better safe than sorry
        if (!file_exists($basepath)) {
            return $custom;
        }

        // Get the config from the base file
        $base = $this->getConfigFromFile($basepath);

        // Handle cleansing of the custom array by comparing against the base
        $config = $this->getSanitizedConfigParams($custom, $base);

        // Handle the cleansing of the name now and send it back
        return $this->getSanitizedConfig($config);
    }

    /**
     * Gets the config array from the file at $file
     * @param string $file Full path to a file
     * @return array
     */
    public function getConfigFromFile($file)
    {
        // Initialize the return
        $config = array();

        // Get the data in the file
        @require $file;

        // Send back the cleaned up config
        return $config;
    }

    /**
     * Recursively gets a cleansed config by comparing current to the base
     *
     * @param array $config Current config array
     * @param array $base Base config array
     * @return array
     */
    public function getSanitizedConfigParams(array $config, array $base)
    {
        $return = array();

        foreach ($config as $key => $val) {
            // Only work on saving data if the key is in the base array
            if (array_key_exists($key, $base)) {
                // If we are dealing with arrays on both, handle that now
                if (is_array($val) && is_array($base[$key])) {
                    $return[$key] = $this->getSanitizedConfigParams($val, $base[$key]);
                } else {
                    // Otherwise take the entry as is
                    $return[$key] = $val;
                }
            }
        }

        return $return;
    }

    /**
     * Writes the config data to the custom file from whence it came
     *
     * @param array $config The config data to save
     * @param string $file The file name to save
     * @return int The amount of data saved to the file
     */
    protected function saveUpdatedConfigFile($config, $file)
    {
        $write = "<?php\n/***CONNECTOR SOURCE***/\n";
        foreach ($config as $key => $val) {
            if (!empty($val)) {
                $write .= override_value_to_string_recursive2('config', $key, $val, false);
            }
        }

        return file_put_contents($file, $write);
    }
}
