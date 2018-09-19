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
require_once 'modules/ModuleBuilder/parsers/constants.php';

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

class MetaDataFiles
{
    /**
     * Constants for this class, used for pathing metadata files
     */
    const PATHBASE    = '';
    const PATHCUSTOM  = 'custom/';
    const PATHWORKING = 'custom/working/';
    const PATHHISTORY = 'custom/history/';

    /**
     * Constant for component types... in our case, layouts and views
     */
    const COMPONENTVIEW   = 'view';
    const COMPONENTLAYOUT = 'layout';

    /**
     * Path prefixes for metadata files
     *
     * @var array
     * @access public
     * @static
     */
    public static $paths = array(
        MB_BASEMETADATALOCATION    => self::PATHBASE,
        MB_CUSTOMMETADATALOCATION  => self::PATHCUSTOM,
        MB_WORKINGMETADATALOCATION => self::PATHWORKING,
        MB_HISTORYMETADATALOCATION => self::PATHHISTORY,
    );

    /**
     * The types of metadata files that could be loaded and their directory
     * locations inside of the metadata directory
     *
     * @var array
     * @access public
     * @static
     */
    public static $clients = array(
        'base'    => 'base',
        'portal'  => 'portal',
        'mobile'  => 'mobile',
    );

    /**
     * Listing of clients as they relate to their respective views
     *
     * @var array
     * @access public
     * @static
     */
    public static $clientsByView = array(
        MB_WIRELESSDETAILVIEW => MB_WIRELESS,
        MB_WIRELESSEDITVIEW => MB_WIRELESS,
        MB_WIRELESSLISTVIEW => MB_WIRELESS,
        MB_WIRELESSADVANCEDSEARCH => MB_WIRELESS,
        MB_WIRELESSBASICSEARCH => MB_WIRELESS,
        MB_PORTALEDITVIEW => MB_PORTAL,
        MB_PORTALDETAILVIEW => MB_PORTAL,
        MB_PORTALRECORDVIEW => MB_PORTAL,
        MB_PORTALLISTVIEW => MB_PORTAL,
        MB_PORTALSEARCHVIEW => MB_PORTAL,
    );

    /**
     * Names of the files themselves
     *
     * @var array
     * @access public
     * @static
     */
    public static $names = array(
        MB_DASHLETSEARCH          => 'dashletviewdefs',
        MB_DASHLET                => 'dashletviewdefs',
        MB_POPUPSEARCH            => 'popupdefs',
        MB_POPUPLIST              => 'popupdefs',
        MB_LISTVIEW               => 'listviewdefs' ,
        MB_SIDECARLISTVIEW        => 'list' ,
        MB_BASICSEARCH            => 'searchdefs' ,
        MB_ADVANCEDSEARCH         => 'searchdefs' ,
        MB_EDITVIEW               => 'editviewdefs' ,
        MB_DETAILVIEW             => 'detailviewdefs' ,
        MB_QUICKCREATE            => 'quickcreatedefs',
        MB_RECORDVIEW             => 'record',
        MB_SIDECARPOPUPVIEW       => 'selection-list',
        MB_SIDECARDUPECHECKVIEW   => 'dupecheck-list',
        MB_WIRELESSEDITVIEW       => 'edit' ,
        MB_WIRELESSDETAILVIEW     => 'detail' ,
        MB_WIRELESSLISTVIEW       => 'list' ,
        MB_WIRELESSBASICSEARCH    => 'search' ,
        MB_WIRELESSADVANCEDSEARCH => 'search' ,
        MB_PORTALEDITVIEW         => 'edit',
        MB_PORTALDETAILVIEW       => 'detail',
        MB_PORTALRECORDVIEW       => 'record',
        MB_PORTALLISTVIEW         => 'list',
        MB_PORTALSEARCHVIEW       => 'search',
        MB_FILTERVIEW             => 'default',
        MB_BWCFILTERVIEW          => 'SearchFields',
        MB_SIDECARQUOTEDATAGROUPLIST => 'quote-data-group-list',
    );

    /**
     * List of metadata def array vars
     *
     * @static
     * @access public
     * @var array
     */
    public static $viewDefVars = array(
        MB_EDITVIEW    => 'EditView' ,
        MB_DETAILVIEW  => 'DetailView' ,
        MB_QUICKCREATE => 'QuickCreate',
        MB_RECORDVIEW  => array('base', 'view', 'record'),

        MB_WIRELESSEDITVIEW => array('mobile','view','edit'),
        MB_WIRELESSDETAILVIEW => array('mobile','view','detail'),
        MB_PORTALEDITVIEW => array('portal','view','edit'),
        MB_PORTALDETAILVIEW => array('portal','view','detail'),
        MB_PORTALRECORDVIEW => array('portal','view','record'),

    );

    /**
     * Listing of components, used in pathing
     * @var array
     */
    public static $components = array(
        self::COMPONENTVIEW   => self::COMPONENTVIEW,
        self::COMPONENTLAYOUT => self::COMPONENTLAYOUT,
    );

    /**
     * The path inside the $client directories to the views
     *
     * @var string
     * @access public
     * @static
     */
    public static $viewsPath = 'views/';

    /**
     * Listing of module name placeholders in SugarObject templates metadata
     *
     * @var array
     * @static
     */
    public static $moduleNamePlaceholders = array(
        '<object_name>', '<_object_name>', '<OBJECT_NAME>',
        '<module_name>', '<_module_name>', '<MODULE_NAME>',
    );

