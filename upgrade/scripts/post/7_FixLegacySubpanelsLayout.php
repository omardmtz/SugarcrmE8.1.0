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
 * Not able to save Account > Member Organization subpanel after upgrading
 *
 * @see PAT-2031 for related issue
 */
class SugarUpgradeFixLegacySubpanelsLayout extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;
    private $mdc;

    public function run()
    {
        if (!version_compare($this->from_version, '7.8.1.0', '<')) {
            return;
        }

        $files = array_merge(
            glob('custom/modules/*/Ext/clients/base/layouts/subpanels/subpanels.ext.php'),
            glob('custom/Extension/modules/*/Ext/clients/base/layouts/subpanels/*.php')
        );

        $this->mdc = new MetaDataConverter();

        foreach ($files as $file) {
            $this->process($file);
        }
    }

    /**
     * Fix broken layout defs
     * @param string $file
     */
    public function process($file)
    {
        global $beanList;

        if (is_dir($file)) {
            return;
        }

        $torewrite = false;
        $viewdefs = array();
        require $file;

        if (!empty($viewdefs)) {
            $module = key($viewdefs);

            foreach ($viewdefs[$module]['base']['layout']['subpanels']['components'] as $i => $component) {
                if (key($component) != 'override_subpanel_list_view') {
                    continue;
                }

                $subpanelview = $component['override_subpanel_list_view']['view'];
                $relLinkName = ucfirst($component['override_subpanel_list_view']['link']);
                $moduleName = $module;
                // if related link is in the bean list, we check if subpanel file exists under the related bean module
                // otherwise, we check whether subpanel file exists under the current bean module
                if (!empty($beanList[$relLinkName])) {
                    $moduleName = $relLinkName;
                }
                $subpanelviewfile = "custom/modules/{$moduleName}/clients/base/views/{$subpanelview}/{$subpanelview}" .
                    '.php';
                if (!file_exists($subpanelviewfile)) {
                    // fix the wrong view that is written during upgrade to 7.7
                    $subpanelname = $this->mdc->fromLegacySubpanelName("For{$module}");
                    $newSubpanelname = $subpanelname . '-' . strtolower($relLinkName);
                    $subpanelnamefile = "custom/modules/{$moduleName}/clients/base/views/{$newSubpanelname}/" .
                        "{$newSubpanelname}.php";
                    if (file_exists($subpanelnamefile)) {
                        $torewrite = true;
                        $viewdefs[$module]['base']['layout']['subpanels']['components'][$i]['override_subpanel_list_view']['view'] = $newSubpanelname;
                    } else {
                        // this could be carried over from old structures
                        $subpanelnamefile = "custom/modules/{$moduleName}/clients/base/views/{$subpanelname}/" .
                            "{$subpanelname}.php";
                        if (file_exists($subpanelnamefile)) {
                            $torewrite = true;
                            $viewdefs[$module]['base']['layout']['subpanels']['components'][$i]['override_subpanel_list_view']['view'] = $subpanelname;
                        }
                    }
                }
            }
        }

        if ($torewrite) {
            $strToFile = "<?php\n\n";

            foreach ($viewdefs[$module]['base']['layout']['subpanels']['components'][$i] as $key => $value) {
                $strToFile .= "\$viewdefs['{$module}']['base']['layout']['subpanels']['components'][] = " . var_export(
                    array($key => $value),
                    true
                ) . ";\n";
            }

            $this->upgrader->backupFile($file);
            sugar_file_put_contents_atomic($file, $strToFile);
        }
    }
}
