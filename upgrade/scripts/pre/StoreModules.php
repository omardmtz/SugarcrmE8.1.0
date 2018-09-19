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
  * Store old modules list so we could use it to compare to new modules list
  * and update display tabs, etc.
  */
class SugarUpgradeStoreModules extends UpgradeScript
{
    public $order = 400;
    // DB because DB scripts may need the data
    public $type = self::UPGRADE_DB;

    public function run()
    {
        include 'include/modules.php';
        $this->upgrader->state['old_modules'] = $moduleList;
        include 'include/language/en_us.lang.php';
        $this->upgrader->state['old_moduleList'] = $app_list_strings['moduleList'];
    }
}
