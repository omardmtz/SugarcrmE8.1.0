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

class SugarUpgradeHomeRemoveFiles extends UpgradeScript
{
    /**
     * When to run this upgrade script
     * @var int
     */
    public $order = 8501;

    /**
     * Type of upgrade script
     *
     * @var int
     */
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        $files = array();
        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $files[] = 'modules/Home/clients/base/layouts/list/list.js';
            $files[] = 'modules/Home/clients/base/layouts/record/record.js';
        }

        if (!empty($files)) {
            $this->upgrader->fileToDelete($files, $this);
        }
    }
}
