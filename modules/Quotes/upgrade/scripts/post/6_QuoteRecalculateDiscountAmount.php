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
class SugarUpgradeQuoteRecalculateDiscountAmount extends UpgradeScript
{
    public $order = 6500;
    public $version = '7.5.0.0';
    public $type = self::UPGRADE_DB;

    public function run()
    {
        // lets make sure the hotfix is not installed
        // the id_name is the key from the package
        $sql = 'select name, date_entered from upgrade_history where id_name = ' . $this->db->quoted('1407215153');
        $result = $this->db->fetchOne($sql);

        if ($result !== false) {
            $this->log("Skipping Discount Price Recalculate as Hotfix '{$result['name']}' was applied on {$result['date_entered']}");
            return 1;
        }

        if (version_compare($this->from_version, '7.5.0.0', '<')) {
            // Collect all quote, product_bundle and products data
            $sql = "UPDATE products
                    SET    discount_amount = ( discount_amount * quantity )
                    WHERE  ( discount_select IS NULL
                              OR discount_select = 0 )
                       AND discount_amount != '0.000000'
                       AND ( quote_id IS NOT NULL
                              OR quote_id <> '' )
                       AND deleted = 0";

            $result = $this->db->query($sql);
            $affected = $this->db->getAffectedRowCount($result);

            $this->log($affected . 'Products Rows Updated');
        }
    }
}
