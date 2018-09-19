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
 * Create portal config if does not exist
 */
class SugarUpgradePortalConfig extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (!$this->toFlavor('ent')) {
            return;
        }

        require_once 'ModuleInstall/ModuleInstaller.php';
        $this->putFile('portal2/config.js', ModuleInstaller::getJSConfig(ModuleInstaller::getPortalConfig()));
    }
}
