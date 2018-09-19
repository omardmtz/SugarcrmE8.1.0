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
  * Store old BWC modules list so we could use it to compare to new BWC modules list
  * and upgrade those which are not BWC anymore.
  */
class SugarUpgradeRegisterBwcModules extends UpgradeScript
{
    public $type = self::UPGRADE_CUSTOM;
    public $order = 400;

    public function run()
    {
        $this->upgrader->state['bwc_modules'] = $this->getBwcModules();
    }

    protected function getBwcModules()
    {
        $bwcModules = array();
        include 'include/modules.php';

        return $bwcModules;
    }
}
