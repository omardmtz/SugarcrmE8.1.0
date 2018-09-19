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

class SugarUpgradeUpgradeCustomViews extends UpgradeScript
{
    public $order = 5000;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.2';

    public function run()
    {
        // somehow we missed quick create configuration on upgrade. here we update it too.
        if (version_compare($this->from_version, '7.6', '<') && is_file('custom/include/DashletContainer/Containers/DCActions.php')) {
            $quickCreate = new ViewConfigureshortcutbar();
            $modules = $quickCreate->getQuickCreateModules();
            $needToUpdate = true;
            foreach (array_merge($modules['enabled'], $modules['disabled']) as $module => $definition) {
                // it means that someone already configured that so we don't need to do anything.
                if (is_file("custom/modules/$module/clients/base/menus/quickcreate/quickcreate.php")) {
                    $needToUpdate = false;
                    break;
                }
            }

            if ($needToUpdate) {
                $DCActions = array();
                require 'custom/include/DashletContainer/Containers/DCActions.php';
                $DCActions = array_flip($DCActions);
                $successful = $quickCreate->saveChangesToQuickCreateMetadata($modules['enabled'], $modules['disabled'], $DCActions);
                if ($successful) {
                    MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_MODULES));
                }
            }
        }

        // Only run when coming from a version lower than 7.2.
        if (version_compare($this->from_version, '7.2', '<')) {
            $this->fixQuickCreateOrder();
        }

        // Only run when coming from a version lower than 7.6.
        if (version_compare($this->from_version, '7.6', '<')) {
            $this->addStickyResizableColumnsFlag('recordlist');
            $this->addStickyResizableColumnsFlag('history-summary');

            $this->fixRecordListIcons();
            $this->fixQuickCreateIcons();
        }

        // Only run when coming from a version lower than 7.9.
        if (version_compare($this->from_version, '7.9.0.0', '<')) {
            $this->fixSubpanelIcons();
        }
    }

    /**
     * Fixes quickcreate modules order to be the same as the Enabled Modules
     * list.
     */
    private function fixQuickCreateOrder() {
        global $moduleList;

        foreach ($moduleList as $module) {

            $quickCreateFile = "modules/$module/clients/base/menus/quickcreate/quickcreate.php";
            $customQuickCreateFile = "custom/$quickCreateFile";

            if (!file_exists($quickCreateFile) || !file_exists($customQuickCreateFile)) {
                continue;
            }
            require $customQuickCreateFile;
            $customMeta = $viewdefs[$module]['base']['menu']['quickcreate'];

            if (!$customMeta['visible'] || isset($customMeta['order'])) {
                continue;
            }
            require $quickCreateFile;
            $defaultMeta = $viewdefs[$module]['base']['menu']['quickcreate'];

            // -1 is default value for non-ordered modules.
            // See ViewConfigureshortcutbar::getQuickCreateModules();
            $customMeta['order'] = isset($defaultMeta['order']) ? $defaultMeta['order'] : -1;
            write_array_to_file(
                "viewdefs['$module']['base']['menu']['quickcreate']",
                $customMeta,
                $customQuickCreateFile
            );
        }
    }

    /**
     * Fix icons for recordlist, because 7.6 changed the icon names
     */
    private function fixRecordListIcons()
    {
        global $moduleList;

        $iconMap = array(
            'icon-eye-open' => 'fa-eye',
        );

        foreach ($moduleList as $module) {
            $customQuickCreateFile = "custom/modules/$module/clients/base/views/recordlist/recordlist.php";

            if (!file_exists($customQuickCreateFile)) {
                continue;
            }

            require $customQuickCreateFile;

            $defs = $viewdefs[$module]['base']['view']['recordlist'];

            foreach ($defs['rowactions']['actions'] as $key => $action) {
                if (!empty($action['icon'])) {
                    if (in_array($action['icon'], array_keys($iconMap))) {
                        $defs['rowactions']['actions'][$key]['icon'] = $iconMap[$action['icon']];
                    }
                }
            }

            write_array_to_file(
                "viewdefs['$module']['base']['view']['recordlist']",
                $defs,
                $customQuickCreateFile
            );
        }
    }

    /**
     * Fix icons for subpanels, because 7.6 changed the icon names
     */
    private function fixSubpanelIcons()
    {
        global $moduleList;

        $iconMap = array(
            'icon-eye-open' => 'fa-eye',
        );

        foreach ($moduleList as $module) {
            $customSubpanelFiles = glob("custom/modules/$module/clients/base/views/subpanel*/subpanel*.php");

            foreach ($customSubpanelFiles as $subpanelFile) {
                if (!file_exists($subpanelFile)) {
                    continue;
                }

                require $subpanelFile;
                $defs = $viewdefs[$module]['base']['view'];
                foreach ($defs as $k => $contents) {
                    if (!empty($contents['rowactions']['actions'])) {
                        $fileChanged = false;
                        foreach ($contents['rowactions']['actions'] as $key => $action) {
                            if (!empty($action['icon'])) {
                                if (in_array($action['icon'], array_keys($iconMap))) {
                                    $contents['rowactions']['actions'][$key]['icon'] = $iconMap[$action['icon']];
                                    $fileChanged = true;
                                }
                            }
                        }
                        if ($fileChanged === true) {
                            write_array_to_file(
                                "viewdefs['$module']['base']['view']['" . $k . "']",
                                $contents,
                                $subpanelFile
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Fix icons for quick create menu, because 7.6 changed the icon names
     */
    private function fixQuickCreateIcons()
    {
        global $moduleList;

        $iconMap = array(
            'icon-plus' => 'fa-plus',
            'icon-phone' => 'fa-phone',
            'icon-calendar' => 'fa-calendar',
        );

        foreach ($moduleList as $module) {
            $customQuickCreateFile = "custom/modules/$module/clients/base/menus/quickcreate/quickcreate.php";

            if (!file_exists($customQuickCreateFile)) {
                continue;
            }

            require $customQuickCreateFile;

            $defs = $viewdefs[$module]['base']['menu']['quickcreate'];

            if (!empty($defs['icon'])) {
                if (in_array($defs['icon'], array_keys($iconMap))) {
                    $defs['icon'] = $iconMap[$defs['icon']];
                }
            }

            write_array_to_file(
                "viewdefs['$module']['base']['menu']['quickcreate']",
                $defs,
                $customQuickCreateFile
            );
        }
    }

    /**
     * Adds missing `sticky_resizable_columns` flag on custom list views.
     *
     * @param string $viewName The name of the view.
     */
    private function addStickyResizableColumnsFlag($viewName) {
        $file = 'clients/base/' . $viewName . '/' . $viewName .'.php';
        $customFile = 'custom/' . $file;
        if (!file_exists($file) || !file_exists($customFile)) {
            return;
        }

        require $customFile;
        $customMeta = $viewdefs['base']['view'][$viewName];
        if (isset($customMeta['sticky_resizable_columns'])) {
            return;
        }

        require $file;
        $defaultMeta = $viewdefs['base']['view'][$viewName];
        if (!isset($defaultMeta['sticky_resizable_columns'])) {
            return;
        }

        $customMeta['sticky_resizable_columns'] = $defaultMeta['sticky_resizable_columns'];
        write_array_to_file(
            "viewdefs['base']['view']['$viewName']",
            $customMeta,
            $customFile
        );
    }
}
