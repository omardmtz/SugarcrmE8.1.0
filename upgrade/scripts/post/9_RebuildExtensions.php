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
 * Register upgrade with the system
 */
class SugarUpgradeRebuildExtensions extends UpgradeScript
{
    public $order = 9500;

    public function run()
    {
        // we just finished with the layouts, we need to rebuild the extensions
        include "include/modules.php";
        $rac = new RepairAndClear('', '', false, false);
        $rac->rebuildExtensions();
    }
}
