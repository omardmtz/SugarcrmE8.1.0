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
 * Fix labels in language module customizations for labels that were renamed in 7.x
 *
 * Since some labels were renamed in 7.x, instance after upgrade should handle language customizations
 * for such labels.
 *
 * @see CRYS-198 and CRYS-557 for related issues
 */
class SugarUpgradeFixCustomLabelsForCoreModules extends UpgradeScript
{
    public $order = 7800;
    public $type = self::UPGRADE_CUSTOM;

    /** @var ModuleInstaller */
    public $mi;

    /**
     * Labels by module name to change
     *
     * @var array
     */
    public $upgradeLabels = array(
        'Campaigns' => array(
            'LBL_CAMPAIGN_TYPE' => 'LBL_TYPE',
        ),
        'Opportunities' => array(
            'LBL_BEST_CASE'  => 'LBL_BEST',
            'LBL_WORST_CASE' => 'LBL_WORST',
            'LBL_AMOUNT'     => 'LBL_LIKELY',
        ),
        'Forecasts' => array(
            'LBL_BEST_CASE'  => 'LBL_BEST',
            'LBL_WORST_CASE' => 'LBL_WORST',
            'LBL_AMOUNT'     => 'LBL_LIKELY',
        ),
        'Products' => array(
            'LBL_BEST_CASE'  => 'LBL_BEST',
            'LBL_WORST_CASE' => 'LBL_WORST',
            'LBL_AMOUNT'     => 'LBL_LIKELY',
        ),
    );

    public function run()
    {
        $config = SugarConfig::getInstance();

        foreach ($this->upgradeLabels as $module => $labels) {
            $changedLanguages = array();

            // Let's fix labels for all languages
            foreach ($config->get('languages') as $key => $value) {
                if ($this->upgradeModuleLabels($module, $key)) {
                    $changedLanguages[$key] = $key;
                }
            }

            // Rebuild changed languages for module
            if (!empty($changedLanguages)) {
                $this->rebuildLanguages($changedLanguages, $module);
            }

            $changedSubpanels = array();
            // Fix subpanels
            if ($this->upgradeSubpanelModuleLabels($module)) {
                $changedSubpanels[$key] = $key;
            }

            $changedListViews = array();
            // Fix list views
            if ($this->upgradeListViewModuleLabels($module)) {
                $changedListViews[$key] = $key;
            }
        }
    }

    /**
     * Change old labels to new ones in all $module subpanels
     * @param $module
     * @return bool
     */
    public function upgradeSubpanelModuleLabels($module)
    {
        $upgradeLabels = $this->upgradeLabels[$module];
        $upgradeDriver = $this->upgrader;
        $upgraded = false;

        // upgrade viewdefs
        $path = "custom/modules/{$module}/clients/base/views/subpanel-for-*/subpanel-for-*.php";
        foreach (glob($path) as $scanFile) {
            $viewdefs = array();

            include $scanFile;
            // Modification flag
            $changed = false;

            array_walk_recursive(
                $viewdefs,
                function (&$value, $key) use (&$changed, $upgradeLabels, $upgradeDriver, $scanFile) {
                    if ('label' == $key && isset($upgradeLabels[$value])) {
                        $upgradeDriver->log(sprintf(
                            'FixCustomLabelsForCoreModules: Fix label name from "%s" to "%s" in file "%s"',
                            $value,
                            $upgradeLabels[$value],
                            $scanFile
                        ));
                        $value = $upgradeLabels[$value];
                        $changed = true;
                    }
                }
            );
            if ($changed) {
                $this->backupFile($scanFile);
                $upgraded = $upgraded || $changed;
                $keyNames = array();
                $viewdefsWrite = $viewdefs;

                // collect key names for array path "{module}/{platform}/{view_type}/{view}"
                for ($i = 0; $i < 4; $i++) {
                    if (1 != count($viewdefsWrite)) {
                        break;
                    }
                    reset($viewdefsWrite);
                    $keyNames[] = key($viewdefsWrite);
                    $viewdefsWrite = $viewdefsWrite[key($viewdefsWrite)];
                }

                $out = "<?php\n// created: ' . date('Y-m-d H:i:s')\n";
                $out .= override_value_to_string_recursive($keyNames, 'viewdefs', $viewdefsWrite);

                sugar_file_put_contents_atomic($scanFile, $out);
                unset($out);
                unset($viewdefsWrite);
            }
            unset($viewdefs);
        }

        // upgrade subpanel_layout
        $path = "custom/modules/{$module}/metadata/subpanels/*.php";
        foreach (glob($path) as $scanFile) {
            $subpanel_layout = array();
            include $scanFile;
            // Modification flag
            $changed = false;

            array_walk_recursive(
                $subpanel_layout,
                function (&$value, $key) use (&$changed, $upgradeLabels, $upgradeDriver, $scanFile) {
                    if ('vname' == $key && isset($upgradeLabels[$value])) {
                        $upgradeDriver->log(sprintf(
                            'FixCustomLabelsForCoreModules: Fix label name from "%s" to "%s" in file "%s"',
                            $value,
                            $upgradeLabels[$value],
                            $scanFile
                        ));
                        $value = $upgradeLabels[$value];
                        $changed = true;
                    }
                }
            );

            if ($changed) {
                $upgraded = $upgraded || $changed;
                $this->backupFile($scanFile);
                write_array_to_file('subpanel_layout', $subpanel_layout, $scanFile, 'w');
            }
            unset($subpanel_layout);
        }
        return $upgraded;
    }

