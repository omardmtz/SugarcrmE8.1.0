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
 * Move custom activity stream meta data from custom/modules/Activities/clients to custom/modules/ActivityStream/Activities/clients
 */
class SugarUpgradeMoveActivityStreamCustomMetaData extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $originalPath = 'custom/modules/Activities/clients';
        $newPath = 'custom/modules/ActivityStream/Activities/clients';
        if (!version_compare($this->from_version, '7.0.0', ">=")) {
            return;
        }

        if (!is_dir($originalPath) || is_dir($newPath)) {
            return;
        }

        if (!is_dir($newPath)) {
            sugar_mkdir($newPath, null, true);
        }

        $this->log("MoveActivityStreamCustomMetaData: Renaming {$originalPath}");
        rename($originalPath, $newPath);
        $this->log("MoveActivityStreamCustomMetaData: Renamed {$originalPath} to {$newPath}");

        $this->upgrader->fileToDelete('modules/Activities/clients', $this);
        $this->upgrader->fileToDelete('custom/modules/Activities/clients', $this);
    }
}
