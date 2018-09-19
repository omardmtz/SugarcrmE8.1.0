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
 * Changes the email1 field to email field on module list views
 */
class SugarUpgradeUpdateEmailFieldOnSidecarListViews extends UpgradeScript
{
    public $order = 7200;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * For this upgrader, this only needs to be list
     * @var string
     */
    protected $viewType = 'list';

    /**
     * Indicates whether a save is necessary
     * @var boolean
     */
    protected $saveChanges = false;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Only run this upgrader version coming from less than 7.7
        if (version_compare($this->from_version, '7.7', '>=')) {
            return;
        }

        $this->updatePackageViewdefs();
        $this->updateModuleViewdefs();
    }

    /**
     * Updates the viewdefs for $this->viewType for all packages, including staged,
     * history and built packages
     */
    protected function updatePackageViewdefs()
    {
        // This process simply replaces an email1 string with email
        // And changes the label, because that is important
        // And adds a header to the file
        $pattern = array(
            '/(\\\'email1\\\'|"email1")/',
            '/(\\\'LBL_EMAIL_ADDRESS\\\'|"LBL_EMAIL_ADDRESS")/',
            '/<\?php/',
        );

        $header  = "/**\n * This file was updated by the Sugar upgrader on " . date('Y-m-d H:i:s');
        $header .= "\n */";

        $replace = array(
            "'email'",
            "'LBL_ANY_EMAIL'",
            "<?php\n$header",
        );

        // Get all of the modulebuilder files
        $files = $this->getPackageViewFiles($this->viewType);

        // Loop the files and handle string replacements
        foreach ($files as $file) {
            if (is_readable($file)) {
                $contents = file_get_contents($file);
                // If there is an email1 in the viewdefs, replace it
                if (preg_match($pattern[0], $contents)) {
                    $clean = preg_replace($pattern, $replace, $contents);
                    if ($this->savePackageFile($file, $clean)) {
                        $this->log("email1 to email {$this->viewType} view field conversion done for $file");
                    }else {
                        $this->log("email1 to email {$this->viewType} view field conversion ***FAILED*** for $file");
                    }
                } else {
                    $this->log("email1 to email {$this->viewType} view field conversion ***SKIPPED*** for $file");
                }
            }
        }
    }

    /**
     * Updates the viewdefs for $this->viewType for all deployed and OOTB modules
     */
    protected function updateModuleViewdefs()
    {
        $files = $this->getViewFileData($this->viewType);
        foreach ($files as $data) {
            if (!empty($data['defs']['panels'])) {
                // Used only for logging at this point
                $custom = !empty($data['custom']) ? 'custom' : '';

                // Get the updated panels, if there are any
                $panels = $this->getSanitizedDefs($data['defs']['panels']);

                // Only save if there are changes to save
                if ($this->hasChanges()) {
                    // This puts the panels back to where they belong in the defs
                    $defs = array('panels' => $panels);

                    // This saves the changes to the file
                    $this->saveChanges($defs, $data['file'], $data['module'], $this->viewType, $data['client']);
                    $this->log("email1 to email {$this->viewType} view field conversion done for $custom $data[module] $data[client] viewdefs");
                } else {
                    $this->log("email1 to email {$this->viewType} view field conversion ***SKIPPED*** for $custom $data[module] $data[client] viewdefs - no changes made");
                }
            }
        }
    }

    /**
     * Gets all file paths for type $view for all packages
     * @param string $view The type of view to get files for
     * @return array
     */
    public function getPackageViewFiles($view)
    {
        // Start with MB package modules with configs, since we need to confirm
        // that a module is a company
        $packages = $this->getCompanyModulePackages();

        // Now get the view meta for all modules from the various paths
        return $this->getViewFilesFromPackages($view, $packages);
    }

    /**
     * Gets view files from a package. This will get staged, history and deployed
     * files for a view type.
     * @param string $view The view to get files for
     * @param  array $packages Listing of modulebuilder packages
     * @return array
     */
    public function getViewFilesFromPackages($view, array $packages)
    {
        $files = array();

        // MB Package path to view files
        $viewPath = "custom/modulebuilder/packages/%s/modules/%s/clients/*/views/$view/$view.php";

        // MB Build history path
        $histPath = "custom/working/modulebuilder/packages/%s/modules/%s/clients/*/views/$view/$view.php*";

        foreach ($packages as $package => $modules) {
            foreach (array_keys($modules) as $module) {
                foreach (array($viewPath, $histPath) as $filePath) {
                    $path = sprintf($filePath, $package, $module);
                    $files = array_merge($files, glob($path));
                }

                // Then get build files
                $builds = $this->getPackageBuildFiles($package, $module, $view);
                $files = array_merge($files, $builds);
            }
        }

        return $files;
    }

    /**
     * Gets build files for a view, module and package
     * @param string $package The package to get the build files for
     * @param string $module The module to get the file path for
     * @param string $view The view to get the file path for
     * @return array
     */
    protected function getPackageBuildFiles($package, $module, $view)
    {
        // Common path prefix for this method
        $prefix = "custom/modulebuilder/builds/$package/SugarModules/modules";

        // Get all of our $view files in the build tree
        return glob("$prefix/{$package}_$module/clients/*/views/$view/$view.php");
    }

    /**
     * Gets package that contain modules of type company
     * @return array
     */
    public function getCompanyModulePackages()
    {
        // Set up the return
        $packages = array();

        // Search along the modulebuilder packages paths
        $configs = glob('custom/modulebuilder/packages/*/modules/*/config.php');
        foreach ($configs as $configFile) {
            $config = array();
            include $configFile;

            // If this module config specifies a company template, use it
            if (isset($config['templates']['company'])) {
                // Easy way to get the package and module names from the path
                $parts = explode('/', $configFile);

                // Build a result array of package module data
                $packages[$parts[3]][$parts[5]] = true;
            }
        }

        return $packages;
    }

    /**
     * Resets the save change flag to false so that the field finder can set it
     * to true if there is a change to save
     */
    protected function resetSaveFlag()
    {
        $this->saveChanges = false;
    }

    /**
     * Checks the state of needed changes
     * @return boolean
     */
    public function hasChanges()
    {
        return $this->saveChanges;
    }

    /**
     * Changes the email1 field to email on list views for all platforms
     *
     * @param array $panels
     */
    public function getSanitizedDefs(array $panels)
    {
        $this->resetSaveFlag();
        foreach ($panels as $panelKey => $panel) {
            if (isset($panel['fields'])) {
                foreach ($panel['fields'] as $fieldKey => $field) {
                    if (isset($field['name']) && $field['name'] === 'email1') {
                        // Set the field name to the correct field name
                        $panels[$panelKey]['fields'][$fieldKey]['name'] = 'email';

                        // And since the label is of utmost importance, set that too
                        $panels[$panelKey]['fields'][$fieldKey]['label'] = 'LBL_ANY_EMAIL';

                        // Set the write flag, since it doesn't make sense to write
                        // unless there are changes to write
                        $this->saveChanges = true;

                        // Once we find it, stop
                        break 2;
                    }
                }
            }
        }

        return $panels;
    }

    /**
     * Get array of of data containing file name, module name, client and defs
     *
     * @param string $view
     * @return array
     */
    public function getViewFileData($view)
    {
        // Get all the core module list views first
        $core = glob("modules/*/clients/*/views/$view/$view.php");;

        // Get all custom $view type viewdefs for all modules and platforms
        $custom = glob("custom/modules/*/clients/*/views/$view/$view.php");

        $files = array_merge($core, $custom);
        return $this->getViewFileDataFromFiles($files);
    }

    /**
     * Gets a list of custom view files and related data from a list of paths
     * @param array $files Array of paths, usually from a glob() call
     * @return array
     */
    public function getViewFileDataFromFiles(array $files)
    {
        $return = array();

        foreach ($files as $file) {
            // Get some of the particulars for this file
            $parts = explode('/', $file);

            // Check for custom first
            $custom = $parts[0] === 'custom';
            if ($custom) {
                $module = $parts[2];
                $client = $parts[4];
                $view = $parts[6];
            } else {
                $module = $parts[1];
                $client = $parts[3];
                $view = $parts[5];
            }

            // Only work on non-BWC modules
            if ($this->isSidecarCompanyModule($module)) {
                // Get the view defs now
                $viewdefs = $this->getViewDefsFromFile($file);

                // If the viewdefs are properly formatted, get them
                if (isset($viewdefs[$module][$client]['view'][$view])) {
                    $return[] = array(
                        'file' => $file,
                        'module' => $module,
                        'client' => $client,
                        'custom' => $custom,
                        'defs' => $viewdefs[$module][$client]['view'][$view],
                    );
                }
            }
        }

        return $return;
    }

    /**
     * Checks if a module is a sidecar Company type module
     * @param string $module The module name
     * @return boolean
     */
    protected function isSidecarCompanyModule($module)
    {
        if (!isModuleBWC($module)) {
            $bean = BeanFactory::newBean($module);
            if ($bean instanceof Company) {
                return true;
            }
        }

        return false;
    }

    /**
     * Includes a viewdef files and returns its data
     * @param string $file The file to include
     * @return array
     */
    protected function getViewDefsFromFile($file)
    {
        $viewdefs = array();
        require $file;
        return $viewdefs;
    }

    /**
     * Saves viewdefs to file
     *
     * @param array $defs
     * @param string $module
     * @param string $file
     * @param string $view
     * @param string $platform
     */
    public function saveChanges($defs, $file, $module, $view, $platform)
    {
        write_array_to_file("viewdefs['$module']['$platform']['view']['$view']", $defs, $file);
    }

    /**
     * Saves a viewdef string to a file, used for package viewdef modification
     * @param string $file The name of the file to save
     * @param string $data The content to save
     * @return int
     */
    public function savePackageFile($file, $data)
    {
        return file_put_contents($file, $data);
    }
}
