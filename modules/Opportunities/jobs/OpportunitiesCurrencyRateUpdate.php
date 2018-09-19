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
 * OpportunitiesCurrencyRateUpdate
 *
 * A class for updating currency rates on specified database table columns
 * when a currency conversion rate is updated by the administrator.
 *
 */
class OpportunitiesCurrencyRateUpdate extends CurrencyRateUpdateAbstract
{
    /**
     * constructor
     *
     * @access public
     */
    public function __construct()
    {
        // set rate field definitions
        $this->addRateColumnDefinition('opportunities', 'base_rate');
        // set usdollar field definitions
        $this->addUsDollarColumnDefinition('opportunities', 'amount', 'amount_usdollar');
    }

    /**
     * doCustomUpdateRate
     *
     * Return true to skip updates for this module.
     * Return false to do default update of base_rate column.
     * To custom processing, do here and return true.
     *
     * @access public
     * @param  string $table
     * @param  string $column
     * @param  string $currencyId
     * @return boolean true if custom processing was done
     */
    public function doCustomUpdateRate($table, $column, $currencyId)
    {
        // get the conversion rate
        $rate = $this->db->getOne(sprintf("SELECT conversion_rate FROM currencies WHERE id = '%s'", $currencyId));

        $stages = $this->getClosedStages();

        // setup SQL statement
        $query = sprintf("UPDATE %s SET %s = '%s'
        WHERE sales_stage NOT IN ('%s')
        AND currency_id = '%s'",
            $table,
            $column,
            $rate,
            implode("','", $stages),
            $currencyId
        );
        // execute
        $result = $this->db->query(
            $query,
            true,
            string_format(
                $GLOBALS['app_strings']['ERR_DB_QUERY'],
                array('OpportunitiesCurrencyRateUpdate',$query
                )
            )
        );
        return !empty($result);
    }

    /**
     * doCustomUpdateUsDollarRate
     *
     * Return true to skip updates for this module.
     * Return false to do default update of amount * base_rate = usdollar
     * To custom processing, do here and return true.
     *
     * @access public
     * @param  string    $tableName
     * @param  string    $usDollarColumn
     * @param  string    $amountColumn
     * @param  string    $currencyId
     * @return boolean true if custom processing was done
     */
    public function doCustomUpdateUsDollarRate($tableName, $usDollarColumn, $amountColumn, $currencyId)
    {

        $stages = $this->getClosedStages();

        // setup SQL statement
        $query = sprintf("UPDATE %s SET %s = %s / base_rate
            WHERE sales_stage NOT IN ('%s')
            AND currency_id = '%s'",
            $tableName,
            $usDollarColumn,
            $amountColumn,
            implode("','", $stages),
            $currencyId
        );
        // execute
        $result = $this->db->query(
            $query,
            true,
            string_format(
                $GLOBALS['app_strings']['ERR_DB_QUERY'],
                array('OpportunitiesCurrencyRateUpdate', $query)
            )
        );
        return !empty($result);
    }

    /**
     * getClosedStages
     *
     * Return an array of closed stage names from the admin bean.
     *
     * @access public
     * @return array array of closed stage values
     */
    public function getClosedStages()
    {
        static $opp;
        if (!isset($opp)) {
            $opp = BeanFactory::newBean('Opportunities');
        }
        return $opp->getClosedStages();
    }


}