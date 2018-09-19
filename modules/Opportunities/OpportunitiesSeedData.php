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
 * OpportunitiesSeedData.php
 *
 * This is a class used for creating OpportunitiesSeedData.  We moved this code out from install/populateSeedData.php so
 * that we may better control and test creating default Opportunities.
 *
 */

class OpportunitiesSeedData {

    static private $_ranges;

    public static $pt_ids = array();

    public static $pc_ids = array();

    /**
     * @var $db DBManager
     */
    protected static $db;

    /**
     * populateSeedData
     *
     * This is a static function to create Opportunities.
     *
     * @static
     * @param $records Integer value indicating the number of Opportunities to create
     * @param $app_list_strings Array of application language strings
     * @param $accounts Array of Account instances to randomly build data against
     * @param $timeperiods Array of Timeperiods to create timeperiod seed data off of
     * @param $users Array of User instances to randomly build data against
     * @return array Array of Opportunities created
     */
    public static function populateSeedData($records, $app_list_strings, $accounts, $users)
    {
        if (empty($accounts) || empty($app_list_strings) || (!is_int($records) || $records < 1) || empty($users)) {
            return array();
        }

        $opp_config = Opportunity::getSettings(true);
        $usingRLIs = ($opp_config['opps_view_by'] === 'RevenueLineItems');

        self::$db = DBManagerFactory::getInstance();

        $opp_ids = array();
        $timedate = TimeDate::getInstance();

        // get the additional currencies from the table
        /* @var $currency Currency */
        $currency = SugarCurrency::getCurrencyByISO('EUR');

        $now = $GLOBALS['timedate']->nowDb();
        /* @var $opp Opportunity */
        $opp = BeanFactory::newBean('Opportunities');
        $oppFieldDefs = $opp->getFieldDefinitions();
        $oppSql = 'INSERT INTO '. $opp->table_name . ' ('. join(',', array_keys($opp->toArray(true))) . ') VALUES';
        $oppRows = array();
        $oppAccRows = array();
        $oppAccRow = array(
            'id' => '',
            'opportunity_id' => '',
            'account_id' => '',
            'date_modified' => self::$db->quoted($now),
            'deleted' => 0
        );
        $oppAccSql = 'INSERT INTO accounts_opportunities ('. join(',', array_keys($oppAccRow)) . ') VALUES';



        if ($usingRLIs) {
            // load up the product template_ids
            $sql = 'SELECT id, list_price, cost_price, discount_price, category_id, mft_part_num,
                      list_usdollar, cost_usdollar, discount_usdollar, tax_class, weight
                  FROM product_templates WHERE deleted = 0';

            $results = self::$db->query($sql);
            while ($row = self::$db->fetchByAssoc($results)) {
                static::$pt_ids[$row['id']] = $row;
            }
            $sql = 'SELECT id FROM product_categories WHERE deleted = 0';
            $results = self::$db->query($sql);
            while ($row = self::$db->fetchByAssoc($results)) {
                static::$pc_ids[] = $row['id'];
            }
        }

        /* @var $fw ForecastWorksheet */
        $fw = BeanFactory::newBean('ForecastWorksheets');
        $fw->parent_type = 'Opportunities';
        $fw->draft = 1;
        $fw->date_entered = $now;
        $fw->date_modified = $now;

        $fwFieldDefs = $fw->getFieldDefinitions();
        $fwRows = array();
        $fwSql = 'INSERT INTO ' . $fw->table_name . '(' . join(',', array_keys($fw->toArray(true))) . ') VALUES';

        while ($records-- > 0) {
            $key = array_rand($accounts);
            $account = $accounts[$key];

            //Create new opportunities
            $opp->team_id = $account->team_id;
            $opp->team_set_id = $account->team_set_id;

            $opp->assigned_user_id = $account->assigned_user_id;
            $opp->assigned_user_name = $account->assigned_user_name;

            $opp->created_by = $account->assigned_user_id;
            $opp->modified_user_id = $account->assigned_user_id;

            // figure out which one to use
            $base_rate = '1.0';
            $currency_id = '-99';

            if (!$usingRLIs) {
                $seed = rand(1, 15);
                if ($seed % 2 == 0) {
                    $currency_id = $currency->id;
                    $base_rate = $currency->conversion_rate;
                }
            }

            $opp->base_rate = $base_rate;
            $opp->currency_id = $currency_id;

            $opp->name = $account->name;
            $opp->lead_source = array_rand($app_list_strings['lead_source_dom']);
            $opp->sales_stage = array_rand($app_list_strings['sales_stage_dom']);
            $opp->sales_status = 'New';

            if (!$usingRLIs) {
                // If the deal is already done, make the date closed occur in the past.
                $opp->date_closed = ($opp->sales_stage == Opportunity::STAGE_CLOSED_WON || $opp->sales_stage == Opportunity::STAGE_CLOSED_LOST)
                    ? self::createPastDate()
                    : self::createDate();
                $opp->date_closed_timestamp = $timedate->fromDbDate($opp->date_closed)->getTimestamp();
            }
            $opp->opportunity_type = array_rand($app_list_strings['opportunity_type_dom']);
            $amount = rand(1000, 7500);
            $opp->amount = $amount;
            $opp->amount_usdollar = SugarMath::init($amount)->div($base_rate)->result();
            $opp->probability = $app_list_strings['sales_probability_dom'][$opp->sales_stage];

            //Setup forecast seed data
            $opp->best_case = $opp->amount;
            $opp->worst_case = $opp->amount;
            $opp->commit_stage = $opp->probability >= 70 ? 'include' : 'exclude';

            $opp->id = create_guid();
            $opp->new_with_id = true;

            $opp->date_entered = $now;
            $opp->date_modified = $now;

            // set the account on the opps, just for saving to the worksheet table
            $opp->account_id = $account->id;
            $opp->account_name = $account->name;

            $oppAccRows[] = '(' . join(',', array_merge($oppAccRow, array(
                'id' => self::$db->quoted(create_guid()),
                'account_id' => self::$db->quoted($account->id),
                'opportunity_id' => self::$db->quoted($opp->id)
            ))) . ')';

            $return = array();
            if ($usingRLIs) {
                $return = static::createRevenueLineItems($opp, rand(3, 5), $app_list_strings);
            }
            $values = array_merge($opp->toArray(true), $return);

            $sqlValues = array();
            foreach($values as $key => $value) {
                $sqlValues[] = self::$db->massageValue($value, $oppFieldDefs[$key]);
            }
            $oppRows[] = '(' . join(',', $sqlValues) . ')';

            if (!$usingRLIs) {
                $fw->modified_user_id = $account->assigned_user_id;
                $fw->created_by = $account->assigned_user_id;
                $fw->copyValues($fw->opportunityFieldMap, $opp);

                $fw->id = create_guid();
                $fw->parent_id = $opp->id;
                $fwValues = $fw->toArray(true);

                $sqlValues = array();
                foreach ($fwValues as $key => $value) {
                    $sqlValues[$key] = self::$db->massageValue($value, $fwFieldDefs[$key]);
                }
                $fwRows[] = '(' . join(',', $sqlValues) . ')';
            }

            $opp_ids[] = $opp->id;
        }

        self::insertAndCommit($oppSql, $oppRows);
        self::insertAndCommit($oppAccSql, $oppAccRows);
        if (!$usingRLIs) {
            self::insertAndCommit($fwSql, $fwRows);
        }

        return $opp_ids;
    }

