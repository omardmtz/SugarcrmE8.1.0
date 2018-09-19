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
 * Update license for CE->PRO upgrade
 */
class SugarUpgradeSetLicense extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if(!($this->from_flavor == 'ce' && $this->toFlavor('pro'))) return;
        $admin = new Administration();
		$category = 'license';
		$admin->saveSetting($category, 'users', 0);
        foreach (array('key', 'expire_date') as $k) {
			$admin->saveSetting($category, $k, '');
		}
    }
}
