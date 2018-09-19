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

class MetaDataManagerPortal extends MetaDataManager
{
    /**
     * Find all modules with Portal metadata
     * 
     * @return array List of Portal module names
     */
    protected function getModules($filtered = true)
    {
        $modules = array();
        foreach (SugarAutoLoader::getDirFiles("modules", true) as $mdir) {
            // strip modules/ from name
            $mname = substr($mdir, 8);
            if (file_exists("$mdir/clients/portal/")) {
                $modules[] = $mname;
            }
        }
        $modules[] = 'Users';
        $modules[] = 'Filters';
        return $modules;
    }

    /**
     * Gets the full module list of Portal.
     * Returns the same module list as `getModules`.
     *
     * @return array List of Portal module names
     */
    public function getFullModuleList($filtered = false)
    {
        return $this->getModules();
    }

    /**
     * Gets the moduleTabMap array to allow clients to decide which menu element
     * a module should live in for non-module modules
     *
     * @return array
     */
    public function getModuleTabMap()
    {
        $map = $GLOBALS['moduleTabMap'];
        $map['Search'] = 'Home';
        return $map;
    }

    /**
     * Gets configs
     * 
     * @return array
     */
    protected function getConfigs() {
        $admin = new Administration();
        $configs = $admin->getConfigForModule('portal', 'support');

        return $configs;
    }


    /**
     * Fills in additional app list strings data as needed by the client
     * 
     * @param array $public Public app list strings
     * @param array $main Core app list strings
     * @return array
     */
    protected function fillInAppListStrings(Array $public, Array $main) {
        $public['countries_dom'] = $main['countries_dom'];
        $public['state_dom'] = $main['state_dom'];
        
        return $public;
    }

    /**
     * Gets list of modules that are displayed in the navigation bar
     *
     * @return array The list of module names
     */
    public function getTabList($filter = true)
    {
        $controller = new TabController();
        return $controller->getPortalTabs();
    }

    /**
     * Gets the module list for the current user
     * Returns the same module list as `getTabList`.
     *
     * In the future, there may be a UI to allow user to configure visible
     * modules in his `Profile` section.
     *
     * @return array The list of modules for portal
     */
    public function getUserModuleList()
    {
        return $this->getTabList();
    }

    /**
     * Retrieves the portal logo if defined, otherwise the company logo url
     *
     * @return string url of the portal logo
     */
    public function getLogoUrl() {
        global $sugar_config;
        $config = $this->getConfigs();
        if (!empty($config['logoURL'])) {
            return $config['logoURL'];
        } else {
            $themeObject = SugarThemeRegistry::current();
            return $sugar_config['site_url'] . '/' . $themeObject->getImageURL('company_logo.png', true, true);
        }
    }

    /**
     * Load Portal specific metadata (heavily pruned to only show modules enabled for Portal)
     * @return array Portal metadata
     */
    protected function loadMetadata($args = array(), MetaDataContextInterface $context)
    {
        $data = parent::loadMetadata($args, $context);

        if (!empty($data['modules'])) {
            foreach ($data['modules'] as $modKey => $modMeta) {
                if (!empty($modMeta['isBwcEnabled'])) {
                    // portal has no concept of bwc so get rid of it
                    unset($data['modules'][$modKey]['isBwcEnabled']);
                }
            }
        }

        // Rehash the hash
        $data['_hash'] = $this->hashChunk($data);
        return $data;
    }
}
