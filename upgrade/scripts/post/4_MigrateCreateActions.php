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
 * Class SugarUpgradeMigrateCreateActions
 *
 * Fixes 'create-actions' components to now be 'create' components instead.
 */
class SugarUpgradeMigrateCreateActions extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * MD5 sums from files.md5
     * @var array
     */
    protected $md5_files;

    public function run()
    {
        if (version_compare($this->from_version, '7.7', '>')) {
            return;
        }

        $md5_string = array();
        if (!file_exists('files.md5')) {
            return $this->fail("files.md5 not found");
        }

        require 'files.md5';
        $this->md5_files = $md5_string;

        require 'include/modules.php';
        $this->beanList = $beanList;
        $this->beanFiles = $beanFiles;

        $createActionsPath = 'create-actions' . DIRECTORY_SEPARATOR . 'create-actions';
        $createPath = 'create' . DIRECTORY_SEPARATOR . 'create';

        $custom = glob(
            'custom' . DIRECTORY_SEPARATOR .
            'clients' . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            '{layouts,views}' . DIRECTORY_SEPARATOR .
            $createActionsPath . '.*',
            GLOB_BRACE
        );

        $customModules = glob(
            'custom' . DIRECTORY_SEPARATOR .
            'modules' . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            'clients' . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            '{layouts,views}' . DIRECTORY_SEPARATOR .
            $createActionsPath . '.*',
            GLOB_BRACE
        );

        $modules = glob(
            'modules' . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            'clients' . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            '{layouts,views}' . DIRECTORY_SEPARATOR .
            $createActionsPath . '.*',
            GLOB_BRACE
        );

        $filesToMigrate = array_merge($custom, $customModules, $modules);
        $jsMigrateOptions = array(
            'regex' => "/(extendsFrom('|\")?(\s)*:(\s)*('|\")(.)*)CreateActions/U",
            'replacement' => '$1Create',
        );
        $phpMigrateOptions = array(
            'regex' => "/(\\\$viewdefs(.)*\[('|\"))create-actions/U",
            'replacement' => '$1create',
        );

        foreach ($filesToMigrate as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $explodedComponent = explode($createActionsPath, $file);
            $componentPath = $explodedComponent[0];
            $createFile = $componentPath . $createPath . '.' . $ext;
            $exploded = explode(DIRECTORY_SEPARATOR, $file);

            if ($exploded[0] === 'modules') {
                $module = $exploded[1];

                if (!$this->isNewModule($module)) {
                    // Skip OOB modules, since we automatically migrate these.
                    continue;
                }
            }

            if ($ext === 'js') {
                $options = $jsMigrateOptions;
            } else if ($ext === 'php') {
                $options = $phpMigrateOptions;
            }

            $this->migrateCreateActionsFile($file, $createFile, $options);
        }
    }


    /**
     * Is $module a new module or standard Sugar module?
     *
     * @param string $module
     * @return boolean $module is new?
     */
    protected function isNewModule($module)
    {
        if (empty($this->beanList[$module])) {
            // absent from module list, not an actual module
            return false;
        }
        $object = $this->beanList[$module];
        if (empty($this->beanFiles[$object])) {
            // no bean file - check directly
            $path = 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . '*';
            foreach (glob($path) as $file) {
                // if any file from this dir mentioned in md5 - not a new module
                if (!empty($this->md5_files['.' . DIRECTORY_SEPARATOR . $file])) {
                    return false;
                }
            }
            return true;
        }

        if (empty($this->md5_files['.' . DIRECTORY_SEPARATOR . $this->beanFiles[$object]])) {
            // no mention of the bean in files.md5 - new module
            return true;
        }

        return false;
    }

    /**
     * Renames $oldFilePath to $newFilePath if it doesn't already exist.
     *
     * If a js controller is passed, it parses the JS controller to change the
     * `extendsFrom` value to 'Create' instead of 'CreateActions'.
     *
     * If a php viewdef metadata file is passed, it parses the viewdef file and
     * replaces 'create-actions' with 'create' in the `$viewdefs` hash.
     *
     * @param string $oldFilePath The old file path.
     * @param string $newFilePath The new file path.
     * @param array $migrateOptions {
     *     Required hash that defines the regex and replacement for the passed
     *     file, to pass to preg_replace.
     *
     *     @type string $regex The regular expression to look for in the
     *       $oldFilepath.
     *     @type string $replacement The replacement format for the $regex whose
     *       result will be saved in $newFilePath.
     * }
     */
    private function migrateCreateActionsFile($oldFilePath, $newFilePath, $migrateOptions)
    {
        if (file_exists($newFilePath)) {
            $this->log("SugarUpgradeMigrateCreateActions: custom file already exists: {$newFilePath}. The file:" .
                " {$oldFilePath}, was not migrated."
            );
            return;
        }

        // Now, let's check if we need to find & replace any strings.
        $data = sugar_file_get_contents($oldFilePath);
        $replaced = preg_replace($migrateOptions['regex'], $migrateOptions['replacement'], $data);

        // preg_replace returns null if an error occurred.
        if (is_null($replaced)) {
            $this->log("SugarUpgradeMigrateCreateActions: Error occurred during preg_replace of {$oldFilePath}. The file: {$oldFilePath}, was not migrated.");
            return;
        }

        // OK, let's create the new file with the new content.
        if (sugar_file_put_contents($newFilePath, $replaced) === false) {
            $this->log("SugarUpgradeMigrateCreateActions: Error occurred during migration of {$oldFilePath}.");
        }
    }
}