    /**
     * Change old labels to new ones in $module listviewdefs
     * @param $module
     * @return bool
     */
    public function upgradeListViewModuleLabels($module)
    {
        $upgradeLabels = $this->upgradeLabels[$module];
        $upgradeDriver = $this->upgrader;
        $upgraded = false;

        // upgrade viewdefs
        $scanFile = "custom/modules/{$module}/clients/base/views/list/list.php";
        if (file_exists($scanFile)) {
            $changed = false;
            $viewdefs = array();

            include $scanFile;

            array_walk_recursive(
                $viewdefs,
                function (&$value, $key) use (&$changed, $upgradeLabels, $upgradeDriver, $scanFile) {
                    if ('label' == $key && isset($upgradeLabels[$value])) {
                        $upgradeDriver->log(sprintf(
                            'FixCustomLabelsForCoreModules: Fix label name from "%s" to "%s" in file "%s"',
                            $value,
                            $upgradeLabels[$value],
                            $scanFile
                        ));
                        $value = $upgradeLabels[$value];
                        $changed = true;
                    }
                }
            );

            if ($changed) {
                $upgraded = $upgraded || $changed;
                $this->backupFile($scanFile);
                write_array_to_file('viewdefs', $viewdefs, $scanFile, 'w');
            }
        }

        return $upgraded;
    }

    /**
     * For all available languages change old label names to new one
     *
     * @param $module string
     * @param $language string
     * @return bool
     */
    public function upgradeModuleLabels($module, $language)
    {
        $path = 'custom/modules/' . $module . '/language/' . $language. '.lang.php';

        if (file_exists($path) && is_array($this->upgradeLabels[$module])) {
            $mod_strings = array();
            include $path;

            // Modification flag
            $changed = false;

            // Add new label translation based on old label and remove old label
            foreach ($this->upgradeLabels[$module] as $oldLabel => $newLabel) {
                if (isset($mod_strings[$oldLabel]) && empty($mod_strings[$newLabel])) {
                    $this->upgrader->log(
                        sprintf(
                            'FixCustomLabelsForCoreModules: Fix label name from "%s" to "%s" for module "%s"',
                            $oldLabel,
                            $newLabel,
                            $module
                        )
                    );

                    $mod_strings[$newLabel] = $mod_strings[$oldLabel];
                    $changed = true;
                }
            }

            // Save language as changed
            if ($changed) {
                write_array_to_file('mod_strings', $mod_strings, $path, 'w');
            }

            return $changed;
        }

        return false;
    }

    /**
     * Rebuild changes languages for module
     *
     * @param $languages
     * @param $module
     */
    protected function rebuildLanguages($languages, $module)
    {
        if (!$this->mi) {
            $this->mi = new ModuleInstaller();
            $this->mi->silent = true;
        }

        $this->mi->rebuild_languages($languages, array($module));
    }
}
