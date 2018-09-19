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
require_once 'modules/ModuleBuilder/parsers/MetaDataFiles.php';
require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarSubpanelMetaDataUpgrader.php';

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

/**
 * Handles migration of wireless and portal metadata for pre-6.6 modules into 6.6+
 * formats. Looks for the following metadata to migrate and remove legacy versions of:
 *  - custom, history and working portal module metadata
 *  - custom, history and working wireless metadata
 *  - base, custom, history and working for deployed and undeployed custom modules wireless metadata
 *
 * Also looks for the following metadata to remove legacy versions of:
 *  - All OOTB module wireless metadata (will be replaced on upgrade)
 *  - All SugarObject templates wireless metadata (will be replaced on upgrade)
 *
 * This upgrader should be the last of the upgraders run as it relies on files that
 * are new to 6.6. As part of this upgrader will handle setting of files to be
 * removed, which will be handled by
 * ../build/scripts_for_patch/files_to_remove/UpgradeRemoval66x.php
 */
class SidecarMetaDataUpgrader
{
    /**
     * Files to be removed by the upgrader after handling the processing of this
     * script
     *
     * @var array
     */
    protected static $filesForRemoval = array();

    /**
     * Listing of modules that need to be upgraded
     *
     * @var array
     */
    protected $upgradeModules = array();

    /**
     * The list of paths for both portal and wireless viewdefs under the legacy
     * system.
     *
     * @var array
     */
    protected $legacyFilePaths = array(
        'portal' => array(
            'custom'  => 'custom/portal/',
            'history' => 'custom/portal/',
            'working' => 'custom/working/portal/',
        ),
        'wireless' => array(
            'custom'  => 'custom/',
            'history' => 'custom/history/',
            'working' => 'custom/working/',
        ),
        'base'        => array(
            'custom'  => 'custom/',
            'working' => 'custom/working/',
            'history' => 'custom/history/',
        ),
    );

    protected $sidecarFilePaths = array(
        'portal' => array(
            'custom'  => 'custom/',
            'history' => 'custom/history/',
            'working' => 'custom/working/',
        ),
    );

    /**
     * Specific module to upgrade
     * @var string
     */
    protected $module = "";

    /**
     * Maps of old metadata file names
     *
     * @var array
     */
    protected $legacyMetaDataFileNames = array(
        'wireless' => array(
            MB_WIRELESSEDITVIEW       => 'wireless.editviewdefs' ,
            MB_WIRELESSDETAILVIEW     => 'wireless.detailviewdefs' ,
            MB_WIRELESSLISTVIEW       => 'wireless.listviewdefs' ,
            MB_WIRELESSBASICSEARCH    => 'wireless.searchdefs' ,
            // Advanced is unneeded since it shares with basic
        ),
        'portal' => array(
            MB_PORTALLISTVIEW         => 'listviewdefs',
            MB_PORTALSEARCHVIEW       => 'searchformdefs',
            MB_EDITVIEW               => 'editviewdefs',
            MB_DETAILVIEW             => 'detailviewdefs',
            MB_PORTALEDITVIEW         => 'edit',
            MB_PORTALDETAILVIEW       => 'detail',
        ),
        'base' => array(
            MB_LISTVIEW               => 'listviewdefs',
            MB_EDITVIEW               => 'editviewdefs',
            MB_DETAILVIEW             => 'detailviewdefs',
            MB_SEARCHVIEW             => 'searchdefs',
            MB_QUICKCREATE            => 'quickcreatedefs',
            MB_CONVERT                => 'convertdefs',
            MB_POPUPLIST              => 'popupdefs',
        )
    );

    /**
     * Listing of actual metadata files, by client
     *
     * @var array
     */
    protected $files = array();

    /**
     * List of Sidecar*MetaDataUpgrader classes that map to a view type
     *
     * @var array
     */
    protected $upgraderClassMap = array(
        'list'               => 'List',
        'edit'               => 'Grid',
        'detail'             => 'Grid',
        MB_RECORDVIEW        => 'MergeGrid',
        MB_PORTALRECORDVIEW  => 'MergeGrid',
        'search'             => 'Search',
        'filter'             => 'Filter',
        'drop'               => 'Drop',
        'subpanel'           => 'Subpanel',
        'layoutdef'          => 'Layoutdefs',
        'quickcreate'        => 'Quickcreate',
        'menu'               => 'Menu',
        'convert'            => 'LeadConvert',
        'popuplist'          => 'SelectionList',
    );

    /**
     * List of failed upgrades
     *
     * @var array
     */
    protected $failures = array();

