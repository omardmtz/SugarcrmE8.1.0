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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;
use Sugarcrm\Sugarcrm\Security\Validator\ConstraintBuilder;
use Sugarcrm\Sugarcrm\Security\Validator\Validator;
use Sugarcrm\Sugarcrm\Security\InputValidation\Exception\ViolationException;

require_once 'modules/ModuleBuilder/parsers/constants.php';

class MBPackage{
    var $name;
    var $is_uninstallable = true;
    var $description = '';
    var $modules = array();
    var $date_modified = '';
    var $author = '';
    var $key = '';
    var $readme='';

    /**
     * @var Request
     */
    protected $request;

    /**
     * Flavor compatibility map
     *
     * @var array
     */
    protected static $compatibilityMap = array(
        'PRO'  => array('PRO', 'CORP', 'ENT', 'ULT'),
        'CORP' => array('PRO', 'CORP', 'ENT', 'ULT'),
        'ENT'  => array('ENT', 'ULT'),
        'ULT'  => array('ENT', 'ULT'),
    );

    /**
     * Exportable application level customizations. Key is type for manifest, value is root directory and variable name.
     *
     * @var array
     */
    protected static $appExtensions = array(
        'language' => array(
            'dir' => 'Extension/application/Ext/Language',
            'varName' => 'app_list_strings',
        ),
        'dropdown_filters' => array(
            'dir' => 'Extension/application/Ext/DropdownFilters',
            'varName' => 'role_dropdown_filters',
        ),
    );

    public function __construct($name)
    {
        $this->name = $name;
        $this->request = InputValidation::getService();
        $this->load();
    }

    function loadModules($force=false){
        if(!file_exists(MB_PACKAGE_PATH . '/' . $this->name .'/modules'))return;
        $d = dir(MB_PACKAGE_PATH . '/' . $this->name .'/modules');
        while($e = $d->read()){
            if(substr($e, 0, 1) != '.' && is_dir(MB_PACKAGE_PATH . '/'. $this->name. '/modules/' . $e)){
                $this->getModule($e, $force);
            }
        }
    }

    /**
     * Loads the translated module titles from the selected language into.
     * Will override currently loaded string to reflect undeployed label changes.
     * $app_list_strings
     * @return
     * @param $languge String language identifyer
     */
    function loadModuleTitles($languge = '')
    {
        if (empty($language))
        {
            $language = $GLOBALS['current_language'];
        }
        global $app_list_strings;
        $packLangFilePath = $this->getPackageDir() . "/language/application/" . $language . ".lang.php";
        if (file_exists($packLangFilePath))
        {

            require FileLoader::validateFilePath($packLangFilePath);
        }
    }

    /**
     * @param $name
     * @param bool $force
     * @return MBModule
     */
    function getModule($name, $force=true){
        if(!$force && !empty($this->modules[$name]))
            return $this->modules[$name];

        $path = $this->getPackageDir();
        $this->modules[$name] = new MBModule($name, $path, $this->name, $this->key);

        return $this->modules[$name];
    }

    /**
     * Returns an MBModule by the given full name (package key + module name)
     * if it exists in this package
     *
     * @param string $name
     * @return MBModule
     */
    public function getModuleByFullName($name){
        foreach($this->modules as $mname => $module) {
            if ($this->key . "_" . $mname == $name)
                return $module;
        }
    }

    function deleteModule($name){
        $this->modules[$name]->delete();
        unset($this->modules[$name]);
    }

function getManifest($version_specific = false, $for_export = false){
    global $sugar_flavor;

    //If we are exporting the package, we must ensure a different install key
    $pre = $for_export ? MB_EXPORTPREPEND : "";
    $date = TimeDate::getInstance()->nowDb();
    $time = time();
    $this->description = to_html($this->description);
    $is_uninstallable = ($this->is_uninstallable ? true : false);

    if (isset($sugar_flavor)) {
        if (self::$compatibilityMap[$sugar_flavor]) {
            $flavors = self::$compatibilityMap[$sugar_flavor];
        } else {
            $flavors = array($sugar_flavor);
        }
    } else {
        $flavors = array();
    }

    $version = (!empty($version_specific))?$GLOBALS['sugar_version']:'';

    // Build an array and use var_export to build this file
    $manifest = array(
        'built_in_version' => $GLOBALS['sugar_version'],
        'acceptable_sugar_versions' => array($version),
        'acceptable_sugar_flavors' => $flavors,
        'readme' => $this->readme,
        'key' => $this->key,
        'author' => $this->author,
        'description' => $this->description,
        'icon' => '',
        'is_uninstallable' => $is_uninstallable,
        'name' => $pre.$this->name,
        'published_date' => $date,
        'type' => 'module',
        'version' => $time,
        'remove_tables' => 'prompt',
    );



    $header = file_get_contents('modules/ModuleBuilder/MB/header.php');

    return $header."\n// THIS CONTENT IS GENERATED BY MBPackage.php\n".'$manifest = '.var_export_helper($manifest).";\n\n";
/*
    return  <<<EOQ
    $header
    \$manifest = array (
         'acceptable_sugar_versions' =>
          array (
            $version
          ),
          'acceptable_sugar_flavors' =>
          array(
            $flavor
          ),
          'readme'=>'$this->readme',
          'key'=>'$this->key',
          'author' => '$this->author',
          'description' => '$this->description',
          'icon' => '',
          'is_uninstallable' => $is_uninstallable,
          'name' => '$pre$this->name',
          'published_date' => '$date',
          'type' => 'module',
          'version' => '$time',
          'remove_tables' => 'prompt',
          );
EOQ;
*/
}

function buildInstall($path){
    $installdefs = array ('id' => $this->name,
        'beans'=>array(),
        'layoutdefs'=>array(),
        'relationships'=>array(),
    );
    foreach(array_keys($this->modules) as $module){
        $this->modules[$module]->build($path);
        $this->modules[$module]->addInstallDefs($installdefs);
    }
    $this->path = $this->getPackageDir();
    if(file_exists($this->path . '/language')){
        $d= dir($this->path . '/language');
        while($e = $d->read()){
            $lang_path = $this->path .'/language/' . $e;
            if(substr($e, 0, 1) != '.' && is_dir($lang_path)){
                $f = dir($lang_path);
                while($g = $f->read()){
                    if(substr($g, 0, 1) != '.' && is_file($lang_path.'/'. $g)){
                        $lang = substr($g, 0, strpos($g, '.'));
                        $installdefs['language'][] = array(
                        'from'=> '<basepath>/SugarModules/language/'.$e . '/'. $g,
                        'to_module'=> $e,
                        'language'=> $lang
                        );
                    }
                }
            }
        }

        copy_recursive( $this->path . '/language/', $path . '/language/');
    }

    if (file_exists($this->path . '/icons/')) {
        $icon_path = $path . '/../icons/default/images/';
        mkdir_recursive($icon_path);
        copy_recursive($this->path . '/icons/', $icon_path);
        $installdefs['image_dir'] = '<basepath>/icons';
    }

    return "\n".'$installdefs = ' . var_export_helper($installdefs). ';';

}

