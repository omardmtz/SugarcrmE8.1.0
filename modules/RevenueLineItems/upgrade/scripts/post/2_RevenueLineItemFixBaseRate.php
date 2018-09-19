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

class SugarUpgradeRevenueLineItemFixBaseRate extends UpgradeScript
{
    public $order = 2150;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.0.0', '>=') &&
            version_compare($this->from_version, '7.2.0', '<=') && $this->toFlavor('ent')) {
            $sql = "UPDATE revenue_line_items SET base_rate = (discount_price/discount_usdollar)
                    WHERE discount_price IS NOT NULL AND discount_usdollar IS NOT NULL;";
            $r = $this->db->query($sql);

            $this->log('Updated base_rate on ' . $this->db->getAffectedRowCount($r) . ' rows');

            // update all the usd fields
            $sql = 'UPDATE revenue_line_items SET
                discount_amount_usdollar = (discount_amount/base_rate),
                deal_calc_usdollar = (deal_calc/base_rate),
                cost_usdollar = (cost_price/base_rate),
                discount_usdollar = (discount_price/base_rate),
                list_usdollar = (list_price/base_rate),
                book_value_usdollar = (book_value/base_rate)
              ';
            $r = $this->db->query($sql);
            $this->log('Updated usdollar fields on ' . $this->db->getAffectedRowCount($r) . ' rows');
        }
    }
}
