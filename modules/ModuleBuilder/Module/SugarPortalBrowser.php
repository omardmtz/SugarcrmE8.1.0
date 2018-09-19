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



class SugarPortalBrowser
{
    var $modules = array();

    function loadModules()
    {
        foreach(SugarAutoLoader::getDirFiles("modules", true) as $mdir) {
            // strip modules/ from name
            $mname = substr($mdir, 8);
            if(SugarAutoLoader::existingCustomOne("modules/{$mname}/metadata/studio.php")  && $this->isPortalModule($mname)) {
                $this->modules[$mname] = new SugarPortalModule($mname);
            }
        }
    }

    function getNodes(){
        $nodes = array();
        $functions = new SugarPortalFunctions();
        $nodes = $functions->getNodes();
        $this->loadModules();
        $layouts = array();
        foreach($this->modules as $module){
            $layouts[$module->name] = $module->getNodes();
        }
        $nodes[] = array(
            'name'=> translate('LBL_LAYOUTS'),
            'imageTitle' => 'Layouts',
            'type'=>'Folder',
            'children'=>$layouts,
            'action'=>'module=ModuleBuilder&action=wizard&portal=1&layout=1');
        ksort($nodes);
        return $nodes;
    }

    /**
     * Runs through the views metadata directory to check for expected portal
     * files to verify if a given module is a portal module.
     *
     * This replaces the old file path checker that looked for
     * portal/modules/$module/metadata. We are now looking for
     * modules/$module/metadata/portal/views/(edit|list|detail).php
     *
     * @param string $module The module to check portal validity on
     * @return bool True if a portal/view/$type.php file was found
     */
    function isPortalModule($module)
    {
        // If this module isn't studio enabled for portal, don't bother with the
        // rest of the validation
        if ($this->isStudioEnabled($module)) {
            // Create the path to search
            $path = "modules/$module/clients/portal/views/";

            // Handle it
            // Bug 55003 - Notes showing as a portal module because it has non
            // standard layouts
            $views = SugarPortalModule::getViewFiles();
            $viewFiles = array_keys($views);
            foreach ($viewFiles as $file) {
                $fullPath = $path . basename($file, '.php') . '/' . $file;
                if (file_exists($fullPath)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Checks to see if a module is studio enabled for portal.
     *
     * The default expectation is false unless a module is explicitly true or
     * does not set an expectation.
     *
     * @param string $module The name of the module
     * @return boolean
     */
    protected function isStudioEnabled($module)
    {
        global $dictionary;
        
        // Grab the bean to make sure this is a legit module
        $bean = BeanFactory::newBean($module);
        
        // Do some simple sanity checking before checking portal status
        if (is_object($bean) && !empty($bean->object_name) && isset($dictionary[$bean->object_name])) {
            $vardef = $dictionary[$bean->object_name];
            
            // No expectation set, means it does not explicitly disallow studio
            // Explicit setting to true for the module means the same
            if (!isset($vardef['studio_enabled']) || $vardef['studio_enabled'] === true) {
                return true;
            }
            
            // Explicit setting to true for the platform within an array
            $hasPortal = is_array($vardef['studio_enabled']) && isset($vardef['studio_enabled']['portal']);
            return $hasPortal && $vardef['studio_enabled']['portal'] === true;
        }
        
        // Return the default value
        return false;
    }
}