    /**
     * @param Opportunity $opp
     * @param int $rlis_to_create
     * @param array $app_list_strings
     */
    private static function createRevenueLineItems(&$opp, $rlis_to_create, $app_list_strings) {
        $currency_id = $opp->currency_id;
        $base_rate = $opp->base_rate;

        $seed = rand(1, 15);
        if ($seed%2 == 0) {
            $currency = SugarCurrency::getCurrencyByISO('EUR');
            $currency_id = $currency->id;
            $base_rate = $currency->conversion_rate;
        }

        $rlis_created = 0;
        $opp_best_case = 0;
        $opp_worst_case = 0;
        $opp_amount = 0;
        $opp_units = 0;
        $opp->total_revenue_line_items = $rlis_to_create;
        $opp->closed_revenue_line_items = 0;
        $opp->included_revenue_line_items = 0;

        $closedWon = 0;
        $closedLost = 0;

        $timedate = TimeDate::getInstance();
        $now = $timedate->nowDb();

        /* @var $rli RevenueLineItem */
        $rli = BeanFactory::newBean('RevenueLineItems');
        $rliFieldDefs = $rli->getFieldDefinitions();
        $rliSql = array();
        $sqlRli = 'INSERT INTO '. $rli->table_name . '('. join(',', array_keys($rli->toArray(true))) . ') VALUES';


        /* @var $fw ForecastWorksheet */
        $fw = BeanFactory::newBean('ForecastWorksheets');
        $fw->parent_type = 'RevenueLineItems';
        $fw->draft = 1;
        $fw->date_entered = $now;
        $fw->date_modified = $now;
        $fw->modified_user_id = $opp->modified_user_id;
        $fw->created_by = $opp->created_by;
        $fwFieldDefs = $fw->getFieldDefinitions();
        $fwRows = array();
        $fwSql = 'INSERT INTO '. $fw->table_name . '('. join(',', array_keys($fw->toArray(true))) . ') VALUES';

        $opp_date_closed = '';
        $opp_date_closed_timestamp = 0;


        //SugarBean::enterOperation('saving_related');
        while($rlis_created < $rlis_to_create) {
            $amount = rand(1000, 7500);
            $rand_best_worst = rand(100, 900);
            $doPT = false;
            $quantity = rand(1, 100);
            $cost_price = $amount/2;
            $list_price = $amount;
            $discount_price = ($amount / $quantity);
            if ($rlis_created%2 === 0) {
                $doPT = true;
                $pt_id = array_rand(static::$pt_ids);
                $pt = static::$pt_ids[$pt_id];
                $cost_price = $pt['cost_price'];
                $list_price = $pt['list_price'];
                $discount_price = ($pt['discount_price'] / $quantity);
                $amount = $pt['discount_price'];
                $rand_best_worst = rand(100, $cost_price);
            }

            $rli->team_id = $opp->team_id;
            $rli->team_set_id = $opp->team_set_id;
            $rli->name = $opp->name;
            $rli->best_case = $amount+$rand_best_worst;
            $rli->likely_case = $amount;
            $rli->worst_case = $amount-$rand_best_worst;
            $rli->list_price = $list_price;
            $rli->discount_price = $discount_price;
            $rli->cost_price = $cost_price;
            $rli->quantity = $quantity;
            $rli->currency_id = $currency_id;
            $rli->base_rate = $base_rate;
            $rli->discount_amount = '0.00';
            $rli->book_value = '0.00';
            $rli->deal_calc = '0.00';
            $rli->sales_stage = array_rand($app_list_strings['sales_stage_dom']);
            $rli->probability = $app_list_strings['sales_probability_dom'][$rli->sales_stage];
            $isClosed = false;
            $isClosedLost = false;
            if ($rli->sales_stage == Opportunity::STAGE_CLOSED_WON || $rli->sales_stage == Opportunity::STAGE_CLOSED_LOST) {
                $isClosed = true;
                $rli->best_case = $rli->likely_case;
                $rli->worst_case = $rli->likely_case;
                if ($rli->sales_stage == Opportunity::STAGE_CLOSED_WON) {
                    $closedWon++;
                } else {
                    // closedLost shouldn't be added to the opp totals
                    $isClosedLost = true;
                    $closedLost++;
                }
                $opp->closed_revenue_line_items++;
            }
            $rli->commit_stage = $rli->probability >= 70 ? 'include' : 'exclude';
            $rli->date_closed = ($isClosed) ? self::createPastDate() : self::createDate();
            $rli->date_closed_timestamp = $timedate->fromDbDate($rli->date_closed)->getTimestamp();
            $rli->assigned_user_id = $opp->assigned_user_id;
            $rli->account_id = $opp->account_id;
            $rli->account_name = $opp->account_name;
            $rli->opportunity_id = $opp->id;
            $rli->lead_source = array_rand($app_list_strings['lead_source_dom']);
            $rli->product_type = $opp->opportunity_type;
            if ($rli->commit_stage === 'include') {
                $opp->included_revenue_line_items++;
            }

            // if this is an even number, assign a product template
            if ($doPT) {
                $rli->product_template_id = $pt_id;
                $rli->discount_amount = rand(100, $rli->cost_price);
                $rli->discount_rate_percent = (($rli->discount_amount/$rli->discount_price)*100);
                foreach($pt as $field => $value) {
                    if ($field != 'id') {
                        $rli->$field = $value;
                    }
                }
            } else {
                $rli->discount_amount = 0;
                $rli->discount_rate_percent = 0;
                // if this is not an even number, assign a product category only
                $rli->category_id = static::$pc_ids[array_rand(static::$pc_ids, 1)];
            }
            $rli->total_amount = (($rli->discount_price-$rli->discount_amount)*$rli->quantity);
            $rli->id = create_guid();
            $rli->date_entered = $now;
            $rli->date_modified = $now;
            $rli->modified_user_id = $opp->modified_user_id;
            $rli->created_by = $opp->created_by;
            //$rli->save();
            $values = $rli->toArray(true);

            $sqlValues = array();
            foreach($values as $key => $value) {
                $sqlValues[] = self::$db->massageValue($value, $rliFieldDefs[$key]);
            }

            $rliSql[] = '(' . join(',', $sqlValues) . ')';

            $fw->copyValues($fw->productFieldMap, $rli);

            $fw->id = create_guid();
            $fw->parent_id = $rli->id;
            $fwValues = $fw->toArray(true);

            $sqlValues = array();
            foreach($fwValues as $key => $value) {
                $sqlValues[$key] = self::$db->massageValue($value, $fwFieldDefs[$key]);
            }
            $fwRows[] = $sqlValues;

            $opp_units += $rli->quantity;

            if ($opp_date_closed_timestamp < $rli->date_closed_timestamp) {
                $opp_date_closed_timestamp = $rli->date_closed_timestamp;
                $opp_date_closed = $rli->date_closed;
            }

            if (!$isClosedLost) {
                $opp_amount = SugarMath::init($opp_amount)
                                ->add(SugarCurrency::convertWithRate($rli->likely_case, $base_rate, $opp->base_rate))
                                ->result();
                $opp_best_case = SugarMath::init($opp_best_case)
                                ->add(SugarCurrency::convertWithRate($rli->best_case, $base_rate, $opp->base_rate))
                                ->result();
                $opp_worst_case = SugarMath::init($opp_worst_case)
                                ->add(SugarCurrency::convertWithRate($rli->worst_case, $base_rate, $opp->base_rate))
                                ->result();
            }
            $rlis_created++;
        }

        if ($rlis_to_create > ($closedWon + $closedLost) || $rlis_to_create === 0) {
            // still in progress
            $opp->sales_status = Opportunity::STATUS_IN_PROGRESS;
        } else {
            // they are equal so if the total lost == total rlis then it's closed lost,
            // otherwise it's always closed won
            if ($closedLost == $rlis_to_create) {
                $opp->sales_status = Opportunity::STATUS_CLOSED_LOST;
            } else {
                $opp->sales_status = Opportunity::STATUS_CLOSED_WON;
            }
        }

        $opp->name .= ' - ' . $opp_units . ' Units';
        self::insertAndCommit($sqlRli, $rliSql);

        // process all the forecast worksheet rows since we have the correct opp name now
        $tRows = array();
        foreach($fwRows as $row) {
            $row['opportunity_name'] = self::$db->quoted($opp->name);
            $tRows[] = '(' . join(',', $row) . ')';
        }
        self::insertAndCommit($fwSql, $tRows);


        return array(
            'date_closed' => $opp_date_closed,
            'date_closed_timestamp' => $opp_date_closed_timestamp,
            'amount' => $opp_amount,
            'best_case' => $opp_best_case,
            'worst_case' => $opp_worst_case
        );
    }

