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
 * Remove old connectors in 7.0.0 and above
 */
class SugarUpgradeRemoveOldCustomConnectors extends UpgradeScript
{
    /**
     * @var int
     */
    public $order = 7000;

    /**
     * @var int
     */
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // according to INT-20 - old core connectors was deleted in 7.0.0
        if (!version_compare($this->to_version, '7.0.0', '>=')) {
            return;
        }

        $this->upgrader->fileToDelete(array(
            'custom/modules/Connectors/connectors/sources/ext/rest/zoominfocompany',
            'custom/modules/Connectors/connectors/sources/ext/rest/zoominfoperson',
            'custom/modules/Connectors/connectors/sources/ext/rest/linkedin',
            'custom/modules/Connectors/connectors/sources/ext/rest/insideview',
            'custom/modules/Connectors/connectors/sources/ext/eapm/facebook',
            'custom/modules/Connectors/connectors/sources/ext/soap/hoovers',
        ), $this);
    }
}
