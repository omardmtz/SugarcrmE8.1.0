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
 * Removes files for fields
 */
class SugarUpgradeRemoveOldFieldFiles extends UpgradeScript
{
    public $order = 4101;
    public $type = self::UPGRADE_CORE;
    public $version = '7.2.1';

    public function run()
    {
        $this->log('Removing old field files');
        // we only need to remove these files if
        // the from_version is less than 7.2.1 but greater or equal to 6.7.0
        if (version_compare($this->from_version, '7.2.1', '<')
            && version_compare($this->from_version, '6.7.0', '>=')
        ) {
            $this->log('Removing files for 6.7.0 -> 7.2.1');
            // files to delete
            $files = array(
                'clients/base/fields/date/default.hbs',
                'clients/base/fields/date/detail.hbs',
                'clients/base/fields/date/list.hbs',
                'clients/base/fields/datetimecombo/default.hbs',
                'clients/base/fields/datetimecombo/detail.hbs',
                'clients/base/fields/datetimecombo/list.hbs',
                'modules/Notifications/clients/base/fields/datetimecombo/datetimecombo.js',
                'modules/Notifications/clients/base/fields/datetimecombo/detail.hbs',
            );

            $this->upgrader->fileToDelete($files, $this);
        }

        $this->log('Done removing old field files');
    }
}
