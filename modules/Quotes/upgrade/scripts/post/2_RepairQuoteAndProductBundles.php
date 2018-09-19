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
class SugarUpgradeRepairQuoteAndProductBundles extends UpgradeScript
{
    public $order = 2050;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // Bug 66658, 66795, 65573 update scripts
        if (version_compare($this->from_version, '6.7.2', '>') && version_compare($this->from_version, '6.7.6', '<')) {
            $this->fixProductBundleIndexes();
            $this->fixQuoteAndProductBundleValues();
        }
    }

    /**
     * Updates currency values for Product Bundles and Quotes based on the Products table
     */
    public function fixQuoteAndProductBundleValues()
    {

        // Collect all quote, product_bundle and products data
        $sql = "
            SELECT
            PBQ.quote_id AS quote_id,
            PBQ.bundle_id AS bundle_id,
            Q.currency_id AS currency_id,
            PB.shipping as shipping,
            P.quantity AS quantity,
            P.discount_price AS discount_price,
            P.discount_amount AS discount_amount,
            P.discount_select AS discount_select,
            T.value AS tax_value,
            P.tax_class AS tax_class
            FROM
            quotes Q LEFT JOIN taxrates T ON Q.taxrate_id = T.id,
            product_bundles PB,
            product_bundle_quote PBQ,
            product_bundle_product PBP,
            products P
            WHERE
            Q.id = PBQ.quote_id
            AND PBQ.bundle_id = PB.id
            AND PBQ.bundle_id = PBP.bundle_id
            AND PBP.product_id = P.id
        ";

        $bundles = array();
        $quotes = array();

        $result = $this->db->query($sql);
        while ($row = $this->db->fetchByAssoc($result)) {
            if (empty($bundles[$row['bundle_id']])) {
                $bundles[$row['bundle_id']] = array(
                    'tax' => 0,
                    'subtotal' => 0,
                    'deal_tot' => 0,
                    'new_sub' => 0,
                );
            }

            $subtotal = $row['quantity'] * $row['discount_price'];
            if ($row['discount_select'] != 1) {
                $deal_tot = $row['quantity'] * $row['discount_amount'];
            } else {
                $deal_tot = $row['quantity'] * ($row['discount_amount'] * $row['discount_price'] / 100);
            }
            $new_sub = $subtotal - $deal_tot;

            // Calculate the aggregate fields
            $bundles[$row['bundle_id']]['subtotal'] += $subtotal;
            $bundles[$row['bundle_id']]['deal_tot'] += $deal_tot;
            $bundles[$row['bundle_id']]['new_sub'] += $new_sub;

            // If field is taxable, add tax for it
            if ($row['tax_class'] == 'Taxable') {
                $bundles[$row['bundle_id']]['tax'] += ($row['tax_value'] * $new_sub) / 100;
            }

            $bundles[$row['bundle_id']]['shipping'] = $row['shipping'];
            $bundles[$row['bundle_id']]['currency_id'] = $row['currency_id'];
            $bundles[$row['bundle_id']]['quote_id'] = $row['quote_id'];
            $quotes[$row['quote_id']] = array(
                'tax' => 0,
                'subtotal' => 0,
                'deal_tot' => 0,
                'new_sub' => 0,
                'shipping' => 0,
                'total' => 0,
                'tax_usdollar' => 0,
                'subtotal_usdollar' => 0,
                'deal_tot_usdollar' => 0,
                'new_sub_usdollar' => 0,
                'shipping_usdollar' => 0,
                'total_usdollar' => 0,
            );
        }

        // Build total and _usdollar fields
        foreach ($bundles as $id => $value) {
            $bundles[$id]['total'] = $value['tax'] + $value['new_sub'] + $value['shipping'];

            $bundles[$id]['tax_usdollar'] = SugarCurrency::convertAmountToBase(
                $value['tax'],
                $value['currency_id']
            );
            $bundles[$id]['subtotal_usdollar'] = SugarCurrency::convertAmountToBase(
                $value['subtotal'],
                $value['currency_id']
            );
            $bundles[$id]['deal_tot_usdollar'] = SugarCurrency::convertAmountToBase(
                $value['deal_tot'],
                $value['currency_id']
            );
            $bundles[$id]['new_sub_usdollar'] = SugarCurrency::convertAmountToBase(
                $value['new_sub'],
                $value['currency_id']
            );
            $bundles[$id]['shipping_usdollar'] = SugarCurrency::convertAmountToBase(
                $bundles[$id]['shipping'],
                $value['currency_id']
            );
            $bundles[$id]['total_usdollar'] = SugarCurrency::convertAmountToBase(
                $bundles[$id]['total'],
                $value['currency_id']
            );
        }

        // Cycle through the bundles and update the values for them
        foreach ($bundles as $id => $values) {
            $quoteId = $values['quote_id'];
            unset($values['currency_id']);
            unset($values['quote_id']);

            // Build the fields to be updated
            $sqlFields = array();
            foreach ($values as $fieldId => $fieldValue) {
                $sqlFields[] = "$fieldId = '{$fieldValue}'";
                // Fill in quote values
                $quotes[$quoteId][$fieldId] += $fieldValue;
            }

            // Update the product_bundle values
            $sql = "UPDATE product_bundles SET "
                . implode(', ', $sqlFields)
                . " WHERE id = '$id'";
            $this->db->query($sql);
        }

        // Cycle through the quotes and update the values for them
        foreach ($quotes as $id => $values) {
            // Build the fields to be updated
            $sqlFields = array();
            foreach ($values as $fieldId => $fieldValue) {
                $sqlFields[] = "$fieldId = '{$fieldValue}'";
            }

            // Update the product_bundle values
            $sql = "UPDATE quotes SET "
                . implode(', ', $sqlFields)
                . " WHERE id = '$id'";
            $this->db->query($sql);
        }
    }

    /**
     * Upgrade script to fix duplicate product bundle indexes
     */
    public function fixProductBundleIndexes()
    {

        // Pick all product bundles with duplicate indexes
        $result = $this->db->query(
            "
            SELECT *
            FROM product_bundle_quote
            WHERE quote_id IN
                (
                    SELECT quote_id
                    FROM product_bundle_quote
                    GROUP BY quote_id, bundle_index
                    HAVING count(quote_id) > 1
                )
            ORDER BY quote_id, bundle_index
            "
        );

        $bundleQuoteForUpdate = array();
        $counters = array();

        while ($row = $this->db->fetchByAssoc($result)) {
            // Create/Increase the counters for the bundles
            if (empty($counters[$row['quote_id']])) {
                $counters[$row['quote_id']] = 1;
            } else {
                $counters[$row['quote_id']]++;
            }

            // Save records as 'id' of product_bundle_quote to update, and the new bundle_index value
            $bundleQuoteForUpdate[$row['id']] = $counters[$row['quote_id']];
        }

        foreach ($bundleQuoteForUpdate as $id => $value) {
            $this->db->query(
                "
            UPDATE product_bundle_quote
            SET bundle_index = {$value}
            WHERE id = '{$id}'
            "
            );
        }
    }

}
