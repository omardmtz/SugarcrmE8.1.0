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

use Sugarcrm\Sugarcrm\SearchEngine\AdminSettings;
use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement\MultiFieldHandler;

/**
 * Upgrade script to update the FTS settings.
 */
class SugarUpgradeUpdateFTSSettings extends UpgradeScript
{
    /////////////////////////////////////////////////////////////////////////
    //
    // The order number 1099 is important here, since the script expects
    // 1_ClearVardefs.php to be run next.
    // The $dictionary is not clean by calling VardefManager::refreshVardefs()
    // with $includeExtension = false in ::getNewFieldDefs() of this script.
    //
    /////////////////////////////////////////////////////////////////////////
    public $order = 1099;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * The AdminSetting class instance
     * @var object
     */
    protected $ftsAdmin;

    /**
     * The list of FTS modules
     * @var object
     */
    protected $ftsModules;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.7', '<')) {
            $this->ftsAdmin = new AdminSettings();
            if (!isset($this->ftsAdmin)) {
                $this->log("SugarUpgradeUpdateFTSSettings: cannot instantiate AdminSettings!");
                return;
            }
            $this->updateModuleList();
            $this->updateFieldSettings();
        }
    }

    /**
     * Update the modules' FTS enabled/disabled settings.
     */
    public function updateModuleList()
    {
        try {
            // This should bring the newest moduleList into the global app list
            // strings array
            $this->ensureNewestModuleList();

            list($enabled, $disabled) = $this->mergeModuleList();

            //save the settings to ext files, but do not rebuild cache
            if (isset($this->ftsAdmin)) {
                $this->ftsAdmin->saveFTSModuleListSettings($enabled, $disabled, false);
            }
        } catch (Exception $e) {
            $this->log("SugarUpgradeUpdateFTSSettings: updating FTS module list got exceptions!");
        }

    }

    /**
     * Merge the old and new settings for the modules.
     * @return array
     */
    protected function mergeModuleList()
    {
        $oldModules = $this->getOldModuleList();

        if (!isset($this->ftsModules)) {
            $this->ftsModules = $this->getNewModuleList();
        }
        $newModules = $this->ftsModules;

        //the new module list is supposed to contain all the modules from the old list
        $extraModules = array_diff(array_keys($oldModules), array_keys($newModules));
        if (!empty($extraModules)) {
            $this->log("SugarUpgradeUpdateFTSSettings failure: extra modules from the old list found!");
            return array(array(), array());
        }

        $modules = array_merge($newModules, $oldModules);
        $enabled = array_keys($modules, true);
        sort($enabled);
        $disabled = array_keys($modules, false);
        sort($disabled);

        return array($enabled, $disabled);
    }

    /**
     * Get the old settings from Unified Search.
     * @return array
     */
    protected function getUsaModuleList()
    {
        $usa = new UnifiedSearchAdvanced();
        $usaModules = $usa->getUnifiedSearchModulesDisplay();
        return $usaModules;
    }

    /**
     * Get the old module list for merge.
     * @return array
     */
    protected function getOldModuleList()
    {
        $usaModules = $this->getUsaModuleList();
        $modules = array();
        foreach ($usaModules as $module => $data) {
            //Knowledge Base module changed from "KBDocuments" to "KBContents"
            if ($module === "KBDocuments") {
                $name = "KBContents";
            } else {
                $name = $module;
            }
            $modules[$name] = $data['visible'];
        }
        return $modules;
    }

    /**
     * Get the new settings from Full Text Search.
     * @return array
     */
    protected function getFTSModuleList()
    {
        if (isset($this->ftsAdmin)) {
            return $this->ftsAdmin->getModules();
        }
        return array(array(), array());
    }

    /**
     * Get the new module list for merge.
     * @return array
     */
    protected function getNewModuleList()
    {
        list($enabled, $disabled) = $this->getFTSModuleList();

        //compose the full module list with
        //'enabled_module' => true,
        //'disabled_module' => false,
        return array_merge(array_fill_keys($enabled, true), array_fill_keys($disabled, false));
    }

    /**
     * Update the modules' FTS fields' settings.
     */
    public function updateFieldSettings()
    {
        try {
            //Get the list of modules
            if (isset($this->ftsModules)) {
                $modules = array_keys($this->ftsModules);
            } else {
                $modules = array_keys($this->getNewModuleList());
            }

            //Update the extension files
            foreach ($modules as $module) {
                $files = $this->getSugarFieldFiles($module);
                if (empty($files)) {
                    continue;
                }

                $oldDefs = $this->getFieldDefsFromDictionary($module);
                $newDefs = $this->getNewFieldDefs($module);
                foreach ($files as $file) {
                    $fieldName = $this->getSugarFieldName($file);
                    $oldDef = !empty($oldDefs[$fieldName])?$oldDefs[$fieldName]:array();
                    $newDef = !empty($newDefs[$fieldName])?$newDefs[$fieldName]:array();

                    //compose the updated def for the FTS field by merging the old and the new
                    $mergedFtsDef = $this->mergeFtsDefs($oldDef, $newDef);
                    $this->writeToFieldFile($file, $module, $fieldName, $mergedFtsDef);
                }
            }

        } catch (Exception $e) {
            $this->log("SugarUpgradeUpdateFTSSettings: updating FTS fields settings got exceptions!");
        }
    }

    /**
     * Get the list of extension files sugarfield_xxx.php for the given module
     * @param string $module the name of the module
     * @return array
     */
    protected function getSugarFieldFiles($module)
    {
        $fileName = "custom/Extension/modules/" . $module . "/Ext/Vardefs/sugarfield_*.php";
        $files = glob($fileName);
        return $files;
    }

    /**
     * Get theh field name from the file name
     * @param $file
     * @return string
     */
    protected function getSugarFieldName($file)
    {
        $fileName = baseName($file);
        preg_match('/^sugarfield_(.*)\.php$/', $fileName, $matches);
        if (empty($matches)) {
            $name = '';
        } else {
            $name = $matches[1];
        }
        return $name;
    }

    /**
     * Get the field defs from the global $dictionary
     * @param string $module the module name
     * @return array
     */
    protected function getFieldDefsFromDictionary($module)
    {
        global $dictionary;

        $moduleName = \BeanFactory::getObjectName($module);
        return $dictionary[$moduleName]['fields'];
    }

    /**
     * Save to the extension vardef file.
     * @param string $file the file name
     * @param string $module the module name
     * @param string $fieldName the field name
     */
    protected function writeToFieldFile($file, $module, $fieldName, $ftsDef)
    {
        include($file);

        $moduleName = \BeanFactory::getObjectName($module);
        //checking if the file contains the field
        if (empty($dictionary[$moduleName]['fields'][$fieldName])) {
            return;
        }

        if (!empty($ftsDef)) {
            $dictionary[$moduleName]['fields'][$fieldName]['full_text_search'] = $ftsDef;
        } else {
            if (!empty($dictionary[$moduleName]['fields'][$fieldName]['full_text_search'])) {
                unset($dictionary[$moduleName]['fields'][$fieldName]['full_text_search']);
            }
        }

        $out =  "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
        foreach (array_keys($dictionary) as $key) {
            $out .= override_value_to_string_recursive2('dictionary', $key, $dictionary[$key]);
        }
        $out .= "\n";

        //write to the file
        sugar_file_put_contents_atomic($file, $out);
    }

    /**
     * Get the vardef for a given module from MetaDataHelper
     * @param string $module the module name
     * @return array
     */
    protected function getNewFieldDefs($module)
    {
        $moduleName = \BeanFactory::getObjectName($module);

        //load the vardefs without the custom fields from extension files
        VardefManager::refreshVardefs($module, $moduleName, null, false, array(), false);

        return $this->getFieldDefsFromDictionary($module);
    }

    /**
     * Merge and compose the new def for FTS property
     * @param array $oldDef the old fts def before the upgrade
     * @param array $newDef the new def from MetaDataHelper
     * @return array
     */
    protected function mergeFtsDefs($oldDef, $newDef)
    {
        if (!empty($newDef) && !empty($newDef['type'])) {
            $type = $newDef['type'];
        } else {
            if (!empty($oldDef) && !empty($oldDef['type'])) {
                $type = $oldDef['type'];
            } else {
                return array();
            }
        }

        if (!$this->isSupportedType($type)) {
            return array();
        }

        if (isset($newDef['full_text_search'])) {
            $mergedDef = $newDef['full_text_search'];
        } else {
            $mergedDef = array();
        }

        if (isset($oldDef['full_text_search'])) {
            //studio fields should have enabled = true, searchable = true/false
            if (isset($oldDef['full_text_search']['enabled'])) {
                $mergedDef['enabled'] = true;
                $mergedDef['searchable'] = ($oldDef['full_text_search']['enabled'] === true)? true: false;
            }
        }

        if (empty($mergedDef['boost'])) {
            $mergedDef['boost'] = 1;
        }
        return $mergedDef;
    }

    /**
     * Check if a type is a supported type in the new global search
     * @param string $type the field type
     * @return bool
     */
    protected function isSupportedType($type)
    {
        $handler = new MultiFieldHandler();
        return in_array($type, $handler->getSupportedTypes());
    }

    /**
     * Loads up the module list into the GLOBAL app_list_strings array, because
     * down the line MetaDataManager uses the GLOBAL app_list_string array to
     * build the module list that the FTS objects use to determine enabled and
     * disabled state.
     * @return boolean
     */
    protected function ensureNewestModuleList()
    {
        // Realistically we only need english since that holds the module list
        // by name of the module. We also pass the false flag to force a new load
        // of the app_list_strings so that we have the latest and greatest data.
        $GLOBALS['app_list_strings'] = return_app_list_strings_language('en_us', false);
        if (!isset($GLOBALS['app_list_strings']['moduleList']['Tags'])) {
            throw new \Exception("Tags module not brought into new module list for setting FTS settings");
        }

        return true;
    }
}
