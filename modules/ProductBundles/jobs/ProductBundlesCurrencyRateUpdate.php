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
 * ProductBundlesCurrencyRateUpdate
 *
 * A class for updating currency rates on specified database table columns
 * when a currency conversion rate is updated by the administrator.
 *
 */
class ProductBundlesCurrencyRateUpdate extends CurrencyRateUpdateAbstract
{
    /**
     * constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        // set rate field definitions
        $this->addRateColumnDefinition('product_bundles', 'base_rate');
        // set usdollar field definitions
        $this->addUsDollarColumnDefinition('product_bundles', 'total', 'total_usdollar');
        $this->addUsDollarColumnDefinition('product_bundles', 'subtotal', 'subtotal_usdollar');
        $this->addUsDollarColumnDefinition('product_bundles', 'shipping', 'shipping_usdollar');
        $this->addUsDollarColumnDefinition('product_bundles', 'deal_tot', 'deal_tot_usdollar');
        $this->addUsDollarColumnDefinition('product_bundles', 'new_sub', 'new_sub_usdollar');
        $this->addUsDollarColumnDefinition('product_bundles', 'tax', 'tax_usdollar');
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
        $sq = new SugarQuery();
        $sq->select(array('conversion_rate'));
        $sq->from(BeanFactory::newBean('Currencies'));
        $sq->where()
            ->equals('id', $currencyId);

        $rate = $sq->getOne();

        $ids = $this->getProductsWithNonClosedQuote();

        if (empty($ids)) {
            // we have no records, just ignore this module
            return true;
        }

        // setup SQL statement
        $query = sprintf("UPDATE %s SET %s = %s
        WHERE id IN (%s)
        AND currency_id = %s",
            $table,
            $column,
            $this->db->quote($rate),
            implode(",", $ids),
            $this->db->quoted($currencyId)
        );
        // execute
        $result = $this->db->query(
            $query,
            true,
            string_format(
                $GLOBALS['app_strings']['ERR_DB_QUERY'],
                array(__CLASS__, $query)
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
        $ids = $this->getProductsWithNonClosedQuote();

        if (empty($ids)) {
            // we have no records, just ignore this module
            return true;
        }

        // setup SQL statement
        $query = sprintf("UPDATE %s SET %s = %s / base_rate
            WHERE id IN (%s)
            AND currency_id = %s",
            $tableName,
            $usDollarColumn,
            $amountColumn,
            implode(",", $ids),
            $this->db->quoted($currencyId)
        );
        // execute
        $result = $this->db->query(
            $query,
            true,
            string_format(
                $GLOBALS['app_strings']['ERR_DB_QUERY'],
                array(__CLASS__, $query)
            )
        );
        return !empty($result);
    }

    protected function getProductsWithNonClosedQuote()
    {
        $product_bundles = BeanFactory::newBean('ProductBundles');

        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from($product_bundles);

        $product_bundles->load_relationship('quotes');
        $product_bundles->quotes->buildJoinSugarQuery($sq, array('joinType' => 'LEFT'));

        $quote = BeanFactory::newBean('Quotes');

        $sq->where()
            ->queryOr()
            ->isNull('quotes.quote_stage', $quote)
            ->notIn('quotes.quote_stage', $quote->closed_statuses, $quote);

        $results = $sq->execute();

        $db = $this->db;
        // we just need the array, so use array_map to pull it out of the results
        return array_map(function($a) use($db) {
                return $db->quoted($a['id']);
            }, $results);
    }

}
