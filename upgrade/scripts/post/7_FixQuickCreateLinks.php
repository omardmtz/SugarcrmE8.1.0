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
 * Removes BWC links from QuickCreate definitions of non-BWC modules
 */
class SugarUpgradeFixQuickCreateLinks extends UpgradeScript
{
    public $order = 7411;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        global $beanList;
        global $bwcModules;

        $nonBwcModules = array_diff(array_keys($beanList), $bwcModules);
        foreach ($nonBwcModules as $module) {
            $fileName = 'custom/modules/' . $module . '/clients/base/menus/quickcreate/quickcreate.php';
            if (!file_exists($fileName)) {
                continue;
            }

            $viewdefs = array();
            include $fileName;
            if (!isset($viewdefs[$module]['base']['menu']['quickcreate'])) {
                continue;
            }

            $metadata = $viewdefs[$module]['base']['menu']['quickcreate'];
            if (!isset($metadata['href'])) {
                continue;
            }

            if (strpos($metadata['href'], '#bwc/') !== 0) {
                continue;
            }

            $this->log('Removing BWC link from QuickCreate definition for ' . $module);
            unset($metadata['href']);
            $arrayName = "viewdefs['{$module}']['base']['menu']['quickcreate']";
            write_array_to_file($arrayName, $metadata, $fileName);
        }
    }
}
