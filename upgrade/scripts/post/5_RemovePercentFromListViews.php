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

class SugarUpgradeRemovePercentFromListViews extends UpgradeScript
{
    public $order = 5000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.9.0.0', '>=')) {
            return;
        }

        $files = array_merge(
            // customized views including Studio customizations
            glob('custom/modules/*/metadata/listviewdefs.php'),
            // non-deployed module builder customizations
            glob('custom/modulebuilder/packages/*/modules/*/metadata/listviewdefs.php'),
            // deployed module builder customizations
            glob('modules/*_*/metadata/listviewdefs.php')
        );

        foreach ($files as $file) {
            $listViewDefs = array();
            $this->log('Reading ' . $file);
            include $file;

            $module = key($listViewDefs);

            if (!$module) {
                $this->log('Cannot find module key in ' . $file);
                continue;
            }

            $found = false;
            foreach ($listViewDefs[$module] as $column => &$definition) {
                if (isset($definition['width']) && substr($definition['width'], -1) === '%') {
                    $this->log('Found % sign in the ' . $column . ' width definition');
                    $definition['width'] = substr($definition['width'], 0, -1);
                    $found = true;
                }
            }
            unset($definition);

            if ($found) {
                $this->log('Saving modified definitions');
                write_array_to_file('listViewDefs[' . var_export($module, true) . ']', $listViewDefs[$module], $file);
            } else {
                $this->log('No changes have been made');
            }
        }
    }
}