    /**
     * Listing of excluded client file paths. This tells {@see getClientFiles} not
     * to included files in these paths when getting the list of client files.
     *
     * The structure of this array should be
     *  - path/to/type/dir => array(files)
     *
     * At present, this will only exclude PHP files.
     *
     * @var array
     */
    public static $excludedClientFilePaths = array(
        'include/SugarObjects/templates/basic/clients/base/views/' => array(
            'edit',
            'detail',
        ),
    );

    /**
     * The compiled list of excluded client files. This is based off
     * excludedClientFilePaths.
     *
     * @var array
     */
    public static $excludedClientFiles = array();

    /**
     * Gets the file base names array
     *
     * @static
     * @return array
     */
    public static function getNames()
    {
        return self::$names;
    }

    /**
     * Gets the file/variable name for a given view
     *
     * @param  string $name The name of the view to get the variable/file name for
     * @return string The name of the file/variable
     */
    public static function getName($name)
    {
        return empty(self::$names[$name]) ? null : self::$names[$name];
    }

    /**
     * Gets the clients array
     *
     * @static
     * @return array
     */
    public static function getClients()
    {
        return self::$clients;
    }

    /**
     * Gets a particular client by name. $client should map to an index of the
     * clients array.
     *
     * @static
     * @param  string $client The client to get
     * @return string
     */
    public static function getClient($client)
    {
        return empty(self::$clients[$client]) ? '' : self::$clients[$client];
    }

    /**
     * Gets a view client for a known view
     *
     * @static
     * @param  string $view The view to get the client for
     * @return string
     */
    public static function getClientByView($view)
    {
        return empty($view) || empty(self::$clientsByView[$view]) ? '' : self::$clientsByView[$view];
    }

    /**
     * Gets the file paths array
     *
     * @static
     * @return array
     */
    public static function getPaths()
    {
        return self::$paths;
    }

    /**
     * Gets a single file path by type
     *
     * @static
     * @return string
     */
    public static function getPath($path)
    {
        return empty(self::$paths[$path]) ? '' : self::$paths[$path];
    }

    /**
     * Gets the view type of a client based on the requested view
     *
     * @static
     * @param  string $view The requested view
     * @return string
     */
    public static function getViewClient($view)
    {
        if (!empty($view)) {
            if (stripos($view, 'portal') !== false) {
                return 'portal';
            }

            if (stripos($view, 'wireless') !== false || stripos($view, 'mobile') !== false) {
                return 'mobile';
            }

            return 'base';
        }

        return '';
    }

    /**
     * Gets the listing of SugarObject template placeholders
     *
     * @static
     * @return array
     */
    public static function getModuleNamePlaceholders()
    {
        return self::$moduleNamePlaceholders;
    }

    /**
     * helper to give us a parameterized path to create viewdefs for saving to file
     * @param  string | array $path (path of keys to use for array)
     * @param  mixed          $data the data to place at that path
     * @return array          the data in the correct path
     */
    public static function mapPathToArray($path, $data)
    {
        if (!is_array($path)) {
            return array($path => $data);
        }

        $arr = $data;
        while ($key = array_pop($path)) {
            $arr = array($key => $arr);
        }

        return $arr;
    }

    /**
     * helper to give us a parameterized path find our data from our viewdefs
     * @param  string | array $path (path of keys to use for array)
     * @param  mixed          $arr  the array to search for the path
     * @return array|         null the data in the correct path or null if a key isn't found.
     */
    public static function mapArrayToPath($path, $arr)
    {
        if (!is_array($arr)) {
            return null;
        }

        if (!is_array($path)) {
            return (isset($arr[$path]) ? $arr[$path] : null);
        }

        // traverse the array for our path
        $out = &$arr;
        foreach ($path as $key) {
            if (!isset($out[$key])) {
                return null;
            }

            $out = $out[$key];
        }

        return $out;
    }



    /**
     * Gets the list of view def array variable names
     *
     * @static
     * @return array
     */
    public static function getViewDefVars()
    {
        return self::$viewDefVars;
    }

    /**
     * Gets a single view def variable name
     *
     * This checks the def vars array first then the file name arrays. This
     * fallback allows for the use of the more standard naming for sidecar stuff
     * without having to redefine a bunch of vars that are the exact same as their
     * filename counterparts
     *
     * @static
     * @param  string $view The name of the view to get the def var for
     * @return string The def variable name
     */
    public static function getViewDefVar($view)
    {
        // Try the view def var array first
        if (isset(self::$viewDefVars[$view])) {
            return self::$viewDefVars[$view];
        }

        // try the file name array second
        return self::getName($view);
    }

    public static function setViewDefVar($view, $defVar)
    {
    }

    /**
     * Gets a deployed metadata filename. This is generally called from a
     * DeployedMetaDataImplementation instance.
     *
     * @static
     * @param  string $view   The requested view type
     * @param  string $module The module for this metadata file
     * @param  string $type   The type of metadata file location (custom, working, etc)
     * @param  string $client The client type for this file
     * @param  array $params  Optional metadata file parameters
     * @return string
     */
    public static function getDeployedFileName(
        $view,
        $module,
        $type = MB_CUSTOMMETADATALOCATION,
        $client = '',
        array $params = array()
    ) {
        $file = self::getFile($view, $module, array_merge($params, array(
            'client' => $client,
            'location' => $type,
        )));

        return self::getFilePath($file);
    }

