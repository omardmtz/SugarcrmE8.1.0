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
 * Merge sidecar defs for Sugar 7
 * This is a first part of the process, only collecting the information.
 * The other part is done by post-script 7_Merge7Templates.
 * @see BR-1491
 */
class SugarUpgradeMerge7 extends UpgradeScript
{
    public $order = 401;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if(version_compare($this->from_version, '7.0', '<')) {
            // This is for 7->7 upgrades
            return;
        }

        if(empty($this->context['new_source_dir'])) {
            $this->log("**** Merge skipped - no new source dir");
            return;
        }

        /* look for views that:
         * 1. Have custom view
         * 2. Changed between old and new
         */
        foreach(glob("modules/*", GLOB_ONLYDIR) as $dir) {
            if(!is_dir("$dir/clients/") || !is_dir("custom/$dir/clients") || !is_dir("{$this->context['new_source_dir']}/$dir/clients")) {
                // either does not have clients or is not customized
                continue;
            }
            $module_name = substr($dir, 8); // cut off modules/
            $this->log("Checking $dir");
            foreach(glob("$dir/clients/*/views/*/*.php") as $phpfile) {
                if(!file_exists("{$this->context['new_source_dir']}/$phpfile")) {
                    // no longer in the source - skip
                    continue;
                }
                if(!file_exists("custom/$phpfile")) {
                    // not customized - skip
                    continue;
                }
                $this->checkFile($phpfile);
            }
        }
    }

    /**
     * Load view file
     * @param string $filename
     * @param string $module_name
     * @param string $platform
     * @param string $viewname
     * @return NULL|array
     */
    protected function loadFile($filename, $module_name, $platform, $viewname)
    {
        $viewdefs = array();
        include $filename;
        if(empty($viewdefs) || empty($viewdefs[$module_name][$platform]['view'][$viewname]['panels'])) {
            // we do not handle non-panel views for now
            return null;
        }
        return $viewdefs;
    }

    /**
     * Check if the file needs to be merged
     * @param string $filename
     */
    protected function checkFile($filename)
    {
        $this->log("Checking file $filename");
        list($modules, $module_name, $clients, $platform, $views, $viewname) = explode(DIRECTORY_SEPARATOR, $filename);

        $old_viewdefs = $this->loadFile($filename, $module_name, $platform, $viewname);
        $new_viewdefs = $this->loadFile("{$this->context['new_source_dir']}/$filename", $module_name, $platform, $viewname);
        $custom_viewdefs =  $this->loadFile("custom/$filename", $module_name, $platform, $viewname);

        if(empty($old_viewdefs) || empty($new_viewdefs) || empty($custom_viewdefs)) {
            // defs missing - can't do anything here
            return;
        }

        // Shorten our viewdef path for easier handling
        $oldDefs = $old_viewdefs[$module_name][$platform]['view'][$viewname];
        $newDefs = $new_viewdefs[$module_name][$platform]['view'][$viewname];
        $customDefs = $custom_viewdefs[$module_name][$platform]['view'][$viewname];
        if ($this->defsUnchanged($oldDefs, $newDefs, $customDefs)) {
            // no changes to handle
            return;
        }
        $this->log("Queued for merge: $filename");
        $this->upgrader->state['for_merge'][$filename] = $old_viewdefs;
    }

    /**
     * Check to see if there are changes between the old def and the new def OR 
     * the custom def and the new def
     * 
     * @param array $old View defs for the previous installation
     * @param array $new View defs for the upgraded installation
     * @param array $custom Custom viewdefs from the previous installation
     * @return boolean
     */
    public function defsUnchanged($old, $new, $custom)
    {
        // Grab all keys of the defs so we can diff them
        $oldKeys = array_keys($old);
        $custKeys = array_keys($custom);

        // Set our default flag, which says that there were no changes
        $changed = false;

        // Check old defs for changes in the new
        foreach ($oldKeys as $key) {
            if (!isset($new[$key]) || $old[$key] != $new[$key]) {
                $changed = true;
            }
        }

        // Check custom props for changes in the new
        foreach ($custKeys as $def) {
            if (!isset($new[$def]) || $custom[$def] != $new[$def]) {
                $changed = true;
            }
        }

        return $changed === false;
    }
}
