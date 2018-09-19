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
 * Use moduleListSingular to find new modules that need buttons added.  When found, copies them from
 * the basic template SugarObject
 */
class SugarUpgradeUpdateAuditButton extends UpgradeScript
{
    public $order = 7455;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * Compares versions to determine whether this upgrade script should run
     * @return boolean
     */
    protected function shouldRun()
    {
        //run this if the version is older than 7.9
        return version_compare($this->from_version, '7.9.0.0', '<');
    }

    /**
     * Run this upgrade script if we are coming from a version prior to 7.9
     */
    public function run()
    {
        if ($this->shouldRun()) {
            $custMods = $this->getCustomModules();
            $this->updateAuditButton($custMods);
        }
    }

    /**
     * Get custom modules
     * @return array
     */
    protected function getCustomModules()
    {
        // Find all the classes we want to convert.
        $customModules = array();
        $customFiles = glob('modules/*/*_sugar.php', GLOB_NOSORT);
        foreach ($customFiles as $customFile) {
            $moduleName = str_replace('_sugar', '', pathinfo($customFile, PATHINFO_FILENAME));
            $customModules[] = $moduleName;
        }
        return $customModules;
    }

    /**
     * Get a custom module's template record.php file
     * @param string $modueName
     * @return string
     */
    protected function getTemplateFile($moduleName)
    {
        $template = StudioModuleFactory::getStudioModule($moduleName)->getType();
        $templateFile = "include/SugarObjects/templates/$template/clients/base/views/record/record.php";
        if (file_exists($templateFile)) {
            return $templateFile;
        }
        return null;
    }

    /**
     * Update record file if file has not already been created and buttons definition is empty
     * @param array $custMods array of custom modules to check for button toolbar
     */
    protected function updateAuditButton($custMods)
    {
        if (empty($custMods)) {
            return;
        }
        foreach ($custMods as $custMod) {
            $modPath = SugarAutoLoader::existingCustomOne("modules/$custMod/clients/base/views/record/record.php");
            if ($modPath) {
                $viewdefs = array();
                include $modPath;
                if (!empty($viewdefs[$custMod]['base']['view']['record'])
                    && !isset($viewdefs[$custMod]['base']['view']['record']['buttons'])) {
                    $defsToWrite = $viewdefs[$custMod]['base']['view']['record'];
                    $templateFile = $this->getTemplateFile($custMod);
                    if ($templateFile) {
                        $viewdefs = array();
                        include $templateFile;
                        if (!empty($viewdefs['<module_name>']['base']['view']['record']['buttons'])) {
                            $buttonString = json_encode($viewdefs['<module_name>']['base']['view']['record']['buttons']);
                            $buttonString = str_replace(array('<module_name>', '<_module_name>'), array($custMod, strtolower($custMod)), $buttonString);
                            $defsToWrite['buttons'] = json_decode($buttonString, true);
                            write_array_to_file("viewdefs['$custMod']['base']['view']['record']", $defsToWrite, $modPath);
                            $this->upgrader->log("Added audit button to file: $modPath");
                        }
                    }
                }
            }
        }
    }
}
