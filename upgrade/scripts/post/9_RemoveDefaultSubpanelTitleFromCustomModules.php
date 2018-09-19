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
 * Remove Default Subpanel Title from Custom Modules throughout Sugar App
 */
class SugarUpgradeRemoveDefaultSubpanelTitleFromCustomModules extends UpgradeScript
{
    public $order = 9716;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * A list of exceptions to allow
     *
     * This is necessary since Opps+RLI by default stores the RLI module as a
     * custom module
     *
     * @var array
     */
    protected $moduleExceptions = [
        'RevenueLineItems' => 1,
    ];

    /**
     * Module Builder custom files
     * custom/modulebuilder/builds/*_/SugarModules/modules/*_/language/*.php
     * custom/modulebuilder/packages/*_/modules/*_/language/*.php

     * Custom Module files
     * custom/application/Ext/Include/modules.ext.php (if exists)
     * include/modules_override.php (if exists)
     * Look for $moduleList array in both of these. That contains the custom modules
     * For each customModule value, look for
     * modules/$module/language/*.php

     * For all of these, load the $mod_list array, unset($mod_list['LBL_DEFAULT_SUBPANEL_TITLE'])
     * then resave the $moduleList array to the file location you just opened
     */
    public function run()
    {
        // Remove default subpanel title from language files
        $this->removeDefaultSubpanelTitle();
    }


    /**
     *  Removes default subpanel title from language files
     */
    protected function removeDefaultSubpanelTitle()
    {
        // get language files from modulebuilder package folder
        $packageFiles = $this->getFiles("custom/modulebuilder/packages/*/modules/*/language/*.php");

        // get language files from modulebuilder builds folder
        $buildFiles = $this->getFiles("custom/modulebuilder/builds/*/SugarModules/modules/*/language/*.php");

        $files = array_merge($packageFiles, $buildFiles);

        // there might be some customisations here
        if (file_exists('custom/application/Ext/Include/modules.ext.php')) {
            $moduleExtFiles = $this->getModuleListFiles('custom/application/Ext/Include/modules.ext.php');
            $files = array_merge($files, $moduleExtFiles);
        }

        // probably, here too!
        if (file_exists('include/modules_override.php')) {
            $moduleOverrideFiles = $this->getModuleListFiles('include/modules_override.php');
            $files = array_merge($files, $moduleOverrideFiles);
        }

        if (!empty($files)) {
            foreach ($files as $file) {
                $data = $this->getDataForLanguageFile($file);
                if (!empty($data)) {
                    $this->saveFileData('mod_strings', $data, $file);
                }
            }
        }
    }

    /**
     * Gets the files for custom modules added to $moduleList in modules.ext.php & modules_override.php files
     * @param $file string
     */
    protected function getModuleListFiles($file)
    {
        $moduleList = $this->getModuleListFromFile($file);
        $moduleFiles = array();
        if (!empty($moduleList)) {
            foreach ($moduleList as $module) {
                // Handle named module exceptions
                if (isset($this->moduleExceptions[$module])) {
                    continue;
                }

                $modFiles = $this->getFiles('modules/'.$module.'/language/*.php');
                $moduleFiles = array_merge($moduleFiles, $modFiles);
            }
        }
        return $moduleFiles;
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
     * Removes default subpanel title label from $mod_strings
     *
     * @param string $file file name
     * @return array $mod_strings
     */
    protected function getDataForLanguageFile($file)
    {
        $mod_strings = $this->getModStringsFromFile($file);
        return $this->removeLangProperty($mod_strings);
    }

    /**
     * Removes the default subpanel title language strings from an MB module strings array
     * @param array $data
     * @return array
     */
    public function removeLangProperty(array $data)
    {
        if (!empty($data) && array_key_exists('LBL_DEFAULT_SUBPANEL_TITLE', $data)) {
            unset($data['LBL_DEFAULT_SUBPANEL_TITLE']);
        }

        return $data;
    }

    /**
     * Gets $moduleList value from the given file
     *
     * @param string $file file name
     * @return array $mod_strings
     */
    protected function getModuleListFromFile($file)
    {
        return $this->getDataFromFile($file, 'moduleList');
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
     * @param string $string `mod_string`
     * @param array $data data to save
     * @param string $file file name
     */
    protected function saveFileData($string, $data, $file)
    {
        // This saves the changes to the file
        write_array_to_file($string, $data, $file);

        // let's log just for the heck of it!!
        $this->log("**** REMOVED: Default Subpanel Title property from here, $file");
    }
}
