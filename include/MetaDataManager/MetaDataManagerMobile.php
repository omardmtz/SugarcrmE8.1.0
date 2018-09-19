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

class MetaDataManagerMobile extends MetaDataManager
{
    protected $blackListModuleDataKeys = array(
        'menu'
    );

    protected $allowedModuleViews = array(
        'list',
        'edit',
        'detail',
        'forecast-pipeline',
        'dupecheck-list',
    );

    protected $allowedModuleLayouts = array(
        'list',
        'edit',
        'detail',
        'subpanels',
        'convert-main',
    );

    /**
     * Find all modules enabled in Mobile
     *
     * @return array List of Mobile module names
     */
    protected function getModules($filtered = true)
    {
        // Get the current user module list
        $modules = array_intersect(parent::getModules($filtered), $this->getTabList());
        $defaultEnabledModules = $this->getDefaultEnabledModuleList();

        // Add default enabled modules to the list
        $modules = array_keys(array_merge(
            array_flip($modules),
            array_flip($defaultEnabledModules),
            array_flip($this->getSupportingModules($modules))
        ));

        return $modules;
    }

    /**
     * Utility function to make static call testable
     *
     * @param $BeanName
     * @return string bean name
     */
    public function retrieveSupportingModuleListByBeanName($BeanName)
    {
        $modClass = BeanFactory::getBeanClass($BeanName);
        return $modClass::getMobileSupportingModules();
    }

    /**
     * Gets enabled supporting module list for things like Quotes on Mobile.
     *
     * @return array List of Mobile module names
     */
    public function getSupportingModules($modules)
    {
        $supportingModules = array();
        foreach ($modules as $module) {
            $supportingModules = array_merge(
                $supportingModules,
                $this->retrieveSupportingModuleListByBeanName($module)
            );
        }
        return $supportingModules;
    }

    /**
     * Gets default enabled module list of Mobile.
     *
     * @return array List of Mobile module names
     */
    public function getDefaultEnabledModuleList()
    {
        return array(
            'Activities',
            'Forecasts',
            'Home',
            // add in Users [Bug59548] since it is forcefully removed for the
            // CurrentUserApi
            'Users',
        );
    }

    /**
     * Gets the full module list of Mobile.
     * Returns the same module list as `getModules`.
     *
     * @return array List of Mobile module names
     */
    public function getFullModuleList($filtered = false)
    {
        return $this->getModules();
    }

    /**
     * Gets every single module of the application and the properties for every
     * of these modules
     *
     * @return array An array with all the modules and their properties
     */
    public function getModulesInfo($data = array(), MetaDataContextInterface $context = null)
    {
        // Need to override the base one because it grabs the visibility settings from
        // the $moduleList global and we don't like messing with globals
        $modulesInfo = parent::getModulesInfo($data, $context);
        if (isset($modulesInfo['Employees'])) {
            $modulesInfo['Employees']['visible'] = $modulesInfo['Employees']['display_tab'];
        }
         
        return $modulesInfo;
    }

    /**
     * Gets the list of mobile modules. Used by getModules and the CurrentUserApi
     * to get the module list for a user.
     * 
     * @return array The list of modules for mobile
     */
    public function getTabList($filter = true)
    {
        $cache = SugarCache::instance();
        $wireless_module_registry_keys = $cache->wireless_module_registry_keys;

        if (empty($wireless_module_registry_keys)) {
            // replicate the essential part of the behavior of the private loadMapping() method in SugarController
            foreach (SugarAutoLoader::existingCustom('include/MVC/Controller/wireless_module_registry.php') as $file) {
                require $file;
            }

            if (isset($wireless_module_registry) && is_array($wireless_module_registry)) {
                $wireless_module_registry_keys = array_keys($wireless_module_registry);
                $cache->set('wireless_module_registry_keys', $wireless_module_registry_keys);
            } else {
                $wireless_module_registry_keys = array();
            }
        }

        // Forcibly remove the Users module
        $wireless_module_registry_keys = array_diff($wireless_module_registry_keys, array('Users'));

        return $wireless_module_registry_keys;
    }

    public function getQuickcreateList($filter = true)
    {
        // replicate the essential part of the behavior of the private loadMapping() method in SugarController
        foreach (SugarAutoLoader::existingCustom('include/MVC/Controller/wireless_module_registry.php') as $file) {
            require $file;
        }

        // Forcibly remove the Users module
        // So if they have added it, remove it here
        if (isset($wireless_module_registry['Users'])) {
            unset($wireless_module_registry['Users']);
        }

        $quickcreateList = array();

        foreach($wireless_module_registry as $module => $moduleData) {
            if (empty($moduleData['disable_create'])) {
                $quickcreateList[] = $module;
            }
        }
        return $quickcreateList;
    }

    /**
     * Gets the module list for the current user.
     * Returns the same module list as `getTabList`.
     *
     * In the future, there will be a UI to allow user to configure visible
     * modules in his `Profile` section.
     *
     * @return array The list of modules for mobile
     */
    public function getUserModuleList()
    {
        return $this->getTabList();
    }

    /**
     * Retrieve white listed properties which shall be copied from server side
     * configurations to client side configurations.
     *
     * @return array Configuration properties.
     */
    protected function getConfigProperties()
    {
        $properties = parent::getConfigProperties();
        $properties['offlineEnabled'] = true;
        return $properties;
    }

    /**
     * Normalizes the metadata response for the platform.
     * 
     * Mobile needs to cleanse itself of some of the stuff that base metadata 
     * needs that mobile doesn't. That is done here.
     * 
     * @param array $data The metadata collection
     * @return array The normalize metadata collection for this platform
     */
    public function normalizeMetadata($data)
    {
        if (!empty($data['modules'])) {
            foreach($data['modules'] as $module=> $mData) {
                //blacklist certain data types alltogether
                foreach($this->blackListModuleDataKeys as $key) {
                    unset($data['modules'][$module][$key]);
                }

                //views and layouts should be white-list filtered
                if (!empty($mData['views'])) {
                    foreach($mData['views'] as $key => $def) {
                        if (!in_array($key, $this->allowedModuleViews)) {
                            unset($data['modules'][$module]['views'][$key]);
                        }
                    }
                }

                if (!empty($mData['layouts'])) {
                    foreach($mData['layouts'] as $key => $def) {
                        if (!in_array($key, $this->allowedModuleLayouts)) {
                            unset($data['modules'][$module]['layouts'][$key]);
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    protected function getLanguageCacheAttributes()
    {
        $modules = $this->getModules();
        sort($modules);
        return array_merge(parent::getLanguageCacheAttributes(), array(
            // refresh client side language cache after the list of mobile enabled modules is changed
            'modules' => $modules,
        ));
    }
}
