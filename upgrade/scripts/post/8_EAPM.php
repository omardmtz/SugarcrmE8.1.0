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
 * Update connectors & refresh connector metadata files
 */
class SugarUpgradeEAPM extends UpgradeScript
{
    public $order = 8000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        // mark any eapm deleted for connectors we have removed
        $removedEAPMs =array(
            'Facebook',
        );
        $inStr = implode(',', $removedEAPMs);
        $query = "UPDATE eapm " .
            "SET deleted = 1 " .
            "WHERE application IN ('" .$inStr. "')";
        $this->db->query($query);
    }
}
