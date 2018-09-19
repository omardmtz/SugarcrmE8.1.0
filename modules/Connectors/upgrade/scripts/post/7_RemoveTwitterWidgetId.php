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
 * Remove twitter widget id from custom config file when upgrading from 6.7.5+ to 7
 */
class SugarUpgradeRemoveTwitterWidgetId extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // must be upgrading from 6.7.5+
        if (!version_compare($this->from_version, '6.7.4', '>') || !version_compare($this->from_version, '7.0.0', '<')) {
            return;
        }

        // remove data_widget_id from custom config file
        $source = SourceFactory::getSource("ext_rest_twitter");
        if ($source && $source->getProperty('data_widget_id')) {
            $properties = $source->getProperties();
            unset($properties['data_widget_id']);
            $source->setProperties($properties);
            $source->saveConfig();
        }
    }
}
