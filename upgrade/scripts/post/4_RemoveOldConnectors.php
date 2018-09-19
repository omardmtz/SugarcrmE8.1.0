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

class SugarUpgradeRemoveOldConnectors extends UpgradeScript
{
    public $order = 4200;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $oldConnectors = array('ext_soap_hoovers', 'ext_rest_insideview');
            $connectorRemoved = false;
            $fileName = 'custom/modules/Connectors/metadata/display_config.php';

            include($fileName);

            foreach ($modules_sources as $module => $connectors) {
                foreach ($connectors as $connector) {
                    if (in_array($connector, $oldConnectors)) {
                        unset($modules_sources[$module][$connector]);
                        $connectorRemoved = true;
                    }
                }
                if (empty($modules_sources[$module])) {
                    unset($modules_sources[$module]);
                    $connectorRemoved = true;
                }
            }

            if ($connectorRemoved) {
                write_array_to_file('modules_sources', $modules_sources, $fileName);
            }
        }
    }
}