    /**
     * Gets an undeployed metadata filename. This is generally called from an
     * UndeployedMetaDataImplementation instance.
     *
     * @static
     * @param  string $view        The requested view
     * @param  string $module      The module for this metadata file
     * @param  string $packageName The package for this metadata file
     * @param  string $type        The type of metadata file to get (custom, working, etc)
     * @param  string $client      The client type for this file
     * @return string
     */
    public static function getUndeployedFileName($view, $module, $packageName, $type = MB_BASEMETADATALOCATION, $client = '')
    {
        $file = self::getFile($view, $module, array(
            'package' => $packageName,
            'location' => $type,
            'client' => $client,
        ));

        return self::getFilePath($file);
    }

    public static function getFile($view, $module, array $params = array())
    {
        $file = new MetaDataFile($view, $module);

        if (!empty($params['client'])) {
            $file = new MetaDataFileSidecar($file, $params['client']);
        } else {
            $file = new MetaDataFileBwc($file);
        }

        if (!empty($params['location'])) {
            if (!empty($params['package'])) {
                $file = new MetaDataFileUndeployed($file, $params['package'], $params['location']);
            } else {
                $file = new MetaDataFileDeployed($file, $params['location']);

                if (!empty($params['role'])) {
                    $file = new MetaDataFileRoleDependent($file, $params['role']);
                }
            }
        }

        return $file;
    }

    public static function getFilePath(MetaDataFileInterface $file)
    {
        return implode('/', $file->getPath()) . '.php';
    }

    /**
     * Gets a $deftype metadata file for a given module
     *
     * @static
     * @param  string      $module    The name of the module to get metadata for
     * @param  string      $deftype   The def type to get (list, detail, edit, search)
     * @param  string      $path      The path to the metadata (base path, custom path, working path, history path)
     * @param  string      $client    The client making this request
     * @param  string      $component Layout or view
     * @return null|string Null if the request is invalid, path if it is good
     */
    public static function getModuleFileName($module, $deftype, $path = MB_BASEMETADATALOCATION, $client = '', $component = self::COMPONENTVIEW)
    {
        $filedir = self::getModuleFileDir($module, $path);
        if ($filedir === null) {
            return null;
        }

        if ($client) {
            $metadataPath = 'clients/' . $client . '/' . $component . 's/' . $deftype . '/';
        } else {
            $metadataPath = 'metadata/';
        }

        $filename = $filedir . $metadataPath . $deftype . '.php';

        return $filename;
    }

    /**
     * Gets a metadata file path for a module from its SugarObject template type
     *
     * @static
     * @param  string $module    The name of the module to get metadata for
     * @param  string $deftype   The def type to get (list, detail, edit, search)
     * @param  string $client    The client making this request
     * @param  string $component Layout or view
     * @return string
     */
    public static function getSugarObjectFileName($module, $deftype, $client = '', $component = self::COMPONENTVIEW)
    {
        $filename =  self::getSugarObjectFileDir($module, $client, $component) . '/' . $deftype . '.php';

        return $filename;
    }

    /**
     * Gets a metadata directory for a given module and path (custom, history, etc)
     *
     * @static
     * @param string $module The name of the module to get metadata for
     * @param string $path The path to the metadata (base path, custom path, working path, history path)
     * @param string $client The client making this request
     * @param string $component Layout or view
     * @return null|string Null if the request is invalid, path if it is good
     */
    public static function getModuleFileDir($module, $path = MB_BASEMETADATALOCATION)
    {
        // Simple validation of path
        if (!isset(self::$paths[$path])) {
            return null;
        }

        // Now get to building
        $dirname = self::$paths[$path] . 'modules/' . $module . '/';

        return $dirname;
    }

    /**
     * Gets a metadata directory path for a module from its SugarObject template type
     *
     * @static
     * @param string $module The name of the module to get metadata for
     * @param string $client The client making this request
     * @param string $component Layout or view
     * @return string
     */
    public static function getSugarObjectFileDir($module, $client = '', $component = self::COMPONENTVIEW, $seed = null)
    {
        $sm = new StudioModule($module);

        $dirname = 'include/SugarObjects/templates/' . $sm->getType();
        if (!empty($client)) {
            $dirname .= '/clients/' . $client . '/' . $component . 's';
        } else {
            $dirname .= '/metadata';
        }

        return $dirname;
    }

    /**
     * Gets a metadata directory path for a module from the ext framework type
     *
     * @static
     * @param  string $module The name of the module to get metadata for
     * @param  string $client The client making this request
     * @param  string $component Layout or view
     * @return string
     */
    public static function getSugarExtensionFileDir($module = '', $client = 'base', $component = self::COMPONENTVIEW)
    {
        if (empty($module)) {
            $dirname = "custom/application/Ext/clients/{$client}/{$component}s";
        } else {
            $dirname = "custom/modules/{$module}/Ext/clients/{$client}/{$component}s";
        }

        return $dirname;
    }

