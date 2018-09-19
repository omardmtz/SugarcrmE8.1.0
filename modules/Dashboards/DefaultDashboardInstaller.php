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
 *  DefaultDashboardInstaller is used to build the default dashboards.
 */
class DefaultDashboardInstaller
{
    private $globalTeamId = '1';

    /**
     * Builds the default dashboards in the database using the dashboard files.
     *
     * @param array $modules The list of modules available in Sugar
     */
    public function buildDashboardsFromFiles(array $modules)
    {
        // Loop over each module to get each module's dashboard directory
        foreach ($modules as $module) {
            $moduleDir = "modules/$module/dashboards/";
            $layoutDirs = $this->getSubDirs($moduleDir);

            // Loop over each module's dashboard views to get each view dir
            foreach ($layoutDirs as $layoutDir) {
                $layout = basename($layoutDir);
                $dashboardFiles = $this->getPhpFiles($layoutDir);

                // Loop over each dashboard within the view dir
                foreach ($dashboardFiles as $dashboardFile) {
                    $dashboardContents = $this->getFileContents($dashboardFile);
                    if (!$dashboardContents) {
                        continue;
                    }

                    $dashboardProperties = array(
                        'name' => $dashboardContents['name'],
                        'dashboard_module' => $module,
                        'view_name' => $module !== 'Home' ? $layout : null,
                        'metadata' => json_encode($dashboardContents['metadata']),
                        'default_dashboard' => true,
                        'team_id' => $this->globalTeamId,
                    );
                    $dashboardBean = $this->getNewDashboardBean();
                    $this->storeDashboard($dashboardBean, $dashboardProperties);
                }
            }
        }
    }

    /**
     * Given a directory, returns all its subdirectories.
     *
     * @param string $dir The base directory
     * @return array Array of subdirectories
     */
    public function getSubDirs($dir)
    {
        return glob($dir . '/*' , GLOB_ONLYDIR);
    }

    /**
     * Given a directory, returns all the php files in it.
     *
     * @param string $dir The base directory
     * @return array Array of .php files
     */
    public function getPhpFiles($dir)
    {
        return glob($dir . '/*.php');
    }

    /**
     * Retrieves data from the specified file.
     *
     * @param string $dashboardFile The file to  data.
     *
     * @return array The data from the affiliated file.
     */
    public function getFileContents($dashboardFile)
    {
        return include $dashboardFile;
    }

    /**
     * Using the supplied properties, create and store a new dashboard bean.
     *
     * @param SugarBean $dashboardBean The dashboard bean to populate.
     * @param array $properties The properties to store to the dashboard bean.
     */
    public function storeDashboard($dashboardBean, $properties)
    {
        foreach ($properties as $key => $value) {
            $dashboardBean->$key = $value;
        }
        $dashboardBean->save();
    }

    /**
     * Creates a new blank dashboard bean.
     *
     * @return SugarBean
     */
    public function getNewDashboardBean()
    {
        return BeanFactory::newBean('Dashboards');
    }

    /**
     * Retrieve a system user.
     *
     * @return User A system user.
     */
    public function getAdminUser()
    {
        $user = BeanFactory::newBean('Users');
        if (empty($user)) {
            throw new SugarException('Unable to retrieve user bean.');
        }
        return $user->getSystemUser();
    }

    /**
     * Builds the default dashboards in the database using metadata.
     *
     * @param array $metadata The standard application metadata.
     */
    public function buildDashboardsFromMetadata(array $metadata)
    {
        $adminUser = $this->getAdminUser();
        $adminUserId = $adminUser->id;

        // Maps between the name of the old OOTB dashboard layout name and the
        // view_name to which it refers.
        $layoutToNameMap = array(
            'record-dashboard' => 'record',
            'list-dashboard' => 'records',
        );

        $layouts = array_keys($layoutToNameMap);
        $modules = array_keys($metadata['modules']);

        foreach ($modules as $module) {
            foreach ($layouts as $layout) {
                $moduleLayouts = $metadata['modules'][$module]['layouts'];

                // Some modules only have one dashboard.
                if (!isset($moduleLayouts[$layout])) {
                    continue;
                }

                $dashboardContents = $moduleLayouts[$layout]['meta'];
                $viewName = $module !== 'Home' ? $layoutToNameMap[$layout] : null;
                $dashboardProperties = array(
                    'name' => $dashboardContents['name'],
                    'dashboard_module' => $module,
                    'view_name' => $viewName,
                    'metadata' => json_encode($dashboardContents['metadata']),
                    'default_dashboard' => true,
                    'team_id' => $this->globalTeamId,
                    'assigned_user_id' => $adminUserId,
                    'set_created_by' => false,
                    'created_by' => $adminUserId,
                    'update_modified_by' => false,
                    'modified_user_id' => $adminUserId,
                );

                $dashboardBean = $this->getNewDashboardBean();
                $this->storeDashboard($dashboardBean, $dashboardProperties);
            }
        }
    }
}