    function getPackageDir(){
        return MB_PACKAGE_PATH . '/' . $this->name;
    }

    function getBuildDir(){
        return MB_PACKAGE_BUILD . DIRECTORY_SEPARATOR . $this->name;
    }

    function getZipDir(){
        return $this->getPackageDir() . '/zips';
    }


    function load(){
        $path = $this->getPackageDir();
        if(file_exists($path .'/manifest.php')){
            require FileLoader::validateFilePath($path . '/manifest.php');
            if(!empty($manifest)){
                $this->date_modified = $manifest['published_date'];
                $this->is_uninstallable = $manifest['is_uninstallable'];
                $this->author = $manifest['author'];
                $this->key = $manifest['key'];
                $this->description = $manifest['description'];
                if(!empty($manifest['readme']))
                    $this->readme = $manifest['readme'];
            }
        }
        $this->loadModules(true);
    }

    function save(){
        $path = $this->getPackageDir();
        if(mkdir_recursive($path)){
            //Save all the modules when we save a package
            $this->updateModulesMetaData(true);
            sugar_file_put_contents_atomic($path .'/manifest.php', $this->getManifest());
        }
    }

    function build($export=true, $clean = false){
        $this->loadModules();
        require_once('include/utils/zip_utils.php');
        $package_path = $this->getPackageDir();
        $path = $this->getBuildDir() . '/SugarModules';
        if($clean && file_exists($path))rmdir_recursive($path);
        if(mkdir_recursive($path)){

            $manifest = $this->getManifest().$this->buildInstall($path);
            $fp = sugar_fopen($this->getBuildDir() .'/manifest.php', 'w');
            fwrite($fp, $manifest);
            fclose($fp);

        }
        if(file_exists('modules/ModuleBuilder/MB/LICENSE.txt')){
            copy('modules/ModuleBuilder/MB/LICENSE.txt', $this->getBuildDir() . '/LICENSE.txt');
        }else if(file_exists('LICENSE')){
            copy('LICENSE', $this->getBuildDir() . '/LICENSE');
        }
        $date = date('Y_m_d_His');
        $zipDir = $this->getZipDir();
        if(!file_exists($zipDir))mkdir_recursive($zipDir);
        zip_dir($this->getBuildDir(), $zipDir . '/' . $this->name . $date . '.zip');
        if($export){
            header('Location:' . $zipDir. '/'. $this->name. $date. '.zip');
        }
        return array(
            'zip'=>$zipDir. '/'. $this->name. $date. '.zip',
            'manifest'=>$this->getBuildDir(). '/manifest.php',
            'name'=>$this->name. $date,
            );
    }


    function getNodes(){
        $this->loadModules();
        $node = array('name'=>$this->name, 'action'=>'module=ModuleBuilder&action=package&package=' . $this->name, 'children'=>array());
        foreach(array_keys($this->modules) as $module){
            $node['children'][] = $this->modules[$module]->getNodes();
        }
        return $node;
    }