    protected static function insertAndCommit($sql, array $rows, $table = '')
    {
        if (self::$db->dbType !== 'oci8') {
            self::$db->query($sql . ' ' . join(',', $rows));
            self::$db->commit();
        } else {
            $sql = substr($sql, 7);
            self::$db->query("INSERT ALL\n\t" . $sql . ' ' . join("\n\t" . $sql . ' ', $rows) .
                "\nSELECT * FROM dual\n");

            self::$db->commit();
        }
    }

    /**
     * @static creates range of probability for the months
     * @param int $total_months - total count of months
     * @return mixed
     */
    private static function getRanges($total_months = 12)
    {
        if (self::$_ranges === null) {
            self::$_ranges = array();
            for ($i = $total_months; $i >= 0; $i--) {
                // define priority for month,
                self::$_ranges[$total_months-$i] = ( $total_months-$i > 6 )
                    ? self::$_ranges[$total_months-$i] = pow(6, 2) + $i
                    :  self::$_ranges[$total_months-$i] = pow($i, 2) + 1;
                // increase probability for current quarters
                self::$_ranges[$total_months-$i] = $total_months-$i == 0 ? self::$_ranges[$total_months-$i]*2.5 : self::$_ranges[$total_months-$i];
                self::$_ranges[$total_months-$i] = $total_months-$i == 1 ? self::$_ranges[$total_months-$i]*2 : self::$_ranges[$total_months-$i];
                self::$_ranges[$total_months-$i] = $total_months-$i == 2 ? self::$_ranges[$total_months-$i]*1.5 : self::$_ranges[$total_months-$i];
            }
        }
        return self::$_ranges;
    }

