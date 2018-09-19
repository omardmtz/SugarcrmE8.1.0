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
 * ProductsCurrencyRateUpdate
 *
 * A class for updating currency rates on specified database table columns
 * when a currency conversion rate is updated by the administrator.
 *
 */
class ProductsCurrencyRateUpdate extends CurrencyRateUpdateAbstract
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
        $this->addRateColumnDefinition('products','base_rate');
        // set usdollar field definitions
        $this->addUsDollarColumnDefinition('products','discount_amount','discount_amount_usdollar');
        $this->addUsDollarColumnDefinition('products','discount_price','discount_usdollar');
        $this->addUsDollarColumnDefinition('products','deal_calc','deal_calc_usdollar');
        $this->addUsDollarColumnDefinition('products','cost_price','cost_usdollar');
        $this->addUsDollarColumnDefinition('products','list_price','list_usdollar');
        $this->addUsDollarColumnDefinition('products','book_value','book_value_usdollar');
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
        $product = BeanFactory::newBean('Products');

        $sq = new SugarQuery();
        $sq->select(array('id'));
        $sq->from($product);

        // join in the product bundles table
        $product->load_relationships('product_bundles');
        // we use a left join here so we can get products that do not have quotes
        $product->product_bundles->buildJoinSugarQuery($sq, array('joinType' => 'LEFT'));

        // join in the quotes table off of Product Bundles
        $bundle = BeanFactory::newBean('ProductBundles');
        $bundle->load_relationship('quotes');
        $bundle->quotes->buildJoinSugarQuery($sq, array('joinType' => 'LEFT'));

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
