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
 * Converts custom KB "popupdefs.php" to sidecar "selection-list.php".
 * @todo Should be removed when/if 7_ConvertPopupListView.php will be enabled for all versions and modules
 */
class SugarUpgradeConvertKBPopupListView extends SugarUpgradeConvertPopupListView
{
    /**
     * @inheritdoc
     */
    public $version = '7.7';

    /**
     * @var string
     */
    public $module = 'KBContents';

    /**
     * Converts only listViewDefs.
     * Old format contains only default fields, current - default and enabled.
     */
    public function run()
    {
        if (!version_compare($this->from_version, '7.7', '<')) {
            return;
        }

        $this->convertModuleListViewDefs($this->module);
    }
}