    /**
     * Gets SugarObjects type metadata for a module and cleans the defs up by
     * replacing variables with correct values based on the module
     *
     * @static
     * @param SugarBean|string $module Either a been or a string name of a module
     * @param array $defs The defs associated with this module
     * @return array Cleaned up metadata
     */
    public static function getModuleMetaDataDefsWithReplacements($module, $defs)
    {
        if (!$module instanceof SugarBean) {
            // We need to preserve state on $module since we'll fall back to it if
            // there is no bean for this module. This can happen when upgrading
            // views for undeployed modules
            $mod = BeanFactory::newBean($module);

            if ($mod === null) {
                // No Bean for this $module, so use the module name
                $m = $o = $module;
            } else {
                $m = $mod->module_dir;
                $o = $mod->object_name;
            }
        } else {
            $m = $module->module_dir;
            $o = $module->object_name;
        }

        $replacements = array(
            "<object_name>"  => $m,
            "<_object_name>" => strtolower($o),
            "<OBJECT_NAME>"  => strtoupper($o),
            "<module_name>"  => $m,
            '<_module_name>' => strtolower($m),
        );

        return self::recursiveVariableReplace($defs, $replacements);
    }

    /**
     * Does deep recursive variable replacement on an array
     *
     * @TODO Consider making a MetaDataUtils class and adding this to that class
     * @static
     * @param array $source The input array to work replacements on
     * @param array $replacements An array of replacements as $find => $replace pairs
     * @return array $source array with $replacements applied to them
     */
    public static function recursiveVariableReplace($source, $replacements)
    {
        $ret = array();
        foreach ($source as $key => $val) {
            if (is_array($val)) {
                $newkey = $key;
                $val = self::recursiveVariableReplace($val, $replacements);
                $newkey = str_replace(array_keys($replacements), $replacements, $newkey);
                $ret[$newkey] = $val;
            } else {
                $newkey = $key;
                $newval = $val;
                if (is_string($val)) {
                    $newkey = str_replace(array_keys($replacements), $replacements, $newkey);
                    $newval = str_replace(array_keys($replacements), $replacements, $newval);
                }
                $ret[$newkey] = $newval;
            }
        }

        return $ret;
    }

    /**
     * @param $view
     * @return mixed
     * hack for portal to use its own constants
     */
    public static function getMBConstantForView($view, $client = "base")
    {
        // Sometimes client is set to a defined null
        if (empty($client)) {
            $client = 'base';
        }

        $map = array(
            "portal" => array(
                'record' => MB_PORTALRECORDVIEW,
                'search' => MB_PORTALSEARCHVIEW,
                'list' => MB_PORTALLISTVIEW
            ),
            'mobile' => array(
                'edit' => MB_WIRELESSEDITVIEW,
                'detail' => MB_WIRELESSDETAILVIEW,
                'list' => MB_WIRELESSLISTVIEW
            ),
            "base" => array(
                'edit' => MB_EDITVIEW,
                'detail' => MB_DETAILVIEW,
                'advanced_search' => MB_ADVANCEDSEARCH,
                'basic_search' => MB_BASICSEARCH,
                'list' => MB_LISTVIEW,
            ),
        );

        // view variable sent to the factory has changed: remove 'view' suffix
        // in case of further change
        $view = strtolower($view);
        if (substr_compare($view,'view',-4) === 0) {
            $view = substr($view,0,-4);
        }

        return isset($map[$client][$view]) ? $map[$client][$view] : $view;
    }