    function populateFromPost(){
        $this->description = trim($this->request->getValidInputRequest('description'));
        $this->author = trim($this->request->getValidInputRequest('author'));
        $this->key = trim($this->request->getValidInputRequest('key'));

        $constraintBuilder = new ConstraintBuilder();
        $constraints = $constraintBuilder->build('Assert\ComponentName');

        $violations = Validator::getService()->validate($this->key, $constraints);
        if (count($violations) > 0) {
            $sugarConfig = \SugarConfig::getInstance();
            // Check softFail mode - enabled by default
            $softFail = $sugarConfig->get('validation.soft_fail', true);
            if (!$softFail) {
                $GLOBALS['log']->fatal("InputValidation: Violation for REQUEST -> key");
                throw new ViolationException(
                    'Violation for REQUEST -> key',
                    $violations
                );
            } else {
                $GLOBALS['log']->warn("InputValidation: Violation for REQUEST -> key");
            }
        }
        $this->readme = trim($this->request->getValidInputRequest('readme'));
    }

    function rename($new_name){
        $old= $this->getPackageDir();
        $this->name = $new_name;
        $new = $this->getPackageDir();
        if(file_exists($new)){
            return false;
        }
        if(rename($old, $new)){
            return true;
        }

        return false;
    }

    function updateModulesMetaData($save=false){
            foreach(array_keys($this->modules) as $module){
                $old_name = $this->modules[$module]->key_name;
            	$this->modules[$module]->key_name = $this->key . '_' . $this->modules[$module]->name;
                $this->modules[$module]->renameRelationships(
                    $this->modules[$module]->getModuleDir(),
                    $old_name,
                    $this->modules[$module]->key_name
                );
                $this->modules[$module]->renameMetaData($this->modules[$module]->getModuleDir(), $old_name);
                $this->modules[$module]->renameLanguageFiles($this->modules[$module]->getModuleDir());
                if($save)$this->modules[$module]->save();
                $this->modules[$module]->cleanupLayout();

            }

    }

    function copy($new_name){
        $old= $this->getPackageDir();

        $count = 0;
        $this->name = $new_name;
        $new= $this->getPackageDir();
        while(file_exists($new)){
            $count++;
            $this->name = $new_name . $count;
            $new= $this->getPackageDir();
        }

        $new = $this->getPackageDir();
        if(copy_recursive($old, $new)){
            $this->updateModulesMetaData();
            return true;
        }
        return false;

    }

    function delete(){
        return rmdir_recursive($this->getPackageDir());
    }


        //creation of the installdefs[] array for the manifest when exporting customizations
    function customBuildInstall($modules, $path, $extensions = array()){
        $installdefs = array ('id' => $this->name, 'relationships' => array());
        foreach (self::$appExtensions as $type => $spec) {
            $include_path = $path . '/SugarModules/' . $spec['dir'];
            $it = $this->getDirectoryIterator($include_path);
            foreach ($it as $file) {
                $subPathName = $it->getSubPathname();
                $def = array(
                    'from'=> '<basepath>/SugarModules/' . $spec['dir'] . '/' . $subPathName,
                    'to_module'=> 'application',
                );
                $baseName = $file->getBasename();
                if ($type == 'language') {
                    $def['language'] = substr($baseName, 0, strpos($baseName, '.'));
                }
                $installdefs[$type][] = $def;
            }
        }

        foreach($modules as $value){
            $custom_module = $this->getModuleCustomizations($value);
            foreach ($custom_module as $va => $_) {
                switch ($va) {
                    case 'language':
                    case 'Ext/Vardefs';
                    case 'Ext/Language';
                        // Old way
                        if ($va === 'language') {
                            $this->getLanguageManifestForModule($value, $installdefs);
                            $this->getCustomFieldsManifestForModule($value, $installdefs);
                        } else {
                            // Build a full path to the Ext directory for the
                            // package module
                            $fullpath = "$path/Extension/modules/$value/Ext/";
                            $paths = array(
                                'Vardefs' => 'getCustomFieldsManifestForModule',
                                'Language' => 'getLanguageManifestForModule',
                            );

                            // Check to make sure that the directories in question
                            // exist and are not empty (like when something might
                            // have been disabled/deleted)
                            foreach ($paths as $pathKey => $pathMethod) {
                                $full = $fullpath . $pathKey;
                                if ($this->isDirectoryExportable($full)) {
                                    $this->$pathMethod($value, $installdefs);
                                }
                            }
                        }
                        break;

                    case 'metadata':
                        $this->getCustomMetadataManifestForModule($value, $installdefs);
                        break;

                    case 'clients':
                        $this->getCustomClientMetadata($value, $installdefs);
                        break;
                }
            }
            $relationshipsMetaFiles = $this->getCustomRelationshipsMetaFilesByModuleName($value, true, true,$modules);
            if($relationshipsMetaFiles)
            {
                foreach ($relationshipsMetaFiles as $file)
                {
                    $installdefs['relationships'][] = array(
                        'meta_data' => str_replace(
                            'custom' . DIRECTORY_SEPARATOR,
                            '<basepath>' . DIRECTORY_SEPARATOR,
                            $file
                        ),
                    );
                }
            }
        }//foreach
        if (is_dir($path . DIRECTORY_SEPARATOR . 'Extension'))
        {
            $this->getExtensionsManifestForPackage($path, $installdefs);
        }

        $roles = $this->extractRoles($installdefs);
        $installdefs['roles'] = $this->getRoleNames($roles);

        return "\n".'$installdefs = ' . var_export_helper($installdefs). ';';
    }

