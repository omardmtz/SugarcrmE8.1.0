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

class SugarUpgradeOpportunityFixRevenueLineItemsHidden extends UpgradeScript
{
    public $order = 2200;
    public $version = '7.6.0.0';
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.0', '<') || (
                version_compare($this->from_version, '7.0', '>=') &&
                version_compare($this->from_version, '7.6', '<') &&
                in_array(strtolower($this->from_flavor), array('pro', 'corp')))) {

            $this->setConfigSetting('hide_subpanels', 'revenuelineitems');
        }
    }

    protected function setConfigSetting($setting, $value, $show = true)
    {
        $sql = "SELECT value FROM config
                WHERE category = 'MySettings'
                AND name = '" . $setting . "'
                AND (platform = 'base' OR platform IS NULL OR platform = '')";
        $results = $this->db->query($sql);

        while ($row = $this->db->fetchRow($results)) {
            $tabArray = unserialize(base64_decode($row['value']));
            // find the key
            $key = array_search($value, $tabArray);
            if ($key === false && $show === true) {
                $tabArray[] = $value;
            } elseif ($key !== false & $show === false) {
                unset($tabArray[$key]);
            }

            $sql = "UPDATE config
                SET value = '" . base64_encode(serialize($tabArray)) . "'
                WHERE category = 'MySettings'
                AND name = '" . $setting . "'
                AND (platform = 'base' OR platform IS NULL OR platform = '')";
            $this->db->query($sql);
        }
    }
}
