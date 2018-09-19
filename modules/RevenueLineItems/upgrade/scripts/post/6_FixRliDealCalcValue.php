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

class SugarUpgradeFixRliDealCalcValue extends UpgradeScript
{

    public $order = 6510;
    public $version = '7.5.0.0';
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.5.0.0', '<')) {

            $sql = "UPDATE revenue_line_items
                SET deal_calc =
                  CASE
                    WHEN discount_select = 1
                    THEN (discount_price * quantity) * (discount_amount / 100)
                    ELSE discount_amount
                  END,
                deal_calc_usdollar =
                  (CASE
                    WHEN discount_select = 1
                    THEN (discount_price * quantity) * (discount_amount / 100)
                    ELSE discount_amount
                  END)/base_rate";

            $results = $this->db->query($sql);
            $total_updated = $this->db->getAffectedRowCount($results);

            $this->log('Updated ' . $total_updated . ' revenue_line_items deal_calc values');
        }
    }
}