    /**
     * Extracts IDs of ACL roles from package manifest
     *
     * @param array $installdefs Package manifest
     * @return array
     */
    protected function extractRoles(array $installdefs)
    {
        $roles = array();
        foreach ($installdefs as $section) {
            if (is_array($section)) {
                foreach ($section as $def) {
                    if (isset($def['from']) && preg_match('/\/roles\/([^\/]+)/', $def['from'], $matches)) {
                        $roles[$matches[1]] = true;
                    }
                }
            }
        }

        return array_keys($roles);
    }

    /**
     * Retrieves names of the roles with the given IDs
     *
     * @param array $ids ACL role IDs
     * @return array
     */
    protected function getRoleNames(array $ids)
    {
        $roles = array();
        foreach ($ids as $id) {
            $role = BeanFactory::retrieveBean('ACLRoles', $id);
            if ($role) {
                $roles[$id] = $role->name;
            }
        }

        return $roles;
    }

    private function getLanguageManifestForModule($module, &$installdefs)
    {
    	$lang_path = 'custom' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'language';
        foreach(scandir($lang_path) as $langFile)
        {
	        if(substr($langFile, 0, 1) != '.' && is_file($lang_path . DIRECTORY_SEPARATOR . $langFile)){
	            $lang = substr($langFile, 0, strpos($langFile, '.'));
	            $installdefs['language'][] = array(
	                'from'=> '<basepath>/SugarModules/modules/' . $module . '/language/'. $langFile,
	                'to_module'=> $module,
	                'language'=>$lang
	            );
	        }
        }
    }

    private function getCustomFieldsManifestForModule($module, &$installdefs)
    {
    	$db = DBManagerFactory::getInstance();
    	$result=$db->query("SELECT *  FROM fields_meta_data where custom_module='$module'");
    	while($row = $db->fetchByAssoc($result)){
    		$name = $row['id'];
    		foreach($row as $col=>$res){
    			switch ($col) {
    				case 'custom_module':
    					$installdefs['custom_fields'][$name]['module'] = $res;
    					break;
    				case 'required':
    					$installdefs['custom_fields'][$name]['require_option'] = $res;
    					break;
    				case 'vname':
    					$installdefs['custom_fields'][$name]['label'] = $res;
    					break;
    				case 'required':
    					$installdefs['custom_fields'][$name]['require_option'] = $res;
    					break;
    				case 'massupdate':
    					$installdefs['custom_fields'][$name]['mass_update'] = $res;
    					break;
    				case 'comments':
    					$installdefs['custom_fields'][$name]['comments'] = $res;
    					break;
    				case 'help':
    					$installdefs['custom_fields'][$name]['help'] = $res;
    					break;
    				case 'len':
    					$installdefs['custom_fields'][$name]['max_size'] = $res;
    					break;
    				default:
    					$installdefs['custom_fields'][$name][$col] = $res;
    			}//switch
    		}//foreach
    	}//while
    }

    /**
     * Gets the custom metadata from inside the clients directory for a module
     * 
     * @param string $module The module to scrape
     * @param array $installdefs The current install defs to append
     */
    public function getCustomClientMetadata($module, &$installdefs)
    {
        $it = $this->getDirectoryIterator('custom/modules/' . $module . '/clients');
        foreach ($it as $file) {
            $installdefs['copy'][] = array(
                'from' => str_replace('custom/modules', '<basepath>/SugarModules/modules', $file),
                'to' => $file->getPathname(),
            );
        }
    }

    private function getCustomMetadataManifestForModule($module, &$installdefs)
    {
    	$meta_path = 'custom/modules/' . $module . '/metadata';
    	foreach(scandir($meta_path) as $meta_file)
    	{
    		if(substr($meta_file, 0, 1) != '.' && is_file($meta_path . '/' . $meta_file)){
    			if($meta_file == 'listviewdefs.php'){
    				$installdefs['copy'][] = array(
                                'from'=> '<basepath>/SugarModules/modules/'. $module . '/metadata/'. $meta_file,
                                'to'=> 'custom/modules/'. $module . '/metadata/' . $meta_file,
    				);
    			}
    			else{
    				$installdefs['copy'][] = array(
                                'from'=> '<basepath>/SugarModules/modules/'. $module . '/metadata/'. $meta_file,
                                'to'=> 'custom/modules/'. $module . '/metadata/' . $meta_file,
    				);
    				$installdefs['copy'][] = array(
                                'from'=> '<basepath>/SugarModules/modules/'. $module . '/metadata/'. $meta_file,
                                'to'=> 'custom/working/modules/'. $module . '/metadata/' . $meta_file,
    				);
    			}
    		}
    	}
    }