    /**
     * @static return month delta as random value using range of probability, 0 - current month, 1 next/previos month...
     * @param int $total_months - total count of months
     * @return int
     */
    public static function getMonthDeltaFromRange($total_months = 12)
    {
        $ranges = self::getRanges($total_months);
        asort($ranges, SORT_NUMERIC);
        $x = mt_rand(1, array_sum($ranges));
        foreach ($ranges as $key => $y) {
            $x -= $y;
            if ($x <= 0) {
                break;
            }
        }
        return $key;
    }

    /**
     * @static generates date
     * @param null $monthDelta - offset from current date in months to create date, 0 - current month, 1 - next month
     * @return string
     */
    public static function createDate($monthDelta = null)
    {
        global $timedate;
        $monthDelta = $monthDelta === null ? self::getMonthDeltaFromRange() : $monthDelta;

        $now = $timedate->getNow(true);
        $now->modify("+$monthDelta month");
        // random day from now to end of month
        $now->setTime(0, 0, 0);
        $day = mt_rand($now->day, $now->days_in_month);
        return $timedate->asDbDate($now->get_day_begin($day));
    }

    /**
     * @static generate past date
     * @param null $monthDelta - offset from current date in months to create past date, 0 - current month, 1 - previous month
     * @return string
     */
    public static function createPastDate($monthDelta = null)
    {
        global $timedate;
        $monthDelta = $monthDelta === null ? self::getMonthDeltaFromRange() : $monthDelta;

        $now = $timedate->getNow(true);
        $now->modify("-$monthDelta month");

        if ($monthDelta == 0 && $now->day == 1) {
            $now->modify("-1 day");
            $day = $now->day;
        } else {
            // random day from start of month to now
            $tmpDay = ($now->day-1 != 0) ? $now->day-1 : 1;
            $day =  mt_rand(1, $tmpDay);
        }
        $now->setTime(0, 0, 0); // always default it to midnight
        return $timedate->asDbDate($now->get_day_begin($day));
    }
}

