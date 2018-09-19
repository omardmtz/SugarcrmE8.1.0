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

class SugarUpgradeCasesRemoveFiles extends UpgradeScript
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

    /**
     * Lets Run This Upgrade Script!
     */
    public function run()
    {
        $files = array();
        // if we are coming from before 7.2.1, these files need to be deleted
        if (version_compare($this->from_version, '7.2.1', '<')) {
            $files[] = 'modules/Cases/CasesApiHelper.php';
        }

        if (version_compare($this->from_version, '7.8.0.0', '<')) {
            $files[] = 'modules/Cases/clients/base/layouts/tabbed-layout';
        }

        if (!empty($files)) {
            $this->upgrader->fileToDelete($files, $this);
        }
    }
}
