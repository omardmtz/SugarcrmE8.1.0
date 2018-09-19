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
 * This script moves dropdown filters from the custom/include directory to custom/Extension.
 * This is only required when upgrading from a 7.6RC to GA but should be harmless on versions under 7.6
 */
class SugarUpgradeMoveDropdownFilters extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.6.0.0', '<')) {
            $this->moveDropdownFilters();
        }
    }

    /**
     * Migrate the old custom dropdown filters into the new location.
     * Will no-op if the destination directory already exists
     */
    public function moveDropdownFilters()
    {
        $src_dir = 'custom/include/dropdown_filters';
        $dst_dir = 'custom/Extension/application/Ext/DropdownFilters';
        if (is_dir($src_dir) && !file_exists($dst_dir)) {
            mkdir_recursive('custom/Extension/application/Ext');
            rename($src_dir, $dst_dir);
        }
    }
}