    /**
     * @todo private changed protected for testing purposes.
     * 
     * @param string $path
     * @param array $installdefs link
     */
    protected function getExtensionsManifestForPackage($path, &$installdefs)
    {
        if(empty($installdefs['copy']))
        {
            $installdefs['copy']= array();
        }
        $generalPath = DIRECTORY_SEPARATOR . 'Extension' . DIRECTORY_SEPARATOR . 'modules';

        //do not process if path is not a valid directory, or recursiveIterator will break.
        if(!is_dir($path.$generalPath))
        {
            return;
        }
        
        $recursiveIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path . $generalPath),
                RecursiveIteratorIterator::SELF_FIRST
        );

        /* @var $fInfo SplFileInfo */
        foreach (new RegexIterator($recursiveIterator, "/\.php$/i") as $fInfo)
        {

            $newPath = substr($fInfo->getPathname(), strrpos($fInfo->getPathname(), $generalPath));

            $installdefs['copy'][] = array(
                'from' => '<basepath>' . $newPath,
                'to' => 'custom' . $newPath
            );
        }
    }


    //return an array which contain the name of fields_meta_data table's columns
    function getColumnsName(){

        $meta = BeanFactory::newBean('EditCustomFields');
        $arr = array();
         foreach($meta->getFieldDefinitions() as $key=>$value) {
            $arr[] = $key;
        }
        return $arr;
    }


    //creation of the custom fields ZIP file (use getmanifest() and customBuildInstall() )
    function exportCustom($modules, $export=true, $clean = true){
        
        $relationshipFiles = array();

        $path = $this->getBuildDir();
        if ($clean && file_exists($path)) {
            rmdir_recursive($path);
        }
        //Copy the custom files to the build dir
        foreach ($modules as $module) {
            $pathmod = "$path/SugarModules/modules/$module";
            // create module directory only if customizations that belong directly to the module exist
            if (file_exists("custom/modules/$module") && mkdir_recursive($pathmod)) {
                $this->export("custom/modules/$module", $pathmod);
                //Don't include cached extension files
                if (is_dir("$pathmod/Ext")) {
                    rmdir_recursive("$pathmod/Ext");
                }
                //Convert modstring files to extension compatible arrays
                $this->convertLangFilesToExtensions("$pathmod/language");
            }

            $extensions = $this->getExtensionsList($module, $modules);
            $relMetaFiles = $this->getCustomRelationshipsMetaFilesByModuleName($module, true,false,$modules);
            $extensions = array_merge($extensions, $relMetaFiles);

            foreach ($extensions as $file) {
                $fileInfo = new SplFileInfo($file);
                $trimmedPath = ltrim($fileInfo->getPath(), 'custom');

                sugar_mkdir($path . $trimmedPath, NULL, true);

                // append package name to the language file name in order to make the package have its own unique
                // language files and thus avoid collisions between package and instance customizations
                if (strpos($trimmedPath, '/Ext/Language') !== false) {
                    $baseName = $fileInfo->getBasename('.lang.php');
                    $fileName = $baseName . '.' . $this->name . '.lang.php';
                } else {
                    $fileName = $fileInfo->getFilename();
                }

                copy($file, $path . $trimmedPath . '/' . $fileName);
            }
        }

        $this->copyCustomIncludesForModules($modules, $path);
        if (!is_dir($path)) {
            sugar_die('Build directory has not been created');
        }

        $manifest = $this->getManifest(true) . $this->customBuildInstall($modules, $path);
        sugar_file_put_contents($path .'/manifest.php', $manifest);

        if(file_exists('modules/ModuleBuilder/MB/LICENSE.txt')){
            copy('modules/ModuleBuilder/MB/LICENSE.txt', $path . '/LICENSE.txt');
        }
        else if(file_exists('LICENSE')){
            copy('LICENSE', $path . '/LICENSE');
        }
        require_once('include/utils/zip_utils.php');
        $date = date('Y_m_d_His');
        $zipDir = $this->getZipDir();
        if(!file_exists($zipDir))mkdir_recursive($zipDir);
        $cwd = getcwd();
        chdir($this->getBuildDir());
        zip_dir('.',$cwd . '/'. $zipDir. '/'. $this->name. $date. '.zip');
        chdir($cwd);
        if($clean && file_exists($this->getBuildDir()))rmdir_recursive($this->getBuildDir());
        if($export){
            header('Location:' . $zipDir. '/'. $this->name. $date. '.zip');
        }
        return $zipDir. '/'. $this->name. $date. '.zip';
    }

    private function convertLangFilesToExtensions($langDir)
    {
        if (is_dir($langDir))
        {
            foreach(scandir($langDir) as $langFile)
            {
                $mod_strings = array();
                if (strcasecmp(substr($langFile, -4), ".php") != 0)
                    continue;
                include FileLoader::validateFilePath("$langDir/$langFile");
                $out = "<?php \n // created: " . date('Y-m-d H:i:s') . "\n";
                foreach($mod_strings as $lbl_key => $lbl_val )
                {
                    $out .= override_value_to_string("mod_strings", $lbl_key, $lbl_val) . "\n";
                }
                $out .= "\n?>\n";
                sugar_file_put_contents("$langDir/$langFile", $out);
            }
        }
    }
    private function copyCustomIncludesForModules($modules, $path)
    {
        foreach (self::$appExtensions as $spec) {
            $varName = $spec['varName'];
            $it = $this->getDirectoryIterator('custom/' . $spec['dir']);
            foreach ($it as $file) {
                $$varName = array();
                include $file;

                $values = $this->getCustomOptionsForModules($modules, $$varName);
                if (count($values) > 0) {
                    $contents = "<?php \n";
                    foreach ($values as $name => $arr) {
                        $contents .= override_value_to_string($varName, $name, $arr);
                    }
                    $subPathName = $it->getSubPathname();
                    $subPathName = str_replace('.lang', '', $subPathName);
                    $subPathName = substr($subPathName, 0, -4) . '.' . $this->name . substr($subPathName, -4);
                    $destination = $path . '/SugarModules/' . $spec['dir'] . '/' . $subPathName;
                    mkdir_recursive(dirname($destination));
                    sugar_file_put_contents($destination, $contents);
                }
            }
        }
    }

    /**
     * Returns iterator over exportable files in the given directory and subdirectories
     *
     * @param string $dir Directory path
     * @return RecursiveDirectoryIterator|SplFileInfo[]
     */
    protected function getDirectoryIterator($dir)
    {
        if (file_exists($dir)) {
            $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
            $it = new RecursiveIteratorIterator($it);
            $it = new RegexIterator($it, '/\.php$/');
            return $it;
        }

        return new EmptyIterator();
    }

    protected function getCustomOptionsForModules($modules, $list_strings)
    {
        $options = array();
        foreach($modules as $module)
        {
            $bean = BeanFactory::newBean($module);
            if (!empty($bean))
            {
                foreach($bean->field_defs as $field => $def)
                {
                    if (isset($def['options']) && isset($list_strings[$def['options']]))
                    {
                        $options[$def['options']] = $list_strings[$def['options']];
                    }
                }
            }
        }
        return $options;
    }

    /**
     * Returns associative array containing module names as keys and types of their customizations as values
     *
     * @return array
     */
    public function getCustomModules()
    {
        global $mod_strings;
        global $modInvisList;

        $modulesWithCustomDropdowns = $this->getModulesWithApplicationExtensions();
        $modules = array_merge($this->getSubdirectories('custom/modules/'), $modulesWithCustomDropdowns);
        $modules = array_unique($modules);

        //Use StudioBrowser to grab list of modules that are customizeable through studio.
        $sb = new StudioBrowser();
        $sb->loadModules();
        $studioModules = array_keys($sb->modules);

        //limit modules to process to the ones that can be edited in studio
        $modules = array_intersect($modules, $studioModules);

        $result = array();
        foreach ($modules as $module) {
            $result[$module] = $this->getModuleCustomizations($module);
            if (in_array($module, $modulesWithCustomDropdowns) &&
                SugarAutoLoader::existingCustomOne("modules/{$module}/metadata/studio.php")) {
                $result[$module]['Dropdown'] = $mod_strings['LBL_EC_CUSTOMDROPDOWN'];
            }
        }

        return array_filter($result);
    }

    /**
     * Returns types of existing customizations for the given module
     * 
     * @param string $module Module name
     * @return array
     */
    protected function getModuleCustomizations($module)
    {
        global $mod_strings;

        $result = array();
        if (!SugarAutoLoader::existingCustomOne("modules/{$module}/metadata/studio.php")) {
            return $result;
        }

        $path = 'custom/modules/' . $module;
        $subdirectories = $this->getSubdirectories('custom/modules/' . $module);
        foreach ($subdirectories as $type) {
            switch ($type) {
                case 'language':
                    $result[$type] = $mod_strings['LBL_EC_CUSTOMFIELD'];
                    break;
                case 'metadata':
                case 'clients':
                    // BWC modules keep metadata in the 'metadata' directory
                    if (isModuleBWC($module)) {
                        $result[$type] = $mod_strings['LBL_EC_CUSTOMLAYOUT'];
                    } else {
                        // New style pathing
                        $fullpath = $path . '/' . $type;
                        // Right now only views are customizable in studio
                        $viewDirs = glob("$fullpath/*/views");
                        foreach ($viewDirs as $viewDir) {
                            if ($this->isDirectoryExportable($viewDir)) {
                                $result[$type] = $mod_strings['LBL_EC_CUSTOMLAYOUT'];
                                break;
                            }
                        }
                    }
                    break;
                case 'Ext':
                    // Simply checking the Ext directory isn't enough...
                    // we need to check certain directories inside of it
                    // to make sure there are things that are eligible
                    // to export
                    $fullpath = $path . '/' . $type;

                    // Start first with custom fields
                    if ($this->isDirectoryExportable("$fullpath/Vardefs")) {
                        $result["$type/Vardefs"] = $mod_strings['LBL_EC_CUSTOMFIELD'];
                    }

                    // Now check custom labels
                    if ($this->isDirectoryExportable("$fullpath/Language")) {
                        $result["$type/Language"] = $mod_strings['LBL_EC_CUSTOMLABEL'];
                    }
                    break;
                default:
                    $result[$type] = $mod_strings['LBL_UNDEFINED'];
            }
        }

        return $result;
    }

    /**
     * Returns array of subdirectories for the given directory
     *
     * @param string $path Directory path
     * @return array
     */
    protected function getSubdirectories($path)
    {
        $path = rtrim($path, '/');
        $subdirectories = array();
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $value) {
                if (is_dir($path . '/' . $value) && $value != '.' && $value != '..') {
                    $subdirectories[] = $value;
                }
            }
        }

        return $subdirectories;
    }

    /**
     * Returns array of module names that use application level extensions
     * 
     * @return array
     */
    protected function getModulesWithApplicationExtensions()
    {
        global $beanList;

        $app_list_strings = array();
        $role_dropdown_filters = array();
        foreach (self::$appExtensions as $spec) {
            $it = $this->getDirectoryIterator('custom/' . $spec['dir']);
            foreach ($it as $file) {
                include $file;
            }
        }

        $modules = array();
        if (count($app_list_strings) > 0
            || count($role_dropdown_filters) > 0
        ) {
            foreach ($beanList as $module => $_) {
                $bean = BeanFactory::newBean($module);
                if (!isset($bean->field_defs) || !is_array($bean->field_defs)) {
                    continue;
                }

                foreach ($bean->field_defs as $field => $def) {
                    if (isset($def['options'])) {
                        foreach (self::$appExtensions as $spec) {
                            $varName = $spec['varName'];
                            if (isset(${$varName}[$def['options']])) {
                                $modules[] = $module;
                                continue 3;
                            }
                        }
                    }
                }
            }
        }

        return $modules;
    }

    /**
     * Get _custom_ extensions for module.
     * Default path - custom/Extension/modules/$module/Ext.
     * 
     * @param array $module Name.
     * @param mixed $includeRelationships ARRAY - relationships files between $module and names in array;
     * TRUE - with all relationships files; 
     * @return array Paths.
     */
    protected function getExtensionsList($module, $includeRelationships = true)
    {
        if (BeanFactory::getBeanClass($module) === false) {
            return array();
        }
        
        $result = array();
        $includeMask = false;
        $extPath = sprintf('custom%1$sExtension%1$smodules%1$s' . $module . '%1$sExt', DIRECTORY_SEPARATOR);

        //do not process if path is not a valid directory, or recursiveIterator will break.
        if(!is_dir($extPath))
        {
            return $result;
        }


        if (is_array($includeRelationships))
        {
            $includeMask = array();
            $customRels = $this->getCustomRelationshipsByModuleName($module);

            $includeRelationships[] = $module;

            foreach ($customRels as $k => $v)
            {
                if (
                    in_array($v->getLhsModule(), $includeRelationships) &&
                    in_array($v->getRhsModule(), $includeRelationships)
                )
                {
                    $includeMask[] = $k;
                }
            }
        }

        $recursiveIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($extPath),
                RecursiveIteratorIterator::SELF_FIRST
        );

        /* @var $fileInfo SplFileInfo */
        foreach ($recursiveIterator as $fileInfo)
        {

            if ($fileInfo->isFile() && !in_array($fileInfo->getPathname(), $result))
            {
                //get the filename in lowercase for easier comparison
                $fn = $fileInfo->getFilename();
                if(!empty($fn))
                {
                    $fn = strtolower($fn);
                }

                if ($this->filterExportedRelationshipFile($fn,$module,$includeRelationships) ){
                    $result[] = $fileInfo->getPathname();
                }
            }
        }

        return $result;
    }

    /**
     * Processes the name of the file and compares against the passed in module names to
     * evaluate whether the file should be included for export.  Returns true or false
     *
     * @param $fn (file name that is being evaluated)
     * @param $module (name of current module being evaluated)
     * @param $includeRelationship (list of related modules that are also being exported, to be used as filters)
     * @return boolean true or false
     */
    function filterExportedRelationshipFile($fn,$module,$includeRelationships)
    {
        $shouldExport = false;
        if(empty($fn) || !is_array($includeRelationships) || empty($includeRelationships))
        {
            return $shouldExport;
        }

        //if file name does not contain the current module name then it is not a relationship file,
        //or if the module has the current module name twice seperated with an underscore, then this is a relationship within itself
        //in both cases set the shouldExport flag to true
        $lc_mod = strtolower($module);
        $fn = strtolower($fn);
        if ((strpos($fn, $lc_mod) === false) || (strpos($fn, $lc_mod.'_'.$lc_mod) !== false))
        {
            $shouldExport = true;
        }else{

            //grab only rels that have both modules in the export list
            foreach ($includeRelationships as $relatedModule)
            {
                //skip if the related module is empty
                if(empty($relatedModule))
                {
                    continue;
                }

                //if the filename also has the related module name, then add the relationship file
                //strip the current module,as we have already checked for existance of module name and dont want any false positives
                $fn = str_replace($lc_mod,'',$fn);
                if (strpos($fn, strtolower($relatedModule)) !== false)
                {
                    //both modules exist in the filename lets include in the results array
                    $shouldExport = true;
                    break;
                }
            }
        }

        return $shouldExport;
    }

    /**
     * Returns a set of field defs for fields that will exist when this package is deployed
     * based on the relationships in all of its modules.
     *
     * @param $moduleName (module must be from whithin this package)
     * @return array Field defs
     */
    function getRelationshipsForModule($moduleName) {
    	$ret = array();
    	if (isset($this->modules[$moduleName])) {
    		$keyName = $this->modules[$moduleName]->key_name;
    		foreach($this->modules as $mName => $module) {
    			$rels = $module->getRelationships();
    			$relList = $rels->getRelationshipList();
    			foreach($relList as $rName ) {
    			    $rel = $rels->get ( $rName ) ;
    			     if ($rel->lhs_module == $keyName || $rel->rhs_module == $keyName) {
                        $ret[$rName] =  $rel;
    			     }
    			}
    		}
    	}
    	return $ret;
    }



    private function exportProjectInstall()
    {
        $installdefs = array ('id' => MB_EXPORTPREPEND . $this->name);
        $installdefs['copy'][] = array(
            'from'=> '<basepath>/' . $this->name,
            'to'=> 'custom/modulebuilder/packages/'. $this->name,
        );
        return "\n".'$installdefs = ' . var_export_helper($installdefs). ';';

    }



    public function exportProject()
    {
        $tmppath="custom/modulebuilder/projectTMP/";
        if(file_exists($this->getPackageDir())){
            if(mkdir_recursive($tmppath)){
                copy_recursive($this->getPackageDir(), $tmppath ."/". $this->name);
                $manifest = $this->getManifest(true, true).$this->exportProjectInstall();
                $fp = sugar_fopen($tmppath .'/manifest.php', 'w');
                fwrite($fp, $manifest);
                fclose($fp);
                if(file_exists('modules/ModuleBuilder/MB/LICENSE.txt')){
                    copy('modules/ModuleBuilder/MB/LICENSE.txt', $tmppath . '/LICENSE.txt');
                }
                else if(file_exists('LICENSE')){
                    copy('LICENSE', $tmppath . '/LICENSE');
                }
                $readme_contents = $this->readme;
                $readmefp = sugar_fopen($tmppath.'/README.txt','w');
                fwrite($readmefp, $readme_contents);
                fclose($readmefp);
            }
        }
        require_once('include/utils/zip_utils.php');
        $date = date('Y_m_d_His');
        $zipDir = "custom/modulebuilder/packages/ExportProjectZips";
        if(!file_exists($zipDir))mkdir_recursive($zipDir);
        $cwd = getcwd();
        chdir($tmppath);
        zip_dir('.',$cwd . '/'. $zipDir. '/project_'. $this->name. $date. '.zip');
        chdir($cwd);
        if (file_exists($tmppath)) {
            rmdir_recursive($tmppath);
        }
            header('Location:' . $zipDir. '/project_'. $this->name. $date. '.zip');
        return $zipDir. '/project_'. $this->name. $date. '.zip';
    }
    
    /**
     * This returns an UNFILTERED list of custom relationships by module name.  You will have to filter the relationships
     * by the modules being exported after calling this method
     * @param string $moduleName
     * @param bool $lhs Return relationships where $moduleName - left module in join.
     * @return mixed Array or false when module name is wrong.
     */
    protected function getCustomRelationshipsByModuleName($moduleName, $lhs = false)
    {
        if (BeanFactory::getBeanClass($moduleName) === false) {
            return false;
        }
        
        $result = array();
        $relation = null;
        $module = StudioModuleFactory::getStudioModule($moduleName);

        /* @var $rel DeployedRelationships */
        $rel = $module->getRelationships();

        $relList = $rel->getRelationshipList();

        foreach ($relList as $relationshipName)
        {
            $relation = $rel->get($relationshipName);

            if ($relation->getFromStudio())
            {
                if ($lhs && $relation->getLhsModule() != $moduleName)
                {
                    continue;
                }
                $result[$relationshipName] = $relation;
            }
        }

        return $result;
    }
    
    /**
     * @param string $moduleName
     * @param bool $lhs Return relationships where $moduleName - left module in join.
     * @param bool $metadataOnly Return only relationships metadata file.
     * @param $includeRelationship (list of related modules that are also being exported)
     * @return array (array of relationships filtered to only include relationships to modules being exported)
     */
    protected function getCustomRelationshipsMetaFilesByModuleName($moduleName, $lhs = false, $metadataOnly = false,$exportedModulesFilter=array())
    {
        
        $path = $metadataOnly ?
                'custom' . DIRECTORY_SEPARATOR . 'metadata' . DIRECTORY_SEPARATOR :
                'custom' . DIRECTORY_SEPARATOR;
        $result = array();


        //do not process if path is not a valid directory, or recursiveIterator will break.
        if(!is_dir($path))
        {
            return $result;
        }

        $relationships = $this->getCustomRelationshipsByModuleName($moduleName, $lhs);
        
        if (!$relationships)
        {
            return array();
        }
        
        $recursiveIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path),
                RecursiveIteratorIterator::SELF_FIRST
        );

        /**
         * @var $fileInfo SplFileInfo 
         */
        foreach ($recursiveIterator as $fileInfo)
        {
            if ($fileInfo->isFile() && !in_array($fileInfo->getPathname(), $result))
            {
                foreach ($relationships as $k => $v)
                {

                    if (strpos($fileInfo->getFilename(), $k) !== false)
                    {   //filter by modules being exported
                        if ($this->filterExportedRelationshipFile($fileInfo->getFilename(),$moduleName,$exportedModulesFilter) ){
                            $result[] = $fileInfo->getPathname();
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function deleteBuild()
    {
        return rmdir_recursive($this->getBuildDir());
    }

    /**
     * Checks a directory to make sure there is something in it for export
     * 
     * @param string $path Directory path to check for files
     * @return boolean 
     */
    public function isDirectoryExportable($path)
    {
        if (file_exists($path)) {
            $it = $this->getDirectoryIterator($path);
            $it->rewind();
            return $it->valid();
        }

        return false;
    }

    /**
     * Exports source directory to the destination directory
     *
     * @param string $src Source directory
     * @param string $dst Destination directory
     */
    protected function export($src, $dst)
    {
        $it = $this->getDirectoryIterator($src);
        foreach ($it as $file) {
            $subPathName = $it->getSubPathname();
            $dstPath = $dst . '/' . $subPathName;
            $dirName = dirname($dstPath);
            if (!is_dir($dirName)) {
                mkdir_recursive($dirName);
            }
            copy($file, $dstPath);
        }
    }
}
