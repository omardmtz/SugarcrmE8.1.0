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

class SugarUpgradeRemoveDNBConnector extends UpgradeScript
{
    public $order = 7600;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * This is the root of the DNB connector config
     * @var string
     */
    protected $dnbPath = 'custom/modules/Connectors/connectors/sources/ext/rest/dnb';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Only run for instances coming from 7.9.0.0 or lower
        if (version_compare($this->from_version, '7.9.0.0', '>')) {
            return;
        }

        // Log what we are doing...
        $this->log('Remove DNB Connector: About to uninstall connector source config');

        // Remove the source from the connector config cache
        ConnectorUtils::uninstallSource('ext_rest_dnb');

        // Remove the connector path
        $this->log("Preparing to remove the DNB connector artifacts from {$this->dnbPath} ...");
        $this->upgrader->removeDir($this->dnbPath);

        // Clear the autoloader cache, since removeDir doesn't do that
        $this->log("Preparing to clear the autoloader cache ...");
        $this->upgrader->cleanFileCache();

        // Log what we've done
        $this->log('Remove DNB Connector: artifacts removal complete.');
    }
}
