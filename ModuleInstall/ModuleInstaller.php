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

/*
 * ModuleInstaller - takes an installation package from files in the custom/Extension/X directories, and moves them into custom/X to install them.
 * If a directory has multiple files they are concatenated together.
 * Relevant directories (X) are Layoutdefs, Vardefs, Include (bean stuff), Language, TableDictionary (relationships)
 *
 * Installation steps that involve more than just copying files:
 * 1. installing custom fields - calls bean->custom_fields->addField
 * 2. installing relationships - calls createTableParams to build the relationship table, and createRelationshipMeta to add the relationship to the relationship table
 * 3. rebuilding the relationships - at almost the last step in install(), calls modules/Administration/RebuildRelationship.php
 * 4. repair indices - uses "modules/Administration/RepairIndex.php";
 */



require_once 'include/utils/progress_bar_utils.php';

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

define('DISABLED_PATH', 'Disabled');

class ModuleInstaller{
    var $modules = array();
    var $silent = false;
    var $base_dir  = '';
    var $modulesInPackage = array();
    public $disabled_path = DISABLED_PATH;

    /** @var array */
    public $installdefs;

    /**
     * List of install sections and modules affected by installation in each
     * sections. This is used to handle post install cleanup prior to a complete
     * rebuild to allow various caches to be handled at the correct point in
     * installation.
     *
     * @var array
     */
    protected $affectedModules = array();

    /**
     * The specification of the patch that should be applied to the module definition during installation
     *
     * @var array
     */
    protected $patch = array();

    public function __construct()
    {
        $this->ms = new ModuleScanner();
        $this->modules = $this->getModuleDirs();
        $this->db = DBManagerFactory::getInstance();
        include("ModuleInstall/extensions.php");
        $this->extensions = $extensions;
    }

    /**
     * Sets patch specification
     *
     * @param $patch
     */
    public function setPatch($patch)
    {
        $this->patch = $patch;
    }