    /**
     * Flag to tell the upgrader to write to the log or not. On by default. Can
     * be toggled using {@see toggleWriteToLog()}
     *
     * @var bool
     */
    protected $writeToLog = true;

    /**
     * Extensions data
     * @var array
     */
    public $extensions = array();

    /**
     * Flag that is used in the upgrader to let the process know that this is an
     * installation process and not a typical upgrade. This is used in getting the
     * proper name of the module for upgrading pre-6.6 mobile metadata to 6.6+
     * during installation of a package.
     * 
     * @var boolean
     */
    public $fromInstallation = false;

    const UPGRADE_BASE = 1;
    const UPGRADE_PORTAL = 2;
    const UPGRADE_MOBILE = 4;
    const UPGRADE_SUBPANEL = 8;
    const UPGRADE_ALL = 15;
    protected $upgradeCategories = self::UPGRADE_ALL;

    public function setUpgradeCategories($categories)
    {
        $this->upgradeCategories = $categories;
    }

    /**
     * Sets the list of files that need to be upgraded. Will look in directories
     * contained in $legacyFilePaths and will also attempt to identify custom
     * modules that are found within modules/
     */
    public function setFilesToUpgrade()
    {
        if ($this->upgradeCategories & self::UPGRADE_BASE) {
            $this->setBaseFilesToUpgrade();
        }
        if ($this->upgradeCategories & self::UPGRADE_PORTAL) {
            $this->setPortalFilesToUpgrade();
        }
        if ($this->upgradeCategories & self::UPGRADE_MOBILE) {
            $this->setMobileFilesToUpgrade();
        }
        if ($this->upgradeCategories & self::UPGRADE_SUBPANEL) {
            $this->setSubpanelFilesToUpgrade();
        }
    }

    /**
     * Sets the listing of customized portal module metadata to upgrade
     */
    public function setPortalFilesToUpgrade()
    {
        $this->setUpgradeFiles('portal');
        // we also upgrade edit/detail -> record views for 6.7
        $this->setSidecarUpgradeFiles('portal');
    }

    /**
     * Sets the listing of customized portal module metadata to upgrade
     */
    public function setBaseFilesToUpgrade()
    {
        $this->setUpgradeFiles('base');
        $this->getCustomModuleMetadata();
        $this->setUpgradeMBFiles($this->getMBModules());
        $this->setQuickCreateFiles();
        $this->setMenuFiles();
    }

    /**
     * Get the list of modules that need core files to be upgraded
     */
    public function getMBModules()
    {
        if(!empty($this->module)) {
            return array($this->module);
        }
        return array();
    }

    /**
     * Sets the listing of MB modules to upgrade
     * @param array List of MB modules to upgrade
     */
    public function setUpgradeMBFiles($modules)
    {
        foreach($modules as $module) {
            $this->logUpgradeStatus("Checking module $module for core upgrade");
            $basefiles = $this->getUpgradeableFilesInPath("modules/$module/metadata/", $module, 'base');
            $this->files = array_merge($this->files, $basefiles);
            $subfiles = $this->getUpgradeableFilesInPath("modules/$module/metadata/subpanels/", $module, 'base', null, true, true);
            $this->files = array_merge($this->files, $subfiles);
            // No need to scan portal here since MB modules are not supported for portal
            // Mobile part takes care of itself
        }
    }

    /**
     * Sets the listing of customized mobile module metadata to upgrade. Will
     * also scrape custom modules (deployed and undeployed) looking for all custom
     * modules and their respective metadata to upgrade.
     */
    public function setMobileFilesToUpgrade()
    {
        $metatype = 'wireless';
        $this->setUpgradeFiles($metatype);

        $total = $this->getCustomModuleMetadata($metatype);
        $this->logUpgradeStatus('Custom module mobile metadata done.');
        $this->logUpgradeStatus("$total custom module files fetched for conversion");
    }

