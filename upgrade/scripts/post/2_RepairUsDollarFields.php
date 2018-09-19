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
class SugarUpgradeRepairUsDollarFields extends UpgradeScript
{
    public $order = 2050;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // Bug 66658, 66795, 65573 update scripts
        if (version_compare($this->from_version, '6.7.2', '>') && version_compare($this->from_version, '6.7.6', '<')) {
            $this->fixUSDollarFields();
        }

        // only affects upgrades from Sugar 7.x
        if (version_compare($this->from_version, '7.0', '<')) {
            return;
        }

        // Fix ProductTemplates
        $fields = array(
            'list_price' => 'list_usdollar',
            'cost_price' => 'cost_usdollar',
            'discount_price' => 'discount_usdollar',
        );
        foreach ($fields as $field => $fieldUSDollar) {
            $this->db->query(
                "
            UPDATE product_templates
            SET {$fieldUSDollar} = {$field} / base_rate
            WHERE base_rate > 0
            AND " . $this->dbRoundString("{$field}/base_rate") . " <> " . $this->dbRoundString($fieldUSDollar) . "
            AND deleted = 0
            "
            );
        }

        // Fix ProductBundles
        $fields = array(
            'total' => 'total_usdollar',
            'subtotal' => 'subtotal_usdollar',
            'shipping' => 'shipping_usdollar',
            'deal_tot' => 'deal_tot_usdollar',
            'new_sub' => 'new_sub_usdollar',
            'tax' => 'tax_usdollar',
        );
        foreach ($fields as $field => $fieldUSDollar) {
            $this->db->query(
                "
            UPDATE product_bundles
            SET {$fieldUSDollar} = {$field} / base_rate
            WHERE base_rate > 0
            AND " . $this->dbRoundString("{$field}/base_rate") . " <> " . $this->dbRoundString($fieldUSDollar) . "
            AND deleted = 0
            "
            );
        }

    }

    public function fixUSDollarFields()
    {
        // Fix Opportunities
        $this->db->query(
            "
            UPDATE opportunities
            SET amount_usdollar = amount / base_rate
            WHERE base_rate > 0
            AND " . $this->dbRoundString("amount/base_rate") . " <> " . $this->dbRoundString('amount_usdollar') . "
            AND deleted = 0
            "
        );

        // Fix Products
        $fields = array(
            'deal_calc' => 'deal_calc_usdollar',
            'discount_amount' => 'discount_amount_usdollar',
            'cost_price' => 'cost_usdollar',
            'discount_price' => 'discount_usdollar',
            'list_price' => 'list_usdollar',
            'book_value' => 'book_value_usdollar',
        );
        foreach ($fields as $field => $fieldUSDollar) {
            $this->db->query(
                "UPDATE products
            SET {$fieldUSDollar} = {$field} / base_rate
            WHERE base_rate > 0
            AND " . $this->dbRoundString("{$field}/base_rate") . " <> " . $this->dbRoundString($fieldUSDollar) . "
            AND deleted = 0
            "
            );
        }
    }

    /**
     * convert a string into a round function string with a precision of 6
     *
     * @param string $string
     * @return string
     */
    protected function dbRoundString($string)
    {
        return $this->db->convert($string, 'round', array(6));
    }
}
