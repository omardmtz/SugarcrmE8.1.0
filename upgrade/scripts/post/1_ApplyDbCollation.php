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
 * Applies collation defined in DB configuration to all DB objects for consistency
 */
class SugarUpgradeApplyDbCollation extends UpgradeScript
{
    public $order = 1010;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.6.2', '>=')) {
            return;
        }

        $db = DBManagerFactory::getInstance();
        if (!$db->supports('collation')) {
            return;
        }

        $collation = $db->getOption('collation');
        if (!$collation) {
            return;
        }

        $db->setCollation($collation);
    }
}