    /**
     * Get Packages Deployed and Undeployed to upgrade
     * @param string $metatype - Is it portal, wireless, base?
     * @param string $customPath - Is there a special path to look into
     * @param bool $subpanels - is it a subpanel
     * @return int|void
     */
    public function getCustomModuleMetadata($metatype = 'base', $customPath = '', $subpanels = false)
    {
        $total = 0;
        // Get custom modules. We need both DEPLOYED and UNDEPLOYED
        // Undeployed will be those in packages that are NOT in builds but are
        // also in modules

        require_once 'modules/ModuleBuilder/MB/ModuleBuilder.php';
        $mb = new ModuleBuilder();

        // Set the packages and modules in place
        $mb->getPackages();

        // Set the core app module path for checking deployment
        $modulepath = 'modules/';

        // Handle module list making. We need to look for metadata in three places:
        // - modules/
        // - custom/modulebuilder/packages/<PACKAGENAMES>/modules/<MODULENAME>/metadata
        // - custom/modulebuilder/builds/<PACKAGENAMES>/SugarModules/modules/<PACKAGEKEY>_<MODULENAME>/metadata
        //
        // The first path will be handled if we don't send the packagename and deployed status
        // The second path will be handled by history types with a package name and undeployed status
        // The last path will be handdled by base types with a package name and undeployed status
        //
        $this->logUpgradeStatus("Beginning search for custom module $metatype metadata...");
        // Count for logging
        $total = 0;
        foreach ($mb->packages as $packagename => $package) {
            // For count logging
            $count = $deployedcount = $undeployedcount = 0;
            $buildpath = $package->getBuildDir() . '/SugarModules/modules/';
            foreach ($package->modules as $module => $mbmodule) {
                if($this->module && $package->key . '_' . $module != $this->module) {
                    continue;
                }
                $appModulePath = $modulepath . $package->key . '_' . $module;
                $mbbModulePath = $buildpath . $package->key . '_' . $module;
                $packagePath   = $package->getPackageDir() . '/modules/' . $module;
                $deployed = file_exists($appModulePath) && file_exists($mbbModulePath);

                // For deployed modules we need to get
                if ($deployed) {
                    // Reset the module name to the key_module name format
                    $modulename = $package->key . '_' . $module;

                    // Get the metadata directory
                    $metadatadir = "$appModulePath/metadata/{$customPath}";

                    // Get our upgrade files as base files since these are regular metadata
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $modulename, $metatype, 'base', null, true, $subpanels);
                    $count += count($files);
                    $deployedcount += count($files);
                    $this->files = array_merge($this->files, $files);

                    // For deployed modules we still need to handle package dir metadata
                    $metadatadir = "$mbbModulePath/metadata/{$customPath}";

                    // Get our upgrade files as undeployed base type wireless client
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $metatype, 'base', $packagename, false, $subpanels);
                    $count += count($files);
                    $deployedcount += count($files);
                    $this->files = array_merge($this->files, $files);
                } else {
                    // Handle undeployed history metadata
                    $metadatadir = "$packagePath/metadata/{$customPath}";

                    // Get our upgrade files... these are still 'base' type files
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $metatype, 'base', $packagename, false, $subpanels);
                    $count += count($files);
                    $undeployedcount += count($files);
                    $this->files = array_merge($this->files, $files);
                }
            }
            $this->logUpgradeStatus("$count upgrade files set for package $packagename: Deployed - $deployedcount, Undeployed - $undeployedcount ...");
            $total += $count;
        }
        return $total;
    }

    /**
     * Checks to see if a module is deployed
     *
     * @param sting $module The name of the module
     * @return bool
     */
    public function isModuleDeployed($module) {
        if (empty($this->deployedModules)) {
            $dirs = glob('modules/*', GLOB_ONLYDIR);
            foreach ($dirs as $dir) {
                $this->deployedModules[$dir] = $dir;
            }

            sort($this->deployedModules);
        }

        return isset($this->deployedModules[$module]);
    }

    /**
     * Processes the entire upgrade process of old to new metadata styles
     */
    public function upgrade()
    {
        include "ModuleInstall/extensions.php";
        $this->extensions = $extensions;
        // Set the upgrade file list
        $this->logUpgradeStatus('Setting upgrade file list...');
        $this->setFilesToUpgrade();

        // Traverse the files and start parsing and moving
        $this->logUpgradeStatus('Beginning metadata upgrade process...');
        foreach ($this->files as $file) {
            // Get the appropriate upgrade class name for this view type
            $class = $this->getUpgraderClass($file['viewtype']);
            if ($class) {
                if (!class_exists($class, false)) {
                    $classfile = $class . '.php';
                    require_once $classfile;
                }

                $upgrader = new $class($this, $file);

                // If the upgrade worked for this file, add it to the remove stack
                $this->logUpgradeStatus("Delegating upgrade to $class for {$file['fullpath']}...");
                if ($upgrader->upgrade()) {
                    if (!empty($upgrader->deleteOld) && !in_array($file['fullpath'], self::$filesForRemoval)) {
                        self::$filesForRemoval[] = $file['fullpath'];
                    }
                } else {
                    $this->registerFailure($file);
                }
                $this->logUpgradeStatus("{$class} :: upgrade() complete...");
            }
        }
        $this->logUpgradeStatus('Metadata upgrade process complete.');

        $this->logUpgradeStatus('Mobile/portal metadata upgrade process complete.');

        foreach ($this->getModulesList() as $module) {
            // if this is not a BWC module remove the old subpaneldefs layout
            if(!isModuleBWC($module)) {
                self::$filesForRemoval[] = "modules/{$module}/metadata/subpaneldefs.php";
            }

            // make sure team_name is not on the layout in case it doesn't exist in the module
            // (even if defined in template's layout)
            $sm = StudioModuleFactory::getStudioModule($module);
            $fields = $sm->getFields();
            if (is_array($fields) && !isset($fields['team_name'])) {
                $sm->removeFieldFromLayouts('team_name');
            }
        }

        // Add the rest of the OOTB module wireless metadata files to the stack
        $this->cleanupLegacyFiles();
    }


    /**
     * Get all modules we're going to process
     * @return array
     */
    protected function getModulesList()
    {
        if($this->module) {
            return array($this->module => $this->module);
        }
        return $GLOBALS['moduleList'];
    }

    /**
     * Add quickcreate files to the list
     */
    protected function setQuickCreateFiles()
    {
        $modules = $this->getModulesList();

        $DCActions = array();
        $actions_path = 'include/DashletContainer/Containers/DCActions.php';
        if (file_exists($actions_path)) {
            include $actions_path;
        }
        if (file_exists('custom/' . $actions_path)) {
            include 'custom/' . $actions_path;
        }

        $availableModules = $DCActions;

        $disabled = array_diff($modules, $DCActions);

        $position = 0;
        foreach ($DCActions as $module) {
            if($this->module && $this->module != $module) {
                continue;
            }
            $sidecarMetadataPath = 'modules/' . $module . '/clients/base/menus/quickcreate/quickcreate.php';
            $quickcreatedefsPath = 'modules/' . $module . '/metadata/quickcreatedefs.php';
            if (!file_exists('custom/' . $sidecarMetadataPath) && !file_exists($sidecarMetadataPath) &&
                !file_exists('custom/' . $quickcreatedefsPath) && !file_exists($quickcreatedefsPath)) {
                continue;
            }

            //[CRYS-697] If module in BWC and don't have any customization not needed to upgrade it
            $isCustomized =
                file_exists('custom/' . $sidecarMetadataPath) || file_exists('custom/' . $quickcreatedefsPath);
            if (isModuleBWC($module) && !$isCustomized) {
                continue;
            }

            $file = $this->getUpgradeFileParams(
                'modules/' . $module . '/clients/base/menus/quickcreate/quickcreate.php',
                $module, 'base', 'custom', null, true, false, true
            );
            if (empty($file)) {
                continue;
            }
            $file['isDCEnabled'] = $this->isQuickCreateVisible($sidecarMetadataPath, $module, true);
            $file['order'] = $position++;
            $this->files[] = $file;
        }

        foreach ($disabled as $module) {
            if($this->module && $this->module != $module) {
                continue;
            }
            $sidecarMetadataPath = 'modules/' . $module . '/clients/base/menus/quickcreate/quickcreate.php';
            $quickcreatedefsPath = 'modules/' . $module . '/metadata/quickcreatedefs.php';
            if (!file_exists('custom/' . $sidecarMetadataPath) && !file_exists($sidecarMetadataPath) &&
                !file_exists('custom/' . $quickcreatedefsPath) && !file_exists($quickcreatedefsPath)) {
                continue;
            }

            //[CRYS-697] If module in BWC and don't have any customization not needed to upgrade it
            $isCustomized =
                file_exists('custom/' . $sidecarMetadataPath) || file_exists('custom/' . $quickcreatedefsPath);
            if (isModuleBWC($module) && !$isCustomized) {
                continue;
            }

            $file = $this->getUpgradeFileParams(
                'modules/' . $module . '/clients/base/menus/quickcreate/quickcreate.php',
                $module, 'base', 'custom', null, true, false, true
            );
            if (empty($file)) {
                continue;
            }
            $file['isDCEnabled'] = $this->isQuickCreateVisible($sidecarMetadataPath, $module, false);
            $file['order'] = $position++;
            $this->files[] = $file;
        }
    }

    /**
     * Is the Quick Create file visible, if it's visible it should stay visible on the metadata upgrade
     *
     * @param string $quickCreateFile The Sidecar Metadata file
     * @param string $module What module are we working with
     * @param bool $default If visible is not found in the defs, it should return this value
     * @return bool
     */
    protected function isQuickCreateVisible($quickCreateFile, $module, $default = false)
    {
        $viewdefs = array();
        if (file_exists("custom/{$quickCreateFile}")) {
            include FileLoader::validateFilePath("custom/{$quickCreateFile}");
        } elseif (file_exists($quickCreateFile)) {
            include FileLoader::validateFilePath($quickCreateFile);
        } else {
            return $default;
        }

        $def = $viewdefs[$module]['base']['menu']['quickcreate'];
        return (isset($def['visible'])) ? $def['visible'] : $default;
    }


    protected function setMenuFiles()
    {
        foreach ($this->getModulesList() as $module) {
            if(file_exists("custom/modules/{$module}/Menu.php")) {
                $file = $this->getUpgradeFileParams("custom/modules/{$module}/Menu.php", $module, "base", "custom", null, true, false, true);
                if(empty($file)) continue;
                $this->files[] = $file;
            }
        }
        $this->getExtensionFiles("menus");

        // Upgrading globalControlLinks for profileactions
        if(file_exists('custom/application/Ext/GlobalLinks/links.ext.php') ||
           file_exists('custom/include/globalControlLinks.php')){
            $file['fullpath'] = 'include/globalControlLinks.php';
            $file['viewtype'] = 'menu';
            $file['basename'] = 'globalControlLinks';
            $this->files[] = $file;
        }
    }

    /**
     * Gets the list of failed upgrades
     * @return array
     */
    public function getFailures()
    {
        return $this->failures;
    }

    /**
     * Registers a failed filedata array
     *
     * @param array $file
     */
    protected function registerFailure($file)
    {
        $this->failures[] = $file;
    }

    /**
     * Gets all files for a client that need to be upgraded. This is OOTB install
     * only! Custom modules are handled differently inside the call for mobile
     * file setting.
     *
     * @param $client
     */
    protected function setUpgradeFiles($client)
    {
        // Only allow portal upgrade files for pre-6.6 instances
        $isPortal  = $client == 'portal';
        $skipFiles = !empty($this->upgrade)
                     && $this->upgrade instanceof UpgradeScript
                     && version_compare($this->upgrade->from_version, '6.6.0', '>=');
        if ($isPortal && $skipFiles) {
            $this->logUpgradeStatus("Skipping setUpgradeFiles for portal because the Sugar instance version is newer than 6.5 - version: {$this->upgrade->from_version}");
            return;
        }

        $this->logUpgradeStatus("Getting $client upgrade files ...");

        // Keep track of how many files were added, for logging
        $count = 0;

        // Hit the legacy paths list to start the ball rolling
        if (!empty($this->legacyFilePaths[$client]) && is_array($this->legacyFilePaths[$client])) {
            foreach ($this->legacyFilePaths[$client] as $type => $path) {
                // Get the modules from inside the path. If $this->module is set
                // this process will run only for that module.
                $dirs = glob($path . "modules/{$this->module}*", GLOB_ONLYDIR);
                if (!empty($dirs)) {
                    foreach ($dirs as $dirpath) {
                        // Get the module to list it in case it needs to be upgraded
                        $module = basename($dirpath);

                        // Get the metadata directory
                        $metadatadir = "$dirpath/metadata/";

                        // Get our upgrade files
                        $files = $this->getUpgradeableFilesInPath($metadatadir, $module, $client, $type);

                        // Increment the count
                        $count += count($files);

                        // Merge them
                        $this->files = array_merge($this->files, $files);
                    }
                }
            }
        }
        $this->logUpgradeStatus("$count $client upgrade files set ...");
    }

    /**
     * Set the Subpanel Upgrade Files
     */
    protected function setSubpanelFilesToUpgrade()
    {
        $this->logUpgradeStatus("Getting subpanel upgrade files ...");
        $paths = $this->legacyFilePaths['base'];
        if($this->module) {
            $glob = $this->module;
        } else {
            $glob = "*";
        }
        foreach ($paths as $type => $path) {
            $dirs = glob($path . "modules/$glob", GLOB_ONLYDIR);
            if (!empty($dirs)) {
                foreach ($dirs as $dirpath) {
                    $module = basename($dirpath);
                    $metadatadir = $dirpath . '/metadata/subpanels/';
                    $files = $this->getUpgradeableFilesInPath($metadatadir, $module, 'base', $type, null, true, true);
                    $this->files = array_merge($this->files, $files);
                }
            }
        }
        $this->getExtensionFiles("layoutdefs");
        $this->getExtensionFiles("wireless_subpanels", 'mobile');
        $this->getCustomModuleMetadata('base', 'subpanels/', true);
        $this->getCustomModuleMetadata('mobile', 'subpanels/', true);
    }

    /**
     * Add Extensions/ files to the list
     * @param string $extename Extension type
     * @param string $client the client type
     */
    protected function getExtensionFiles($extename, $client='base')
    {
        if(empty($this->extensions[$extename])) {
            return;
        }
        $extdefs = $this->extensions[$extename];
        if($this->module) {
            $glob = $this->module;
        } else {
            $glob = "*";
        }
        $dirs = glob("custom/Extension/modules/$glob/Ext/{$extdefs['extdir']}", GLOB_ONLYDIR);
        if(empty($dirs)) {
            return;
        }
        foreach($dirs as $dir) {
            $comps = explode('/', $dir);
            $module = $comps[3];
            $files = $this->getUpgradeableFilesInPath($dir."/", $module, $client, 'custom', null, true, true);
            $this->files = array_merge($this->files, $files);
        }
    }

    /**
     * Gets all sidecar views that need to be upgraded
     *
     * @param $client
     */
    protected function setSidecarUpgradeFiles($client)
    {
        $this->logUpgradeStatus("Getting $client sidecar upgrade files ...");

        // Keep track of how many files were added, for logging
        $count = 0;

        // Hit the legacy paths list to start the ball rolling
        if (!empty($this->sidecarFilePaths[$client]) && is_array($this->sidecarFilePaths[$client])) {
            foreach ($this->sidecarFilePaths[$client] as $type => $path) {
                // Get the modules from inside the path
                $dirs = glob($path . 'modules/*', GLOB_ONLYDIR);
                if (!empty($dirs)) {
                    foreach ($dirs as $dirpath) {
                        // Get the module to list it in case it needs to be upgraded
                        $module = basename($dirpath);

                        // Get the metadata directory
                        $metadatadir = "$dirpath/clients/$client/views";
                        $views = glob("$metadatadir/*", GLOB_ONLYDIR);
                        if(empty($views)) continue;

                        foreach($views as $view) {
                            $filename = basename($view);
                            if(!in_array($filename, $this->legacyMetaDataFileNames[$client])) {
                                continue;
                            }
                            $files = glob("$view/{$filename}.php*");
                            if(!empty($files)) {
                                foreach ($files as $file) {
                                    if (($data = $this->getUpgradeFileParams($file, $module, $client, $type, null, true, true)) !== false) {
                                        $this->files[] = $data;
                                    }
                                }

                                // Increment the count
                                $count += count($files);
                            }
                        }
                    }
                }
            }
        }
        $this->logUpgradeStatus("$count $client sidecar upgrade files set ...");
    }

    /**
     * Gets all metadata files that need to be upgraded for a module
     *
     * @param string $path    The path to scan for metadata files
     * @param string $module  The module name, used for indexing
     * @param string $client  The client, also used for indexing
     * @param string $type    The type (custom, history, working, base)
     * @param string $package The name of the package for this module if custom
     * @param boolean $deployed Marker to determine if a custom module is deployed or not
     * @param boolean $subpanel Is there subpanel files in this page
     * @return array
     */
    public function getUpgradeableFilesInPath($path, $module, $client, $type = 'base', $package = null, $deployed = true, $subpanel = false)
    {
        $this->logUpgradeStatus("Scanning $path for module $module client $client type $type");
        $return = array();
        if (file_exists($path)) {
            // The second * is to pick up history files
            $files = glob($path . '*.php*');

            // And if we have any, match them against what we are looking for
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (($data = $this->getUpgradeFileParams($file, $module, $client, $type, $package, $deployed, false, $subpanel)) !== false) {
                        $return[] = $data;
                    }
                }
            }
        }

        return $return;
    }

    /**
     * Adds a module name to the list of upgradeable modules, for reporting
     *
     * @param string $module The module name
     */
    protected function addUpgradeModule($module)
    {
        if (empty($this->upgradeModules[$module])) {
            $this->upgradeModules[$module] = $module;
        }
    }

    /**
     * Gets a view type from a filename
     *
     * @param string $filename The name of the file to get the view type from
     * @return string The target view type that the upgrade will produce
     */
    protected function getViewTypeFromFilename($filename, $client, $type, $fullname)
    {
        if(strpos($fullname, '/Ext/Layoutdefs/') !== false || strpos($fullname, '/Ext/WirelessLayoutdefs/') !== false) {
            return 'layoutdef';
        }

        if(strpos($fullname, '/Ext/Menus/') !== false || $filename == 'Menu') {
            return 'menu';
        }

        if (strpos($filename, 'For') !== false || strpos($filename, 'default') !== false || strpos($fullname, "metadata/subpanels/") !== false) {
            return 'subpanel';
        }

        if (strpos($filename, 'list') !== false) {
            return 'list';
        }

        if (strpos($filename, 'quickcreate') !== false) {
            return 'quickcreate';
        }

        if (strpos($filename, 'convert') !== false) {
            return 'convert';
        }

        if (strpos($filename, 'popup') !== false) {
            return 'popuplist';
        }

        if (strpos($filename, 'search') !== false) {
            if($client == 'base') {
                return 'filter';
            }
            return 'search';
        }

        if (strpos($filename, 'edit') !== false) {
            $viewtype = 'edit';
        }
        if (strpos($filename, 'detail') !== false) {
            $viewtype = 'detail';
        }

        if(!empty($viewtype)) {
            // mobile/wireless keep their views
            if($client == 'mobile' || $client == 'wireless') {
                return $viewtype;
            }
            // History views get dropped from edit/detail merge
            if($type == 'history') {
                return 'drop';
            }

            return $client == 'portal'?MB_PORTALRECORDVIEW:MB_RECORDVIEW;
        }

        return '';
    }

    /**
     * Gets the class name for the upgrader that will carry out the upgrade
     *
     * @param string $viewtype The view type (list, edit, detail)
     * @return string
     */
    public function getUpgraderClass($viewtype)
    {
        if (isset($this->upgraderClassMap[$viewtype])) {
            return 'Sidecar' . $this->upgraderClassMap[$viewtype] . 'MetaDataUpgrader';
        }

        return false;
    }

    /**
     * Adds to the stack of files for removal all wireless metadata that is
     * currently living in OOTB modules and in all of the SugarObject templates
     * metadata directories.
     */
    protected function cleanupLegacyFiles()
    {
        // In addition to all of the files we already worked on, we need to include
        // the OOTB wireless metadata files that fit the bill.
        $moduledirs = glob("modules/{$this->module}*", GLOB_ONLYDIR);
        foreach ($moduledirs as $moduledir) {
            $files = glob("$moduledir/metadata/*.php");
            foreach ($files as $filepath) {
                $filename = basename($filepath);

                // Handle history files and such
                if (is_numeric(substr($filename, -4))) {
                    $filename = substr($filename, 0, strpos('.php', $filename));
                } else {
                    $filename = basename($filename, '.php');
                }

                // If this file name is an upgrade file and it hasn't been stacked, stack it
                if (!empty($this->legacyMetaDataFileNames['wireless']) && in_array($filename, $this->legacyMetaDataFileNames['wireless']) && !in_array($filepath, self::$filesForRemoval)) {
                    self::$filesForRemoval[] = $filepath;
                }
            }
        }

        // And lastly we need to handle SugarObject Templates
        $path = 'include/SugarObjects/templates/';
        $SugarObjectsPaths = glob($path . '*', GLOB_ONLYDIR);
        foreach ($SugarObjectsPaths as $objPath) {
            $path = "$objPath/metadata/";
            $files = glob($path . '*.php');
            foreach ($files as $file) {
                $filename = basename($file, '.php');

                // If this file name is an upgrade file and it hasn't been stacked, stack it
                if (!empty($this->legacyMetaDataFileNames['wireless']) && in_array($filename, $this->legacyMetaDataFileNames['wireless']) && !in_array($file, self::$filesForRemoval)) {
                    self::$filesForRemoval[] = $file;
                }
            }
        }
    }

    /**
     * Gets a listing of all files that will need to be removed as part of the
     * metadata upgrade to 6.6
     *
     * @static
     * @return array
     */
    public static function getFilesForRemoval()
    {
        return self::$filesForRemoval;
    }

    /**
     * Gets the listing of files that are to be upgraded
     *
     * @return array
     */
    public function getFilesForUpgrade()
    {
        return $this->files;
    }

    /**
     * Gets the count of the files that are to be upgraded
     *
     * @return int
     */
    public function getCountOfFilesForUpgrade()
    {
        return count($this->files);
    }

    /**
     * Logs a message to the upgrade wizard if logging is turned on
     *
     * @param $message
     */
    public function logUpgradeStatus($message)
    {
        if ($this->writeToLog) {
            if (!function_exists('logThis')) {
                require_once 'modules/UpgradeWizard/uw_utils.php';
            }

            logThis("Sidecar Upgrade: $message");
        }
    }

    /**
     * Toggles the writeToLog flag
     */
    public function toggleWriteToLog()
    {
        $this->writeToLog = !$this->writeToLog;
    }

    /**
     * Gets the current value of the log write status
     *
     * @return bool
     */
    public function getWriteToLogStatus()
    {
        return $this->writeToLog;
    }

    /**
     * Gets the file data array needed for the upgraders to process the upgrade
     *
     * @param string $file
     * @param string $module
     * @param string $client
     * @param string $type
     * @param string $package
     * @param bool $deployed
     * @param bool $sidecar Is this a sidecar view?
     * @param bool $subpanels Is this a subpanel view
     * @return array Array of file params if found, false otherwise
     */
    public function getUpgradeFileParams($file, $module, $client, $type = 'base', $package = null, $deployed = true, $sidecar = false, $subpanels = false)
    {
        $this->logUpgradeStatus("Candidate for upgrade: $file");
        // Timestamp for history files
        $timestamp = null;

        // Handle history file handling different
        $history = is_numeric(substr($file, -4));

        // In the case of undeployed modules, type may be set to base
        // If it is, and there is a history file, set type to history
        // This is primarily for saving new defs using the MetaDataFiles
        // class to get the correct name of the metadata file
        if ($history && !$deployed && $type == 'base') {
            $type = 'history';
        }

        // if the module is not among active, not upgrading it for now, but letting
        // undeployed get through to the next step of validation
        if (empty($GLOBALS['beanList'][$module]) && $client != 'wireless' && $deployed) {
            $this->logUpgradeStatus("Not upgrading $file: Module $module is deployed but not in the module list");
            return false;
        }

        // If this is an undeployed module and a history file, stop. We only
        // upgrade history files for deployed modules.
        if (!$deployed && $history) {
            $this->logUpgradeStatus("Not upgrading $file: This $module module is not deployed and this file is a history file");
            return false;
        }

        if ($client == 'base' && isModuleBWC($module) && !$subpanels) {
            // if the module is in BWC, do not upgrade its views in base client
            $this->logUpgradeStatus("Not upgrading $file: BWC module");
            return false;
        }

        // Only hit history files for history types with a timestamp
        // Unless we are looking at undeployed modules
        if (($history && $type != 'history') || (!$history && $type == 'history') && $deployed) {
            $this->logUpgradeStatus("Not upgrading $file: wrong history format");
            return false;
        }

        if ($history) {
            $parts = explode(':', str_replace('.php_', ':', $file));
            $filename  = basename($parts[0]);
            $timestamp = $parts[1];
        } else {
            $filename = basename($file, '.php');
        }

        if ($subpanels || (!empty($this->legacyMetaDataFileNames[$client]) && in_array($filename, $this->legacyMetaDataFileNames[$client]))) {
            // Success! We have a full file path. Add this module to the stack
            $this->addUpgradeModule($module);
            $viewtype = $this->getViewTypeFromFilename($filename, $client, $type, $file);

            if ($viewtype == 'layoutdef') {
                $this->upgradeRelatedModuleSubpanel($filename);
            }
            return array(
                'client'    => $client,
                'module'    => $module,
                'type'      => $type,
                'basename'  => $filename,
                'timestamp' => $timestamp,
                'fullpath'  => $file,
                'package'   => $package,
                'deployed'  => $deployed,
                'sidecar'   => $sidecar,
                'viewtype'  => $viewtype,
            );
        }
        $this->logUpgradeStatus("Not upgrading $file: no file name for $filename");

        return false;
    }

    /**
     * If you're upgrading a subpanel layout, check to see if the supporting module's subpanel def also needs to be
     * upgraded to match.  This is needed if you're upgrading a BWC module and there are custom subpanel definitions
     * for another module that is also still in BWC.
     *
     * @param $basename filename of BWC layout override (looks like _overrideQuote_subpanel_documents.php)
     */
    public function upgradeRelatedModuleSubpanel($basename)
    {
        //pull the module out of the override file
        $parts = explode('_', $basename);
        $module = ucfirst($parts[count($parts) - 1]);

        //set the path to look in
        $path = 'custom/modules/' . $module . '/metadata/subpanels/';

        //look for files and add any candidates to the internal file array
        $files = $this->getUpgradeableFilesInPath($path, $module, 'base', 'base', null, true, true);
        $this->files = array_merge($this->files, $files);
    }

    /**
     * Set specific module to upgrade
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }
}
