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
 * Files to delete for 7.0 - old SAML libs, now moved to vendor/
 */
class SugarUpgradeOldSamlLibs extends UpgradeScript
{
    public $order = 7000;
    public $version = '7.0.0';
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        $this->upgrader->fileToDelete('modules/Users/authentication/SAMLAuthenticate/lib', $this);
    }
}
