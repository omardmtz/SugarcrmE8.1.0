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
 * Create empty file custom.less
 */
class SugarUpgradeCreateCustomless extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        if (!version_compare($this->from_version, '6.7.0', '<'))
        {
            return;
        }

        if(!file_exists('styleguide/less/clients/base/custom.less')) {
            $this->createFile('styleguide/less/clients/base/custom.less');
        }
    }
}
