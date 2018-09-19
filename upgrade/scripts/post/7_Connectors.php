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
 * Update connectors & refresh connector metadata files
 */
class SugarUpgradeConnectors extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        require_once('include/connectors/utils/ConnectorUtils.php');
        if(!ConnectorUtils::updateMetaDataFiles()) {
            $this->log('Cannot update metadata files for connectors');
        }

        //Delete the custom connectors.php file if it exists so that it may be properly rebuilt
        if (file_exists('custom/modules/Connectors/metadata/connectors.php')) {
            unlink('custom/modules/Connectors/metadata/connectors.php');
        }
    }
}