    /**
     * Removes module cache files. This can clear a single cache file by type or
     * all cache files for a module. Can also clear a single platform type cache
     * for a module.
     *
     * @param array $modules The modules to clear the cache for. An empty array
     *                       clears caches for all modules
     * @param string $type   The cache file to clear. An empty string clears all
     *                       cache files for a module
     * @param array $platforms A list of platforms to clear the caches for. An
     *                         empty platform list assumes all platforms.
     */
    public static function clearModuleClientCache($modules = array(), $type = '', $platforms = array())
    {
        // Clean up the module list
        if ( is_string($modules) ) {
            // They just want one module
            $modules = array($modules);
        } elseif ( count($modules) == 0 ) {
            // They want all of the modules, so get them if they are already set
            $modules = empty($GLOBALS['app_list_strings']['moduleList']) ? array() : array_keys($GLOBALS['app_list_strings']['moduleList']);
        }

        // Clean up the type
        if ( empty($type) ) {
            $type = '*';
        }

        // Clean up the platforms
        if (empty($platforms)) {
            $platforms = array('*');
        }

        // Handle it
        foreach ($modules as $module) {
            // Start at the client dir level
            $clientDirs = glob(sugar_cached('modules/'.$module.'/clients/'));
            foreach ($clientDirs as $clientDir) {
                foreach ($platforms as $platform) {
                    // Build up to the platform dir level
                    $platformDirs = glob($clientDir . $platform . '/');
                    foreach ($platformDirs as $platformDir) {
                        // Get all of the files in question
                        $cacheFiles = glob($platformDir . $type . '.php');

                        // Handle nuking the files
                        foreach ($cacheFiles as $cacheFile) {
                            unlink($cacheFile);
                        }
                        // Build up to the role dir level
                        $roleDirs = glob($platformDir . '*/');
                        foreach ($roleDirs as $roleDir) {

                            $cacheFiles = glob($roleDir . $type . '.php');

                            // Handle nuking the files
                            foreach ($cacheFiles as $cacheFile) {
                                unlink($cacheFile);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Gets all module cache files for a module. Uses the first platform in the
     * platform list to get the cache files.
     *
     * @param array $platforms The list of platforms to get a module cache for.
     *                         Uses the first member of this list as the platform.
     * @param string $type     The type of cache file to get.
     * @param string $module   The module to get the cache for.
     * @param MetaDataContextInterface|null $context Metadata context
     * @return array
     */
    public static function getModuleClientCache($platforms, $type, $module, MetaDataContextInterface $context = null)
    {
        if (empty($module)) {
            return null;
        }
        $sc = SugarConfig::getInstance();
        //No need to write the module cache for a specific context, we can't load from it anyway.
        $noCache = !empty($context);
        $noCache = $noCache || $sc->get('roleBasedViews', false);
        $clientCache = array();
        $cacheFile = sugar_cached('modules/' . $module . '/clients/' . $platforms[0] . '/' . $type . '.php');
        if ($noCache || !file_exists($cacheFile)) {
            $result = self::buildModuleClientCache($platforms, $type, $module, $context, $noCache);
            if ($noCache) {
                return $result;
            }
        }
        $clientCache[$module][$platforms[0]][$type] = array();
        require $cacheFile;

        return $clientCache[$module][$platforms[0]][$type];
    }

    /**
     * Builds up module client cache files
     *
     * @param array $platforms A list of platforms to build for. Uses the first
     *                         platform in the list as the platform.
     * @param string $type     The type of file to create the cache for.
     * @param array $modules   The module to create the cache for.
     * @param MetaDataContextInterface|null $context Metadata context
     */
    public static function buildModuleClientCache(
        $platforms,
        $type,
        $modules = array(),
        MetaDataContextInterface $context = null,
        $noCache = false
    ) {
        if ( is_string($modules) ) {
            // They just want one module
            $modules = array($modules);
        } elseif ( count($modules) == 0 ) {
            // They want all of the modules
            $modules = array_keys($GLOBALS['app_list_strings']['moduleList']);
        }
        if (!$context) {
            $context = new MetaDataContextDefault();
        }
        foreach ($modules as $module) {
            $seed = BeanFactory::newBean($module);
            $fileList = self::getClientFiles($platforms, $type, $module, $context, $seed);
            $moduleResults = self::getClientFileContents($fileList, $type, $module, $seed);

            if ($type == "view") {
                foreach ($moduleResults as $view => $defs) {
                    if (!is_array($defs) || empty($seed) || empty($seed->field_defs)) {
                        continue;
                    }
                    $meta = !empty($defs['meta']) ? $defs['meta'] : array();
                    $deps = DependencyManager::getDependenciesForView($meta, ucfirst($view) . "View", $module);
                    if (!empty($deps)) {
                        if (!isset($meta['dependencies']) || !is_array($meta['dependencies'])) {
                            $moduleResults[$view]['meta']['dependencies'] = array();
                        }
                        foreach ($deps as $dep) {
                            $moduleResults[$view]['meta']['dependencies'][] = $dep->getDefinition();
                        }
                    }
                }
            } elseif ($type == 'dependency') {
                if (!empty($seed) && !empty($seed->field_defs)) {
                    $modDeps = DependencyManager::getDependenciesForFields($seed->field_defs);
                    if (!empty($modDeps)) {
                        $moduleResults['dependencies'] = array();
                        foreach ($modDeps as $dep) {
                            $moduleResults['dependencies'][] = $dep->getDefinition();
                        }
                    }
                }
            }

            if ($noCache) {
                return $moduleResults;
            } else {
                $basePath = sugar_cached('modules/'.$module.'/clients/'.$platforms[0]);
                if ($context != null && $context->getHash()) {
                    $basePath .= '/' . $context->getHash();
                }
                sugar_mkdir($basePath,null,true);
    
                $output = "<?php\n\$clientCache['".$module."']['".$platforms[0]."']['".$type."'] = ".var_export($moduleResults,true).";\n\n";
                sugar_file_put_contents_atomic($basePath.'/'.$type.'.php', $output);
            }
        }
    }


    /**
     * Get a list of client files:
     * 1) Get a list of directories;
     * 2) Get the files in these directories.
     *
     * @param array  $platforms A list of platforms to build for. Uses the first
     *                          platform in the list as the platform.
     * @param string $type      The type of file to retrieve for building metadata.
     * @param string $module    The module to retrieve for building metadata.
     * @param MetaDataContextInterface|null $context Metadata context
     *
     * @return array
     */
    public static function getClientFiles( $platforms, $type, $module = null, MetaDataContextInterface $context = null, $bean = null)
    {
        $checkPaths = array();

        // First, build a list of paths to check
        if ($module == '') {
            foreach ($platforms as $platform) {
                // These are sorted in order of priority.
                // No templates for the non-module stuff
                $checkPaths['custom/clients/'.$platform.'/'.$type.'s'] = array('platform'=>$platform,'template'=>false);
                $checkPaths['clients/'.$platform.'/'.$type.'s'] = array('platform'=>$platform,'template'=>false);
                $checkPaths[self::getSugarExtensionFileDir('', $platform, $type)] = array('platform' => $platform, 'template' => false);
            }
        } else {
            foreach ($platforms as $platform) {
                // These are sorted in order of priority.
                // The template flag is if that file needs to be "built" by the metadata loader so it
                // is no longer a template file, but a real file.
                // Use the module_dir if available to support submodules
                if (!$bean) {
                    $bean = BeanFactory::newBean($module);
                }
                $module_path  = isset($bean->module_dir) ? $bean->module_dir : $module;
                $checkPaths['custom/modules/'.$module_path.'/clients/'.$platform.'/'.$type.'s'] = array('platform'=>$platform,'template'=>false);
                $checkPaths['modules/'.$module_path.'/clients/'.$platform.'/'.$type.'s'] = array('platform'=>$platform,'template'=>false);
                $baseTemplateDir = 'include/SugarObjects/templates/basic/clients/'.$platform.'/'.$type.'s';
                $nonBaseTemplateDir = self::getSugarObjectFileDir($module, $platform, $type, $bean);
                if (!empty($nonBaseTemplateDir) && $nonBaseTemplateDir != $baseTemplateDir ) {
                    $checkPaths['custom/'.$nonBaseTemplateDir] = array('platform'=>$platform,'template'=>true);
                    $checkPaths[$nonBaseTemplateDir] = array('platform'=>$platform, 'template'=>true);
                }
                $checkPaths['custom/'.$baseTemplateDir] = array('platform'=>$platform,'template'=>true);
                $checkPaths[$baseTemplateDir] = array('platform'=>$platform,'template'=>true);
                $checkPaths[self::getSugarExtensionFileDir($module, $platform, $type)] = array('platform' => $platform, 'template' => false);
            }
        }

        // Second, get a list of files in those directories, sorted by "relevance"
        $fileList = self::getClientFileList($checkPaths, $context);
        return $fileList;
    }

    /**
     * Get a list of files in the given directories.
     *
     * @param array $checkPaths A list of directories to include files.
     * @param MetaDataContextInterface|null $context Metadata context
     *
     * @return array
     */
    public static function getClientFileList($checkPaths, MetaDataContextInterface $context = null)
    {
        $fileLists = array();
        foreach ($checkPaths as $path => $pathInfo) {
            // Looks at /modules/Accounts/clients/base/views/*
            // So should pull up "record","list","preview"
            $dirsInPath = SugarAutoLoader::getDirFiles($path, true, null, true);
            $fileList = array();

            foreach ($dirsInPath as $fullSubPath) {
                $subPath = basename($fullSubPath);
                // This should find the files in each view/layout
                // So it should pull up list.js, list.php, list.hbs
                $filesInDir = SugarAutoLoader::getDirFiles($fullSubPath, false, null, true);
                foreach ($filesInDir as $fullFile) {
                    // If this file is an excluded file, skip it
                    if (self::isExcludedClientFile($fullFile)) {
                        continue;
                    }

                    $file = basename($fullFile);
                    $fileIndex = $fullFile;
                    if ( !isset($fileList[$fileIndex]) ) {
                        $fileList[$fileIndex] = array(
                            'path' => $fullFile,
                            'file' => $file,
                            'subPath' => $subPath,
                            'platform' => $pathInfo['platform'],
                            'template' => $pathInfo['template'] && substr($file, -4) == '.php',
                            'params' => self::getClientFileParams($fullFile),
                        );
                    }
                }
            }

            if ($context) {
                $fileList = self::filterClientFiles($fileList, $context);
            }
            $fileLists[] = $fileList;
        }

        return call_user_func_array('array_merge', $fileLists);
    }

    /**
     * Returns context parameters which are required for the given file to be effective
     *
     * @param $path
     * @return array
     * @todo Make this back protected after handling role based LOV is reworked
     * @see MetaDataManager::getEditableDropdownFilters()
     */
    public static function getClientFileParams($path)
    {
        if (preg_match('/\/roles\/([^\/]+)\//', $path, $matches)) {
            return array(
                'role' => $matches[1],
            );
        }

        return array();
    }
    /**
     * Get the content from the global files.
     *
     * @param array  $platform The platform for building metadata.
     * @param string $type     The type of file to retrieve for building metadata.
     * @param string $subPath  The subPath of file to retrieve for building metadata.
     *
     * @return array
     */
    public static function getGlobalFileContent($platform, $type, $subPath)
    {
        $checkPaths = array();

        //Add the global base files as the default ones for the extension files
        //Used when the corresponding files in custom module, module & template cannot be found
        //See the previously checked files in getClientFiles().
        $checkPaths['custom/clients/'.$platform.'/'.$type.'s'] = array('platform' => $platform, 'template' => false);
        $checkPaths['clients/'.$platform.'/'.$type.'s'] = array('platform' => $platform, 'template' => false);

        //Find the files in the paths
        $fileList = self::getClientFileList($checkPaths);

        //Get the content of the files to find the metadata in $subPath
        $results = array();
        foreach ($fileList as $fileIndex => $fileInfo) {
            $extension = substr($fileInfo['path'], -3);
            if ($extension == 'php') {
                $viewdefs = array();
                require $fileInfo['path'];
                if (isset($viewdefs[$platform][$type][$subPath])) {
                    $results = $viewdefs[$platform][$type][$subPath];
                    break;
                }
            }
        }
        return $results;
    }

    /**
     * Get the content of the given files to build metadata.
     *
     * @param array  $fileList A list of files to retrieve for building metadata.
     * @param string $type     The type of file to retrieve for building metadata.
     * @param string $module   The module to retrieve for building metadata.
     *
     * @return array
     */
    public static function getClientFileContents( $fileList, $type, $module = '', $bean = null)
    {
        $results = array();

        foreach ($fileList as $fileInfo) {
            $extension = substr($fileInfo['path'],-3);
            switch ($extension) {
                case '.js':
                    $subpath = $fileInfo['subPath'];
                    if (strpos($fileInfo['path'], "custom/") === 0) {
                        $subpath = "custom" . ucfirst($subpath);
                    }
                    if (isset($results[$subpath]['controller'][$fileInfo['platform']])) {
                        continue;
                    }
                    $controller = file_get_contents($fileInfo['path']);
                    $results[$subpath]['controller'][$fileInfo['platform']] = $controller;
                    break;
                case 'hbs':
                    $layoutName = substr($fileInfo['file'],0,-4);
                    if ( isset($results[$fileInfo['subPath']]['templates'][$layoutName]) ) {
                        continue;
                    }
                    $results[$fileInfo['subPath']]['templates'][$layoutName] = self::trimLicense(
                        file_get_contents($fileInfo['path'])
                    );
                    break;
                case 'php':
                    $viewdefs = array();
                    if ( isset($results[$fileInfo['subPath']]['meta']) && !strstr($fileInfo['path'], '.ext.php')) {
                        continue;
                    }
                    //When an extension file is found and NO corresponding metadata has been found so far
                    if (!empty($module) && strstr($fileInfo['path'], '.ext.php') &&
                        !isset($results[$fileInfo['subPath']]['meta'])) {
                        //need to check the global files for metadata
                        $results[$fileInfo['subPath']]['meta'] = self::getGlobalFileContent(
                            $fileInfo['platform'],
                            $type,
                            $fileInfo['subPath']
                        );
                    }
                    //Viewdefs must be maintained between files to allow for extension files that append to out of box meta.
                    if (!empty($results[$fileInfo['subPath']]['meta'])) {
                        if (empty($module)) {
                            $viewdefs[$fileInfo['platform']][$type][$fileInfo['subPath']] = $results[$fileInfo['subPath']]['meta'];
                        } else {
                            $viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']] = $results[$fileInfo['subPath']]['meta'];
                        }
                    }
                    if ($fileInfo['template']) {
                        // This is a template file, not a real one.
                        require $fileInfo['path'];
                        if (!$bean) {
                            $bean = BeanFactory::newBean($module);
                        }
                        if ( !is_a($bean,'SugarBean') ) {
                            // I'm not sure what this is, but it's not something we can template
                            continue;
                        }
                        $viewdefs = self::getModuleMetaDataDefsWithReplacements($bean, $viewdefs);
                        if ( ! isset($viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']]) ) {
                            $GLOBALS['log']->error('Could not generate a metadata file for module '.$module.', platform: '.$fileInfo['platform'].', type: '.$type);
                            continue;
                        }

                        $results[$fileInfo['subPath']]['meta'] = $viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']];
                    } else {
                        require $fileInfo['path'];
                        if($fileInfo['subPath'] != 'subpanels') {
                            $extensionName = "sidecar{$type}{$fileInfo['platform']}{$fileInfo['subPath']}";
                            $extFile = SugarAutoLoader::loadExtension($extensionName, $module);
                            if ($extFile) {
                                include FileLoader::validateFilePath($extFile);
                            }
                        }
                        if ( empty($module) ) {
                            if ( !isset($viewdefs[$fileInfo['platform']][$type][$fileInfo['subPath']]) ) {
                                $GLOBALS['log']->error('No viewdefs for type: '.$type.' viewdefs @ '.$fileInfo['path']);
                            } else {
                                $results[$fileInfo['subPath']]['meta'] = $viewdefs[$fileInfo['platform']][$type][$fileInfo['subPath']];
                            }
                        } else {
                            if ($fileInfo['subPath'] == 'header' && $type == 'menu') {
                                $viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']] =
                                    array_values(
                                        $viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']]
                                    );
                            }
                            if ( !isset($viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']]) ) {
                                $GLOBALS['log']->error('No viewdefs for module: '.$module.' viewdefs @ '.$fileInfo['path']);
                            } else {
                                if(isset($results[$fileInfo['subPath']]['meta']) && $fileInfo['subPath'] == 'subpanels') {
                                    $results[$fileInfo['subPath']]['meta'] = self::mergeSubpanels($viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']], $results[$fileInfo['subPath']]['meta']);

                                } else {
                                    // For custom modules or if there is no subpanel
                                    // layout defined and edits are made, we
                                    // need to capture the changes that all live
                                    // in one file for subpanels
                                    if($fileInfo['subPath'] == 'subpanels') {
                                        $results[$fileInfo['subPath']]['meta'] = self::mergeSubpanels($viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']], array('components' => array()));
                                    } else {
                                        $results[$fileInfo['subPath']]['meta'] = $viewdefs[$module][$fileInfo['platform']][$type][$fileInfo['subPath']];
                                    }
                                }
                            }
                        }
                        break;
                    }
            }
        }

        $results['_hash'] = md5(serialize($results));

        return $results;
    }

    /**
     * @param array $mergeDefs the defs to merge in
     * @param array $currentDefs the current defs that need the new stuff
     * @return array updated layoutdefs
     */
    public static function mergeSubpanels(array $mergeDefs, array $currentDefs)
    {
        $mergeComponents = $mergeDefs['components'];

        foreach($mergeComponents as $mergeComponent) {
            // if it is the only thing in the array its an override and it needs to be added to an existing component
            if (isset($currentDefs['components'], $mergeComponent['override_subpanel_list_view'])
                && count($mergeComponent) == 1
            ) {
                $overrideView = $mergeComponent['override_subpanel_list_view']['view'];
                $mergeContext = $mergeComponent['override_subpanel_list_view']['link'];
                foreach ($currentDefs['components'] as $key => $currentComponent) {
                    if(!empty($currentComponent['context']['link']) && $currentComponent['context']['link'] == $mergeContext) {
                        $currentDefs['components'][$key]['override_subpanel_list_view'] = $overrideView;
                        continue;
                    }
                }
            } else {
                $linkExists = false;
                if (isset($currentDefs['components'], $mergeComponent['context']['link'])) {
                    foreach ($currentDefs['components'] as $currentComponent) {
                        if ($currentComponent['context']['link'] == $mergeComponent['context']['link']) {
                            $linkExists = true;
                            break;
                        }
                    }
                }
                if(!$linkExists) {
                    $currentDefs['components'][] = $mergeComponent;
                }
            }
        }

        // Allow modules that set their subpanels dynamically to flow down to
        // the client
        if (isset($mergeDefs['dynamic'])) {
            $currentDefs['dynamic'] = $mergeDefs['dynamic'];
        }

        return $currentDefs;
    }

    /**
     * Checks if a client file path should be excluded from the list of files to
     * be fetched when getting client files for a module
     *
     * @param string $filepath Full path to the file to check
     * @return boolean
     */
    public static function isExcludedClientFile($filepath)
    {
        // If the listing of excluded client files hasn't been built yet then
        // create it
        if (empty(self::$excludedClientFiles)) {
            self::buildExcludedClientFileList();
        }

        return !empty(self::$excludedClientFiles[$filepath]);
    }

    /**
     * Builds the listing of excluded client files.
     */
    public static function buildExcludedClientFileList()
    {
        foreach (self::$excludedClientFilePaths as $basePath => $files) {
            foreach ($files as $file) {
                // Set the path as an index for we can use isset()/empty()
                // instead of in_array()
                $path = $basePath . $file . '/' . $file . '.php';
                $customPath = 'custom/' . $path;
                self::$excludedClientFiles[$path] = true;
                self::$excludedClientFiles[$customPath] = true;
            }
        }
    }

    /**
     * Adds a path => array(files) to the excluded client file path array
     *
     * @param array $pathArray Array of files keyed on a base path
     */
    public static function addExcludedClientFilePath(array $pathArray)
    {
        self::$excludedClientFilePaths = array_merge(self::$excludedClientFilePaths, $pathArray);
    }

    /**
     * Adds an excluded file to a path in the excluded client file path array
     *
     * @param string $path The path to use as the index for the excluded file array
     * @param string $file the file basename to add
     */
    public static function addExcludedClientFileToPath($path, $file)
    {
        self::$excludedClientFilePaths[$path][] = $file;
    }

    /**
     * Loads a specific client file's contents
     *
     * @param string $type The type of the client data (view/layout/field)
     * @param string $name The name of the client data (record/history/list)
     * @param string $platform The platform to search for the client data (defaults to base)
     *
     * @return array Contents of the metadata file
     */
    public static function loadSingleClientMetadata($type, $name, $platform = 'base')
    {
        $fileList = array();
        $platforms = array();
        if ($platform != 'base') {
            $platforms[] = $platform;
        }
        $platforms[] = 'base';
        foreach ($platforms as $platform) {
            $fileToCheck = "clients/$platform/{$type}s/$name/$name.php";
            $fileList['custom/'.$fileToCheck] = $platform;
            $fileList[$fileToCheck] = $platform;
        }

        foreach ($fileList as $file => $platform) {
            if (SugarAutoLoader::existing($file)) {
                require $file;
                if (isset($viewdefs[$platform][$type][$name])) {
                    return $viewdefs[$platform][$type][$name];
                }
            }
        }

        return null;
    }

    /**
     * Loads a specific client router's contents
     *
     * @param string $module   The name of the module to get metadata for
     * @param string $platform The platform to search for the client data (defaults to base)
     *
     * @return string          Contents of the metadata file
     */
    public static function loadRouterFile($module, $platform = 'base')
    {
        $routerFile = 'modules/' . $module . '/clients/' . $platform . '/routes/routes.js';
        if (file_exists($routerFile)) {
            return self::trimLicense(
                file_get_contents($routerFile)
            );
        }
        return '';
    }

    /**
     * Used to remove the sugarcrm license header from component files that are going to be rolled into a JSON response
     * @param string $text
     *
     * @return string
     */
    protected static function trimLicense($text) {
        $start = "{{!";
        $end = "}}";
        if (substr($text, 0, 3) == $start) {
            $endOfLicense = strpos($text, $end) + strlen($end);
            $text = substr($text, $endOfLicense);
        }

        return $text;

    }

    /**
     * Filters client files
     *
     * @param array $files Files to be filtered
     * @param MetaDataContextInterface $context Metadata context
     *
     * @return array Filtered set of files
     */
    protected static function filterClientFiles(array $files, MetaDataContextInterface $context)
    {
        $files = array_filter($files, function (array $file) use ($context) {
            return $context->isValid($file);
        });

        uasort($files, function ($a, $b) use ($context) {
            return $context->compare($a, $b);
        });

        return $files;
    }
}
