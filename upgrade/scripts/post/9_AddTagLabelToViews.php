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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Add Tag label to views
 */
class SugarUpgradeAddTagLabelToViews extends UpgradeScript
{
    public $order = 9701;
    public $type = self::UPGRADE_CUSTOM;
    protected $taggableLang = array();

    public function run()
    {
        // Get the taggable implementation language strings
        $this->setTaggableLangStrings();

        // Add the tag label to the the config
        $this->addTagLabelToConfig();

        // Add the taggable language strings
        $this->addTagLabelToLanguageFile();
    }

    /**
     * Utillity method to get `mod_strings` array from language file
     */
    public function setTaggableLangStrings()
    {
        $this->taggableLang = $this->getDataFromFile(
            'include/SugarObjects/implements/taggable/language/en_us.lang.php',
            'mod_strings'
        );
    }

    /**
     *  Adds tag labels to config files
     */
    protected function addTagLabelToConfig()
    {
        $files = $this->getFiles("custom/modulebuilder/packages/*/modules/*/config.php");

        foreach ($files as $file) {
            $data = $this->getFileData($file);
            if (!empty($data)) {
                $this->saveFileData('config', $data, $file);
            }
        }
    }

    /**
     * Adds `taggable` config option to $config, if not there already
     *
     * @param string $file file name
     * @return array $config
     */
    protected function getFileData($file)
    {
        $config = $this->getConfigFromFile($file);
        return $this->addTaggableConfigProperty($config);
    }

    /**
     * Adds the taggable property to a MB config where needed
     * @param array $config
     * @return array
     */
    public function addTaggableConfigProperty(array $config)
    {
        if (!empty($config) && !array_key_exists('taggable', $config)) {
            $config['taggable'] = 1;
        }

        return $config;
    }

    /**
     *  Adds Tag labels to language files
     */
    protected function addTagLabelToLanguageFile()
    {
        $files = $this->getFiles("custom/modulebuilder/builds/*/SugarModules/modules/*/language/en_us.lang.php");

        foreach ($files as $file) {
            $data = $this->getDataForLanguageFile($file);
            if (!empty($data)) {
                $this->saveFileData('mod_strings', $data, $file);
            }
        }
    }

    /**
     * Gets a list of files for a path
     *
     * @return array
     */
    protected function getFiles($path)
    {
        return glob($path);
    }

    /**
     * Adds `Tags` label to $mod_strings, if not there already
     *
     * @param string $file file name
     * @return array $mod_strings
     */
    protected function getDataForLanguageFile($file)
    {
        $mod_strings = $this->getModStringsFromFile($file);
        return $this->addTaggableLangProperties($mod_strings);
    }

    /**
     * Adds the taggable language strings to an MB module strings array
     * @param array $data
     * @return array
     */
    public function addTaggableLangProperties(array $data)
    {
        if (!empty($data) && !array_key_exists('LBL_TAG', $data)) {
            $data = array_merge($data, $this->taggableLang);
        }

        return $data;
    }

    /**
     * Gets $config value from the given file
     *
     * @param string $file file name
     * @return array $config
     */
    protected function getConfigFromFile($file)
    {
        return $this->getDataFromFile($file, 'config');
    }

    /**
     * Gets $mod_strings value from the given file
     *
     * @param string $file file name
     * @return array $mod_strings
     */
    protected function getModStringsFromFile($file)
    {
        return $this->getDataFromFile($file, 'mod_strings');
    }

    /**
     * Utility method to get a PHP variable from a file
     * @param string $file The file to get the data from
     * @param string $var The variable to get
     * @return mixed
     */
    protected function getDataFromFile($file, $var)
    {
        $$var = array();
        @require $file;
        return $$var;
    }

    /**
     * Save data to file and logs it
     *
     * @param string $string `config` or `mod_string`
     * @param array $data data to save
     * @param string $file file name
     */
    protected function saveFileData($string, $data, $file)
    {
        // This saves the changes to the file
        write_array_to_file($string, $data, $file);
        $parts = explode('/', $file);
        if ($string == 'config') {
            $fileName = $parts[6];
            $moduleName = $parts[5];
        } else {
            $fileName = $parts[8];
            $moduleName = $parts[6];
        }
        $this->log("**** SAVED: Taggable implementations for the custom module, $moduleName, in $fileName.");
    }
}
