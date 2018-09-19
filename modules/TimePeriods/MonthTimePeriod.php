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
 * Implements the annual representation of a time period
 * @api
 */
class MonthTimePeriod extends TimePeriod implements TimePeriodInterface {

    public function __construct() {
        //Override module_name to distinguish bean for BeanFactory
        $this->module_name = 'MonthTimePeriods';

        parent::__construct();

        //The time period type
        $this->type = TimePeriod::MONTH_TYPE;

        //Fiscal is 52-week based, chronological is year based
        $this->is_fiscal = false;

        $this->is_fiscal_year = false;

        //The number of periods in a year
        $this->periods_in_year = 12;

        //The next period modifier
        $this->next_date_modifier = '1 month';

        //The previous period modifier
        $this->previous_date_modifier = '-1 month';

        //The name template
        global $app_strings;
        $this->name_template = $app_strings['LBL_MONTH_TIMEPERIOD_FORMAT'];

        //The chart label
        $this->chart_label = "n/j";

        //The chart data interval modifier
        $this->chart_data_modifier = '+1 week';
    }


    /**
     * Returns the timeperiod name
     *
     * @param $count int value of the time period count (not used in MonthTimePeriod class)
     * @return string The formatted name of the timeperiod
     */
    public function getTimePeriodName($count, $timeperiod = null)
    {
        global $sugar_config;
        $timedate = TimeDate::getInstance();

        $start = $timedate->fromDbDate($this->start_date);
        if(isset($this->currentSettings['timeperiod_fiscal_year']) &&
            $this->currentSettings['timeperiod_fiscal_year'] == 'next_year') {
            $start->modify('+1 year');
        }

        return string_format($this->name_template, array($start->format('F Y')));
    }


    /**
     * Returns the formatted chart label data for the timeperiod
     *
     * @param $chartData Array of chart data values
     * @return formatted Array of chart data values where the labels are broken down by the timeperiod's increments
     */
    public function getChartLabels($chartData)
    {
        $weeks = array();
        $start = strtotime($this->start_date . " 00:00:00");
        $end = strtotime($this->end_date . " 23:59:59");
        $count = 0;
        $timedate = TimeDate::getInstance();

        while ($start <= $end) {
            //Find out how many days are left for this period
            $remainingDays = floor(abs($end - $start) / 86400);

            //Create the modifier for the timeperiod
            $modifier = $remainingDays > 6 ? '+6 day' : '+' . ceil($remainingDays) . ' day';

            $val = $chartData;
            $val['label'] = date($this->chart_label, $start) . '-' . date($this->chart_label, strtotime($modifier, $start));
            $val['start_timestamp'] = $start;
            $val['end_timestamp'] = $timedate->fromTimestamp($start)->modify($modifier)->setTime(23, 59, 59)->getTimestamp();

            //We internally use $count to store the corresponding data set for the week in the given timeperiod.
            //For a one month interval we will most likely get 4 weeks except for the case of non-leap year February
            $weeks[$count++] = $val;
            $start = strtotime($this->chart_data_modifier, $start);
        }

        return $weeks;
    }


    /**
     * Returns the key for the chart label data for the date closed value
     *
     * @param String The date_closed value in db date format
     * @return String value of the key to use to map to the chart labels
     */
    public function getChartLabelsKey($dateClosed)
    {
        $timedate = TimeDate::getInstance();
        $ts = $timedate->fromDbDate($dateClosed)->getTimestamp();

        $key = $this->id . ':keys';
        $keys = sugar_cache_retrieve($key);

        if(!empty($keys)) {
            foreach($keys as $timestamp=>$count) {
               if($ts < $timestamp) {
                   return $count;
               }
            }
            return count($keys);
        }

        $keys = array();
        $start = $timedate->fromDbDate($this->start_date);
        $end = $timedate->fromDbDate($this->end_date);
        $count = 0;

        while ($start <= $end) {
            $start->modify($this->chart_data_modifier);
            $tsKey = $start->getTimestamp();
            $keys[$tsKey] = $count;
            $count++;
        }

        sugar_cache_put($key, $keys);

        foreach($keys as $tsKey=>$count) {
            if($ts < $tsKey) {
                return $count;
            }
        }

        return count($keys);
    }
}