    /**
     * ModuleInstaller->install includes the manifest.php from the base directory it has been given. If it has been asked to do an upgrade it checks to see if there is
     * an upgrade_manifest defined in the manifest; if not it errors. It then adds the bean into the custom/Extension/application/Ext/Include/<module>.php - sets beanList, beanFiles
     * and moduleList - and then calls ModuleInstaller->merge_files('Ext/Include', 'modules.ext.php', '', true) to merge the individual module files into a combined file
     * /custom/Extension/application/Ext/Include/modules.ext.php (which now contains a list of all $beanList, $beanFiles and $moduleList for all extension modules) -
     * this file modules.ext.php is included at the end of modules.php.
     *
     * Finally it runs over a list of defined tasks; then install_beans, then install_custom_fields, then clear the Vardefs, run a RepairAndClear, then finally call rebuild_relationships.
     */
    function install($base_dir, $is_upgrade = false, $previous_version = ''){
        if(defined('TEMPLATE_URL'))SugarTemplateUtilities::disableCache();
        if ((defined('MODULE_INSTALLER_PACKAGE_SCAN') && MODULE_INSTALLER_PACKAGE_SCAN)
            || !empty($GLOBALS['sugar_config']['moduleInstaller']['packageScan'])) {
            $this->ms->scanPackage($base_dir);
            if($this->ms->hasIssues()){
                $this->ms->displayIssues();
                sugar_cleanup(true);
            }
        }

        MetaDataManager::disableCache();

        // workaround for bug 45812 - refresh vardefs cache before unpacking to avoid partial vardefs in cache
        global $beanList;
        foreach ($this->modules as $module_name) {
            if (!empty($beanList[$module_name])) {
                $objectName = BeanFactory::getObjectName($module_name);
                VardefManager::loadVardef($module_name, $objectName);
            }
        }

        global $app_strings, $mod_strings;
        $this->base_dir = $base_dir;
        $total_steps = 5; //minimum number of steps with no tasks
        $current_step = 0;
        $tasks = array(
            'pre_execute',
            'install_copy',
            'update_wireless_metadata',
            'install_extensions',
            'install_images',
            'install_dcactions',
            'install_dashlets',
            'install_connectors',
            'install_layoutfields',
            'install_relationships',
            'install_client_files',
            'enable_manifest_logichooks',
            'post_execute',
            'reset_opcodes',
            'reset_file_cache',
            'setup_elastic_mapping',
        );

        $total_steps += count($tasks);
        if(file_exists($this->base_dir . '/manifest.php')){
            if(!$this->silent){
                $current_step++;
                display_progress_bar('install', $current_step, $total_steps);
                echo '<div id ="displayLoglink" ><a href="#" onclick="document.getElementById(\'displayLog\').style.display=\'\'">'
                    .$app_strings['LBL_DISPLAY_LOG'].'</a> </div><div id="displayLog" style="display:none">';
            }

            $data = $this->readManifest();
            extract($data);

            if($is_upgrade && !empty($previous_version)){
                //check if the upgrade path exists
                if(!empty($upgrade_manifest)){
                    if(!empty($upgrade_manifest['upgrade_paths'])){
                        if(!empty($upgrade_manifest['upgrade_paths'][$previous_version])){
                            $installdefs = $upgrade_manifest['upgrade_paths'][$previous_version];
                        }else{
                            $errors[] = 'No Upgrade Path Found in manifest.';
                            $this->abort($errors);
                        }//fi
                    }//fi
                }//fi
            }//fi
            $this->id_name = $installdefs['id'];
            $this->installdefs = $installdefs;
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
            }

            foreach($tasks as $task){
                $this->$task();
                if(!$this->silent){
                    $current_step++;
                    update_progress_bar('install', $current_step, $total_steps);
                }
            }
            // Run clean up on processes that need it prior to installing beans
            $this->runInstallCleanup();

            $this->install_beans($this->installed_modules);
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $total_steps, $total_steps);
            }
            if(isset($installdefs['custom_fields'])){
                $this->log(translate('LBL_MI_IN_CUSTOMFIELD'));
                $this->install_custom_fields($installdefs['custom_fields']);
            }
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
                echo '</div>';
            }
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
                echo '</div>';
            }
            $selectedActions = array(
                'clearTpls',
                'clearJsFiles',
                'clearDashlets',
                'clearVardefs',
                'clearJsLangFiles',
                'rebuildAuditTables',
                'repairDatabase',
            );
            VardefManager::clearVardef();
            global $beanList, $beanFiles, $moduleList;
            if (file_exists('custom/application/Ext/Include/modules.ext.php'))
            {
                include('custom/application/Ext/Include/modules.ext.php');
            }
            require_once("modules/Administration/upgrade_custom_relationships.php");
            upgrade_custom_relationships($this->installed_modules);
            $this->rebuild_all(true);
            $rac = new RepairAndClear();
            $rac->repairAndClearAll($selectedActions, $this->installed_modules,true, false);
            $this->updateSystemTabs('Add',$this->tab_modules);
            //Clear out all the langauge cache files.
            clearAllJsAndJsLangFilesWithoutOutput();
            $cache_key = 'app_list_strings.'.$GLOBALS['current_language'];
            sugar_cache_clear($cache_key );
            sugar_cache_reset();

            //clear the unified_search_module.php file
            UnifiedSearchAdvanced::unlinkUnifiedSearchModulesFile();

            // Rebuild roles so the ACLs for new modules are fresh immediately
            $this->updateRoles();

            // Get the newest app_list_strings
            $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

            // Refresh the vardefs
            $this->clearAffectedVardefsCache($this->modules);

            // Destroy all metadata caches and rebuild the base metadata. This
            // will cause a small amount of lag on subsequent requests for other
            // clients.
            MetaDataManager::enableCache();
            MetaDataManager::clearAPICache(true, true);
            LanguageManager::invalidateJsLanguageCache();

            //TODO: we need to setup the metadata for the platforms via a job queue.
            //Doing this inline is prohibitively expensive
            //MetaDataManager::setupMetadata();

            $dict = new ServiceDictionaryRest();
            $dict->buildAllDictionaries();

            $this->log('<br><b>' . translate('LBL_MI_COMPLETE') . '</b>');
        }else{
            die("No \$installdefs Defined In $this->base_dir/manifest.php");
        }

    }

    function install_user_prefs($module, $hide_from_user=false){
        UserPreference::updateAllUserPrefs('display_tabs', $module, '', true, !$hide_from_user);
        UserPreference::updateAllUserPrefs('hide_tabs', $module, '', true, $hide_from_user);
        UserPreference::updateAllUserPrefs('remove_tabs', $module, '', true, $hide_from_user);
    }
    function uninstall_user_prefs($module){
        UserPreference::updateAllUserPrefs('display_tabs', $module, '', true, true);
        UserPreference::updateAllUserPrefs('hide_tabs', $module, '', true, true);
        UserPreference::updateAllUserPrefs('remove_tabs', $module, '', true, true);
    }

    function pre_execute(){
        $data = $this->readManifest();
        extract($data);
        if(isset($this->installdefs['pre_execute']) && is_array($this->installdefs['pre_execute'])){
            foreach($this->installdefs['pre_execute'] as $includefile){
                require_once(str_replace('<basepath>', $this->base_dir, $includefile));
            }
        }
    }

    function post_execute(){
        $data = $this->readManifest();
        extract($data);
        if(isset($this->installdefs['post_execute']) && is_array($this->installdefs['post_execute'])){
            foreach($this->installdefs['post_execute'] as $includefile){
                require_once(str_replace('<basepath>', $this->base_dir, $includefile));
            }
        }
    }

    function pre_uninstall(){
        $data = $this->readManifest();
        extract($data);
        if(isset($this->installdefs['pre_uninstall']) && is_array($this->installdefs['pre_uninstall'])){
            foreach($this->installdefs['pre_uninstall'] as $includefile){
                require_once(str_replace('<basepath>', $this->base_dir, $includefile));
            }
        }
    }

    function post_uninstall(){
        $data = $this->readManifest();
        extract($data);
        if(isset($this->installdefs['post_uninstall']) && is_array($this->installdefs['post_uninstall'])){
            foreach($this->installdefs['post_uninstall'] as $includefile){
                require_once(str_replace('<basepath>', $this->base_dir, $includefile));
            }
        }
    }

    /*
     * ModuleInstaller->install_copy gets the copy section of installdefs in the manifest and calls copy_path to copy each path (file or directory) to its final location
     * (specified as from and to in the manifest), replacing <basepath> by the base_dir value passed in to install.
     */
    function install_copy(){
        if(isset($this->installdefs['copy'])){
            /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:22:11 PM */

            $backup_path = clean_path( remove_file_extension(urldecode($this->validateInstallFile())) . "-restore" );
            /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
            foreach($this->installdefs['copy'] as $cp){
                $GLOBALS['log']->debug("Copying ..." . $cp['from'].  " to " .$cp['to'] );
                /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:22:11 PM */
                $this->copy_path($cp['from'], $cp['to'], $backup_path);
                /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
            }
            //here we should get the module list again as we could have copied something to the modules dir
            $this->modules = $this->getModuleDirs();
        }
    }
    function uninstall_copy(){

        if(!empty($this->installdefs['copy'])){
            foreach($this->installdefs['copy'] as $cp){
                $cp['to'] = clean_path(str_replace('<basepath>', $this->base_dir, $cp['to']));
                $cp['from'] = clean_path(str_replace('<basepath>', $this->base_dir, $cp['from']));
                $GLOBALS['log']->debug('Unlink ' . $cp['to']);
                /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:22:11 PM */

                $backup_path = clean_path( remove_file_extension(urldecode(hashToFile($this->validateInstallFile())))."-restore/".$cp['to'] );
                $this->uninstall_new_files($cp, $backup_path);
                $this->copy_path($backup_path, $cp['to'], $backup_path, true);
                /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
            }
            $backup_path = clean_path( remove_file_extension(urldecode(hashToFile($this->validateInstallFile())))."-restore");
            if(file_exists($backup_path))
                rmdir_recursive($backup_path);
        }
        SugarAutoLoader::buildCache();
    }


    /**
     * Removes any files that were added by the loaded module. If the files already existed prior to install
     * it will be handled by copy_path with the uninstall parameter.
     *
     */
    function uninstall_new_files($cp, $backup_path){
        $zip_files = $this->dir_get_files($cp['from'],$cp['from']);
        $backup_files = $this->dir_get_files($backup_path, $backup_path);
        foreach($zip_files as $k=>$v){
            //if it's not a backup then it is probably a new file but we'll check that it is not in the md5.files first
            if(!isset($backup_files[$k])){
                $to = $cp['to'] . $k;
                //if it's not a sugar file then we remove it otherwise we can't restor it
                if(!$this->ms->sugarFileExists($to)){
                    $GLOBALS['log']->debug('ModuleInstaller[uninstall_new_file] deleting file ' . $to);
                    if(file_exists($to)) {
                        unlink($to);
                    }
                }else{
                    $GLOBALS['log']->fatal('ModuleInstaller[uninstall_new_file] Could not remove file ' . $to . ' as no backup file was found to restore to');
                }
            }
        }
        //lets check if the directory is empty if it is we will delete it as well
        $files_remaining = $this->dir_file_count($cp['to']);
        if(file_exists($cp['to']) && $files_remaining == 0){
            $GLOBALS['log']->debug('ModuleInstaller[uninstall_new_file] deleting directory ' . $cp['to']);
            rmdir_recursive($cp['to']);
        }
    }

    /**
     * Get directory where module's extensions go
     * @param string $module Module name
     */
    public function getExtDir($module)
    {
        if($module == 'application') {
            return "custom/Extension/application/Ext";
        } else {
            return "custom/Extension/modules/$module/Ext";
        }
    }

    /**
     * Install file(s) into Ext/ part
     * @param string $section Name of the install file section
     * @param string $extname Name in Ext directory
     * @param string $module This extension belongs to a specific module
     */
    public function installExt($section, $extname, $module = '')
    {
        if(isset($this->installdefs[$section])) {
            $this->log(sprintf(translate("LBL_MI_IN_EXT"), $section));
            foreach($this->installdefs[$section] as $item){
                if(isset($item['from'])) {
                    $from = str_replace('<basepath>', $this->base_dir, $item['from']);
                } else {
                    $from = '';
                }
                if(!empty($module)) {
                    $item['to_module'] = $module;
                }
                // we have already written out this item elsewhere
                if(isset($item['do_not_write']) && $item['do_not_write'] === true) {
                    continue;
                }
                $GLOBALS['log']->debug("Installing section $section from $from for " .$item['to_module'] );
                if (stristr($extname, '__PH_SUBTYPE__')) {
                    $path = $this->getClientExtPath($item['to_module'], $from);
                }
                elseif($item['to_module'] == 'application') {
                    $path = "custom/Extension/application/Ext/$extname";
                } else {
                    // Stack modules affected by this change to make sure all
                    // modules that need updating in caches can handle it
                    $this->affectedModules[$section][$item['to_module']] = $item['to_module'];
                    $path = "custom/Extension/modules/{$item['to_module']}/Ext/$extname";
                }
                if(!file_exists($path)){
                    mkdir_recursive($path, true);
                }
                if(isset($item["name"])) {
                    $target = $item["name"];
                } else if (!empty($from)){
                    $target = basename($from, ".php");
                } else {
                    $target = $this->id_name;
                }
                if(!empty($from)) {
                    copy_recursive($from , "$path/$target.php");
                }
            }
        }
    }

    /**
     * Used to copy over extension files for the clients directory. We have to assume that the structure in the zip
     * matches the structure of the desination location.
     *
     * Ex: SugarModules/clients/base/views/viewname/my_viewname_extension.php
     *
     * @param String $module module we are copying into
     * @param String $from Path of file we are copying
     *
     * @return string
     */
    public function getClientExtPath($module, $from)
    {
        $path = "custom/Extension/modules/{$module}/Ext/";
        if ($module == 'application') {
            $path = "custom/Extension/application/Ext/";
        }

        $start = strpos($from, "/clients/");
        $path .= substr($from, $start + 1);
        return dirname($path);
    }

    /**
     * Uninstall file(s) into Ext/ part
     * @param string $section Name of the install file section
     * @param string $extname Name in Ext directory
     * @param string $module This extension belongs to a specific module
     */
    public function uninstallExt($section, $extname, $module = '')
    {
        if(isset($this->installdefs[$section])){
            $this->log(sprintf(translate("LBL_MI_UN_EXT"), $section));
            foreach($this->installdefs[$section] as $item){
                if(isset($item['from'])) {
                    $from = str_replace('<basepath>', $this->base_dir, $item['from']);
                } else {
                    $from = '';
                }
                if(!empty($module)) {
                    $item['to_module'] = $module;
                }
                $GLOBALS['log']->debug("Uninstalling section $section from $from for " .$item['to_module'] );
                if (stristr($extname, '__PH_SUBTYPE__')) {
                    $path = $this->getClientExtPath($item['to_module'], $from);
                }elseif($item['to_module'] == 'application') {
                    $path = "custom/Extension/application/Ext/$extname";
                } else {
                    $path = "custom/Extension/modules/{$item['to_module']}/Ext/$extname";
                }
                if(isset($item["name"])) {
                    $target = $item["name"];
                } else if (!empty($from)){
                    $target = basename($from, ".php");
                } else {
                    $target = $this->id_name;
                }
                $disabled_path = $path.'/'.DISABLED_PATH;
                if (file_exists("$path/$target.php")) {
                    rmdir_recursive("$path/$target.php");
                } else if (file_exists("$disabled_path/$target.php")) {
                    rmdir_recursive("$disabled_path/$target.php");
                } else if (!empty($from) && file_exists($path . '/'. basename($from))) {
                    rmdir_recursive( $path . '/'. basename($from));
                } else if (!empty($from) && file_exists($disabled_path . '/'. basename($from))) {
                    rmdir_recursive( $disabled_path . '/'. basename($from));
                }
            }
        }
    }

    /**
     * Rebuild generic extension
     *
     * @param string $ext Extension directory
     * @param string $filename Target filename
     * @param array  $modules
     */
    public function rebuildExt($ext, $filename, $modules = array())
    {
        if (stristr($ext, '__PH_SUBTYPE__')) {
            $this->log(translate('LBL_MI_REBUILDING') . " " . translate('LBL_MI_REBUILDING_CLIENT_METADATA'));
        } else {
            $this->log(translate('LBL_MI_REBUILDING') . " $ext...");
        }
        $this->merge_files("Ext/$ext/", $filename, null, null, $modules);
    }

    /**
     * Disable generic extension
     * @param string $section Install file section name
     * @param string $extname Extension directory
     * @param string $module This extension belongs to a specific module
     */
    public function disableExt($section, $extname, $module = '')
    {
        if(isset($this->installdefs[$section])) {
            foreach($this->installdefs[$section] as $item) {
                if(isset($item['from'])) {
                    $from = str_replace('<basepath>', $this->base_dir, $item['from']);
                } else {
                    $from = '';
                }
                if(!empty($module)) {
                    $item['to_module'] = $module;
                }
                $GLOBALS['log']->debug("Disabling $extname ... from $from for " .$item['to_module']);
                if($item['to_module'] == 'application') {
                    $path = "custom/Extension/application/Ext/$extname";
                } else {
                    $path = "custom/Extension/modules/{$item['to_module']}/Ext/$extname";
                }
                if(isset($item["name"])) {
                    $target = $item["name"];
                } else if (!empty($from)){
                    $target = basename($from, ".php");
                }else {
                    $target = $this->id_name;
                }

                $path = $this->subSidecarPlaceHolders($path, $from);

                if (file_exists("$path/$target.php")) {
                    $this->enableMetadataFile($path . DIRECTORY_SEPARATOR, $target . '.php', false);
                } elseif (!empty($from)) {
                    $this->enableMetadataFile($path . DIRECTORY_SEPARATOR, basename($from), false);
                }
            }
        }
    }

    /**
     * subs /__PH_PLATFORM__/__PH_TYPE__/__PH_SUBTYPE__ in a path
     * with sidecar properties from a target so
     * path 'extension/__PH_PLATFORM__/__PH_TYPE__/__PH_SUBTYPE__/blah.php'
     * with target
     * 'extentsion/clients/base/views/blah/'
     * returns extension/clients/base/views/blah/blah.php
     * @param $path
     * @param $from
     * @return mixed
     */
    public function subSidecarPlaceHolders($target, $source) {
        $outPath = $target;
        $phIdx = strpos($target,'/__PH_PLATFORM__/__PH_TYPE__/__PH_SUBTYPE__');
        if ($phIdx !== -1) {
            $ph2pathKey = array(
                '__PH_PLATFORM__' => 'platform',
                '__PH_TYPE__' => 'type',
                '__PH_SUBTYPE__' => 'subtype',
            );
            $fromPathInfo = $this->getSidecarFileInfo($source);
            foreach($ph2pathKey as $ph => $pathKey) {
                if (isset($fromPathInfo[$pathKey])) {
                    $target = str_replace($ph, $fromPathInfo[$pathKey], $target);
                }
            }
        }
        return $outPath;
    }

    /**
     * gets path information about a sidecar file path for example will break
     * extension/clients/base/views/test/test.php
     * into array('platform' => 'base', 'type' =>'views', 'subtype' =>'test')
     * @param $path
     * @return array
     */
    public function getSidecarFileInfo($path = '') {
        $info = array();
        $matches = array();

        $regExPattern ='/\/clients\/(.+)\/(.+)\/(.+)\/(.*)/i';
        if (preg_match($regExPattern, $path, $matches) && count($matches) ===4) {
            $info['platform'] = $matches[1];
            $info['type'] = $matches[2];
            $info['subtype'] = $matches[3];
        }

        return $info;
    }

    /**
     * Enable generic extension
     * @param string $section Install file section name
     * @param string $extname Extension directory
     * @param string $module This extension belongs to a specific module
     */
    public function enableExt($section, $extname, $module = '')
    {
        if(isset($this->installdefs[$section])) {
            foreach($this->installdefs[$section] as $item) {
                if(isset($item['from'])) {
                    $from = str_replace('<basepath>', $this->base_dir, $item['from']);
                } else {
                    $from = '';
                }
                if(!empty($module)) {
                    $item['to_module'] = $module;
                }
                $GLOBALS['log']->debug("Enabling $extname ... from $from for " .$item['to_module']);

                if($item['to_module'] == 'application') {
                    $path = "custom/Extension/application/Ext/$extname";
                } else {
                    $path = "custom/Extension/modules/{$item['to_module']}/Ext/$extname";
                }
                if(isset($item["name"])) {
                    $target = $item["name"];
                } else if (!empty($from)){
                    $target = basename($from, ".php");
                } else {
                    $target = $this->id_name;
                }
                $this->enableMetadataFile($path . DIRECTORY_SEPARATOR, $target . '.php', true);
                if (!empty($from)) {
                    $this->enableMetadataFile($path . DIRECTORY_SEPARATOR, basename($from), true);
                }
            }
        }
    }

    /**
     * Method removes module from global search configurations
     *
     * return bool
     */
    public function uninstall_global_search()
    {
        if (empty($this->installdefs['beans']))
        {
            return true;
        }

        if (is_file('custom/modules/unified_search_modules_display.php') == false)
        {
            return true;
        }

        $user = BeanFactory::newBean('Users');
        $users = get_user_array();
        $unified_search_modules_display = array();
        require('custom/modules/unified_search_modules_display.php');

        foreach($this->installdefs['beans'] as $beanDefs)
        {
            if (array_key_exists($beanDefs['module'], $unified_search_modules_display) == false)
            {
                continue;
            }
            unset($unified_search_modules_display[$beanDefs['module']]);
            foreach($users as $userId => $userName)
            {
                if (empty($userId))
                {
                    continue;
                }
                $user->retrieve($userId);
                $prefsInSession = isset($_SESSION[$user->user_name . '_PREFERENCES']) ? $_SESSION[$user->user_name . '_PREFERENCES'] : null;
                $prefs = $user->getPreference('globalSearch', 'search');

                if (is_array($prefs) && array_key_exists($beanDefs['module'], $prefs)) {
                    unset($prefs[$beanDefs['module']]);
                    $user->setPreference('globalSearch', $prefs, 0, 'search');
                    $user->savePreferencesToDB();
                }

                // Delete temp session data
                if (empty($prefsInSession)) {
                    unset($_SESSION[$user->user_name . '_PREFERENCES']);
                } else if (empty($prefsInSession['search'])) {
                    unset($_SESSION[$user->user_name . '_PREFERENCES']['search']);
                }
            }
        }

        if (write_array_to_file("unified_search_modules_display", $unified_search_modules_display, 'custom/modules/unified_search_modules_display.php') == false)
        {
            global $app_strings;
            $msg = string_format($app_strings['ERR_FILE_WRITE'], array('custom/modules/unified_search_modules_display.php'));
            $GLOBALS['log']->error($msg);
            throw new Exception($msg);
            return false;
        }
        return true;
    }

    /**
     * Uninstalls module filters
     */
    protected function uninstall_filters()
    {
        if (empty($this->installdefs['beans'])) {
            return;
        }

        $modules = array();
        foreach ($this->installdefs['beans'] as $definition) {
            $modules[] = $definition['module'];
        }

        $filter = BeanFactory::newBean('Filters');
        $query = new SugarQuery();
        $query->select('id');
        $query->from($filter)->where()->in('module_name', $modules);
        $data = $query->execute();
        foreach ($data as $row) {
            $filter->mark_deleted($row['id']);
        }
    }

    /**
     * Method enables module in global search configurations by disabled_module_visible key
     *
     * return bool
     */
    public function enable_global_search()
    {
        if (empty($this->installdefs['beans']))
        {
            return true;
        }

        if (is_file('custom/modules/unified_search_modules_display.php') == false)
        {
            return true;
        }

        $unified_search_modules_display = array();
        require('custom/modules/unified_search_modules_display.php');

        foreach($this->installdefs['beans'] as $beanDefs)
        {
            if (array_key_exists($beanDefs['module'], $unified_search_modules_display) == false)
            {
                continue;
            }
            if (isset($unified_search_modules_display[$beanDefs['module']]['disabled_module_visible']) == false)
            {
                continue;
            }
            $unified_search_modules_display[$beanDefs['module']]['visible'] = $unified_search_modules_display[$beanDefs['module']]['disabled_module_visible'];
            unset($unified_search_modules_display[$beanDefs['module']]['disabled_module_visible']);
        }

        if (write_array_to_file("unified_search_modules_display", $unified_search_modules_display, 'custom/modules/unified_search_modules_display.php') == false)
        {
            global $app_strings;
            $msg = string_format($app_strings['ERR_FILE_WRITE'], array('custom/modules/unified_search_modules_display.php'));
            $GLOBALS['log']->error($msg);
            throw new Exception($msg);
            return false;
        }
        return true;
    }

    /**
     * Method disables module in global search configurations by disabled_module_visible key
     *
     * return bool
     */
    public function disable_global_search()
    {
        if (empty($this->installdefs['beans']))
        {
            return true;
        }

        if (is_file('custom/modules/unified_search_modules_display.php') == false)
        {
            return true;
        }

        $unified_search_modules_display = array();
        require('custom/modules/unified_search_modules_display.php');

        foreach($this->installdefs['beans'] as $beanDefs)
        {
            if (array_key_exists($beanDefs['module'], $unified_search_modules_display) == false)
            {
                continue;
            }
            if (isset($unified_search_modules_display[$beanDefs['module']]['visible']) == false)
            {
                continue;
            }
            $unified_search_modules_display[$beanDefs['module']]['disabled_module_visible'] = $unified_search_modules_display[$beanDefs['module']]['visible'];
            $unified_search_modules_display[$beanDefs['module']]['visible'] = false;
        }

        if (write_array_to_file("unified_search_modules_display", $unified_search_modules_display, 'custom/modules/unified_search_modules_display.php') == false)
        {
            global $app_strings;
            $msg = string_format($app_strings['ERR_FILE_WRITE'], array('custom/modules/unified_search_modules_display.php'));
            $GLOBALS['log']->error($msg);
            throw new Exception($msg);
            return false;
        }
        return true;
    }

    public function install_extensions()
    {
        foreach($this->extensions as $extname => $ext) {
            $install = "install_$extname";
            if(method_exists($this, $install)) {
                // non-standard function
                $this->$install();
            } else {
                if(!empty($ext["section"])) {
                    $module = isset($ext['module'])?$ext['module']:'';
                    $this->installExt($ext["section"], $ext["extdir"], $module);
                }
            }
        }
        $this->rebuild_extensions();
    }

    public function uninstall_extensions()
    {
        foreach($this->extensions as $extname => $ext) {
            $func = "uninstall_$extname";
            if(method_exists($this, $func)) {
                // non-standard function
                $this->$func();
            } else {
                if(!empty($ext["section"])) {
                    $module = isset($ext['module'])?$ext['module']:'';
                    $this->uninstallExt($ext["section"], $ext["extdir"], $module);
                }
            }
        }
        $this->rebuild_extensions();
    }

    public function rebuild_extensions($modules = array(), $filter = array())
    {
        foreach ($this->extensions as $extname => $ext) {
            if (empty($filter) || in_array($extname, $filter)) {
                $func = "rebuild_$extname";
                //Special case for languages since it breaks the $modules as first argument interface.
                //TODO: Refactor rebuilding helper into proper interfaces and out of the "Installer" class.
                if ($func == "rebuild_languages") {
                    $this->rebuild_languages(array(), $modules);
                } elseif (method_exists($this, $func)) {
                    // non-standard function
                    $this->$func($modules);
                } else {
                    $this->rebuildExt($ext["extdir"], $ext["file"], $modules);
                }
            }
        }
    }

    public function disable_extensions()
    {
        foreach($this->extensions as $extname => $ext) {
            $func = "disable_$extname";
            if(method_exists($this, $func)) {
                // non-standard install
                $this->$func();
            } else {
                if(!empty($ext["section"])) {
                    $module = isset($ext['module'])?$ext['module']:'';
                    $this->disableExt($ext["section"], $ext["extdir"], $module);
                }
            }
        }
        $this->rebuild_extensions();
    }

    public function enable_extensions()
    {
        foreach($this->extensions as $extname => $ext) {
            $func = "enable_$extname";
            if(method_exists($this, $func)) {
                // non-standard install
                $this->$func();
            } else {
                if(!empty($ext["section"])) {
                    $module = isset($ext['module'])?$ext['module']:'';
                    $this->enableExt($ext["section"], $ext["extdir"], $module);
                }
            }
        }
        $this->rebuild_extensions($this->modulesInPackage);
    }

    function install_dashlets()
    {
        if(isset($this->installdefs['dashlets'])){
            foreach($this->installdefs['dashlets'] as $cp){
                $this->log(translate('LBL_MI_IN_DASHLETS') . $cp['name']);
                $cp['from'] = str_replace('<basepath>', $this->base_dir, $cp['from']);
                $path = 'custom/modules/Home/Dashlets/' . $cp['name'] . '/';
                $GLOBALS['log']->debug("Installing Dashlet " . $cp['name'] . "..." . $cp['from'] );
                if(!file_exists($path)){
                    mkdir_recursive($path, true);
                }
                copy_recursive($cp['from'] , $path);
            }
            include('modules/Administration/RebuildDashlets.php');

        }
    }

    function uninstall_dashlets(){
        if(isset($this->installdefs['dashlets'])){
            foreach($this->installdefs['dashlets'] as $cp){
                $this->log(translate('LBL_MI_UN_DASHLETS') . $cp['name']);
                $path = 'custom/modules/Home/Dashlets/' . $cp['name'];
                $GLOBALS['log']->debug('Unlink ' .$path);
                if (file_exists($path))
                    rmdir_recursive($path);
            }
            include('modules/Administration/RebuildDashlets.php');
        }
    }


    function install_images(){
        if(isset($this->installdefs['image_dir'])){
            $this->log( translate('LBL_MI_IN_IMAGES') );
            $this->copy_path($this->installdefs['image_dir'] , 'custom/themes');

        }
    }

    function install_dcactions(){
        if(isset($this->installdefs['dcaction'])){
            $this->log(translate('LBL_MI_IN_MENUS'));
            foreach($this->installdefs['dcaction'] as $action){
                $action['from'] = str_replace('<basepath>', $this->base_dir, $action['from']);
                $GLOBALS['log']->debug("Installing DCActions ..." . $action['from']);
                $path = 'custom/Extension/application/Ext/DashletContainer/Containers';
                if(!file_exists($path)){
                    mkdir_recursive($path, true);
                }
                copy_recursive($action['from'] , $path . '/'. $this->id_name . '.php');
            }
            $this->rebuild_dashletcontainers();
        }
    }

    function uninstall_dcactions(){
        if(isset($this->installdefs['dcaction'])){
            $this->log(translate('LBL_MI_UN_MENUS'));
            foreach($this->installdefs['dcaction'] as $action){
                $action['from'] = str_replace('<basepath>', $this->base_dir, $action['from']);
                $GLOBALS['log']->debug("Uninstalling DCActions ..." . $action['from'] );
                $path = 'custom/Extension/application/Ext/DashletContainer/Containers';
                if (sugar_is_file($path . '/'. $this->id_name . '.php', 'w'))
                {
                    rmdir_recursive( $path . '/'. $this->id_name . '.php');
                }
                else if (sugar_is_file($path . '/'. DISABLED_PATH . '/'. $this->id_name . '.php', 'w'))
                {
                    rmdir_recursive( $path . '/'. DISABLED_PATH . '/'. $this->id_name . '.php');
                }
            }
            $this->rebuild_dashletcontainers();
        }
    }

    function install_connectors(){
        if(isset($this->installdefs['connectors'])){
            foreach($this->installdefs['connectors'] as $cp){
                $this->log(translate('LBL_MI_IN_CONNECTORS') . $cp['name']);
                $dir = str_replace('_','/',$cp['name']);
                $cp['connector'] = str_replace('<basepath>', $this->base_dir, $cp['connector']);
                $source_path = 'custom/modules/Connectors/connectors/sources/' . $dir. '/';
                $GLOBALS['log']->debug("Installing Connector " . $cp['name'] . "..." . $cp['connector'] );
                if(!file_exists($source_path)){
                    mkdir_recursive($source_path, true);
                }
                copy_recursive($cp['connector'] , $source_path);

                //Install optional formatter code if it is specified
                if(!empty($cp['formatter'])) {
                    $cp['formatter'] = str_replace('<basepath>', $this->base_dir, $cp['formatter']);
                    $formatter_path = 'custom/modules/Connectors/connectors/formatters/' . $dir. '/';
                    if(!file_exists($formatter_path)){
                        mkdir_recursive($formatter_path, true);
                    }
                    copy_recursive($cp['formatter'] , $formatter_path);
                }
            }
            $this->reset_file_cache();
            require_once('include/connectors/utils/ConnectorUtils.php');
            ConnectorUtils::installSource($cp['name']);

            // refresh connector cache
            $cm = new ConnectorManager();
            $connectors = $cm->buildConnectorsMeta();

        }

    }
    function uninstall_connectors(){
        if(isset($this->installdefs['connectors'])){
            foreach($this->installdefs['connectors'] as $cp){
                $this->log(translate('LBL_MI_UN_CONNECTORS') . $cp['name']);
                $dir = str_replace('_','/',$cp['name']);
                $source_path = 'custom/modules/Connectors/connectors/sources/' . $dir;
                $formatter_path = 'custom/modules/Connectors/connectors/formatters/' . $dir;
                $GLOBALS['log']->debug('Unlink ' .$source_path);
                rmdir_recursive($source_path);
                rmdir_recursive($formatter_path);
            }
            $this->reset_file_cache();
            require_once('include/connectors/utils/ConnectorUtils.php');
            ConnectorUtils::uninstallSource($cp['name']);
        }
    }

    function install_vardef($from, $to_module)
    {
        $GLOBALS['log']->debug("Installing Vardefs ..." . $from .  " for " .$to_module);
        $path = 'custom/Extension/modules/' . $to_module. '/Ext/Vardefs';
        if($to_module == 'application'){
            $path ='custom/Extension/' . $to_module. '/Ext/Vardefs';
        }
        if(!file_exists($path)){
            mkdir_recursive($path, true);
        }
        copy_recursive($from , $path.'/'. basename($from));
    }

    function install_layoutdef($from, $to_module){
        $GLOBALS['log']->debug("Installing Layout Defs ..." . $from .  " for " .$to_module);
        $path = 'custom/Extension/modules/' . $to_module. '/Ext/Layoutdefs';
        if($to_module == 'application'){
            $path ='custom/Extension/' . $to_module. '/Ext/Layoutdefs';
        }
        if(!file_exists($path)){
            mkdir_recursive($path, true);
        }
        copy_recursive($from , $path.'/'. basename($from));
    }

    // Non-standard - needs special rebuild call
    function install_languages()
    {
        $languages = array();
        if(isset($this->installdefs['language']))
        {
            $modules = array();
            $this->log(translate('LBL_MI_IN_LANG') );
            foreach($this->installdefs['language'] as $packs)
            {
                $languages[$packs['language']] = $packs['language'];
                $packs['from'] = str_replace('<basepath>', $this->base_dir, $packs['from']);
                $GLOBALS['log']->debug("Installing Language Pack ..." . $packs['from']  .  " for " .$packs['to_module']);
                if($packs['to_module'] == 'application'){
                    $path ='custom/Extension/' . $packs['to_module']. '/Ext/Language';
                } else {
                    $modules[$packs['to_module']] = true;
                    $path = 'custom/Extension/modules/' . $packs['to_module'] . '/Ext/Language';
                }

                if(!file_exists($path)){
                    mkdir_recursive($path, true);
                }
                $lang_file = $path.'/'.$packs['language'].'.'. $this->id_name . '.php';
                if (!file_exists($lang_file)) {
                    copy_recursive($packs['from'], $lang_file);
                }
                else {
                    $temp_lang_file = $path.'/'.'temp.php';
                    copy_recursive($packs['from'], $temp_lang_file);
                    $contents = $this->getExtensionFileContents(array($lang_file, $temp_lang_file));
                    file_put_contents($lang_file, $contents);
                    unlink($temp_lang_file);
                }
            }
            $this->rebuild_languages($languages, array_keys($modules));
        }
    }

    // Non-standard, needs special rebuild
    function uninstall_languages(){
        $languages = array();
        if(isset($this->installdefs['language'])){
            $modules = array();
            $this->log(translate('LBL_MI_UN_LANG') );
            foreach($this->installdefs['language'] as $packs){
                $languages[$packs['language']] = $packs['language'];
                $packs['from'] = str_replace('<basepath>', $this->base_dir, $packs['from']);
                $GLOBALS['log']->debug("Uninstalling Language Pack ..." . $packs['from']  .  " for " .$packs['to_module']);
                if($packs['to_module'] == 'application'){
                    $path ='custom/Extension/' . $packs['to_module']. '/Ext/Language';
                } else {
                    $modules[$packs['to_module']] = true;
                    $path = 'custom/Extension/modules/' . $packs['to_module'] . '/Ext/Language';
                }
                if (sugar_is_file($path.'/'.$packs['language'].'.'. $this->id_name . '.php', 'w')) {
                    rmdir_recursive( $path.'/'.$packs['language'].'.'. $this->id_name . '.php');
                } else if (sugar_is_file($path.'/'.DISABLED_PATH.'/'.$packs['language'].'.'. $this->id_name . '.php', 'w')) {
                    rmdir_recursive($path.'/'.DISABLED_PATH.'/'.$packs['language'].'.'. $this->id_name . '.php', 'w');
                }
            }
            $this->rebuild_languages($languages, array_keys($modules));
        }
    }

    // Non-standard, needs special rebuild
    public function disable_languages()
    {
        if(isset($this->installdefs['language'])) {
            $languages = $modules = array();
            foreach($this->installdefs['language'] as $item) {
                $from = str_replace('<basepath>', $this->base_dir, $item['from']);
                $GLOBALS['log']->debug("Disabling Language {$item['language']}... from $from for " .$item['to_module']);
                $modules[]=$item['to_module'];
                $languages[$item['language']] = $item['language'];
                if($item['to_module'] == 'application') {
                    $path = "custom/Extension/application/Ext/Language";
                } else {
                    $path = "custom/Extension/modules/{$item['to_module']}/Ext/Language";
                }
                if(isset($item["name"])) {
                    $target = $item["name"];
                } else {
                    $target = $this->id_name;
                }
                $target = "{$item['language']}.$target";

                $disabled_path = $path.'/'.DISABLED_PATH;
                if (file_exists("$path/$target.php")) {
                    mkdir_recursive($disabled_path, true);
                    rename("$path/$target.php", "$disabled_path/$target.php");
                } else if (file_exists($path . '/'. basename($from))) {
                    mkdir_recursive($disabled_path, true);
                    rename( $path . '/'. basename($from), $disabled_path.'/'. basename($from));
                }
            }
            $this->rebuild_languages($languages, $modules);
        }
    }

    // Non-standard, needs special rebuild
    public function enable_languages()
    {
        if(isset($this->installdefs['language'])) {
            foreach($this->installdefs['language'] as $item) {
                $from = str_replace('<basepath>', $this->base_dir, $item['from']);
                $GLOBALS['log']->debug("Enabling Language {$item['language']}... from $from for " .$item['to_module']);
                $modules[]=$item['to_module'];
                $languages[$item['language']] = $item['language'];
                if(!empty($module)) {
                    $item['to_module'] = $module;
                }

                if($item['to_module'] == 'application') {
                    $path = "custom/Extension/application/Ext/Language";
                } else {
                    $path = "custom/Extension/modules/{$item['to_module']}/Ext/Language";
                }
                if(isset($item["name"])) {
                    $target = $item["name"];
                } else {
                    $target = $this->id_name;
                }
                $target = "{$item['language']}.$target";

                if(!file_exists($path)) {
                    mkdir_recursive($path, true);
                }
                $disabled_path = $path.'/'.DISABLED_PATH;
                if (file_exists("$disabled_path/$target.php")) {
                    rename("$disabled_path/$target.php",  "$path/$target.php");
                }
                if (file_exists($disabled_path . '/'. basename($from))) {
                    rename($disabled_path.'/'. basename($from),  $path . '/'. basename($from));
                }
            }
            $this->rebuild_languages($languages, $modules);
        }
    }

    // Functions for adding and removing logic hooks from uploaded files
    // Since one class/file can be used by multiple logic hooks, I'm not going to touch the file labeled in the logic_hook entry
    /* The module hook definition should look like this:
     $installdefs = array(
     ... blah blah ...
         'logic_hooks' => array(
             array('module'      => 'Accounts',
                   'hook'        => 'after_save',
                   'order'       => 99,
                   'description' => 'Account sample logic hook',
                   'file'        => 'modules/Sample/sample_account_logic_hook_file.php',
                   'class'       => 'SampleLogicClass',
                   'function'    => 'accountAfterSave',
             ),
         ),
     ... blah blah ...
     );
     */
    function enable_manifest_logichooks() {
        if(empty($this->installdefs['logic_hooks']) || !is_array($this->installdefs['logic_hooks'])) {
            return;
        }



        foreach($this->installdefs['logic_hooks'] as $hook ) {
            check_logic_hook_file($hook['module'], $hook['hook'], array($hook['order'], $hook['description'],  $hook['file'], $hook['class'], $hook['function']));
        }
    }

    function disable_manifest_logichooks() {
        if(empty($this->installdefs['logic_hooks']) || !is_array($this->installdefs['logic_hooks'])) {
            return;
        }

        foreach($this->installdefs['logic_hooks'] as $hook ) {
            remove_logic_hook($hook['module'], $hook['hook'], array($hook['order'], $hook['description'],  $hook['file'], $hook['class'], $hook['function']));
        }
    }

    /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
    function copy_path($from, $to, $backup_path='', $uninstall=false){
        /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
        $to = str_replace('<basepath>', $this->base_dir, $to);

        if(!$uninstall) {
            $from = str_replace('<basepath>', $this->base_dir, $from);
            $GLOBALS['log']->debug('Copy ' . $from);
        }
        else {
            $from = str_replace('<basepath>', $backup_path, $from);
        }
        $from = clean_path($from);
        $to = clean_path($to);

        if (!isValidCopyPath($to)) {
            sugar_die('Invalid destination path: ' . $to);
        }

        $dir = dirname($to);
        //there are cases where if we need to create a directory in the root directory
        if($dir == '.' && is_dir($from)){
            $dir = $to;
        }
        if(!sugar_is_dir($dir, 'instance'))
            mkdir_recursive($dir, true);
        /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
        if(empty($backup_path)) {
            /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
            if(!copy_recursive($from, $to)){
                die('Failed to copy ' . $from. ' ' . $to);
            }
            /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
        }
        elseif(!$this->copy_recursive_with_backup($from, $to, $backup_path, $uninstall)){
            die('Failed to copy ' . $from. ' to ' . $to);
        }
        /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:22:18 PM */
    }

    function install_custom_fields($fields){
        global $beanList, $beanFiles;
        include('include/modules.php');
        require_once('modules/DynamicFields/FieldCases.php');
        foreach($fields as $field){
            $installed = false;
            if(!empty($beanList[ $field['module']])){

                if(!isset($field['ext4']))$field['ext4'] = '';
                if(!isset($field['mass_update']))$field['mass_update'] = 0;
                if(!isset($field['duplicate_merge']))$field['duplicate_merge'] = 0;
                if(!isset($field['help']))$field['help'] = '';

                //Merge contents of the sugar field extension if we copied one over
                if (file_exists("custom/Extension/modules/{$field['module']}/Ext/Vardefs/sugarfield_{$field['name']}.php"))
                {
                    $dictionary = array();
                    include ("custom/Extension/modules/{$field['module']}/Ext/Vardefs/sugarfield_{$field['name']}.php");
                    $obj = BeanFactory::getObjectName($field['module']);
                    if (!empty($dictionary[$obj]['fields'][$field['name']])) {
                        $field = array_merge($dictionary[$obj]['fields'][$field['name']], $field);
                    }
                }

                $mod = BeanFactory::newBean($field['module']);

                if(!empty($mod)){
                    $installed = true;
                    $fieldObject = get_widget($field['type']);
                    $fieldObject->populateFromRow($field);
                    $mod->custom_fields->use_existing_labels =  true;
                    $mod->custom_fields->addFieldObject($fieldObject);
                }
            }
            if(!$installed){
                $GLOBALS['log']->debug('Could not install custom field ' . $field['name'] . ' for module ' .  $field['module'] . ': Module does not exist');
            }
        }
    }

    function uninstall_custom_fields($fields){
        $dyField = new DynamicField();

        foreach($fields as $field){
            $mod = BeanFactory::newBean($field['module']);
            if(!empty($mod)) {
                $dyField->bean = $mod;
                $dyField->module = $field['module'];
                $dyField->deleteField($field['name']);
            }
        }
    }

    /*
 * ModuleInstaller->install_relationships calls install_relationship for every file included in the module package that defines a relationship, and then
 * writes a custom/Extension/application/Ext/TableDictionary/$module.php file containing an include_once for every relationship metadata file passed to install_relationship.
 * Next it calls install_vardef and install_layoutdef. Finally, it rebuilds the vardefs and layoutdefs (by calling merge_files as usual), and then calls merge_files to merge
 * everything in 'Ext/TableDictionary/' into 'tabledictionary.ext.php'
 */
    function install_relationships ()
    {
        if (isset ( $this->installdefs [ 'relationships' ] ))
        {
            $this->log ( translate ( 'LBL_MI_IN_RELATIONSHIPS' ) ) ;
            $str = "<?php \n //WARNING: The contents of this file are auto-generated\n" ;
            $save_table_dictionary = false ;

            if (! file_exists ( "custom/Extension/application/Ext/TableDictionary" ))
            {
                mkdir_recursive ( "custom/Extension/application/Ext/TableDictionary", true ) ;
            }

            foreach ( $this->installdefs [ 'relationships' ] as $key => $relationship )
            {
                $filename = basename ( $relationship [ 'meta_data' ] ) ;
                $this->copy_path ( $relationship [ 'meta_data' ], 'custom/metadata/' . $filename ) ;
                $this->install_relationship ( 'custom/metadata/' . $filename ) ;
                $save_table_dictionary = true ;

                if (! empty ( $relationship [ 'module_vardefs' ] ))
                {
                    $relationship [ 'module_vardefs' ] = str_replace ( '<basepath>', $this->base_dir, $relationship [ 'module_vardefs' ] ) ;
                    $this->install_vardef ( $relationship [ 'module_vardefs' ], $relationship [ 'module' ] ) ;
                }

                if (! empty ( $relationship [ 'module_layoutdefs' ] ))
                {
                    $relationship [ 'module_layoutdefs' ] = str_replace ( '<basepath>', $this->base_dir, $relationship [ 'module_layoutdefs' ] ) ;
                    $this->install_layoutdef ( $relationship [ 'module_layoutdefs' ], $relationship [ 'module' ] ) ;
                }

                $relName = strpos($filename, "MetaData") !== false ? substr($filename, 0, strlen($filename) - 12) : $filename;
                $out = sugar_fopen ( "custom/Extension/application/Ext/TableDictionary/$relName.php", 'w' ) ;
                fwrite ( $out, $str . "include('custom/metadata/$filename');\n\n?>" ) ;
                fclose ( $out ) ;
            }

            $this->rebuild_vardefs () ;
            $this->rebuild_layoutdefs () ;
            if ($save_table_dictionary)
            {
                $this->rebuild_tabledictionary () ;
            }
            SugarRelationshipFactory::deleteCache();
        }
    }

    /*
     * Install_relationship obtains a set of relationship definitions from the filename passed in as a parameter.
     * For each definition it calls db->createTableParams to build the relationships table if it does not exist,
     * and SugarBean::createRelationshipMeta to add the relationship into the 'relationships' table.
     */
    function install_relationship($file)
    {
        $_REQUEST['moduleInstaller'] = true;
        if (!file_exists($file)) {
            $GLOBALS['log']->debug('File does not exists : ' . $file);

            return;
        }
        $rel_dictionary = FileLoader::varFromInclude($file, 'dictionary');

        array_walk($rel_dictionary, array("ModuleInstaller", "cleanUpRelationship"));

        foreach ($rel_dictionary as $rel_name => $rel_data) {
            // check if we have a table definition - not all relationships require a join table
            if (isset($rel_data['table'])) {
                $table = $rel_data['table'];

                if (!$this->db->tableExists($table)) {
                    $indices = isset($rel_data['indices']) ? $rel_data['indices'] : [];
                    $this->db->createTableParams($table, $rel_data['fields'], $indices);
                }
            }

            if (!$this->silent) {
                $GLOBALS['log']->debug("Processing relationship meta for " . $rel_name . "...");
            }
            if (!$this->silent) {
                $GLOBALS['log']->debug('done<br>');
            }
        }

        SugarRelationshipFactory::deleteCache();
    }


    /**
     * Clean up the relationship definition for dbs with max string lengths
     *
     * @param array $item
     * @param char $key
     *
     */
    public static function cleanUpRelationship(&$item, &$key)
    {
        global $db;
        if (isset($item['table'])) {
            $item['table'] = $db->getValidDBName($item['table'], false, 'table');
        }
        $key = $db->getValidDBName($key);
        if (is_array($item['fields'])) {
            foreach ($item['fields'] as &$field) {
                $field['name'] = $db->getValidDBName($field['name']);
            }
        }

        if (isset($item['indices']) && is_array($item['indices'])) {
            foreach ($item['indices'] as &$index) {
                $index['name'] = $db->getValidDBName($index['name'], false, 'index');
            }
        }
    }


    function install_layoutfields() {
        if (!empty ( $this->installdefs [ 'layoutfields' ] ))
        {
            foreach ( $this->installdefs [ 'layoutfields' ] as $fieldSet )
            {
                if (!empty($fieldSet['additional_fields']))
                {
                    $this->addFieldsToLayout($fieldSet['additional_fields']);
                }
            }
        }
    }

    function uninstall_layoutfields() {
        if (!empty ( $this->installdefs [ 'layoutfields' ] ))
        {
            foreach ( $this->installdefs [ 'layoutfields' ] as $fieldSet )
            {
                if (!empty($fieldSet['additional_fields']))
                {
                    $this->removeFieldsFromLayout($fieldSet['additional_fields']);
                }
            }
        }
    }

    function uninstall_relationship($file, $rel_dictionary = null){
        if ($rel_dictionary == null)
        {
            if(!file_exists($file)){
                $GLOBALS['log']->debug( 'File does not exists : '.$file);
                return;
            }
            include($file);
            $rel_dictionary = $dictionary;
        }

        foreach ($rel_dictionary as $rel_name => $rel_data)
        {
            if (!empty($rel_data['table'])){
                $table = $rel_data['table'];
            }
            else{
                $table = ' One-to-Many ';
            }

            if ($this->db->tableExists($table) && isset($GLOBALS['mi_remove_tables']) && $GLOBALS['mi_remove_tables'])
            {
                SugarBean::removeRelationshipMeta($rel_name, $this->db,$table,$rel_dictionary,'');
                $this->db->dropTableName($table);
                if(!$this->silent) $this->log( translate('LBL_MI_UN_RELATIONSHIPS_DROP') . $table);
            }

            //Delete Layout defs
            // check to see if we have any vardef or layoutdef entries to remove - must have a relationship['module'] parameter if we do
            if (!isset($rel_data[ 'module' ]))
                $mods = array(
                    $rel_data['relationships'][$rel_name]['lhs_module'],
                    $rel_data['relationships'][$rel_name]['rhs_module'],
                );
            else
                $mods = array($rel_data[ 'module' ]);

            $filename = "$rel_name.php";

            foreach($mods as $mod) {
                if ($mod != 'application' )  {
                    $basepath = "custom/Extension/modules/$mod/Ext/";
                } else {
                    $basepath = "custom/Extension/application/Ext/";
                }

                foreach (array($filename , "custom" . $filename, $rel_name ."_". $mod. ".php") as $fn) {
                    //remove any vardefs
                    $path = $basepath . "Vardefs/$fn" ;
                    if (file_exists( $path ))
                        rmdir_recursive( $path );

                    //remove any layoutdefs
                    $path = $basepath . "Layoutdefs/$fn" ;
                    if( file_exists( $path ))
                    {
                        rmdir_recursive( $path );
                    }

                    // Remove any wireless layoutdefs
                    $path = $basepath . "WirelessLayoutdefs/$fn";
                    if (file_exists($path)) {
                        rmdir_recursive($path);
                    }
                }
                // remove the subpanel layoutdefs for the relationship
                $subpanelFileNames = array(
                    "{$rel_name}_{$mod}.php",
                    "_overridesubpanel-for-" . strtolower($mod) . "-{$rel_name}.php"
                );
                foreach (glob("{$basepath}/clients/*", GLOB_NOSORT | GLOB_ONLYDIR) AS $path) {
                    foreach (glob($path . "/layouts/subpanels/*.php", GLOB_NOSORT) AS $file) {
                        $baseFile = basename($file);
                        if (in_array($baseFile, $subpanelFileNames)) {
                            unlink($file);
                        }
                    }
                }

                // Need to remove stale dashlets for the Activities relationship
                if (isset($rel_data['relationships'][$rel_name]) && file_exists('modules/'.$mod)) {
                    // Not our relationship
                    $ourRel = $rel_data['relationships'][$rel_name];

                    $globPath = 'modules/'.$mod.'/clients/base/views/*';
                    foreach (glob($globPath, GLOB_NOSORT | GLOB_ONLYDIR) as $viewPath) {
                        $platform = 'base';

                        $view = basename($viewPath);
                        if ($view != 'history' && $view != 'attachments' && $view != 'planned-activities') {
                            continue;
                        }
                        $viewMetaFile = $viewPath."/".$view.".php";
                        if (!file_exists($viewMetaFile)) {
                            continue;
                        }
                        include $viewMetaFile;
                        if (! isset($viewdefs[$ourRel['lhs_module']][$platform]['view'][$view]['tabs'][0]['link'])) {
                            continue;
                        }

                        foreach ($viewdefs[$ourRel['lhs_module']][$platform]['view'][$view]['tabs'] as $viewTab) {
                            if ($viewTab['link'] == $rel_name) {
                                rmdir_recursive(dirname($viewPath));
                            }
                        }

                    }
                }

                // Remove relationship metadata
                $basepath = "custom/Extension/modules/relationships/*/";
                foreach (array($rel_name . "_" . $mod . ".php", $rel_name . "MetaData.php") as $fileName) {
                    foreach (glob($basepath . $fileName, GLOB_NOSORT) as $file) {
                        unlink($file);
                    }
                }
            }

            foreach (array($filename , "custom" . $filename, $rel_name ."_". $mod. ".php") as $fn) {
                // remove the table dictionary extension
                if ( file_exists("custom/Extension/application/Ext/TableDictionary/$fn"))
                    unlink("custom/Extension/application/Ext/TableDictionary/$fn");

                if (file_exists("custom/metadata/{$rel_name}MetaData.php"))
                    unlink( "custom/metadata/{$rel_name}MetaData.php" );
            }

            unset($GLOBALS['dictionary'][$rel_name]);
        }
    }

    function uninstall_relationships($include_studio_relationships = false){
        $relationships = array();

        //Find and remove studio created relationships.
        global $beanList, $beanFiles, $dictionary;
        //Load up the custom relationship definitions.
        if(file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')){
            include('custom/application/Ext/TableDictionary/tabledictionary.ext.php');
        }
        //Find all the relatioships/relate fields involving this module.
        $rels_to_remove = array();
        foreach($beanList as $mod => $bean) {
            //Some modules like cases have a bean name that doesn't match the object name
            $bean = BeanFactory::getObjectName($mod);
            VardefManager::loadVardef($mod, $bean);
            //We can skip modules that are in this package as they will be removed anyhow
            if (!in_array($mod, $this->modulesInPackage) && !empty($dictionary[$bean]) && !empty($dictionary[$bean]['fields']))
            {
                $field_defs = $dictionary[$bean]['fields'];
                foreach($field_defs as $field => $def)
                {
                    //Weed out most fields first
                    if (isset ($def['type']))
                    {
                        //Custom relationships created in the relationship editor
                        if ($def['type'] == "link" && !empty($def['relationship']) && !empty($dictionary[$def['relationship']]))
                        {
                            $rel_name = $def['relationship'];
                            if (isset($dictionary[$rel_name]['relationships'][$rel_name])) {
                                $rel_def = $dictionary[$rel_name]['relationships'][$rel_name];

                                //Check against mods to be removed.
                                foreach ($this->modulesInPackage as $removed_mod) {
                                    if (($rel_def['lhs_module'] == $removed_mod)
                                        || ($rel_def['rhs_module'] == $removed_mod)
                                    ) {
                                        $dictionary[$rel_name]['from_studio'] = true;
                                        $relationships[$rel_name] = $dictionary[$rel_name];
                                    }
                                }
                            }
                        }
                        //Custom "relate" fields created in studio also need to be removed
                        if ($def['type'] == 'relate' && isset($def['module'])) {
                            foreach($this->modulesInPackage as $removed_mod) {
                                if ($def['module'] == $removed_mod)
                                {
                                    $studioMod = new StudioModule ( $mod );
                                    $studioMod->removeFieldFromLayouts( $field );
                                    if (isset($def['custom_module'])) {
                                        $seed = BeanFactory::newBean($mod);
                                        $df = new DynamicField ( $mod ) ;
                                        $df->setup ( $seed ) ;
                                        //Need to load the entire field_meta_data for some field types
                                        $field_obj = $df->getFieldWidget($mod, $field);
                                        $field_obj->delete ( $df ) ;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }



        $this->uninstall_relationship(null, $relationships);

        if(isset($this->installdefs['relationships'])) {
            $relationships = $this->installdefs['relationships'];
            $this->log(translate('LBL_MI_UN_RELATIONSHIPS') );
            foreach($relationships as $relationship)
            {
                // remove the metadata entry
                $filename = basename ( $relationship['meta_data'] );
                $pathname = (file_exists("custom/metadata/$filename")) ? "custom/metadata/$filename" : "metadata/$filename" ;
                if(isset($GLOBALS['mi_remove_tables']) && $GLOBALS['mi_remove_tables'])
                    $this->uninstall_relationship( $pathname );
                if (file_exists($pathname))
                    unlink( $pathname );
            }
        }

        if (file_exists("custom/Extension/application/Ext/TableDictionary/{$this->id_name}.php"))
            unlink("custom/Extension/application/Ext/TableDictionary/{$this->id_name}.php");
        Relationship::delete_cache();
        $this->rebuild_tabledictionary();
    }

    function uninstall($base_dir)
    {
        global $app_strings;
        $total_steps = 5; //min steps with no tasks
        $current_step = 0;
        $this->base_dir = $base_dir;
        $tasks = array(
            'pre_uninstall',
            'uninstall_relationships',
            'uninstall_copy',
            'uninstall_dcactions',
            'uninstall_dashlets',
            'uninstall_connectors',
            'uninstall_layoutfields',
            'uninstall_extensions',
            'uninstall_global_search',
            'uninstall_filters',
            'disable_manifest_logichooks',
            'post_uninstall',
        );
        $total_steps += count($tasks); //now the real number of steps
        if(file_exists($this->base_dir . '/manifest.php')){
            if(!$this->silent){
                $current_step++;
                display_progress_bar('install', $current_step, $total_steps);
                echo '<div id ="displayLoglink" ><a href="#" onclick="toggleDisplay(\'displayLog\')">'.$app_strings['LBL_DISPLAY_LOG'].'</a> </div><div id="displayLog" style="display:none">';
            }

            global $moduleList;
            $data = $this->readManifest();
            extract($data);
            $this->installdefs = $installdefs;
            $this->id_name = $this->installdefs['id'];
            $installed_modules = array();
            if(isset($this->installdefs['beans'])){
                foreach($this->installdefs['beans'] as $bean){

                    $installed_modules[] = $bean['module'];
                    $this->uninstall_user_prefs($bean['module']);
                }
                $this->modulesInPackage = $installed_modules;
                $this->uninstall_beans($installed_modules);
                $this->uninstall_customizations($installed_modules);
                if(!$this->silent){
                    $current_step++;
                    update_progress_bar('install', $total_steps, $total_steps);
                }
            }
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
            }
            foreach($tasks as $task){
                $this->$task();
                $this->reset_file_cache();
                if(!$this->silent){
                    $current_step++;
                    update_progress_bar('install', $current_step, $total_steps);
                }
            }
            if(isset($installdefs['custom_fields']) && (isset($GLOBALS['mi_remove_tables']) && $GLOBALS['mi_remove_tables'])){
                $this->log(translate('LBL_MI_UN_CUSTOMFIELD'));
                $this->uninstall_custom_fields($installdefs['custom_fields']);
            }
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
                echo '</div>';
            }
            //since we are passing $silent = true to rebuildAll() in that method it will set $this->silent = true, so
            //we need to save the setting to set it back after rebuildAll() completes.
            $silentBak = $this->silent;
            $this->rebuild_all(true);
            $this->silent = $silentBak;

            //TY-188, clearing session
            $ACLAllowedModules = getACLAllowedModules(true);

            //#27877, If the request from MB redeploy a custom module , we will not remove the ACL actions for this package.
            if( !isset($_REQUEST['action']) || $_REQUEST['action']!='DeployPackage' ){
                $this->remove_acl_actions();
            }
            //end

            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
                echo '</div>';
            }

            $this->updateSystemTabs('Restore',$installed_modules);

            //clear the unified_search_module.php file
            UnifiedSearchAdvanced::unlinkUnifiedSearchModulesFile();

            // Destroy all metadata caches and rebuild the base metadata. This
            // will cause a small amount of lag on subsequent requests for other
            // clients.
            MetaDataManager::clearAPICache(true, true);
            MetaDataManager::setupMetadata();

            $dict = new ServiceDictionaryRest();
            $dict->buildAllDictionaries();

            $this->log('<br><b>' . translate('LBL_MI_COMPLETE') . '</b>');
            if(!$this->silent){
                update_progress_bar('install', $total_steps, $total_steps);
            }
        }else{
            die("No manifest.php Defined In $this->base_dir/manifest.php");
        }
    }

    function rebuild_languages($languages = array(), $modules = array())
    {
        global $current_language, $app_list_strings, $app_strings;

        foreach ($languages as $language => $value) {
            $this->log(translate('LBL_MI_REBUILDING') . " Language...$language");
            $this->merge_files('Ext/Language/', $language . '.lang.ext.php', $language, false, $modules);
            if (is_array($modules) && !empty($modules)) {
                foreach ($modules as $module) {
                    LanguageManager::clearLanguageCache($module, $language);
                }
            } else {
                LanguageManager::clearLanguageCache(null, $language);
            }
        }
        sugar_cache_reset();

        // since both were cleared out, we need to set the app_strings
        $app_strings = return_application_language($current_language);
        // put actual metadata into global variable as well
        $app_list_strings = return_app_list_strings_language($current_language);
    }

    function rebuild_vardefs($modules = array())
    {
        $this->rebuildExt("Vardefs", 'vardefs.ext.php', $modules);
        if (!empty($modules)) {
            foreach($modules as $module) {
                VardefManager::clearVardef($module);
            }
        } else {
            VardefManager::clearVardef();
        }
        sugar_cache_reset();
    }

    function rebuild_dashletcontainers($modules = array())
    {
        $this->log(translate('LBL_MI_REBUILDING') . " DC Actions...");
        $this->merge_files('Ext/DashletContainer/Containers/', 'dcactions.ext.php', null, null, $modules);
    }

    function rebuild_tabledictionary()
    {
        $this->rebuildExt("TableDictionary", 'tabledictionary.ext.php');
    }

    function rebuild_relationships($changedModules = array())
    {
        SugarRelationshipFactory::rebuildCache($changedModules);
    }

    function remove_acl_actions() {
        global $beanFiles, $beanList, $current_user;
        include('include/modules.php');
        include("modules/ACL/remove_actions.php");
    }

    /**
     * Wrapper call to modules/Administration/RepairIndex.php
     */
    function repair_indices() {
        global $current_user,$beanFiles,$dictionary;
        $this->log(translate('LBL_MI_REPAIR_INDICES'));
        $_REQUEST['silent'] = true; // local var flagging echo'd output in repair script
        $_REQUEST['mode'] = 'execute'; // flag to just go ahead and run the script
        include("modules/Administration/RepairIndex.php");
    }

    /**
     * Rebuilds the extension files found in custom/Extension
     *
     * @param boolean $silent
     * @param array   $modules optional list of modules to update. If $modules is empty, all modules are rebuilt
     */
    function rebuild_all($silent = false, $modules = array())
    {
        if (defined('TEMPLATE_URL')) {
            SugarTemplateUtilities::disableCache();
        }
        $this->silent = $silent;
        global $sugar_config;

        $this->rebuild_languages($sugar_config['languages'], $modules);
        $this->rebuild_extensions($modules);
        $this->rebuild_dashletcontainers($modules);
        // This will be a time consuming process, particularly if $modules is empty
        $this->rebuild_relationships(array_flip($modules));
        $this->rebuild_tabledictionary();
        $this->reset_opcodes();
        sugar_cache_reset();
    }

    function reset_file_cache()
    {
        // check for upgrades
        if(is_callable(array('SugarAutoLoader', 'buildCache'))) {
            // rebuild cache after all changes
            SugarAutoLoader::buildCache();
        }
    }

    /**
     * Merge the new style extensions
     * @param  string $module_path Path to the module's directory.
     */
    public function mergeExtensionFiles($module_path)
    {
        $php_tags = array('<?php', '?>', '<?PHP', '<?');
        $path = "custom/Extension/{$module_path}/Ext/clients";
        $clients = MetaDataFiles::getClients();
        foreach ($clients as $client) {
            foreach (glob("$path/$client/*", GLOB_ONLYDIR|GLOB_NOSORT) as $layout) {
                $layoutType = basename($layout);
                foreach (glob("$layout/*", GLOB_ONLYDIR|GLOB_NOSORT) as $subLayout) {
                    $subLayoutFileName = basename($subLayout);
                    $extension = "<?php\n// WARNING: The contents of this file are auto-generated.\n";
                    $override = array();
                    foreach (glob("$subLayout/*.php") as $file) {
                        $basenameFile = basename($file);
                        if (substr($basenameFile, 0, 9) == '_override') {
                            $override[] = $file;
                        } else {
                            $fileContents = file_get_contents($file);
                            $extension .= "\n". str_replace($php_tags, '', $fileContents);
                        }
                    }
                    foreach($override AS $file) {
                        $fileContents = file_get_contents($file);
                        $extension .= "\n". str_replace($php_tags, '', $fileContents);
                    }
                    $cachePath = "custom/{$module_path}/Ext/clients/{$client}/{$layoutType}/{$subLayoutFileName}";
                    if (!is_dir($cachePath)) {
                        sugar_mkdir($cachePath, null, true);
                    }
                    file_put_contents("{$cachePath}/{$subLayoutFileName}.ext.php", $extension);
                }
            }
        }
    }

    /**
     * Internal function used to merge extensions for a module from both core
     * and custom sources.
     * @param  string $module_path Path to the module's directory.
     * @param  string $ext_path    Path for the extension used.
     * @param  string $name        Name of file to output to.
     * @param  string $filter      Filter for extension filename for languages.
     */
    protected function mergeModuleFiles($module_path, $ext_path, $name, $filter)
    {
        if (stristr($ext_path, '__PH_SUBTYPE__')) {
            $this->mergeExtensionFiles($module_path);
            return;
        } else {
            if ($module_path == 'application') {
                $paths = array($ext_path);
                $cache_path = 'custom/application/' . $ext_path;
            } else {
                $paths = array($module_path . '/' . $ext_path);
                $cache_path = 'custom/' . $module_path . '/' . $ext_path;
            }
            $paths[] = 'custom/Extension' . '/' . $module_path . '/' . $ext_path;
        }

        $files = array();
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $dir = dir($path);
                while (false !== ($entry = $dir->read())) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    $fullpath = SugarAutoLoader::normalizeFilePath("$path/$entry");
                    $filterCheck = empty($filter) || substr_count($entry, $filter) > 0;
                    $isPHPFile = strtolower(substr($entry, -4)) == ".php";
                    if (is_file($fullpath) && $filterCheck && $isPHPFile) {
                        $GLOBALS['log']->debug(__METHOD__ . ": found {$path}{$entry}");
                        $files[] = $fullpath;
                    }
                }
            }
        }

        $this->cacheExtensionFiles($files, $cache_path . '/' . $name);
    }

    /**
     * Saves the contents of the source files to the cache file or removes it
     * depending on the number of the source files
     *
     * @param array $sourceFiles Source file paths
     * @param string $cacheFile Cache file paths
     */
    protected function cacheExtensionFiles(array $sourceFiles, $cacheFile)
    {
        if (count($sourceFiles) > 0) {
            $sourceFiles = $this->sortExtensionFiles($sourceFiles);
            $contents = $this->getExtensionFileContents($sourceFiles);
            $dirName = dirname($cacheFile);
            if (!file_exists($dirName)) {
                mkdir_recursive($dirName, true);
            }
            file_put_contents($cacheFile, $contents);
        } else {
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
        }
    }

    /**
     * Sorts file paths and returns sorted copy
     *
     * @param array $files File paths
     * @return array Sorted file paths
     */
    protected function sortExtensionFiles(array $files)
    {
        return sortExtensionFiles($files);
    }

    /**
     * Returns merged contents of the given files
     *
     * @param array $files Sorted file paths
     * @return string The contents
     */
    protected function getExtensionFileContents($files)
    {
        $contents = "<?php\n// WARNING: The contents of this file are auto-generated.\n?>\n";

        foreach ($files as $path) {
            $file = file_get_contents($path);

            // remove the 1st opening tag <?php, <?PHP or <?
            $replaced = preg_replace('/^\s*<\?(php|PHP)?/', '', $file);

            // replace the closing tag and the trailing whitespace if any
            $replaced = preg_replace('/\?>\s*$/', '', $replaced);

            // each file is merged with the added open and close tags
            $contents .= "<?php\n// Merged from $path\n" . $replaced . "\n?>\n";
        }

        return $contents;
    }

    /**
     * Merges all extension files for a module into one file.
     *
     * This method runs over the list of all modules already installed in the
     * modules directory. For each module, it reads the contents of every file
     * within custom/Extension/modules/<module>/$path, reading files that end
     * with "_override" last. It then concatenates those files, copying the
     * result to custom/application/<path>/<file>.
     *
     * @param  string  $path Path for extension.
     * @param  string  $name Filename for extension.
     * @param  string  $filter Filter extension file name. Useful for lang.
     * @param  boolean $application If true, process only application wide exts.
     * @param  array   $modules optional list of list of modules to rebuild files for.
     *                 If no list is specified, all modules are rebuilt.
     */
    public function merge_files($path, $name, $filter = '', $application = false, $modules = array())
    {
        if (!$application) {
            $GLOBALS['log']->debug(__METHOD__ . ": merging module extensions in custom/Extension/modules/<module>/$path to custom/modules/<module>/$path$name");
            if (empty($modules)) {
                $modules = $this->modules;
            }
            foreach ($modules as $module) {
                $module_path = "modules/" . $module;
                $this->mergeModuleFiles($module_path, $path, $name, $filter);
            }
        }

        $GLOBALS['log']->debug(__METHOD__ . ": merging application extensions for $name in $path");
        // Now process the application-wide extensions.
        $this->mergeModuleFiles('application', $path, $name, $filter);
    }

    function install_modules()
    {
        // Some processes later in the installation run need the newest members of
        // these arrays when it comes up
        global $beanList, $beanFiles, $moduleList;

        $this->installed_modules = array();
        $this->tab_modules = array();
        if(isset($this->installdefs['beans'])){
            $str = "<?php \n //WARNING: The contents of this file are auto-generated\n";
            foreach($this->installdefs['beans'] as $bean){
                if(!empty($bean['module']) && !empty($bean['class']) && !empty($bean['path'])){
                    $module = $bean['module'];
                    $class = $bean['class'];
                    $path = $bean['path'];

                    // Handle the globals. This will ultimately be rerun after
                    // several other tasks, but some of the relationship installation
                    // needs this next
                    $beanList[$module] = $class;
                    $beanFiles[$class] = $path;

                    $str .= "\$beanList['$module'] = '$class';\n";
                    $str .= "\$beanFiles['$class'] = '$path';\n";
                    if($bean['tab']){
                        // Add this module to the moduleList array
                        $moduleList[] = $module;
                        $str .= "\$moduleList[] = '$module';\n";
                        $this->install_user_prefs($module, empty($bean['hide_by_default']));
                        $this->tab_modules[] = $module;
                    }else{
                        $str .= "\$modules_exempt_from_availability_check['$module'] = '$module';\n";
                        $str .= "\$report_include_modules['$module'] = '$module';\n";
                        $str .= "\$modInvisList[] = '$module';\n";
                    }
                    $this->installed_modules[] = $module;
                }else{
                    $errors[] = 'Bean array not well defined.';
                    $this->abort($errors);
                }
            }
            $str.= "\n?>";
            if(!file_exists("custom/Extension/application/Ext/Include")){
                mkdir_recursive("custom/Extension/application/Ext/Include", true);
            }
            file_put_contents("custom/Extension/application/Ext/Include/{$this->id_name}.php", $str);
        }
    }

    /*
     * ModuleInstaller->install_beans runs through the list of beans given, instantiates each bean, calls bean->create_tables, and then calls SugarBean::createRelationshipMeta for the
     * bean/module.
     */
    function install_beans($beans){
        foreach($beans as $bean){
            // This forces new beans to refresh their vardefs because at this
            // point the global dictionary for this object may be set with just
            // relationship fields.
            $rv = isset($GLOBALS['reload_vardefs']) ? $GLOBALS['reload_vardefs'] : null;
            $dm = isset($_SESSION['developerMode']) ? $_SESSION['developerMode'] : null;
            $GLOBALS['reload_vardefs'] = true;
            $_SESSION['developerMode'] = true;

            $this->log( translate('LBL_MI_IN_BEAN') . " $bean");
            $mod = BeanFactory::newBean($bean);
            if(!empty($mod) && $mod instanceof SugarBean && empty($mod->disable_vardefs)) { //#30273
                $GLOBALS['log']->debug( "Creating Tables Bean : $bean");
                $mod->create_tables();
            }

            // Return state. Null values essentially unset what wasn't set before
            $GLOBALS['reload_vardefs'] = $rv;
            $_SESSION['developerMode'] = $dm;
        }
    }

    function uninstall_beans($beans){
        foreach($beans as $bean){
            $this->log( translate('LBL_MI_UN_BEAN') . " $bean");
            $mod = BeanFactory::newBean($bean);
            if(!empty($mod) && $mod instanceof SugarBean) {
                $GLOBALS['log']->debug( "Drop Tables : $bean");
                if(isset($GLOBALS['mi_remove_tables']) && $GLOBALS['mi_remove_tables']) {
                    // remove custom fields before dropping tables
                    // in order to let DynamicField drop custom columns first
                    $studioModule = StudioModuleFactory::getStudioModule($bean);
                    $studioModule->removeCustomFields();
                    $mod->drop_tables();
                }
            }
        }
    }

    /**
     * Remove any customizations made within Studio while the module was installed.
     */
    function uninstall_customizations($beans){
        foreach($beans as $bean){
            $dirs = array(
                'custom/modules/' . $bean,
                'custom/Extension/modules/' . $bean,
                'custom/working/modules/' . $bean
            );
            foreach($dirs as $dir)
            {
                if(is_dir($dir)){
                    rmdir_recursive($dir);
                }
            }
        }
    }

    function log($str){
        $GLOBALS['log']->debug('ModuleInstaller:'. $str);
        if(!$this->silent){
            echo $str . '<br>';
        }
    }

    /* BEGIN - RESTORE POINT - by MR. MILK August 31, 2005 02:15:18 PM 	*/
    function copy_recursive_with_backup( $source, $dest, $backup_path, $uninstall=false ) {
        if(is_file($source)) {
            if($uninstall) {
                $GLOBALS['log']->debug("Restoring ... " . $source.  " to " .$dest );
                if(copy( $source, $dest)) {
                    if(is_writable($dest))
                        sugar_touch( $dest, filemtime($source) );
                    return(unlink($source));
                }
                else {
                    $GLOBALS['log']->debug( "Can't restore file: " . $source );
                    return true;
                }
            }
            else {
                if(file_exists($dest)) {
                    $rest = clean_path($backup_path."/$dest");
                    if( !is_dir(dirname($rest)) )
                        mkdir_recursive(dirname($rest), true);

                    $GLOBALS['log']->debug("Backup ... " . $dest.  " to " .$rest );
                    if(copy( $dest, $rest)) {
                        if(is_writable($rest))
                            sugar_touch( $rest, filemtime($dest) );
                    }
                    else {
                        $GLOBALS['log']->debug( "Can't backup file: " . $dest );
                    }
                }
                return( copy( $source, $dest ) );
            }
        }
        elseif(!is_dir($source)) {
            if($uninstall) {
                if(is_file($dest))
                    return(unlink($dest));
                else {
                    //don't do anything we already cleaned up the files using uninstall_new_files
                    return true;
                }
            }
            else
                return false;
        }

        if( !is_dir($dest) && !$uninstall){
            sugar_mkdir( $dest );
        }

        $status = true;

        $d = dir( $source );
        while( $f = $d->read() ){
            if( $f == "." || $f == ".." ){
                continue;
            }
            $status &= $this->copy_recursive_with_backup( "$source/$f", "$dest/$f", $backup_path, $uninstall );
        }
        $d->close();
        return( $status );
    }

    private function dir_get_files($path, $base_path){
        $files = array();
        if(!is_dir($path))return $files;
        $d = dir($path);
        while ($e = $d->read()){
            //ignore invisible files . .. ._MACOSX
            if(substr($e, 0, 1) == '.')continue;
            if(is_file($path . '/' . $e))$files[str_replace($base_path , '', $path . '/' . $e)] = str_replace($base_path , '', $path . '/' . $e);
            if(is_dir($path . '/' . $e))$files = array_merge($files, $this->dir_get_files($path . '/' . $e, $base_path));
        }
        $d->close();
        return $files;

    }

    private function dir_file_count($path){
        //if its a file then it has at least 1 file in the directory
        if(is_file($path)) return 1;
        if(!is_dir($path)) return 0;
        $d = dir($path);
        $count = 0;
        while ($e = $d->read()){
            //ignore invisible files . .. ._MACOSX
            if(substr($e, 0, 1) == '.')continue;
            if(is_file($path . '/' . $e))$count++;
            if(is_dir($path . '/' . $e))$count += $this->dir_file_count($path . '/' . $e);
        }
        $d->close();
        return $count;


    }
    /* END - RESTORE POINT - by MR. MILK August 31, 2005 02:15:34 PM */


    /**
     * Static function which allows a module developer to abort their progress, pass in an array of errors and
     * redirect back to the main module loader page
     *
     * @param errors	an array of error messages which will be displayed on the
     * 					main module loader page once it is loaded.
     */
    function abort($errors = array()){
        //set the errors onto the session so we can display them one the moduler loader page loads
        $_SESSION['MODULEINSTALLER_ERRORS'] = $errors;
        echo '<META HTTP-EQUIV="Refresh" content="0;url=index.php?module=Administration&action=UpgradeWizard&view=module">';
        die();
    }

    /**
     * Return the set of errors stored in the SESSION
     *
     * @return an array of errors
     */
    function getErrors(){
        if(!empty($_SESSION['MODULEINSTALLER_ERRORS'])){
            $errors = $_SESSION['MODULEINSTALLER_ERRORS'];
            unset($_SESSION['MODULEINSTALLER_ERRORS']);
            return $errors;
        }
        else
            return null;
    }

    /*
     * Add any fields to the DetailView and EditView of the appropriate modules
     * Only add into deployed modules, as addFieldsToUndeployedLayouts has done this already for undeployed modules (and the admin might have edited the layouts already)
     * @param array $layoutAdditions  An array of module => fieldname
     * return null
     */
    function addFieldsToLayout($layoutAdditions) {
        // these modules either lack editviews/detailviews or use custom mechanisms for the editview/detailview.
        // In either case, we don't want to attempt to add a relate field to them
        // would be better if GridLayoutMetaDataParser could handle this gracefully, so we don't have to maintain this list here
        $invalidModules = array ( 'emails' ) ;

        foreach ( $layoutAdditions as $deployedModuleName => $fieldName )
        {
            if ( ! in_array( strtolower ( $deployedModuleName ) , $invalidModules ) )
            {
                // Handle decision making on views for BWC/non-BWC modules
                if (isModuleBWC($deployedModuleName)) {
                    $views = array(MB_EDITVIEW, MB_DETAILVIEW);
                } else {
                    $views = array(MB_RECORDVIEW);
                }

                foreach($views as $view) {
                    $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . ": adding $fieldName to $view layout for module $deployedModuleName" ) ;
                    $parser = ParserFactory::getParser($view, $deployedModuleName);
                    $parser->addField(array('name' => $fieldName));
                    $parser->handleSave(false);
                }
            }
        }

    }

    function removeFieldsFromLayout($layoutAdditions) {
        // these modules either lack editviews/detailviews or use custom mechanisms for the editview/detailview.
        // In either case, we don't want to attempt to add a relate field to them
        // would be better if GridLayoutMetaDataParser could handle this gracefully, so we don't have to maintain this list here
        $invalidModules = array ( 'emails' ) ;

        foreach ( $layoutAdditions as $deployedModuleName => $fieldName )
        {
            if ( ! in_array( strtolower ( $deployedModuleName ) , $invalidModules ) )
            {
                // Handle decision making on views for BWC/non-BWC modules
                if (isModuleBWC($deployedModuleName)) {
                    $views = array(MB_EDITVIEW, MB_DETAILVIEW);
                } else {
                    $views = array(MB_RECORDVIEW);
                }

                foreach($views as $view) {
                    $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . ": adding $fieldName to $view layout for module $deployedModuleName" ) ;
                    $parser = ParserFactory::getParser($view, $deployedModuleName);
                    $parser->removeField ( $fieldName ) ;
                    $parser->handleSave ( false ) ;
                }
            }
        }

    }

    ///////////////////
    //********** DISABLE/ENABLE FUNCTIONS
    ///////////////////
    function enable($base_dir, $is_upgrade = false, $previous_version = ''){
        global $app_strings;
        $this->base_dir = $base_dir;
        $total_steps = 3; //minimum number of steps with no tasks
        $current_step = 0;
        $tasks = array(
            'enable_copy',
            'enable_dashlets',
            'enable_relationships',
            'enable_extensions',
            'enable_global_search',
            'enable_manifest_logichooks',
            'install_layoutfields',
            'reset_opcodes',
            'reset_file_cache',
        );
        $total_steps += count($tasks);
        if(file_exists($this->base_dir . '/manifest.php')){
            if(!$this->silent){
                $current_step++;
                display_progress_bar('install', $current_step, $total_steps);
                echo '<div id ="displayLoglink" ><a href="#" onclick="toggleDisplay(\'displayLog\')">'.$app_strings['LBL_DISPLAY_LOG'].'</a> </div><div id="displayLog" style="display:none">';
            }

            $data = $this->readManifest();
            extract($data);
            if($is_upgrade && !empty($previous_version)){
                //check if the upgrade path exists
                if(!empty($upgrade_manifest)){
                    if(!empty($upgrade_manifest['upgrade_paths'])){
                        if(!empty($upgrade_manifest['upgrade_paths'][$previous_version])){
                            $installdefs = $upgrade_manifest['upgrade_paths'][$previous_version];
                        }else{
                            $errors[] = 'No Upgrade Path Found in manifest.';
                            $this->abort($errors);
                        }//fi
                    }//fi
                }//fi
            }//fi
            $this->id_name = $installdefs['id'];
            $this->installdefs = $installdefs;
            $installed_modules = $this->getInstalledModules();
            $this->modulesInPackage = $installed_modules;
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
            }

            foreach($tasks as $task){
                $this->$task();
                if(!$this->silent){
                    $current_step++;
                    update_progress_bar('install', $current_step, $total_steps);
                }
            }

            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
                echo '</div>';
            }
            $this->updateSystemTabs('Add',$installed_modules);
            $GLOBALS['log']->debug('Complete');

        }else{
            die("No \$installdefs Defined In $this->base_dir/manifest.php");
        }

    }
    function disable($base_dir){
        global $app_strings;
        $total_steps = 3; //min steps with no tasks
        $current_step = 0;
        $this->base_dir = $base_dir;
        $tasks = array(
            'disable_copy',
            'disable_dashlets',
            'disable_relationships',
            'disable_extensions',
            'disable_global_search',
            'disable_manifest_logichooks',
            'uninstall_layoutfields',
            'disable_module',
            'reset_opcodes',
            'reset_file_cache',
        );
        $total_steps += count($tasks); //now the real number of steps
        if(file_exists($this->base_dir . '/manifest.php')){
            if(!$this->silent){
                $current_step++;
                display_progress_bar('install', $current_step, $total_steps);
                echo '<div id ="displayLoglink" ><a href="#" onclick="toggleDisplay(\'displayLog\')">'.$app_strings['LBL_DISPLAY_LOG'].'</a> </div><div id="displayLog" style="display:none">';
            }

            $data = $this->readManifest();
            extract($data);
            $this->installdefs = $installdefs;
            $this->id_name = $this->installdefs['id'];
            $installed_modules = $this->getInstalledModules();
            $this->modulesInPackage = $installed_modules;
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
            }
            foreach($tasks as $task){
                $this->$task();
                if(!$this->silent){
                    $current_step++;
                    update_progress_bar('install', $current_step, $total_steps);
                }
            }
            if(!$this->silent){
                $current_step++;
                update_progress_bar('install', $current_step, $total_steps);
                echo '</div>';
            }
            $this->updateSystemTabs('Restore',$installed_modules);

        }else{
            die("No manifest.php Defined In $this->base_dir/manifest.php");
        }
    }

    /**
     * Remove module from module list
     */
    protected function disable_module()
    {
        global $moduleList;
        foreach ($this->modulesInPackage as $module) {
            unset($moduleList[array_search($module, $moduleList)]);
        }
    }

    function enable_vardef($to_module)
    {
        $this->enableExt("vardefs", "Vardefs", $to_module);
    }

    function enable_layoutdef($to_module)
    {
        $this->enableExt("layoutdefs", "Layoutdefs", $to_module);
    }

    function enable_relationships(){
        $rebuild = false;
        if(isset($this->installdefs['relationships'])){
            $str = "<?php \n //WARNING: The contents of this file are auto-generated\n";
            $save_table_dictionary = false;
            foreach($this->installdefs['relationships'] as $relationship){
                $filename = basename($relationship['meta_data']);

                $save_table_dictionary  = true;
                $str .= "include_once('metadata/$filename');\n";
                if (empty($relationship['module']))
                    continue;

                if(!empty($relationship['module_vardefs'])){
                    $this->enable_vardef($relationship['module']);
                }
                if(!empty($relationship['module_layoutdefs'])){
                    $this->enable_layoutdef($relationship['module']);
                }
            }
            $rebuild = true;
            if ($save_table_dictionary) {
                $this->enableMetadataFile(
                    'custom/Extension/application/Ext/TableDictionary/',
                    $this->id_name . '.php',
                    true
                );
            }
        }
        $studioRelationships = $this->findDisabledStudioRelationships();
        if ($studioRelationships) {
            $rebuild = true;
            $save_table_dictionary = true;
            foreach ($studioRelationships as $relName => $relationship) {
                $this->enableRelationship($relName, $relationship, true);
            }
        }
        if ($rebuild) {
            $this->rebuild_vardefs();
            $this->rebuild_layoutdefs();
            if ($save_table_dictionary) {
                $this->rebuild_tabledictionary();
            }
            SugarRelationshipFactory::deleteCache();
        }
    }

    protected function disable_relationships()
    {
        $rebuild = false;
        if(isset($this->installdefs['relationships'])){
            foreach($this->installdefs['relationships'] as $relationship){
                $filename = basename($relationship['meta_data']);
                $relName = substr($filename, -12) == "MetaData.php" ? substr($filename,0,strlen($filename) - 12) : "";
                if (empty($relationship['module']) && empty($relName))
                    continue;

                //remove the vardefs
                if (empty($relName))
                    $path = 'custom/Extension/modules/' . $relationship['module']. '/Ext/Vardefs';
                if(!empty($relationship['module']) && $relationship['module'] == 'application'){
                    $path ='custom/Extension/' . $relationship['module']. '/Ext/Vardefs';
                }
                if (!empty($relationship['module_vardefs'])) {
                    $this->enableMetadataFile($path . DIRECTORY_SEPARATOR, $this->id_name . '.php', false);
                }

                // disable the layoutdefs
                if (!empty($relationship['module']) && !empty($relationship['module_layoutdefs'])) {
                    $path = 'custom/Extension/modules/' . $relationship['module']. '/Ext/Layoutdefs/';
                    if ($relationship['module'] == 'application') {
                        $path ='custom/Extension/' . $relationship['module']. '/Ext/Layoutdefs/';
                    }
                    $this->enableMetadataFile($path, $this->id_name . '.php', false);
                }
            }
            $this->enableMetadataFile(
                'custom/Extension/application/Ext/TableDictionary/',
                $this->id_name . '.php',
                false
            );
            $rebuild = true;
        }

        $studioRelationships = $this->findStudioRelationships();
        if ($studioRelationships) {
            foreach ($studioRelationships as $relName => $relationship) {
                $this->enableRelationship($relName, $relationship, false);
            }
            $rebuild = true;
        }

        if ($rebuild) {
            $this->rebuild_tabledictionary();
            $this->rebuild_vardefs();
            $this->rebuild_layoutdefs();
        }
    }

    /**
     * Enable/disable single studio relationship
     *
     * @param string  $relName      Relationship name
     * @param array   $relationship Relationship definition
     * @param boolean $enable       Enable or disable it
     */
    protected function enableRelationship($relName, $relationship, $enable)
    {
        // check to see if we have any vardef or layoutdef entries to disable
        // - must have a relationship['module'] parameter if we do
        if (!isset($relationship['module'])) {
            $mods = array(
                $relationship['relationships'][$relName]['lhs_module'],
                $relationship['relationships'][$relName]['rhs_module'],
            );
        } else {
            $mods = array($relationship['module']);
        }

        $filename = $relName . '.php';

        foreach ($mods as $mod) {
            if ($mod != 'application') {
                $basepath = "custom/Extension/modules/$mod/Ext/";
            } else {
                $basepath = "custom/Extension/application/Ext/";
            }

            foreach (array($filename , 'custom' . $filename, $relName . '_' . $mod . '.php') as $fn) {
                foreach (array('Vardefs', 'Layoutdefs', 'WirelessLayoutdefs') as $path) {
                    $path = $basepath . $path . DIRECTORY_SEPARATOR;
                    $this->enableMetadataFile($path, $fn, $enable);
                }
            }
            $this->enableMetadataFile('custom/Extension/application/Ext/TableDictionary/', $filename, $enable);
        }
        $this->enableMetadataFile('custom/metadata/', $relName . 'MetaData.php', $enable);
    }

    /**
     * Enable/disable single metadata file
     *
     * @param string  $path     Directory
     * @param string  $filename File name
     * @param boolean $enable   Enable or disable it
     */
    protected function enableMetadataFile($path, $filename, $enable)
    {
        if ($enable) {
            $fromDir = $path . DISABLED_PATH . DIRECTORY_SEPARATOR;
            $toDir = $path;
        } else {
            $fromDir = $path ;
            $toDir = $path . DISABLED_PATH . DIRECTORY_SEPARATOR;
        }
        if (file_exists($fromDir . $filename)) {
            mkdir_recursive($toDir, true);
            rename($fromDir . $filename, $toDir . $filename);
        }
    }

    /**
     * Get relationships to the disabled package modules
     *
     * @return array
     */
    protected function findDisabledStudioRelationships()
    {
        //Find studio created relationships.
        $relationships = array();
        foreach (glob('custom/metadata/' . DISABLED_PATH . '/*.php') as $meta) {
            $dictionary = array();
            include $meta;
            $dictionaryKeys = array_keys($dictionary);
            $relName = $dictionaryKeys[0];
            foreach ($this->modulesInPackage as $module) {
                if (isset($dictionary[$relName]['relationships'][$relName])
                    && (($dictionary[$relName]['relationships'][$relName]['lhs_module'] == $module)
                        || ($dictionary[$relName]['relationships'][$relName]['rhs_module'] == $module))
                ) {
                    $relationships[$relName] = $dictionary[$relName];
                }
            }
        }
        return $relationships;
    }

    /**
     * Get relationships to the package modules created from studio
     *
     * @return array
     */
    protected function findStudioRelationships()
    {
        //Find studio created relationships.
        $relationships = array();

        global $beanList, $dictionary;
        //Load up the custom relationship definitions.
        if (file_exists('custom/application/Ext/TableDictionary/tabledictionary.ext.php')) {
            include 'custom/application/Ext/TableDictionary/tabledictionary.ext.php';
        }
        //Find all the relatioships/relate fields involving this module.
        foreach ($beanList as $mod => $bean) {
            //Some modules like cases have a bean name that doesn't match the object name
            $bean = BeanFactory::getObjectName($mod);
            VardefManager::loadVardef($mod, $bean);
            //We can skip modules that are in this package as they will be removed/disabled anyhow
            if (!in_array($mod, $this->modulesInPackage)
                && !empty($dictionary[$bean])
                && !empty($dictionary[$bean]['fields'])
            ) {
                $field_defs = $dictionary[$bean]['fields'];
                foreach ($field_defs as $def) {
                    //Weed out most fields first
                    //Custom relationships created in the relationship editor
                    if (isset($def['type'])
                        && ($def['type'] == 'link')
                        && !empty($def['relationship'])
                        && !empty($dictionary[$def['relationship']])
                    ) {
                        $relName = $def['relationship'];
                        if (isset($dictionary[$relName]['relationships'][$relName])) {
                            $relDef = $dictionary[$relName]['relationships'][$relName];

                            //Check against mods to be removed/disabled
                            foreach ($this->modulesInPackage as $affectedMod) {
                                if (($relDef['lhs_module'] == $affectedMod)
                                    || ($relDef['rhs_module'] == $affectedMod)
                                ) {
                                    $dictionary[$relName]['from_studio'] = true;
                                    $relationships[$relName] = $dictionary[$relName];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $relationships;
    }

    function enable_dashlets(){
        if(isset($this->installdefs['dashlets'])){
            foreach($this->installdefs['dashlets'] as $cp){
                $cp['from'] = str_replace('<basepath>', $this->base_dir, $cp['from']);
                $path = 'custom/modules/Home/Dashlets/' . $cp['name'] . '/';
                $disabled_path = 'custom/modules/Home/'.DISABLED_PATH.'Dashlets/' . $cp['name'];
                $GLOBALS['log']->debug("Enabling Dashlet " . $cp['name'] . "..." . $cp['from'] );
                if (file_exists($disabled_path))
                {
                    rename($disabled_path,  $path);
                }
            }
            include('modules/Administration/RebuildDashlets.php');

        }
    }

    function disable_dashlets(){
        if(isset($this->installdefs['dashlets'])){
            foreach($this->installdefs['dashlets'] as $cp){
                $path = 'custom/modules/Home/Dashlets/' . $cp['name'];
                $disabled_path = 'custom/modules/Home/'.DISABLED_PATH.'Dashlets/' . $cp['name'];
                $GLOBALS['log']->debug('Disabling ' .$path);
                if (file_exists($path))
                {
                    mkdir_recursive('custom/modules/Home/'.DISABLED_PATH.'Dashlets/', true);
                    rename( $path, $disabled_path);
                }
            }
            include('modules/Administration/RebuildDashlets.php');
        }
    }

    function enable_copy(){
        //copy files back onto file system. first perform md5 check to determine if anything has been modified
        //here we should just go through the files in the -restore directory and copy those back
        if(isset($GLOBALS['mi_overwrite_files']) && $GLOBALS['mi_overwrite_files']){
            if(!empty($this->installdefs['copy'])){
                foreach($this->installdefs['copy'] as $cp){
                    $cp['to'] = clean_path(str_replace('<basepath>', $this->base_dir, $cp['to']));
                    if (file_exists($cp['to'])) {
                        $backup_path = clean_path(remove_file_extension(urldecode(hashToFile($this->validateInstallFile())))."-restore/".$cp['to']);

                        $GLOBALS['log']->debug('ENABLE COPY:: CREATING BACKUP OF: ' . $cp['to']);
                        $this->copy_path($cp['to'], $backup_path);

                        $GLOBALS['log']->debug('ENABLE COPY:: REMOVING: ' . $cp['to']);
                        $cp['from'] = clean_path(str_replace('<basepath>', $this->base_dir, $cp['from']));
                        $this->uninstall_new_files($cp, $backup_path);
                    }
                    $GLOBALS['log']->debug("ENABLE COPY:: FROM: ".$cp['from']. " TO: ".$cp['to']);
                    $this->copy_path($cp['from'], $cp['to']);
                }//rof
            }//fi
        }//fi
    }

    function disable_copy(){
        //when we disable we want to copy the -restore files back into the file system
        //but we should check the version in the module install against the version on the file system
        //if they match then we can copy the file back, but otherwise we should ask the user.

        if(isset($GLOBALS['mi_overwrite_files']) && $GLOBALS['mi_overwrite_files']){
            if(!empty($this->installdefs['copy'])){
                foreach($this->installdefs['copy'] as $cp){
                    $cp['to'] = clean_path(str_replace('<basepath>', $this->base_dir, $cp['to']));
                    $cp['from'] = clean_path(str_replace('<basepath>', $this->base_dir, $cp['from']));
                    $backup_path = clean_path( remove_file_extension(urldecode(hashToFile($this->validateInstallFile())))."-restore/".$cp['to'] ); // bug 16966 tyoung - replaced missing assignment to $backup_path
                    //check if this file exists in the -restore directory
                    $this->uninstall_new_files($cp, $backup_path);
                    if(file_exists($backup_path)){
                        //since the file exists, then we want do an md5 of the install version and the file system version
                        $from = str_replace('<basepath>', $this->base_dir, $cp['from']);

                        //since the files are the same then we can safely move back from the -restore
                        //directory into the file system
                        $GLOBALS['log']->debug("DISABLE COPY:: FROM: ".$backup_path. " TO: ".$cp['to']);
                        $this->copy_path($backup_path, $cp['to']);
                    }//fi
                }//rof
            }//fi
        }//fi
    }

    public function reset_opcodes()
    {
        /* Bug 39354 - added function_exists check. Not optimal fix, but safe nonetheless.
         * This is for the upgrade to 6.1 from pre 6.1, since the utils files haven't been updated to 6.1 when this is called,
         * but this file has been updated to 6.1
         */
        if(function_exists('sugar_clean_opcodes')){
            sugar_clean_opcodes();
        }
    }

    /**
     * BC implementation to provide specific calls to extensions
     */
    public function __call($name, $args)
    {
        $nameparts = explode('_', $name);
        // name is something_something
        if(count($nameparts) == 2 && isset($this->extensions[$nameparts[1]])) {
            $ext = $this->extensions[$nameparts[1]];
            switch($nameparts[0]) {
                case 'enable':
                    return $this->enableExt($ext['section'], $ext['extdir']);
                case 'disable':
                    return $this->disableExt($ext['section'], $ext['extdir']);
                case 'install':
                    return $this->installExt($ext['section'], $ext['extdir']);
                case 'uninstall':
                    return $this->uninstallExt($ext['section'], $ext['extdir']);
                case 'rebuild':
                    return $this->rebuildExt($ext['extdir'], $ext['file']);
            }
        }
        sugar_die("Unknown method ModuleInstaller::$name called");
    }

    /**
     * handles portal config creation
     */
    public static function handlePortalConfig()
    {
        sugar_file_put_contents('portal2/config.js', self::getJSConfig(self::getPortalConfig()));
    }

    /**
     * Get portal configuration
     */
    public static function getPortalConfig()
    {
        $config = SugarConfig::getInstance();

        $portalConfig = array(
            'appId' => 'SupportPortal',
            'appStatus' => 'offline',
            'env' => 'dev',
            'platform' => 'portal',
            'additionalComponents' => array(
                'header' => array(
                    'target' => '#header',
                    'layout' => 'header'
                ),
                'footer' => array(
                    'target' => '#footer',
                    'layout' => 'footer'
                ),
                'drawer' => array(
                    'target' => '#drawers',
                    'layout' => 'drawer'
                ),
            ),
            'alertsEl' => '#alerts',
            'alertAutoCloseDelay' => 2500,
            'serverUrl' => $config->get('site_url') . '/rest/v11_1',
            'siteUrl' => $config->get('site_url'),
            'unsecureRoutes' => array('signup', 'error'),
            'loadCss' => 'url',
            'themeName' => 'default',
            'clientID' => 'support_portal',
            'serverTimeout' => self::getPortalTimeoutValue(),
            'maxSearchQueryResult'=>'5',
            'analytics' => $config->get('analytics_portal', array('enabled' => false)),
        );

        $jsConfig = $config->get('additional_js_config', array());
        $portalConfig = array_merge($portalConfig, $jsConfig);

        return $portalConfig;
    }
    /**
     * Handle base configuration
     */

    public static function handleBaseConfig()
    {
        sugar_file_put_contents(sugar_cached('config.js'), self::getJSConfig(self::getBaseConfig()));
    }

    /**
     * Get base configuration
     */
    public static function getBaseConfig()
    {
        $config = SugarConfig::getInstance();
        $sidecarConfig = array(
            'appId' => 'SugarCRM',
            'env' => 'prod',
            'platform' => 'base',
            'additionalComponents' => array(
                'header' => array(
                    'target' => '#header',
                    'layout' => 'header'
                ),
                'footer' => array(
                    'target' => '#footer',
                    'layout' => 'footer'
                ),
                'drawer' => array(
                    'target' => '#drawers',
                    'layout' => 'drawer'
                ),
                'sweetspot' => array(
                    'target' => '#sweetspot',
                    'layout' => 'sweetspot'
                ),
            ),
            'alertsEl' => '#alerts',
            'alertAutoCloseDelay' => 2500,
            'serverUrl' => 'rest/v11_1',
            'siteUrl' => '',
            'unsecureRoutes' => array('login', 'logout', 'error', 'forgotpassword', 'externalAuthError'),
            'loadCss' => false,
            'themeName' => 'default',
            'clientID' => 'sugar',
            'collapseSubpanels' => $config->get('collapse_subpanels', false),
            'previewEdit' => $config->get('preview_edit', false),
            'serverTimeout' => self::getBaseTimeoutValue(),
            'metadataTypes' => array(
                "currencies",
                "full_module_list",
                "modules_info",
                "hidden_subpanels",
                "jssource",
                "jssource_public",
                "ordered_labels",
                "module_tab_map",
                "modules",
                "relationships",
                "server_info",
                "config",
                "_override_values",
                "filters",
                "logo_url",
                "editable_dropdown_filters",
            ),
            'teamBasedAcl' => $config->get(TeamBasedACLConfigurator::CONFIG_KEY),
            'uniqueKey' => $config->get('unique_key'),
        );

        $jsConfig = $config->get('additional_js_config', array());
        $sidecarConfig = array_merge($sidecarConfig, $jsConfig);

        return $sidecarConfig;
    }

    /**
     * If the portal timeout is configured, use it, otherwise use the base
     * timeout if it's configured, otherwise, fallback to 180 seconds.
     *
     * @return Integer The number of seconds before an API timeout.
     */
    private static function getPortalTimeoutValue()
    {
        $config = SugarConfig::getInstance();
        $timeout = $config->get('portal.api.timeout', self::getBaseTimeoutValue());
        return $timeout;
    }

    /**
     * If the base timeout is configured, use it, otherwise fallback to 180
     * seconds.
     *
     * @return Integer The number of seconds before an API timeout.
     */
    private static function getBaseTimeoutValue()
    {
        $config = SugarConfig::getInstance();
        $timeout = $config->get('api.timeout', 180);
        return $timeout;
    }

    /**
     * Convert config array to JS config for Sidecar
     */
    public static function getJSConfig($config)
    {
        $configString = json_encode($config);
        return '(function(app) {app.augment("config", ' . $configString . ', false);})(SUGAR.App);';
    }

    /**
     * Write out config as Sidecar config file
     */
    public static function writeJSConfig($config, $path) {
        $configString = json_encode($config);
        $JSConfig = '(function(app) {app.augment("config", ' . $configString . ', false);})(SUGAR.App);';
        sugar_file_put_contents($path, $JSConfig);
    }
    /**
     * Update wireless metadata for packages that were created prior to 6.6 but
     * are being installed or deployed in 6.6+
     */
    public function update_wireless_metadata() {
        // If there was a copy to path then we can work it
        if (isset($this->installdefs['copy'])) {
            // Add in Sidecar upgrader after old style metadata changes are brought over
            $sidecarUpgrader = new SidecarMetaDataUpgrader();

            // Let the upgrader know that this is from installation
            $sidecarUpgrader->fromInstallation = true;

            // Turn off writing to log
            $sidecarUpgrader->toggleWriteToLog();

            // Get our files in the $cp['to'] path to upgrade
            foreach($this->installdefs['copy'] as $cp) {
                // Set the files array
                $files = array();

                // Grab the package name
                $package = basename($cp['to']);

                // Set the dir to get the files from
                $modulesDir = $cp['to'] . '/modules/';

                // If we have the modules directory
                if (is_dir($modulesDir)) {
                    // Get the modules from inside the path
                    $dirs = glob($modulesDir . '*', GLOB_ONLYDIR);
                    if (!empty($dirs)) {
                        foreach ($dirs as $dirpath) {
                            // Get the module to list it in case it needs to be upgraded
                            $module = basename($dirpath);

                            // Get the metadata directory
                            $metadatadir = "$dirpath/metadata/";

                            // We only want to do this if there is a metadata dir
                            // and there isn't already a clients dir
                            if (is_dir($metadatadir) && !is_dir("$dirpath/clients")) {
                                // Get our upgrade files
                                $files = array_merge($files, $sidecarUpgrader->getUpgradeableFilesInPath($metadatadir, $module, 'wireless', 'base', $package, false));
                            }
                        }
                    }
                }

                // Upgrade them
                foreach ($files as $file) {
                    // Get the appropriate upgrade class name for this view type
                    $class = $sidecarUpgrader->getUpgraderClass($file['viewtype']);
                    if ($class) {
                        if (!class_exists($class, false)) {
                            $classfile = $class . '.php';
                            require_once "modules/UpgradeWizard/SidecarUpdate/$classfile";
                        }

                        $upgrader = new $class($sidecarUpgrader, $file);

                        // Let the upgrader do its thing
                        $upgrader->upgrade();
                    }
                }
            }
        }
    }

    /**
     * Refreshes roles after installation of a module(s). This mimics the call
     * to Repair Roles from the admin main menu.
     */
    protected function updateRoles()
    {
        // Try to maintain state of the request since we need to modify it
        $uw = isset($_REQUEST['upgradeWizard']) ? $_REQUEST['upgradeWizard'] : null;

        // This tells the install actions script to NOT output anything
        $_REQUEST['upgradeWizard'] = true;
        require 'modules/ACL/install_actions.php';

        // Reset the state of the request
        $_REQUEST['upgradeWizard'] = $uw;
    }


    public function install_client_files()
    {
        if (!isset($this->installdefs['clientfiles'])
            || !is_array($this->installdefs['clientfiles'])) {
            return;
        }

        // clientfiles contains five identical lists of files for each of the
        // activities relationships, this condenses them so we only copy once.
        $copyList = array();
        foreach ($this->installdefs['clientfiles'] as $outer) {
            foreach ($outer as $to => $from) {
                $copyList[$to] = $from;
            }
        }

        foreach ($copyList as $to => $from) {
            $contents = file_get_contents($from);
            SugarAutoLoader::ensureDir(dirname($to));
            file_put_contents($to, $contents);
        }
    }

    /**
     * Refreshes vardefs for modules that are affected by a change during installation.
     *
     * @param array $modules List of modules to refresh vardefs for
     */
    protected function clearAffectedVardefsCache($modules = array())
    {
        foreach ($modules as $module) {
            $obj = BeanFactory::getObjectName($module);
            VardefManager::refreshVardefs($module, $obj);
        }
    }

    /**
     * Runs cleanup after installation to make sure various extension section are
     * fresh for the next steps in the installation
     *
     * This is called in the {@see install} method prior to installing new beans.
     *
     * @return [type] [description]
     */
    protected function runInstallCleanup()
    {
        foreach ($this->affectedModules as $section => $modules) {
            $method = 'clearAffected' . $section . 'Cache';
            if (method_exists($this, $method)) {
                $this->$method($modules);
            }
        }
    }

    /**
     * Updates systems tabs
     *
     * @param string $action The action to take
     * @param array $installed_modules The list of modules to add for this action
     */
    protected function updateSystemTabs($action, $installed_modules)
    {
        $controller = new TabController();
        $isSystemTabsInDB = $controller->is_system_tabs_in_db();
        if ($isSystemTabsInDB && !empty($installed_modules)) {
            switch ($action) {
                case 'Restore':
                    $currentTabs = $controller->get_system_tabs();
                    foreach ($installed_modules as $module) {
                        if (in_array($module, $currentTabs)) {
                            unset($currentTabs[$module]);
                        }
                    }
                    $controller->set_system_tabs($currentTabs);
                    break;
                case 'Add':
                    $currentTabs = $controller->get_system_tabs();
                    foreach ($installed_modules as $module) {
                        if (!in_array($module, $currentTabs)) {
                            $currentTabs[$module] = $module;
                        }
                    }
                    $controller->set_system_tabs($currentTabs);
                    break;
            }
        }
    }

    /**
     * Get module directories
     * @return multitype:string mixed
     */
    public static function getModuleDirs()
    {
        // For now, we define module dirs as:
        // 1. Any directory directly under modules/
        // 2. Any directory like modules/Something/Other if modules/Something/Other/vardefs.php exists
        $modules = array();
        foreach (new FilesystemIterator('modules', FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS) as $name => $fileInfo) {
            if (!$fileInfo->isDir()) {
                continue;
            }
            $modules[] = $name;
            foreach (new FilesystemIterator("modules/$name", FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS) as $subname => $fileInfo) {
                if (!$fileInfo->isDir()) {
                    continue;
                }
                if (file_exists("modules/$name/$subname/vardefs.php")) {
                    $modules[] = "$name/$subname";
                }
            }
        }
        return $modules;
    }

    /**
     * Installs dropdown filters extension
     */
    protected function install_dropdown_filters()
    {
        if (!isset($this->installdefs['dropdown_filters'])) {
            return;
        }

        foreach ($this->installdefs['dropdown_filters'] as $item) {
            $from = str_replace('<basepath>', $this->base_dir, $item['from']);
            $to = $this->getDropdownFilterPath($item);
            $GLOBALS['log']->debug("Installing dropdown_filters from $from");
            $dirName = dirname($to);
            if (!file_exists($dirName)) {
                mkdir_recursive($dirName, true);
            }
            copy($from, $to);
        }
    }

    /**
     * Uninstalls dropdown filters extension
     */
    protected function uninstall_dropdown_filters()
    {
        if (!isset($this->installdefs['dropdown_filters'])) {
            return;
        }

        foreach ($this->installdefs['dropdown_filters'] as $item) {
            $from = str_replace('<basepath>', $this->base_dir, $item['from']);
            $GLOBALS['log']->debug("Uninstalling dropdown_filters from $from");
            $path = $this->getDropdownFilterPath($item);
            if (file_exists($path)) {
                unlink($path);
            } else {
                $disabledPath = $this->getDropdownFilterPath($item, true);
                if (file_exists($disabledPath)) {
                    unlink($disabledPath);
                }
            }
        }
    }

    /**
     * Disables dropdown filters extension
     */
    protected function disable_dropdown_filters()
    {
        if (!isset($this->installdefs['dropdown_filters'])) {
            return;
        }

        foreach ($this->installdefs['dropdown_filters'] as $item) {
            $from = str_replace('<basepath>', $this->base_dir, $item['from']);
            $GLOBALS['log']->debug("Disabling dropdown_filters from $from");
            $path = $this->getDropdownFilterPath($item);
            if (file_exists($path)) {
                $disabledPath = $this->getDropdownFilterPath($item, true);
                $dirName = dirname($disabledPath);
                mkdir_recursive($dirName, true);
                rename($path, $disabledPath);
            }
        }
    }

    /**
     * Enables dropdown filters extension
     */
    protected function enable_dropdown_filters()
    {
        if (!isset($this->installdefs['dropdown_filters'])) {
            return;
        }

        foreach ($this->installdefs['dropdown_filters'] as $item) {
            $from = str_replace('<basepath>', $this->base_dir, $item['from']);
            $GLOBALS['log']->debug("Enabling dropdown_filters from $from");
            $disabledPath = $this->getDropdownFilterPath($item, true);
            if (file_exists($disabledPath)) {
                $path = $this->getDropdownFilterPath($item);
                $dirName = dirname($path);
                mkdir_recursive($dirName, true);
                rename($disabledPath, $path);
            }
        }
    }

    /**
     * Rebuilds cache files for dropdown filters extension
     */
    protected function rebuild_dropdown_filters()
    {
        $roles = ACLRole::getAllRoles();
        foreach ($roles as $role) {
            $this->rebuild_role_dropdown_filters($role->id);
        }
    }

    /**
     * Rebuilds cache files for dropdown filters extension for the given role
     *
     * @param string $role Role ID
     */
    public function rebuild_role_dropdown_filters($role)
    {
        $baseDir = 'custom/Extension/application/Ext/DropdownFilters/roles';
        $roleDir = $baseDir . '/' . $role;
        $files = array();
        if (is_dir($roleDir)) {
            $it = new FilesystemIterator($roleDir);
            foreach ($it as $file) {
                if ($file->isFile()) {
                    $files[] = $file->getPathname();
                }
            }
        }
        $cacheFile = 'custom/application/Ext/DropdownFilters/roles/' . $role . '/dropdownfilters.ext.php';
        $this->cacheExtensionFiles($files, $cacheFile);
    }

    /**
     * Return extension file path
     *
     * @param string $item Extension item
     * @param bool $isDisabled Whether the extension should be disabled
     *
     * @return string
     */
    protected function getDropdownFilterPath($item, $isDisabled = false)
    {
        $basePath = 'custom';
        if ($isDisabled) {
            $basePath .= '/' . DISABLED_PATH;
        }
        $path = str_replace('<basepath>/SugarModules', $basePath, $item['from']);
        $path = $this->patchPath($path);

        return $path;
    }

    /**
     * Reads package manifest
     *
     * @return array Scope variables initialized in manifest file
     */
    protected function readManifest()
    {
        $installdefs = array();
        require FileLoader::validateFilePath($this->base_dir . '/manifest.php');
        $installdefs = $this->patchInstallDefs($installdefs);
        return compact('manifest', 'installdefs');
    }

    /**
     * Patches package installation definitions
     *
     * @param array $installdefs Original installation definitions
     * @return array Patched installation definitions
     */
    public function patchInstallDefs(array $installdefs)
    {
        foreach ($installdefs as $sectionName => $section) {
            if (is_array($section)) {
                foreach ($section as $i => $def) {
                    if (isset($def['to'])) {
                        $patched = $this->patchPath($def['to']);
                        if ($patched !== null) {
                            $installdefs[$sectionName][$i]['to'] = $patched;
                        } else {
                            unset($installdefs[$sectionName][$i]);
                        }
                    }
                }
            }
        }

        return $installdefs;
    }

    /**
     * Patches destination path according to the current patch specification.
     *
     * @param string $path Destination path
     * @return string|null Patched path or NULL if the file shouldn't be copied
     */
    protected function patchPath($path)
    {
        foreach ($this->patch as $param => $map) {
            foreach ($map as $search => $replace) {
                if ($search == $replace) {
                    continue;
                }

                $search = $param . '/' . $search;
                if ($replace) {
                    // if replacement is not empty, try to replace the value
                    $patched = str_replace($search, $param . '/' . $replace, $path, $count);
                    if ($count > 0) {
                        if (isValidCopyPath($patched)) {
                            return $patched;
                        }
                        return null;
                    }
                } else {
                    // if replacement is empty, it means that the file should be excluded from the package
                    if (strpos($path, $search) !== false) {
                        return null;
                    }
                }
            }
        }

        return $path;
    }

    /**
     * Add Elasticsearch mapping so records sync with elastic
     */
    public function setup_elastic_mapping()
    {
        if (!isset($this->installdefs['beans'])) {
            return;
        }
        foreach ($this->installdefs['beans'] as $beanDefs) {
            $modules[] = $beanDefs['module'];
        }
        $engine = SearchEngine::getInstance()->getEngine();
        if (isset($engine) && isset($modules)) {
            $engine->addMappings($modules);
        }
    }

    /*
     * Returns a valid input for the install_file
     * paramete in the $_REQUEST sugperglobal
     *
     * @return mixed
     */
    protected function validateInstallFile()
    {
        $request = InputValidation::getService();
        // $_REQUEST['install_file'] is a hash as per fileToHash/hashToFile
        $installFile = $request->getValidInputRequest('install_file');
        return $installFile;
    }

    /**
     * Get package modules
     *
     * @return array
     */
    protected function getInstalledModules()
    {
        $installedModules = array();
        if (isset($this->installdefs['beans'])) {
            foreach ($this->installdefs['beans'] as $bean) {
                $installedModules[] = $bean['module'];
            }
        }
        return $installedModules;
    }
}
