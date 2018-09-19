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
 * If any of the custom files exist that would show the Project Module, they should be deleted
 */
class SugarUpgradeProjectRemoveUnhideCustomFiles extends UpgradeScript
{
    public $order = 7999;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $files = array();
        if (version_compare($this->from_version, '7.5', '<')) {
            $files[] = 'custom/Extension/application/Ext/Include/project_unhide.php';
            $files[] = 'custom/Extension/application/Ext/Language/en_us-project_unhide.php';
            $files[] = 'custom/Extension/application/Ext/Include/project_unhide.php';
            $files[] = 'custom/Extension/modules/Opportunities/Ext/clients/base/layouts/subpanels/project_unhide.php';
            $files[] = 'modules/Project/upgrade/scripts/post/6_ProjectShowModule.php';
        }

        $this->upgrader->fileToDelete($files, $this);
    }
}
