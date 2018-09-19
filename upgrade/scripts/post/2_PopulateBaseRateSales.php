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

class SugarUpgradePopulateBaseRateSales extends UpgradeScript
{
    public $order = 2200;
    public $type = self::UPGRADE_DB;

    /**
     * Run the Upgrade Task
     *
     * initialize the base_rate field
     */
    public function run()
    {
        // only run this when coming from a 6.x upgrade
        if (!version_compare($this->from_version, '7.0.0', "<")) {
            return;
        }

        $this->log('Populating custom sales module base_rate value from conversion_rate.');

        global $beanList;

        // update all the custom sales module's base_rate fields
        foreach ($beanList as $moduleName => $bean) {
            $module = BeanFactory::newBean($moduleName);
            if ($module instanceof Sale) {
                $this->updateBaseRate($module->table_name);
            }
        }

        $this->log('Done populating custom sales module base_rate value from conversion_rate.');
    }

    public function updateBaseRate($table)
    {
        // first set base currencies to 1.0
        $sql = "update {$table} set base_rate = 1.0 where currency_id = '-99'";
        $this->db->query($sql);

        $sql = "update {$table}
                set base_rate = (
                  select currencies.conversion_rate
                  from currencies
                  where currencies.id = {$table}.currency_id
                )
                where {$table}.base_rate IS NULL and exists (
                  select *
                  from currencies
                  where currencies.id = {$table}.currency_id
                )";
        $this->db->query($sql);

    }
}
