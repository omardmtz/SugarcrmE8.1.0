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
 * API service dictionary
 * Collects information about what endpoints are available in the system
 */
class ServiceDictionary {
    public function __construct() {
        $this->cacheDir = sugar_cached('include/api/');
    }

    /**
     * Clear all API path caches
     */
    public function clearCache() {
        $thedir = $this->cacheDir;
        if ($current = @opendir($thedir)) {
            while (false !== ($children = readdir($current))) {
                $children = trim($children);
                $fileexists = (is_file($thedir."/". $children)) ? "TRUE" : "FALSE";
                if ($children != "." && $children != "..") {
                    if (is_dir($thedir . "/" . $children)) {
                        $this->clearCache($thedir . "/" . $children, 'php');
                    }
                    elseif (is_file($thedir . "/" . $children) && (substr_count($children, 'php'))) {
                        unlink($thedir . "/" . $children);
                    }
                }
            }
        }
    }

    /**
     * Load a dictionary for a particular api
     * @internal
     * @param string $apiType The api type for the dictionary you want to load ("Rest" or "Soap")
     * @return array The data stored in saveDictionaryToStorage()
     */
    protected function loadDictionaryFromStorage($apiType) {
        $dictFile = $this->cacheDir.'ServiceDictionary.'.$apiType.'.php';
        if ( ! file_exists($dictFile) || inDeveloperMode() ) {
            // No stored service dictionary, I need to build them
            $this->buildAllDictionaries();
        }

        require($dictFile);
        
        return $apiDictionary[$apiType];
    }

    /**
     * Save a dictionary for a particular API to storage
     * @internal
     * @param string $apiType The api type for the dictionary you want to load ("Rest" or "Soap")
     * @param array $storageData The data that the API needs to store for it's dictionary.
     */
    protected function saveDictionaryToStorage($apiType,$storageData) {
        if ( !is_dir($this->cacheDir) ) {
            sugar_mkdir($this->cacheDir,null,true);
        }

        sugar_file_put_contents($this->cacheDir.'ServiceDictionary.'.$apiType.'.php','<'."?php\n\$apiDictionary['".$apiType."'] = ".var_export($storageData,true).";\n");
        
    }

    /**
     * Build all dictionaries for the known service types.
     */
    public function buildAllDictionaries() {
        $apis = $this->loadAllDictionaryClasses();

        foreach ( $apis as $apiType => $api ) {
            $api->preRegisterEndpoints();
        }

        $globPaths = array(
            array('glob'=>'clients/*/api/*.php','custom'=>false, 'platformPart'=>1),
            array('glob'=>'custom/clients/*/api/*.php','custom'=>true, 'platformPart'=>2),
            array('glob'=>'modules/*/clients/*/api/*.php','custom'=>false, 'platformPart'=>3),
            array('glob'=>'custom/modules/*/clients/*/api/*.php','custom'=>true, 'platformPart'=>4),
        );

        foreach ( $globPaths as $path ) {
            $files = glob($path['glob'],GLOB_NOSORT);

            if ( !is_array($files) ) {
                // No matched files, skip to the next glob
                continue;
            }
            foreach ( $files as $file ) {
                // Strip off the directory, then the .php from the end
                $fileClass = substr(basename($file),0,-4);

                $pathParts = explode('/',$file);
                if (empty($pathParts[$path['platformPart']]) ) {
                    $platform = 'base';
                } else {
                    $platform = $pathParts[$path['platformPart']];
                }

                require_once($file);
                if (!(class_exists($fileClass) 
                      && is_subclass_of($fileClass,'SugarApi')) ) {
                    // Either the class doesn't exist, or it's not a subclass of SugarApi, regardless, we move on
                    continue;
                }

                $re = new ReflectionClass($fileClass);
                if ($re->isAbstract()) {
                    continue;
                }

                $obj = new $fileClass();
                foreach ( $apis as $apiType => $api ) {
                    $methodName = 'registerApi'.$apiType;
                    
                    if ( method_exists($obj,$methodName) ) {
                        $api->registerEndpoints($obj->$methodName(),$file,$fileClass,$platform,$path['custom']);
                    }
                }
            }
        }

        foreach ( $apis as $apiType => $api ) {
            $this->saveDictionaryToStorage($apiType,$api->getRegisteredEndpoints());
        }
    }

    /**
     * Fetch the list of recognized service types.
     * @internal
     */
    protected function loadAllDictionaryClasses() {
        // Currently hardcoded to just Soap and Rest
        

        $apis = array();
        $apis['rest'] = new ServiceDictionaryRest();
        // $apis['soap'] = new ServiceDictionarySoap();
        
        return $apis;
    }
}
